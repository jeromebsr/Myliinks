<?php

require '../../../../vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_s84ulO6WuXfGl1M8gDGNFIF800G4OhThSy');
header('Content-Type: application/json');
$YOUR_DOMAIN = 'https://myliinks.com';

$checkout_session = \Stripe\Checkout\Session::create([
	'billing_address_collection' => 'required',
	'payment_method_types' => ['card'],
	'line_items' => [[
		'price' => 'price_1IGstXD6Eyqp829axj4Rey92',
		'quantity' => 1,
	]],
	'mode' => 'subscription',
	'success_url' => $YOUR_DOMAIN . '/success',
	'cancel_url' => $YOUR_DOMAIN . '/cancel',
]);

echo json_encode(['id' => $checkout_session->id]);
