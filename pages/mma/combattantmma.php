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
        }
        .category {
            margin-bottom: 30px;
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
            justify-content: space-between;
            margin: 150px;
        }
        .combattant-card {
            width: 300px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            margin-right: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .combattant-card p {
            margin-bottom: 10px;
            text-align: center
            color: white;
        }
        .combattant-card img {
            max-width: 100%;
            border-radius: 5px;
            margin-left: 100px;
        }
    </style>
</head>
<body>
    <?php
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "cmwfight75012";
    $dbname = "kiwi";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sélectionner toutes les catégories
        $stmt_categories = $pdo->query("SELECT * FROM Categories");
        $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

        // Afficher les combattants par catégorie
        foreach ($categories as $categorie) {
            echo '<div class="category">';
            echo '<h2>' . $categorie['category_name'] . '</h2>';
            echo '<div class="combattant-cards">';

            // Sélectionner les combattants de cette catégorie
            $stmt_combattants = $pdo->prepare("SELECT * FROM Combattant WHERE category_id = ?");
            $stmt_combattants->execute([$categorie['category_id']]);
            $combattants = $stmt_combattants->fetchAll(PDO::FETCH_ASSOC);

            // Afficher les cartes de combattant
            foreach ($combattants as $combattant) {
                echo '<div class="combattant-card">';
                echo '<img src="' . $combattant['image_url'] . '" alt="' . $combattant['nom'] . '">';
                echo '<p><strong>Nom: </strong>' . $combattant['nom'] . '</p>';
                echo '<p><strong>Âge: </strong>' . $combattant['age'] . '</p>';
                echo '<p><strong>Poids: </strong>' . $combattant['poids'] . '</p>';
                echo '<p><strong>Taille: </strong>' . $combattant['taille'] . '</p>';
                echo '<p><strong>Palmarès: </strong>' . $combattant['palmares'] . '</p>';
                echo '</div>';
            }

            echo '</div>'; // fin de combattant-cards
            echo '</div>'; // fin de la catégorie
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    ?>
</body>
</html>
