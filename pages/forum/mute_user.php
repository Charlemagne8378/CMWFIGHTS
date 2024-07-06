<?php
session_start();

if (!isset($_SESSION['utilisateur_connecte']) || !in_array($_SESSION['utilisateur_connecte']['type'], ['moderateur', 'admin'])) {
    header('Location: chat');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pseudo'])) {
    $pseudo = $_POST['pseudo'];
    $mutes_file = 'mutes.txt';

    // Ajouter l'utilisateur à la liste des mutes
    $mute_until = time() + (24 * 60 * 60); // Mute pendant 24 heures
    file_put_contents($mutes_file, $pseudo . '|' . $mute_until . PHP_EOL, FILE_APPEND);
}

header('Location: chat');
exit();
?>
