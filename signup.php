<?php
if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = new PDO('mysql:host=localhost;dbname=webshop', 'root', '');

    // Controleer of het emailadres al bestaat
    $checkEmail = $conn->prepare('SELECT * FROM inloggen WHERE email = :email');
    $checkEmail->bindValue(':email', $email);
    $checkEmail->execute();

    if ($checkEmail->rowCount() > 0) {
        // Email bestaat al
        $error = "Dit emailadres is al in gebruik.";
    } else {
        // Stel de rol in op basis van het e-mailadres
        $role = ($email == 'admin@admin.com') ? 1 : 0;

        // Voeg gebruiker toe
        $options = ['cost' => 12];
        $hash = password_hash($password, PASSWORD_DEFAULT, $options);

        $statement = $conn->prepare('INSERT INTO inloggen (email, password, role) VALUES (:email, :password, :role)');
        $statement->bindValue(':email', $email);
        $statement->bindValue(':password', $hash);
        $statement->bindValue(':role', $role);  // Rol wordt hier toegevoegd
        $statement->execute();

        // Doorsturen naar loginpagina na succesvolle registratie
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="css/log.css">
</head>
<body>
    <div class="netflixLogin">
        <div class="form form--login">
            <form action="" method="post">
                <h2 class="form__title">Sign Up</h2>

                <?php if (isset($error)): ?>
                    <div class="form__error">
                        <p><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>

                <div class="form__field">
                    <label for="Email">Email</label>
                    <input type="text" name="email" required>
                </div>
                <div class="form__field">
                    <label for="Password">Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form__field">
                    <input type="submit" value="Sign Up" class="btn btn--primary">    
                </div>
            </form>
        </div>
    </div>
</body>
</html>
