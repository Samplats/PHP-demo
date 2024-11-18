<?php
session_start();

// Check if the user is logged in
if ($_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Database connection details
$host = 'localhost'; // Your MySQL server
$dbname = 'webshop'; // Your database name
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password

try {
    // Create a PDO instance (connect to the database)
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch products from the database
    $stmt = $conn->prepare("SELECT nam, price, image_url FROM products");
    $stmt->execute();

    // Fetch all products as an associative array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
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
        <div class= "icons">
        <img src="images/user.svg" alt="">
        <img src="images/shopping-bag.svg" alt="">
        <a href="logout.php" class="logout">Uitloggen</a> 
        </div>
    </header>

    <nav>
       
        <a href="index.php?option=all">Alles</a>
        <a href="index.php?option=schoenen">Schoenen</a>
        <a href="index.php?option=tassen">Tassen</a>
        <a href="index.php?option=basketballen">Basketballen</a>
        <a href="index.php?option=accessoires">Accessoires</a>
    </nav>

    <main>
        <section class="products">
            <?php foreach ($products as $product): ?>
              
                    <article class="product">
                        <div class="image" style="background-image: url('<?php echo $product['image_url']; ?>')"></div>
                        <div class="details">
                            <h2 class="titel"><?php echo $product['nam']; ?></h2>
                            <p class="prijs">Prijs: €<?php echo number_format($product['price'], 2); ?></p>
                            <button class="btn">Voeg toe aan winkelwagentje</button>
                        </div>
                    </article>
        
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
