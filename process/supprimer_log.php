<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_log'])) {
    $errorLogFile = '/var/log/apache2/error.log';
    if (file_exists($errorLogFile)) {
        $handle = fopen($errorLogFile, 'w');
        if ($handle !== false) {
            fclose($handle);
            header("Location: ../admin/erreur");
            exit;
        } else {
            echo '<div class="alert alert-danger" role="alert">Erreur : Impossible d\'ouvrir le fichier error.log pour écriture.</div>';
        }
    } else {
        echo '<div class="alert alert-warning" role="alert">Aucun fichier error.log trouvé.</div>';
    }
} else {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
?>
