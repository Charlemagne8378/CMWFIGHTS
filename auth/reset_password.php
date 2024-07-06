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

if (!isset($_GET['code']) || !isset($_GET['email'])) {
    die("Lien invalide.");
}

$verification_code = $_GET['code'];
$email = $_GET['email'];

$sql = "SELECT * FROM UTILISATEUR WHERE adresse_email = ? AND verification_code = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $email);
$stmt->bindValue(2, $verification_code);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    die("Lien invalide ou expiré.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($new_password) || empty($confirm_password)) {
        echo '<div class="alert alert-danger" role="alert">Veuillez entrer votre nouveau mot de passe et le confirmer.</div>';
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo '<div class="alert alert-danger" role="alert">Les mots de passe ne correspondent pas.</div>';
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $sql = "UPDATE UTILISATEUR SET mot_de_passe = ?, email_verifie = 1, verification_code = NULL WHERE adresse_email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $hashed_password);
    $stmt->bindValue(2, $email);

    if ($stmt->execute()) {
        echo '<div id="success-message" class="alert alert-success" role="alert">Votre mot de passe a été réinitialisé avec succès. Vous allez être redirigé vers la page de connexion.</div>';
        echo '<script>
                setTimeout(function() {
                    window.location.href = "connexion";
                }, 3000);
              </script>';
        exit();
    } else {
        echo '<div class="alert alert-danger" role="alert">Erreur lors de la réinitialisation du mot de passe : ' . print_r($pdo->errorInfo(), true) . '</div>';
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
    <title>Réinitialisation de mot de passe</title>
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h1 class="card-title">Réinitialisation de mot de passe</h1>
                    </div>
                    <div class="card-body">
                        <?php if ($_SERVER["REQUEST_METHOD"] != "POST"): ?>
                            <form action="reset_password.php?code=<?php echo urlencode($verification_code); ?>&email=<?php echo urlencode($email); ?>" method="post">
                                <div class="form-group">
                                    <label for="new_password">Nouveau mot de passe:</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirmer le mot de passe:</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                </div>
                                <div class="form-group text-center">
                                    <input type="submit" value="Réinitialiser le mot de passe" class="btn btn-primary">
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
