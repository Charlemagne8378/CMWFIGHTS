<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '/var/www/html/require/config/config.php';

header("Permissions-Policy: camera=(), microphone=(), geolocation=()"); // Adjust the features as needed

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
    <title>Dessin</title>
    <link rel="icon" type="image/png" href="../../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/sidebar.css">
    <style>
        .canvas-container {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
        .controls-container {
            display: none;
            text-align: center;
            margin-top: 10px;
        }
        #canvas {
            border: 1px solid #000;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="mt-5">Dessin</h1>
                <button id="startDrawingBtn" class="btn btn-primary mt-4">Commencer à dessiner</button>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 canvas-container">
                <canvas id="canvas" width="800" height="600"></canvas>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 controls-container">
                <div class="form-group">
                    <label for="colorPicker">Couleur :</label>
                    <input type="color" id="colorPicker" class="form-control">
                </div>
                <div class="form-group">
                    <label for="brushSize">Taille du pinceau :</label>
                    <input type="range" id="brushSize" min="1" max="50" value="5" class="form-control">
                </div>
                <button id="clearBtn" class="btn btn-secondary">Effacer</button>
                <button id="downloadBtn" class="btn btn-success">Télécharger</button>
                <button id="saveBtn" class="btn btn-info">Enregistrer</button>
            </div>
        </div>
    </div>
<script src="../../js/dessin.js"></script>
</body>
</html>
