require_once('vendor/autoload.php');

\Stripe\Stripe::setApiKey('pk_test_51HSHEDHXupmPYY82tB7pMN7BJirx3u2HT5oWXoXjs5FgaMO6Xmzld3pqe8Mkts06Y7SyWlcpeVKmwWhcu26OTVlo001bth8MH3');

$token = $_POST['stripeToken'];
$charge = \Stripe\Charge::create([
    'amount' => $_POST['amount'],
    'currency' => 'usd',
    'description' => 'Bilet pentru eveniment',
    'source' => $token,
]);