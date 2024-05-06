<?php
// Nombre d'entrées par page
$limit = 10;

// Calculer le numéro de page actuel
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Ouvrir le fichier de journal d'accès Apache en lecture
$file = fopen('/var/log/apache2/access.log', 'r');

// Lire les lignes du fichier et les stocker dans un tableau
$lines = [];
while (!feof($file)) {
    $line = fgets($file);
    $lines[] = explode(' ', $line);
}

// Fermer le fichier
fclose($file);

// Calculer le nombre total de pages
$total = count($lines);
$pages = ceil($total / $limit);

// Découper le tableau en fonction de la pagination
$lines = array_slice($lines, $start, $limit);

// Créer le tableau HTML dynamique
echo '<table border="1">';
echo '<thead><tr><th>Adresse IP</th><th>Date et heure</th><th>Méthode</th><th>URL</th><th>Protocole</th><th>Code de statut</th><th>Taille de la réponse</th><th>Agent utilisateur</th></tr></thead>';
echo '<tbody>';
foreach ($lines as $line) {
    // Extraire les données de la ligne
    $ip = $line[0];
    $dateTime = $line[3] . ' ' . $line[4];
    $method = $line[5];
    $url = $line[6];
    $protocol = $line[7];
    $statusCode = $line[8];
    $responseSize = $line[9];
    $userAgent = $line[11];

    // Afficher la ligne dans le tableau
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

// Afficher la pagination
echo '<div class="pagination">';
for ($i = 1; $i <= $pages; $i++) {
    if ($i == $page) {
        echo '<span>' . $i . '</span>';
    } else {
        echo '<a href="?page=' . $i . '">' . $i . '</a>';
    }
}
echo '</div>';
?>
