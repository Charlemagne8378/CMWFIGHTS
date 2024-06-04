<nav class="sidebar">
    <div class="text-center mb-3">
      <img src="../Images/cmwnoir.png" alt="Logo" style="width: 128px; height: 128px;">
    </div>
    <ul class="nav flex-column">
      <li class="nav-item <?php if ($active_page == 'admin') echo 'active'; ?>">
        <a class="nav-link" href="admin">
          <i class="bi bi-house-door"></i>
          <span class="ms-2">Admin</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'utilisateurs') echo 'active'; ?>">
        <a class="nav-link" href="utilisateurs">
          <i class="bi bi-person-lines-fill"></i>
          <span class="ms-2">Utilisateurs</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'evenements') echo 'active'; ?>">
        <a class="nav-link" href="evenements">
          <i class="bi bi-calendar-event"></i>
          <span class="ms-2">Événements</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'modifier_utilisateur') echo 'active'; ?>">
        <a class="nav-link" href="modifier_utilisateur">
          <i class="bi bi-pencil-square"></i>
          <span class="ms-2">Modifier le compte</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'classement') echo 'active'; ?>">
        <a class="nav-link" href="classement">
          <i class="bi bi-bar-chart"></i>
          <span class="ms-2">Classement</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'combattants') echo 'active'; ?>">
        <a class="nav-link" href="combattants">
          <i class="bi bi-people"></i>
          <span class="ms-2">Combattants</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'candidature') echo 'active'; ?>">
        <a class="nav-link" href="candidature">
          <i class="bi bi-file-earmark-text"></i>
          <span class="ms-2">Candidature</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'billetterie') echo 'active'; ?>">
        <a class="nav-link" href="billetterie">
          <i class="bi bi-ticket"></i>
          <span class="ms-2">Billetterie</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'service_client') echo 'active'; ?>">
        <a class="nav-link" href="service_client">
          <i class="bi bi-telephone"></i>
          <span class="ms-2">Service Client</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'image') echo 'active'; ?>">
        <a class="nav-link" href="image">
          <i class="bi bi-image"></i>
          <span class="ms-2">Image</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'newsletters') echo 'active'; ?>">
        <a class="nav-link" href="newsletters">
          <i class="bi bi-envelope"></i>
          <span class="ms-2">Newsletters</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'captcha') echo 'active'; ?>">
        <a class="nav-link" href="captcha">
          <i class="bi bi-shield-lock"></i>
          <span class="ms-2">Captcha</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'accueil') echo 'active'; ?>">
        <a class="nav-link" href="accueil">
          <i class="bi bi-house-door"></i>
          <span class="ms-2">Accueil</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'logs') echo 'active'; ?>">
        <a class="nav-link" href="logs">
          <i class="bi bi-journal"></i>
          <span class="ms-2">Logs</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'permissions') echo 'active'; ?>">
        <a class="nav-link" href="permissions">
          <i class="bi bi-shield-lock"></i>
          <span class="ms-2">Permissions utilisateurs</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'bdd') echo 'active'; ?>">
        <a class="nav-link" href="bdd">
          <i class="bi bi-gear"></i>
          <span class="ms-2">Base de données</span>
        </a>
      </li>
      <li class="nav-item <?php if ($active_page == 'error') echo 'active'; ?>">
        <a class="nav-link" href="error">
          <i class="bi bi-exclamation-circle"></i>
          <span class="ms-2">Log d'erreur</span>
          
        </a>
      </li>
    </ul>
    <div class="account-box">
      <a href="../pages/compte/settings">Paramètres</a>
      <a href="../auth/logout.php">Déconnexion</a>
    </div>
    <button class="btn btn-primary btn-block account-btn">
      Compte
    </button>
  </nav>
