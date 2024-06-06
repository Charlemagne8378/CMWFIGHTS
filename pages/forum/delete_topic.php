<?php
require_once '../require/config/config.php';

if (!$pdo) {
    die("Échec de la connexion à la base de données : " . print_r(error_get_last(), true));
}

$topic_id = $_GET["topic_id"];

$sql = "DELETE FROM POSTS WHERE sujet_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $topic_id);
$stmt->execute();

$sql = "DELETE FROM TOPICS WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $topic_id);

if ($stmt->execute()) {
    echo "Le sujet et tous les messages associés ont été supprimés avec succès.";
    header("Refresh:2; url=forum");
} else {
    echo "Erreur lors de la suppression du sujet : " . print_r($pdo->errorInfo(), true);
}

$stmt->closeCursor();
$pdo = null;
?>
