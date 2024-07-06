<?php
session_start();

$messages_file = 'messages.txt';
$mutes_file = 'mutes.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message) && isset($_SESSION['utilisateur_connecte'])) {
        $pseudo = $_SESSION['utilisateur_connecte']['pseudo'];

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

        if (!$is_muted) {
            $avatar_url = $_SESSION['utilisateur_connecte']['avatar_url'];
            $formatted_message = date('Y-m-d H:i:s') . '|' . $pseudo . '|' . $avatar_url . '|' . $message;
            file_put_contents($messages_file, $formatted_message . PHP_EOL, FILE_APPEND);
        }
    }
}

header('Location: chat');
exit();
?>
