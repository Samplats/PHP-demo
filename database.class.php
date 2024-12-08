<?php
class Database {
    private $host = 'localhost'; 
    private $dbname = 'webshop'; 
    private $username = 'root';
    private $password = ''; 
    private $conn;

   
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
