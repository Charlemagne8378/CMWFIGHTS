<?php
require_once '../require/config/config.php';
require_once '../require/sidebar.php';

session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

if (isset($_GET['db']) && isset($_GET['table'])) {
    $db = $_GET['db'];
    $table = $_GET['table'];

    $sort_column = isset($_GET['sort']) ? $_GET['sort'] : getFirstColumn($pdo, $table);
    $sort_order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

    $limit = 15;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM $table ORDER BY $sort_column $sort_order LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_rows = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        $total_pages = ceil($total_rows / $limit);
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
}

function getFirstColumn($pdo, $table) {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM $table");
    $stmt->execute();
    $column = $stmt->fetch(PDO::FETCH_ASSOC);
    return $column['Field'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table <?php echo htmlspecialchars($table); ?></title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
    <style>
        th a {
            color: inherit;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        th a .fas {
            margin-left: 5px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Table <?php echo htmlspecialchars($table); ?></h2>
    <?php if (isset($data) && count($data) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <?php foreach (array_keys($data[0]) as $column): ?>
                            <th>
                                <a href="?db=<?php echo urlencode($db); ?>&table=<?php echo urlencode($table); ?>&sort=<?php echo urlencode($column); ?>&order=<?php echo $sort_order == 'ASC' ? 'desc' : 'asc'; ?>">
                                    <?php echo htmlspecialchars($column); ?>
                                    <?php if ($sort_column == $column): ?>
                                        <i class="fas fa-sort-<?php echo $sort_order == 'ASC' ? 'up' : 'down'; ?>"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?db=<?php echo urlencode($db); ?>&table=<?php echo urlencode($table); ?>&page=<?php echo $i; ?>&sort=<?php echo urlencode($sort_column); ?>&order=<?php echo urlencode($sort_order); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php else: ?>
        <p class="text-center">Aucune donnée trouvée dans la table <?php echo htmlspecialchars($table); ?></p>
    <?php endif; ?>
    <div class="text-center mt-3">
        <a href="tables?db=<?php echo urlencode($db); ?>" class="btn btn-primary">Retour à la Liste des Tables</a>
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
