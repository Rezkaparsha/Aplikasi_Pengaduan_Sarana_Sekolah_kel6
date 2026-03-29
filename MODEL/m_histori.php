<?php
require_once 'm_koneksi.php';

/**
 * Class HistoriAspirasi
 * Model untuk mengelola data histori aspirasi
 * Menggunakan OOP dengan PDO
 */
class HistoriAspirasi {
    private $pdo;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->pdo = Koneksi::getInstance()->getPdo();
    }
    
    /**
     * Mengambil semua histori
     * @return array
     */
    public function getAllHistori() {
        $query = "SELECT h.*, a.judul_laporan, s.nama as nama_siswa 
                  FROM histori_aspirasi h 
                  JOIN aspirasi a ON h.id_aspirasi = a.id_aspirasi 
                  JOIN siswa s ON a.nis = s.nis 
                  ORDER BY h.tanggal_perubahan DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil histori berdasarkan ID aspirasi
     * @param int $idAspirasi
     * @return array
     */
    public function getHistoriByAspirasi($idAspirasi) {
        $query = "SELECT * FROM histori_aspirasi 
                  WHERE id_aspirasi = :id_aspirasi 
                  ORDER BY tanggal_perubahan DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_aspirasi', $idAspirasi, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil histori berdasarkan NIS siswa
     * @param int $nis
     * @return array
     */
    public function getHistoriBySiswa($nis) {
        $query = "SELECT h.*, a.judul_laporan 
                  FROM histori_aspirasi h 
                  JOIN aspirasi a ON h.id_aspirasi = a.id_aspirasi 
                  WHERE a.nis = :nis 
                  ORDER BY h.tanggal_perubahan DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Menambah histori baru
     * @param array $data
     * @return bool
     */
    public function tambahHistori($data) {
        $query = "INSERT INTO histori_aspirasi (id_aspirasi, status_sebelum, status_sesudah, tanggal_perubahan) 
                  VALUES (:id_aspirasi, :status_sebelum, :status_sesudah, CURDATE())";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':id_aspirasi', $data['id_aspirasi'], PDO::PARAM_INT);
        $stmt->bindParam(':status_sebelum', $data['status_sebelum'], PDO::PARAM_STR);
        $stmt->bindParam(':status_sesudah', $data['status_sesudah'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Update id_histori di tabel aspirasi
     * @param int $idAspirasi
     * @param int $idHistori
     * @return bool
     */
    public function updateAspirasiHistori($idAspirasi, $idHistori) {
        $query = "UPDATE aspirasi SET id_histori = :id_histori WHERE id_aspirasi = :id_aspirasi";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_histori', $idHistori, PDO::PARAM_INT);
        $stmt->bindParam(':id_aspirasi', $idAspirasi, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
