<?php
require_once '../../require/config/config.php';
require_once '../../require/sidebar_newsletters.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM NEWSLETTERS WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $newsletter = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir Newsletter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../style/sidebar.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Voir Newsletter</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($newsletter['titre']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($newsletter['sujet'])) ?></p>
            <p class="card-text">Date d'envoi : <?= htmlspecialchars($newsletter['date_envoi']) ?></p>
        </div>
    </div>
</div>
</body>
</html>
