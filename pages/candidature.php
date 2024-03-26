<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si tous les champs requis sont remplis
    if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['poids']) && isset($_POST['taille']) && isset($_POST['experience'])) {
        
        // Inclure le fichier de configuration de la base de données
        require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

        try {
            // Préparer la requête d'insertion
            $stmt = $pdo->prepare("INSERT INTO Candidature (nom, prenom, pseudo, email, poids, taille, experience) VALUES (:nom, :prenom, :pseudo, :email, :poids, :taille, :experience)");
            
            // Liaison des paramètres
            $stmt->bindParam(':nom', $_POST['nom']);
            $stmt->bindParam(':prenom', $_POST['prenom']);
            $stmt->bindParam(':pseudo', $_POST['pseudo']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':poids', $_POST['poids']);
            $stmt->bindParam(':taille', $_POST['taille']);
            $stmt->bindParam(':experience', $_POST['experience']);
            
            
            // Exécuter la requête
            $stmt->execute();
            
            // Redirection vers une page de confirmation
            echo "GOOD";
            exit;
        } catch (PDOException $e) {
            // Gérer les erreurs de la base de données
            echo "Erreur d'insertion dans la base de données : " . $e->getMessage();
        }
    } else {
        // Tous les champs requis ne sont pas remplis
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
    <style>
        body {
        font-family: Arial, sans-serif;
        color: #FFFFFF;
        background-color: #888888;
        margin-top: 100px;
        }
        .fiche{
        max-width: 800px;
        margin: 0 auto;
        padding: 10px;
        border-radius: 5px;
        background-color: #696666;
        box-shadow:6px 6px 25px #000000;


        }
        input{
        width: 50%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-align: center;
        margin-left: 190px;
        }
        .form-group {
        margin-bottom: 15px;
        }
        input[type="submit"]{
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #000000;
        color: #FFFFFF;
        font-size: 20px;
        cursor: pointer;
        margin-left: 0px;
        }
        label {
        display: block;
        margin-bottom: 2px;
        font-size: 20px;
        text-align: center;
        }
        h1{
        text-align: center;
        text-decoration: underline #FFFFFF;
        }
        select{
        width: 10%;
        padding: 10px;
        border-radius: 5px;
        margin-left: 210px;

        }
    </style>
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