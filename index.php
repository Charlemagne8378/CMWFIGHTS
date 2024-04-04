<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CMWFIGHT</title>
  <meta name="description" content="Organisation amateur de street fight MMA et Boxe Anglaise.">
  <meta name="robots" content="index, follow">

  <!-- Link vers Bootstrap CSS -->
  <link rel="icon" type="image/png" sizes="32x32" href="../Images/cmwicon.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Style pour positionner header et footer fixe */
   

    .container h2{
        color: white;
    }

   

    /* Style pour ajuster la position du contenu pour éviter l'obstruction par le header et le footer */
    body {
      padding-top: 70px; /* Ajustez en fonction de la hauteur de votre en-tête */
      padding-bottom: 70px; /* Ajustez en fonction de la hauteur de votre pied de page */
      background-color: black;
    }

    /* Style pour centrer verticalement la bannière */
    .banner {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 500px; /* Ajustez la hauteur selon votre besoin */
      background-color: black;
    }
  </style>
</head>
<body>
<?php include'header.php' ?>

  <!-- Bannière avec image -->
  <div class="banner">
    <img src="Images/bannierefidirs0.png" alt="Bannière" class="img-fluid">
  </div>

  <!-- Section des nouvelles (avec 4 cartes) -->
  <section class="news py-5">
    <div class="container">
      <h2 class="text-center mb-4">Dernières Nouvelles</h2>
      <div class="row">
        <!-- Carte 1 -->
        <div class="col-md-3">
          <div class="card">
            <img src="Images/belaidfabio.png" class="card-img-top" alt="Image 1">
            <div class="card-body">
              <p class="card-text">Belaid sera présent sur la carte du 9 Février ! Il affrontera Fabio en co main-event</p>
            </div>
          </div>
        </div>
        <!-- Carte 2 -->
        <div class="col-md-3">
          <div class="card">
            <img src="Images/fidirs0.png" class="card-img-top" alt="Image 2">
            <div class="card-body">
              <p class="card-text">Le main-event entre FID et Irs0 est enfin officiel ! Il s'affronteront en Boxe anglaise le 9 février à fight room pour la ceinture des -75kg.</p>
            </div>
          </div>
        </div>
        <!-- Carte 3 -->
        <div class="col-md-3">
          <div class="card">
            <img src="Images/aliasilyas.png" class="card-img-top" alt="Image 3">
            <div class="card-body">
              <p class="card-text">Alias l'emporte à la décision unanime face à Ilyas et devient le nouveau champion des -65kg. Les deux combattants nous ont livrés un combat intense sur 5 round qui n'a pas déçu les supporters présent.</p>
            </div>
          </div>
        </div>
        <!-- Carte 4 -->
        <div class="col-md-3">
          <div class="card">
            <img src="Images/basileacceuil.png" class="card-img-top" alt="Image 4">
            <div class="card-body">
              <p class="card-text">Basile surprend encore ! Il vient de remporter son premier combat en boxe anglaise face au redoutable S2R à la décision Unanime.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section des sponsors -->
  <section class="sponsors bg-light py-5">
    <div class="container">
      <h2 class="text-center mb-4">Nos Sponsors</h2>
      <div class="row">
        <!-- Colonne 1 -->
        <div class="col-md-3">
          <img src="sponsor1.jpg" alt="Sponsor 1" class="img-fluid">
        </div>
        <!-- Colonne 2 -->
        <div class="col-md-3">
          <img src="sponsor2.jpg" alt="Sponsor 2" class="img-fluid">
        </div>
        <!-- Colonne 3 -->
        <div class="col-md-3">
          <img src="sponsor3.jpg" alt="Sponsor 3" class="img-fluid">
        </div>
        <!-- Colonne 4 -->
        <div class="col-md-3">
          <img src="sponsor4.jpg" alt="Sponsor 4" class="img-fluid">
        </div>
      </div>
    </div>
  </section>

  <!-- Pied de page -->
  

  <!-- Script Bootstrap JS (jQuery requis) -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
