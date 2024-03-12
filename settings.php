<?php
include 'config.php'; 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte'])) {
    header('Location: connexion.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    // Nouvelles données
    $utilisateur_email = $_SESSION['utilisateur_connecte']['Adresse_email'];
    $stmt = $pdo->prepare("UPDATE Utilisateurs SET Pseudo = :pseudo, Nom = :nom, Genre = :genre, Adresse_email = :nouveau_email, Mot_de_passe = :nouveau_mot_de_passe WHERE Adresse_email = :email");

    // Pseudo
    if (!empty($_POST['nouveau_pseudo'])) {
        $stmt->bindParam(":pseudo", $_POST['nouveau_pseudo']);
    } else {
        $stmt->bindValue(":pseudo", $_SESSION['utilisateur_connecte']['Pseudo']);
    }

    // Nom
    if (!empty($_POST['nouveau_nom'])) {
        $stmt->bindParam(":nom", $_POST['nouveau_nom']);
    } else {
        $stmt->bindValue(":nom", $_SESSION['utilisateur_connecte']['Nom']);
    }

    // Genre
    if (!empty($_POST['nouveau_genre'])) {
        $stmt->bindParam(":genre", $_POST['nouveau_genre']);
    } else {
        $stmt->bindValue(":genre", $_SESSION['utilisateur_connecte']['Genre']);
    }

    // Nouveau email
    if (!empty($_POST['nouveau_email'])) {
        $stmt->bindParam(":nouveau_email", $_POST['nouveau_email']);
    } else {
        $stmt->bindValue(":nouveau_email", $_SESSION['utilisateur_connecte']['Adresse_email']);
    }

    // Nouveau mot de passe
    if (!empty($_POST['nouveau_mot_de_passe'])) {
        // Vérifier si le champ de confirmation du mot de passe est également rempli
        if (!empty($_POST['confirmation_mot_de_passe']) && ($_POST['nouveau_mot_de_passe'] == $_POST['confirmation_mot_de_passe'])) {
            $hashed_password = password_hash($_POST['nouveau_mot_de_passe'], PASSWORD_DEFAULT);
            $stmt->bindParam(":nouveau_mot_de_passe", $hashed_password);
        } else {
            echo "<script>alert('Erreur : Veuillez remplir le champ de confirmation du mot de passe et assurez-vous qu'il correspond au nouveau mot de passe.');</script>";
            exit();
        }
    } else {
        // Le champ de mot de passe est vide, assurez-vous que la confirmation est également vide
        if (!empty($_POST['confirmation_mot_de_passe'])) {
            echo "<script>alert('Erreur : Veuillez remplir le champ de mot de passe.');</script>";
            exit();
        }
    }

    $stmt->bindParam(":email", $utilisateur_email);

    try {
        if ($stmt->execute()) {
            echo "<script>alert('Modification réussie.');</script>";
        } else {
            echo "<script>alert('Erreur lors de l'exécution de la requête.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erreur lors de la mise à jour des données : " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte</title>
    <link rel="icon" type="image/png" href="Images/cmwicon.png">
</head>
<body>

    <h2>Modifier les informations</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Nouveau pseudo: <input type="text" name="nouveau_pseudo"><br>
        Nouveau nom: <input type="text" name="nouveau_nom"><br>
        Nouveau genre:
        <label><input type="radio" name="nouveau_genre" value="Homme"> Homme</label>
        <label><input type="radio" name="nouveau_genre" value="Femme"> Femme</label>
        <label><input type="radio" name="nouveau_genre" value="Autre"> Autre</label><br>
        
        Nouveau email: <input type="email" name="nouveau_email"><br>
        Nouveau mot de passe: <input type="password" name="nouveau_mot_de_passe"><br>
        Confirmer le mot de passe: <input type="password" name="confirmation_mot_de_passe"><br>
        
        <input type="submit" name="modifier" value="Modifier">
    </form>

</body>
</html>
