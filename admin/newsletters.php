<?php
require_once '../config/config.php';
require_once '../function/function.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les abonnés à la newsletter
$stmt = $pdo->query("SELECT * FROM UTILISATEUR WHERE newsletter = 1");
$users = $stmt->fetchAll();

// Récupérer l'historique des newsletters
$stmt = $pdo->query("SELECT * FROM NEWSLETTER ORDER BY id DESC");
$newsletters = $stmt->fetchAll();

// Traiter la demande de suppression de newsletter
if (isset($_POST['delete_newsletter'])) {
    $newsletter_id = $_POST['delete_id'];

    $query = "DELETE FROM NEWSLETTER WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $newsletter_id);
    $stmt->execute();

    header('Location: newsletters');
    exit();    
}

// Traiter le formulaire d'envoi de newsletter
if (isset($_POST['send_newsletter'])) {
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    $sent_to = implode(',', array_column($users, 'id'));

    // Enregistrer la newsletter dans la base de données
    $stmt = $pdo->prepare("INSERT INTO NEWSLETTER (sujet, contenu, date_envoi, envoye_a) VALUES (:subject, :content, NOW(), :sent_to)");
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':sent_to', $sent_to);
    $stmt->execute();

    // Envoyer la newsletter aux abonnés
    foreach ($users as $user) {
        sendEmail($user['adresse_email'], $subject, $content);
    }

    // Rediriger vers la page d'administration de la newsletter
    header('Location: newsletters');
    exit();
}

// Traiter la demande de désabonnement
if (isset($_GET['unsubscribe_email'])) {
    $email = $_GET['unsubscribe_email'];

    $query = "UPDATE UTILISATEUR SET newsletter = 0 WHERE adresse_email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    echo "<p>Vous avez été désabonné de notre newsletter avec succès.</p>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100%;
            z-index: 1000;
        }
    </style>
</head>
<body>
<div class="sidebar p-3">
    <div class="list-group">
        <a href="accueil" class="list-group-item list-group-item-action py-2">Accueil</a>
        <a href="billetterie" class="list-group-item list-group-item-action py-2">Billetterie</a>
        <a href="captcha" class="list-group-item list-group-item-action py-2">Captcha</a>
        <a href="classement" class="list-group-item list-group-item-action py-2">Classement</a>
        <a href="combattants" class="list-group-item list-group-item-action py-2">Combattants</a>
        <a href="evenements" class="list-group-item list-group-item-action py-2">Événements</a>
        <a href="image" class="list-group-item list-group-item-action py-2">Image</a>
        <a href="modifier_utilisateur" class="list-group-item list-group-item-action py-2">Modifier utilisateur</a>
        <a href="newsletters" class="list-group-item list-group-item-action py-2">Newsletters</a>
        <a href="service_client" class="list-group-item list-group-item-action py-2">Service client</a>
        <a href="utilisateurs" class="list-group-item list-group-item-action py-2 active">Utilisateurs</a>
    </div>
</div>
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
        <form action="newsletters" method="post">
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
                    <th>Envoyé à</th>
                    <th>Date d'envoi</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsletters as $newsletter) : ?>
                    <tr>
                        <td><?php echo $newsletter['id']; ?></td>
                        <td><?php echo htmlspecialchars($newsletter['sujet']); ?></td>
                        <td><?php echo htmlspecialchars($newsletter['envoye_a']); ?></td>
                        <td><?php echo htmlspecialchars($newsletter['date_envoi']); ?></td>
                        <td>
                            <form action="newsletters" method="post">
                                <input type="hidden" name="delete_id" value="<?php echo $newsletter['id']; ?>">
                                <button type="submit" name="delete_newsletter" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
