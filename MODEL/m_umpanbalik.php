<?php
require_once 'm_koneksi.php';

/**
 * Class UmpanBalik
 * Model untuk mengelola data umpan balik
 * Menggunakan OOP dengan PDO
 */
class UmpanBalik {
    private $pdo;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->pdo = Koneksi::getInstance()->getPdo();
    }
    
    /**
     * Mengambil semua umpan balik
     * @return array
     */
    public function getAllUmpanBalik() {
        $query = "SELECT u.*, a.judul_laporan, ad.nama_lengkap as nama_admin 
                  FROM umpan_balik u 
                  JOIN aspirasi a ON u.id_aspirasi = a.id_aspirasi 
                  JOIN admin ad ON u.id_admin = ad.id_admin 
                  ORDER BY u.tanggal DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil umpan balik berdasarkan ID aspirasi
     * @param int $idAspirasi
     * @return array|false
     */
    public function getUmpanBalikByAspirasi($idAspirasi) {
        $query = "SELECT u.*, ad.nama_lengkap as nama_admin 
                  FROM umpan_balik u 
                  JOIN admin ad ON u.id_admin = ad.id_admin 
                  WHERE u.id_aspirasi = :id_aspirasi 
                  ORDER BY u.tanggal DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_aspirasi', $idAspirasi, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Menambah umpan balik baru
     * @param array $data
     * @return bool
     */
    public function tambahUmpanBalik($data) {
        $query = "INSERT INTO umpan_balik (id_aspirasi, id_admin, isi_UmpanBalik, tanggal) 
                  VALUES (:id_aspirasi, :id_admin, :isi, CURDATE())";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':id_aspirasi', $data['id_aspirasi'], PDO::PARAM_INT);
        $stmt->bindParam(':id_admin', $data['id_admin'], PDO::PARAM_INT);
        $stmt->bindParam(':isi', $data['isi_UmpanBalik'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Update umpan balik
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUmpanBalik($id, $data) {
        $query = "UPDATE umpan_balik SET isi_UmpanBalik = :isi, id_admin = :id_admin 
                  WHERE id_UmpanBalik = :id";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':id_admin', $data['id_admin'], PDO::PARAM_INT);
        $stmt->bindParam(':isi', $data['isi_UmpanBalik'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Hapus umpan balik
     * @param int $id
     * @return bool
     */
    public function hapusUmpanBalik($id) {
        $query = "DELETE FROM umpan_balik WHERE id_UmpanBalik = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
