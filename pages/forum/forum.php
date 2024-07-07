<?php
include '../../require/config/config.php';
session_start();
require_once '../../require/sidebar/sidebar_forum.php';
$query = $pdo->query("SELECT * FROM FORUM_CATEGORIES");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Forum</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/sidebar.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Forum</h1>
        <div class="list-group">
            <?php foreach ($categories as $category): ?>
                <a href="category.php?id=<?= $category['ID'] ?>" class="list-group-item list-group-item-action">
                    <h5 class="mb-1"><?= htmlspecialchars($category['NAME']) ?></h5>
                    <p class="mb-1"><?= htmlspecialchars($category['DESCRIPTION']) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
