<?php
session_start();


require_once 'Database.class.php';
require_once 'User.class.php';


$db = new Database();
$conn = $db->connect();
$userClass = new User($conn);


if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}


$userEmail = $_SESSION['email']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Profiel</title>
    <link rel="stylesheet" href="css/profiel.css"> 
<body>
   
    <header>
        <h1>Mijn Profiel</h1>
        <div class="header-right">
            <a href="logout.php" class="logout">Uitloggen</a>
        </div>
    </header>

    
        <div class="profiel-box">
            <p>Email: <?php echo htmlspecialchars($userEmail); ?></p>


            <p><a href="wachtwoord.php" class="btn">Wachtwoord resetten</a></p>

        
            <p><a href="index.php" class="back-button">Terug naar overzicht</a></p>
        </div>
    </div>
</body>
</html>
