<?php
require_once '../require/config/config.php';

if (!$pdo) {
    die("Échec de la connexion à la base de données : " . print_r(error_get_last(), true));
}

$topic_id = $_GET["topic_id"];

$sql = "SELECT * FROM TOPICS WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $topic_id);
$stmt->execute();
$topic = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM POSTS WHERE sujet_id = ? ORDER BY date_creation ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $topic_id);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir un sujet</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($topic["titre"]); ?></h1>
    <p><?php echo htmlspecialchars($topic["description"]); ?></p>
    <h2>Messages</h2>
    <?php foreach ($posts as $post): ?>
        <div>
            <h3><?php echo htmlspecialchars($post["utilisateur_id"]); ?> - <?php echo date("d/m/Y à H:i", strtotime($post["date_creation"])); ?></h3>
            <p><?php echo htmlspecialchars($post["contenu"]); ?></p>
        </div>
    <?php endforeach; ?>
    <?php if (isset($_SESSION["utilisateur_id"])): ?>
        <h2>Répondre</h2>
        <form action="create_post.php" method="post">
            <input type="hidden" name="sujet_id" value="<?php echo htmlspecialchars($topic["id"]); ?>">
            <textarea name="contenu" rows="4" cols="50" required></textarea>
            <br>
            <input type="submit" value="Répondre">
        </form>
    <?php endif; ?>
</body>
</html>
<?php
$stmt->closeCursor();
$pdo = null;
?>
