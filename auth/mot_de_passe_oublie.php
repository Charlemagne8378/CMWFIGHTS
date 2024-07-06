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

$alert_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (empty($email)) {
        $alert_message = '<div class="alert alert-danger" role="alert">Veuillez entrer votre adresse e-mail.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert_message = '<div class="alert alert-danger" role="alert">Adresse e-mail invalide.</div>';
    } else {
        $sql = "SELECT * FROM UTILISATEUR WHERE adresse_email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $alert_message = '<div class="alert alert-danger" role="alert">Cette adresse e-mail n\'est pas associée à un compte.</div>';
        } else {
            $verification_code = bin2hex(random_bytes(32));

            $sql = "UPDATE UTILISATEUR SET mot_de_passe = ?, email_verifie = 0, verification_code = ? WHERE adresse_email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, password_hash($verification_code, PASSWORD_DEFAULT));
            $stmt->bindValue(2, $verification_code);
            $stmt->bindValue(3, $email);

            if ($stmt->execute()) {
                $reset_link = 'https://www.cmwfight.fr/auth/reset_password?code=' . urlencode($verification_code) . '&email=' . urlencode($email);
                $email_sent = sendEmail($email, 'Réinitialisation de mot de passe', "Cliquez sur le lien suivant pour réinitialiser votre mot de passe :\n\n$reset_link");

                if ($email_sent) {
                    $alert_message = '<div class="alert alert-success" role="alert">Un e-mail de réinitialisation de mot de passe a été envoyé à votre adresse e-mail.</div>';
                } else {
                    $alert_message = '<div class="alert alert-danger" role="alert">Erreur lors de l\'envoi de l\'e-mail de réinitialisation de mot de passe.</div>';
                }
            } else {
                $alert_message = '<div class="alert alert-danger" role="alert">Erreur lors de la réinitialisation du mot de passe : ' . print_r($pdo->errorInfo(), true) . '</div>';
            }
        }
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
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h1 class="card-title text-center">Mot de passe oublié</h1>
                    </div>
                    <div class="card-body">
                        <?php echo $alert_message; ?>
                        <form action="mot_de_passe_oublie.php" method="post">
                            <div class="form-group">
                                <label for="email">Adresse e-mail:</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Envoyer le lien de réinitialisation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
