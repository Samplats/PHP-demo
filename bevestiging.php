<?php
session_start();


if (!isset($_SESSION['resterend_saldo'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bevestiging</title>
    <link rel="stylesheet" href="css/bevestig.css">
</head>
<header>
    <h1>Bedankt voor je bestelling!</h1>
    </header>
<body>
    
    <h2>Dit is nu je resterende saldo: €<?php echo number_format($_SESSION['resterend_saldo'], 2); ?></h2>
    <a href="index.php">Verder winkelen</a>
</body>
</html>
