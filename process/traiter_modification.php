<?php
require_once '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'Adresse_email', FILTER_VALIDATE_EMAIL);
    $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $mot_de_passe = filter_input(INPUT_POST, 'mot_de_passe', FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);

    if ($email && $pseudo && $nom && $type) {
        $stmt = $pdo->prepare("UPDATE Utilisateurs SET Pseudo = :pseudo, Nom = :nom, Type = :type, Mot_de_passe = IF(LENGTH(:mot_de_passe) > 0, :mot_de_passe, Mot_de_passe) WHERE Adresse_email = :email");
        $stmt->bindParam(":pseudo", $pseudo);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":mot_de_passe", password_hash($mot_de_passe, PASSWORD_DEFAULT));
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        header("Location: ../admin/admin");
        exit();
    } else {
        echo "Des informations sont manquantes ou invalides.";
    }
}

$pdo = null;
?>
