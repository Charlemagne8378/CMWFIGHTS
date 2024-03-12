  <?php
  require_once '../config/config.php';
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte']['Type'] != 'admin') {
      header('Location: ../auth/connexion');
      exit();
  }

  $pdo = null;
  ?>

  <!DOCTYPE html>
  <html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Administration</title>
    <link rel="icon" type="image/png" sizes="64x64" href="../Images/cmwicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
      body {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          height: 100vh;
          margin: 0;
      }

      header {
          margin-bottom: 20px;
      }

      .btn-square {
          height: 100px;
          margin-bottom: 10px;
      }

      .btn-container {
          display: flex;
          flex-wrap: wrap;
          justify-content: space-evenly;
          margin: 10px;
      }

      .btn-container .col-md-4 {
          flex: 0 0 33.33333%;
          max-width: 33.33333%;
          text-align: center; /* Ajout de centrage du texte */
      }

      .logout-btn-container {
          display: flex;
          justify-content: flex-end;
          margin-top: 10px;
      }

      .btn-users,
      .btn-events,
      .btn-password,
      .btn-ranking,
      .btn-fighter,
      .btn-application,
      .btn-ticketing,
      .btn-service-client,
      .btn-image,
      .btn-logout {
          width: 100%;
          padding: 10px;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          font-size: 16px;
          margin-top: 10px;
      }

      .btn-users { background-color: #3498db; color: #fff; }
      .btn-events { background-color: #2ecc71; color: #fff; }
      .btn-password { background-color: #e74c3c; color: #fff; }
      .btn-ranking { background-color: #9b59b6; color: #fff; }
      .btn-fighter { background-color: #f39c12; color: #fff; }
      .btn-application { background-color: #c0392b; color: #fff; }
      .btn-ticketing { background-color: #1abc9c; color: #fff; }
      .btn-service-client { background-color: #7f8c8d; color: #fff; }
      .btn-image { background-color: #34495e; color: #fff; }
      .btn-logout { background-color: #95a5a6; color: #fff; }

      @media screen and (max-width: 768px) {
          .btn-users,
          .btn-events,
          .btn-password,
          .btn-ranking,
          .btn-fighter,
          .btn-application,
          .btn-ticketing,
          .btn-service-client,
          .btn-image,
          .btn-logout {
              font-size: 14px;
              margin-top: 5px;
          }
      }
  </style>









  </head>
  <body>

    <div class="container mt-5">
      <h2 class="text-center mb-4">Page d'Administration</h2>
      
      <div class="btn-container">
        <div class="col-md-4">
          <a href="utilisateurs" class="btn btn-users btn-block mb-2 btn-square">
            <i class="fas fa-users fa-3x"></i><br>
            Utilisateurs
          </a>
        </div>
        <div class="col-md-4">
          <a href="evenements" class="btn btn-events btn-block mb-2 btn-square">
            <i class="fas fa-calendar-alt fa-3x"></i><br>
            Événements
          </a>
        </div>
        <div class="col-md-4">
          <a href="modifier_utilisateur" class="btn btn-password btn-block mb-2 btn-square">
            <i class="fas fa-key fa-3x"></i><br>
            Modifier le compte
          </a>
        </div>
        <div class="col-md-4">
          <a href="classement" class="btn btn-ranking btn-block mb-2 btn-square">
            <i class="fas fa-trophy fa-3x"></i><br>
            Classement
          </a>
        </div>
        <div class="col-md-4">
          <a href="combattants" class="btn btn-fighter btn-block mb-2 btn-square">
            <i class="fas fa-fist-raised fa-3x"></i><br>
            Combattant
          </a>
        </div>
        <div class="col-md-4">
          <a href="candidature" class="btn btn-application btn-block mb-2 btn-square">
            <i class="fas fa-file-alt fa-3x"></i><br>
            Candidature
          </a>
        </div>
        <div class="col-md-4">
          <a href="billetterie" class="btn btn-ticketing btn-block mb-2 btn-square">
            <i class="fas fa-ticket-alt fa-3x"></i><br>
            Billetterie
          </a>
        </div>
        <div class="col-md-4">
          <a href="service_client" class="btn btn-service-client btn-block mb-2 btn-square">
            <i class="fas fa-headset fa-3x"></i><br>
            Service Client
          </a>
        </div>
        <div class="col-md-4">
          <a href="image" class="btn btn-image btn-block mb-2 btn-square">
            <i class="fas fa-image fa-3x"></i><br>
            Image
          </a>
        </div>
      </div>

      <!-- Bouton Logout en bas à droite -->
      <div class="logout-btn-container">
        <a href="/auth/logout.php" class="btn btn-logout btn-square">
          <i class="fas fa-sign-out-alt fa-3x"></i><br>
          Logout
        </a>
      </div>
      
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="alert alert-primary text-center" role="alert" id="squareMessage">
            Cliquez sur les boutons pour accéder aux fonctionnalités!
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
      // Ajoute un événement de clic à chaque bouton
      const userBtn = document.getElementById('userBtn');
      const eventBtn = document.getElementById('eventBtn');
      const logoutBtn = document.getElementById('logoutBtn');
      const passwordBtn = document.getElementById('passwordBtn');
      const rankingBtn = document.getElementById('rankingBtn');
      const fighterBtn = document.getElementById('fighterBtn');
      const applicationBtn = document.getElementById('applicationBtn');
      const ticketingBtn = document.getElementById('ticketingBtn');
      const serviceClientBtn = document.getElementById('serviceClientBtn');
      const imageBtn = document.getElementById('imageBtn');

      userBtn.addEventListener('click', () => {
        updateSquareMessage('Accéder à la gestion des utilisateurs');
      });

      // Autres événements de clic pour les autres boutons...

      logoutBtn.addEventListener('click', () => {
        updateSquareMessage('Déconnecté avec succès');
      });

      passwordBtn.addEventListener('click', () => {
        updateSquareMessage('Changer le mot de passe');
      });

      rankingBtn.addEventListener('click', () => {
        updateSquareMessage('Accéder au classement');
      });

      fighterBtn.addEventListener('click', () => {
        updateSquareMessage('Accéder à la gestion des combattants');
      });

      applicationBtn.addEventListener('click', () => {
        updateSquareMessage('Accéder à la gestion des candidatures');
      });

      ticketingBtn.addEventListener('click', () => {
        updateSquareMessage('Accéder à la billetterie');
      });

      serviceClientBtn.addEventListener('click', () => {
        updateSquareMessage('Accéder au service client');
      });

      imageBtn.addEventListener('click', () => {
        updateSquareMessage('Accéder à la gestion des images');
      });

      // Met à jour le message du carré
      function updateSquareMessage(message) {
        const squareMessage = document.getElementById('squareMessage');
        squareMessage.innerHTML = message;
        squareMessage.classList.remove('alert-primary');
        squareMessage.classList.add('alert-success');
      }
    </script>

  </body>
  </html>
