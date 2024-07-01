<?php
require_once '../require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']['type'] === 'admin') {
    header('Location: ../admin/admin');
    exit();
  }
if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']['type'] === 'banni') {
    header('Location: ../banni');
    exit();
  }


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? $_POST["email"] : '';
    $motDePasse = isset($_POST["mdp"]) ? $_POST["mdp"] : '';

    $sql = "SELECT * FROM UTILISATEUR WHERE adresse_email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($motDePasse, $result['mot_de_passe'])) {
        $_SESSION['utilisateur_connecte'] = $result;
        $_SESSION['id'] = $result['id'];
        $_SESSION['admin_type'] = $result['type'];
        $date_derniere_connexion = date('Y-m-d H:i:s');
        $sql = "UPDATE UTILISATEUR SET derniere_connexion = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$date_derniere_connexion, $result['id']]);
        if ($result['type'] == 'admin') {
            header('Location: ../admin/admin');
        } else {
            header('Location: ../index');
        }
        exit();
    } elseif ($result) {
        echo "Mot de passe incorrect.";
    } else {
        echo "Aucun compte n'est associé à cette adresse e-mail.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Connexion</title>
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../style/auth.css">
</head>
<body>
<?php include'../header.php' ?>
<div class="login-container">
    <form action="" method="post">
        <h2>Connexion</h2>
        <div class="container">
            <input type="text" placeholder="Entrez votre email" name="email" required>
            <input type="password" placeholder="Entrez votre mot de passe" name="mdp" required>
            <button type="submit" id="login-btn">Se connecter</button>
            <label for="remember">
                <input type="checkbox" checked="checked">Se rappeler de moi
            </label>
            <a href="mot_de_passe_oublie" id="forgot-password">Mot de passe oublié ?</a>
        </div>
        <div class="member">
            <button type="button" id="create-btn"><a href="inscription">S'inscrire</a></button>
        </div>
    </form>
</div>
</body>
</html>
