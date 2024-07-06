<?php
session_start();

$messages_file = 'messages.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message) && isset($_SESSION['utilisateur_connecte'])) {
        $pseudo = $_SESSION['utilisateur_connecte']['pseudo'];
        $avatar_url = $_SESSION['utilisateur_connecte']['avatar_url'];
        $formatted_message = date('Y-m-d H:i:s') . '|' . $pseudo . '|' . $avatar_url . '|' . $message;
        file_put_contents($messages_file, $formatted_message . PHP_EOL, FILE_APPEND);
    }
}

header('Location: chat');
exit();
?>
