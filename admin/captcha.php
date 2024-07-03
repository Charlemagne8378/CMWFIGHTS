<?php
require_once '../require/config/config.php';
require_once '../require/sidebar/sidebar.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] !== 'admin') {
    header('Location: ../auth/connexion.php');
    exit();
}

$message = $error = '';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["csrf_token"], $_POST["question"], $_POST["answer"]) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $question = trim($_POST["question"]);
            $answer = trim($_POST["answer"]);

            if (!empty($question) && !empty($answer)) {
                $stmt = $pdo->prepare("INSERT INTO CAPTCHA (question, answer) VALUES (:question, :answer)");
                $stmt->bindParam(':question', $question);
                $stmt->bindParam(':answer', $answer);

                if ($stmt->execute()) {
                    $message = "La question a été ajoutée avec succès.";
                } else {
                    $error = "Erreur lors de l'ajout de la question : " . $stmt->errorInfo()[2];
                }
            } else {
                $error = "Les champs de question et de réponse ne peuvent pas être vides.";
            }
        } else {
            $error = "Les données du formulaire sont invalides ou manquantes.";
        }
    }
} catch (PDOException $e) {
    error_log("Erreur de connexion à la base de données : " . $e->getMessage());
    $error = "Une erreur est survenue. Veuillez réessayer plus tard.";
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$sql_count = "SELECT COUNT(*) FROM CAPTCHA";
$total_results = $pdo->query($sql_count)->fetchColumn();
$total_pages = ceil($total_results / $limit);

$sql = "SELECT id, question, answer FROM CAPTCHA LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
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
    <div class="d-flex">
        <div class="main-content flex-grow-1 p-4 mx-auto">
            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <h1 class="text-center">Ajouter une question de captcha</h1>
            <form action="" method="post" class="col-md-8 mx-auto">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group mb-3">
                    <label for="question">Question :</label>
                    <input type="text" name="question" id="question" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="answer">Réponse :</label>
                    <input type="text" name="answer" id="answer" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter la question</button>
            </form>

            <h2 class="text-center mt-4">Liste des questions de captcha</h2>
            <div class="table-responsive col-md-8 mx-auto">
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
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . htmlspecialchars($row["question"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["answer"]) . "</td>";
                                echo '<td>
                                        <a href="../process/captcha/modifier_question.php?id=' . $row["id"] . '" class="btn btn-primary btn-sm">Modifier</a>
                                        <form action="../process/supprimer_question.php" method="post" style="display: inline-block;">
                                            <input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">
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
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
    <script src="../scripts/compte.js"></script>
</body>

</html>
