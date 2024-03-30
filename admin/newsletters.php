<?php
require_once '../config/config.php';
require_once '../function/function.php';

// Récupérer les abonnés à la newsletter
$stmt = $pdo->query("SELECT * FROM UTILISATEUR WHERE newsletter = 1");
$users = $stmt->fetchAll();

// Récupérer l'historique des newsletters
$stmt = $pdo->query("SELECT * FROM UTILISATEUR WHERE newsletter = 1 ORDER BY id DESC");
$newsletters = $stmt->fetchAll();

// Traiter le formulaire d'envoi de newsletter
if (isset($_POST['send_newsletter'])) {
    $subject = $_POST['subject'];
    $content = $_POST['content'];

    // Enregistrer la newsletter dans la base de données
    $stmt = $pdo->prepare("INSERT INTO UTILISATEUR (sujet, contenu, newsletter) VALUES (:subject, :content, 1)");
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':content', $content);
    $stmt->execute();

    // Envoyer la newsletter aux abonnés
    foreach ($users as $user) {
        sendEmail($user['adresse_email'], $subject, $content);
    }

    // Rediriger vers la page d'administration de la newsletter
    header('Location: newsletter_admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration de la newsletter</title>
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Administration de la newsletter</h1>
        <p>Nombre total d'abonnés : <?php echo count($users); ?></p>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pseudo</th>
                    <th>Adresse e-mail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['pseudo']); ?></td>
                        <td><?php echo htmlspecialchars($user['adresse_email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <hr>
        <h2>Créer une nouvelle newsletter</h2>
        <form action="newsletter_admin.php" method="post">
            <div class="form-group">
                <label for="subject">Sujet</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="content">Contenu</label>
                <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
            </div>
            <button type="submit" name="send_newsletter" class="btn btn-primary">Envoyer la newsletter</button>
        </form>
        <hr>
        <h2>Historique des newsletters</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sujet</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsletters as $newsletter) : ?>
                    <tr>
                        <td><?php echo $newsletter['id']; ?></td>
                        <td><?php echo htmlspecialchars($newsletter['sujet']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
