<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '/var/www/html/require/config/config.php';
session_start();
require_once '../../require/sidebar/sidebar_compte.php';

if (!isset($_SESSION['utilisateur_connecte'])) {
    header('Location: ../../auth/connexion');
    exit();
}

if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']['type'] === 'banni') {
    header('Location: ../banni');
    exit();
}

$is_moderator_or_admin = isset($_SESSION['utilisateur_connecte']) && in_array($_SESSION['utilisateur_connecte']['type'], ['moderateur', 'admin']);
$pseudo = $_SESSION['utilisateur_connecte']['pseudo'];
$mutes_file = 'mutes.txt';

// Vérifier si l'utilisateur est muté
$is_muted = false;
if (file_exists($mutes_file)) {
    $mutes = file($mutes_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($mutes as $mute) {
        list($muted_pseudo, $mute_until) = explode('|', $mute);
        if ($muted_pseudo === $pseudo && $mute_until > time()) {
            $is_muted = true;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5"> <!-- Rafraîchir toutes les 5 secondes -->
    <title>Chat en Direct</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/sidebar.css">
    <link rel="stylesheet" href="../style/chat.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Chat en Direct</h1>
        <div id="chat-container">
            <div id="messages" class="border rounded p-3 mb-3" style="height: 300px; overflow-y: scroll;">
                <?php
                // Chemin vers le fichier de messages
                $messages_file = 'messages.txt';

                // Vérifier si le fichier existe, sinon le créer
                if (!file_exists($messages_file)) {
                    file_put_contents($messages_file, '');
                }

                // Charger les messages depuis le fichier
                $messages = file($messages_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($messages as $index => $message) {
                    $parts = explode('|', $message);
                    if (count($parts) === 4) {
                        list($timestamp, $pseudo, $avatar_url, $content) = $parts;
                        echo '<div class="d-flex align-items-start mb-2">';
                        echo '<img src="' . htmlspecialchars($avatar_url) . '" class="rounded-circle mr-2" alt="avatar" style="width: 40px; height: 40px;">';
                        echo '<div class="flex-grow-1">';
                        echo '<strong>' . htmlspecialchars($pseudo) . '</strong> <small class="text-muted">' . htmlspecialchars($timestamp) . '</small><br>'; // Date et heure à côté du pseudo
                        echo '<span>' . htmlspecialchars($content) . '</span>';
                        echo '</div>';
                        if ($is_moderator_or_admin) {
                            echo '<div>';
                            echo '<button class="btn btn-danger btn-sm ml-2" onclick="deleteMessage(' . $index . ')">Supprimer</button>';
                            echo '<button class="btn btn-warning btn-sm ml-2" onclick="muteUser(\'' . htmlspecialchars($pseudo) . '\')">Mute</button>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="d-flex align-items-start mb-2">';
                        echo '<div>';
                        echo '<span>Message mal formé : ' . htmlspecialchars($message) . '</span>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <div class="input-group">
                <input type="text" id="message-input" class="form-control" placeholder="Tapez votre message..." <?php echo $is_muted ? 'disabled' : ''; ?>>
                <div class="input-group-append">
                    <button id="send-button" class="btn btn-primary" <?php echo $is_muted ? 'disabled' : ''; ?>>Envoyer</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../../scripts/chat.js"></script>
</body>
</html>
