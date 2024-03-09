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
    <h1>Evenement MMA</h2>
    <div class="separator"></div>

    <div class="combat-section">
        <h2>Poids Super-moyen (-85kg) Title Shot</h2>
        <h3>28 Août 2023</h3>
        <div class="combat-box">
            <img src="Images/fadal.png" alt="Fadal">
            <span class="versus">Fadal VS S2R</span>
            <img src="Images/s2r.png" alt="S2R">
        </div>
        <button><a href="fadals2r">Voir la carte complète</a></button>
    </div>

    <div class="combat-section">
        <h2>Poids Super-moyen (-85kg)</h2>
        <h3>6 Mars 2023</h3>
        <div class="combat-box">
            <img src="Images/fadal.png" alt="Fadal">
            <span class="versus">Fadal VS Wass</span>
            <img src="Images/wass.png" alt="Wass">
        </div>
        <button><a href="fadalwass">Voir la carte complète</a></button>
    </div>

    <div class="combat-section">
        <h2>Poids Mi-moyen (-75kg)</h2>
        <h3>26 Avril 2023</h3>
        <div class="combat-box">
            <img src="Images/fid.png" alt="FID">
            <span class="versus">FID VS Sada</span>
            <img src="Images/sada.png" alt="Sada">
        </div>
        <button><a href="fidsada">Voir la carte complète</a></button>
    </div>

    <div class="combat-section">
        <h2>Poids Plumes (-65kg) Title Shot</h2>
        <h3>20 Mai 2023</h3>
        <div class="combat-box">
            <img src="Images/belaid.png" alt="Belaid">
            <span class="versus">Belaid VS S2R</span>
            <img src="Images/s2r.png" alt="S2R">
        </div>
        <button><a href="belaids2r">Voir la carte complète</a></button>
    </div>

    <div class="combat-section">
        <h2>Poids Mi-moyen (-75kg)</h2>
        <h3>16 Mars 2023</h3>
        <div class="combat-box">
            <img src="Images/fid.png" alt="FID">
            <span class="versus">FID VS Comoco</span>
            <img src="Images/comoco.png" alt="Comoco">
        </div>
        <button><a href="fidcomoco">Voir la carte complète</a></button>
    </div>

    <div class="combat-section">
        <h2>Poids Mi-moyen (-75kg)</h2>
        <h3>1 Mars 2023</h3>
        <div class="combat-box">
            <img src="Images/fid.png" alt="FID">
            <span class="versus">FID VS Fadal</span>
            <img src="Images/fadal.png" alt="Fadal">
        </div>
        <button><a href="fidfadal">Voir la carte complète</a></button>
    </div>

    <div class="combat-section">
        <h2>Poids Plumes (-65kg)</h2>
        <h3>23 Février 2023</h3>
        <div class="combat-box">
            <img src="Images/belaid.png" alt="Belaid">
            <span class="versus">Belaid VS Cheick</span>
            <img src="Images/cheick.png" alt="Cheick">
        </div>
        <button><a href="belaidcheick">Voir la carte complète</a></button>
    </div>
    </body>
</html>