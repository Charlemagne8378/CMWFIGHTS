<?php
require_once '../../require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les données des classements MMA depuis la base de données
$sql = "SELECT CLASSEMENTMMA.classementmma_id, CLASSEMENTMMA.classement_id, CLASSEMENTMMA.combattant_id, CLASSEMENTMMA.ranking, CLASSEMENT.classement_name AS classement_name, COMBATTANT.nom AS combattant_name, COMBATTANT.palmares_mma AS combattant_palmares, COMBATTANT.image_url AS combattant_photo
FROM CLASSEMENTMMA
JOIN CLASSEMENT ON CLASSEMENTMMA.classement_id = CLASSEMENT.classement_id
JOIN COMBATTANT ON CLASSEMENTMMA.combattant_id = COMBATTANT.combattant_id
ORDER BY CLASSEMENTMMA.classement_id ASC, 
         CASE WHEN CLASSEMENTMMA.ranking = 0 THEN 0 ELSE CAST(CLASSEMENTMMA.ranking AS UNSIGNED) END ASC";

$stmt = $pdo->query($sql);
$classementmma = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement MMA</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../style/classementboxe.css">
</head>
<body>
    <?php include '../../header.php' ?>
    <div class="wrapper">
        <h2 class="text-center">Classement MMA</h2>
        <?php
        $prev_classement_id = null;
        $champion = null;

        foreach ($classementmma as $row):
            if ($row['classement_id'] != $prev_classement_id):
                if ($prev_classement_id !== null):
                    echo '</tbody></table>';
                endif;

                echo '<div class="section-title">' . $row['classement_name'] . '</div>';
                
                if ($row['ranking'] == 0):
                    echo '<div class="text-center"><img src="' . $row['combattant_photo'] . '" class="champion-photo" alt="Champion"><br><h3>' . $row['combattant_name'] . '</h3></div>';
                endif;

                echo '<table class="table table-dark table-striped">';
                echo '<thead><tr><th>Ranking</th><th>Combattant</th><th>Palmarès</th></tr></thead><tbody>';
                
                $prev_classement_id = $row['classement_id'];
            endif;

            if ($row['ranking'] != 0):
        ?>
        <tr>
            <td><?php echo $row['ranking']; ?></td>
            <td><?php echo $row['combattant_name']; ?></td>
            <td><?php echo $row['combattant_palmares']; ?></td>
        </tr>
        <?php
            endif;
        endforeach;
        if ($prev_classement_id !== null):
            echo '</tbody></table>';
        endif;
        ?>
    </div>
</body>
</html>
