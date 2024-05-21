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
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;

    if (!empty($password) && $password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $query = "UPDATE UTILISATEUR SET nom = :nom, adresse_email = :email, genre = :genre, Type = :type, newsletter = :newsletter";

        if (!empty($password)) {
            $query .= ", Mot_de_passe = :password";
        }

        $query .= " WHERE pseudo = :pseudo";
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':newsletter', $newsletter);
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
            $_SESSION['utilisateur_connecte']['newsletter'] = $newsletter;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
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
        <a class="nav-link active" href="modifier_utilisateur">
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
        <a class="nav-link" href="image">
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
        <a class="nav-link" href="logs">
            <i class="bi bi-journal"></i>
            <span class="ml-2 d-none d-sm-inline">Logs</span>
        </a>
        <a class="nav-link" href="permissions">
            <i class="bi bi-shield-lock"></i>
            <span class="ml-2 d-none d-sm-inline">Permissions utilisateurs</span>
        </a>
        <a class="nav-link" href="bdd">
            <i class="bi bi-gear"></i>
            <span class="ml-2 d-none d-sm-inline">Base de données</span>
        </a>
        <div class="account-box">
            <a href="../compte/settings">Paramètres</a>
            <a href="../auth/logout.php">Déconnexion</a>
        </div>
        <button class="btn btn-primary btn-block account-btn">
            Compte
        </button>
    </nav>
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
            <button type="button" class="btn btn-primary mt-3" onclick="window.location.href='../process/download_data'">Télécharger les données utilisateur</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css"></script>
    <script src="../script/script.js"></script>


</body>
</html>
