<?php
require_once '../require/config/config.php';
require_once '../require/sidebar/sidebar.php';

$limit = 5;
$page_drafts = isset($_GET['page_drafts']) ? (int)$_GET['page_drafts'] : 1;
$offset_drafts = ($page_drafts - 1) * $limit;

$query_drafts = $pdo->prepare("SELECT * FROM NEWSLETTERS WHERE statut = 'brouillon' LIMIT :limit OFFSET :offset");
$query_drafts->bindParam(':limit', $limit, PDO::PARAM_INT);
$query_drafts->bindParam(':offset', $offset_drafts, PDO::PARAM_INT);
$query_drafts->execute();
$drafts = $query_drafts->fetchAll(PDO::FETCH_ASSOC);

$total_drafts = $pdo->query("SELECT COUNT(*) FROM NEWSLETTERS WHERE statut = 'brouillon'")->fetchColumn();

$page_sent = isset($_GET['page_sent']) ? (int)$_GET['page_sent'] : 1;
$offset_sent = ($page_sent - 1) * $limit;

$query_sent = $pdo->prepare("SELECT * FROM NEWSLETTERS WHERE statut = 'envoyé' LIMIT :limit OFFSET :offset");
$query_sent->bindParam(':limit', $limit, PDO::PARAM_INT);
$query_sent->bindParam(':offset', $offset_sent, PDO::PARAM_INT);
$query_sent->execute();
$sent_newsletters = $query_sent->fetchAll(PDO::FETCH_ASSOC);

$total_sent = $pdo->query("SELECT COUNT(*) FROM NEWSLETTERS WHERE statut = 'envoyé'")->fetchColumn();

$page_users = isset($_GET['page_users']) ? (int)$_GET['page_users'] : 1;
$offset_users = ($page_users - 1) * $limit;

$query_users = $pdo->prepare("SELECT pseudo, adresse_email, derniere_connexion FROM UTILISATEUR WHERE newsletter = 1 LIMIT :limit OFFSET :offset");
$query_users->bindParam(':limit', $limit, PDO::PARAM_INT);
$query_users->bindParam(':offset', $offset_users, PDO::PARAM_INT);
$query_users->execute();
$users = $query_users->fetchAll(PDO::FETCH_ASSOC);

$total_users = $pdo->query("SELECT COUNT(*) FROM UTILISATEUR WHERE newsletter = 1")->fetchColumn();

function renderPagination($total, $limit, $page, $param) {
    $totalPages = ceil($total / $limit);
    echo '<nav><ul class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i == $page ? ' active' : '';
        echo "<li class='page-item$active'><a class='page-link' href='?$param=$i'>$i</a></li>";
    }
    echo '</ul></nav>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Newsletters</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Gestion des Newsletters</h1>
    <div class="card mb-4">
        <div class="card-header">Créer une Newsletter</div>
        <div class="card-body">
            <form action="../process/newsletter/newsletter_action.php" method="post">
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Titre de la newsletter">
                </div>
                <div class="form-group">
                    <label for="subject">Sujet</label>
                    <textarea class="form-control" id="subject" name="subject" rows="3" placeholder="Sujet de la newsletter"></textarea>
                </div>
                <div class="form-group">
                    <label for="schedule">Programmer l'envoi</label>
                    <input type="datetime-local" class="form-control" id="schedule" name="schedule">
                </div>
                <button type="submit" name="action" value="send" class="btn btn-primary">Envoyer</button>
                <button type="submit" name="action" value="save" class="btn btn-secondary">Enregistrer comme brouillon</button>
            </form>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">Brouillons</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($drafts as $draft): ?>
                    <tr>
                        <td><?= htmlspecialchars($draft['titre']) ?></td>
                        <td><?= htmlspecialchars($draft['date_creation']) ?></td>
                        <td>
                            <a href="../process/newsletter/newsletter_edit.php?id=<?= $draft['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="../process/newsletter/newsletter_delete.php?id=<?= $draft['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            <a href="../process/newsletter/newsletter_send.php?id=<?= $draft['id'] ?>" class="btn btn-success btn-sm">Envoyer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php renderPagination($total_drafts, $limit, $page_drafts, 'page_drafts'); ?>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">Newsletters envoyées</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date d'envoi</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sent_newsletters as $newsletter): ?>
                    <tr>
                        <td><?= htmlspecialchars($newsletter['titre']) ?></td>
                        <td><?= htmlspecialchars($newsletter['date_envoi']) ?></td>
                        <td>
                            <a href="../process/newsletter/newsletter_send.php?id=<?= $newsletter['id'] ?>" class="btn btn-info btn-sm">Voir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php renderPagination($total_sent, $limit, $page_sent, 'page_sent'); ?>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">Utilisateurs inscrits à la newsletter</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Dernière connexion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['pseudo']) ?></td>
                        <td><?= htmlspecialchars($user['adresse_email']) ?></td>
                        <td><?= htmlspecialchars($user['derniere_connexion']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php renderPagination($total_users, $limit, $page_users, 'page_users'); ?>
        </div>
    </div>
</div>
<script src="../scripts/compte.js"></script>
</body>
</html>
