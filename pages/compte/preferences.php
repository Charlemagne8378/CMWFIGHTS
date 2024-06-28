<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '/var/www/html/require/config/config.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte'])) {
    header('Location: ../../auth/connexion');
    exit();
}
require_once '../../require/sidebar_compte.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préférences</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/sidebar.css">
    <style>
        :root {
            --btn-primary-bg: #ff7043;
            --btn-primary-hover-bg: #ff5722;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .preferences-container {
            margin-top: 20px;
        }

        .data-access-container, .info-comm-container {
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 20px;
            background-color: var(--sidebar-light-bg);
            margin-top: 20px;
        }

        .data-access-container h2, .info-comm-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .data-access-container p, .info-comm-container p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .data-access-container button, .info-comm-container button {
            background-color: var(--btn-primary-bg);
            border: none;
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .data-access-container button:hover, .info-comm-container button:hover {
            background-color: var(--btn-primary-hover-bg);
        }

        .info-comm-container label {
            display: flex;
            align-items: center;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .info-comm-container input[type="checkbox"] {
            margin-right: 10px;
        }

        .info-comm-container a {
            color: var(--btn-primary-bg);
            text-decoration: none;
        }

        .info-comm-container a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .d-flex {
                flex-direction: column;
                align-items: center;
            }

            .btn-group {
                flex-direction: column;
                margin-bottom: 10px;
            }
        }

        [data-bs-theme="dark"] .data-access-container, [data-bs-theme="dark"] .info-comm-container {
            background-color: var(--sidebar-dark-bg);
            color: var(--sidebar-dark-text);
        }

        [data-bs-theme="dark"] .info-comm-container a {
            color: var(--sidebar-dark-text);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="container px-4">
            <h1 class="my-4">Préférences</h1>
            <div class="d-flex justify-content-around mt-4">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-secondary" id="frLangBtn">
                        <i class="bi bi-flag-fill"></i> Français
                    </button>
                    <button type="button" class="btn btn-secondary" id="enLangBtn">
                        <i class="bi bi-flag-fill"></i> Anglais
                    </button>
                </div>

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-secondary" id="kgsWeightBtn">
                        <i class="bi bi-weight"></i> Kgs
                    </button>
                    <button type="button" class="btn btn-secondary" id="lbsWeightBtn">
                        <i class="bi bi-weight"></i> Lbs
                    </button>
                </div>

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-secondary" id="mHeightBtn">
                        <i class="bi bi-ruler"></i> Mètres
                    </button>
                    <button type="button" class="btn btn-secondary" id="inchHeightBtn">
                        <i class="bi bi-ruler"></i> Pouces
                    </button>
                </div>
            </div>

            <div class="info-comm-container mt-5">
                <h2>Mes informations et communications</h2>
                <label>
                    <input type="checkbox" name="offres_evenements">
                    J'autorise le service CMWFIGHT à me communiquer par email des offres ou des évènements liés au service et à ses partenaires.
                </label>
                <label>
                    <input type="checkbox" name="analyse_service">
                    J'autorise le service CMWFIGHT à utiliser mes informations à des fins d'amélioration du service.
                </label>
                <p>
                    <a href="#">Consulter les Conditions générales de vente</a>
                </p>
                <button type="submit">Enregistrer mes préférences</button>
            </div>

            <div class="data-access-container mt-5">
                <h2>Accès à mes données</h2>
                <p>
                    Le droit d'accès te permet de savoir si nous traitons des données te concernant et d'en obtenir une copie. 
                    Nous te ferons parvenir entre autres : les informations fournies sur ton profil.
                </p>
                <p>
                    Tes données sont conservées en tout temps lorsque ton compte est actif, et totalement supprimées 
                    suivant la suppression définitive de ton compte.
                </p>
                <button onclick="window.location.href='../../process/download_data'">Accéder à mes données</button>
            </div>
        </div>
    </div>
</body>
</html>
