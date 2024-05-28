<?php
require_once '../require/config/config.php';

if (!$pdo) {
    die("Échec de la connexion à la base de données : " . print_r(error_get_last(), true));
}

$post_id = $_GET["post_id"];

$sql = "SELECT * FROM POSTS WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $post_id);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "DELETE FROM POSTS WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $post_id);

if ($stmt->execute()) {
    echo "Le message a été supprimé avec succès.";
    header("Refresh:2; url=view_topic.php?topic_id=" . $post["sujet_id"]);
} else {
    echo "Erreur lors de la suppression du message : " . print_r($pdo->errorInfo(), true);
}

$stmt->closeCursor();
$pdo = null;
?>
