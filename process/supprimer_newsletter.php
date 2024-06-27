<?php
require_once '../require/config/config.php';

if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    try {
        // Suppression de la newsletter de la base de données
        $stmt = $pdo->prepare("DELETE FROM NEWSLETTER WHERE id = :id");
        $stmt->bindParam(':id', $delete_id);
        $stmt->execute();

        echo "La newsletter a été supprimée avec succès.";
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de la newsletter : " . $e->getMessage();
    }
} else {
    echo "ID de la newsletter non spécifié pour la suppression.";
}
?>
