<?php
namespace App;

class Cart {
    public function getID() {
        return session_id();
    }

    public function getProducts() {
        return $_SESSION['panier'] ?? [];
    }

    public function setSessionID($sessionID) {
        $_SESSION['stripe_session_id'] = $sessionID;
    }
}
?>
