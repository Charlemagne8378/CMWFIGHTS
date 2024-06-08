<nav class="sidebar">
    <div class="text-center mb-4">
        <img src="../Images/cmwnoir.png" alt="Logo" width="128" height="128">
    </div>
    <ul class="nav flex-column">
        <?php
        $current_page = basename($_SERVER['PHP_SELF'], '.php');
        $active_page = $current_page;

        $menuItems = [
            'admin' => ['label' => 'Admin', 'icon' => 'bi bi-house-door'],
            'utilisateurs' => ['label' => 'Utilisateurs', 'icon' => 'bi bi-person-lines-fill'],
            'evenements' => ['label' => 'Événements', 'icon' => 'bi bi-calendar-event'],
            'modifier_utilisateur' => ['label' => 'Modifier le compte', 'icon' => 'bi bi-pencil-square'],
            'classement' => ['label' => 'Classement', 'icon' => 'bi bi-bar-chart'],
            'combattants' => ['label' => 'Combattants', 'icon' => 'bi bi-people'],
            'candidature' => ['label' => 'Candidature', 'icon' => 'bi bi-file-earmark-text'],
            'billetterie' => ['label' => 'Billetterie', 'icon' => 'bi bi-ticket'],
            'service_client' => ['label' => 'Service Client', 'icon' => 'bi bi-telephone'],
            'image' => ['label' => 'Image', 'icon' => 'bi bi-image'],
            'newsletters' => ['label' => 'Newsletters', 'icon' => 'bi bi-envelope'],
            'captcha' => ['label' => 'Captcha', 'icon' => 'bi bi-shield-lock'],
            'accueil' => ['label' => 'Accueil', 'icon' => 'bi bi-house-door'],
            'logs' => ['label' => 'Logs', 'icon' => 'bi bi-journal'],
            'permissions' => ['label' => 'Permissions utilisateurs', 'icon' => 'bi bi-shield-lock'],
            'bdd' => ['label' => 'Base de données', 'icon' => 'bi bi-gear'],
            'error' => ['label' => 'Log d\'erreur', 'icon' => 'bi bi-exclamation-circle'],
        ];

        foreach ($menuItems as $page => $item) {
            $activeClass = ($active_page == $page) ? 'active' : '';
            echo "<li class='nav-item'>";
            echo "<a class='nav-link $activeClass' href='$page'>";
            echo "<i class='{$item['icon']}'></i>";
            echo "<span class='ms-2'>{$item['label']}</span>";
            echo "</a>";
            echo "</li>";
        }
        ?>
    </ul>
    <div class="form-check form-switch d-flex justify-content-center align-items-center">
        <i class="bi bi-sun-fill text-warning me-2"></i>
        <input class="form-check-input" type="checkbox" id="darkModeToggle">
        <i class="bi bi-moon-fill text-dark ms-2"></i>
    </div>

    <div class="account-box collapse" id="account-box">
        <a href="../pages/compte/settings">Paramètres</a>
        <a href="../auth/logout.php">Déconnexion</a>
    </div>
    <button class="btn btn-primary btn-block account-btn" data-bs-toggle="collapse" data-bs-target="#account-box">
        Compte
    </button>
</nav>

<script>
    const darkModeToggle = document.getElementById('darkModeToggle');
    

    darkModeToggle.addEventListener('change', () => {
        if (darkModeToggle.checked) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-bs-theme', 'light');
        }
    });
</script>
