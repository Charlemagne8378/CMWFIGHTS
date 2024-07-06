<?php
require_once 'require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Si une réponse est soumise
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reponse']) && isset($_POST['message_id'])) {
    $reponse = $_POST['reponse'];
    $message_id = $_POST['message_id'];

    // Récupérer l'email du client pour envoyer une réponse par email
    $stmt = $pdo->prepare("SELECT email_client FROM SERVICECLIENT WHERE id = ?");
    $stmt->execute([$message_id]);
    $email_client = $stmt->fetchColumn();

    // Mettre à jour le message avec la réponse
    $stmt = $pdo->prepare("UPDATE SERVICECLIENT SET reponse = ?, date_reponse = NOW() WHERE id = ?");
    $stmt->execute([$reponse, $message_id]);

    // Envoyer un email de réponse au client
    mail($email_client, "Réponse du Service Client", $reponse);

    echo "Réponse envoyée avec succès.";
}

// Récupérer tous les messages
$stmt = $pdo->query("SELECT * FROM SERVICECLIENT ORDER BY date_creation DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Administration - Messages Service Client</title>
</head>
<body>
<div class="container mt-5">
    <h2>Messages du Service Client</h2>
    <?php foreach ($messages as $message): ?>
        <div class="card my-4">
            <div class="card-body">
                <h5 class="card-title">Message de : <?php echo htmlspecialchars($message['email_client']); ?></h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                <?php if ($message['reponse']): ?>
                    <h6 class="card-subtitle mb-2 text-muted">Réponse :</h6>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($message['reponse'])); ?></p>
                <?php else: ?>
                    <form action="messages.php" method="POST">
                        <div class="form-group">
                            <label for="reponse">Répondre :</label>
                            <textarea class="form-control" id="reponse" name="reponse" rows="3" required></textarea>
                        </div>
                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                        <button type="submit" class="btn btn-primary">Envoyer la réponse</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="card-footer text-muted">
                Reçu le <?php echo $message['date_creation']; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
