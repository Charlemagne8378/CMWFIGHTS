<?php
$root = '';
for ($i = 0; $i < substr_count($_SERVER['REQUEST_URI'], '/'); $i++) {
    $root .= '../';
}
?>
<footer class="bg-dark text-white">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-6 mb-4">
                <h5 class="text-uppercase">Contact</h5>
                <p>5 Rue du Chalet, 75012, Paris</p>
                <p>Email: cmwfight@gmail.com</p>
                <p>Téléphone: +781813711</p>
            </div>
            <div class="col-md-6">
                <h5 class="text-uppercase">Service Client</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Contactez-nous</a></li>
                    <li><a href="boutique.php" class="text-white">Achetez nos vetement</a></li>
                </ul>
            </div>
        </div>
        <hr class="bg-light">
        <div class="social-icons">
            <a href="#"><i class="fab fa-instagram fa-lg text-white mr-3"></i></a>
            <a href="#"><i class="fab fa-tiktok fa-lg text-white"></i></a>
        </div>
        <p class="mt-3 mb-0 text-muted small">&copy; 2024 Company. All rights reserved.</p>
    </div>
</footer>

<style>
    .social-icons a {
        color: #fff;
    }

    .social-icons a:hover {
        color: #ccc;
    }

    .list-unstyled li {
        padding-bottom: 5px;
    }
</style>
