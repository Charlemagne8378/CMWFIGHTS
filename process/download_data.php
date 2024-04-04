<?php
session_start();

if (!isset($_SESSION['utilisateur_connecte'])) {
    header('Location: ../auth/connexion');
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Paris');

require_once '../config/config.php';

$user_id = $_SESSION['id'];

$csv_filepath = '/var/www/html/csv/utilisateurs/utilisateur-' . $user_id . '-' . date("Y-m-d-H-i-s") . '.csv';

if (!is_dir(dirname($csv_filepath)) || !is_writable(dirname($csv_filepath))) {
    die('Erreur : le répertoire cible n\'est pas accessible en écriture.');
}

$csv_file = fopen($csv_filepath, 'w');

fputcsv($csv_file, ['ID', 'Pseudo', 'Nom', 'Adresse e-mail', 'Mot de passe', 'Genre', 'Type', 'Email vérifié', 'Code de vérification', 'Newsletter']);

$stmt = $pdo->prepare('SELECT id, pseudo, nom, adresse_email, mot_de_passe, genre, type, email_verifie, verification_code, newsletter FROM UTILISATEUR WHERE id = :user_id');
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Erreur : aucun utilisateur trouvé avec cet identifiant.');
}

fputcsv($csv_file, $user);
$pdo = null;
fclose($csv_file);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . basename($csv_filepath) . '"');
readfile($csv_filepath);
exit();
