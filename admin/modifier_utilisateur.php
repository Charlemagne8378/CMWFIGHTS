<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../config/config.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$pseudo_utilisateur = $_GET['pseudo'] ?? $_SESSION['utilisateur_connecte']['pseudo'];
$user_to_edit = [];

if (!empty($pseudo_utilisateur)) {
    $query = "SELECT * FROM UTILISATEUR WHERE pseudo = :pseudo";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':pseudo', $pseudo_utilisateur);
    $stmt->execute();
    $user_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $user_to_edit = $_SESSION['utilisateur_connecte'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $genre = $_POST['genre'];
    $type = $_POST['type'];

    if (!empty($password) && $password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $query = "UPDATE UTILISATEUR SET nom = :nom, adresse_email = :email, genre = :genre, Type = :type";

        if (!empty($password)) {
            $query .= ", Mot_de_passe = :password";
        }

        $query .= " WHERE pseudo = :pseudo";
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':pseudo', $pseudo);

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashed_password);
        }

        $stmt->execute();

        if ($_SESSION['utilisateur_connecte']['pseudo'] === $pseudo) {
            $_SESSION['utilisateur_connecte']['nom'] = $nom;
            $_SESSION['utilisateur_connecte']['adresse_email'] = $email;
            $_SESSION['utilisateur_connecte']['genre'] = $genre;
            $_SESSION['utilisateur_connecte']['type'] = $type;
        }

        header('Location: admin');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le profil</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
<body>
    <div class="container">
        <h1 class="my-4">Modifier le profil</h1>
        <form method="post">
            <div class="mb-3">
                <label for="pseudo" class="form-label">Pseudo:</label>
                <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo htmlspecialchars($pseudo_utilisateur); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom:</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($user_to_edit['nom'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_to_edit['adresse_email'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmer le mot de passe:</label>
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
                <label for="type" class="form-label">Type:</label>
                <select class="form-select" id="type" name="type">
                    <option value="admin" <?php if ($user_to_edit['type'] === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="moderateur" <?php if ($user_to_edit['type'] === 'moderateur') echo 'selected'; ?>>Modérateur</option>
                    <option value="utilisateur" <?php if ($user_to_edit['type'] === 'utilisateur') echo 'selected'; ?>>Utilisateur</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="admin" class="btn btn-secondary">Retour</a>
        </form>

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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css"></script>
    <script src="../script/script.js"></script>


</body>
</html>
