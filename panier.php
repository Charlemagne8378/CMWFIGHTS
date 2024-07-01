<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <style>
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
        .panier{
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .panier img{
            width: 100px;
            padding: 8px 0;
        }
        .panier section{
            width: 70%;
            background-color: black;
            padding: 10px;
            border-radius: 6px;
        }
        table{
            border-collapse: collapse;
            width: 100%;
            letter-spacing: 1px;
            font-size: 13px;
        }
        th{
            padding: 10px 0;
            font-weight: 400;
        }
        td{
            border-top: 0.5px solid white;
            text-align: center;
        }

        tr{
            border-top: 0.5px solid white;
            color: white;
        }
        .total th{
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
            <tr>
                <td><img src="tshirtCMW.JPG"></td>
                <td>Tshirt en Jersey CMW X INOFLEX</td>
                <td>50€</td>
                <td>5</td>
                <td>Supprimer</td>
            </tr>
            <tr class="total">
                <th>Total : 250€</th>
            </tr>
        </table>
    </section>

</body>
</html>