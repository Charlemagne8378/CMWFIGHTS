<?php
require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$stmt = $pdo->prepare('SELECT nom, prenom, pseudo, email, poids, taille, experience FROM Candidature');
$stmt->execute();
$candidat = $stmt->fetchAll();

$pdo = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Administration</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Administration</h1>

        <h2>Statistiques</h2>

        <h2>Liste des Candidats</h2>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th class="sortable" data-column="1">nom</th>
                    <th class="sortable" data-column="2">prenom</th>
                    <th class="sortable" data-column="3">pseudo</th>
                    <th class="sortable" data-column="4">email</th>
                    <th class="sortable" data-column="5">poids</th>
                    <th class="sortable" data-column="6">taille</th>
                    <th class="sortable" data-column="7">experience</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($candidat as $utilisateur): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($utilisateur['nom']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($utilisateur['prenom']) ?>
                        </td>
                        <td>

                            <?= htmlspecialchars($utilisateur['pseudo']) ?>
                        </td>
                        <td>
                            
                            <?= htmlspecialchars($utilisateur['email']) ?>
                        </td>
                        <td>
                            
                            <?= htmlspecialchars($utilisateur['poids']) ?>
                        </td>
                        <td>
                            
                            <?= htmlspecialchars($utilisateur['taille']) ?>
                        </td>
                        <td>
                            
                            <?= htmlspecialchars($utilisateur['experience']) ?>
                </td>
                <td>
                <a href="combattants.php" class="btn btn-primary">Accepter</a>
                </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        

    
</body>
</html>


