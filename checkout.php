<?php
session_start();

require_once 'database.class.php';
$db = new Database();
$conn = $db->connect();


if (!isset($_SESSION['user_id'])) {
    echo "<h1>Je bent niet ingelogd!</h1>";
    echo "<a href='login.php'>Login hier</a>";
    exit;
}

if (empty($_SESSION['cart'])) {
    echo "<h1>Je winkelmandje is leeg.</h1>";
    echo "<a href='index.php'>Verder winkelen</a>";
    exit;
}


$product_ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));

$stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($product_ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


$totaal_prijs = 0;
foreach ($products as $product) {
    $product_id = $product['id'];
    $quantity = $_SESSION['cart'][$product_id]['quantity'];
    $totaal_prijs += $quantity * $product['price'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    try {
   
        $stmt = $conn->prepare("SELECT saldo FROM inloggen WHERE id = :user_id");
        $stmt->bindValue(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($totaal_prijs > $user['saldo']) {
            echo "<h1>Je saldo is onvoldoende voor deze bestelling.</h1>";
            echo "<p>Je hebt een saldo van €" . number_format($user['saldo'], 2) . ", maar je bestelling kost €" . number_format($totaal_prijs, 2) . ".</p>";
            
            echo "<a href='index.php'>Terug naar overzicht</a>";
            exit;
        }

    
        $stmt = $conn->prepare("UPDATE inloggen SET saldo = saldo - :totaal WHERE id = :user_id");
        $stmt->bindValue(':totaal', $totaal_prijs);
        $stmt->bindValue(':user_id', $_SESSION['user_id']);
        $stmt->execute();

      
        $stmt = $conn->prepare("SELECT saldo FROM inloggen WHERE id = :user_id");
        $stmt->bindValue(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['resterend_saldo'] = $user['saldo']; // Sla resterend saldo tijdelijk op in de sessie

       
        $_SESSION['cart'] = [];

        
        header("Location: bevestiging.php");
        exit;
    } catch (Exception $e) {
        echo "<h1>Er is een fout opgetreden bij de bestelling.</h1>";
        echo "<p>Foutmelding: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
    <header>
    <h1>Checkout</h1>
    </header>
    <h2>Overzicht van je bestelling:</h2>
    
    <table>
        <thead>
            <tr>
                <th>Afbeelding</th>
                <th>Product</th>
                <th>Aantal</th>
                <th>Maat</th>
                <th>Prijs</th>
                <th>Totaal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($products as $product): 
                $product_id = $product['id'];
                $quantity = $_SESSION['cart'][$product_id]['quantity'];
                $maat = $_SESSION['cart'][$product_id]['maat'];
                $subtotaal = $quantity * $product['price'];
            ?>
            <tr>
                <td>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Afbeelding van <?php echo htmlspecialchars($product['nam']); ?>" style="width: 80px; height: auto;">
                </td>
                <td><?php echo htmlspecialchars($product['nam']); ?></td>
                <td><?php echo $quantity; ?></td>
                <td><?php echo htmlspecialchars($maat); ?></td>
                <td>€<?php echo number_format($product['price'], 2); ?></td>
                <td>€<?php echo number_format($subtotaal, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <h2>Totaal: €<?php echo number_format($totaal_prijs, 2); ?></h2>
    
    <form method="POST">
        <button type="submit" name="confirm_order" style="background-color: green; color: white; padding: 10px; border: none; cursor: pointer;">Bevestig bestelling</button>
    </form>
</body>
</html>
