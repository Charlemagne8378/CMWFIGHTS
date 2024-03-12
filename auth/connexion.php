<?php
require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? $_POST["email"] : '';
    $motDePasse = isset($_POST["mdp"]) ? $_POST["mdp"] : '';

    $sql = "SELECT * FROM Utilisateurs WHERE Adresse_email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($motDePasse, $result['Mot_de_passe'])) {
        $_SESSION['utilisateur_connecte'] = $result;
        $_SESSION['admin_type'] = $result['Type'];
        if ($result['Type'] == 'admin') {
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
    <link rel="icon" type="image/png" href="Images/cmwicon.png">
    <meta charset="UTF-8">
    <style>
            *{
    font-family: Arial, Helvetica, sans-serif;
}

body{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: rgb(116, 115, 115);
}

.outerContainer{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

form{
    width: 380px;
    height: auto;
    border: 3px solid white;
    border-radius: 10px;
    box-shadow: 0 12px 20px wheat;
    overflow: hidden;
}

h2{
    text-align: center;
    color: aliceblue;
}

.container{
    padding: 16px;
}

input[type=text], input[type=password]{
    width: 100%;
    border: 1px solid white;
    border-radius: 5px;
    margin: 8px 0;
    padding: 12px 20px;
    box-sizing: border-box;
}

input[type=text]:focus, input[type=password]:focus{
    outline: 1px solid white;
}

button{
    border: none;
    cursor: pointer;
    margin: 8px 0;
    font-size: 15px;
    font-weight: bold;
    color: white;
    background-color: black;
    border-radius: 5px;
    transition: 0.3s;
}

#login-btn{
    width: 100%;
    text-transform: uppercase;
    padding: 14px 20px;
    background-image: linear-gradient(to right, black,white);
}
#login-btn:hover{
    background-image: linear-gradient(to right, white, black);
}

label[for=remember]{
    font-size: 14px;
    color: aliceblue;
}

#forgot-password{
    font-size: 14px;
    float: right;
    padding-top: 3px;
    text-decoration: none;
    color: wheat;
}

#forgot-password:hover{
    text-decoration: underline;
}

.member{
    text-align: center;
    padding: 14px;
    border-top: 2px solid white;
}

#create-btn{
    padding: 12px 14px;
    background-color: wheat;
    color: black;
}

#create-btn:hover{
    background-color: black;
    color: wheat;
}

#create-btn a{
    text-decoration: none;
    color: black;
}

#create-btn a:hover{
    color: wheat;
}


    </style>
</head>
<body>
<?php include '../pages/compo/header.php'; ?>
<div class="outerContainer">
    <form action="" method="post">
        <h2>Connexion</h2>
        <div class="container">
            <input type="text" placeholder="Entrez votre email" name="email" required>
            <input type="password" placeholder="Entrez votre mot de passe" name="mdp" required>
            <button type="submit" id="login-btn">Se connecter</button>
            <label for="remember">
                <input type="checkbox" checked="checked">Se rappeler de moi
            </label>
            <a href="#" id="forgot-password">Mot de passe oublié ?</a>
        </div>
        <div class="member">
            <button type="button" id="create-btn"><a href="inscription">S'inscrire</a></button>
        </div>
    </form>
</div>
</body>
</html>
