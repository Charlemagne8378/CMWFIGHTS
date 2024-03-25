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

// Récupérer les données du combattant à modifier
$classementmma_id = $_GET['id'];

// Préparer la requête pour récupérer les données du combattant
$sql = "SELECT classementmma.classementmma_id, classementmma.classement_id, classementmma.combattant_id, classementmma.ranking, classement.classement_name, combattant.nom AS combattant_name 
        FROM classementmma 
        INNER JOIN classement ON classementmma.classement_id = classement.classement_id 
        INNER JOIN Combattant ON classementmma.combattant_id = combattant.combattant_id 
        WHERE classementmma.classementmma_id = ?";

if ($stmt = $pdo->prepare($sql)) {
    // Liaison des paramètres
    $stmt->bindParam(1, $classementmma_id, PDO::PARAM_INT);

    // Exécution de la requête
    if ($stmt->execute()) {
        // Récupérer les résultats de la requête
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si le combattant existe
        if ($row) {
            // Récupérer les valeurs des colonnes
            $classementmma_id = $row['classementmma_id'];
            $classement_id = $row['classement_id'];
            $classement_name = $row['classement_name'];
            $combattant_id = $row['combattant_id'];
            $combattant_name = $row['combattant_name'];
            $ranking = $row['ranking'];
        } else {
            // Combattant non trouvé, afficher un message d'erreur
            echo "Combattant non trouvé.";
            exit;
        }
    } else {
        // Erreur lors de l'exécution de la requête, afficher un message d'erreur
        echo "Erreur lors de l'exécution de la requête.";
        exit;
    }
} else {
    // Erreur lors de la préparation de la requête, afficher un message d'erreur
    echo "Erreur lors de la préparation de la requête.";
    exit;
}

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les nouvelles valeurs du formulaire
    $new_ranking = $_POST['ranking'];

    // Préparer la requête de mise à jour
    $sql_update = "UPDATE classementmma SET ranking = ? WHERE classementmma_id = ?";

    if ($stmt_update = $pdo->prepare($sql_update)) {
        // Liaison des paramètres
        $stmt_update->bindParam(1, $new_ranking, PDO::PARAM_INT);
        $stmt_update->bindParam(2, $classementmma_id, PDO::PARAM_INT);

        // Exécution de la requête de mise à jour
        if ($stmt_update->execute()) {
            // Redirection vers une page de confirmation ou autre
            echo'yesy'
            exit;
        } else {
            // Erreur lors de l'exécution de la requête de mise à jour, afficher un message d'erreur
            echo "Erreur lors de la mise à jour du combattant.";
            exit;
        }
    } else {
        // Erreur lors de la préparation de la requête de mise à jour, afficher un message d'erreur
        echo "Erreur lors de la préparation de la requête de mise à jour.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un combattant MMA</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Modifier un combattant MMA</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="classementmma_id">ID du Combattant MMA</label>
                <input type="text" class="form-control" id="classementmma_id" name="classementmma_id" value="<?php echo $classementmma_id; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="classement_id">Classement</label>
                <input type="text" class="form-control" id="classement_id" name="classement_id" value="<?php echo $classement_name; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="combattant_id">Combattant</label>
                <input type="text" class="form-control" id="combattant_id" name="combattant_id" value="<?php echo $combattant_name; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="ranking">Ranking</label>
                <input type="number" class="form-control" id="ranking" name="ranking" value="<?php echo $ranking; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
        </form>
    </div>
</body>

</html>
