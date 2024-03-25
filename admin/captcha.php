<?php
require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["question"]) && isset($_POST["answer"])) {
        $question = htmlspecialchars(strip_tags(trim($_POST["question"])));
        $answer = htmlspecialchars(strip_tags(trim($_POST["answer"])));

        $sql = "INSERT INTO captcha (question, answer) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);

        if ($stmt) {
            $stmt->execute([$question, $answer]);
            $message = "La question a été ajoutée avec succès.";
        } else {
            $error = "Erreur lors de la préparation de la requête : " . $pdo->error;
        }
    } else {
        $error = "Les données du formulaire sont invalides ou manquantes.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captcha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /**/
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
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
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Réponse</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, question, answer FROM captcha";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["question"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["answer"]) . "</td>";
                        echo '<td><form action="../process/supprimer_question.php" method="post"><input type="hidden" name="question_id" value="' . $row["id"] . '"><button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette question ?\')">Supprimer</button></form></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucune question trouvée</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt = null;
$pdo = null;
?>
