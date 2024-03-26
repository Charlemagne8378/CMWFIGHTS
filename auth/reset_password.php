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
        echo "Veuillez entrer votre nouveau mot de passe et le confirmer.";
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $sql = "UPDATE UTILISATEUR SET mot_de_passe = ?, email_verifie = 1, verification_code = NULL WHERE adresse_email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $hashed_password);
    $stmt->bindValue(2, $email);

    if ($stmt->execute()) {
        echo 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.';
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
    <title>Réinitialisation de mot de passe</title>
</head>
<body>
    <h1>Réinitialisation de mot de passe</h1>
    <?php if ($_SERVER["REQUEST_METHOD"] != "POST"): ?>
        <form action="reset_password?code=<?php echo urlencode($verification_code); ?>&email=<?php echo urlencode($email); ?>" method="post">
            <label for="new_password">Nouveau mot de passe:</label>
            <input type="password" name="new_password" id="new_password" required><br>
            <label for="confirm_password">Confirmer le mot de passe:</label>
            <input type="password" name="confirm_password" id="confirm_password" required><br>
            <input type="submit" value="Réinitialiser le mot de passe">
        </form>
    <?php endif; ?>
</body>
</html>