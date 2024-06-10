<?php
require_once '../require/config/config.php';
session_start();

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

        .btn-users:hover,
        .btn-events:hover,
        .btn-password:hover,
        .btn-ranking:hover,
        .btn-fighter:hover,
        .btn-application:hover,
        .btn-ticketing:hover,
        .btn-service-client:hover,
        .btn-image:hover,
        .btn-logout:hover,
        .btn-newsletters:hover,
        .btn-captcha:hover,
        .btn-accueil:hover,
        .btn-logs:hover,
        .btn-permissions:hover,
        .btn-databases:hover,
        .btn-error:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

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
            <div class="col-md-4">
                <a href="utilisateurs" class="btn btn-users btn-block mb-2 btn-square">
                    <i class="fas fa-users fa-3x"></i>
                    <span>Utilisateurs</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="evenements" class="btn btn-events btn-block mb-2 btn-square">
                    <i class="fas fa-calendar-alt fa-3x"></i>
                    <span>Événements</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="modifier_utilisateur" class="btn btn-password btn-block mb-2 btn-square">
                    <i class="fas fa-key fa-3x"></i>
                    <span>Modifier le compte</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="classement" class="btn btn-ranking btn-block mb-2 btn-square">
                    <i class="fas fa-trophy fa-3x"></i>
                    <span>Classement</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="combattants" class="btn btn-fighter btn-block mb-2 btn-square">
                    <i class="fas fa-fist-raised fa-3x"></i>
                    <span>Combattant</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="back_candidature" class="btn btn-application btn-block mb-2 btn-square">
                    <i class="fas fa-file-alt fa-3x"></i>
                    <span>Candidature</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="billetterie" class="btn btn-ticketing btn-block mb-2 btn-square">
                    <i class="fas fa-ticket-alt fa-3x"></i>
                    <span>Billetterie</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="service_client" class="btn btn-service-client btn-block mb-2 btn-square">
                    <i class="fas fa-headset fa-3x"></i>
                    <span>Service Client</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="image" class="btn btn-image btn-block mb-2 btn-square">
                    <i class="fas fa-image fa-3x"></i>
                    <span>Image</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="newsletters" class="btn btn-newsletters btn-block mb-2 btn-square">
                    <i class="fas fa-envelope fa-3x"></i>
                    <span>Newsletters</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="captcha" class="btn btn-captcha btn-block mb-2 btn-square">
                    <i class="fas fa-robot fa-3x"></i>
                    <span>Captcha</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="accueil" class="btn btn-accueil btn-block mb-2 btn-square">
                    <i class="fas fa-home fa-3x"></i>
                    <span>Accueil</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="logs" class="btn btn-logs btn-block mb-2 btn-square">
                    <i class="fas fa-clipboard fa-3x"></i>
                    <span>Logs</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="permissions" class="btn btn-permissions btn-block mb-2 btn-square">
                    <i class="fas fa-user-lock fa-3x"></i>
                    <span>Permissions Utilisateurs</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="bdd" class="btn btn-databases btn-block mb-2 btn-square">
                    <i class="fas fa-database fa-3x"></i>
                    <span>Bases de Données</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="error" class="btn btn-error btn-block mb-2 btn-square">
                    <i class="fas fa-exclamation-triangle fa-3x"></i>
                    <span>Error</span>
                </a>
            </div>
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
