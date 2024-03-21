<?php
// Inclure le fichier de configuration de la base de données
require_once '../config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définir les variables et initialiser avec des valeurs vides
$image_url = $nom = $age = $poids = $taille = $category_id = $palmares = "";
$image_url_err = $nom_err = $age_err = $poids_err = $taille_err = $category_id_err = $palmares_err = "";

// Traitement du formulaire lorsque le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Valider l'URL de l'image
    if(empty(trim($_POST["image_url"]))){
        $image_url_err = "Veuillez entrer l'URL de l'image.";
    } else{
        $image_url = trim($_POST["image_url"]);
    }
    
    // Valider le nom
    if(empty(trim($_POST["nom"]))){
        $nom_err = "Veuillez entrer le nom.";
    } else{
        $nom = trim($_POST["nom"]);
    }
    
    // Valider l'âge
    if(empty(trim($_POST["age"]))){
        $age_err = "Veuillez entrer l'âge.";
    } else{
        $age = trim($_POST["age"]);
    }
    
    // Valider le poids
    if(empty(trim($_POST["poids"]))){
        $poids_err = "Veuillez entrer le poids.";
    } else{
        $poids = trim($_POST["poids"]);
    }
    
    // Valider la taille
    if(empty(trim($_POST["taille"]))){
        $taille_err = "Veuillez entrer la taille.";
    } else{
        $taille = trim($_POST["taille"]);
    }
    
    // Valider l'ID de catégorie
    if(empty(trim($_POST["category_id"]))){
        $category_id_err = "Veuillez entrer l'ID de catégorie.";
    } else{
        $category_id = trim($_POST["category_id"]);
    }
    
    // Valider le palmarès
    if(empty(trim($_POST["palmares"]))){
        $palmares_err = "Veuillez entrer le palmarès.";
    } else{
        $palmares = trim($_POST["palmares"]);
    }
    
    // Vérifier s'il n'y a pas d'erreurs de saisie avant d'insérer dans la base de données
    if(empty($image_url_err) && empty($nom_err) && empty($age_err) && empty($poids_err) && empty($taille_err) && empty($category_id_err) && empty($palmares_err)){
        // Préparer une instruction d'insertion
        $sql = "INSERT INTO Combattant (image_url, nom, age, poids, taille, category_id, palmares) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        if($stmt = $pdo->prepare($sql)){
            // Liaison des variables à l'instruction préparée en tant que paramètres
            $stmt->bindParam(1, $param_image_url);
            $stmt->bindParam(2, $param_nom);
            $stmt->bindParam(3, $param_age);
            $stmt->bindParam(4, $param_poids);
            $stmt->bindParam(5, $param_taille);
            $stmt->bindParam(6, $param_category_id);
            $stmt->bindParam(7, $param_palmares);
            
            // Définir les valeurs des paramètres
            $param_image_url = $image_url;
            $param_nom = $nom;
            $param_age = $age;
            $param_poids = $poids;
            $param_taille = $taille;
            $param_category_id = $category_id;
            $param_palmares = $palmares;
            
            // Exécuter la déclaration préparée
            if($stmt->execute()){
                // Rediriger vers la page d'accueil
                header("location: ../index");
                exit();
            } else{
                echo "Quelque chose s'est mal passé. Veuillez réessayer plus tard.";
            }
        }
        
        // Fermer la déclaration
        unset($stmt);
    }
    
    // Fermer la connexion
    unset($pdo);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backend - Créer un combattant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Créer un combattant</h2>
        <p>Remplissez ce formulaire pour ajouter un nouveau combattant.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                <label>Palmarès</label>
                <textarea name="palmares" class="form-control <?php echo (!empty($palmares_err)) ? 'is-invalid' : ''; ?>"><?php echo $palmares; ?></textarea>
                <span class="invalid-feedback"><?php echo $palmares_err; ?></span>
            </div>
            <input type="submit" class="btn btn-primary" value="Ajouter">
            <a href="index.php" class="btn btn-secondary ml-2">Annuler</a>
        </form>
    </div>
</body>
</html>
