<?php
class Database {
    private $host = 'localhost'; // Database host
    private $dbname = 'webshop'; // Naam van je database
    private $username = 'root'; // Database-gebruiker
    private $password = ''; // Database-wachtwoord
    private $conn;

    // Maak een verbinding met de database
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Schakel foutmeldingen in
        } catch (PDOException $e) {
            echo "Verbinding mislukt: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
