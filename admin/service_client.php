<?php
require_once '../require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($pdo)) {
    die("Erreur: La connexion à la base de données n'a pas été initialisée.");
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['message'])) {
        $email = $_POST['email'];
        $message = $_POST['message'];
        $stmt = $pdo->prepare("INSERT INTO MESSAGES (email, message) VALUES (:email, :message)");
        $stmt->execute(['email' => $email, 'message' => $message]);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['response']) && isset($_POST['id'])) {
        $response = $_POST['response'];
        $id = $_POST['id'];
        
      
        $stmt = $pdo->prepare("SELECT email FROM MESSAGES WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userEmail = $row['email'];

        
        $stmt = $pdo->prepare("UPDATE MESSAGES SET response = :response WHERE id = :id");
        $stmt->execute(['response' => $response, 'id' => $id]);
        
        
        $to = $userEmail;
        $subject = "Réponse à votre message";
        $headers = "From: support@cmwfight.fr"; 
        $messageBody = "Bonjour,\n\nNous avons répondu à votre message :\n\n" . $response . "\n\nMerci.";
        
        if (mail($to, $subject, $messageBody, $headers)) {
            echo "Réponse envoyée et email envoyé avec succès.";
        } else {
            echo "Réponse envoyée mais échec de l'envoi de l'email.";
        }
    }

    $stmt = $pdo->query("SELECT id, email, message, response FROM MESSAGES");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages Reçus</title>
</head>
<body>
    <h1>Messages Reçus</h1>
    <?php
    if (count($messages) > 0) {
        foreach ($messages as $row) {
            echo "<div class='message'>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($row["email"]) . "</p>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($row["message"]) . "</p>";
            if ($row["response"]) {
                echo "<p><strong>Réponse:</strong> " . htmlspecialchars($row["response"]) . "</p>";
            } else {
                echo "<form method='post' action='service_client.php'>";
                echo "<input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>";
                echo "<textarea name='response' required></textarea><br>";
                echo "<input type='submit' value='Répondre'>";
                echo "</form>";
            }
            echo "</div>";
        }
    } else {
        echo "Aucun message reçu.";
    }
    ?>
</body>
</html>
