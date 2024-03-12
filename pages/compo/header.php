<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            margin: 0;
            font: Arial, sans-serif;
        }

        header{
            background-color: black;
            padding: 10px 20px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .logo{
            flex: 1;
            text-align: center;
        }

        .logo img{
            max-width: 40%;
            height: auto;
        }

        .section-droite,
        .section-gauche {
            display: flex;
            align-items: center;
        }

        .section-gauche{
            flex: 1;
        }

        .section-droite{
            flex: 1;
            justify-content: flex-end;
        }

        .section-droite a{
            color: aliceblue;
            text-decoration: none;
            margin-left: 20px;
            font-size: 20px;
        }

        .section-gauche a{
            color: aliceblue;
            text-decoration: none;
            margin-left: 20px;
            font-size: 20px;
        }

        .search-bar {
            position: relative;
            margin-left: 20px;
        }

        .search-bar input[type="text"] {
            border: none;
            background-color: #fcfbfb;
            color: #000000;
            width: 200px;
            padding: 10px;
            border-radius: 20px;
            outline: none;
            transition: width 0.4s ease-in-out;
        }

        .search-bar input[type="text"]:focus {
            width: 300px;
        }

        .search-bar input[type="submit"] {
            background-color: #ccc;
            border: none;
            color: #333;
            padding: 10px 10px;
            width: 40%;
            border-radius: 20px;
            cursor: pointer;
            position: absolute;
            right: 0;
            top: 0;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #060606;
            border:solid 1px #fff;
            min-width: 120px;
            z-index: 1;
        }

        .dropdown-content a {
            color: #fff;
            padding: 10px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #555;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<header>
    <link rel="icon" type="image/png" href="../Images/cmwicon.png">
    <div class="section-gauche">
        <div class="dropdown">
            <a href="#">Combattant</a>
            <div class="dropdown-content">
                <a href="../pages/boxe/combattantboxe">Boxe</a>
                <a href="../pages/mma/combattantmma">MMA</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#">Evenement</a>
            <div class="dropdown-content">
                <a href="../pages/boxe/evenementboxe">Boxe</a>
                <a href="../pages/mma/evenementmma">MMA</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#">Classement</a>
            <div class="dropdown-content">
                <a href="../pages/boxe/classementboxe">Boxe</a>
                <a href="../pages/mma/classementmma">MMA</a>
            </div>
        </div>
    </div>
    </div>

    <div class="logo">
        <a href="../index">
            <img src="../Images/cmwblanc.png" alt="Logocmw">
        </a>
    </div>
    <div class="section-droite">
        <a href="about">About</a>
        <?php if (isset($_SESSION['utilisateur_connecte'])): ?>
            <a href="../auth/logout">Logout</a>
            <a href="settings">Settings</a>
        <?php else: ?>
            <div class="dropdown">
                <a href="#">Login/Register</a>
                <div class="dropdown-content">
                    <a href="../auth/connexion">Se connecter</a>
                    <a href="../auth/inscription">S'inscrire</a>
                </div>
            </div>
        <?php endif; ?>
        <div class="search-bar">
            <input type="text" placeholder="Rechercher...">
            <input type="submit" value="Rechercher">
        </div>
    </div>

</header>

</html>