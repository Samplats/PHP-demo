<?php
session_start();
// Functie om te controleren of inloggen mogelijk is
function canLogin($email, $password) {
    try {
        // Verbind met de database
        $conn = new PDO('mysql:host=localhost;dbname=webshop', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Haal de gebruiker op uit de database
        $statement = $conn->prepare('SELECT * FROM inloggen WHERE email = :email');
        $statement->bindValue(':email', $email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Controleer of de gebruiker bestaat en het wachtwoord correct is
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Login succesvol, retourneer gebruikersgegevens
        }

        return false; // Geen match gevonden
    } catch (PDOException $e) {
        // Toon foutmelding bij databaseproblemen
        echo "Er is een fout opgetreden: " . $e->getMessage();
        return false;
    }
}

// Verwerk de login bij een POST-verzoek
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Probeer in te loggen
    $user = canLogin($email, $password);
    if ($user) {
        // Start de sessie en sla gebruikersgegevens op
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Controleer of de gebruiker een admin is
        $_SESSION['is_admin'] = ($user['role'] == 1);

        // Redirect naar de indexpagina
        header('Location: index.php');
        exit;
    } else {
        // Login mislukt
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="css/log.css">
</head>
<body>
<div class="login-container">
    <div class="form form--login">
        <form action="" method="post">
            <h2 class="form__title">Inloggen</h2>
            <?php if (isset($error)): ?>
                <div class="form__error">
                    <p>Sorry, we kunnen je niet inloggen met dit e-mailadres en wachtwoord. Probeer het opnieuw.</p>
                </div>
            <?php endif; ?>
            <div class="form__field">
                <label for="email">E-mail</label>
                <input type="text" name="email" required>
            </div>
            <div class="form__field">
                <label for="password">Wachtwoord</label>
                <input type="password" name="password" required>
            </div>
            <div class="form__field">
                <input type="submit" value="Inloggen" class="btn btn--primary">
                <input type="checkbox" id="rememberMe">
                <label for="rememberMe" class="label__inline">Onthoud mij</label>
            </div>
        </form>

        <div class="signup-link">
            <p>Heb je nog geen account? <a href="signup.php">Aanmelden</a></p>
        </div>
    </div>
</div>
</body>
</html>
