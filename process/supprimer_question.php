<?php
require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["question_id"])) {
    $question_id = (int) $_POST["question_id"];

    $sql = "DELETE FROM captcha WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt) {
        $stmt->execute([$question_id]);
        $message = "La question a été supprimée avec succès.";
        header('Location: ../admin/captcha');
        exit();
    } else {
        $error = "Erreur lors de la préparation de la requête : " . $pdo->error;
    }
} else {
    $error = "Les données du formulaire sont invalides ou manquantes.";
}
