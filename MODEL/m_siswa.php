<?php
require_once 'm_koneksi.php';

/**
 * Class Siswa
 * Model untuk mengelola data siswa
 * Menggunakan OOP dengan PDO
 */
class Siswa {
    private $pdo;
    
    // Properties
    public $nis;
    public $nama;
    public $kelas;
    public $email;
    public $password;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->pdo = Koneksi::getInstance()->getPdo();
    }
    
    /**
     * Mengambil semua data siswa
     * @return array
     */
    public function getAllSiswa() {
        $query = "SELECT * FROM siswa ORDER BY nama ASC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    
    /**
     * Mengambil data siswa berdasarkan NIS
     * @param int $nis
     * @return array|false
     */
    public function getSiswaByNis($nis) {
        $query = "SELECT * FROM siswa WHERE nis = :nis";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Login siswa
     * @param int $nis
     * @param string $password
     * @return array|false
     */
    public function login($nis, $password) {
        $query = "SELECT * FROM siswa WHERE nis = :nis";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->execute();
        $siswa = $stmt->fetch();
        
        if ($siswa && password_verify($password, $siswa['password'])) {
            return $siswa;
        }
        return false;
    }
    
    /**
     * Menambah data siswa baru
     * @param array $data
     * @return bool
     */
    public function tambahSiswa($data) {
        $query = "INSERT INTO siswa (nis, nama, kelas, email, password) 
                  VALUES (:nis, :nama, :kelas, :email, :password)";
        $stmt = $this->pdo->prepare($query);
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nis', $data['nis'], PDO::PARAM_INT);
        $stmt->bindParam(':nama', $data['nama'], PDO::PARAM_STR);
        $stmt->bindParam(':kelas', $data['kelas'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Update data siswa
     * @param array $data
     * @return bool
     */
    public function updateSiswa($data) {
        $query = "UPDATE siswa SET nama = :nama, kelas = :kelas, email = :email";
        
        // Jika password diisi, update juga
        if (!empty($data['password'])) {
            $query .= ", password = :password";
        }
        
        $query .= " WHERE nis = :nis";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':nis', $data['nis'], PDO::PARAM_INT);
        $stmt->bindParam(':nama', $data['nama'], PDO::PARAM_STR);
        $stmt->bindParam(':kelas', $data['kelas'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Hapus data siswa
     * @param int $nis
     * @return bool
     */
    public function hapusSiswa($nis) {
        $query = "DELETE FROM siswa WHERE nis = :nis";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Cek apakah NIS sudah terdaftar
     * @param int $nis
     * @return bool
     */
    public function cekNis($nis) {
        $query = "SELECT COUNT(*) FROM siswa WHERE nis = :nis";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Update profil siswa
     * @param int $nis
     * @param array $data
     * @return bool
     */
    public function updateProfil($nis, $data) {
        $query = "UPDATE siswa SET nama = :nama, kelas = :kelas, email = :email WHERE nis = :nis";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->bindParam(':nama', $data['nama'], PDO::PARAM_STR);
        $stmt->bindParam(':kelas', $data['kelas'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Update password siswa
     * @param int $nis
     * @param string $passwordBaru
     * @return bool
     */
    public function updatePassword($nis, $passwordBaru) {
        $query = "UPDATE siswa SET password = :password WHERE nis = :nis";
        $stmt = $this->pdo->prepare($query);
        
        $hashedPassword = password_hash($passwordBaru, PASSWORD_DEFAULT);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
}
?>
