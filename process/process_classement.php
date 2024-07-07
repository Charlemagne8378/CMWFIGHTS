<?php
require_once '../require/config/config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $combattant_id = $_POST["combattant_id"];
    $category_id = $_POST["category_id"];
    $discipline = $_POST["discipline"];
    $ranking = $_POST["ranking"];

    
    $table_name = ($discipline == "mma") ? "CLASSEMENTMMA" : "CLASSEMENTBOXE";

    $sql_check_category = "SELECT classement_id FROM CLASSEMENT WHERE category_id = ?";
    $stmt_check_category = $pdo->prepare($sql_check_category);
    $stmt_check_category->execute([$category_id]);
    $classement_id = $stmt_check_category->fetchColumn();

    if ($classement_id) {
        $sql_insert = "INSERT INTO $table_name (classement_id, combattant_id, ranking) VALUES (?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([$classement_id, $combattant_id, $ranking]);

        header('Location: ../admin/classement.php');
        exit();
    } else {
        echo "La catégorie sélectionnée n'existe pas dans le classement.";
    }
}
?>
