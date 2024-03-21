<?php
require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getRandomCaptchaQuestion($pdo)
{
    $sql = "SELECT * FROM captcha ORDER BY RAND() LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

if (!$pdo) {
    die("Échec de la connexion à la base de données : " . print_r(error_get_last(), true));
}

$captchaQuestion = false;

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $captchaQuestion = getRandomCaptchaQuestion($pdo);
} else {
    $pseudo = $_POST["Pseudo"];
    $nom = $_POST["Nom"];
    $email = $_POST["Adresse_email"];
    $motDePasse = $_POST["Mot_de_passe"];
    $confirmerMotDePasse = $_POST["confirmpassword"];
    $captchaAnswer = $_POST["captchaAnswer"];

    if (empty($pseudo) || empty($nom) || empty($email) || empty($motDePasse) || empty($confirmerMotDePasse) || empty($captchaAnswer)) {
        echo "Tous les champs obligatoires doivent être remplis.";
        exit();
    }

    if ($motDePasse !== $confirmerMotDePasse) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse e-mail invalide.";
        exit();
    }

    $sql = "SELECT * FROM utilisateurs WHERE Adresse_email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo "Cette adresse e-mail est déjà utilisée.";
        exit();
    }

    if ($captchaQuestion && $captchaQuestion['reponse'] != $captchaAnswer) {
        echo "La réponse au captcha est incorrecte.";
        exit();
    }

    $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

    $sql = "INSERT INTO utilisateurs (Pseudo, Nom, Adresse_email, Mot_de_passe, email_verifie) VALUES (?, ?, ?, ?, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $pseudo);
    $stmt->bindValue(2, $nom);
    $stmt->bindValue(3, $email);
    $stmt->bindValue(4, $motDePasseHash);

    if ($stmt->execute()) {
        $verification_code = bin2hex(random_bytes(32));

        $sql = "UPDATE utilisateurs SET email_verifie = 0, verification_code = ? WHERE Pseudo = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $verification_code);
        $stmt->bindValue(2, $pseudo);
        $stmt->execute();

        $to = $email;
        $subject = "Vérification d'email";
        $message = "Cliquez sur le lien suivant pour vérifier votre email :\n\n";
        $message .= "http://51.38.177.164/verifier-email.php?code=" . urlencode($verification_code) . "&pseudo=" . urlencode($pseudo);
        $headers = "From: noreply@example.com";
        mail($to, $subject, $message, $headers);

        echo "Inscription réussie ! Veuillez vérifier votre email pour activer votre compte.";
        header("Refresh:2; url=connexion");
    } else {
        echo "Erreur lors de l'inscription : " . print_r($pdo->errorInfo(), true);
    }

    $stmt->closeCursor();
    $pdo = null;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <style>
        * {
    padding: 0;
    margin: 0;
    font-family: sans-serif;
    box-sizing: border-box;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: rgb(116, 115, 115);
}

.container {
    max-width: 650px;
    padding: 28px;
    margin: 0 28px;
    border-radius: 10px;
    overflow: hidden;
    background: rgba(0, 0, 0, 0.2);
    box-shadow: 0 15px 20px rgba(0, 0, 0, 0.6);
}

h2 {
    font-size: 26px;
    font-weight: bold;
    text-align: left;
    color: aliceblue;
    padding-bottom: 8px;
    border-bottom: 1px solid white;
}

.content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 20px 0;
}

.input-box {
    display: flex;
    flex-wrap: wrap;
    width: 50%;
    padding-bottom: 15px;
}

.input-box:nth-child(2n) {
    justify-content: end;
}

.input-box label, .gender-title {
    width: 95%;
    color: aliceblue;
    font-weight: bold;
    margin: 5px 0;
}

.gender-title {
    font-size: 16px;
}

.input-box input {
    height: 40px;
    width: 95%;
    padding: 0 10px;
    border-radius: 5px;
    border: 1px solid white;
    outline: none;
}

.input-box input:is(:focus,:valid) {
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
}

.gender-category label {
    padding: 0 20px 0 5px;
    font-size: 14px;
}

.gender-category {
    color: gainsboro;
}

.gender-category label, .gender-category input {
    cursor: pointer;
}

.alert p {
    font-size: 14px;
    font-style: italic;
    color: aliceblue;
    margin: 5px 0;
    padding: 10px;
    line-height: 1.5;
}

.button-container {
    margin: 15px 0;
}

.button-container button {
    width: 100%;
    margin-top: 10px;
    padding: 10px;
    display: block;
    font-size: 20px;
    color: white;
    border: none;
    border-radius: 5px;
    background-color: black;
    cursor: pointer;
    transition: 0.3s;
}

.button-container button:hover {
    background-color: aliceblue;
    color: black;
}

@media (max-width: 600px) {
    .container {
        min-width: 280px;
    }
    .content {
        max-height: 380px;
        overflow: auto;
    }
    .input-box {
        margin-bottom: 12px;
        width: 100%;
    }
    .input-box:nth-child(2n) {
        justify-content: space-between;
    }
    .gender-category {
        display: flex;
        justify-content: space-between;
        width: 60%;
    }
    .content::-webkit-scrollbar {
        width: 0;
    }
}

    </style>
</head>
<body>
<?php include '../header.php' ?>
<div class="container">
    <form action="inscription" method="post">
        <h2>S'inscrire</h2>
        <div class="content">

            <div class="input-box">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="Nom" placeholder="Entrez votre nom" required>
            </div>

            <div class="input-box">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="Pseudo" placeholder="Entrez votre pseudo" required>
            </div>

            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" id="email" name="Adresse_email" placeholder="Entrez votre email" required>
            </div>

            <div class="input-box">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="Mot_de_passe" placeholder="Entrez un mot de passe" required>
            </div>

            <div class="input-box">
                <label for="confirm-password">Confirmation Mot de passe</label>
                <input type="password" id="confirm-password" name="confirmpassword" placeholder="Confirmez votre mot de passe" required>
            </div>
            <span class="gender-title">Genre</span>
            <div class="gender-category">
                <input type="radio" id="homme" name="genre" value="homme">
                <label for="homme">Homme</label>
                <input type="radio" id="femme" name="genre" value="femme">
                <label for="femme">Femme</label>
                <input type="radio" id="autre" name="genre" value="autre">
                <label for="autre">Autres</label>
            </div>
            <?php if ($captchaQuestion): ?>
            <div class="input-box">
                <label for="captchaQuestion">Question de captcha:</label>
                <input type="text" id="captchaQuestion" name="captchaQuestion" value="<?php echo htmlspecialchars($captchaQuestion['question']); ?>" readonly>
            </div>
            <div class="input-box">
                <label for="captchaAnswer">Réponse:</label>
                <input type="text" id="captchaAnswer" name="captchaAnswer" required>
            </div>
            <?php endif; ?>
        </div>
        <div class="alert">
            <p>En cliquant j'accepte les termes et conditions, vous allez recevoir un mail de confirmation contenant les détails de votre inscription.</p>
            <div class="button-container">
                <button type="submit">S'inscrire</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
