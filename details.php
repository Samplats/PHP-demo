<?php
session_start();

// Controleer of een product ID is meegegeven
if (!isset($_GET['id'])) {
    exit("Product niet gevonden.");
}

// Databaseverbinding
$host = 'localhost';
$dbname = 'webshop';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Haal gegevens van het product op
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        exit("Product niet gevonden.");
    }

} catch (PDOException $e) {
    echo "Fout bij verbinding: " . $e->getMessage();
}

// Als het formulier is ingediend, voeg het product toe aan de winkelmand
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Haal de product ID en maat op uit het formulier
    $product_id = $_POST['product_id'];
    $maat = isset($_POST['maat']) ? $_POST['maat'] : null;

    // Voeg het product toe aan de sessie-winkelmand
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'quantity' => 1,
            'maat' => $maat,
        ];
    } else {
        // Verhoog de hoeveelheid als het product al in de winkelmand zit
        $_SESSION['cart'][$product_id]['quantity']++;
    }

    // Toon een alert dat het product is toegevoegd
    echo "<script>alert('Product succesvol toegevoegd aan winkelmandje!');</script>";
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/detail.css">
    <title><?php echo htmlspecialchars($product['nam']); ?></title>
</head>
<body>
    <header>
        <h1>GearUp</h1>
        <!-- Zoekbalk toevoegen -->
        <div class="header-search-container">
            <form class="header-search-form" action="search.php" method="get">
                <input type="text" name="query" class="header-search-input" placeholder="Zoeken...">
            </form>
        </div>
        <div class="icons">
            <a href="profiel.php">
                <img src="images/user.svg" alt="Gebruiker">
            </a>
            <a href="winkelmandje.php">
                <img src="images/shopping-bag.svg" alt="Winkelmandje">
            </a>
            <a href="logout.php" class="logout">Uitloggen</a>
        </div>
    </header>

    <main>
        <div class="detail-container">
            <div class="product-image-container">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" class="product-image">
            </div>
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['nam']); ?></h2>
                <p class="product-price">Prijs: €<?php echo number_format($product['price'], 2); ?></p>
                <h3>Beschrijving:</h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>

                <form action="" method="post">
                    <!-- Alleen tonen als het een tenue is -->
                    <?php if ($product['categorie_id'] === 'tenues'): ?>
                        <h3>Maat:</h3>
                        <select name="maat" class="maat-dropdown">
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                        </select>
                    <?php endif; ?>

                    <!-- Alleen tonen als het een basketbalschoen is -->
                    <?php if ($product['categorie_id'] === 'basketbalschoenen'): ?>
                        <h3>Maat</h3>
                        <input type="number" name="maat" class="maat-input" min="0" max="60" placeholder="Vul je maat in" required>
                    <?php endif; ?>

                    <!-- Voeg de submit-button toe -->
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <button type="submit" class="btn">Voeg toe aan winkelwagentje</button>
                </form>
            </div>
        </div>
        <a href="index.php" class="back-button">Terug naar overzicht</a>
    </main>
</body>
</html>
