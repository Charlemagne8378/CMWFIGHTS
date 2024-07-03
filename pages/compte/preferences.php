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
require_once '../../require/sidebar/sidebar_compte.php';
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
    <link rel="stylesheet" href="../../style/preference.css">
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
<script src="../../scripts/compte.js"></script>
</body>
</html>
