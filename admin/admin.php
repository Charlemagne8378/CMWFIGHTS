<?php
require_once '../require/config/config.php';
session_start();

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$menuItems = [
    'utilisateurs' => ['label' => 'Utilisateurs', 'icon' => 'fas fa-users', 'color' => 'btn-users'],
    'evenements' => ['label' => 'Événements', 'icon' => 'fas fa-calendar-alt', 'color' => 'btn-events'],
    'modifier_utilisateur' => ['label' => 'Modifier le compte', 'icon' => 'fas fa-key', 'color' => 'btn-password'],
    'classement' => ['label' => 'Classement', 'icon' => 'fas fa-trophy', 'color' => 'btn-ranking'],
    'combattants' => ['label' => 'Combattant', 'icon' => 'fas fa-fist-raised', 'color' => 'btn-fighter'],
    'back_candidature' => ['label' => 'Candidature', 'icon' => 'fas fa-file-alt', 'color' => 'btn-application'],
    'billetterie' => ['label' => 'Billetterie', 'icon' => 'fas fa-ticket-alt', 'color' => 'btn-ticketing'],
    'service_client' => ['label' => 'Service Client', 'icon' => 'fas fa-headset', 'color' => 'btn-service-client'],
    'image' => ['label' => 'Image', 'icon' => 'fas fa-image', 'color' => 'btn-image'],
    'newsletters' => ['label' => 'Newsletters', 'icon' => 'fas fa-envelope', 'color' => 'btn-newsletters'],
    'captcha' => ['label' => 'Captcha', 'icon' => 'fas fa-robot', 'color' => 'btn-captcha'],
    'accueil' => ['label' => 'Accueil', 'icon' => 'fas fa-home', 'color' => 'btn-accueil'],
    'logs' => ['label' => 'Logs', 'icon' => 'fas fa-clipboard', 'color' => 'btn-logs'],
    'permissions' => ['label' => 'Permissions Utilisateurs', 'icon' => 'fas fa-user-lock', 'color' => 'btn-permissions'],
    'bdd' => ['label' => 'Bases de Données', 'icon' => 'fas fa-database', 'color' => 'btn-databases'],
    'error' => ['label' => 'Error', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'btn-error'],
];

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Administration</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .btn-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 10px;
        }

        .btn-container .col-md-4 {
            flex: 0 0 33.33333%;
            max-width: 33.33333%;
            text-align: center;
            padding: 10px;
        }

        .logout-btn-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .btn-users,
        .btn-events,
        .btn-password,
        .btn-ranking,
        .btn-fighter,
        .btn-application,
        .btn-ticketing,
        .btn-service-client,
        .btn-image,
        .btn-logout,
        .btn-newsletters,
        .btn-captcha,
        .btn-accueil,
        .btn-logs,
        .btn-permissions,
        .btn-databases,
        .btn-error {
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            transition: box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .btn-users { background-color: #3498db; }
        .btn-events { background-color: #2ecc71; }
        .btn-password { background-color: #e74c3c; }
        .btn-ranking { background-color: #9b59b6; }
        .btn-fighter { background-color: #f39c12; }
        .btn-application { background-color: #c0392b; }
        .btn-ticketing { background-color: #1abc9c; }
        .btn-service-client { background-color: #7f8c8d; }
        .btn-image { background-color: #34495e; }
        .btn-logout { background-color: #95a5a6; }
        .btn-newsletters { background-color: #4CAF50; }
        .btn-captcha { background-color: #007BFF; }
        .btn-accueil { background-color: #FFC107; }
        .btn-logs { background-color: #8e44ad; }
        .btn-permissions { background-color: #e67e22; }
        .btn-databases { background-color: #d35400; }
        .btn-error { background-color: #e74c3c; }


        @media screen and (max-width: 770px) {
            .btn-users,
            .btn-events,
            .btn-password,
            .btn-ranking,
            .btn-fighter,
            .btn-application,
            .btn-ticketing,
            .btn-service-client,
            .btn-image,
            .btn-logout,
            .btn-newsletters,
            .btn-captcha,
            .btn-accueil,
            .btn-logs,
            .btn-permissions,
            .btn-databases,
            .btn-error {
                font-size: 14px;
                margin-top: 5px;
            }

            body {
                min-height: auto;
            }
        }
    </style>
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const darkModeBtn = document.getElementById('darkModeBtn');
            const lightModeBtn = document.getElementById('lightModeBtn');

            darkModeBtn.addEventListener('click', () => {
                document.body.classList.add('dark-mode');
                document.body.classList.remove('light-mode');
                localStorage.setItem('theme', 'dark');
            });

            lightModeBtn.addEventListener('click', () => {
                document.body.classList.add('light-mode');
                document.body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            });

            const storedTheme = localStorage.getItem('theme');
            if (storedTheme) {
                if (storedTheme === 'dark') {
                    darkModeBtn.click();
                } else {
                    lightModeBtn.click();
                }
            }
        });
    </script>
</body>

</html>
