<?php
class User {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Methode om in te loggen
    public function login($email, $password) {
        // Haal de gebruiker op uit de tabel inloggen
        $stmt = $this->db->prepare("SELECT * FROM inloggen WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['passwoord']) { // Wachtwoord check
            // Sla gebruikersgegevens op in de sessie
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['is_admin'] = isset($user['is_admin']) ? (bool)$user['is_admin'] : false; // Admin check
            return true;
        } else {
            return false;
        }
    }

    // Controleer of de gebruiker ingelogd is
    public function isLoggedIn() {
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    }

    // Controleer of de gebruiker een admin is
    public function isAdmin() {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }

    // Log de gebruiker uit
    public function logout() {
        session_start();
        $_SESSION = array(); // Vernietig alle sessievariabelen
        session_destroy(); // Vernietig de sessie
    }
}
?>
