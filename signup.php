<?php
// Verwerk de registratie als er een POST-verzoek is
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Wachtwoord hashen voor veiligheid

    // Verbind met de database
    $conn = new PDO('mysql:host=localhost;dbname=webshop', 'root', '');

    // Voeg de gebruiker toe aan de database
    $statement = $conn->prepare('INSERT INTO inloggen (email, password) VALUES (:email, :password)');
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', $hashedPassword);

    if ($statement->execute()) {
        // Als registratie succesvol is, redirect naar login-pagina
        header('Location: login.php');
        exit;
    } else {
        // Als er een fout is tijdens de registratie, toon een foutmelding
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aanmelden</title>
    <link rel="stylesheet" href="css/log.css"> <!-- Verwijzing naar de CSS voor consistente styling -->
</head>
<body>
<div class="login-container">
    <div class="form form--signup">
        <form action="" method="post">
            <h2 class="form__title">Aanmelden</h2>
            <?php if (isset($error)): ?>
                <div class="form__error">
                    <p>Er is iets misgegaan bij je registratie. Probeer het opnieuw.</p>
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
                <input type="submit" value="Aanmelden" class="btn btn--primary">
            </div>
        </form>

        <div class="login-link">
            <p>Heb je al een account? <a href="login.php">Inloggen</a></p>
        </div>
    </div>
</div>
</body>
</html>
