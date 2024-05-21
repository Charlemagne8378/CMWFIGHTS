<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Apache Log</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <?php
    $limit = 20;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    $file = fopen('/var/log/apache2/access.log', 'r');
    $lines = [];
    while (!feof($file)) {
        $line = fgets($file);
        $lines[] = explode(' ', $line);
    }
    fclose($file);

    $total = count($lines);
    $pages = ceil($total / $limit);
    $lines = array_slice($lines, $start, $limit);

    echo '<table class="table table-bordered table-striped text-center">';
    echo '<thead class="thead-dark"><tr><th>Adresse IP</th><th>Date et heure</th><th>Méthode</th><th>URL</th><th>Protocole</th><th>Code de statut</th><th>Taille de la réponse</th><th>Agent utilisateur</th></tr></thead>';
    echo '<tbody>';
    foreach ($lines as $line) {
        $ip = $line[0];
        $dateTime = $line[3] . ' ' . $line[4];
        $method = $line[5];
        $url = $line[6];
        $protocol = $line[7];
        $statusCode = $line[8];
        $responseSize = $line[9];
        $userAgent = $line[11];

        echo '<tr>';
        echo '<td>' . htmlspecialchars($ip) . '</td>';
        echo '<td>' . htmlspecialchars($dateTime) . '</td>';
        echo '<td>' . htmlspecialchars($method) . '</td>';
        echo '<td>' . htmlspecialchars($url) . '</td>';
        echo '<td>' . htmlspecialchars($protocol) . '</td>';
        echo '<td>' . htmlspecialchars($statusCode) . '</td>';
        echo '<td>' . htmlspecialchars($responseSize) . '</td>';
        echo '<td>' . htmlspecialchars($userAgent) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';

    echo '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    // Bouton pour la première page
    if ($page > 1) {
        echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
    }

    // Affichage des boutons de pagination centrés autour de la page actuelle
    $range = 3; // Nombre de boutons à afficher de chaque côté de la page actuelle
    $startRange = max(2, $page - $range);
    $endRange = min($pages - 1, $page + $range);
    
    for ($i = $startRange; $i <= $endRange; $i++) {
        if ($i == $page) {
            echo '<li class="page-item active"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
    }

    // Bouton pour la dernière page
    if ($page < $pages) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . $pages . '">' . $pages . '</a></li>';
    }

    echo '</ul></nav>';
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
