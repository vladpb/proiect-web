<?php
include('server.php');
include('header.php');

// Conectare la baza de date
$db = mysqli_connect('localhost', 'root', '', 'events');

// Inițializăm coșul în sesiune dacă nu există
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Adăugăm sau actualizăm biletul în coș
if (isset($_POST['ticket_id'])) {
    $ticketId = $_POST['ticket_id'];
    $found = false;

    foreach ($_SESSION['cart'] as $key => $value) {
        if (is_array($value) && isset($value['id']) && $value['id'] == $ticketId) {
            $_SESSION['cart'][$key]['quantity']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = ['id' => $ticketId, 'quantity' => 1];
    }
}

// Pregătim datele biletelor pentru afișare
$ticketData = [];
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    if (is_array($item) && isset($item['id'])) {
        $ticketId = $item['id'];

        $query = "SELECT * FROM evenimente WHERE id='$ticketId'";
        $result = mysqli_query($db, $query);
        $ticket = mysqli_fetch_assoc($result);

        if ($ticket) {
            $ticket['quantity'] = $item['quantity'];
            $ticketData[] = $ticket;
            $total += $ticket['pret_bilet'] * $item['quantity'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Coș de Cumpărături</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Coș de cumpărături</h2>
    </div>
    <table>
        <tr><th>Titlu eveniment</th><th>Preț bilet</th><th>Cantitate</th><th>Acțiuni</th></tr>
        <?php foreach ($ticketData as $ticket): ?>
            <tr>
                <td><?php echo htmlspecialchars($ticket['titlu']); ?></td>
                <td><?php echo htmlspecialchars($ticket['pret_bilet']); ?></td>
                <td><?php echo htmlspecialchars($ticket['quantity']); ?></td>
                <td>
                    <form action="update_cart.php" method="post">
                        <input type="number" name="quantity" value="<?php echo htmlspecialchars($ticket['quantity']); ?>" min="1">
                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                        <input type="submit" name="update" value="Update">
                    </form>
                    <form action="remove_from_cart.php" method="post">
                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                        <input type="submit" name="remove" value="Remove">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="content">
        <p>Total: <?php echo $total; ?></p>
    </div>
    <form action="charge.php" method="post">
        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="pk_test_51HSHEDHXupmPYY82tB7pMN7BJirx3u2HT5oWXoXjs5FgaMO6Xmzld3pqe8Mkts06Y7SyWlcpeVKmwWhcu26OTVlo001bth8MH3"
                data-amount="<?php echo $total * 100; ?>"
                data-name="Coș de cumpărături"
                data-description="Plată pentru bilete"
                data-currency="usd"></script>
        <input type="hidden" name="amount" value="<?php echo $total * 100; ?>">
    </form>
</body>
</html>
