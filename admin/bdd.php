<?php
require_once '../require/config/config.php';
require_once '../require/sidebar.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $databases = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toutes les Bases de Données</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Toutes les Bases de Données</h2>
    <div class="list-group">
        <?php foreach ($databases as $database): ?>
            <a href="tables.php?db=<?php echo urlencode($database); ?>" class="list-group-item list-group-item-action"><?php echo $database; ?></a>
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-3">
        <a href="admin" class="btn btn-primary">Retour à la Page d'Administration</a>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
            $(document).ready(function() {
                $('.account-btn').click(function() {
                    $('.account-box').toggleClass('show');
                });
            });
        </script>

</body>
</html>
