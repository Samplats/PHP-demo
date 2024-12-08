<?php
session_start();

require_once 'Database.class.php';
require_once 'User.class.php';

$db = new Database();
$conn = $db->connect();
$userClass = new User($conn);

if (!$userClass->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

try {
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = "%" . $_GET['search'] . "%";
        $stmt = $conn->prepare("SELECT id, nam, price, image_url FROM products WHERE nam LIKE :search");
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    } else {
        $stmt = $conn->prepare("SELECT id, nam, price, image_url FROM products");
    }
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Verbinding mislukt: " . $e->getMessage();
}

// Product toevoegen
if ($isAdmin && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    if (!empty($name) && !empty($price) && !empty($image_url)) {
        try {
            $stmt = $conn->prepare("INSERT INTO products (nam, price, image_url) VALUES (:name, :price, :image_url)");
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
            $stmt->execute();
            $successMessage = "Product succesvol toegevoegd!";
        } catch (PDOException $e) {
            $errorMessage = "Fout bij het toevoegen van product: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Alle velden zijn verplicht!";
    }
}

// Product verwijderen
if ($isAdmin && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $successMessage = "Product succesvol verwijderd!";
    } catch (PDOException $e) {
        $errorMessage = "Fout bij het verwijderen van product: " . $e->getMessage();
    }
}

// Product bijwerken
if ($isAdmin && isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    if (!empty($name) && !empty($price) && !empty($image_url)) {
        try {
            $stmt = $conn->prepare("UPDATE products SET nam = :name, price = :price, image_url = :image_url WHERE id = :id");
            $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
            $stmt->execute();
            $successMessage = "Product succesvol bijgewerkt!";
        } catch (PDOException $e) {
            $errorMessage = "Fout bij het bijwerken van product: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Alle velden zijn verplicht!";
    }
}

// Product toevoegen aan winkelmandje
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO winkelmandje (product_id, user_id) VALUES (:product_id, :user_id)");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $cartMessage = "Product succesvol toegevoegd aan winkelmandje!";
    } catch (PDOException $e) {
        $cartMessage = "Fout bij het toevoegen aan winkelmandje: " . $e->getMessage();
    }
}
?>

<?php include('nav.class.php'); ?>

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
        <div class="header-search-container">
            <form method="GET" action="index.php" class="header-search-form">
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
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <article class="product">
                        <a href="details.php?id=<?php echo $product['id']; ?>" class="image-link">
                            <div class="image" style="background-image: url('<?php echo htmlspecialchars($product['image_url']); ?>');"></div>
                        </a>
                        <div class="details">
                            <h2 class="titel"><?php echo htmlspecialchars($product['nam']); ?></h2>
                            <p class="prijs">Prijs: €<?php echo number_format($product['price'], 2); ?></p>
                            <form method="POST" action="index.php">
                                <input type="hidden" name="add_to_cart" value="1">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            </form>

                            <?php if ($isAdmin): ?>
                                <form method="POST" action="index.php" onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">
                                    <input type="hidden" name="delete_product" value="1">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn delete">Verwijderen</button>
                                </form>

                                <form method="POST" action="index.php" class="update-form">
                                    <input type="hidden" name="update_product" value="1">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="text" name="name" value="<?php echo htmlspecialchars($product['nam']); ?>" required>
                                    <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                                    <input type="url" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>
                                    <button type="submit" class="btn">Bijwerken</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Geen producten gevonden.</p>
            <?php endif; ?>

            <?php if ($isAdmin): ?>
                <section class="add-product">
                    <h2>Nieuw product toevoegen</h2>
                    <?php if (isset($successMessage)): ?>
                        <p class="success"><?php echo $successMessage; ?></p>
                    <?php elseif (isset($errorMessage)): ?>
                        <p class="error"><?php echo $errorMessage; ?></p>
                    <?php endif; ?>
                    <form method="POST" action="index.php" class="product-form">
                        <input type="text" name="name" placeholder="Productnaam" required>
                        <input type="number" name="price" step="0.01" placeholder="Prijs (€)" required>
                        <input type="url" name="image_url" placeholder="Afbeeldings-URL" required>
                        <button type="submit" name="add_product" class="btn">Product Toevoegen</button>
                    </form>
                </section>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
