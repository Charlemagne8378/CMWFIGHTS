<?php
require_once 'require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les données du formulaire
$email = $_POST['email'];
$message = $_POST['message'];

// Insérer le message dans la base de données
$stmt = $pdo->prepare("INSERT INTO SERVICECLIENT (email_client, message) VALUES (?, ?)");
$stmt->execute([$email, $message]);

echo "Message envoyé avec succès.";
?>
