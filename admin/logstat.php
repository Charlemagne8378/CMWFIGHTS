<?php
// Fichier de log Apache à analyser
$logFile = '/var/log/apache2/access.log';

// Vérifiez si le fichier de log existe
if (!file_exists($logFile)) {
    die("Le fichier de log n'existe pas.\n");
}

// Lire le fichier de log
$logContent = file($logFile);

// Initialiser les statistiques
$stats = [
    'requests_per_ip' => [],
    'requests_per_status' => [],
    'most_requested_pages' => [],
    'hourly_requests' => [],
    'unique_visitors' => [],
];

// Date et heure actuelle
$currentDate = strtotime('now');

// Analyser chaque ligne du fichier de log
foreach ($logContent as $line) {
    // Exemple de ligne de log Apache
    // 127.0.0.1 - - [01/Jan/2020:12:00:00 +0000] "GET /index.html HTTP/1.1" 200 2326
    preg_match('/^(\S+) \S+ \S+ \[.*?\] "(\S+) (\S+) \S+" (\d+) \d+/', $line, $matches);

    if (count($matches) !== 5) {
        continue; // Ligne de log mal formée
    }

    list(, $ip, $method, $page, $status) = $matches;

    // Compter les requêtes par IP
    if (!isset($stats['requests_per_ip'][$ip])) {
        $stats['requests_per_ip'][$ip] = 0;
    }
    $stats['requests_per_ip'][$ip]++;

    // Compter les requêtes par statut HTTP
    if (!isset($stats['requests_per_status'][$status])) {
        $stats['requests_per_status'][$status] = 0;
    }
    $stats['requests_per_status'][$status]++;

    // Compter les pages les plus demandées
    if (!isset($stats['most_requested_pages'][$page])) {
        $stats['most_requested_pages'][$page] = 0;
    }
    $stats['most_requested_pages'][$page]++;

    // Analyser l'heure de la requête
    preg_match('/^\S+ \S+ \S+ \[(\d+\/\w+\/\d+):(\d+):\d+:\d+ \S+\]/', $line, $time_matches);

    if (count($time_matches) !== 3) {
        continue; // Ligne de log mal formée
    }

    list(, $date, $hour) = $time_matches;

    // Compter les requêtes par heure
    $hourly = date('Y-m-d H:00:00', strtotime("$date $hour:00:00"));
    if (!isset($stats['hourly_requests'][$hourly])) {
        $stats['hourly_requests'][$hourly] = 0;
    }
    $stats['hourly_requests'][$hourly]++;

    // Compter les visiteurs uniques par jour
    $visitor = $ip . '-' . date('Y-m-d', strtotime($date));
    if (!isset($stats['unique_visitors'][$visitor])) {
        $stats['unique_visitors'][$visitor] = true;
    }
}

// Limiter les tableaux à 20 éléments maximum
$stats['requests_per_ip'] = array_slice($stats['requests_per_ip'], 0, 20, true);
$stats['requests_per_status'] = array_slice($stats['requests_per_status'], 0, 20, true);
$stats['most_requested_pages'] = array_slice($stats['most_requested_pages'], 0, 20, true);

// Statistiques sur les requêtes par heure
$hourly_stats = [];
foreach ($stats['hourly_requests'] as $hourly => $count) {
    $hourly_stats[] = ['heure' => $hourly, 'nombre' => $count];
}
usort($hourly_stats, function ($a, $b) {
    return strtotime($a['heure']) - strtotime($b['heure']);
});
$hourly_stats = array_slice($hourly_stats, 0, 20);

// Nombre de visiteurs uniques sur la période
$num_unique_visitors = count($stats['unique_visitors']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Logs Apache</title>
    <!-- Styles Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Styles personnalisés -->
    <style>
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="my-4 text-center">Statistiques des Logs Apache</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Requêtes par IP
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>IP</th>
                                <th>Nombre de Requêtes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['requests_per_ip'] as $ip => $count): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ip); ?></td>
                                    <td><?php echo htmlspecialchars($count); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Requêtes par Statut HTTP
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Statut HTTP</th>
                                <th>Nombre de Requêtes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['requests_per_status'] as $status => $count): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($status); ?></td>
                                    <td><?php echo htmlspecialchars($count); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Heures de Pointe (Requêtes par Heure)
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="hourlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Nombre de Visiteurs Uniques
                </div>
                <div class="card-body">
                    <p><?php echo $num_unique_visitors; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Pages les Plus Demandées
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Nombre de Requêtes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['most_requested_pages'] as $page => $count): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($page); ?></td>
                                    <td><?php echo htmlspecialchars($count); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts Bootstrap et Chart.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script pour le graphique des heures de pointe -->
<script>
    var ctx = document.getElementById('hourlyChart').getContext('2d');
    var hourlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($hourly_stats, 'heure')); ?>,
            datasets: [{
                label: 'Requêtes par Heure',
                data: <?php echo json_encode(array_column($hourly_stats, 'nombre')); ?>,
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Heure'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Nombre de Requêtes'
                    }
                }
            }
        }
    });
</script>
</body>
</html>
