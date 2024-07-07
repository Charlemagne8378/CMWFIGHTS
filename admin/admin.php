<?php
require_once '../require/config/config.php';
session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] !== 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$menuItems = [
    'utilisateurs' => ['label' => 'Utilisateurs', 'icon' => 'bi-people', 'color' => 'btn-users'],
    'evenements' => ['label' => 'Événements', 'icon' => 'bi-calendar-event', 'color' => 'btn-events'],
    'combat' => ['label' => 'Combats', 'icon' => 'bi-shield', 'color' => 'btn-shield'],
    'modifier_utilisateur' => ['label' => 'Modifier le compte', 'icon' => 'bi-key', 'color' => 'btn-password'],
    'classement' => ['label' => 'Classement', 'icon' => 'bi-trophy', 'color' => 'btn-ranking'],
    'combattants' => ['label' => 'Combattant', 'icon' => 'bi-hand-thumbs-up', 'color' => 'btn-fighter'],
    'candidature' => ['label' => 'Candidature', 'icon' => 'bi-file-earmark-text', 'color' => 'btn-application'],
    'billetterie' => ['label' => 'Billetterie', 'icon' => 'bi-ticket-perforated', 'color' => 'btn-ticketing'],
    'service_client' => ['label' => 'Service Client', 'icon' => 'bi-headset', 'color' => 'btn-service-client'],
    'image' => ['label' => 'Image', 'icon' => 'bi-image', 'color' => 'btn-image'],
    'newsletters' => ['label' => 'Newsletters', 'icon' => 'bi-envelope', 'color' => 'btn-newsletters'],
    'captcha' => ['label' => 'Captcha', 'icon' => 'bi-robot', 'color' => 'btn-captcha'],
    'accueil' => ['label' => 'Accueil', 'icon' => 'bi-house', 'color' => 'btn-accueil'],
    'logs' => ['label' => 'Logs', 'icon' => 'bi-clipboard', 'color' => 'btn-logs'],
    'permissions' => ['label' => 'Permissions Utilisateurs', 'icon' => 'bi-shield-lock', 'color' => 'btn-permissions'],
    'bdd' => ['label' => 'Bases de Données', 'icon' => 'bi-database', 'color' => 'btn-databases'],
    'erreur' => ['label' => 'Error', 'icon' => 'bi-exclamation-triangle', 'color' => 'btn-error'],
];
?>

<!DOCTYPE html>
<html lang="fr" <?= isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark' ? 'data-bs-theme="dark"' : 'data-bs-theme="light"' ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Administration</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Page d'Administration</h2>
    <div class="btn-container">
        <?php foreach ($menuItems as $page => $item): ?>
            <div class="col-md-4">
                <a href="<?= htmlspecialchars($page) ?>" class="btn <?= htmlspecialchars($item['color']) ?> btn-block mb-2 btn-square">
                    <i class="bi <?= htmlspecialchars($item['icon']) ?> fa-3x"></i>
                    <span><?= htmlspecialchars($item['label']) ?></span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="logout-btn-container">
        <a href="/auth/logout.php" class="btn btn-logout btn-square">
            <i class="bi bi-box-arrow-right fa-3x"></i>
            <span>Se déconnecter</span>
        </a>
    </div>
</div>
<script src="../scripts/compte.js"></script>
</body>
</html>
