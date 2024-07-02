<?php
require_once '../require/config/config.php';
session_start();
require_once '../require/sidebar/sidebar.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fonction pour ajouter un événement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addEvent'])) {
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $nombre_combat = $_POST['nombre_combat'];
    $lieu = $_POST['lieu'];

    $sql = "INSERT INTO EVENEMENT (date, heure, nombre_combat, lieu) VALUES (:date, :heure, :nombre_combat, :lieu)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':heure', $heure);
    $stmt->bindParam(':nombre_combat', $nombre_combat, PDO::PARAM_INT);
    $stmt->bindParam(':lieu', $lieu);
    $stmt->execute();

    header("Location: evenements.php");
    exit();
}

// Fonction pour modifier un événement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editEvent'])) {
    $evenement_id = $_POST['evenement_id'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $nombre_combat = $_POST['nombre_combat'];
    $lieu = $_POST['lieu'];

    $sql = "UPDATE EVENEMENT SET date = :date, heure = :heure, nombre_combat = :nombre_combat, lieu = :lieu WHERE evenement_id = :evenement_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':heure', $heure);
    $stmt->bindParam(':nombre_combat', $nombre_combat, PDO::PARAM_INT);
    $stmt->bindParam(':lieu', $lieu);
    $stmt->bindParam(':evenement_id', $evenement_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: evenements.php");
    exit();
}

// Fonction pour supprimer un événement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteEvent'])) {
    $evenement_id = $_POST['evenement_id'];

    $sql = "DELETE FROM EVENEMENT WHERE evenement_id = :evenement_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':evenement_id', $evenement_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: evenements.php");
    exit();
}

// Récupérer tous les événements
$sql = "SELECT * FROM EVENEMENT";
$stmt = $pdo->query($sql);
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Événements</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Gestion des Événements</h1>
    
    <form method="post" action="">
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="heure">Heure</label>
            <input type="time" class="form-control" id="heure" name="heure" required>
        </div>
        <div class="form-group">
            <label for="nombre_combat">Nombre de combats</label>
            <input type="number" class="form-control" id="nombre_combat" name="nombre_combat" required>
        </div>
        <div class="form-group">
            <label for="lieu">Lieu</label>
            <input type="text" class="form-control" id="lieu" name="lieu" required>
        </div>
        <button type="submit" class="btn btn-primary" name="addEvent">Ajouter un événement</button>
    </form>

    <h2 class="mt-5">Événements existants</h2>
    <?php foreach ($evenements as $evenement): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Événement ID: <?php echo $evenement['evenement_id']; ?></h5>
            <p class="card-text">Date: <?php echo $evenement['date']; ?></p>
            <p class="card-text">Heure: <?php echo $evenement['heure']; ?></p>
            <p class="card-text">Nombre de combats: <?php echo $evenement['nombre_combat']; ?></p>
            <p class="card-text">Lieu: <?php echo $evenement['lieu']; ?></p>
            
            <button class="btn btn-warning" data-toggle="modal" data-target="#editModal<?php echo $evenement['evenement_id']; ?>">Modifier</button>
            <form method="post" action="" style="display:inline-block;">
                <input type="hidden" name="evenement_id" value="<?php echo $evenement['evenement_id']; ?>">
                <button type="submit" class="btn btn-danger" name="deleteEvent">Supprimer</button>
            </form>
        </div>
    </div>

    <!-- Modal de modification -->
    <div class="modal fade" id="editModal<?php echo $evenement['evenement_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $evenement['evenement_id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?php echo $evenement['evenement_id']; ?>">Modifier l'événement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" name="date" value="<?php echo $evenement['date']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="heure">Heure</label>
                            <input type="time" class="form-control" name="heure" value="<?php echo $evenement['heure']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre_combat">Nombre de combats</label>
                            <input type="number" class="form-control" name="nombre_combat" value="<?php echo $evenement['nombre_combat']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="lieu">Lieu</label>
                            <input type="text" class="form-control" name="lieu" value="<?php echo $evenement['lieu']; ?>" required>
                        </div>
                        <input type="hidden" name="evenement_id" value="<?php echo $evenement['evenement_id']; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" name="editEvent">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
