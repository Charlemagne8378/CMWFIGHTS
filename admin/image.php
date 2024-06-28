<?php
require_once '../require/config/config.php';
require_once '../require/sidebar.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$pdo = null;
$error = "";
$success = "";

$targetDirectory = '../Images'; // Ensure $targetDirectory is defined here

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["image"])) {
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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_images"])) {
    $imagesToDelete = $_POST["images"] ?? [];
    foreach ($imagesToDelete as $image) {
        $filePath = $targetDirectory . DIRECTORY_SEPARATOR . $image;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    $success = "Les images sélectionnées ont été supprimées avec succès.";
}

// Lister les images existantes dans le répertoire
$images = array();
if (is_dir($targetDirectory)) {
    $dir = opendir($targetDirectory);
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..' && in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
            $images[] = $file;
        }
    }
    closedir($dir);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importation d'images</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
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

        <h3 class="mt-5 mb-4">Images Importées</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row">
                <?php foreach ($images as $image): ?>
                    <div class="col-md-3 col-sm-4 col-6 mb-4">
                        <div class="card">
                            <img src="<?php echo $targetDirectory . '/' . $image; ?>" alt="<?php echo htmlspecialchars($image); ?>" class="card-img-top" style="max-height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <p class="card-text text-center"><?php echo htmlspecialchars($image); ?></p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="images[]" value="<?php echo htmlspecialchars($image); ?>" id="image_<?php echo htmlspecialchars($image); ?>">
                                    <label class="form-check-label" for="image_<?php echo htmlspecialchars($image); ?>">Sélectionner</label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" name="delete_images" class="btn btn-danger mt-3">Supprimer les images sélectionnées</button>
        </form>

        <a href="admin" class="btn btn-secondary mt-3">Retour</a>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
