<?php
require_once 'm_koneksi.php';

/**
 * Class Admin
 * Model untuk mengelola data admin
 * Menggunakan OOP dengan PDO
 */
class Admin {
    private $pdo;
    
    // Properties
    public $id_admin;
    public $username;
    public $password;
    public $nama_lengkap;
    public $email;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->pdo = Koneksi::getInstance()->getPdo();
    }
    
    /**
     * Login admin
     * @param string $username
     * @param string $password
     * @return array|false
     */
    public function login($username, $password) {
        $query = "SELECT * FROM admin WHERE username = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }
    
    /**
     * Mengambil semua admin
     * @return array
     */
    public function getAllAdmin() {
        $query = "SELECT id_admin, username, nama_lengkap, email, created_at FROM admin ORDER BY id_admin ASC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil admin berdasarkan ID
     * @param int $id
     * @return array|false
     */
    public function getAdminById($id) {
        $query = "SELECT id_admin, username, nama_lengkap, email FROM admin WHERE id_admin = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Menambah admin baru
     * @param array $data
     * @return bool
     */
    public function tambahAdmin($data) {
        $query = "INSERT INTO admin (username, password, nama_lengkap, email) 
                  VALUES (:username, :password, :nama, :email)";
        $stmt = $this->pdo->prepare($query);
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':nama', $data['nama_lengkap'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Update data admin
     * @param array $data
     * @return bool
     */
    public function updateAdmin($data) {
        $query = "UPDATE admin SET username = :username, nama_lengkap = :nama, email = :email";
        
        if (!empty($data['password'])) {
            $query .= ", password = :password";
        }
        
        $query .= " WHERE id_admin = :id";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':id', $data['id_admin'], PDO::PARAM_INT);
        $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(':nama', $data['nama_lengkap'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Hapus admin
     * @param int $id
     * @return bool
     */
    public function hapusAdmin($id) {
        $query = "DELETE FROM admin WHERE id_admin = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Cek username sudah ada atau belum
     * @param string $username
     * @return bool
     */
    public function cekUsername($username) {
        $query = "SELECT COUNT(*) FROM admin WHERE username = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>
