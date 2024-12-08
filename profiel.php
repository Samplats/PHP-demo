<?php
session_start();

// Laad de benodigde klassen
require_once 'Database.class.php';
require_once 'User.class.php';

// Maak verbinding met de database
$db = new Database();
$conn = $db->connect();
$userClass = new User($conn);

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}

// Haal de e-mail van de ingelogde gebruiker uit de sessie
$userEmail = $_SESSION['email']; // Zorg dat dit overeenkomt met login.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Profiel</title>
    <link rel="stylesheet" href="css/profiel.css"> <!-- Gebruik de bestaande stijl -->
</head>
<body>
    <!-- Header met uitgelijnde uitlogknop -->
    <header>
        <h1>Mijn Profiel</h1>
        <div class="header-right">
            <a href="logout.php" class="logout">Uitloggen</a>
        </div>
    </header>

    <!-- Container voor profielinhoud met een grijze achtergrond -->
    <div class="container">
        <div class="profiel-box">
            <p>Email: <?php echo htmlspecialchars($userEmail); ?></p>


            <p><a href="wachtwoord.php" class="btn">Wachtwoord resetten</a></p>

        
            <p><a href="index.php" class="back-button">Terug naar overzicht</a></p>
        </div>
    </div>
</body>
</html>
