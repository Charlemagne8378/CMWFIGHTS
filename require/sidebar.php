<?php
$active_page = basename($_SERVER['PHP_SELF']);
$active_page = str_replace('.php', '', $active_page);
?>

<nav class="sidebar">
  <div class="text-center mb-3">
    <img src="../Images/cmwnoir.png" alt="Logo" style="width: 128px; height: 128px;">
  </div>
  <a class="nav-link <?php if ($active_page == 'admin') echo 'active'; ?>" href="admin">
    <i class="bi bi-house-door"></i>
    <span class="ml-2 d-none d-sm-inline">Admin</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'utilisateurs') echo 'active'; ?>" href="utilisateurs">
    <i class="bi bi-person-lines-fill"></i>
    <span class="ml-2 d-none d-sm-inline">Utilisateurs</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'evenements') echo 'active'; ?>" href="evenements">
    <i class="bi bi-calendar-event"></i>
    <span class="ml-2 d-none d-sm-inline">Événements</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'modifier_utilisateur') echo 'active'; ?>" href="modifier_utilisateur">
    <i class="bi bi-pencil-square"></i>
    <span class="ml-2 d-none d-sm-inline">Modifier le compte</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'classement') echo 'active'; ?>" href="classement">
    <i class="bi bi-bar-chart"></i>
    <span class="ml-2 d-none d-sm-inline">Classement</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'combattants') echo 'active'; ?>" href="combattants">
    <i class="bi bi-people"></i>
    <span class="ml-2 d-none d-sm-inline">Combattants</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'candidature') echo 'active'; ?>" href="candidature">
    <i class="bi bi-file-earmark-text"></i>
    <span class="ml-2 d-none d-sm-inline">Candidature</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'billetterie') echo 'active'; ?>" href="billetterie">
    <i class="bi bi-ticket"></i>
    <span class="ml-2 d-none d-sm-inline">Billetterie</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'service_client') echo 'active'; ?>" href="service_client">
    <i class="bi bi-telephone"></i>
    <span class="ml-2 d-none d-sm-inline">Service Client</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'image') echo 'active'; ?>" href="image">
    <i class="bi bi-image"></i>
    <span class="ml-2 d-none d-sm-inline">Image</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'newsletters') echo 'active'; ?>" href="newsletters">
    <i class="bi bi-envelope"></i>
    <span class="ml-2 d-none d-sm-inline">Newsletters</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'captcha') echo 'active'; ?>" href="captcha">
    <i class="bi bi-shield-lock"></i>
    <span class="ml-2 d-none d-sm-inline">Captcha</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'accueil') echo 'active'; ?>" href="accueil">
    <i class="bi bi-house-door"></i>
    <span class="ml-2 d-none d-sm-inline">Accueil</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'logs') echo 'active'; ?>" href="logs">
    <i class="bi bi-journal"></i>
    <span class="ml-2 d-none d-sm-inline">Logs</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'permissions') echo 'active'; ?>" href="permissions">
    <i class="bi bi-shield-lock"></i>
    <span class="ml-2 d-none d-sm-inline">Permissions utilisateurs</span>
  </a>
  <a class="nav-link <?php if ($active_page == 'bdd') echo 'active'; ?>" href="bdd">
    <i class="bi bi-gear"></i>
    <span class="ml-2 d-none d-sm-inline">Base de données</span>
  </a>
  <div class="account-box">
            <a href="../compte/settings">Paramètres</a>
            <a href="../auth/logout.php">Déconnexion</a>
  </div>
  <button class="btn btn-primary btn-block account-btn">
        Compte
  </button>
</nav>
