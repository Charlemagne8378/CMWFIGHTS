<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['Type'] != 'admin') {
    header('Location: connexion');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $type = $_POST['type'];

    $stmt = $conn->prepare('INSERT INTO Utilisateurs (Pseudo, Nom, Adresse_email, Mot_de_passe, Type) VALUES (:pseudo, :nom, :email, :mot_de_passe, :type)');
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe);
    $stmt->bindParam(':type', $type);

    try {
        $stmt->execute();
        header('Location: admin');
        exit();
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    header('Location: admin');
    exit();
}

$conn = null;
?>
