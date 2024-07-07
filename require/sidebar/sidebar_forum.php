<nav class="sidebar">
    <div class="text-center mb-4">
        <img src="../../Images/cmw_icon.png" alt="Logo" width="128" height="128">
    </div>
    <ul class="nav flex-column">
        <?php
        $current_page = basename($_SERVER['PHP_SELF'], '.php');
        $active_page = $current_page;

        $menuItems = [
            'settings' => ['label' => 'Paramètres', 'icon' => 'bi bi-gear', 'url' => '../compte/settings'],
            'preferences' => ['label' => 'Préférences', 'icon' => 'bi bi-sliders', 'url' => '../compte/preferences'],
            'dessin' => ['label' => 'Dessin', 'icon' => 'bi bi-pencil', 'url' => '../compte/dessin'],
            'chat' => ['label' => 'Tchat en direct', 'icon' => 'bi bi-chat-dots', 'url' => 'chat'],
            'forum' => ['label' => 'Forum', 'icon' => 'bi bi-chat-dots', 'url' => 'forum'], 
        ];
        
        

        foreach ($menuItems as $page => $item) {
            $activeClass = ($active_page == $page) ? 'active' : '';
            $url = isset($item['url']) ? $item['url'] : $page; // Utiliser l'URL spécifiée ou le nom de la page par défaut
            echo "<li class='nav-item'>";
            echo "<a class='nav-link $activeClass' href='$url'>";
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
        <a href="/">Page d'accueil</a>
        <a href="../../auth/logout.php">Déconnexion</a>
    </div>
    <button class="btn btn-primary btn-block account-btn" data-bs-toggle="collapse" data-bs-target="#account-box">
        Compte
    </button>
</nav>
<script src="../../scripts/compte.js"></script>
