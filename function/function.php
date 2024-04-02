<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

function getRandomCaptchaQuestion($pdo)
{
    $sql = "SELECT * FROM captcha ORDER BY RAND() LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function sendEmail($to, $subject, $content)
{
    $mail = new PHPMailer(true);

    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'cmwfight00@gmail.com';
        $mail->Password = 'nlao iaqf cbuy ooou';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('cmwfight00@gmail.com', 'CMWFIGHT');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;

        $message = '<html><body>';
        $message .= '<h2>' . htmlspecialchars($subject) . '</h2>';
        $message .= '<p>' . htmlspecialchars($content) . '</p>';
        $message .= '<p>Suivez-nous sur les réseaux sociaux : <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a> <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a> <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a> <a href="https://www.youtube.com/@CMWFIGHT" target="_blank"><i class="fab fa-youtube"></i></a></p>';
        $message .= "<p><a href='privacy.html' target='_blank'>Politique de confidentialité</a> | <a href='terms.html' target='_blank'>Conditions d'utilisation</a></p>";
        $message .= '<p><a href="newsletters?unsubscribe_email=' . urlencode($to) . '">Se désabonner</a></p>';
        $message .= '</body></html>';

        $mail->Body = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi du courriel : {$mail->ErrorInfo}";
        return false;
    }
}

?>