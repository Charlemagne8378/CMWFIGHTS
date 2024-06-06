<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Apache Log</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
</head>
<body>
<div class="container mt-5">
    <?php
    $limit = 20;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;
    $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'date';
    $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'desc';

    function compareByDate($a, $b, $order) {
        $dateA = strtotime(trim($a[3], '[]') . ' ' . trim($a[4], '[]'));
        $dateB = strtotime(trim($b[3], '[]') . ' ' . trim($b[4], '[]'));
        return ($order == 'asc') ? $dateA - $dateB : $dateB - $dateA;
    }

    $file = fopen('/var/log/apache2/access.log', 'r');
    $totalLines = 0;
    while (!feof($file)) {
        fgets($file);
        $totalLines++;
    }
    fclose($file);

    $pages = ceil($totalLines / $limit);
    $file = fopen('/var/log/apache2/access.log', 'r');
    $lines = [];
    $currentLine = 0;

    while (!feof($file) && count($lines) < $limit) {
        $line = fgets($file);
        if ($currentLine >= $start && $currentLine < $start + $limit) {
            if ($line) {
                $lines[] = explode(' ', $line);
            }
        }
        $currentLine++;
    }
    fclose($file);

    if ($sortColumn == 'date') {
        usort($lines, function($a, $b) use ($sortOrder) {
            return compareByDate($a, $b, $sortOrder);
        });
    }

    echo '<table class="table table-bordered table-striped text-center">';
    echo '<thead class="thead-dark">';
    echo '<tr>';
    echo '<th><a href="?sort=ip&order='.($sortOrder == 'asc' ? 'desc' : 'asc').'&page='.$page.'">Adresse IP</a></th>';
    echo '<th><a href="?sort=date&order='.($sortOrder == 'asc' ? 'desc' : 'asc').'&page='.$page.'">Date et heure</a></th>';
    echo '<th>Méthode</th>';
    echo '<th>URL</th>';
    echo '<th>Protocole</th>';
    echo '<th>Code de statut</th>';
    echo '<th>Taille de la réponse</th>';
    echo '<th>Agent utilisateur</th>';
    echo '</tr>';
    echo '</thead>';
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

    echo '<canvas id="logsChart" width="800" height="400"></canvas>';

    echo '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    if ($page > 1) {
        echo '<li class="page-item"><a class="page-link" href="?page=1&sort='.$sortColumn.'&order='.$sortOrder.'">1</a></li>';
    }

    $range = 3;
    $startRange = max(2, $page - $range);
    $endRange = min($pages - 1, $page + $range);
    
    for ($i = $startRange; $i <= $endRange; $i++) {
        if ($i == $page) {
            echo '<li class="page-item active"><a class="page-link" href="?page=' . $i . '&sort='.$sortColumn.'&order='.$sortOrder.'">' . $i . '</a></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '&sort='.$sortColumn.'&order='.$sortOrder.'">' . $i . '</a></li>';
        }
    }
    if ($page < $pages) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . $pages . '&sort='.$sortColumn.'&order='.$sortOrder.'">' . $pages . '</a></li>';
    }

    echo '</ul></nav>';
    echo '<form class="form-inline justify-content-center" method="GET" action="">';
    echo '<input type="hidden" name="sort" value="'.$sortColumn.'">';
    echo '<input type="hidden" name="order" value="'.$sortOrder.'">';
    echo '<input type="number" class="form-control mb-2 mr-sm-2" name="page" placeholder="Numéro" min="1" max="'.$pages.'">';
    echo '<button type="submit" class="btn btn-primary mb-2">Aller à la page</button>';
    echo '</form>';
    ?>
</div>

<script>
    // Fonction pour dessiner le graphique
    function drawChart() {
        var canvas = document.getElementById('logsChart');
        var ctx = canvas.getContext('2d');

        // Vous pouvez remplacer ces données factices par les données réelles que vous souhaitez afficher dans le graphique
        var labels = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet'];
        var data = [65, 59, 80, 81, 56, 55, 40];
        var maxValue = Math.max(...data);

        // Définir les dimensions du graphique
        var chartWidth = canvas.width - 100;
        var chartHeight = canvas.height - 100;

        // Dessiner les axes
        ctx.beginPath();
        ctx.moveTo(50, 50);
        ctx.lineTo(50, canvas.height - 50);
        ctx.lineTo(canvas.width - 50, canvas.height - 50);
        ctx.stroke();

        // Dessiner les étiquettes sur l'axe des X
        var xLabelStep = chartWidth / (labels.length - 1);
        for (var i = 0; i < labels.length; i++) {
            ctx.fillText(labels[i], 50 + i * xLabelStep, canvas.height - 30);
        }

        // Dessiner les étiquettes sur l'axe des Y
        var yLabelStep = chartHeight / 5;
        for (var j = 0; j <= 5; j++) {
            var value = Math.round(maxValue * (5 - j) / 5);
            ctx.fillText(value.toString(), 20, 50 + j * yLabelStep);
        }

        // Dessiner les barres du graphique
        var barWidth = chartWidth / labels.length;
        for (var k = 0; k < data.length; k++) {
            var barHeight = chartHeight * (data[k] / maxValue);
            ctx.fillStyle = 'rgb(75, 192, 192)';
            ctx.fillRect(50 + k * barWidth, canvas.height - 50 - barHeight, barWidth, barHeight);
        }
    }

    // Appel de la fonction pour dessiner le graphique
    drawChart();
</script>

</body>
</html>

