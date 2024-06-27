<?php
require_once '../require/config/config.php';
require_once '../require/function/function.php';
require_once '../require/sidebar.php';

// Vérifier si l'ID de la newsletter est présent dans l'URL
if (!isset($_GET['id'])) {
    header('Location: newsletters');
    exit();
}

$newsletter_id = $_GET['id'];

try {
    // Récupérer la newsletter à modifier
    $stmt = $pdo->prepare("SELECT * FROM NEWSLETTER WHERE id = :id");
    $stmt->bindParam(':id', $newsletter_id);
    $stmt->execute();
    $newsletter = $stmt->fetch(PDO::FETCH_ASSOC);

    // Traiter les requêtes POST
    handlePostRequests($pdo, $newsletter_id);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}

// Fonction pour gérer les requêtes POST
function handlePostRequests($pdo, $newsletter_id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_newsletter'])) {
            $subject = $_POST['subject'];
            $content = $_POST['content'];
            $programmer = !empty($_POST['programmer']) ? $_POST['programmer'] : null;
            $brouillon = isset($_POST['brouillon']) ? 1 : 0;

            // Mettre à jour la newsletter dans la base de données
            $stmt = $pdo->prepare("UPDATE NEWSLETTER SET sujet = :subject, contenu = :content, brouillon = :brouillon, programmer = :programmer WHERE id = :id");
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':brouillon', $brouillon);
            $stmt->bindParam(':programmer', $programmer);
            $stmt->bindParam(':id', $newsletter_id);
            $stmt->execute();

            // Redirection après la mise à jour
            header('Location: newsletters');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la newsletter</title>
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
</head>
<body>
<div class="container mt-5">
    <h1>Modifier la newsletter</h1>
    <form action="edit_newsletter.php?id=<?php echo $newsletter_id; ?>" method="post">
        <div class="form-group">
            <label for="subject">Sujet</label>
            <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($newsletter['sujet']); ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Contenu</label>
            <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($newsletter['contenu']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="programmer">Programmer l'envoi</label>
            <input type="datetime-local" class="form-control" id="programmer" name="programmer" value="<?php echo isset($newsletter['programmer']) ? htmlspecialchars($newsletter['programmer']) : ''; ?>">
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="brouillon" name="brouillon" <?php echo $newsletter['brouillon'] == 1 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="brouillon">Conserver comme brouillon</label>
        </div>
        <button type="submit" name="update_newsletter" class="btn btn-primary">Mettre à jour la newsletter</button>
    </form>
</div>
</body>
</html>
