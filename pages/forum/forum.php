<?php
require_once '../require/config/config.php';

if (!$pdo) {
    die("Échec de la connexion à la base de données : " . print_r(error_get_last(), true));
}

if (!isset($_GET['sujet_id'])) {
    die("Aucun sujet sélectionné.");
}

$sujet_id = $_GET['sujet_id'];

$sql = "SELECT * FROM TOPICS WHERE id = :sujet_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sujet_id', $sujet_id);
$stmt->execute();
$sujet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sujet) {
    die("Le sujet sélectionné n'existe pas.");
}

$sql = "SELECT * FROM POSTS WHERE sujet_id = :sujet_id ORDER BY date_creation DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sujet_id', $sujet_id);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - <?php echo htmlspecialchars($sujet['titre']); ?></title>
    <!-- Ajouter les lignes suivantes pour inclure les fichiers CSS et JS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl7/1L_dstPt3HV5HzF6Gvk/e3s4Wz6iJgD/+ub2o" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.0/dist/umd/popper.min.js" integrity="sha384-DZ9z2/X10+6Cwvq3XIb1N3Oe53j6QxJcrwgfKaWdIhVueUo7m/ufU7JejcT0b2A" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center"><?php echo htmlspecialchars($sujet['titre']); ?></h1>
        <p class="text-center"><?php echo htmlspecialchars($sujet['description']); ?></p>
        <?php if (isset($_SESSION["utilisateur_id"])): ?>
            <form action="create_post.php" method="post">
                <input type="hidden" name="sujet_id" value="<?php echo htmlspecialchars($sujet_id); ?>">
                <div class="form-group">
                    <label for="contenu">Contenu du poste :</label>
                    <textarea class="form-control" id="contenu" name="contenu" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Créer le poste</button>
            </form>
        <?php endif; ?>
        <h2 class="mt-4">Postes existants</h2>
        <ul class="list-group">
            <?php foreach ($posts as $post): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($post['utilisateur_id']); ?></strong>
                    <small class="text-muted">le <?php echo date("d/m/Y à H:i", strtotime($post['date_creation'])); ?></small>
                    <p><?php echo htmlspecialchars($post['contenu']); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
<?php
$stmt->closeCursor();
$pdo = null;
