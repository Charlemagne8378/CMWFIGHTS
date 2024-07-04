<?php
session_start();
include_once "con_dbb.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;500&display=swap');
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
        body{
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: black;
        }
        .link{
            margin: 20px;
            width: fit-content;
            position: relative;
            text-decoration: 0;
            background-color: white;
            color: black;
            padding: 10px 25px;
            border-radius: 6px;
            font-size: 14px;
        }
        span{
            position: absolute;
            top: -9px;
            right: -9px;
            background-color: black;
            height: 20px;
            width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 12px;
            color: white;
        }
        .products_list{
            margin: 100px auto;
            position: relative;
            width: 70%;
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(170px,1fr));
            grid-gap:25px;
            margin-top: 300px;
        }
        .product{
            background-color: black;
            width: 100%;
            border-radius: 6px;
            overflow: hidden;
            transition: 0.5s;
        }
        .image_product{
            height: 200px;
            width: 100%;
            display: flex ;
            align-items: center;
            justify-content: center;
        }
        .image_product img{
            height: 500%;
            width: 100%;
            object-fit: cover;
            margin-top: 100px;
            padding: 20px;
        }
        .content{
            margin-top: 300px;
            margin-bottom: 30px;
            height: fit-content;
            text-align: center;
        }
        .name{
            margin: 15px 0;
            font-weight: 400;
            font-size: 40px;
            color: white;
        }
        .price{
            margin: 15px 0;
            font-weight: 400;
            color: white;
        }
        .id_product{
            text-align: center;
            text-decoration: 0;
            background-color: white;
            letter-spacing: 1px;
            color: black;
            padding: 10px 10%;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <a href="panier.php" class="link">Panier<span><?=array_sum($_SESSION['panier'] ?? [])?></span></a>
    <section class="products_list">
        <?php
            try {
                $stmt = $pdo->prepare('SELECT * FROM products');
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($rows) {
                    foreach ($rows as $row) {
                        ?>
                        <form action="ajouter_panier.php" method="GET" class="produit">
                            <div class="image_product">
                                <img src="<?=$row['img']?>" alt="Product Image">
                            </div>
                            <div class="content">
                                <h4 class="name"><?=$row['name']?></h4>
                                <h2 class="price"><?=$row['price']?>€</h2>
                                <input type="hidden" name="id" value="<?=$row['id']?>">
                                <button type="submit" class="id_product">Ajouter au panier</button>
                            </div>
                        </form>
                        <?php
                    }
                } else {
                    echo "Aucun produit trouvé.";
                }
            } catch (PDOException $e) {
                echo "Erreur de requête : " . $e->getMessage();
            }
        ?>
    </section>
</body>
</html>
