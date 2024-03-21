<?php
require 'vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '/var/www/html/vendor/autoload.php';

require '/var/www/html/vendor/phpmailer/phpmailer/src/Exception.php';
require '/var/www/html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/var/www/html/vendor/phpmailer/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expediteur = $_POST['expediteur'];
    $destinataire = $_POST['destinataire'];
    $sujet = $_POST['sujet'];
    $message = $_POST['message'];
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cmwfight00@gmail.com';
    $mail->Password = 'cmw75012';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->setFrom($expediteur);
    $mail->addAddress($destinataire);
    $mail->Subject = $sujet;
    $mail->Body = $message;
    if ($mail->send()) {
        echo 'Message envoyé avec succès!';
    } else {
        echo 'Erreur lors de l\'envoi du message : ' . $mail->ErrorInfo;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'envoi de message</title>
</head>
<body>
    <h2>Formulaire d'envoi de message</h2>
    <form action="envoi_message.php" method="post">
        <label for="expediteur">Votre adresse e-mail :</label><br>
        <input type="email" id="expediteur" name="expediteur" required><br><br>
        
        <label for="destinataire">Adresse e-mail du destinataire :</label><br>
        <input type="email" id="destinataire" name="destinataire" required><br><br>
        
        <label for="sujet">Sujet :</label><br>
        <input type="text" id="sujet" name="sujet" required><br><br>
        
        <label for="message">Message :</label><br>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>
        
        <input type="submit" value="Envoyer">
    </form>
</body>
</html>
