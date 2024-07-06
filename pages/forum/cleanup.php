<?php
// Chemin vers le fichier de messages
$messages_file = 'messages.txt';

$messages = file($messages_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$current_time = time();
$new_messages = [];

foreach ($messages as $message) {
    $timestamp = strtotime(explode(' - ', $message)[0]);
    if ($current_time - $timestamp <= 30 * 24 * 60 * 60) { // 30 jours en secondes
        $new_messages[] = $message;
    }
}

file_put_contents($messages_file, implode(PHP_EOL, $new_messages) . PHP_EOL);
?>
