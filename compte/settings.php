<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../config/config.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte'])) {
    header('Location: ../auth/connexion');
    exit();
}

$user_to_edit = $_SESSION['utilisateur_connecte'];
$current_page = 'settings';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_SESSION['utilisateur_connecte']['pseudo'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $genre = $_POST['genre'];
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;

    if (!empty($new_password) && $new_password !== $confirm_password) {
        $error = "Les nouveaux mots de passe ne correspondent pas.";
    } elseif (!empty($new_password) && !password_verify($old_password, $user_to_edit['Mot_de_passe'])) {
        $error = "L'ancien mot de passe est incorrect.";
    } else {
        $query = "UPDATE UTILISATEUR SET nom = :nom, adresse_email = :email, genre = :genre, newsletter = :newsletter";

        if (!empty($new_password)) {
            $query .= ", Mot_de_passe = :new_password";
        }

        $query .= " WHERE pseudo = :pseudo";
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':newsletter', $newsletter);
        $stmt->bindParam(':pseudo', $pseudo);

        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt->bindParam(':new_password', $hashed_password);
        }

        $stmt->execute();

        $_SESSION['utilisateur_connecte']['nom'] = $nom;
        $_SESSION['utilisateur_connecte']['adresse_email'] = $email;
        $_SESSION['utilisateur_connecte']['genre'] = $genre;
        $_SESSION['utilisateur_connecte']['newsletter'] = $newsletter;

        header('Location: settings');
        exit();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <a class="nav-link <?php if ($current_page == 'settings') echo 'active'; ?>" href="settings">
                        <i class="bi bi-gear"></i> Paramètres
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_page == 'preferences') echo 'active'; ?>" href="preferences">
                        <i class="bi bi-slider"></i> Préférences
                    </a>
                </li>
                <!-- Ajoutez d'autres liens de navigation ici -->
            </ul>
        </div>
        <div class="container px-4">
            <h1 class="my-4">Paramètres du profil</h1>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post">
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
                    <label for="genre" class="form-label">Genre:</label>
                    <select class="form-select" id="genre" name="genre">
                        <option value="homme" <?php if ($user_to_edit['genre'] === 'homme') echo 'selected'; ?>>Homme</option>
                        <option value="femme" <?php if ($user_to_edit['genre'] === 'femme') echo 'selected'; ?>>Femme</option>
                        <option value="autre" <?php if ($user_to_edit['genre'] === 'autre') echo 'selected'; ?>>Autre</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="newsletter" class="form-label">Newsletter:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" <?php if ($user_to_edit['newsletter'] === 1) echo 'checked'; ?>>
                        <label class="form-check-label" for="newsletter">
                            S'inscrire à la newsletter
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="../auth/mot_de_passe_oublie" class="btn btn-link">J'ai oublié mon mot de passe</a>
                <a href="dashboard" class="btn btn-secondary">Retour</a>
            </form>
        </div>
    </div>

    <script src="../script/script.js"></script>

</body>
</html>
