<?php
require_once '../config/config.php';
session_start();

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] !== 'admin') {
    header('Location: ../auth/connexion.php');
    exit();
}
$message = $error = '';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["question"], $_POST["answer"])) {
            $question = htmlspecialchars(trim($_POST["question"]));
            $answer = htmlspecialchars(trim($_POST["answer"]));

            $stmt = $pdo->prepare("INSERT INTO captcha (question, answer) VALUES (?, ?)");

            if ($stmt->execute([$question, $answer])) {
                $message = "La question a été ajoutée avec succès.";
            } else {
                $error = "Erreur lors de l'ajout de la question.";
            }
        } else {
            $error = "Les données du formulaire sont invalides ou manquantes.";
        }
    }
} catch (PDOException $e) {
    $error = "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captcha</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    padding: 48px 0 0;
    box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.2);
    width: 280px;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
    justify-content: flex-start; /* Aligner le contenu en haut */
}

.sidebar .nav-link {
    color: #333;
    white-space: nowrap;
    transition: all 0.3s ease;
    align-self: center;
    margin-bottom: 1rem; /* Ajouter un espace entre les icônes */
}

.sidebar .nav-link i {
    margin-right: 0px;
}

.sidebar.collapsed {
    width: 60px;
}

.sidebar.collapsed .nav-link {
    padding-left: 15px;
    padding-right: 15px;
    font-size: 0;
    text-align: center;
}

.sidebar.collapsed .nav-link i {
    margin-right: 0;
    font-size: 18px;
}

.sidebar .nav-link.active {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}

.main-content {
    transition: margin-left 0.3s ease;
    margin-left: 280px;
}

.main-content.collapsed {
    margin-left: 60px;
}

.toggle-sidebar {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 1.5rem;
    z-index: 999;
    transition: all 0.3s ease;
    width: 30px; /* Ajuster la largeur de l'icône */
    height: 30px; /* Ajuster la hauteur de l'icône */
    display: flex;
    align-items: center;
    justify-content: center;
}

.toggle-sidebar i {
    width: 30px; /* Ajuster la largeur de l'icône */
    height: 30px; /* Ajuster la hauteur de l'icône */
    margin-top: -7px;
}

@media (max-width: 767px) {
    .sidebar {
        width: 100%;
    }

    .sidebar.collapsed {
        margin-left: -100%;
    }

    .main-content {
        margin-left: 0;
    }

    .toggle-sidebar {
        right: auto;
        left: -30px;
    }

    .toggle-sidebar.sidebar-hidden {
        left: 10px;
    }

    .sidebar .nav-link span {
        display: none;
    }
}

    </style>
</head>

<body>

    <nav class="sidebar">
        <button class="btn btn-primary btn-sm toggle-sidebar"><i class="bi bi-list"></i></button>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="accueil"><i class="bi bi-house-door"></i><span class="ml-2 d-none d-sm-inline">Accueil</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="billetterie"><i class="bi bi-ticket-perforated"></i><span class="ml-2 d-none d-sm-inline">Billetterie</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="captcha"><i class="bi bi-shield-lock"></i><span class="ml-2 d-none d-sm-inline">Captcha</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="classement"><i class="bi bi-graph-up"></i><span class="ml-2 d-none d-sm-inline">Classement</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="combattants"><i class="bi bi-people"></i><span class="ml-2 d-none d-sm-inline">Combattants</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="evenements"><i class="bi bi-calendar-event"></i><span class="ml-2 d-none d-sm-inline">Événements</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="image"><i class="bi bi-image"></i><span class="ml-2 d-none d-sm-inline">Image</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="modifier_utilisateur"><i class="bi bi-pencil-square"></i><span class="ml-2 d-none d-sm-inline">Modifier utilisateur</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="newsletters"><i class="bi bi-envelope"></i><span class="ml-2 d-none d-sm-inline">Newsletters</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="service_client"><i class="bi bi-telephone"></i><span class="ml-2 d-none d-sm-inline">Service client</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="utilisateurs"><i class="bi bi-person-lines-fill"></i><span class="ml-2 d-none d-sm-inline">Utilisateurs</span></a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link" href="../auth/logout"><i class="bi bi-box-arrow-left"></i><span class="ml-2 d-none d-sm-inline">Se déconnecter</span></a>
            </li>
        </ul>
    </nav>
    <div class="main-content">
        <div class="container">
            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <h1>Ajouter une question de captcha</h1>
            <form action="" method="post">
                <div class="form-group">
                    <label for="question">Question :</label>
                    <input type="text" name="question" id="question" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="answer">Réponse :</label>
                    <input type="text" name="answer" id="answer" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter la question</button>
            </form>

            <h2>Liste des questions de captcha</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Question</th>
                            <th>Réponse</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, question, answer FROM captcha";
                        $stmt = $pdo->query($sql);

                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . htmlspecialchars($row["question"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["answer"]) . "</td>";
                                echo '<td>
                                        <button class="btn btn-primary btn-sm modifier-question" data-toggle="modal" data-target="#modifierQuestionModal"
                                        data-id="' . $row["id"] . '" data-question="' . htmlspecialchars($row["question"]) . '" data-answer="' . htmlspecialchars($row["answer"]) . '">Modifier</button>
                                        <form action="../process/supprimer_question.php" method="post" style="display: inline-block;">
                                            <input type="hidden" name="question_id" value="' . $row["id"] . '">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette question ?\')">Supprimer</button>
                                        </form>
                                      </td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Aucune question trouvée</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="modifierQuestionModal" tabindex="-1" role="dialog" aria-labelledby="modifierQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modifierQuestionModalLabel">Modifier la question de captcha</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="modifierQuestionForm" action="../process/modifier_question.php" method="post">
                            <div class="form-group">
                                <label for="modal_question">Question :</label>
                                <input type="text" name="question" id="modal_question" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="modal_answer">Réponse :</label>
                                <input type="text" name="answer" id="modal_answer" class="form-control" required>
                            </div>
                            <input type="hidden" name="question_id" id="modal_question_id">
                            <button type="submit" class="btn btn-primary">Modifier la question</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.toggle-sidebar').click(function() {
                $('.sidebar').toggleClass('collapsed');
                $('.main-content').toggleClass('collapsed');
            });

            $('.modifier-question').click(function() {
                const questionId = $(this).data('id');
                const question = $(this).data('question');
                const answer = $(this).data('answer');

                $('#modal_question_id').val(questionId);
                $('#modal_question').val(question);
                $('#modal_answer').val(answer);
            });
        });
    </script>
</body>

</html>
