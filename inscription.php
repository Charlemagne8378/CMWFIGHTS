<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "1J42";
    $dbname = "Utilisateur";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }
    $nom = $_POST["Nom"];
    $pseudo = $_POST["Pseudo"];
    $email = $_POST["Adresse_email"];
    $motDePasse = $_POST["Mot_de_passe"];
    $confirmerMotDePasse = $_POST["confirmpassword"];
    if ($_POST["genre"] == "homme") {
        $genre = "homme";
    } elseif ($_POST["genre"] == "femme") {
        $genre = "femme";
    } else {
        $genre = "autre";
    }
    $genre = mysqli_real_escape_string($conn, $genre);

    if (empty($nom) || empty($pseudo) || empty($email) || empty($motDePasse) || empty($confirmerMotDePasse) || empty($genre)) {
        echo "Tous les champs obligatoires doivent être remplis.";
        exit();
    }
    if ($motDePasse !== $confirmerMotDePasse) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse e-mail invalide.";
        exit();
    }
    $sql = "SELECT * FROM Utilisateurs WHERE Adresse_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Cette adresse e-mail est déjà utilisée.";
        exit();
    }

    $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);
    $sql = "INSERT INTO Utilisateurs (Nom, Pseudo, Adresse_email, Mot_de_passe, Genre) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nom, $pseudo, $email, $motDePasseHash, $genre);

    if ($stmt->execute()) {
        echo "Inscription réussie !";
        header("Refresh:2; url=connexion");
    } else {
        echo "Erreur lors de l'inscription : " . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="icon" type="image/png" href="Images/cmwicon.png">
    <style>
        *{
            padding: 0;
            margin: 0;
            font-family: sans-serif;
            box-sizing: border-box;
        }

        body{
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgb(116, 115, 115);
        }

        .container{
            max-width: 650px;
            padding: 28px;
            margin: 0 28px;
            border-radius: 10px;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.2);
            box-shadow: 0 15px 20px rgba(0, 0, 0, 0.6);
        }

        h2{
            font-size: 26px;
            font-weight: bold;
            text-align: left;
            color: aliceblue;
            padding-bottom: 8px;
            border-bottom: 1px solid white;
        }

        .content{
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px 0;
        }

        .input-box{
            display: flex;
            flex-wrap: wrap;
            width: 50%;
            padding-bottom: 15px;
        }

        .input-box:nth-child(2n){
            justify-content: end;
        }

        .input-box label, .gender-title{
            width: 95%;
            color: aliceblue;
            font-weight: bold;
            margin: 5px 0;
        }

        .gender-title{
            font-size: 16px;
        }

        .input-box input{
            height: 40px;
            width: 95%;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid white;
            outline: none;
        }

        .input-box input:is(:focus,:valid){
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        .gender-category label{
            padding: 0 20px 0 5px;
            font-size: 14px;
        }

        .gender-category{
            color: gainsboro;
        }

        .gender-category label, .gender-category input{
            cursor: pointer;
        }

        .alert p{
            font-size: 14px;
            font-style: italic;
            color: aliceblue;
            margin: 5px 0;
            padding: 10px;
            line-height: 1.5;
        }

        .button-container{
            margin: 15px 0;
        }

        .button-container button{
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            display: block;
            font-size: 20px;
            color: white;
            border: none;
            border-radius: 5px;
            background-color: black;
            cursor: pointer;
            transition: 0.3s;
        }

        .button-container button:hover{
            background-color: aliceblue;
            color: black;
        }

        @media(max-widht:600px){
            .container{
                min-width: 280px;
            }
            .content{
                max-height: 380px;
                overflow: auto;
            }
            .inout-box{
                margin-bottom: 12px;
                width: 100%;
            }
            .inout-box:nth-child(2n){
                justify-content: space-between;
            }
            .gender-category{
                display: flex;
                justify-content: space-between;
                width: 60%;
            }
            .content::-webkit-scrollbar{
                width: 0;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php' ?>
<div class="container">
    <form action="inscription" method="post">
        <h2>S'inscrire</h2>
        <div class="content">
            <div class="input-box">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="Nom" placeholder="Entrez votre nom" required>
            </div>

            <div class="input-box">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="Pseudo" placeholder="Entrez votre pseudo" required>
            </div>

            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" id="email" name="Adresse_email" placeholder="Entrez votre email" required>
            </div>

            <div class="input-box">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="Mot_de_passe" placeholder="Entrez un mot de passe" required>
            </div>

            <div class="input-box">
                <label for="confirm-password">Confirmation Mot de passe</label>
                <input type="password" id="confirm-password" name="confirmpassword" placeholder="Confirmez votre mot de passe" required>
            </div>
            <span class="gender-title">Genre</span>
            <div class="gender-category">
                <input type="radio" id="homme" name="genre" value="homme">
                <label for="homme">Homme</label>
                <input type="radio" id="femme" name="genre" value="femme">
                <label for="femme">Femme</label>
                <input type="radio" id="autre" name="genre" value="autre">
                <label for="autre">Autres</label>
            </div>
        </div>
        <div class="alert">
            <p>En cliquant j'accepte les termes et conditions, vous allez recevoir un mail de confirmation contenant les détails de votre inscription.</p>
            <div class="button-container">
                <button type="submit">S'inscrire</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
