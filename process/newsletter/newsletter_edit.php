<?php
require_once '../../require/config/config.php';
require_once '../../require/sidebar/sidebar_process.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM NEWSLETTERS WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $newsletter = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Newsletter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../style/sidebar.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Modifier Newsletter</h1>

    <div class="card">
        <div class="card-body">
            <form action="newsletter_action.php" method="post">
                <input type="hidden" name="id" value="<?= $newsletter['id'] ?>">
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($newsletter['titre']) ?>">
                </div>
                <div class="form-group">
                    <label for="subject">Sujet</label>
                    <textarea class="form-control" id="subject" name="subject" rows="3"><?= htmlspecialchars($newsletter['sujet']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="schedule">Date d'envoi</label>
                    <input type="datetime-local" class="form-control" id="schedule" name="schedule" value="<?= date('Y-m-d\TH:i', strtotime($newsletter['date_envoi'])) ?>">
                </div>
                <button type="submit" name="action" value="send" class="btn btn-primary">Envoyer</button>
                <button type="submit" name="action" value="save" class="btn btn-secondary">Enregistrer</button>
            </form>
        </div>
    </div>

</div>
</body>
</html>
