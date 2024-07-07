<?php
namespace App;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripePayment {
    private $clientSecret;

    public function __construct($clientSecret) {
        $this->clientSecret = $clientSecret;
        Stripe::setApiKey($this->clientSecret);
    }

    public function startPayment(Cart $cart) {
        $cart_ID = $cart->getID();
        $products = $cart->getProducts();
        $line_items = [];

        foreach ($products as $id => $quantity) {
            $product = $this->getProductDetails($id);

            if ($product) {
                $line_items[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $product['name'],
                        ],
                        'unit_amount' => $product['price'] * 100,
                    ],
                    'quantity' => $quantity,
                ];
            }
        }

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => 'https://www.cmwfight.fr/stripe/success.php',
                'cancel_url' => 'https://www.cmwfight.fr/stripe/cancel.php',
                'billing_address_collection' => 'required',
                'shipping_address_collection' => ['allowed_countries' => ['FR']],
                'metadata' => ['cart_id' => $cart_ID]
            ]);

            $cart->setSessionID($session->id);
            header("HTTP/1.1 303 See Other");
            header("Location: " . $session->url);
            exit;
        } catch (Exception $e) {
            die("Erreur de création de session : " . $e->getMessage());
        }
    }

    private function getProductDetails($id) {

        return [
            'name' => 'Tshirt en Jersey CMW X INOFLEX',
            'price' => 50
        ];
    }
}
?>
