<?php
$servername = "localhost";
$username = "root";
$password = "cmwfight75012";
$dbname = "kiwi";

//include 'indextest.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "visu des actions fait : ". '<br>'. '<br>'. '<br>';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["image_url"])) {
            $img = $_POST["image_url"];
            // Affichage de l'URL de l'image stockée dans la variable $img
            echo "L'URL de l'image saisie est : " . $img;

            // Insertion de l'URL de l'image dans la base de données
            $pdo->exec("INSERT INTO IMG (image_url) VALUES ('$img')");

            exit();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_image_url"])) {
        $newImageUrl = $_POST["new_image_url"];

        // Mettre à jour l'URL de l'image pour l'ID 1
        $sql = "UPDATE IMG SET image_url = :newImageUrl WHERE id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newImageUrl', $newImageUrl);
        $stmt->execute();

        echo "L'URL de l'image pour l'ID 1 a été modifiée avec succès.". '<br>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_image_url1"])) {
        $newImageUrl1 = $_POST["new_image_url1"];

        // Mettre à jour l'URL de l'image pour l'ID 2
        $sql = "UPDATE IMG SET image_url = :newImageUrl1 WHERE id = 2";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newImageUrl1', $newImageUrl1);
        $stmt->execute();

        echo "L'URL de l'image pour l'ID 2 a été modifiée avec succès.". '<br>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_image_url2"])) {
        $newImageUrl2 = $_POST["new_image_url2"];

        // Mettre à jour l'URL de l'image pour l'ID 3
        $sql = "UPDATE IMG SET image_url = :newImageUrl2 WHERE id = 3";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newImageUrl2', $newImageUrl2);
        $stmt->execute();

        echo "L'URL de l'image pour l'ID 3 a été modifiée avec succès.". '<br>';
    }

    $sql =  "SELECT * FROM IMG";
    $req = $pdo->query($sql);
    $results = $req->fetchAll();

    foreach($results as $rep){
        echo "L'URL de l'image  : " . $rep['image_url'] . '<br>';
    }

    $sql1 = "SELECT image_url FROM IMG WHERE id = '1'";
    $img1 = $pdo->query($sql1)->fetch();
    $image_fond = $img1['image_url'];
    $sql2 = "SELECT image_url FROM IMG WHERE id = '2'";
    $img2 = $pdo->query($sql2)->fetch();
    $image1 = $img2['image_url'];
    $sql3 = "SELECT image_url FROM IMG WHERE id = '3'";
    $img3 = $pdo->query($sql3)->fetch();
    $image2 = $img3['image_url'];




    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_title"]) && isset($_POST["new_content"]) && isset($_POST["new_image_url4"])) {
        $newTitle = $_POST["new_title"];
        $newContent = $_POST["new_content"];
        $newImageUrl = $_POST["new_image_url4"];

        $newsId = 1;

        $sql = "UPDATE NEWS SET title = :newTitle, content = :newContent, image_url = :newImageUrl WHERE id = :newsId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newTitle', $newTitle);
        $stmt->bindParam(':newContent', $newContent);
        $stmt->bindParam(':newImageUrl', $newImageUrl);
        $stmt->bindParam(':newsId', $newsId);
        $stmt->execute();

        echo "News1 modifiés avec succès.".'<br>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_title"]) && isset($_POST["new_content"]) && isset($_POST["new_image_url5"])) {
        $newTitle = $_POST["new_title"];
        $newContent = $_POST["new_content"];
        $newImageUrl = $_POST["new_image_url5"];

        $newsId = 2;

        $sql = "UPDATE NEWS SET title = :newTitle, content = :newContent, image_url = :newImageUrl WHERE id = :newsId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newTitle', $newTitle);
        $stmt->bindParam(':newContent', $newContent);
        $stmt->bindParam(':newImageUrl', $newImageUrl);
        $stmt->bindParam(':newsId', $newsId);
        $stmt->execute();

        echo "News2 modifiés avec succès.".'<br>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_title"]) && isset($_POST["new_content"]) && isset($_POST["new_image_url6"])) {
        $newTitle = $_POST["new_title"];
        $newContent = $_POST["new_content"];
        $newImageUrl = $_POST["new_image_url6"];

        $newsId = 3;

        $sql = "UPDATE NEWS SET title = :newTitle, content = :newContent, image_url = :newImageUrl WHERE id = :newsId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newTitle', $newTitle);
        $stmt->bindParam(':newContent', $newContent);
        $stmt->bindParam(':newImageUrl', $newImageUrl);
        $stmt->bindParam(':newsId', $newsId);
        $stmt->execute();

        echo "News3 modifiés avec succès. ".'<br>';
    }





}
catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>back accueil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        h1{
            text-align: center;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }

        .form-container {
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #333;
        }

    </style>
