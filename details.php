<?php
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
            <img src="images/user.svg" alt="Gebruiker">
            <img src="images/shopping-bag.svg" alt="Winkelmandje">
            <a href="logout.php" class="logout">Uitloggen</a>
        </div>
    </header>

    <main>
        <div class="detail-container">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" class="product-image">
           
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['nam']); ?></h2>
                <p class="product-price">Prijs: €<?php echo number_format($product['price'], 2); ?></p>
                <h2><?php echo htmlspecialchars($product['description']); ?></h2>
                <button class="btn">Voeg toe aan winkelwagentje</button>
            </div>
        </div>
        <a href="index.php" class="back-button">Terug naar overzicht</a>
    </main>
</body>
</html>
