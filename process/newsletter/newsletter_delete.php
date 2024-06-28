<?php
require_once '../../require/config/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM NEWSLETTERS WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: ../../admin/newsletters.php");
    exit();
}
?>
