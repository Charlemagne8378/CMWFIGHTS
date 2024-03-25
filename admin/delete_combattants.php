<?php
// Inclure le fichier de configuration de la base de données
require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

// Récupérer les combattants depuis la base de données
$sql = "SELECT * FROM Combattant";
$stmt = $pdo->query($sql);
$combattants = $stmt->fetchAll();

// Gérer les actions de modification ou de suppression
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit'])) {
        // Rediriger vers la page d'édition avec l'ID du combattant à modifier
        header("location: edit_combattants.php?id=" . $_POST['id']);
        exit();
    } elseif (isset($_POST['delete'])) {
        // Supprimer le combattant avec l'ID correspondant
        $id = $_POST['id'];
        $sql = "DELETE FROM Combattant WHERE combattant_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            // Rediriger vers la page backend après la suppression
            header("location: delete_combattants.php");
            exit();
        } else {
            echo "Une erreur s'est produite lors de la suppression.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backend - Gestion des combattants</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Liste des combattants</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image URL</th>
                    <th>Nom</th>
                    <th>Age</th>
                    <th>Poids</th>
                    <th>Taille</th>
                    <th>Catégorie ID</th>
                    <th>Palmarès Boxe</th>
                    <th>Palmarès MMA</th>
                    <th>Discipline ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($combattants as $combattant) : ?>
                    <tr>
                        <td><?php echo $combattant['combattant_id']; ?></td>
                        <td><?php echo $combattant['image_url']; ?></td>
                        <td><?php echo $combattant['nom']; ?></td>
                        <td><?php echo $combattant['age']; ?></td>
                        <td><?php echo $combattant['poids']; ?></td>
                        <td><?php echo $combattant['taille']; ?></td>
                        <td><?php echo $combattant['category_id']; ?></td>
                        <td><?php echo $combattant['palmares_boxe']; ?></td>
                        <td><?php echo $combattant['palmares_mma']; ?></td>
                        <td><?php echo $combattant['discipline_id']; ?></td>
                        <td>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="hidden" name="id" value="<?php echo $combattant['combattant_id']; ?>">
                                <button type="submit" name="edit" class="btn btn-primary">Modifier</button>
                                <button type="submit" name="delete" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
