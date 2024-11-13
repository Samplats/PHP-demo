<?php
session_start();
if ($_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit; // Voorkomt verdere uitvoering na redirect
}

// Standaard "all" instellen als er geen optie is
$option = $_GET['option'] ?? 'all';

// Array met producten
$products = [
    [
        'title' => 'ANTA KAI 1',
        'option' => 'schoenen',
        'price' => 89.99,
        'image' => 'https://anta.com/cdn/shop/files/1_ed60f72d-cc70-4be1-a65c-9b5c3c6d10c7_600x600.jpg?v=1709768964'
    ],
    [
        'title' => 'Nie PG 6',
        'option' => 'schoenen',
        'price' => 139.99,
        'image' => 'https://i.ebayimg.com/images/g/IjsAAOSwhFBjkzv7/s-l1200.jpg'
    ],
    [
        'title' => 'Lebron XXI Premium',
        'option' => 'schoenen',
        'price' => 119.99,
        'image' => 'https://bouncewear.com/cdn/shop/files/AURORA_FV7275-401_PHSRH000-1000.jpg?v=1720100123'
    ],
    [
        'title' => 'Edward 1 low',
        'option' => 'schoenen',
        'price' => 129.99,
        'image' => 'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/dada649cd7164e32b2fe6ed0f8178133_9366/Anthony_Edwards_1_Low_Basketball_Shoes_Green_JI4066_01_standard.jpg'
    ],
    [
        'title' => 'Kobe 8',
        'option' => 'schoenen',
        'price' => 149.99,
        'image' => 'https://bouncewear.com/cdn/shop/files/AURORA_HF9550-400_PHSRH000-1000.jpg?v=1724665229'
    ],
    [
        'title' => 'Nike Ja Morent ONE',
        'option' => 'schoenen',
        'price' => 109.99,
        'image' => 'https://flavourfashion.ca/cdn/shop/files/AURORA_FD6565-300_PHSLH000-2000_1024x1024@2x.jpg?v=1700163994'
    ],
    [
        "title" => "Nike basketbalgaer",
        "option" => "tassen",
        "price" => 49.99,
        "image" => "https://static.nike.com/a/images/f_auto/dpr_3.0,cs_srgb/w_403,c_limit/b4584c7c-a89a-4cce-aa09-2bf9717edec7/nike-s-beste-tassen-voor-basketbalgear.jpg" // Vervang deze door een echte URL
    ],
    [
        "title" => "Nike Elite basketball",
        "option" => "tassen",
        "price" => 55.99,
        "image" => "https://static.nike.com/a/images/f_auto/dpr_3.0,cs_srgb/w_403,c_limit/0ae0de4c-8229-494e-9eeb-e91c6f2ef2e7/nike-s-beste-tassen-voor-basketbalgear.jpg" // Vervang deze door een echte URL
    ],
    [
        "title" => "Kipsta",
        "option" => "tassen",
        "price" => 39.99,
        "image" => "https://contents.mediadecathlon.com/p1354011/k$74c3fae2d3e6305aadbe074f26f4f04d/sq/8e356351-1447-4c04-8856-2aaedff4ea39.jpg?format=auto&f=800x0" // Vervang deze door een echte URL
    ],
    [
        "title" => "Spalding",
        "option" => "tassen",
        "price" => 29.99,
        "image" => "https://shop.kangoeroesbasket.be/wp-content/uploads/40222104-BKWH-kangoeroes.jpg" // Vervang deze door een echte URL
    ],
    [
        "title" => "Mier",
        "option" => "tassen",
        "price" => 89.99,
        "image" => 'https://www.miersports.com/cdn/shop/files/large-basketball-backpack-sports-bag-with-ball-compartment-black-40l-mier-31615429509254.jpg?v=1688698331'// Vervang deze door ee echte URL
    ],
    [
        "title" => "Nike Unisex Elite hoops",
        "option" => "tassen",
        "price" => 29.99,
        "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQcmeKYlL8B_L_b-qQEiINZYxb-mIhCAM3Jww&s" // Vervang deze door een echte URL
    ],
    [
        "title" => "Wilson",
        "option" => "basketballen",
        "price" => 39.99,
        "image" => "https://target.scene7.com/is/image/Target/GUEST_20affc7e-e0d7-4eb6-a6f3-68d13520f8be?wid=488&hei=488&fmt=pjpeg" // Vervang deze door een echte URL
    ],
    [
        "title" => "Tarmak",
        "option" => "basketballen",
        "price" => 29.99,
        "image" => "https://contents.mediadecathlon.com/p2154421/k$1c4bb46d855d4303ac9a44f4f00b2d92/sq/bbeb5bc7-be16-4b36-8089-8afa6e7a75ec.jpg?format=auto&f=800x0" // Vervang deze door een echte URL
    ],
    [
        "title" => "Molten",
        "option" => "basketballen",
        "price" => 49.99,
        "image" => "https://cdn.webshopapp.com/shops/8898/files/428582418/356x473x2/molten-bg3000-indoor-outdoor.jpg" // Vervang deze door een echte URL
    ],
    [
        "title" => "Spalding",
        "option" => "basketballen",
        "price" => 39.99,
        "image" => "https://www.delsport.com/Cached/378080/canvas/510x765/37103759_0.jpg" // Vervang deze door een echte URL
    ],
];
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
    </header>

    <nav>
       
        <a href="index.php?option=all">Alles</a>
        <a href="index.php?option=schoenen">Schoenen</a>
        <a href="index.php?option=tassen">Tassen</a>
        <a href="index.php?option=basketballen">Basketballen</a>
    </nav>

    <main>
        <section class="products">
            <?php foreach ($products as $product): ?>
                <?php if ($option === 'all' || $option === $product['option']): ?>
                    <article class="product">
                        <div class="image" style="background-image: url('<?php echo $product['image']; ?>')"></div>
                        <div class="details">
                            <h2 class="titel"><?php echo $product['title']; ?></h2>
                            <p class="prijs">Prijs: €<?php echo number_format($product['price'], 2); ?></p>
                            <button class="btn">Voeg toe aan winkelwagentje</button>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
