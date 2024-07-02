<?php

$servername = "localhost";
$username = "root";
$password = "cmwfight75012";
$dbname = "kiwi";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données d'image depuis la table 'accueil'
    $sql = "SELECT * FROM products";
    $stmt = $pdo->query($sql);

    /*$sql1 = "SELECT * FROM products WHERE id = ?";
    $stmt1 = $pdo->query($sql1);
    $stmt1->execute(['?' => $id]);*/

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    /*if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
        header('Location: ../auth/connexion');
        exit();
    }*/





} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>