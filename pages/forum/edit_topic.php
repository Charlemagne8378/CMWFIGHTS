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

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Afficher le formulaire de modification de sujet
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier un sujet</title>
    </head>
    <body>
        <h1>Modifier un sujet</h1>
        <form action="edit_topic.php" method="post">
            <input type="hidden" name="topic_id" value="<?php echo htmlspecialchars($topic["id"]); ?>">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($topic["titre"]); ?>" required>
            <br>
            <label for="description">Description :</label>
            <textarea id="description" name="description" rows="4" cols="50"><?php echo htmlspecialchars($topic["description"]); ?></textarea>
            <br>
            <input type="submit" value="Enregistrer les modifications">
        </form>
    </body>
    </html>
    <?php
} else {
    // Traiter le formulaire de modification de sujet
    $topic_id = $_POST["topic_id"];
    $titre = $_POST["titre"];
    $description = $_POST["description"];

    $sql = "UPDATE TOPICS SET titre = ?, description = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $titre);
    $stmt->bindValue(2, $description);
    $stmt->bindValue(3, $topic_id);

    if ($stmt->execute()) {
        echo "Les modifications ont été enregistrées avec succès.";
        header("Refresh:2; url=view_topic.php?topic_id=" . $topic_id);
    } else {
        echo "Erreur lors de l'enregistrement des modifications : " . print_r($pdo->errorInfo(), true);
    }

    $stmt->closeCursor();
    $pdo = null;
}
?>
