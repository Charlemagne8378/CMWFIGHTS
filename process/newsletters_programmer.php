<?php
require_once '../require/config/config.php';
require_once '../require/function/function.php';

$stmt = $pdo->query("SELECT * FROM NEWSLETTER WHERE brouillon = 0 AND programmer IS NOT NULL AND programmer <= NOW()");
$newsletters_programmees = $stmt->fetchAll();

foreach ($newsletters_programmees as $newsletter) {
    $users = $pdo->query("SELECT * FROM UTILISATEUR WHERE newsletter = 1")->fetchAll();

    foreach ($users as $user) {
        sendEmail($user['adresse_email'], $newsletter['sujet'], $newsletter['contenu']);
    }

    $stmt = $pdo->prepare("UPDATE NEWSLETTER SET programmer = NULL, envoye_a = :envoye_a WHERE id = :id");
    $envoye_a = implode(',', array_column($users, 'id'));
    $stmt->bindParam(':envoye_a', $envoye_a);
    $stmt->bindParam(':id', $newsletter['id']);
    $stmt->execute();
}
?>
