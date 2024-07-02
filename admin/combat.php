<?php
require_once '../require/config/config.php';
session_start();
require_once '../require/sidebar/sidebar.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les événements existants
$sql = "SELECT * FROM EVENEMENT";
$stmt = $pdo->query($sql);
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les combattants existants
$sql = "SELECT * FROM COMBATTANT";
$stmt = $pdo->query($sql);
$combattants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les catégories existantes
$sql = "SELECT * FROM CATEGORIES";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les disciplines existantes
$sql = "SELECT * FROM DISCIPLINE";
$stmt = $pdo->query($sql);
$disciplines = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour ajouter un combat
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addCombat'])) {
    $evenement_id = $_POST['evenement_id'];
    $combattant1_id = $_POST['combattant1_id'];
    $combattant2_id = $_POST['combattant2_id'];
    $statut = $_POST['statut'];
    $categorie_id = $_POST['categorie_id'];
    $discipline_id = $_POST['discipline_id'];

    $sql = "INSERT INTO COMBAT (evenement_id, combattant1_id, combattant2_id, statut, category_id, discipline_id) 
            VALUES (:evenement_id, :combattant1_id, :combattant2_id, :statut, :categorie_id, :discipline_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':evenement_id', $evenement_id, PDO::PARAM_INT);
    $stmt->bindParam(':combattant1_id', $combattant1_id, PDO::PARAM_INT);
    $stmt->bindParam(':combattant2_id', $combattant2_id, PDO::PARAM_INT);
    $stmt->bindParam(':statut', $statut);
    $stmt->bindParam(':categorie_id', $categorie_id, PDO::PARAM_INT);
    $stmt->bindParam(':discipline_id', $discipline_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: admin_combat.php");
    exit();
}

// Fonction pour supprimer un combat
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteCombat'])) {
    $combat_id = $_POST['combat_id'];

    $sql = "DELETE FROM COMBAT WHERE combat_id = :combat_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':combat_id', $combat_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: admin_combat.php");
    exit();
}

// Récupérer les combats existants avec les informations des événements, combattants, catégories et disciplines
$sql = "SELECT C.combat_id, E.date, E.heure, C1.nom AS combattant1_nom, C2.nom AS combattant2_nom, 
               CAT.category_name AS categorie_nom, D.discipline_name AS discipline_nom
        FROM COMBAT C
        JOIN EVENEMENT E ON C.evenement_id = E.evenement_id
        JOIN COMBATTANT C1 ON C.combattant1_id = C1.combattant_id
        JOIN COMBATTANT C2 ON C.combattant2_id = C2.combattant_id
        JOIN CATEGORIES CAT ON C.category_id = CAT.category_id
        JOIN DISCIPLINE D ON C.discipline_id = D.discipline_id";

$stmt = $pdo->query($sql);
$combats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Combats</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Gestion des Combats</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="evenement_id">Événement</label>
                <select class="form-control" id="evenement_id" name="evenement_id" required>
                    <?php foreach ($evenements as $evenement): ?>
                        <option value="<?php echo $evenement['evenement_id']; ?>"><?php echo $evenement['evenement_id'] . ' - ' .$evenement['date'] . ' ' . $evenement['lieu']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="combattant1_id">Combattant 1</label>
                <select class="form-control" id="combattant1_id" name="combattant1_id" required>
                    <?php foreach ($combattants as $combattant): ?>
                        <option value="<?php echo $combattant['combattant_id']; ?>"><?php echo $combattant['nom']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="combattant2_id">Combattant 2</label>
                <select class="form-control" id="combattant2_id" name="combattant2_id" required>
                    <?php foreach ($combattants as $combattant): ?>
                        <option value="<?php echo $combattant['combattant_id']; ?>"><?php echo $combattant['nom']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="statut">Statut</label>
                <select class="form-control" id="statut" name="statut" required>
                    <option value="ranking bout">Ranking Bout</option>
                    <option value="title shot">Title Shot</option>
                </select>
            </div>
            <div class="form-group">
                <label for="categorie_id">Catégorie</label>
                <select class="form-control" id="categorie_id" name="categorie_id" required>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo $categorie['category_id']; ?>"><?php echo $categorie['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="discipline_id">Discipline</label>
                <select class="form-control" id="discipline_id" name="discipline_id" required>
                    <?php foreach ($disciplines as $discipline): ?>
                        <option value="<?php echo $discipline['discipline_id']; ?>"><?php echo $discipline['discipline_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="addCombat">Ajouter Combat</button>
        </form>

        <h3 class="my-4">Liste des Combats</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Combattant 1</th>
                    <th>Combattant 2</th>
                    <th>Statut</th>
                    <th>Catégorie</th>
                    <th>Discipline</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($combats as $combat): ?>
    <tr>
        <td><?php echo $combat['combat_id']; ?></td>
        <td><?php echo $combat['date']; ?></td>
        <td><?php echo $combat['heure']; ?></td>
        <td><?php echo $combat['combattant1_nom']; ?></td>
        <td><?php echo $combat['combattant2_nom']; ?></td>
        <td><?php echo isset($combat['statut']) ? $combat['statut'] : 'Statut non défini'; ?></td>
        <td><?php echo $combat['categorie_nom']; ?></td>
        <td><?php echo $combat['discipline_nom']; ?></td>
        <td>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display:inline;">
                <input type="hidden" name="combat_id" value="<?php echo $combat['combat_id']; ?>">
                <button type="submit" class="btn btn-danger btn-sm" name="deleteCombat">Supprimer</button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
