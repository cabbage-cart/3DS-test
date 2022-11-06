<?php 

use Slim\Http\Request;
use Slim\Http\Response;
use Stripe\Stripe;
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

$app = new \Slim\App;

// add new card to new/existing user
$app->get('/', function(Request $request, Response $response, $args) {
    $response->getBody()->write(file_get_contents("../../client/step_1_save_card.html"));
    return $response;
});

// charge later in admin/backend
$app->get('/admin', function(Request $request, Response $response, $args) {
    $response->getBody()->write(file_get_contents("../../client/step_2_charge_later.html"));
    return $response;
});

// recovery flow
$app->get('/complete', function(Request $request, Response $response, $args) {
    $response->getBody()->write(file_get_contents("../../client/step_3_complete.html"));
    return $response;
});

// create setupIntent on the server
$app->get('/setup_intents', function(Request  $request, Response $response) {
    $intent = \Stripe\SetupIntent::create();
    return $response->withJson($intent);
});

// create payment intents
$app->post('/payment_intents', function(Request $request, Response $response) {
    $params = json_decode($request->getBody());
    try {
        $intent = \Stripe\PaymentIntent::create([
            'amount' => 9900,
            'currency' => 'aud',        
            'customer' => $params->customer,
            'payment_method' => $params->payment_method,
            'off_session' => true, // we assume we've authenticated this card and have permission to charge
            'confirm' => true,
        ]);
        return $response->withJson($intent);
    } catch (Exception $e) {
        return $response->withJson($e -> getJsonBody());
    }
});

$app->get('/get-customer-payment-methods', function(Request $request, Response $response) {
    $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
    $customer = $stripe->customers->allPaymentMethods(
        $request->getQueryParam('customer_id'),
        ['type' => 'card']
    );
    return $response->withJson($customer);
});

// get payment intents
$app->get('/payment_intents', function(Request $request, Response $response, array $args) {
    $intent = \Stripe\PaymentIntent::retrieve(
        $request->getQueryParam('id')
    );
    return $response->withJson($intent);
});

// create new user and associate payment method
$app->post('/create_customer', function(Request $request, Response $response) use ($app) {
    $params = json_decode($request -> getBody());
    try {
        $customer = \Stripe\Customer::create([
            'payment_method' => $params->payment_method,
            'email' => $params->email,
        ]);
    } catch (Exception $e) {
        return $response->withJson($e -> getJsonBody());
    }
    return $response->withJson($customer);
});

// or if you already have an existing customer
// \Stripe\Stripe::setApiKey('STRIPE_SECRET_KEY');

// $payment_method = \Stripe\PaymentMethod::retrieve('{{PAYMENT_METHOD_ID}}');
// $payment_method->attach(['customer' => '{{CUSTOMER_ID}}']);

$app->run();

?>
