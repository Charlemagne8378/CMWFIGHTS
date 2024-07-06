<?php
require_once '../require/config/config.php';
session_start();
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['type'] !== 'admin') {
    header('Location: ../auth/connexion');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["image_url"])) {
        $img = $_POST["image_url"];
        echo "L'URL de l'image saisie est : " . $img;

        $stmt = $pdo->prepare("INSERT INTO IMG (image_url) VALUES (:image_url)");
        $stmt->bindParam(':image_url', $img);
        $stmt->execute();

        exit();
    }

    if (isset($_POST["new_image_url"])) {
        $newImageUrl = $_POST["new_image_url"];
        $sql = "UPDATE IMG SET image_url = :newImageUrl WHERE id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newImageUrl', $newImageUrl);
        $stmt->execute();
        echo "L'URL de l'image pour l'ID 1 a été modifiée avec succès.". '<br>';
    }

    if (isset($_POST["new_image_url1"])) {
        $newImageUrl1 = $_POST["new_image_url1"];
        $sql = "UPDATE IMG SET image_url = :newImageUrl1 WHERE id = 2";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newImageUrl1', $newImageUrl1);
        $stmt->execute();
        echo "L'URL de l'image pour l'ID 2 a été modifiée avec succès.". '<br>';
    }

    if (isset($_POST["new_image_url2"])) {
        $newImageUrl2 = $_POST["new_image_url2"];
        $sql = "UPDATE IMG SET image_url = :newImageUrl2 WHERE id = 3";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newImageUrl2', $newImageUrl2);
        $stmt->execute();
        echo "L'URL de l'image pour l'ID 3 a été modifiée avec succès.". '<br>';
    }

    if (isset($_POST["new_title"]) && isset($_POST["new_content"]) && isset($_POST["new_image_url4"])) {
        $newTitle = $_POST["new_title"];
        $newContent = $_POST["new_content"];
        $newImageUrl4 = $_POST["new_image_url4"];
        $newsId = 1;

        $sql = "UPDATE NEWS SET title = :newTitle, content = :newContent, image_url = :newImageUrl WHERE id = :newsId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newTitle', $newTitle);
        $stmt->bindParam(':newContent', $newContent);
        $stmt->bindParam(':newImageUrl', $newImageUrl4);
        $stmt->bindParam(':newsId', $newsId);
        $stmt->execute();
        echo "News1 modifiés avec succès.".'<br>';
    }

    if (isset($_POST["new_title"]) && isset($_POST["new_content"]) && isset($_POST["new_image_url5"])) {
        $newTitle = $_POST["new_title"];
        $newContent = $_POST["new_content"];
        $newImageUrl5 = $_POST["new_image_url5"];
        $newsId = 2;

        $sql = "UPDATE NEWS SET title = :newTitle, content = :newContent, image_url = :newImageUrl WHERE id = :newsId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newTitle', $newTitle);
        $stmt->bindParam(':newContent', $newContent);
        $stmt->bindParam(':newImageUrl', $newImageUrl5);
        $stmt->bindParam(':newsId', $newsId);
        $stmt->execute();
        echo "News2 modifiés avec succès.".'<br>';
    }

    if (isset($_POST["new_title"]) && isset($_POST["new_content"]) && isset($_POST["new_image_url6"])) {
        $newTitle = $_POST["new_title"];
        $newContent = $_POST["new_content"];
        $newImageUrl6 = $_POST["new_image_url6"];
        $newsId = 3;

        $sql = "UPDATE NEWS SET title = :newTitle, content = :newContent, image_url = :newImageUrl WHERE id = :newsId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newTitle', $newTitle);
        $stmt->bindParam(':newContent', $newContent);
        $stmt->bindParam(':newImageUrl', $newImageUrl6);
        $stmt->bindParam(':newsId', $newsId);
        $stmt->execute();
        echo "News3 modifiés avec succès. ".'<br>';
    }
}

$sql = "SELECT * FROM IMG";
$req = $pdo->query($sql);
$results = $req->fetchAll();

foreach($results as $rep){
    echo "L'URL de l'image  : " . $rep['image_url'] . '<br>';
}

$sql1 = "SELECT image_url FROM IMG WHERE id = 1";
$img1 = $pdo->query($sql1)->fetch();
$image_fond = $img1['image_url'];
$sql2 = "SELECT image_url FROM IMG WHERE id = 2";
$img2 = $pdo->query($sql2)->fetch();
$image1 = $img2['image_url'];
$sql3 = "SELECT image_url FROM IMG WHERE id = 3";
$img3 = $pdo->query($sql3)->fetch();
$image2 = $img3['image_url'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>back accueil</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../style/accueil.css">
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
            <label for="new_image_url">Nouvelle image</label><br>
            <input type="text" id="new_image_url" name="new_image_url" placeholder="Entrez la nouvelle URL" required><br>
        </div>
        <input type="submit" value="Modifier" name="submit">
    </form>

    <form method="POST" action="">
        <h2>Modifier l'image 2 du carousel</h2>
        <div class="form-group">
            <label for="new_image_url1">Nouvelle image</label><br>
            <input type="text" id="new_image_url1" name="new_image_url1" placeholder="Entrez la nouvelle URL" required><br>
        </div>
        <input type="submit" value="Modifier" name="submit">
    </form>

    <form method="POST" action="">
        <h2>Modifier l'image 3 du carousel</h2>
        <div class="form-group">
            <label for="new_image_url2">Nouvelle image</label><br>
            <input type="text" id="new_image_url2" name="new_image_url2" placeholder="Entrez la nouvelle URL" required><br>
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
</body>
</html>
