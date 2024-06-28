<?php
require_once '../require/config/config.php';
require_once '../require/sidebar.php';

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
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tables dans <?php echo $db ?? 'la Base de Données'; ?></title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Tables dans <?php echo $db ?? 'la Base de Données'; ?></h2>
    <div class="list-group">
        <?php foreach ($tables as $table): ?>
            <a href="voir_table?db=<?php echo urlencode($db); ?>&table=<?php echo urlencode($table); ?>" class="list-group-item list-group-item-action"><?php echo $table; ?></a>
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-3">
        <a href="bdd" class="btn btn-primary">Retour à la Liste des Bases de Données</a>
    </div>
</div>
<script>
            $(document).ready(function() {
                $('.account-btn').click(function() {
                    $('.account-box').toggleClass('show');
                });
            });
</script>
</body>
</html>
