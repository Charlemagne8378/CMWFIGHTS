<?php
require_once '/var/www/html/require/config/config.php';
require_once '/var/www/html/require/function/function.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM NEWSLETTERS WHERE statut = 'programmé' AND date_programmation <= NOW()");
    $stmt->execute();
    $newsletters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($newsletters as $newsletter) {
        $id = $newsletter['id'];
        $title = $newsletter['titre'];
        $subject = $newsletter['sujet'];
        $recipientsStmt = $pdo->query("SELECT adresse_email FROM UTILISATEUR WHERE newsletter = 1");
        $recipients = $recipientsStmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($recipients as $recipient) {
            try {
                sendEmail($recipient, $title, $subject);
            } catch (Exception $e) {
                echo "Erreur lors de l'envoi de l'e-mail à $recipient : " . $e->getMessage() . "<br>";
            }
        }
        $updateStmt = $pdo->prepare("UPDATE NEWSLETTERS SET statut = 'envoyé', date_envoi = NOW() WHERE id = :id");
        $updateStmt->execute(['id' => $id]);
    }

} catch (PDOException $ex) {
    echo "Erreur PDO : " . $ex->getMessage() . "<br>";
} catch (Exception $ex) {
    echo "Erreur : " . $ex->getMessage() . "<br>";
}
?>
