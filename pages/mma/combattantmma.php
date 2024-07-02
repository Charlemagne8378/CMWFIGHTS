<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des combattants par catégorie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../style/combattantmma.css">
</head>
<body>
    <?php include'../../header.php'; ?>
    <h1>Liste des combattants</h1>
    <?php
    include '../../require/config/config.php';
    
    try {
        $stmt_categories = $pdo->query("SELECT * FROM CATEGORIES");
        $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categories as $categorie) {
            echo '<div class="category">';
            echo '<h2>' . $categorie['category_name'] . '</h2>';
            echo '<div class="combattant-cards">';

            $stmt_combattants = $pdo->prepare("SELECT * FROM COMBATTANT WHERE category_id = ?");
            $stmt_combattants->execute([$categorie['category_id']]);
            $combattants = $stmt_combattants->fetchAll(PDO::FETCH_ASSOC);

            foreach ($combattants as $combattant) {
                echo '<div class="combattant-card">';
                echo '<img src="' . $combattant['image_url'] . '" alt="' . $combattant['nom'] . '">';
                echo '<p><strong>' . $combattant['nom'] . '</strong></p>';
                echo '<p><strong>Âge: </strong>' . $combattant['age'] . ' ans </p>';
                echo '<p><strong>Poids: </strong>' . $combattant['poids'] . 'kg </p>';
                echo '<p><strong>Taille: </strong>' . $combattant['taille'] . 'cm </p>';
                echo '<p><strong>Palmarès Boxe: </strong>' . $combattant['palmares_boxe'] . '</p>';
                echo '<p><strong>Palmarès MMA: </strong>' . $combattant['palmares_mma'] . '</p>';
                echo '<p><strong>Discipline: </strong>';
                if ($combattant['discipline_id'] == 1) {
                    echo 'Boxe';
                } elseif ($combattant['discipline_id'] == 2) {
                    echo 'MMA';
                } elseif ($combattant['discipline_id'] == 3) {
                    echo 'Boxe et MMA';
                }
                echo '</p>';
                echo '</div>';
            }

            echo '</div>';
            echo '</div>'; 
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    ?>
</body>
</html>
