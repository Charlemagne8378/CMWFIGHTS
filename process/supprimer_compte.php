<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '/var/www/html/require/config/config.php';

session_start();

$user_id = $_SESSION['utilisateur_connecte']['id'];

try {
    $sql = "DELETE FROM UTILISATEUR WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        session_destroy();
        header('Location: ../../auth/confirmation_suppression.php');
        exit();
    } else {
        echo "Erreur lors de la suppression du compte.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
