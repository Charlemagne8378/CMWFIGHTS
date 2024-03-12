<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../config/config.php'; // Include le fichier de configuration de la base de données

// Vérifier si l'utilisateur est connecté et est un admin
session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['Type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$pseudo_user = $_SESSION['utilisateur_connecte']['Pseudo'];

// Récupérer les données de l'utilisateur
// Récupérer les données de l'utilisateur
$query = "SELECT * FROM Utilisateurs WHERE pseudo = :pseudo";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':pseudo', $pseudo_user);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


// Traiter le formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pseudo = $_POST['pseudo'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $genre = $_POST['genre'];
    $type = $_POST['type'];

    // Vérifier si le mot de passe et la confirmation correspondent
    if (!empty($password) && $password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Préparer la requête UPDATE
        $query = "UPDATE Utilisateurs SET pseudo = :pseudo, nom = :nom, adresse_email = :email, genre = :genre, type = :type WHERE pseudo = :pseudo_user";
        $stmt = $pdo->prepare($query);

        // Lier les valeurs aux paramètres
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':pseudo_user', $pseudo_user);

        // Vérifier si le mot de passe est rempli et mettre à jour le mot de passe dans la requête
        if (!empty($password)) {
            $query .= ", mot_de_passe = :password";
            $stmt->bindParam(':password', $password);
        }

        // Exécuter la requête
        $stmt->execute();

        header('Location: admin');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Admin - Modifier le profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
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

        input[type="submit"],
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px 20px;
            box-sizing: border-box;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            box-shadow: 0 0 5px rgba(81, 203, 238, 1);
            padding: 8px 20px 8px 20px;
            transition: padding 0.3s ease-in-out;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.2);
        }

        input[type="submit"]:hover {
            background-color: #258cd1;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.3);
        }
        form {
            max-width: 100%;
            padding: 20px;
        }
        @media (min-width: 768px) {
            form {
                max-width: 400px;
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Modifier le profil</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="pseudo" class="form-label">Pseudo:</label>
                <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $user['Pseudo'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom:</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $user['Nom'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['Adresse_email'] ?? ''; ?>">
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
                    <option value="" <?php if ($user['Genre'] === NULL) echo 'selected'; ?>>Aucun</option>
                    <option value="homme" <?php if ($user['Genre'] === 'homme') echo 'selected'; ?>>Homme</option>
                    <option value="femme" <?php if ($user['Genre'] === 'femme') echo 'selected'; ?>>Femme</option>
                    <option value="autre" <?php if ($user['Genre'] === 'autre') echo 'selected'; ?>>Autre</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type:</label>
                <select class="form-select" id="type" name="type">
                    <option value="admin" <?php if ($user['Type'] === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="moderateur" <?php if ($user['Type'] === 'moderateur') echo 'selected'; ?>>Modérateur</option>
                    <option value="utilisateur" <?php if ($user['Type'] === 'utilisateur') echo 'selected'; ?>>Utilisateur</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="admin" class="btn btn-secondary">Retour</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBud7TlRbs/ic4AwGcFZOxg5DpPt8EgeUIgIwzjWfXQKWA3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
</body>
</html>
