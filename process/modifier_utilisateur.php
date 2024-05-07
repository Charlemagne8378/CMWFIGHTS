<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'adresse_email', FILTER_VALIDATE_EMAIL);

    if ($email) {
        $stmt = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE adresse_email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {
?>