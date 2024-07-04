<?php
require_once '../../require/config/config.php';
require_once '../../require/function/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $subject = $_POST['subject'];

    $stmt = $pdo->query("SELECT adresse_email FROM UTILISATEUR WHERE newsletter = 1");
    $recipients = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($recipients as $recipient) {
        sendEmail($recipient, $title, $subject);
    }
    $stmt = $pdo->prepare("UPDATE NEWSLETTERS SET statut = 'envoyé', date_envoi = NOW() WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header("Location: ../../admin/newsletters.php");
    exit();
}
?>
