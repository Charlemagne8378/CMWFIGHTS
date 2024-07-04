<?php
require_once '../../require/config/config.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../../auth/connexion');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['databaseToDelete'])) {
    $database = $_POST['databaseToDelete'];

    try {
        $pdo = new PDO("mysql:host=$servername", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DROP DATABASE IF EXISTS `$database`";
        $pdo->exec($sql);
        header('Location: ../../admin/bdd');
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de la base de données : " . $e->getMessage();
    }
} else {
    echo "Requête invalide.";
}
?>
