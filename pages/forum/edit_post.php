<?php
require_once '../require/config/config.php';

if (!$pdo) {
    die("Échec de la connexion à la base de données : " . print_r(error_get_last(), true));
}

$post_id = $_GET["post_id"];

$sql = "SELECT * FROM POSTS WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $post_id);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Afficher le formulaire de modification de message
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier un message</title>
    </head>
    <body>
        <h1>Modifier un message</h1>
        <form action="edit_post.php" method="post">
            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post["id"]); ?>">
            <textarea name="contenu" rows="4" cols="50" required><?php echo htmlspecialchars($post["contenu"]); ?></textarea>
            <br>
            <input type="submit" value="Enregistrer les modifications">
        </form>
    </body>
    </html>
    <?php
} else {
    // Traiter le formulaire de modification de message
    $post_id = $_POST["post_id"];
    $contenu = $_POST["contenu"];

    $sql = "UPDATE POSTS SET contenu = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $contenu);
    $stmt->bindValue(2, $post_id);

    if ($stmt->execute()) {
        echo "Les modifications ont été enregistrées avec succès.";
        header("Refresh:2; url=view_topic.php?topic_id=" . $post["sujet_id"]);
    } else {
        echo "Erreur lors de l'enregistrement des modifications : " . print_r($pdo->errorInfo(), true);
    }

    $stmt->closeCursor();
    $pdo = null;
}
?>
