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
        <link rel="stylesheet" href="../style/sidebar.css">
    </head>

    <body>
    <nav class="sidebar">
        <div class="text-center mb-3">
            <img src="../Images/cmwnoir.png" alt="Logo" style="width: 128px; height: 128px;">
        </div>
        <a class="nav-link" href="admin">
            <i class="bi bi-house-door"></i>
            <span class="ml-2 d-none d-sm-inline">Admin</span>
        </a>
        <a class="nav-link" href="utilisateurs">
            <i class="bi bi-person-lines-fill"></i>
            <span class="ml-2 d-none d-sm-inline">Utilisateurs</span>
        </a>
        <a class="nav-link" href="evenements">
            <i class="bi bi-calendar-event"></i>
            <span class="ml-2 d-none d-sm-inline">Événements</span>
        </a>
        <a class="nav-link" href="modifier_utilisateur">
            <i class="bi bi-pencil-square"></i>
            <span class="ml-2 d-none d-sm-inline">Modifier le compte</span>
        </a>
        <a class="nav-link" href="classement">
            <i class="bi bi-bar-chart"></i>
            <span class="ml-2 d-none d-sm-inline">Classement</span>
        </a>
        <a class="nav-link" href="combattants">
            <i class="bi bi-people"></i>
            <span class="ml-2 d-none d-sm-inline">Combattants</span>
        </a>
        <a class="nav-link" href="candidature">
            <i class="bi bi-file-earmark-text"></i>
            <span class="ml-2 d-none d-sm-inline">Candidature</span>
        </a>
        <a class="nav-link" href="billetterie">
            <i class="bi bi-ticket"></i>
            <span class="ml-2 d-none d-sm-inline">Billetterie</span>
        </a>
        <a class="nav-link" href="service_client">
            <i class="bi bi-telephone"></i>
            <span class="ml-2 d-none d-sm-inline">Service Client</span>
        </a>
        <a class="nav-link" href="image">
            <i class="bi bi-image"></i>
            <span class="ml-2 d-none d-sm-inline">Image</span>
        </a>
        <a class="nav-link" href="newsletters">
            <i class="bi bi-envelope"></i>
            <span class="ml-2 d-none d-sm-inline">Newsletters</span>
        </a>
        <a class="nav-link active" href="captcha">
            <i class="bi bi-shield-lock"></i>
            <span class="ml-2 d-none d-sm-inline">Captcha</span>
        </a>
        <a class="nav-link" href="accueil">
            <i class="bi bi-house-door"></i>
            <span class="ml-2 d-none d-sm-inline">Accueil</span>
        </a>
        <a class="nav-link" href="logs">
        <i class="bi bi-journal"></i>
        <span class="ml-2 d-none d-sm-inline">Logs</span>
    </a>
    <a class="nav-link" href="permissions">
        <i class="bi bi-shield-lock"></i>
        <span class="ml-2 d-none d-sm-inline">Permissions utilisateurs</span>
    </a>
    <a class="nav-link" href="bdd">
        <i class="bi bi-gear"></i>
        <span class="ml-2 d-none d-sm-inline">Base de données</span>
    </a>

        <div class="account-box">
            <a href="../compte/settings">Paramètres</a>
            <a href="../auth/logout.php">Déconnexion</a>
        </div>
        <button class="btn btn-primary btn-block account-btn">
            Compte
        </button>
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
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.modifier-question').click(function() {
                    const questionId = $(this).data('id');
                    const question = $(this).data('question');
                    const answer = $(this).data('answer');

                    $('#modal_question_id').val(questionId);
                    $('#modal_question').val(question);
                    $('#modal_answer').val(answer);
                });

                $('.account-btn').click(function() {
                    $('.account-box').toggleClass('show');
                });
            });
        </script>
    </body>

    </html>
