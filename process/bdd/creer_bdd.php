<?php
require_once '../../require/config/config.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../../auth/connexion');
    exit();
}

// Vérifier si la requête est POST et si 'newDatabaseName' est défini dans $_POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['newDatabaseName'])) {
    $newDatabase = $_POST['newDatabaseName'];

    try {
        $pdo = new PDO("mysql:host=$servername", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Utilisation de requête préparée pour éviter les injections SQL
        $sql = "CREATE DATABASE IF NOT EXISTS `$newDatabase`";
        $pdo->exec($sql);

        // Redirection après création réussie
        header('Location: ../../admin/bdd');
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la création de la base de données : " . $e->getMessage();
    }
} else {
    echo "Requête invalide."; // Afficher un message si la requête n'est pas correcte
}
?>
