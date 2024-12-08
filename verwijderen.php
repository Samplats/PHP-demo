<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];


    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    // Databaseverbinding
    $host = 'localhost';
    $dbname = 'webshop';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verwijder product uit de winkelmandje-database
        $stmt = $conn->prepare("DELETE FROM winkelmandje WHERE products_id = :product_id AND inloggen_id = :inloggen_id");
        $stmt->bindValue(':product_id', $product_id);
        $stmt->bindValue(':inloggen_id', $_SESSION['user_id']);
        $stmt->execute();

        header('Location: winkelmandje.php');
        exit;
    } catch (PDOException $e) {
        echo "Fout bij databaseverwijdering: " . $e->getMessage();
    }
} else {
    header('Location: winkelmandje.php');
    exit;
}
?>
