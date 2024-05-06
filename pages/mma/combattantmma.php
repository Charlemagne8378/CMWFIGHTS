<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des combattants par catégorie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background-color: black;
            margin-top: 150px;
        }
        .category {
            margin-bottom: 30px;
        }
        h1{
            color: white;
            text-align: center;
            margin-bottom: 50px;
        }
        h2{
            font-size: 24px;
            margin-bottom: 10px;
            color: white;
            margin: 50px;
            text-align: center;
        }
        .combattant-cards {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        .combattant-card {
            width: 300px;
            background-color: #2F4F4F;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin: 35px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .combattant-card p  {
            margin-bottom: 10px;
            text-align: center;
            color: white;
        }
        .combattant-card img {
            max-width: 100%;
            border-radius: 5px;
            
        }
    </style>
</head>
<body>
    <?php include'../../header.php' ?>
    <h1>Liste des combattants</h1>
    <?php
   
    $servername = "localhost";
    $username = "root";
    $password = "cmwfight75012";
    $dbname = "kiwi";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $stmt_categories = $pdo->query("SELECT * FROM Categories");
        $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

        
        foreach ($categories as $categorie) {
            echo '<div class="category">';
            echo '<h2>' . $categorie['category_name'] . '</h2>';
            echo '<div class="combattant-cards">';

            
            $stmt_combattants = $pdo->prepare("SELECT * FROM Combattant WHERE category_id = ?");
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
