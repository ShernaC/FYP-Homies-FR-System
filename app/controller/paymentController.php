<?php
// Check if autoload file exists
if (!file_exists('../vendor/autoload.php')) {
    die('Autoload file not found. Did you run `composer install`?');
}

require_once '../vendor/autoload.php';

// Check if stripe_config file is included
if (!file_exists('../config/stripe_config.php')) {
    die('Stripe configuration file not found.');
}

require_once '../config/stripe_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_checkout_session') {
    $subscriptionId = intval($_POST['subscriptionId']);
    $username = $_POST['username'];
    
    $amount = 0;
    $successUrl = '';
    $cancelUrl = '';
    $name = '';

    switch ($subscriptionId) {
        case 2:
            $amount = 5000; // Amount in cents
            $successUrl = 'http://localhost/otp_test/FYP-Homies-FR-System/app/view/payment_success.php?username=' . urlencode($username) . '&subscriptionId=2';
            $cancelUrl = 'http://localhost/otp_test/FYP-Homies-FR-System/app/view/payment_cancel.php?username=' . urlencode($username) . '&subscriptionId=2';
            $name = 'Small Business Plan';
            break;
        case 3:
            $amount = 10000;
            $successUrl = 'http://localhost/otp_test/FYP-Homies-FR-System/app/view/payment_success.php?username=' . urlencode($username) . '&subscriptionId=3';
            $cancelUrl = 'http://localhost/otp_test/FYP-Homies-FR-System/app/view/payment_cancel.php?username=' . urlencode($username) . '&subscriptionId=3';
            $name = 'Medium-Sized Business Plan';
            break;
        case 4:
            $amount = 12500;
            $successUrl = 'http://localhost/otp_test/FYP-Homies-FR-System/app/view/payment_success.php?username=' . urlencode($username) . '&subscriptionId=4';
            $cancelUrl = 'http://localhost/otp_test/FYP-Homies-FR-System/app/view/payment_cancel.php?username=' . urlencode($username) . '&subscriptionId=4';
            $name = 'Large Enterprise Plan';
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid subscription ID']);
            exit;
    }

    $paymentController = new PaymentController();
    $sessionId = $paymentController->createCheckoutSession($amount, 'sgd', $successUrl, $cancelUrl, $name);

    header('Content-Type: application/json');
    echo json_encode(['sessionId' => $sessionId]);
    exit;
}

class PaymentController {    
    public function createCheckoutSession($amount, $currency = 'sgd', $successUrl, $cancelUrl, $name) {
        if (!class_exists('\Stripe\Stripe')) {
            die('Stripe class not found. Please check the Stripe PHP library installation.');
        }

        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $name,
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);

        return $session->id;
    }
}

?>
