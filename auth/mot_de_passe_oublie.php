<?php
require_once '../require/config/config.php';
require_once '../require/function/function.php';
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
        $email_sent = sendEmail($email, 'Réinitialisation de mot de passe', "Cliquez sur le lien suivant pour réinitialiser votre mot de passe :\n\n" . "https://www.cmwfight.fr/auth/reset_password?code=" . urlencode($verification_code) . "&email=" . urlencode($email));

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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h1 class="card-title">Mot de passe oublié</h1>
                    </div>
                    <div class="card-body">
                        <form action="mot_de_passe_oublie" method="post">
                            <div class="form-group">
                                <label for="email">Adresse e-mail:</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" value="Envoyer le lien de réinitialisation" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
