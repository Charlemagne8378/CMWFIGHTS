    <?php
    require_once '../require/config/config.php';
    require_once '../require/sidebar.php';
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

                $stmt = $pdo->prepare("INSERT INTO CAPTCHA (question, answer) VALUES (?, ?)");

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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../style/sidebar.css">
    </head>

    <body>
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
                            $sql = "SELECT id, question, answer FROM CAPTCHA";
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
