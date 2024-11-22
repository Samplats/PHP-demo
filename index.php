<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Databaseverbinding details
$host = 'localhost'; // Jouw MySQL-server
$dbname = 'webshop'; // Naam van jouw database
$username = 'root'; // Jouw MySQL-gebruikersnaam
$password = ''; // Jouw MySQL-wachtwoord

try {
    // Verbind met de database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Haal producten op uit de database
    $stmt = $conn->prepare("SELECT id, nam, price, image_url FROM products");
    $stmt->execute();

    // Haal alle producten op als associatieve array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Verbinding mislukt: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/homepage.css">
    <title>Webshop</title>
</head>
<body>
    <header>
        <h1>GearUp</h1>
        <div class="icons">
            <img src="images/user.svg" alt="Gebruiker">
            <img src="images/shopping-bag.svg" alt="Winkelmandje">
            <a href="logout.php" class="logout">Uitloggen</a> 
        </div>
    </header>

    <nav>
        <a href="index.php?option=all">Alles</a>
        <a href="index.php?option=schoenen">Schoenen</a>
        <a href="index.php?option=tassen">Tassen</a>
        <a href="index.php?option=basketballen">Basketballen</a>
        <a href="index.php?option=accessoires">Tenues</a>
        <a href="index.php?option=accessoires">Accessoires</a>
    </nav>

    <main>
        <section class="products">
            <?php foreach ($products as $product): ?>
                <article class="product">
                    <!-- Klikbare afbeelding -->
                    <a href="detail.php?id=<?php echo $product['id']; ?>" class="image-link">
                        <div class="image" style="background-image: url('<?php echo htmlspecialchars($product['image_url']); ?>');"></div>
                    </a>
                    <div class="details">
                        <h2 class="titel"><?php echo htmlspecialchars($product['nam']); ?></h2>
                        <p class="prijs">Prijs: €<?php echo number_format($product['price'], 2); ?></p>
                        <button class="btn">Voeg toe aan winkelwagentje</button>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
