<?php
require_once '../require/config/config.php';
require_once '../require/function/function.php';
require_once '../require/sidebar.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$users = [];
$newsletters_envoyees = [];
$brouillons_et_programmees = [];

try {
    $stmt = $pdo->query("SELECT * FROM UTILISATEUR WHERE newsletter = 1");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $brouillons_et_programmees = getDraftsAndScheduledNewsletters($pdo);

    handlePostRequests($pdo, $users);

    $newsletters_envoyees = $pdo->query("SELECT * FROM NEWSLETTER WHERE brouillon = 0 AND (programmer IS NULL OR programmer <= NOW()) ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}

function getDraftsAndScheduledNewsletters($pdo) {
    $brouillons = $pdo->query("SELECT * FROM NEWSLETTER WHERE brouillon = 1 ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    $programmees = $pdo->query("SELECT * FROM NEWSLETTER WHERE programmer IS NOT NULL AND programmer > NOW() ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    return array_merge($brouillons, $programmees);
}

function handlePostRequests($pdo, $users) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['send_newsletter'])) {
            
        } elseif (isset($_POST['save_draft'])) {
            
        } elseif (isset($_POST['modifier_newsletter'])) {
            $edit_id = $_POST['edit_id'];
            header('Location: ../process/modifier_newsletters?id=' . $edit_id);
            exit();
        } elseif (isset($_POST['supprimer_newsletter'])) {
            $delete_id = $_POST['delete_id'];
            try {
                $stmt = $pdo->prepare("DELETE FROM NEWSLETTER WHERE id = :id");
                $stmt->bindParam(':id', $delete_id);
                $stmt->execute();
                header('Location: newsletters');
                exit();
            } catch (PDOException $e) {
                echo "Erreur lors de la suppression de la newsletter : " . $e->getMessage();
            }
        } elseif (isset($_POST['view_newsletter'])) {
            $view_id = $_POST['view_id'];
            header('Location: voir_newsletter.php?id=' . $view_id);
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
    <title>Administration de la newsletter</title>
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
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
                    <td><?php echo isset($user['pseudo']) ? htmlspecialchars($user['pseudo']) : ''; ?></td>
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
        <div class="form-group">
            <label for="programmer">Programmer l'envoi</label>
            <input type="datetime-local" class="form-control" id="programmer" name="programmer">
        </div>
        <input type="hidden" id="edit_id" name="edit_id" value="">
        <button type="submit" name="send_newsletter" class="btn btn-primary">Envoyer la newsletter</button>
        <button type="submit" name="save_draft" class="btn btn-secondary">Enregistrer le brouillon</button>
    </form>
    <hr>
    <h2>Brouillons et newsletters programmées</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sujet</th>
                <th>Date de création</th>
                <th>Date programmée</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($brouillons_et_programmees as $newsletter) : ?>
                <tr>
                    <td><?php echo $newsletter['id']; ?></td>
                    <td><?php echo isset($newsletter['sujet']) ? htmlspecialchars($newsletter['sujet']) : ''; ?></td>
                    <td><?php echo htmlspecialchars($newsletter['date_envoi']); ?></td>
                    <td><?php echo isset($newsletter['programmer']) ? htmlspecialchars($newsletter['programmer']) : ''; ?></td>
                    <td>
                        <?php echo $newsletter['brouillon'] == 1 ? "Brouillon" : "Programmé"; ?>
                    </td>
                    <td>
                        <form action="../process/modifier_newsletter.php" method="get" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $newsletter['id']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Modifier</button>
                        </form>
                        <form action="newsletters" method="post" style="display: inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $newsletter['id']; ?>">
                            <button type="submit" name="supprimer_newsletter" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <h2>Newsletters envoyées</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sujet</th>
                <th>Date d'envoi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($newsletters_envoyees as $newsletter) : ?>
                <tr>
                    <td><?php echo $newsletter['id']; ?></td>
                    <td><?php echo htmlspecialchars($newsletter['sujet']); ?></td>
                    <td><?php echo htmlspecialchars($newsletter['date_envoi']); ?></td>
                    <td>
                        <form action="index.php" method="post" style="display: inline;">
                            <input type="hidden" name="view_id" value="<?php echo $newsletter['id']; ?>">
                            <button type="submit" name="view_newsletter" class="btn btn-primary btn-sm">Voir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
