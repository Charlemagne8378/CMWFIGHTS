<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Adresse_email'])) {
    $email = $_POST['Adresse_email'];

    $stmt = $pdo->prepare('DELETE FROM Utilisateurs WHERE Adresse_email = :email');
    $stmt->bindParam(':email', $email);

    try {
        $stmt->execute();
        http_response_code(200);
    } catch (PDOException $e) {
        http_response_code(500);
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo 'Requête invalide.';
}

$pdo = null;
?>
