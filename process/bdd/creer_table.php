<?php
require_once '../../require/config/config.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../../auth/connexion');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['db'], $_POST['newTableName'])) {
    $db = $_POST['db'];
    $newTableName = $_POST['newTableName'];

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Utilisation de requête préparée pour éviter les injections SQL
        $sql = "CREATE TABLE `$newTableName` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY
        )";
        $pdo->exec($sql);

        header("Location: ../../admin/tables.php?db=" . urlencode($db));
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la création de la table : " . $e->getMessage();
    }
} else {
    echo "Requête invalide.";
}
?>
