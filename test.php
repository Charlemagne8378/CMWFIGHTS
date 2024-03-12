<?php
$to = "erwan.luce.guedon@gmail.com;
$subject = "Test email";
$message = "Ceci est un email de test.";
$headers = "From: noreply@example.com";

if (mail($to, $subject, $message, $headers)) {
    echo "L'e-mail de test a été envoyé avec succès !";
} else {
    echo "Erreur lors de l'envoi de l'e-mail de test.";
}
?>
