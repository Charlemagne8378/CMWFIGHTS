<?php
require_once '../require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les données des CLASSEMENTs BOXE depuis la base de données
$sql = "SELECT CLASSEMENTBOXE.CLASSEMENTBOXE_id, CLASSEMENTBOXE.CLASSEMENT_id, CLASSEMENTBOXE.COMBATTANT_id, CLASSEMENTBOXE.ranking, CLASSEMENT.CLASSEMENT_name AS CLASSEMENT_name, COMBATTANT.nom AS COMBATTANT_name
FROM CLASSEMENTBOXE
JOIN CLASSEMENT ON CLASSEMENTBOXE.CLASSEMENT_id = CLASSEMENT.CLASSEMENT_id
JOIN COMBATTANT ON CLASSEMENTBOXE.COMBATTANT_id = COMBATTANT.COMBATTANT_id
ORDER BY CLASSEMENTBOXE.CLASSEMENT_id ASC, 
         CASE WHEN CLASSEMENTBOXE.ranking = 'C' THEN 0 ELSE CAST(CLASSEMENTBOXE.ranking AS UNSIGNED) END ASC";

$stmt = $pdo->query($sql);
$CLASSEMENTBOXE = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLASSEMENT BOXE</title>
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
        <h2>CLASSEMENT BOXE</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <?php
        // Initialiser la variable pour stocker l'ID de CLASSEMENT précédent
        $prev_CLASSEMENT_id = null;

        // Boucle à travers les données pour afficher les COMBATTANTs dans des sections différentes
        foreach ($CLASSEMENTBOXE as $row):
            // Si l'ID de CLASSEMENT actuel est différent de l'ID de CLASSEMENT précédent
            if ($row['CLASSEMENT_id'] != $prev_CLASSEMENT_id):
                // Afficher le titre de la section avec le nom du CLASSEMENT
                echo '<div class="section-title">' . $row['CLASSEMENT_name'] . '</div>';
            endif;
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>COMBATTANT</th>
                    <th>Ranking</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $row['COMBATTANT_name']; ?></td>
                    <td>
                        <input type="text" name="ranking[]" class="form-control" value="<?php echo $row['ranking']; ?>">
                        <input type="hidden" name="CLASSEMENTBOXE_id[]" value="<?php echo $row['CLASSEMENTBOXE_id']; ?>">
                    </td>
                    <td> <!-- Colonne pour le bouton de suppression -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="delete_CLASSEMENTBOXE_id" value="<?php echo $row['CLASSEMENTBOXE_id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm" name="delete">Supprimer</button>
                </form>
            </td>
                </tr>
            </tbody>
        </table>
        <?php
            // Mettre à jour l'ID de CLASSEMENT précédent avec l'ID de CLASSEMENT actuel
            $prev_CLASSEMENT_id = $row['CLASSEMENT_id'];
        endforeach;
        ?>
        <button type="submit" class="btn btn-primary" name="saveChanges">Enregistrer les modifications</button>
        <a href="CLASSEMENT.php">Retour</a>
    </div>
    </form>


    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        if (isset($_POST['delete_CLASSEMENTBOXE_id'])) {
            $delete_CLASSEMENTBOXE_id = $_POST['delete_CLASSEMENTBOXE_id'];

            // Supprimer le COMBATTANT de la base de données
            $sql = "DELETE FROM CLASSEMENTBOXE WHERE CLASSEMENTBOXE_id = :delete_CLASSEMENTBOXE_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':delete_CLASSEMENTBOXE_id', $delete_CLASSEMENTBOXE_id, PDO::PARAM_INT);
            $stmt->execute();

            // Rafraîchir la page pour refléter les changements
            header("Refresh:0");
        }
    }
}
?>



    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])) {
        if (isset($_POST['CLASSEMENTBOXE_id']) && isset($_POST['ranking'])) {
            $CLASSEMENTBOXE_ids = $_POST['CLASSEMENTBOXE_id'];
            $rankings = $_POST['ranking'];

            // Boucle à travers les données et mettre à jour les CLASSEMENTs dans la base de données
            for ($i = 0; $i < count($CLASSEMENTBOXE_ids); $i++) {
                $CLASSEMENTBOXE_id = $CLASSEMENTBOXE_ids[$i];
                $ranking = $rankings[$i];

                // Mettre à jour le CLASSEMENT dans la base de données
                $sql = "UPDATE CLASSEMENTBOXE SET ranking = :ranking WHERE CLASSEMENTBOXE_id = :CLASSEMENTBOXE_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':ranking', $ranking, PDO::PARAM_INT);
                $stmt->bindParam(':CLASSEMENTBOXE_id', $CLASSEMENTBOXE_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Rafraîchir la page pour afficher les modifications
            header("Location: back_CLASSEMENTBOXE.php");


        }
    }
    ?>
</body>
</html>
