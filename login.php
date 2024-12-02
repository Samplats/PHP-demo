<?php
function canLogin($email, $password) {
    // Verbind met de database
    $conn = new PDO('mysql:host=localhost;dbname=webshop', 'root', '');

    // Haal de gebruiker op uit de database
    $statement = $conn->prepare('SELECT * FROM inloggen WHERE email = :email');
    $statement->bindValue(':email', $email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Controleer of de gebruiker bestaat en het wachtwoord correct is
    if ($user) {
        if (password_verify($password, $user['password'])) {
            return $user; // Return de gebruiker met rol
        }
    }
    return false; // Geen match, login mislukt
}

// Verwerk de login als er een POST-verzoek is
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Probeer in te loggen
    $user = canLogin($email, $password);
    if ($user) {
        // Start de sessie en sla gebruiker en rol op in de sessie
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $user['role'];

        // Controleer of de gebruiker admin is
        if ($user['role'] == 1) {
            $_SESSION['is_admin'] = true; // Admin
        } else {
            $_SESSION['is_admin'] = false; // User
        }

        // Redirect naar de juiste pagina op basis van de rol
        header('Location: index.php'); // Pas dit aan als je een andere pagina hebt voor admins
        exit;
    } else {
        // Als de login mislukt, toon een foutmelding
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
    <link rel="stylesheet" href="css/log.css"> <!-- Verwijzing naar de aangepaste CSS voor de login-pagina -->
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
                <label for="Email">E-mail</label>
                <input type="text" name="email" required>
            </div>
            <div class="form__field">
                <label for="Password">Wachtwoord</label>
                <input type="password" name="password" required>
            </div>
            <div class="form__field">
                <input type="submit" value="Inloggen" class="btn btn--primary">
                <input type="checkbox" id="rememberMe"><label for="rememberMe" class="label__inline">Onthoud mij</label>
            </div>
        </form>

        <div class="signup-link">
            <p>Heb je nog geen account? <a href="signup.php">Aanmelden</a></p>
        </div>
    </div>
</div>
</body>
</html>
