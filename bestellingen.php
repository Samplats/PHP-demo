<?php
session_start();

// Laad de database-klasse
require_once 'Database.class.php';

// Maak verbinding met de database
$db = new Database();
$conn = $db->connect();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}

// Haal de bestellingen van de ingelogde gebruiker op
$inloggen_id = $_SESSION['inloggen_id']; // Haal de ingelogde gebruiker op
$stmt = $conn->prepare('SELECT * FROM bestellingen WHERE inloggen_id = :inloggen_id ORDER BY datum DESC');
$stmt->bindValue(':inloggen_id', $inloggen_id);
$stmt->execute();
$bestellingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Bestellingen</title>
    <link rel="stylesheet" href="css/bestellingen.css">
</head>
<body>
    <header>
        <h1>Mijn Bestellingen</h1>
        <div class="header-right">
            <a href="logout.php" class="logout">Uitloggen</a>
        </div>
    </header>

    <div class="container">
        <?php if (count($bestellingen) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Totaal Bedrag</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bestellingen as $bestelling): ?>
                        <tr>
                            <td><?php echo date('d-m-Y H:i', strtotime($bestelling['datum'])); ?></td>
                            <td>€<?php echo number_format($bestelling['totaal_bedrag'], 2); ?></td>
                            <td><a href="bestelling_details.php?bestelling_id=<?php echo $bestelling['id']; ?>" class="btn">Bekijk details</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Je hebt nog geen bestellingen geplaatst.</p>
        <?php endif; ?>
    </div>
</body>
</html>
