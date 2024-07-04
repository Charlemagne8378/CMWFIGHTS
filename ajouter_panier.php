<?php
session_start();
include_once "con_dbb.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $produit = $stmt->fetch();

    if (empty($produit)) {
        die("Ce produit n'existe pas");
    }

    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]++;
    } else {
        $_SESSION['panier'][$id] = 1;
    }

    header("Location: boutique.php");
}
?>
