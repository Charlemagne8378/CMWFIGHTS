<?php
require_once '../require/config/config.php';

if (!$pdo) {
    die("Échec de la connexion à la base de données : " . print_r(error_get_last(), true));
}

$sujet_id = $_POST["sujet_id"];
$contenu = $_POST["contenu"];
$utilisateur_id = $_SESSION["utilisateur_id"];

$sql = "INSERT INTO POSTS (contenu, utilisateur_id, sujet_id) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $contenu);
$stmt->bindValue(2, $utilisateur_id);
$stmt->bindValue(3, $sujet_id);

if ($stmt->execute()) {
    echo "Le message a été envoyé avec succès.";
    header("Refresh:2; url=view_topic.php?topic_id=" . $sujet_id);
} else {
    echo "Erreur lors de l'envoi du message : " . print_r($pdo->errorInfo(), true);
}

$stmt->closeCursor();
$pdo = null;
?>
