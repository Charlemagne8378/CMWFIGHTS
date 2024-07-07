<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '/var/www/html/vendor/autoload.php';
require '/var/www/html/stripe/config.php';
require 'Cart.php';
require 'StripePayment.php';
include_once "/var/www/html/con_dbb.php";
session_start();

use App\Cart;
use App\StripePayment;

$cart = new Cart();
$payment = new StripePayment(STRIPE_SECRET_KEY);
$payment->startPayment($cart);
?>
