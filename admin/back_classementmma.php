<?php
require_once '../require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les données des classements MMA depuis la base de données
$sql = "SELECT CLASSEMENTMMA.classementmma_id, CLASSEMENTMMA.classement_id, CLASSEMENTMMA.combattant_id, CLASSEMENTMMA.ranking, CLASSEMENT.classement_name AS classement_name, COMBATTANT.nom AS combattant_name
FROM CLASSEMENTMMA
JOIN CLASSEMENT ON CLASSEMENTMMA.classement_id = CLASSEMENT.classement_id
JOIN COMBATTANT ON CLASSEMENTMMA.combattant_id = COMBATTANT.combattant_id
ORDER BY CLASSEMENTMMA.classement_id ASC, 
         CASE WHEN CLASSEMENTMMA.ranking = 'C' THEN 0 ELSE CAST(CLASSEMENTMMA.ranking AS UNSIGNED) END ASC";

$stmt = $pdo->query($sql);
$classementmma = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        if (isset($_POST['delete_classementmma_id'])) {
            $delete_classementmma_id = $_POST['delete_classementmma_id'];

            // Supprimer le combattant de la base de données
            $sql = "DELETE FROM CLASSEMENTMMA WHERE classementmma_id = :delete_classementmma_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':delete_classementmma_id', $delete_classementmma_id, PDO::PARAM_INT);
            $stmt->execute();

            // Rafraîchir la page pour refléter les changements
            header("Refresh:0");
        }
    }
}
?>



    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])) {
        if (isset($_POST['classementmma_id']) && isset($_POST['ranking'])) {
            $classementmma_ids = $_POST['classementmma_id'];
            $rankings = $_POST['ranking'];

            // Boucle à travers les données et mettre à jour les classements dans la base de données
            for ($i = 0; $i < count($classementmma_ids); $i++) {
                $classementmma_id = $classementmma_ids[$i];
                $ranking = $rankings[$i];

                // Mettre à jour le classement dans la base de données
                $sql = "UPDATE CLASSEMENTMMA SET ranking = :ranking WHERE classementmma_id = :classementmma_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':ranking', $ranking, PDO::PARAM_INT);
                $stmt->bindParam(':classementmma_id', $classementmma_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Rafraîchir la page pour afficher les modifications
            header("Location: back_classementmma.php");


        }
    }
    ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement MMA</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 800px;
            margin: 0 auto;
        }
        .section-title {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Classement MMA</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <?php
        // Initialiser la variable pour stocker l'ID de classement précédent
        $prev_classement_id = null;

        // Boucle à travers les données pour afficher les combattants dans des sections différentes
        foreach ($classementmma as $row):
            // Si l'ID de classement actuel est différent de l'ID de classement précédent
            if ($row['classement_id'] != $prev_classement_id):
                // Afficher le titre de la section avec le nom du classement
                echo '<div class="section-title">' . $row['classement_name'] . '</div>';
            endif;
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Combattant</th>
                    <th>Ranking</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $row['combattant_name']; ?></td>
                    <td>
                        <input type="text" name="ranking[]" class="form-control" value="<?php echo $row['ranking']; ?>">
                        <input type="hidden" name="classementmma_id[]" value="<?php echo $row['classementmma_id']; ?>">
                    </td>
                    <td> <!-- Colonne pour le bouton de suppression -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="delete_classementmma_id" value="<?php echo $row['classementmma_id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm" name="delete">Supprimer</button>
                </form>
            </td>
                </tr>
            </tbody>
        </table>
        <?php
            // Mettre à jour l'ID de classement précédent avec l'ID de classement actuel
            $prev_classement_id = $row['classement_id'];
        endforeach;
        ?>
        <button type="submit" class="btn btn-primary" name="saveChanges">Enregistrer les modifications</button>
        <a href="classement.php">Retour</a>
    </div>
    </form>
</body>
</html>
