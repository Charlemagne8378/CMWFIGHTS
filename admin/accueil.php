<?php
// Traitement de la mise à jour de la bannière
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['banner_image'])) {
    // Traitement de l'image de la bannière
    $banner_image = $_FILES['banner_image'];
    // Insérer le code pour sauvegarder l'image dans un emplacement sur le serveur
    // par exemple : move_uploaded_file($banner_image['tmp_name'], 'chemin/vers/dossier/' . $banner_image['name']);
    // Redirection vers la page d'administration après la mise à jour
    header("Location: adminacceuil.php");
    exit;
}

// Traitement de l'ajout/modification des actualités
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['news_title']) && isset($_POST['news_text'])) {
    // Traitement des données de la news
    $news_title = $_POST['news_title'];
    $news_text = $_POST['news_text'];
    // Traitement de l'image de la news
    if (isset($_FILES['news_image'])) {
        // Insérer le code pour sauvegarder l'image dans un emplacement sur le serveur
        // par exemple : move_uploaded_file($_FILES['news_image']['tmp_name'], 'chemin/vers/dossier/' . $_FILES['news_image']['name']);
    }
    // Insérer le code pour ajouter/modifier la news dans la base de données ou un fichier de données
    // Redirection vers la page d'administration après la mise à jour
    header("Location: adminacceuil.php");
    exit;
}

// Traitement de l'ajout/modification des sponsors
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sponsor_name']) && isset($_FILES['sponsor_logo'])) {
    // Traitement des données du sponsor
    $sponsor_name = $_POST['sponsor_name'];
    // Traitement du logo du sponsor
    $sponsor_logo = $_FILES['sponsor_logo'];
    // Insérer le code pour sauvegarder le logo dans un emplacement sur le serveur
    // par exemple : move_uploaded_file($sponsor_logo['tmp_name'], 'chemin/vers/dossier/' . $sponsor_logo['name']);
    // Insérer le code pour ajouter/modifier le sponsor dans la base de données ou un fichier de données
    // Redirection vers la page d'administration après la mise à jour
    header("Location: adminacceuil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <title>Admin Acceuil</title>
    <style>
        /* Styles pour la page d'administration */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100%;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <nav class="sidebar">
        <div class="text-center mb-3">
            <img src="../Images/cmwnoir.png" alt="Logo" style="width: 128px; height: 128px;">
        </div>
        <a class="nav-link" href="admin">
            <i class="bi bi-house-door"></i>
            <span class="ml-2 d-none d-sm-inline">Admin</span>
        </a>
        <a class="nav-link" href="utilisateurs">
            <i class="bi bi-person-lines-fill"></i>
            <span class="ml-2 d-none d-sm-inline">Utilisateurs</span>
        </a>
        <a class="nav-link" href="evenements">
            <i class="bi bi-calendar-event"></i>
            <span class="ml-2 d-none d-sm-inline">Événements</span>
        </a>
        <a class="nav-link" href="modifier_utilisateur">
            <i class="bi bi-pencil-square"></i>
            <span class="ml-2 d-none d-sm-inline">Modifier le compte</span>
        </a>
        <a class="nav-link" href="classement">
            <i class="bi bi-bar-chart"></i>
            <span class="ml-2 d-none d-sm-inline">Classement</span>
        </a>
        <a class="nav-link" href="combattants">
            <i class="bi bi-people"></i>
            <span class="ml-2 d-none d-sm-inline">Combattants</span>
        </a>
        <a class="nav-link" href="candidature">
            <i class="bi bi-file-earmark-text"></i>
            <span class="ml-2 d-none d-sm-inline">Candidature</span>
        </a>
        <a class="nav-link" href="billetterie">
            <i class="bi bi-ticket"></i>
            <span class="ml-2 d-none d-sm-inline">Billetterie</span>
        </a>
        <a class="nav-link" href="service_client">
            <i class="bi bi-telephone"></i>
            <span class="ml-2 d-none d-sm-inline">Service Client</span>
        </a>
        <a class="nav-link active" href="image">
            <i class="bi bi-image"></i>
            <span class="ml-2 d-none d-sm-inline">Image</span>
        </a>
        <a class="nav-link" href="newsletters">
            <i class="bi bi-envelope"></i>
            <span class="ml-2 d-none d-sm-inline">Newsletters</span>
        </a>
        <a class="nav-link" href="captcha">
            <i class="bi bi-shield-lock"></i>
            <span class="ml-2 d-none d-sm-inline">Captcha</span>
        </a>
        <a class="nav-link" href="accueil">
            <i class="bi bi-house-door"></i>
            <span class="ml-2 d-none d-sm-inline">Accueil</span>
        </a>
        <div class="account-box">
            <a href="../compte/settings">Paramètres</a>
            <a href="../auth/logout.php">Déconnexion</a>
        </div>
        <button class="btn btn-primary btn-block account-btn">
            Compte
        </button>
    </nav>
    <h1>Administration Acceuil</h1>

    <!-- Formulaire pour modifier la bannière -->
    <form action="adminacceuil.php" method="post" enctype="multipart/form-data">
        <label for="banner_image">Choisir une image pour la bannière :</label>
        <input type="file" name="banner_image" id="banner_image">
        <button type="submit">Mettre à jour la bannière</button>
    </form>

    <!-- Formulaire pour ajouter/modifier les news -->
    <form action="adminacceuil.php" method="post" enctype="multipart/form-data">
        <label for="news_title">Titre de la news :</label>
        <input type="text" name="news_title" id="news_title">
        <label for="news_image">Choisir une image pour la news :</label>
        <input type="file" name="news_image" id="news_image">
        <label for="news_text">Texte de la news :</label>
        <textarea name="news_text" id="news_text" cols="30" rows="10"></textarea>
        <button type="submit">Ajouter la news</button>
    </form>

    <!-- Formulaire pour ajouter/modifier les sponsors -->
    <form action="adminacceuil.php" method="post" enctype="multipart/form-data">
        <label for="sponsor_name">Nom du sponsor :</label>
        <input type="text" name="sponsor_name" id="sponsor_name">
        <label for="sponsor_logo">Choisir un logo pour le sponsor :</label>
        <input type="file" name="sponsor_logo" id="sponsor_logo">
        <button type="submit">Ajouter le sponsor</button>
    </form>

</body>
</html>
