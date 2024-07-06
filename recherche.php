<?php
require_once 'require/config/config.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $sql = "SELECT nom FROM COMBATTANT WHERE nom LIKE :query LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($results);
}
?>
