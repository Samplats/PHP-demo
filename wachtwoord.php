<?php
session_start();

// Laad benodigde klassen
require_once 'Database.class.php';
require_once 'User.class.php';

// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}

// Maak verbinding met de database
$db = new Database();
$conn = $db->connect();
$userClass = new User($conn);

// Haal ingelogde gebruiker op
$userEmail = $_SESSION['email'];

// Initialiseer berichten
$success = $error = '';

// Verwerk het formulier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Controleer of de nieuwe wachtwoorden overeenkomen
    if ($newPassword !== $confirmPassword) {
        $error = "De nieuwe wachtwoorden komen niet overeen.";
    } elseif (!$userClass->checkPassword($userEmail, $currentPassword)) {
        $error = "Het huidige wachtwoord is onjuist.";
    } else {
        // Update het wachtwoord
        if ($userClass->updatePassword($userEmail, $newPassword)) {
            $success = "Je wachtwoord is succesvol gewijzigd!";
        } else {
            $error = "Er is iets misgegaan bij het wijzigen van je wachtwoord.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord Wijzigen</title>
    <link rel="stylesheet" href="css/wachtwoord.css"> 
</head>
<body>
    <div class="container">
        <h1>Wachtwoord Wijzigen</h1>

   
        <?php if ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="current_password">Huidig Wachtwoord:</label>
            <input type="password" name="current_password" id="current_password" required>

            <label for="new_password">Nieuw Wachtwoord:</label>
            <input type="password" name="new_password" id="new_password" required>

            <label for="confirm_password">Bevestig Nieuw Wachtwoord:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit">Wachtwoord Wijzigen</button>
        </form>

        <p><a href="index.php">Terug naar overzicht</a></p>
    </div>
</body>
</html>
