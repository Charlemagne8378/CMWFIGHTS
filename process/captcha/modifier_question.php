<?php
require_once '../../require/config/config.php';


session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../require/sidebar/sidebar_process.php';
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] !== 'admin') {
    header('Location: ../../auth/connexion.php');
    exit();
}

$message = $error = '';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["csrf_token"], $_POST["question_id"], $_POST["question"], $_POST["answer"]) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $question_id = intval($_POST["question_id"]);
            $question = htmlspecialchars(trim($_POST["question"]));
            $answer = htmlspecialchars(trim($_POST["answer"]));

            if (!empty($question) && !empty($answer)) {
                $stmt = $pdo->prepare("UPDATE CAPTCHA SET question = :question, answer = :answer WHERE id = :id");
                $stmt->bindParam(':question', $question);
                $stmt->bindParam(':answer', $answer);
                $stmt->bindParam(':id', $question_id);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "La question a été mise à jour avec succès.";
                    header('Location: ../../admin/captcha');
                    exit();
                } else {
                    $error = "Erreur lors de la mise à jour de la question.";
                }
            } else {
                $error = "Les champs de question et de réponse ne peuvent pas être vides.";
            }
        } else {
            $error = "Les données du formulaire sont invalides ou manquantes.";
        }
    } else {
        if (isset($_GET["id"])) {
            $question_id = intval($_GET["id"]);
            $stmt = $pdo->prepare("SELECT id, question, answer FROM CAPTCHA WHERE id = :id");
            $stmt->bindParam(':id', $question_id);
            $stmt->execute();
            $question_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$question_data) {
                $error = "Question non trouvée.";
            }
        } else {
            $error = "ID de question non spécifié.";
        }
    }
} catch (PDOException $e) {
    error_log("Erreur de connexion à la base de données : " . $e->getMessage());
    $error = "Une erreur est survenue. Veuillez réessayer plus tard.";
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Question - Captcha</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../style/sidebar.css">
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

        <?php if (isset($question_data)): ?>
            <h1 class="text-center">Modifier une question de captcha</h1>
            <form action="" method="post" class="col-md-8 mx-auto">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="question_id" value="<?php echo $question_data['id']; ?>">
                <div class="form-group mb-3">
                    <label for="question">Question :</label>
                    <input type="text" name="question" id="question" class="form-control" value="<?php echo htmlspecialchars($question_data['question']); ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="answer">Réponse :</label>
                    <input type="text" name="answer" id="answer" class="form-control" value="<?php echo htmlspecialchars($question_data['answer']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">Question non trouvée.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
