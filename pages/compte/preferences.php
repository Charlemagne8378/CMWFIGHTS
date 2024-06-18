<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préférences</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<style>
    body.dark-mode {
        background-color: #212529;
        color: #f8f9fa;
    }

    body.light-mode {
        background-color: #f8f9fa;
        color: #212529;
    }

    .sidebar .nav-link.active {
        font-weight: bold;
        color: #0d6efd;
    }
</style>
<body>
    <div class="d-flex">
        <div class="sidebar bg-light p-3" style="width: 200px;">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="settings">
                        <i class="bi bi-gear"></i> Paramètres
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="preferences">
                        <i class="bi bi-slider"></i> Préférences
                    </a>
                </li>
                <!-- Ajoutez d'autres liens de navigation ici -->
            </ul>
        </div>
        <div class="container px-4">
            <h1 class="my-4">Préférences</h1>
            <div class="d-flex justify-content-around mt-4">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-secondary" id="darkModeBtn">
                        <i class="bi bi-moon"></i> Dark Mode
                    </button>
                    <button type="button" class="btn btn-secondary" id="lightModeBtn">
                        <i class="bi bi-sun"></i> Light Mode
                    </button>
                </div>

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
                <button type="button" class="btn btn-primary mt-3" onclick="window.location.href='../../process/download_data'">Télécharger les données utilisateur</button>
            </div>
        </div>
    </div>

    <script src="../script/script.js"></script>

</body>
</html>
