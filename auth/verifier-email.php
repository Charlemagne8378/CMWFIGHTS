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
        $message = "<div class='alert alert-success' role='alert'>Votre adresse e-mail a été vérifiée avec succès ! Vous pouvez maintenant vous connecter à votre compte.</div>";
        $link = "<a href='../auth/connexion' class='btn btn-primary'>Se connecter</a>";
    } else {
        $message = "<div class='alert alert-danger' role='alert'>Erreur lors de la vérification de l'adresse e-mail. Veuillez contacter l'administrateur pour obtenir de l'aide.</div>";
    }
} else {
    $message = "<div class='alert alert-warning' role='alert'>Lien invalide ou déjà utilisé.</div>";
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
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body style="text-align: center; padding: 2em;">
    <?php echo $message; ?>
    <?php if(isset($link)) echo $link; ?>
</body>
</html>
