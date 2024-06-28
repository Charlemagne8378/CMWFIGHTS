<nav class="sidebar">
    <div class="text-center mb-4">
        <img src="../../Images/cmw_icon.png" alt="Logo" width="128" height="128">
    </div>
    <ul class="nav flex-column">
        <?php
        $current_page = basename($_SERVER['PHP_SELF'], '.php');
        $active_page = $current_page;

        $menuItems = [
            '../../admin/admin' => ['label' => 'Admin', 'icon' => 'bi bi-house-door'],
            '../../admin/utilisateurs' => ['label' => 'Utilisateurs', 'icon' => 'bi bi-person-lines-fill'],
            '../../admin/combat' => ['label' => 'Combats', 'icon' => 'bi bi-shield-fill', 'color' => 'btn-shield'],
            '../../admin/evenements' => ['label' => 'Événements', 'icon' => 'bi bi-calendar-event'],
            '../../admin/modifier_utilisateur' => ['label' => 'Modifier le compte', 'icon' => 'bi bi-pencil-square'],
            '../../admin/classement' => ['label' => 'Classement', 'icon' => 'bi bi-bar-chart'],
            '../../admin/combattants' => ['label' => 'Combattants', 'icon' => 'bi bi-people'],
            '../../admin/candidature' => ['label' => 'Candidature', 'icon' => 'bi bi-file-earmark-text'],
            '../../admin/billetterie' => ['label' => 'Billetterie', 'icon' => 'bi bi-ticket'],
            '../../admin/service_client' => ['label' => 'Service Client', 'icon' => 'bi bi-telephone'],
            '../../admin/image' => ['label' => 'Image', 'icon' => 'bi bi-image'],
            '../../admin/newsletters' => ['label' => 'Newsletters', 'icon' => 'bi bi-envelope'],
            '../../admin/captcha' => ['label' => 'Captcha', 'icon' => 'bi bi-shield-lock'],
            '../../admin/accueil' => ['label' => 'Accueil', 'icon' => 'bi bi-house-door'],
            '../../admin/logs' => ['label' => 'Logs', 'icon' => 'bi bi-journal'],
            '../../admin/permissions' => ['label' => 'Permissions utilisateurs', 'icon' => 'bi bi-shield-lock'],
            '../../admin/bdd' => ['label' => 'Base de données', 'icon' => 'bi bi-gear'],
            '../../admin/erreur' => ['label' => 'Log d\'erreur', 'icon' => 'bi bi-exclamation-circle'],
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
    <div class="d-flex align-items-center justify-content-center mt-4 mb-4">
        <i class="bi bi-sun-fill text-warning me-2"></i>
        <input class="form-check-input" type="checkbox" id="darkModeToggle">
        <label class="ms-2 mb-0" for="darkModeToggle"><i class="bi bi-moon-fill text-dark"></i></label>
    </div>


    <div class="account-box collapse" id="account-box">
        <a href="/">Page Utilisateur</a>
        <a href="../../pages/compte/settings">Paramètres</a>
        <a href="../../auth/logout.php">Déconnexion</a>
    </div>
    <button class="btn btn-primary btn-block account-btn" data-bs-toggle="collapse" data-bs-target="#account-box">
        Compte
    </button>
</nav>

<script>
    const darkModeToggle = document.getElementById('darkModeToggle');
    const applyTheme = (theme) => {
        document.documentElement.setAttribute('data-bs-theme', theme);
        if (theme === 'dark') {
            darkModeToggle.checked = true;
        } else {
            darkModeToggle.checked = false;
        }
    };
    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);

    darkModeToggle.addEventListener('change', () => {
        const theme = darkModeToggle.checked ? 'dark' : 'light';
        applyTheme(theme);
        localStorage.setItem('theme', theme);
    });
</script>
<script>
        function toggleAccountBox() {
            var accountBox = document.querySelector('.account-box');
            accountBox.classList.toggle('show');
        }

        document.addEventListener('DOMContentLoaded', function() {
            var accountBtn = document.querySelector('.account-btn');
            accountBtn.addEventListener('click', toggleAccountBox);
        });
</script>