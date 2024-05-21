<?php
require_once '../config/config.php';
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
   
        .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                padding: 16px 0;
                box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.2);
                width: 280px;
                background-color: #f8f9fa;
                transition: all 0.3s ease;
                overflow-x: hidden;
                display: flex;
                flex-direction: column;
                z-index: 1000;
            }

            .sidebar .nav-link {
                color: #333;
                white-space: nowrap;
                margin-bottom: 0.5rem;
            }

            .sidebar .nav-link i {
                margin-right: 0px;
            }

            .sidebar.collapsed {
                width: 60px;
            }

            .sidebar.collapsed .nav-link {
                padding-left: 15px;
                padding-right: 15px;
                font-size: 0;
                text-align: center;
            }

            .sidebar.collapsed .nav-link i {
                margin-right: 0;
                font-size: 18px;
            }

            .sidebar .nav-link.active {
                color: #007bff;
                background-color: rgba(0, 123, 255, 0.1);
            }

            .main-content {
                transition: margin-left 0.3s ease;
                margin-left: 280px;
            }

            .main-content.collapsed {
                margin-left: 60px;
            }

            .account-box {
                position: absolute;
                bottom: 60px; 
                left: 0;
                width: 100%;
                background-color: #f8f9fa;
                box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
                display: none;
            }

            .account-box.show {
                display: block;
            }

            .account-box a {
                color: #333;
                display: block;
                padding: 0.5rem 1rem;
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .account-box a:hover {
                background-color: rgba(0, 123, 255, 0.1);
            }

            .account-btn {
                position: absolute;
                bottom: 10px;
                left: 0;
                width: 100%;
            }

            @media (max-width: 768px) {
                .sidebar {
                    width: 0;
                    padding: 0;
                }

                .sidebar.collapsed {
                    width: 0;
                }

                .main-content {
                    margin-left: 0;
                }
            }

            @media (min-width: 769px) {
                .toggle-sidebar {
                    display: none;
                }
            }
</style>
</head>
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

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.modifier-question').click(function() {
                    const questionId = $(this).data('id');
                    const question = $(this).data('question');
                    const answer = $(this).data('answer');

                    $('#modal_question_id').val(questionId);
                    $('#modal_question').val(question);
                    $('#modal_answer').val(answer);
                });

                $('.account-btn').click(function() {
                    $('.account-box').toggleClass('show');
                });
            });
        </script>
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
