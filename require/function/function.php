<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../require/phpmailer/src/Exception.php';
require __DIR__ . '/../require/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../require/phpmailer/src/SMTP.php';

function getRandomCaptchaQuestion($pdo)
{
    $sql = "SELECT * FROM CAPTCHA ORDER BY RAND() LIMIT 1";
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
        $message = '<html><body style="font-family: Arial, sans-serif; margin: 0; padding: 0">';
        $message .= '<div style="max-width: 600px; margin: 20px auto; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 3px rgba(0,0,0,0.1);">';
        $message .= '<div style="padding: 20px; text-align: center;">';
        $message .= '<img src="https://www.cmwfight.fr/Images/cmwnoir.png" alt="CMWFIGHT" style="width: 100px; height: auto;">';
        $message .= '</div>';
        $message .= '<div style="padding: 20px;">';
        $message .= '<h2 style="color: #333333;">' . htmlspecialchars($subject) . '</h2>';
        $message .= '<p style="color: #555555;">' . nl2br(htmlspecialchars($content)) . '</p>';
        $message .= '</div>';
        $message .= '<div style="padding: 20px; text-align: center;">';
        $message .= '<p>Suivez-nous sur les réseaux sociaux :</p>';
        $message .= '<a href="https://www.tiktok.com/@cmwfight?'.time().'" target="_blank"><img src="https://www.cmwfight.fr/Images/icon-rs/tiktok_icon.png?'.time().'" alt="TikTok" width="32" style="margin: 0 10px;"></a>';
        $message .= '<a href="https://www.instagram.com/cmwfight/?'.time().'" target="_blank"><img src="https://www.cmwfight.fr/Images/icon-rs/instagram_icon.png?'.time().'" alt="Instagram" width="32" style="margin: 0 10px;"></a>';
        $message .= '<a href="https://www.youtube.com/@CMWFIGHT?'.time().'" target="_blank"><img src="https://www.cmwfight.fr/Images/icon-rs/youtube_icon.png?'.time().'" alt="YouTube" width="32" style="margin: 0 10px;"></a>';
        $message .= '</div>';
        $message .= '<div style="padding: 10px 20px; text-align: center; font-size: 12px; color: #888888;">';
        $message .= '<p><a href="https://www.cmwfight.fr/pages/politique_de_confidentialite.php" target="_blank" style="color: #888888; text-decoration: none;">Politique de confidentialité</a> | <a href="https://www.cmwfight.fr/Conditions_utilisation.php" target="_blank" style="color: #888888; text-decoration: none;">Conditions d\'utilisation</a></p>';
        $message .= '<p>&copy; 2024 CMWFIGHT. Tous droits réservés.</p>';
        $message .= '</div>';
        $message .= '</div>';
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
