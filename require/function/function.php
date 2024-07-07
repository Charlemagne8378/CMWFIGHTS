<?php
require_once '../require/config/config.php'; // Assurez-vous que ce chemin est correct
require_once '../require/phpmailer/Exception.php';
require_once '../require/phpmailer/PHPMailer.php';
require_once '../require/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Récupérer les rôles
function getRoles()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM ROLES");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les permissions
function getPermissions()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM PERMISSIONS");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Vérifier si un rôle a une certaine permission
function roleHasPermission($roleId, $permissionId)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM ROLES_PERMISSIONS WHERE ID_ROLE = :roleId AND ID_PERMISSION = :permissionId");
    $stmt->execute(['roleId' => $roleId, 'permissionId' => $permissionId]);
    return $stmt->fetchColumn() > 0;
}

function supprimerRole($roleId, $pdo) {
    $stmt = $pdo->prepare('DELETE FROM ROLES WHERE ID = ?');
    $stmt->execute([$roleId]);
}

// Ajouter un rôle
function ajouterRole($nomRole)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO ROLES (NOM_ROLE) VALUES (:nomRole)");
    $stmt->execute(['nomRole' => $nomRole]);
}

// Associer des permissions à un rôle
function associerRolePermissions($roleId, $permissions)
{
    global $pdo;
    // Supprimer les anciennes associations de permissions pour ce rôle
    $stmt = $pdo->prepare("DELETE FROM ROLES_PERMISSIONS WHERE ID_ROLE = :roleId");
    $stmt->execute(['roleId' => $roleId]);

    // Ajouter les nouvelles associations
    foreach ($permissions as $permissionId) {
        $stmt = $pdo->prepare("INSERT INTO ROLES_PERMISSIONS (ID_ROLE, ID_PERMISSION) VALUES (:roleId, :permissionId)");
        $stmt->execute(['roleId' => $roleId, 'permissionId' => $permissionId]);
    }
}

// Fonction pour envoyer un email
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
