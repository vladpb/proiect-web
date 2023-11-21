<?php
require_once('vendor/autoload.php');

\Stripe\Stripe::setApiKey('sk_test_51HSHEDHXupmPYY82UXsYpVAFFTKwqMi2xjB6x3bGxn6qRzozmuWIFmjxi8maQdQBm5EZasuHLVbkc1E6sZS0GpQg00UBdJngFR');

try {
    $token = $_POST['stripeToken'];
    $charge = \Stripe\Charge::create([
        'amount' => $_POST['amount'],
        'currency' => 'usd',
        'description' => 'Bilet pentru eveniment',
        'source' => $token,
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {

    echo "Error: " . $e->getMessage();
}
if ($charge) {
    header('Location: confirmation.php');
}