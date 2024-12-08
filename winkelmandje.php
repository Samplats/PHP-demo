<?php
session_start();

// Laad de database-klasse
require_once 'database.class.php';
$db = new Database();
$conn = $db->connect();

// Zorg ervoor dat $_SESSION['cart'] altijd een array is
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    echo "<h1>Je bent niet ingelogd!</h1>";
    echo "<a href='login.php'>Login hier</a>";
    exit;
}

// Haal het saldo van de ingelogde gebruiker op
$stmt = $conn->prepare("SELECT saldo FROM inloggen WHERE id = :id");
$stmt->bindValue(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$saldo = $user ? $user['saldo'] : 0;

// Controleer of er iets in de winkelmand zit
if (empty($_SESSION['cart'])) {
    echo "<h1 class='cart-empty'>Je winkelmandje is leeg.</h1>";
    echo "<a href='index.php' class='btn-back'>Verder winkelen</a>";
    exit;
}

// Haal productgegevens uit de database
$product_ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));

$stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($product_ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verwijder product uit winkelmand bij klik op verwijderen-knop
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $product_id_to_remove = $_POST['remove_id'];
    if (isset($_SESSION['cart'][$product_id_to_remove])) {
        unset($_SESSION['cart'][$product_id_to_remove]);
        header("Location: winkelmandje.php");
        exit();
    }
}

// Verwerk de + en - knoppen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id']) && isset($_POST['action'])) {
    $product_id = $_POST['update_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$product_id])) {
        if ($action === 'plus') {
            $_SESSION['cart'][$product_id]['quantity']++;
        } elseif ($action === 'minus' && $_SESSION['cart'][$product_id]['quantity'] > 0) {
            $_SESSION['cart'][$product_id]['quantity']--;
            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
    header("Location: winkelmandje.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelmandje</title>
    <link rel="stylesheet" href="css/winkel.css">
</head>
<body>
    <header>
    <h1>Winkelmandje</h1>
    </header>
    
    <!-- Toon saldo van ingelogde gebruiker -->
    <h2>Huidig saldo: €<?php echo number_format($saldo, 2); ?></h2>

    <table>
        <thead>
            <tr>
                <th>Afbeelding</th>
                <th>Product</th>
                <th>Aantal</th>
                <th>Maat</th>
                <th>Prijs</th>
                <th>Totaal</th>
                <th>Actie</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totaal_prijs = 0;
            foreach ($products as $product): 
                $product_id = $product['id'];

                // Zorg voor veilige toegang tot de winkelmand
                $quantity = isset($_SESSION['cart'][$product_id]['quantity']) ? $_SESSION['cart'][$product_id]['quantity'] : 0;
                $maat = isset($_SESSION['cart'][$product_id]['maat']) ? $_SESSION['cart'][$product_id]['maat'] : '-';
                $subtotaal = $quantity * $product['price'];
                $totaal_prijs += $subtotaal;
            ?>
            <tr>
                <td>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Afbeelding van <?php echo htmlspecialchars($product['nam']); ?>" style="width: 80px; height: auto;">
                </td>
                <td><?php echo htmlspecialchars($product['nam']); ?></td>
                
                <!-- Knoppen voor plus en min -->
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="update_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="action" value="minus">
                        <button type="submit" style="background-color: red; color: white; padding: 5px 10px; border: none; cursor: pointer;">-</button>
                    </form>
                    <span><?php echo $quantity; ?></span>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="update_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="action" value="plus">
                        <button type="submit" style="background-color: green; color: white; padding: 5px 10px; border: none; cursor: pointer;">+</button>
                    </form>
                </td>

                <td><?php echo htmlspecialchars($maat); ?></td>
                <td>€<?php echo number_format($product['price'], 2); ?></td>
                <td>€<?php echo number_format($subtotaal, 2); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="remove_id" value="<?php echo $product_id; ?>">
                        <button type="submit" style="background-color: red; color: white; padding: 5px 10px; border: none; cursor: pointer;">Verwijderen</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="button-container">
    <a href="checkout.php" class="btn">Afrekenen</a>
    <a href="index.php" class="back-button">Verder winkelen</a>
</div>

</body>
</html>