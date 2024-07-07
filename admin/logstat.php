<?php
$logFile = '/var/log/apache2/access.log';
require_once '../require/sidebar/sidebar.php';

if (!file_exists($logFile)) {
    die("Le fichier de log n'existe pas.\n");
}

$logContent = file($logFile);

$stats = [
    'requests_per_ip' => [],
    'requests_per_status' => [],
    'most_requested_pages' => [],
    'hourly_requests' => [],
    'unique_visitors' => [],
];

$currentDate = strtotime('now');

foreach ($logContent as $line) {
    preg_match('/^(\S+) \S+ \S+ \[.*?\] "(\S+) (\S+) \S+" (\d+) \d+/', $line, $matches);

    if (count($matches) !== 5) {
        continue;
    }

    list(, $ip, $method, $page, $status) = $matches;

    if (!isset($stats['requests_per_ip'][$ip])) {
        $stats['requests_per_ip'][$ip] = 0;
    }
    $stats['requests_per_ip'][$ip]++;

    if (!isset($stats['requests_per_status'][$status])) {
        $stats['requests_per_status'][$status] = 0;
    }
    $stats['requests_per_status'][$status]++;

    if (!isset($stats['most_requested_pages'][$page])) {
        $stats['most_requested_pages'][$page] = 0;
    }
    $stats['most_requested_pages'][$page]++;

    preg_match('/^\S+ \S+ \S+ \[(\d+\/\w+\/\d+):(\d+):\d+:\d+ \S+\]/', $line, $time_matches);

    if (count($time_matches) !== 3) {
        continue;
    }

    list(, $date, $hour) = $time_matches;

    $hourly = date('Y-m-d H:00:00', strtotime("$date $hour:00:00"));
    if (!isset($stats['hourly_requests'][$hourly])) {
        $stats['hourly_requests'][$hourly] = 0;
    }
    $stats['hourly_requests'][$hourly]++;

    $visitor = $ip . '-' . date('Y-m-d', strtotime($date));
    if (!isset($stats['unique_visitors'][$visitor])) {
        $stats['unique_visitors'][$visitor] = true;
    }
}

$stats['requests_per_ip'] = array_slice($stats['requests_per_ip'], 0, 20, true);
$stats['requests_per_status'] = array_slice($stats['requests_per_status'], 0, 20, true);
$stats['most_requested_pages'] = array_slice($stats['most_requested_pages'], 0, 20, true);

$hourly_stats = [];
foreach ($stats['hourly_requests'] as $hourly => $count) {
    $hourly_stats[] = ['heure' => $hourly, 'nombre' => $count];
}
usort($hourly_stats, function ($a, $b) {
    return strtotime($a['heure']) - strtotime($b['heure']);
});
$hourly_stats = array_slice($hourly_stats, 0, 20);

$num_unique_visitors = count($stats['unique_visitors']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Logs Apache</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../style/sidebar.css">
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
    </div>
</div>
</body>
</html>
