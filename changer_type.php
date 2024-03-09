<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'Adresse_email', FILTER_VALIDATE_EMAIL);
    $nouveau_type = filter_input(INPUT_POST, 'nouveau_type', FILTER_SANITIZE_STRING);

    if ($email && $nouveau_type) {
        $stmt = $conn->prepare("UPDATE Utilisateurs SET Type = :nouveau_type WHERE Adresse_email = :email");
        $stmt->bindParam(":nouveau_type", $nouveau_type);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        header("Location: admin");
        exit();
    } else {
        echo "Des informations sont manquantes ou invalides.";
    }
}

$conn = null;
?>
