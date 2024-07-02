<?php
require_once '../require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

// Vérifier si le formulaire a été soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Valider et récupérer les données du formulaire
    $combattant_id = $_POST["combattant_id"];
    $image_url = $_POST["image_url"];
    $nom = $_POST["nom"];
    $age = $_POST["age"];
    $poids = $_POST["poids"];
    $taille = $_POST["taille"];
    $category_id = $_POST["category_id"];
    $palmares_boxe = $_POST["palmares_boxe"];
    $palmares_mma = $_POST["palmares_mma"];
    $discipline_id = $_POST["discipline_id"];
    
    // Préparer et exécuter la requête SQL de mise à jour
    $sql = "UPDATE COMBATTANT SET image_url = ?, nom = ?, age = ?, poids = ?, taille = ?, category_id = ?, palmares_boxe = ?, palmares_mma = ?, discipline_id = ? WHERE combattant_id = ?";
    if($stmt = $pdo->prepare($sql)){
        $stmt->execute([$image_url, $nom, $age, $poids, $taille, $category_id, $palmares_boxe, $palmares_mma, $discipline_id, $combattant_id]);
        // Rediriger l'utilisateur vers une page de confirmation ou ailleurs après la mise à jour
        header("location: delete_combattants.php");
        exit();
    } else{
        echo "Erreur lors de la préparation de la requête SQL.";
    }
}

// Afficher les informations du combattant à éditer
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Récupérer l'ID du combattant depuis l'URL
    $combattant_id = trim($_GET["id"]);

    // Préparer une instruction SELECT pour récupérer les informations du combattant à éditer
    $sql = "SELECT * FROM COMBATTANT WHERE combattant_id = ?";
    
    if($stmt = $pdo->prepare($sql)){
        // Liaison des variables à l'instruction préparée en tant que paramètres
        $stmt->bindParam(1, $combattant_id, PDO::PARAM_INT);
        
        // Tentative d'exécution de la déclaration préparée
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                // Récupérer les informations du combattant
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $image_url = $row["image_url"];
                $nom = $row["nom"];
                $age = $row["age"];
                $poids = $row["poids"];
                $taille = $row["taille"];
                $category_id = $row["category_id"];
                $palmares_boxe = $row["palmares_boxe"];
                $palmares_mma = $row["palmares_mma"];
                $discipline_id = $row["discipline_id"];
            } else{
                // Aucun combattant trouvé avec cet ID, rediriger vers une page d'erreur
                header("location: error.php");
                exit();
            }
        } else{
            echo "Oops! Quelque chose s'est mal passé. Veuillez réessayer plus tard.";
        }

        // Fermer la déclaration
        unset($stmt);
    }
    
    // Fermer la connexion
    unset($pdo);
} else{
    // Aucun ID de combattant fourni dans l'URL, rediriger vers une page d'erreur
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer un combattant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/combattant.css">
</head>
<body>
    <div class="wrapper">
        <h2>Éditer un combattant</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="combattant_id" value="<?php echo $combattant_id; ?>">
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" class="form-control" value="<?php echo $image_url; ?>">
            </div>    
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" class="form-control" value="<?php echo $nom; ?>">
            </div>
            <div class="form-group">
                <label>Âge</label>
                <input type="text" name="age" class="form-control" value="<?php echo $age; ?>">
            </div>
            <div class="form-group">
                <label>Poids</label>
                <input type="text" name="poids" class="form-control" value="<?php echo $poids; ?>">
            </div>
            <div class="form-group">
                <label>Taille</label>
                <input type="text" name="taille" class="form-control" value="<?php echo $taille; ?>">
            </div>
            <div class="form-group">
                <label>Catégorie</label>
                <input type="text" name="category_id" class="form-control" value="<?php echo $category_id; ?>">
            </div>
            <div class="form-group">
                <label>Palmarès Boxe</label>
                <textarea name="palmares_boxe" class="form-control"><?php echo $palmares_boxe; ?></textarea>
            </div>
            <div class="form-group">
                <label>Palmarès MMA</label>
                <textarea name="palmares_mma" class="form-control"><?php echo $palmares_mma; ?></textarea>
            </div>
            <div class="form-group">
                <label>Discipline</label>
                <select name="discipline_id" class="form-control">
                    <option value="1" <?php if($discipline_id == 1) echo "selected"; ?>>Boxe</option>
                    <option value="2" <?php if($discipline_id == 2) echo "selected"; ?>>MMA</option>
                    <option value="3" <?php if($discipline_id == 3) echo "selected"; ?>>Boxe/MMA</option>
                </select>
            </div>
            <input type="submit" class="btn btn-primary" value="Enregistrer les modifications">
            <a href="backend.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>
