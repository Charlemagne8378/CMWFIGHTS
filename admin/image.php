<?php
require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['Type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$pdo = null;
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["image"])) {
    $targetDirectory = '../Images';
    $originalFileName = basename($_FILES["image"]["name"]);
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($fileExtension), $allowedTypes)) {
        $error = "Seules les images sont autorisées.";
    } else if ($_FILES["image"]["size"] > 10000000) {
        $error = "La taille de l'image dépasse la limite.";    
    } else {
        $fileName = isset($_POST["filename"]) ? basename($_POST["filename"]) : "default";
        $targetFile = $targetDirectory . DIRECTORY_SEPARATOR . $fileName . '.' . $fileExtension;

        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        if ($_FILES["image"]["error"] > 0) {
            $error = "Erreur lors du téléchargement de l'image : " . $_FILES["image"]["error"];
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $success = "L'image a été importée avec succès.";
            } else {
                $error = "Erreur lors de l'importation de l'image. Vérifiez les permissions du répertoire cible.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importation d'images</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif; 
        background-image: url('../Images/wallpaper_admin.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        margin: 0;
        padding: 20px;
        text-align: center;
        color: #000;
        position: relative;
        height: 100vh;
        overflow: hidden;
    }

    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('../Images/wallpaper_admin.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        filter: blur(8px);
        z-index: -1;
    }

    h1 {
        color: #fff;
    }

    form {
        max-width: 400px;
        margin: 20px auto;
        background-color: rgba(255, 255, 255, 1);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 1;
    }

    label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }

    input[type="file"],
    input[type="text"],
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        margin-bottom: 20px;
    }

    input[type="submit"] {
        background-color: #3498db;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="submit"]:hover {
        background-color: #258cd1;
    }

    p.success,
    p.error {
        font-weight: bold;
        color: #fff;
    }
</style>
</head>

<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Importation d'images</h1>

        <?php if ($error): ?>
            <p class='error'><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class='success'><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
                <label for="image" class="form-label">Sélectionnez une image à importer :</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*" required onchange="updateFilename()">
            </div>

            <div class="mb-3">
                <label for="filename" class="form-label">Nom du fichier :</label>
                <input type="text" class="form-control" name="filename" id="filename" required>
            </div>

            <button type="submit" class="btn btn-primary">Importer</button>
        </form>

        <a href="admin" class="btn btn-secondary mt-3">Retour</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        function updateFilename() {
            var fileInput = document.getElementById('image');
            var filenameInput = document.getElementById('filename');
            var fileNameWithoutExtension = fileInput.files[0].name.replace(/\.[^/.]+$/, '');
            filenameInput.value = fileNameWithoutExtension;
        }
    </script>
</body>

</html>
