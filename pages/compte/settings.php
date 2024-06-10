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

$user_to_edit = $_SESSION['utilisateur_connecte'];
$current_page = 'settings';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $user_to_edit['pseudo'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $genre = $_POST['genre'];
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatar_url = saveAvatar($_FILES['avatar']);
    } else {
        $avatar_url = $user_to_edit['avatar_url'];
    }

    if (!empty($new_password) && $new_password !== $confirm_password) {
        $error = "Les nouveaux mots de passe ne correspondent pas.";
    } elseif (!empty($new_password) && !password_verify($old_password, $user_to_edit['Mot_de_passe'])) {
        $error = "L'ancien mot de passe est incorrect.";
    } else {
        $query = "UPDATE UTILISATEUR SET nom = :nom, adresse_email = :email, genre = :genre, newsletter = :newsletter, avatar_url = :avatar_url WHERE pseudo = :pseudo";
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':newsletter', $newsletter);
        $stmt->bindParam(':avatar_url', $avatar_url);
        $stmt->bindParam(':pseudo', $pseudo);

        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query .= ", Mot_de_passe = :new_password";
            $stmt->bindParam(':new_password', $hashed_password);
        }

        $stmt->execute();

        $_SESSION['utilisateur_connecte']['nom'] = $nom;
        $_SESSION['utilisateur_connecte']['adresse_email'] = $email;
        $_SESSION['utilisateur_connecte']['genre'] = $genre;
        $_SESSION['utilisateur_connecte']['newsletter'] = $newsletter;
        $_SESSION['utilisateur_connecte']['avatar_url'] = $avatar_url;

        header('Location: settings');
        exit();
    }
}

function saveAvatar($file) {
    $target_dir = "/var/www/html/Images/avatar/";
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verify if the image file is an actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return "/Images/avatar/" . basename($file["name"]);
        } else {
            return '';
        }
    } else {
        return '';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres du profil</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        .sidebar {
            width: 250px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
        }
        .sidebar a.active {
            background-color: #212529;
        }
        .account-btn {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
        }
        .avatar-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
            position: relative;
        }
        .avatar-button {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 50%;
            padding: 10px;
        }
    </style>
</head>
<body>
    <nav class="sidebar">
        <div class="text-center mb-3">
            <img src="../Images/cmwnoir.png" alt="Logo" class="img-fluid">
        </div>
        <a class="nav-link active" href="settings">
            <i class="bi bi-gear"></i>
            <span class="ml-2 d-none d-sm-inline">Paramètres</span>
        </a>
        <a class="nav-link" href="preferences">
            <i class="bi bi-sliders"></i>
            <span class="ml-2 d-none d-sm-inline">Préférences</span>
        </a>
        <div class="account-box">
            <a href="../compte/settings">Paramètres</a>
            <a href="../auth/logout.php">Déconnexion</a>
        </div>
        <button class="btn btn-primary btn-block account-btn">
            Compte
        </button>
    </nav>

    <div class="main-content">
        <div class="container">
            <h1 class="my-4 text-center">Paramètres du profil</h1>
            <div class="text-center mb-4">
                <?php if (!empty($user_to_edit['avatar_url'])): ?>
                    <div class="position-relative d-inline-block">
                        <img src="<?php echo htmlspecialchars($user_to_edit['avatar_url']); ?>" alt="Avatar" class="img-thumbnail avatar-image">
                        <button type="button" class="avatar-button" onclick="document.getElementById('avatar').click();">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    </div>
                <?php else: ?>
                    <div class="position-relative d-inline-block">
                        <img src="../../Images/pp_defaut.jpg" alt="Default Avatar" class="img-thumbnail avatar-image">
                        <button type="button" class="avatar-button" onclick="document.getElementById('avatar').click();">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <div class="mb-3">
                            <label for="pseudo" class="form-label">Pseudo:</label>
                            <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo htmlspecialchars($user_to_edit['pseudo']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom:</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($user_to_edit['nom']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_to_edit['adresse_email']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="genre" class="form-label">Genre:</label>
                            <select class="form-select" id="genre" name="genre">
                                <option value="Homme" <?php if ($user_to_edit['genre'] === 'Homme') echo 'selected'; ?>>Homme</option>
                                <option value="Femme" <?php if ($user_to_edit['genre'] === 'Femme') echo 'selected'; ?>>Femme</option>
                                <option value="Autre" <?php if ($user_to_edit['genre'] === 'Autre') echo 'selected'; ?>>Autre</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Ancien mot de passe:</label>
                            <input type="password" class="form-control" id="old_password" name="old_password">
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nouveau mot de passe:</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" <?php if ($user_to_edit['newsletter'] == 1) echo 'checked'; ?>>
                                <label class="form-check-label" for="newsletter">
                                    Recevoir la newsletter
                                </label>
                            </div>
                        </div>
                        <input type="file" id="avatar" name="avatar" class="d-none">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

