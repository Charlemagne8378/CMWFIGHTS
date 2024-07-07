<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['poids']) && isset($_POST['taille']) && isset($_POST['experience'])) {
        
        require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

        try {
            $stmt = $pdo->prepare("INSERT INTO Candidature (nom, prenom, pseudo, email, poids, taille, experience) VALUES (:nom, :prenom, :pseudo, :email, :poids, :taille, :experience)");
            
            $stmt->bindParam(':nom', $_POST['nom']);
            $stmt->bindParam(':prenom', $_POST['prenom']);
            $stmt->bindParam(':pseudo', $_POST['pseudo']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':poids', $_POST['poids']);
            $stmt->bindParam(':taille', $_POST['taille']);
            $stmt->bindParam(':experience', $_POST['experience']);
            
            
            $stmt->execute();
            
            echo "GOOD";
            exit;
        } catch (PDOException $e) {
            echo "Erreur d'insertion dans la base de données : " . $e->getMessage();
        }
    } else {
        echo "Veuillez remplir tous les champs du formulaire.";
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inscription</title>
    <link rel="stylesheet" href="../style/candidature.css">
</head>
<body>
    <div class="fiche">
        <form method="POST" action="candidature.php">
            <h1>Inscription Combattant</h1>

            <div class="form-group">
                <label for="nom">Nom</label>
                <br>
                <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" required>
                <br>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <br>
                <input type="text" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
                <br>
            </div>
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <br>
                <input type="text" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo" required>
                <br>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <br>
                <input type="email" id="email" name="email" placeholder="Entrez votre Email" required>
                <br>
            </div>
            <div class="form-group">
                <label for="poids">Poids</label>
                <br>
                <input type="number" id="poids" name="poids" placeholder="Entrez votre poids" required>
                <br>
            </div>
            <div class="form-group">
                <label for="taille">Taille</label>
                <br>
                <input type="number" id="taille" name="taille" placeholder="Entrez votre taille" required>
                <br>
            </div>
            <div class="form-group">
                <label for="experience">Nombre d'année d'expérience</label>
                <br>
                <input type="number" id="experience" name="experience" placeholder="Entrez votre " required>
                <br>
            </div>
            
            
            <input type="submit" value="Postuler" name="ok">

        </form>
    </div>
     
</body>
</html>