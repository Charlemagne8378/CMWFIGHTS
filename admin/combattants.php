<?php
require_once '../require/config/config.php';
session_start();
require_once '../require/sidebar/sidebar.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] != 'admin') {
    header('Location: ../auth/connexion');
    exit();
}
$image_url = $nom = $age = $poids = $taille = $category_id = $palmares_boxe = $palmares_mma = $discipline_id = "";
$image_url_err = $nom_err = $age_err = $poids_err = $taille_err = $category_id_err = $palmares_boxe_err = $palmares_mma_err = $discipline_id_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["image_url"]))){
        $image_url_err = "Veuillez entrer l'URL de l'image.";
    } else{
        $image_url = trim($_POST["image_url"]);
    }
    
    if(empty(trim($_POST["nom"]))){
        $nom_err = "Veuillez entrer le nom.";
    } else{
        $nom = trim($_POST["nom"]);
    }
    
    if(empty(trim($_POST["age"]))){
        $age_err = "Veuillez entrer l'âge.";
    } else{
        $age = trim($_POST["age"]);
    }
    
    if(empty(trim($_POST["poids"]))){
        $poids_err = "Veuillez entrer le poids.";
    } else{
        $poids = trim($_POST["poids"]);
    }
    
    if(empty(trim($_POST["taille"]))){
        $taille_err = "Veuillez entrer la taille.";
    } else{
        $taille = trim($_POST["taille"]);
    }
    
    if(empty(trim($_POST["category_id"]))){
        $category_id_err = "Veuillez entrer l'ID de catégorie.";
    } else{
        $category_id = trim($_POST["category_id"]);
    }
    
    if(empty(trim($_POST["palmares_boxe"]))){
        $palmares_err = "Veuillez entrer le palmarès boxe.";
    } else{
        $palmares_boxe = trim($_POST["palmares_boxe"]);
    }

    if(empty(trim($_POST["palmares_mma"]))){
        $palmares_err = "Veuillez entrer le palmarès mma.";
    } else{
        $palmares_mma = trim($_POST["palmares_mma"]);
    }

    if(empty(trim($_POST['discipline_id']))){
        $discipline_id_err = "Veuillez entrer l'ID de discipline.";
    } else{
        $discipline_id = trim($_POST["discipline_id"]);
    }
    
    if(empty($image_url_err) && empty($nom_err) && empty($age_err) && empty($poids_err) && empty($taille_err) && empty($category_id_err) && empty($palmares_boxe_err) && empty($palmares_mma_err) && empty($discipline_id_err)){
        $sql = "INSERT INTO COMBATTANT (image_url, nom, age, poids, taille, category_id, palmares_boxe, palmares_mma, discipline_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(1, $param_image_url);
            $stmt->bindParam(2, $param_nom);
            $stmt->bindParam(3, $param_age);
            $stmt->bindParam(4, $param_poids);
            $stmt->bindParam(5, $param_taille);
            $stmt->bindParam(6, $param_category_id);
            $stmt->bindParam(7, $param_palmares_boxe);
            $stmt->bindParam(8, $param_palmares_mma);
            $stmt->bindParam(9, $param_discipline_id);
            
            $param_image_url = $image_url;
            $param_nom = $nom;
            $param_age = $age;
            $param_poids = $poids;
            $param_taille = $taille;
            $param_category_id = $category_id;
            $param_palmares_boxe = $palmares_boxe;
            $param_palmares_mma = $palmares_mma;
            $param_discipline_id = $discipline_id;
            
            if($stmt->execute()){
                header("location: combattants");
                exit();
            } else{
                echo "Quelque chose s'est mal passé. Veuillez réessayer plus tard.";
            }
        }
        
        unset($stmt);
    
    }
    //-----------------------------------------------------------------------------------------------------------------------------------

    $combattant_id = $_GET['id'];

$sql = "SELECT * FROM COMBATTANT WHERE id = ?";
if($stmt = $pdo->prepare($sql)){
    $stmt->bindParam(1, $combattant_id, PDO::PARAM_INT);
    if($stmt->execute()){
        $combattant = $stmt->fetch(PDO::FETCH_ASSOC);
    } else{
        echo "Quelque chose s'est mal passé. Veuillez réessayer plus tard.";
    }
}

unset($stmt);

    unset($pdo);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backend - Créer un combattant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style/sidebar.css">
    <link rel="stylesheet" href="../style/combattant.css">
</head>
<body>
    <div class="wrapper">
        <h2>Créer un combattant</h2>
        <p>Remplissez ce formulaire pour ajouter un nouveau combattant.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" class="form-control <?php echo (!empty($image_url_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $image_url; ?>">
                <span class="invalid-feedback"><?php echo $image_url_err; ?></span>
            </div>
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" class="form-control <?php echo (!empty($nom_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nom; ?>">
                <span class="invalid-feedback"><?php echo $nom_err; ?></span>
            </div>
            <div class="form-group">
                <label>Âge</label>
                <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>">
                <span class="invalid-feedback"><?php echo $age_err; ?></span>
            </div>
            <div class="form-group">
                <label>Poids</label>
                <input type="text" name="poids" class="form-control <?php echo (!empty($poids_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $poids; ?>">
                <span class="invalid-feedback"><?php echo $poids_err; ?></span>
            </div>
            <div class="form-group">
                <label>Taille</label>
                <input type="text" name="taille" class="form-control <?php echo (!empty($taille_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $taille; ?>">
                <span class="invalid-feedback"><?php echo $taille_err; ?></span>
            </div>
            <div class="form-group">
                <label>Catégorie</label>
                <input type="text" name="category_id" class="form-control <?php echo (!empty($category_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $category_id; ?>">
                <span class="invalid-feedback"><?php echo $category_id_err; ?></span>
            </div>
            <div class="form-group">
                <label>Palmarès Boxe</label>
                <textarea name="palmares_boxe" class="form-control <?php echo (!empty($palmares_boxe_err)) ? 'is-invalid' : ''; ?>"><?php echo $palmares_boxe; ?></textarea>
                <span class="invalid-feedback"><?php echo $palmares_boxe_err; ?></span>
            </div>
            <div class="form-group">
                <label>Palmarès MMA</label>
                <textarea name="palmares_mma" class="form-control <?php echo (!empty($palmares_mma_err)) ? 'is-invalid' : ''; ?>"><?php echo $palmares_mma; ?></textarea>
                <span class="invalid-feedback"><?php echo $palmares_mma_err; ?></span>
            </div>
            <div class="form-group">
                <label>Discipline</label>
                <select name="discipline_id" class="form-control">
                    <option value="1">Boxe</option>
                    <option value="2">MMA</option>
                    <option value="3">Boxe/MMA</option>
                </select>
            </div>
            <input type="submit" class="btn btn-primary" value="Ajouter">
            <a href="index.php" class="btn btn-secondary ml-2">Annuler</a>
            <a href="delete_combattants.php" class="btn btn-default">Modifier les combattants</a>
        </form>
    </div>

    <script src="../scripts/compte.js"></script>
    
</body>
</html>
