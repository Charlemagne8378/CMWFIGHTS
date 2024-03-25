<?php
// Définir la racine du site en fonction de l'emplacement actuel du fichier
$root = '';
for ($i = 0; $i < substr_count($_SERVER['REQUEST_URI'], '/'); $i++) {
    $root .= '../';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        

        header {
            background-color: black;
            padding: 5px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .logo img {
            max-width: 80%;
            width: 150px;
            height: auto;
        }

        .section-gauche, .section-droite {
            display: flex;
            align-items: center;
        }

        .section-droite a{
            text-decoration: none;
            color: white;
            margin: 20px;
        }

        .dropdown{
            margin-right: 30px;
        }

        .dropdown a{
            text-decoration: none;
            color: white;
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

        .search-bar {
            position: relative;
            margin-left: 20px;
        }

        .search-bar input[type="text"], .search-bar input[type="submit"] {
            border: none;
            padding: 10px;
            border-radius: 20px;
            outline: none;
        }

        .search-bar input[type="text"] {
            background-color: #fcfbfb;
            color: #000000;
            width: 150px;
            transition: width 0.4s ease-in-out;
        }

        .search-bar input[type="text"]:focus {
            width: 200px;
        }

        .search-bar input[type="submit"] {
            background-color: #ccc;
            color: #333;
            cursor: pointer;
            margin-right: 50px;
        }

        @media screen and (max-width: 600px) {
            .search-bar input[type="text"] {
                width: 100px;
            }
            .search-bar input[type="text"]:focus {
                width: 150px;
            }
        }

        .navbar a {
            color: #f2f2f2;
            text-decoration: none;
            padding: 14px 16px;
            display: block;
        }

        .navbar a:hover {
            background-color: #555;
        }

    </style>
</head>
<body>

<header>
    <div class="section-gauche">
        <div class="dropdown">
            <a class="navbar" href="<?php echo $root; ?>pages/mma/combattantmma">Combattant</a>
        </div>

        <div class="dropdown">
            <a class="navbar" href="<?php echo $root; ?>#">Evenement</a>
            <div class="dropdown-content">
                <a href="<?php echo $root; ?>pages/boxe/evenementboxe">Boxe</a>
                <a href="<?php echo $root; ?>pages/mma/evenementmma">MMA</a>
            </div>
        </div>

        <div class="dropdown">
            <a class="navbar" href="<?php echo $root; ?>#">Classement</a>
            <div class="dropdown-content">
                <a href="<?php echo $root; ?>pages/boxe/classementboxe">Boxe</a>
                <a href="<?php echo $root; ?>pages/mma/classementmma">MMA</a>
            </div>
        </div>
    </div>

    <div class="logo">
        <a href="<?php echo $root; ?>index">
            <img src="<?php echo $root; ?>Images/cmwblanc.png" alt="Logocmw" width="150">
        </a>
    </div>

    <div class="section-droite">
        <a class="navbar" href="<?php echo $root; ?>about">About</a>
        <?php if (isset($_SESSION['utilisateur_connecte'])): ?>
            <a class="navbar" href="<?php echo $root; ?>auth/logout">Logout</a>
            <a class="navbar" href="<?php echo $root; ?>settings">Settings</a>
        <?php else: ?>
            <div class="dropdown">
                <a class="navbar" href="#">Login/Register</a>
                <div class="dropdown-content">
                    <a href="<?php echo $root; ?>auth/connexion.php">Se connecter</a>
                    <a href="<?php echo $root; ?>auth/inscription">S'inscrire</a>
                </div>
            </div>
        <?php endif; ?>
        <div class="search-bar">
            <input type="text" placeholder="Rechercher...">
            <input type="submit" value="Rechercher">
        </div>
    </div>
</header>

</body>
</html>
