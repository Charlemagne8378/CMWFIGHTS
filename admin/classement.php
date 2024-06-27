<?php
require_once '../require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer la liste des combattants depuis la base de données
$sql_combattants = "SELECT combattant_id, nom FROM COMBATTANT";
$stmt_combattants = $pdo->query($sql_combattants);
$combattants = $stmt_combattants->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des catégories depuis la base de données
$sql_categories = "SELECT category_id, category_name FROM CATEGORIES";
$stmt_categories = $pdo->query($sql_categories);
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un combattant dans un classement</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Ajouter un combattant dans un classement</h2>
        <form action="../process/process_classement.php" method="post">
            <div class="form-group">
                <label>Combattant</label>
                <select name="combattant_id" class="form-control">
                    <?php foreach($combattants as $combattant): ?>
                        <option value="<?php echo $combattant['combattant_id']; ?>"><?php echo $combattant['nom']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Catégorie</label>
                <select name="category_id" class="form-control">
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Discipline</label>
                <select name="discipline" class="form-control">
                    <option value="mma">MMA</option>
                    <option value="boxe">Boxe</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ranking</label>
                <input type="number" name="ranking" class="form-control" min="0">
            </div>
            <input type="submit" class="btn btn-primary" value="Ajouter dans le classement">
            <a href="admin.php" class="btn btn-secondary">Annuler</a>
            
            <a href="back_classementmma.php" class="btn btn-primary">Modifier classement MMA</a>
            <a href="back_classementboxe.php" class="btn btn-primary">Modifier classement Boxe</a>
        </form>
    </div>
</body>
</html>
