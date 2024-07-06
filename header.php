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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        header {
            background-color: black;
            padding: 10px 30px;
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
        .section-droite a, .section-gauche a {
            text-decoration: none;
            color: white;
            margin: 0 15px;
        }
        .dropdown {
            margin-right: 30px;
        }
        .dropdown a {
            text-decoration: none;
            color: white;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #333;
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
        .search-results {
            position: absolute;
            top: 40px;
            left: 0;
            background-color: #fff;
            color: #000;
            border: 1px solid #ccc;
            width: 100%;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .search-results ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .search-results li {
            padding: 10px;
            cursor: pointer;
        }
        .search-results li:hover {
            background-color: #f0f0f0;
        }
        footer {
            background-color: #222;
            color: #ccc;
            padding: 20px 0;
            margin-top: 50px;
        }
        footer a {
            color: #ccc;
            text-decoration: none;
        }
        footer a:hover {
            color: #fff;
        }
        footer .social-icons a {
            margin: 0 10px;
            font-size: 18px;
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
            <a class="navbar" href="<?php echo $root; ?>pages/mma/evenementmma">Evenement</a>
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
        <a class="navbar" href="<?php echo $root; ?>pages/candidature">Candidature</a>
        <?php if (isset($_SESSION['utilisateur_connecte'])): ?>
            <a class="navbar" href="<?php echo $root; ?>auth/logout">Logout</a>
            <a class="navbar" href="<?php echo $root; ?>pages/compte/settings">Settings</a>
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
            <input type="text" id="search-input" placeholder="Rechercher...">
            <input type="submit" value="Rechercher">
            <div class="search-results" id="search-results"></div>
        </div>
    </div>
</header>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    document.getElementById('search-input').addEventListener('input', function() {
        let query = this.value;
        if (query.length > 0) {
            fetch('<?php echo $root; ?>recherche.php?query=' + query)
                .then(response => response.json())
                .then(data => {
                    let resultsContainer = document.getElementById('search-results');
                    resultsContainer.innerHTML = '';
                    if (data.length > 0) {
                        let resultsList = document.createElement('ul');
                        data.forEach(item => {
                            let listItem = document.createElement('li');
                            listItem.textContent = item.nom;
                            listItem.onclick = function() {
                                window.location.href = '<?php echo $root; ?>pages/mma/combattantmma?nom=' + item.nom;
                            };
                            resultsList.appendChild(listItem);
                        });
                        resultsContainer.appendChild(resultsList);
                    } else {
                        resultsContainer.innerHTML = '<p>Aucun résultat trouvé</p>';
                    }
                })
                .catch(error => console.error('Erreur:', error));
        } else {
            document.getElementById('search-results').innerHTML = '';
        }
    });
</script>
</body>
</html>
