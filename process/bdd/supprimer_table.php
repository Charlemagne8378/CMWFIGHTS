<?php
require_once '../../require/config/config.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../../auth/connexion');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['db'], $_POST['tableToDelete'])) {
    $db = $_POST['db'];
    $tableToDelete = $_POST['tableToDelete'];

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DROP TABLE `$tableToDelete`";
        $pdo->exec($sql);

        header("Location: ../../admin/tables.php?db=" . urlencode($db));
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de la table : " . $e->getMessage();
    }
} else {
    echo "Requête invalide.";
}
?>
