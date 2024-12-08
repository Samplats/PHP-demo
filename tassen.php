<?php
session_start();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}


$host = 'localhost';
$dbname = 'webshop';
$username = 'root';
$password = '';

try {
    
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = "%" . $_GET['search'] . "%";
        $stmt = $conn->prepare("SELECT id, nam, price, image_url, description FROM products WHERE nam LIKE :search AND category = 'schoenen'");
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    } else {
        
        $stmt = $conn->prepare("SELECT id, nam, price, image_url FROM products WHERE categorie_id = 'basketbaltassen'");
    }

    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Verbinding mislukt: " . $e->getMessage();
}
?>


<?php include('nav.class.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/homepage.css"> 
    <title>Tassen Webshop</title>
</head>
<body>
    <header>
        <h1>GearUp</h1>
    
        <div class="header-search-container">
            <form method="GET" action="tassen.php" class="header-search-form">
                <input type="text" name="search" placeholder="Zoeken..." class="header-search-input">
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

 
    <?php Navigation::render(); ?>

    <main>
        <section class="products">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <article class="product">
                        <a href="details.php?id=<?php echo $product['id']; ?>" class="image-link">
                            <div class="image" style="background-image: url('<?php echo htmlspecialchars($product['image_url']); ?>');"></div>
                        </a>
                        <div class="details">
                            <h2 class="titel"><?php echo htmlspecialchars($product['nam']); ?></h2>
                            <p class="prijs">Prijs: €<?php echo number_format($product['price'], 2); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Geen producten gevonden.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
