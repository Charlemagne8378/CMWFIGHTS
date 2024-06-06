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

require_once '../require/config/config.php';
require_once '../require/fpdf/fpdf.php';

$user_id = $_SESSION['id'];

class PDF extends FPDF
{
    function header()
    {
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 10, 'Informations Utilisateur', 0, 1, 'C');
        $this->Ln(10); 
    }

    function footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }

    function chapterBody($user, $custom_header)
    {
        $this->SetMargins(20, 20, 20);
        $this->SetFont('Arial', '', 14);
        $this->Ln(10);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.5);
        $this->SetFillColor(230, 230, 230);
        $this->SetTextColor(0);
        
        foreach ($custom_header as $key => $label) {
            if ($key !== 'mot_de_passe' && $key !== 'verification_code') {
                $this->SetFont('Arial', 'B', 14);
                $this->Cell(0, 10, $label, 0, 1, 'L');
                $this->SetFont('Arial', '', 12);
                $value = '';
                if ($key === 'email_verifie') {
                    $value = $user[$key] ? 'Vérifié' : 'Non vérifié';
                } elseif ($key === 'newsletter') {
                    $value = $user[$key] ? 'Inscrit' : 'Non inscrit';
                } else {
                    $value = $user[$key];
                }
                $this->MultiCell(0, 10, $value, 1, 'L', true);
                $this->SetFillColor(180, 180, 180);
                $this->Cell(0, 0, '', 'T', 1, 'L', true);
                $this->Ln(5);
            }
        }
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$custom_header = [
    'pseudo' => 'Pseudo',
    'nom' => 'Nom',
    'adresse_email' => 'Adresse e-mail',
    'genre' => 'Genre',
    'type' => 'Type',
    'email_verifie' => 'Statut de l\'e-mail',
    'newsletter' => 'Inscription à la newsletter'
];

$stmt = $pdo->prepare('SELECT pseudo, nom, adresse_email, genre, type, email_verifie, newsletter FROM UTILISATEUR WHERE id = :user_id');
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Erreur : aucun utilisateur trouvé avec cet identifiant.');
}

$pdf->chapterBody($user, $custom_header);

$pdf->Output('informations-utilisateur.pdf', 'D');
?>
