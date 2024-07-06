<?php
session_start();

if (!isset($_SESSION['utilisateur_connecte']) || !in_array($_SESSION['utilisateur_connecte']['type'], ['moderateur', 'admin'])) {
    header('Location: chat');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $index = (int) $_POST['index'];
    $messages_file = 'messages.txt';

    if (file_exists($messages_file)) {
        $messages = file($messages_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (isset($messages[$index])) {
            unset($messages[$index]);
            file_put_contents($messages_file, implode(PHP_EOL, $messages) . PHP_EOL);
        }
    }
}

header('Location: chat');
exit();
?>
