<!DOCTYPE html>
<html>
    <head>
        <title>Evenement-Boxe</title>
        <link rel="icon" type="image/png" href="Images/cmwicon.png">
        <meta charset="UTF-8">
        <style>
            body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #242222;
}

.combat-section{
    margin: 200px;
    margin-top: 100px;
}

.combat-box{
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px wheat;
}

.combat-box img{
    width: 150px;
    height: 100px;
    border-radius: 50%;
}

.versus{
    font-size: 24px;
    font-weight: bold;
    color: aliceblue;
}

button{
    display: block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: wheat;
    color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 10px;
}

button:hover{
    background-color: white;
    color: wheat;
}

h1{
    color: white;
    text-align: center;
}

h2{
    color: wheat;
}

h3{
    color: wheat;
}

button a{
    text-decoration: none;
    color: black;
}

.separator{
    height: 10px;
    background-color: #ffffff;
    margin: 20px;
    margin: 50px;
}
        </style>
    </head>
    <body>
    <?php include'header.php' ?>

    <div class="separator"></div>
    <h1>Evenement Boxe Anglaise</h2>
    <div class="separator"></div>

    <div class="combat-section">
        <h2>Poids Mi-moyen (-75kg) Title shot</h2>
        <h3>9 Février 2024</h3>
        <div class="combat-box">
            <img src="Images/fid.png" alt="FID">
            <span class="versus">FID VS Irs0</span>
            <img src="Images/Irso.png" alt="Irs0">
        </div>
        <button><a href="fidirs0">Voir la carte complète</a></button>
    </div>

    <div class="combat-section">
        <h2>Poids Plumes (-65kg) Title shot</h2>
        <h3>2 Janvier 2024</h3>
        <div class="combat-box">
            <img src="Images/alias.png" alt="Alias">
            <span class="versus">Alias VS Ilyas</span>
            <img src="Images/ilyas.png" alt="Ilyas">
        </div>
        <button><a href="aliasilyas">Voir la carte complète</a></button>
    </div>
    </body>
</html>