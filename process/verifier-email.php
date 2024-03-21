<?php
require_once '../config/config.php';

$pseudo = $_GET['pseudo'];
$code = $_GET['code'];

$sql = "SELECT * FROM utilisateurs WHERE Pseudo = ? AND verification_code = ?";
$stmt = $pdo->prepare($sql);
$stmt->bind_param("ss", $pseudo, $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sql = "UPDATE utilisateurs SET email_verifie = 1 WHERE Pseudo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bind_param("s", $pseudo);
    $stmt->execute();

    echo "Votre email a été vérifié avec succès !";
} else {
    echo "Code de vérification invalide.";
}

$pdo->close();
?>