</head>
<body>
    <h1>Admin Accueil</h1>
    <form method="POST" action="">
        <h2>Ajouter l'url d'une image</h2>

        <div class="form-group">
            <label for="image_url">Ajouter une image dans la base de données</label><br>
            <input type="text" id="image_url" name="image_url" placeholder="Entrez un url" required><br>
        </div>
        <input type="submit" value="Soumettre" name="ok">
    </form>

    <form method="POST" action="">
        <h2>Modifier l'image 1 du carousel</h2>

        <div class="form-group">
            <label for="image_url">Nouvelle image</label><br>
            <input type="text" id="image_url" name="new_image_url" placeholder="Entrez la nouvelle URL" required><br>
        </div>
        <input type="submit" value="Modifier" name="submit">
    </form>

    <form method="POST" action="">
        <h2>Modifier l'image 2 du carousel</h2>

        <div class="form-group">
            <label for="image_url">Nouvelle image</label><br>
            <input type="text" id="image_url" name="new_image_url1" placeholder="Entrez la nouvelle URL" required><br>
        </div>
        <input type="submit" value="Modifier" name="submit">
    </form>

    <form method="POST" action="">
        <h2>Modifier l'image 3 du carousel</h2>

        <div class="form-group">
            <label for="image_url">Nouvelle image</label><br>
            <input type="text" id="image_url" name="new_image_url2" placeholder="Entrez la nouvelle URL" required><br>
        </div>
        <input type="submit" value="Modifier" name="submit">
    </form>

    <form method="post" action="">
        <h2>Modifier le news 1</h2>
        <label for="new_title">Nouveau titre :</label><br>
        <input type="text" id="new_title" name="new_title" required><br><br>

        <label for="new_content">Nouveau contenu :</label><br>
        <textarea id="new_content" name="new_content" rows="4" required></textarea><br><br>

        <label for="new_image_url4">Nouvelle URL de l'image :</label><br>
        <input type="text" id="new_image_url4" name="new_image_url4" required><br><br>

        <input type="submit" value="Modifier la nouvelle">
        <input type="hidden" id="news_id" name="news_id" value="1">
    </form>

    <form method="post" action="">
        <h2>Modifier le news 2</h2>
        <label for="new_title">Nouveau titre :</label><br>
        <input type="text" id="new_title" name="new_title" required><br><br>

        <label for="new_content">Nouveau contenu :</label><br>
        <textarea id="new_content" name="new_content" rows="4" required></textarea><br><br>

        <label for="new_image_url5">Nouvelle URL de l'image :</label><br>
        <input type="text" id="new_image_url5" name="new_image_url5" required><br><br>

        <input type="submit" value="Modifier la nouvelle">
        <input type="hidden" id="news_id" name="news_id1" value="2">
    </form>

    <form method="post" action="">
        <h2>Modifier le news 3</h2>
        <label for="new_title">Nouveau titre :</label><br>
        <input type="text" id="new_title" name="new_title" required><br><br>

        <label for="new_content">Nouveau contenu :</label><br>
        <textarea id="new_content" name="new_content" rows="4" required></textarea><br><br>

        <label for="new_image_url6">Nouvelle URL de l'image :</label><br>
        <input type="text" id="new_image_url6" name="new_image_url6" required><br><br>

        <input type="submit" value="Modifier la nouvelle">
        <input type="hidden" id="news_id" name="news_id1" value="3">
    </form>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>