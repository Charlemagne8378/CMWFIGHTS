<?php
require_once '../require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $combattant_id = $_POST["combattant_id"];
    $category_id = $_POST["category_id"];
    $discipline = $_POST["discipline"];
    $ranking = $_POST["ranking"];

    
    $table_name = ($discipline == "mma") ? "CLASSEMENTMMA" : "CLASSEMENTBOXE";

    // Vérifier si la catégorie existe dans la table classement
    $sql_check_category = "SELECT classement_id FROM CLASSEMENT WHERE category_id = ?";
    $stmt_check_category = $pdo->prepare($sql_check_category);
    $stmt_check_category->execute([$category_id]);
    $classement_id = $stmt_check_category->fetchColumn();

    if ($classement_id) {
        // Catégorie existante, ajouter le combattant dans le classement correspondant
        $sql_insert = "INSERT INTO $table_name (classement_id, combattant_id, ranking) VALUES (?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([$classement_id, $combattant_id, $ranking]);

        // Rediriger vers une page de confirmation ou autre
        header('Location: classement.php');
        exit();
    } else {
        // Catégorie non trouvée dans le classement, afficher un message d'erreur
        echo "La catégorie sélectionnée n'existe pas dans le classement.";
    }
}
?>
