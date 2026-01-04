<?php
class Database {
    private $host;
    private $user;
    private $pass;
    private $db;
    private $conn;

    public function __construct() {
        // Ambil config dari constant yang sudah didefinisikan
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->db = DB_NAME;
        
        // Connect ke database
        $this->connect();
    }

    private function connect() {
        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
        
        if (!$this->conn) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
        
        // Set charset ke utf8
        mysqli_set_charset($this->conn, "utf8");
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql) {
        return mysqli_query($this->conn, $sql);
    }

    public function fetchAssoc($result) {
        return mysqli_fetch_assoc($result);
    }

    public function numRows($result) {
        return mysqli_num_rows($result);
    }

    public function close() {
        mysqli_close($this->conn);
    }
}
?>