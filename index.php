<?php
$servername = "localhost";
$username = "root";
$password = "cmwfight75012";
$dbname = "kiwi";

try {
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql1 = "SELECT image_url FROM IMG WHERE id = '1'";
  $img1 = $pdo->query($sql1)->fetch();
  $image_fond = $img1['image_url'];
  $sql2 = "SELECT image_url FROM IMG WHERE id = '2'";
  $img2 = $pdo->query($sql2)->fetch();
  $image1 = $img2['image_url'];
  $sql3 = "SELECT image_url FROM IMG WHERE id = '3'";
  $img3 = $pdo->query($sql3)->fetch();
  $image2 = $img3['image_url'];

  $sql4 = "SELECT title FROM NEWS WHERE id = '1'";
  $title1 = $pdo->query($sql4)->fetch();
  $titre1 = $title1['title'];

  $sql5 = "SELECT title FROM NEWS WHERE id = '2'";
  $title2 = $pdo->query($sql5)->fetch();
  $titre2 = $title2['title'];

  $sql6 = "SELECT title FROM NEWS WHERE id = '3'";
  $title3 = $pdo->query($sql6)->fetch();
  $titre3 = $title3['title'];

  $sql7 = "SELECT content FROM NEWS WHERE id = '1'";
  $content1 = $pdo->query($sql7)->fetch();
  $texte1 = $content1['content'];

  $sql8 = "SELECT content FROM NEWS WHERE id = '2'";
  $content2 = $pdo->query($sql8)->fetch();
  $texte2 = $content2['content'];

  $sql9 = "SELECT content FROM NEWS WHERE id = '3'";
  $content3 = $pdo->query($sql9)->fetch();
  $texte3 = $content3['content'];

  $sql10 = "SELECT image_url FROM NEWS WHERE id = '1'";
  $image_url1 = $pdo->query($sql10)->fetch();
  $imag1 = $image_url1['image_url'];

  $sql11 = "SELECT image_url FROM NEWS WHERE id = '2'";
  $image_url2 = $pdo->query($sql11)->fetch();
  $imag2 = $image_url2['image_url'];

  $sql12 = "SELECT image_url FROM NEWS WHERE id = '3'";
  $image_url3 = $pdo->query($sql12)->fetch();
  $imag3 = $image_url3['image_url'];
}
catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
session_start();

if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']['type'] === 'banni') {
  header('Location:banni');
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil</title>
  <link rel="icon" type="image/png" sizes="32x32" href="Images/cmwicon.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style/index.css">
  <style>
    .quoi a{
      text-decoration : none;
    }
  </style>
</head>
<body>
  <?php include 'cookies.php'?>
  <?php include 'header.php'?>


    <div class="container mt-5">
        <h1 class="text-center animated-title">CMW</h1>
    </div>

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" action="essaie.php">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="<?php echo $image_fond; ?>" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="<?php echo $image1; ?>" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="<?php echo $image2; ?>" alt="Third slide">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>



<div class="quoi">
  <h2><a href="boutique">Shop</a></h2>
  <h2>Qui somme nous?</h2>
  <div class="textG">
      <p>
      L'organisation CMW, est une nouvelle entité dédiée à la promotion et au développement du MMA et de la boxe amateur. Notre objectif principal est d'offrir une plateforme dynamique et inclusive pour les combattants passionnés de sports de combat, où l'apprentissage, la compétition et le fair-play sont au cœur de nos activités.
      </p>
  </div>
  <div class="textM">
      <p>
      Chez CMW, nous croyons en la valeur de l'entraînement rigoureux, du respect mutuel et de la camaraderie qui se développe au sein de notre communauté sportive. En tant qu'organisation axée sur l'excellence et l'éthique sportive, nous mettons un accent particulier sur la sécurité des athlètes, en veillant à ce que les compétitions se déroulent dans un environnement contrôlé et équitable.
      </p>
  </div>

  <div class="textD">
      <p>
      Que vous soyez un combattant en herbe cherchant à améliorer vos compétences ou un amateur passionné de spectacles de haut niveau, CMW vous offre l'opportunité de vous épanouir dans le monde exaltant des arts martiaux mixtes et de la boxe. Rejoignez-nous pour vivre une expérience unique où la détermination, la discipline et la passion se rencontrent sur le chemin de la victoire. CMW, là où les guerriers se réunissent pour repousser leurs limites et atteindre de nouveaux sommets dans le monde exigeant des sports de combat.
      </p>
  </div>

</div>

<div class="youtube">
  <?php
  $apikey = "AIzaSyBVbAeQf_I_dhtiYiRtKsuLeUZhHG1kPo0";
  $channelId = "UC--2hj3m2lPsio7mmO8gumQ";
  $url = "https://www.googleapis.com/youtube/v3/search?key=$apikey&channelId=$channelId&part=snippet,id&order=date&maxResults=1";

  $response = file_get_contents($url);
  $data = json_decode($response);

  foreach($data->items as $item){
    $videoId = $item->id->videoId;
    $videoTitle = $item->snippet->title;
    $videoThumbnail = $item->snippet->thumbnails->default->url;

    echo "<h3>$videoTitle</h3>";
    echo "<iframe width='560' height='315' src='https://www.youtube.com/embed/$videoId' frameborder='0' allowfullscreen></iframe>";

  }
  ?>
</div>


<h2 class="ttr">Dernières Nouvelles</h2>
<div class="nvl">

  <div class="card" style="width: 18rem;">
    <img src="<?php echo $imag1; ?>" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title"><?php echo $titre1; ?></h5>
      <p class="card-text"><?php echo $texte1; ?></p>

    </div>
  </div>

  <div class="card" style="width: 18rem;">
    <img src="<?php echo $imag2; ?>" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title"><?php echo $titre2; ?></h5>
      <p class="card-text"><?php echo $texte2; ?></p>

    </div>
  </div>

  <div class="card" style="width: 18rem;">
    <img src="<?php echo $imag3; ?>" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title"><?php echo $titre3; ?></h5>
      <p class="card-text"><?php echo $texte3; ?></p>

    </div>
  </div>

</div>

<div id="nav">
    
</div>

<div id="foot">
   
</div>


<div class="ancre" id="ancre-bas">
    <a href="#foot"><img src="../../Images/bas.png" alt="Aller en Bas" class="icone"></a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php include 'footer.php' ?>
</body>
</html>