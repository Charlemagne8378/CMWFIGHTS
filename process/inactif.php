<?php

require_once __DIR__ . '/../require/function/function.php'; 
require_once __DIR__ . '/../require/config/config.php'; 

$duree_inactivite = 30;

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $date_limite = date('Y-m-d', strtotime("-$duree_inactivite days"));
    $sql = "SELECT * FROM UTILISATEUR WHERE derniere_connexion < :date_limite";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':date_limite', $date_limite);
    $stmt->execute();
    $utilisateurs_inactifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($utilisateurs_inactifs as $utilisateur) {
        $to = $utilisateur['adresse_email'];
        $subject = 'Votre compte est inactif';
        $content = 'Cher utilisateur, Votre compte sur notre site est actuellement inactif depuis ' . $duree_inactivite . ' jours. Veuillez vous connecter dès que possible pour éviter la désactivation de votre compte. Cordialement, L\'équipe de notre site';

        sendEmail($to, $subject, $content);
    }
} catch (PDOException $e) {
}
?>
