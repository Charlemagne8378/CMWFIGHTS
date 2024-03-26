<?php
require_once '../config/config.php';
require_once '../function/function.php';
use PHPMailer\PHPMailer\PHPMailer;

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!$pdo) {
    die("Échec de la connexion à la base de données : " . print_r(error_get_last(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (empty($email)) {
        echo "Veuillez entrer votre adresse e-mail.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse e-mail invalide.";
        exit();
    }

    $sql = "SELECT * FROM UTILISATEUR WHERE adresse_email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo "Cette adresse e-mail n'est pas associée à un compte.";
        exit();
    }

    $verification_code = bin2hex(random_bytes(32));

    $sql = "UPDATE UTILISATEUR SET mot_de_passe = ?, email_verifie = 0, verification_code = ? WHERE adresse_email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, password_hash($verification_code, PASSWORD_DEFAULT));
    $stmt->bindValue(2, $verification_code);
    $stmt->bindValue(3, $email);

    if ($stmt->execute()) {
        $email_sent = sendEmail($email, 'Réinitialisation de mot de passe', "Cliquez sur le lien suivant pour réinitialiser votre mot de passe :\n\n" . "https://www.cmwfight.fr/auth/reset_password.php?code=" . urlencode($verification_code) . "&email=" . urlencode($email));

        if ($email_sent) {
            echo 'Un e-mail de réinitialisation de mot de passe a été envoyé à votre adresse e-mail.';
        } else {
            echo "Erreur lors de l'envoi de l'e-mail de réinitialisation de mot de passe.";
        }
    } else {
        echo "Erreur lors de la réinitialisation du mot de passe : " . print_r($pdo->errorInfo(), true);
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
    <title>Mot de passe oublié</title>
</head>
<body>
    <h1>Mot de passe oublié</h1>
    <form action="mot_de_passe_oublie" method="post">
        <label for="email">Adresse e-mail:</label>
        <input type="email" name="email" id="email" required><br>
        <input type="submit" value="Envoyer le lien de réinitialisation">
    </form>
</body>
</html>
