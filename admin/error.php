<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage du fichier error.log</title>
    <!-- Inclusion de Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-danger text-white">
                Contenu du fichier error.log
            </div>
            <div class="card-body">
                <?php
                // Vérifier si le fichier error.log existe et est accessible en lecture
                if (file_exists('/var/log/apache2/error.log') && is_readable('/var/log/apache2/error.log')) {
                    // Lire le contenu du fichier error.log
                    $errorLog = file_get_contents('/var/log/apache2/error.log');
                    // Afficher le contenu du fichier error.log dans une balise pre
                    echo '<pre class="pre-scrollable bg-light p-3 border">';
                    echo htmlspecialchars($errorLog);
                    echo '</pre>';
                } else {
                    // Afficher un message d'erreur si le fichier error.log n'existe pas ou n'est pas accessible en lecture
                    echo '<div class="alert alert-danger" role="alert">';
                    echo 'Le fichier error.log n\'existe pas ou n\'est pas accessible en lecture.';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    <!-- Inclusion de Bootstrap JS (optionnel) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
