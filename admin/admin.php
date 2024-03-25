<?php
require_once '../config/config.php';
session_start();

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Administration</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .btn-square {
            height: 100px;
            margin-bottom: 10px;
        }

        .btn-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            margin: 10px;
        }

        .btn-container .col-md-4 {
            flex: 0 0 33.33333%;
            max-width: 33.33333%;
            text-align: center;
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
.btn-accueil {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
    color: #fff;
}

body.dark-mode .btn-users,
body.dark-mode .btn-events,
body.dark-mode .btn-password,
body.dark-mode .btn-ranking,
body.dark-mode .btn-fighter,
body.dark-mode .btn-application,
body.dark-mode .btn-ticketing,
body.dark-mode .btn-service-client,
body.dark-mode .btn-image,
body.dark-mode .btn-logout,
body.dark-mode .btn-newsletters,
body.dark-mode .btn-captcha,
body.dark-mode .btn-accueil {
    background-color: #6c757d;
}

body.dark-mode .btn-users:hover,
body.dark-mode .btn-events:hover,
body.dark-mode .btn-password:hover,
body.dark-mode .btn-ranking:hover,
body.dark-mode .btn-fighter:hover,
body.dark-mode .btn-application:hover,
body.dark-mode .btn-ticketing:hover,
body.dark-mode .btn-service-client:hover,
body.dark-mode .btn-image:hover,
body.dark-mode .btn-logout:hover,
body.dark-mode .btn-newsletters:hover,
body.dark-mode .btn-captcha:hover,
body.dark-mode .btn-accueil:hover {
    background-color: #5a6268;
}


        .btn-users { background-color: #3498db; color: #fff; }
        .btn-events { background-color: #2ecc71; color: #fff; }
        .btn-password { background-color: #e74c3c; color: #fff; }
        .btn-ranking { background-color: #9b59b6; color: #fff; }
        .btn-fighter { background-color: #f39c12; color: #fff; }
        .btn-application { background-color: #c0392b; color: #fff; }
        .btn-ticketing { background-color: #1abc9c; color: #fff; }
        .btn-service-client { background-color: #7f8c8d; color: #fff; }
        .btn-image { background-color: #34495e; color: #fff; }
        .btn-logout { background-color: #95a5a6; color: #fff; }
        .btn-newsletters { background-color: #4CAF50; color: #fff; }
        .btn-captcha { background-color: #007BFF; color: #fff; }
        .btn-accueil { background-color: #FFC107; color: #fff; }

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
            .btn-accueil {
                font-size: 14px;
                margin-top: 5px;
            }
            body {
                min-height: auto;
            }
        }
        body.dark-mode {
            background-color: #343a40;
            color: #fff;
        }

        body.dark-mode .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Page d'Administration</h2>
    <div class="btn-container">
        <div class="col-md-4">
            <a href="utilisateurs" class="btn btn-users btn-block mb-2 btn-square">
                <i class="fas fa-users fa-3x"></i><br>
                Utilisateurs
            </a>
        </div>
        <div class="col-md-4">
            <a href="evenements" class="btn btn-events btn-block mb-2 btn-square">
                <i class="fas fa-calendar-alt fa-3x"></i><br>
                Événements
            </a>
        </div>
        <div class="col-md-4">
            <a href="modifier_utilisateur" class="btn btn-password btn-block mb-2 btn-square">
                <i class="fas fa-key fa-3x"></i><br>
                Modifier le compte
            </a>
        </div>
        <div class="col-md-4">
            <a href="classement" class="btn btn-ranking btn-block mb-2 btn-square">
                <i class="fas fa-trophy fa-3x"></i><br>
                Classement
            </a>
        </div>
        <div class="col-md-4">
            <a href="combattants" class="btn btn-fighter btn-block mb-2 btn-square">
                <i class="fas fa-fist-raised fa-3x"></i><br>
                Combattant
            </a>
        </div>
        <div class="col-md-4">
            <a href="back_candidature" class="btn btn-application btn-block mb-2 btn-square">
                <i class="fas fa-file-alt fa-3x"></i><br>
                Candidature
            </a>
        </div>
        <div class="col-md-4">
            <a href="billetterie" class="btn btn-ticketing btn-block mb-2 btn-square">
                <i class="fas fa-ticket-alt fa-3x"></i><br>
                Billetterie
            </a>
        </div>
        <div class="col-md-4">
            <a href="service_client" class="btn btn-service-client btn-block mb-2 btn-square">
                <i class="fas fa-headset fa-3x"></i><br>
                Service Client
            </a>
        </div>
        <div class="col-md-4">
            <a href="image" class="btn btn-image btn-block mb-2 btn-square">
                <i class="fas fa-image fa-3x"></i><br>
                Image
            </a>
        </div>
        <div class="col-md-4">
            <a href="newsletters" class="btn btn-newsletters btn-block mb-2 btn-square">
                <i class="fas fa-envelope fa-3x"></i><br>
                Newsletters
            </a>
        </div>
        <div class="col-md-4">
            <a href="captcha" class="btn btn-captcha btn-block mb-2 btn-square">
                <i class="fas fa-robot fa-3x"></i><br>
                Captcha
            </a>
        </div>
        <div class="col-md-4">
            <a href="accueil" class="btn btn-accueil btn-block mb-2 btn-square">
                <i class="fas fa-home fa-3x"></i><br>
                Accueil
            </a>
        </div>
    </div>
    <div class="logout-btn-container">
        <a href="/auth/logout.php" class="btn btn-logout btn-square">
            <i class="fas fa-sign-out-alt fa-3x"></i><br>
            Se déconnecter
        </a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    const userBtn = document.getElementById('userBtn');
    const eventBtn = document.getElementById('eventBtn');
    const logoutBtn = document.getElementById('logoutBtn');
    const passwordBtn = document.getElementById('passwordBtn');
    const rankingBtn = document.getElementById('rankingBtn');
    const fighterBtn = document.getElementById('fighterBtn');
    const applicationBtn = document.getElementById('applicationBtn');
    const ticketingBtn = document.getElementById('ticketingBtn');
    const serviceClientBtn = document.getElementById('serviceClientBtn');
    const imageBtn = document.getElementById('imageBtn');
</script>
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
