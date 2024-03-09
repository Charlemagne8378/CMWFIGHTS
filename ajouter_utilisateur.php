<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $mot_de_passe = password_hash(filter_input(INPUT_POST, 'mot_de_passe'), PASSWORD_DEFAULT);

    if ($email && $pseudo && $nom && $mot_de_passe) {
        $stmt = $conn->prepare("INSERT INTO Utilisateurs (Pseudo, Nom, Adresse_email, Mot_de_passe) VALUES (:pseudo, :nom, :email, :mot_de_passe)");
        $stmt->bindParam(":pseudo", $pseudo);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":mot_de_passe", $mot_de_passe);
        $stmt->execute();

        header("Location: admin");
        exit();
    } else {
        echo "Des informations sont manquantes ou invalides.";
    }
}

$conn = null;
?>
