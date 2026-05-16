<?php

require 'config.php';

header('Content-Type: application/json');

$DOMAIN = 'http://localhost/php-practice-2/simple-stripe-subscription/';

$checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [[
        'price' => 'price_1TX4IjHxBBbYTrQQxOtKiWrR',
        'quantity' => 1,
    ]],

    'mode' => 'subscription',

    'success_url' => $DOMAIN . '/success.php',

    'cancel_url' => $DOMAIN . '/cancel.php',
]);

//header("HTTP/1.1 303 See Other");

header("Location: " . $checkout_session->url);
