<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage du fichier error.log</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
    <style>
        .pre-scrollable {
            max-height: 70vh;
            overflow-y: auto;
        }
        .card {
            margin: 20px auto;
            max-width: 95%;
            margin-left: 2.5%;
        }
        .card-footer {
            display: flex;
            justify-content: flex-end;
        }
        @media (max-width: 768px) {
            .pre-scrollable {
                max-height: 50vh;
            }
            .card {
                max-width: 90%;
                margin-left: 5%;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-3">
                <?php require_once '../require/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        Contenu du fichier error.log
                    </div>
                    <div class="card-body">
                        <?php
                        if (file_exists('/var/log/apache2/error.log') && is_readable('/var/log/apache2/error.log')) {
                            $errorLog = file_get_contents('/var/log/apache2/error.log');
                            echo '<pre class="pre-scrollable bg-light p-3 border" style="color: #000000;">';
                            echo htmlspecialchars($errorLog);
                            echo '</pre>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">';
                            echo 'Le fichier error.log n\'existe pas ou n\'est pas accessible en lecture.';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <div class="card-footer">
                        <form action="../process/supprimer_erreur.php" method="post">
                            <button type="submit" class="btn btn-danger" name="delete_log">Supprimer le contenu du fichier error.log</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleAccountBox() {
        var accountBox = document.querySelector('.account-box');
        accountBox.classList.toggle('show');
    }

    document.addEventListener('DOMContentLoaded', function() {
        var accountBtn = document.querySelector('.account-btn');
        accountBtn.addEventListener('click', toggleAccountBox);
    });
</script>
</body>
</html>
