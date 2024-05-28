<?php
require_once '../require/config/config.php';
require_once '../require/function/function.php';
require_once '../require/sidebar.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$stmt = $pdo->query("SELECT * FROM UTILISATEUR WHERE newsletter = 1");
$users = $stmt->fetchAll();

$brouillons_et_programmees = getDraftsAndScheduledNewsletters($pdo);

handlePostRequests($pdo, $users);

$brouillons_et_programmees = getDraftsAndScheduledNewsletters($pdo);

$newsletters_envoyees = $pdo->query("SELECT * FROM NEWSLETTER WHERE brouillon = 0 AND (programmer IS NULL OR programmer <= NOW()) ORDER BY id DESC")->fetchAll();

function getDraftsAndScheduledNewsletters($pdo) {
    $brouillons = $pdo->query("SELECT * FROM NEWSLETTER WHERE brouillon = 1 ORDER BY id DESC")->fetchAll();
    $programmees = $pdo->query("SELECT * FROM NEWSLETTER WHERE programmer IS NOT NULL AND programmer > NOW() ORDER BY id DESC")->fetchAll();
    return array_merge($brouillons, $programmees);
}

function handlePostRequests($pdo, $users) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete_newsletter'])) {
            $newsletter_id = $_POST['delete_id'];

            $query = "DELETE FROM NEWSLETTER WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $newsletter_id);
            $stmt->execute();

            header('Location: newsletters');
            exit();
        } elseif (isset($_POST['edit_newsletter'])) {
            $edit_id = $_POST['edit_id'];
            header('Location: edit_newsletter.php?id=' . $edit_id);
            exit();
        } elseif (isset($_POST['update_newsletter'])) {
            $edit_id = $_POST['edit_id'];
            $subject = $_POST['subject'];
            $content = $_POST['content'];
            $programmer = !empty($_POST['programmer']) ? $_POST['programmer'] : null;

            $query = "UPDATE NEWSLETTER SET sujet = :subject, contenu = :content, programmer = :programmer WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':programmer', $programmer);
            $stmt->bindParam(':id', $edit_id);
            $stmt->execute();

            header('Location: newsletters');
            exit();
        } elseif (isset($_POST['send_newsletter'])) {
            $subject = $_POST['subject'];
            $content = $_POST['content'];
            $programmer = !empty($_POST['programmer']) ? $_POST['programmer'] : null;
            $brouillon = isset($_POST['brouillon']) ? 1 : 0;
            $sent_to = $brouillon ? null : implode(',', array_column($users, 'id'));
            if ($sent_to === '') {
                $sent_to = null;
            }

            $stmt = $pdo->prepare("INSERT INTO NEWSLETTER (sujet, contenu, date_envoi, envoye_a, brouillon, programmer) VALUES (:subject, :content, NOW(), " . ($sent_to !== null ? ":sent_to" : "NULL") . ", :brouillon, :programmer)");
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':sent_to', $sent_to);
            $stmt->bindParam(':brouillon', $brouillon);
            $stmt->bindParam(':programmer', $programmer);
            $stmt->execute();

            if (!$brouillon && !$programmer) {
                foreach ($users as $user) {
                    sendEmail($user['adresse_email'], $subject, $content);
                }
            }

            header('Location: newsletters');
            exit();
        } elseif (isset($_POST['save_draft'])) {
            $subject = $_POST['subject'];
            $content = $_POST['content'];
            $programmer = !empty($_POST['programmer']) ? $_POST['programmer'] : null;
            $brouillon = 1;

            $stmt = $pdo->prepare("INSERT INTO NEWSLETTER (sujet, contenu, date_envoi, brouillon, programmer) VALUES (:subject, :content, NOW(), :brouillon, :programmer)");
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':brouillon', $brouillon);
            $stmt->bindParam(':programmer', $programmer);
            $stmt->execute();

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
    <title>Administration de la newsletter</title>
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
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
            <td><?php echo isset($newsletter['date_envoi']) ? htmlspecialchars($newsletter['date_envoi']) : ''; ?></td>
            <td><?php echo isset($newsletter['programmer']) ? htmlspecialchars($newsletter['programmer']) : ''; ?></td>

            <td>
                <?php
                if ($newsletter['brouillon'] == 1) {
                    echo "Brouillon";
                } else {
                    echo "Programmé";
                }
                ?>
            </td>

            <td>
                <form action="newsletters" method="post" style="display: inline;">
                    <input type="hidden" name="edit_id" value="<?php echo $newsletter['id']; ?>">
                    <button type="submit" name="edit_newsletter" class="btn btn-primary btn-sm">Modifier</button>
                </form>
                <form action="newsletters" method="post">
                    <input type="hidden" name="delete_id" value="<?php echo $newsletter['id']; ?>">
                    <button type="submit" name="delete_newsletter" class="btn btn-danger btn-sm">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
    </table>
    <hr>
    <h2>Historique des newsletters envoyées</h2>
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
            <?php foreach ($newsletters_envoyees as $newsletter) : ?>
                <tr>
                    <td><?php echo $newsletter['id']; ?></td>
                    <td><?php echo htmlspecialchars($newsletter['sujet']); ?></td>
                    <td><?php echo $newsletter['envoye_a'] !== null ? htmlspecialchars($newsletter['envoye_a']) : ''; ?></td>
                    <td><?php echo htmlspecialchars($newsletter['date_envoi']); ?></td>
                    <td>
                        <form action="newsletters" method="post" style="display: inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $newsletter['id']; ?>">
                            <button type="submit" name="delete_newsletter" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>


                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="http://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.account-btn').click(function() {
            $('.account-box').toggleClass('show');
        });
    });
    $(document).ready(function() {
        $('button[name="edit_newsletter"]').click(function() {
            var newsletterId = $(this).closest('form').find('input[name="edit_id"]').val();
            $('#edit_id').val(newsletterId);
        });
    });
</script>
</body>
</html>
