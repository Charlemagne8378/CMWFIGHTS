<?php
require_once '../../require/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Adresse_email'])) {
    $adresse_email = filter_input(INPUT_POST, 'Adresse_email', FILTER_SANITIZE_EMAIL);

    try {
        $stmt = $pdo->prepare('UPDATE UTILISATEUR SET type = "banni" WHERE adresse_email = :adresse_email');
        $stmt->bindParam(':adresse_email', $adresse_email);
        $stmt->execute();

        http_response_code(200);
    } catch (PDOException $e) {
        http_response_code(500);
        error_log('Erreur lors du ban de l\'utilisateur : ' . $e->getMessage());
    }
} else {
    http_response_code(400);
}

$pdo = null;
