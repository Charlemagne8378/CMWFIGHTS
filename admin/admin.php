<?php
require_once '../require/config/config.php';
session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] !== 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$menuItems = [
    'utilisateurs' => ['label' => 'Utilisateurs', 'icon' => 'fas fa-users', 'color' => 'btn-users'],
    'evenements' => ['label' => 'Événements', 'icon' => 'fas fa-calendar-alt', 'color' => 'btn-events'],
    'combat' => ['label' => 'Combats', 'icon' => 'fas fa-shield-alt', 'color' => 'btn-shield'],
    'modifier_utilisateur' => ['label' => 'Modifier le compte', 'icon' => 'fas fa-key', 'color' => 'btn-password'],
    'classement' => ['label' => 'Classement', 'icon' => 'fas fa-trophy', 'color' => 'btn-ranking'],
    'combattants' => ['label' => 'Combattant', 'icon' => 'fas fa-fist-raised', 'color' => 'btn-fighter'],
    'candidature' => ['label' => 'Candidature', 'icon' => 'fas fa-file-alt', 'color' => 'btn-application'],
    'billetterie' => ['label' => 'Billetterie', 'icon' => 'fas fa-ticket-alt', 'color' => 'btn-ticketing'],
    'service_client' => ['label' => 'Service Client', 'icon' => 'fas fa-headset', 'color' => 'btn-service-client'],
    'image' => ['label' => 'Image', 'icon' => 'fas fa-image', 'color' => 'btn-image'],
    'newsletters' => ['label' => 'Newsletters', 'icon' => 'fas fa-envelope', 'color' => 'btn-newsletters'],
    'captcha' => ['label' => 'Captcha', 'icon' => 'fas fa-robot', 'color' => 'btn-captcha'],
    'accueil' => ['label' => 'Accueil', 'icon' => 'fas fa-home', 'color' => 'btn-accueil'],
    'logs' => ['label' => 'Logs', 'icon' => 'fas fa-clipboard', 'color' => 'btn-logs'],
    'permissions' => ['label' => 'Permissions Utilisateurs', 'icon' => 'fas fa-user-lock', 'color' => 'btn-permissions'],
    'bdd' => ['label' => 'Bases de Données', 'icon' => 'fas fa-database', 'color' => 'btn-databases'],
    'erreur' => ['label' => 'Error', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'btn-error'],
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Page d'Administration</h2>
    <div class="btn-container">
        <?php foreach ($menuItems as $page => $item): ?>
            <div class="col-md-4">
                <a href="<?= htmlspecialchars($page) ?>" class="btn <?= htmlspecialchars($item['color']) ?> btn-block mb-2 btn-square">
                    <i class="<?= htmlspecialchars($item['icon']) ?> fa-3x"></i>
                    <span><?= htmlspecialchars($item['label']) ?></span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="logout-btn-container">
        <a href="/auth/logout.php" class="btn btn-logout btn-square">
            <i class="fas fa-sign-out-alt fa-3x"></i>
            <span>Se déconnecter</span>
        </a>
    </div>
</div>
<script src="../scripts/compte.js"></script>
</body>
</html>
