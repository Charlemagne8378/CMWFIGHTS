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
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

header {
    background-color: #000;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header .logo img {
    height: 50px;
}

nav {
    display: flex;
    align-items: center;
}

nav ul {
    list-style: none;
    display: flex;
    margin: 0;
    padding: 0;
}

nav ul li {
    margin: 0 15px;
    position: relative; 
}

nav ul li a {
    text-decoration: none;
    color: #fff;
    font-size: 16px;
}

nav ul li .dropdown-menu {
    display: none; 
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #000;
    list-style: none;
    padding: 0;
    margin: 0;
    width: 150px;
}

nav ul li .dropdown-menu li {
    margin: 0;
}

nav ul li .dropdown-menu li a {
    padding: 10px;
    display: block;
    text-decoration: none;
    color: #fff;
}

nav ul li:hover .dropdown-menu {
    display: block; 
}


#nav_check {
    display: none;
}

.menu_icon {
    display: none;
}


@media (max-width: 768px) {
    nav ul {
        display: none;
        flex-direction: column;
        width: 100%;
        background-color: #000;
        position: absolute;
        top: 60px;
        left: 0;
    }

    nav ul li {
        margin: 10px 0;
        text-align: center;
    }

    nav ul li .dropdown-menu {
        position: static; 
        width: 100%; 
    }

    nav ul li.active .dropdown-menu {
        display: block; 
    }

    nav ul li a {
        font-size: 20px;
    }

    header .logo {
        margin: 0 auto;
    }

    .menu_icon {
        display: block;
        cursor: pointer;
    }

    .menu_icon img {
        height: 30px;
    }

    #nav_check:checked + nav ul {
        display: flex;
    }

    .dropdown-menu.show {
        display: block; 
    }
}

    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="Images/cmwblanc.png" alt="logo">
        </div>
        <input type="checkbox" id="nav_check" hidden>
        <label for="nav_check" class="menu_icon">
            <img src="Images/cmwicon.png" alt="menu">
        </label>
        <nav>
            <ul>
                <li><a href="pages/mma/combattantmma">Combattant</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Evenement</a>
                    <ul class="dropdown-menu">
                        <li><a href="pages/mma/evenementmma">MMA</a></li>
                        <li><a href="pages/boxe/evenementboxe">BOXE</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Classement</a>
                    <ul class="dropdown-menu">
                        <li><a href="pages/mma/classementmma">MMA</a></li>
                        <li><a href="pages/boxe/classementboxe">BOXE</a></li>
                    </ul>
                </li>
                <li><a href="#">About</a></li>
                <li><a href="#">Candidature</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Login/Register</a>
                    <ul class="dropdown-menu">
                        <li><a href="auth/connexion.php">Login</a></li>
                        <li><a href="auth/inscription">Register</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function (e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    parent.classList.toggle('active');
                    parent.querySelector('.dropdown-menu').classList.toggle('show');
                });
            });
        });
    </script>
</body>
</html>