<?php

require 'vendor/autoload.php';

http_response_code(200);

$input = file_get_contents('php://input');
$event = json_decode($input);
$stripe = new \Stripe\StripeClient('sk_test_s84ulO6WuXfGl1M8gDGNFIF800G4OhThSy');
var_dump($event);
$stripe->paymentIntents->retrieve(
	$event->id,
	[]
);
//$event = $stripe->events->retrieve(
//	$event->id,
//	[]
//);
//var_dump($event);



// /v1/payment_intents/:id/confirm