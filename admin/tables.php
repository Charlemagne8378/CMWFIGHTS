<?php
require_once '../require/config/config.php';
require_once '../require/sidebar/sidebar.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}
if (isset($_GET['db'])) {
    $db = $_GET['db'];
    
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Récupérer la liste des tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        exit();
    }
} else {
    echo "Base de données non spécifiée.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tables dans <?php echo htmlspecialchars($db); ?></title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Tables dans <?php echo htmlspecialchars($db); ?></h2>
    <div class="list-group">
        <?php foreach ($tables as $table): ?>
            <a href="voir_table.php?db=<?php echo urlencode($db); ?>&table=<?php echo urlencode($table); ?>" class="list-group-item list-group-item-action"><?php echo htmlspecialchars($table); ?></a>
        <?php endforeach; ?>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Supprimer une Table</h5>
                    <form action="../process/bdd/supprimer_table.php" method="POST">
                        <input type="hidden" name="db" value="<?php echo htmlspecialchars($db); ?>">
                        <div class="mb-3">
                            <label for="deleteTable" class="form-label">Sélectionnez une table à supprimer</label>
                            <select class="form-select" id="deleteTable" name="tableToDelete" required>
                                <option value="" selected disabled>Choisissez une table</option>
                                <?php foreach ($tables as $table): ?>
                                    <option value="<?php echo htmlspecialchars($table); ?>"><?php echo htmlspecialchars($table); ?></option>
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
                    <h5 class="card-title">Créer une Nouvelle Table</h5>
                    <form action="../process/bdd/creer_table.php" method="POST">
                        <input type="hidden" name="db" value="<?php echo htmlspecialchars($db); ?>">
                        <div class="mb-3">
                            <label for="newTableName" class="form-label">Nom de la Nouvelle Table</label>
                            <input type="text" class="form-control" id="newTableName" name="newTableName" required>
                        </div>
                        <button type="submit" class="btn btn-success">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="bdd.php" class="btn btn-primary">Retour à la Liste des Bases de Données</a>
    </div>
</div>
<script src="../scripts/compte.js"></script>
</body>
</html>
