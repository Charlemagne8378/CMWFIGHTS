<?php
require_once '../require/config/config.php';
require_once '../require/function/function.php';
require_once '../require/sidebar.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id'])) {
    $view_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM NEWSLETTER WHERE id = :id");
        $stmt->bindParam(':id', $view_id);
        $stmt->execute();
        $newsletter = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$newsletter) {
            echo "Newsletter non trouvée.";
            exit();
        }

        $sent_to = explode(',', $newsletter['envoye_a']);
        $nombre_destinataires = count($sent_to);

        echo "<h1>Détails de la newsletter</h1>";
        echo "<p><strong>Sujet:</strong> " . htmlspecialchars($newsletter['sujet']) . "</p>";
        echo "<p><strong>Contenu:</strong><br>" . nl2br(htmlspecialchars($newsletter['contenu'])) . "</p>";
        echo "<p><strong>Date d'envoi:</strong> " . htmlspecialchars($newsletter['date_envoi']) . "</p>";
        echo "<p><strong>Nombre de destinataires:</strong> " . $nombre_destinataires . "</p>";

        echo "<form action=\"supprimer_newsletter.php\" method=\"post\" style=\"display: inline;\">";
        echo "<input type=\"hidden\" name=\"delete_id\" value=\"" . $newsletter['id'] . "\">";
        echo "<button type=\"submit\" name=\"delete_newsletter\" class=\"btn btn-danger btn-sm\">Supprimer cette newsletter</button>";
        echo "</form>";
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
} else {
    echo "ID de la newsletter non spécifié.";
}
?>
