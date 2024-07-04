<?php
require_once '../require/config/config.php';
require_once '../require/sidebar/sidebar.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query('SHOW DATABASES');
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toutes les Bases de Données</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Toutes les Bases de Données</h2>
    <div class="list-group">
        <?php foreach ($databases as $database): ?>
            <a href="tables.php?db=<?php echo urlencode($database); ?>" class="list-group-item list-group-item-action"><?php echo htmlspecialchars($database); ?></a>
        <?php endforeach; ?>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Supprimer une Base de Données</h5>
                    <form action="../process/bdd/supprimer_bdd.php" method="POST">
                        <div class="mb-3">
                            <label for="deleteDatabase" class="form-label">Sélectionnez une base de données à supprimer</label>
                            <select class="form-select" id="deleteDatabase" name="databaseToDelete" required>
                                <option value="" selected disabled>Choisissez une base de données</option>
                                <?php foreach ($databases as $database): ?>
                                    <option value="<?php echo htmlspecialchars($database); ?>"><?php echo htmlspecialchars($database); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Créer une Nouvelle Base de Données</h5>
                    <form action="../process/bdd/creer_bdd.php" method="POST">
                        <div class="mb-3">
                            <label for="newDatabaseName" class="form-label">Nom de la Nouvelle Base de Données</label>
                            <input type="text" class="form-control" id="newDatabaseName" name="newDatabaseName" required>
                        </div>
                        <button type="submit" class="btn btn-success">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../scripts/compte.js"></script>
</body>
</html>
