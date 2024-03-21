<?php
require_once '../config/config.php';

if (isset($_GET['email'])) {
    $email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);

    if ($email) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE Adresse_email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {
            header('Content-Type: application/json');
            echo json_encode($utilisateur);
        } else {
            echo "Aucun utilisateur trouvé avec cette adresse e-mail.";
        }
    } else {
        echo "Adresse e-mail invalide.";
    }
}

$pdo = null;
?>
