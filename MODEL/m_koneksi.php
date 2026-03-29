<?php
/**
 * Class Koneksi
 * Class untuk mengelola koneksi database menggunakan PDO
 * Menggunakan pola Singleton untuk memastikan hanya satu koneksi aktif
 */
class Koneksi {
    private static $instance = null;
    private $pdo;
    
    // Konfigurasi database
    private $host = 'localhost';
    private $dbname = 'pengaduan_sarana_sekolah_kel6_11rpl2';
    private $username = 'root';
    private $password = '';
    
    /**
     * Constructor - membuat koneksi PDO
     */
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }
    
    /**
     * Mendapatkan instance koneksi (Singleton pattern)
     * @return Koneksi
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Mendapatkan objek PDO
     * @return PDO
     */
    public function getPdo() {
        return $this->pdo;
    }
    
    /**
     * Mencegah cloning
     */
    private function __clone() {}
}
?>
