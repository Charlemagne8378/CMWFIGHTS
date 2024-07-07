<?php
require_once 'require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $message = $_POST['message'];
    $date_creation = date('Y-m-d H:i:s');

    // Insertion du message dans la base de données avec date_creation
    $stmt = $pdo->prepare("INSERT INTO SERVICECLIENT (email_client, message, date_creation) VALUES (?, ?, ?)");
    $stmt->execute([$email, $message, $date_creation]);

    header('Location: success.php');
    exit;
}
?>
