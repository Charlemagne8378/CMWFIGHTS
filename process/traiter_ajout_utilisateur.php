<?php
require_once '../config/config.php';
session_start();

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['Type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $type = $_POST['type'];

    $stmt = $pdo->prepare('INSERT INTO Utilisateurs (Pseudo, Nom, Adresse_email, Mot_de_passe, Type) VALUES (:pseudo, :nom, :email, :mot_de_passe, :type)');
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe);
    $stmt->bindParam(':type', $type);

    try {
        $stmt->execute();
        header('Location: ../admin/utilisateurs');
        exit();
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    header('Location: ../admin/utilisateurs');
    exit();
}

$pdo = null;
?>
