<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include_once "con_dbb.php";
require '/var/www/html/stripe/config.php';

// Supprimer les produits
if (isset($_GET['del'])) {
    $id_del = $_GET['del'];
    unset($_SESSION['panier'][$id_del]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: black;
        }
        .link {
            margin: 20px;
            width: fit-content;
            position: relative;
            text-decoration: none;
            background-color: white;
            color: black;
            padding: 10px 25px;
            border-radius: 6px;
            font-size: 14px;
        }
        .panier {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .panier img {
            width: 100px;
            padding: 8px 0;
        }
        .panier section {
            width: 70%;
            background-color: black;
            padding: 10px;
            border-radius: 6px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            letter-spacing: 1px;
            font-size: 13px;
        }
        th {
            padding: 10px 0;
            font-weight: 400;
        }
        td {
            border-top: 0.5px solid white;
            text-align: center;
        }
        tr {
            border-top: 0.5px solid white;
            color: white;
        }
        .total th {
            border: 0;
            float: left;
            font-weight: 500;
            padding: 10px;
        }
    </style>
</head>
<body class="panier">
    <a href="boutique.php" class="link">Retour Boutique</a>
    <section>
        <table>
            <tr>
                <th></th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Action</th>
            </tr>
            <?php
            $total = 0;
            $ids = array_keys($_SESSION['panier'] ?? []);
            if (empty($ids)) {
                echo "<tr><td colspan='5'>Votre panier est vide</td></tr>";
            } else {
                try {
                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
                    $stmt->execute($ids);
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($products as $product) {
                        $total += $product['price'] * $_SESSION['panier'][$product['id']];
            ?>
            <tr>
                <td><img src="<?=$product['img']?>"></td>
                <td><?=$product['name']?></td>
                <td><?=$product['price']?>€</td>
                <td><?=$_SESSION['panier'][$product['id']]?></td>
                <td><a href="panier.php?del=<?=$product['id']?>">Supprimer</a></td>
            </tr>
            <?php } ?>
            <tr class="total">
                <th colspan="5">Total : <?=$total?>€</th>
            </tr>
            <?php
                } catch (PDOException $e) {
                    echo "<tr><td colspan='5'>Erreur de requête : " . $e->getMessage() . "</td></tr>";
                }
            } ?>
        </table>
        <div>
            <a href="/stripe/pay.php" class="link">Payer</a>
        </div>
    </section>
</body>
</html>
