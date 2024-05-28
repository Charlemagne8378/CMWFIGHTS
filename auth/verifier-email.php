<?php
require_once '../require/config/config.php';

if (!isset($_GET['code']) || !isset($_GET['pseudo'])) {
    die("Lien invalide. Veuillez vérifier votre lien de vérification d'e-mail.");
}

$code = $_GET['code'];
$pseudo = $_GET['pseudo'];

$sql = "SELECT * FROM UTILISATEUR WHERE pseudo = ? AND verification_code = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $pseudo);
$stmt->bindValue(2, $code);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $email_verifie = 1;
    $sql = "UPDATE UTILISATEUR SET email_verifie = ?, verification_code = '' WHERE pseudo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $email_verifie);
    $stmt->bindValue(2, $pseudo);

    if ($stmt->execute()) {
        echo "<h2>Votre adresse e-mail a été vérifiée avec succès !</h2>";
        echo "<p>Vous pouvez maintenant vous connecter avec votre compte.</p>";
        echo "<a href='../auth/connexion'>Se connecter</a>";
    } else {
        echo "<h2>Erreur lors de la vérification de l'adresse e-mail.</h2>";
        echo "<p>Veuillez contacter l'administrateur pour obtenir de l'aide.</p>";
    }
} else {
    echo "<h2>Lien invalide ou déjà utilisé.</h2>";
}

$stmt->closeCursor();
$pdo = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification d'email</title>
</head>
<body style="text-align: center; padding: 2em;">
</body>
</html>
