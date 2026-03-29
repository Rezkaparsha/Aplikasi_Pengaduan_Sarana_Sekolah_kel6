<?php
require_once 'm_koneksi.php';

/**
 * Class Progres
 * Model untuk mengelola data progres laporan aspirasi
 * Menggunakan OOP dengan PDO
 */
class Progres {
    private $pdo;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->pdo = Koneksi::getInstance()->getPdo();
    }
    
    /**
     * Mengambil semua progres
     * @return array
     */
    public function getAllProgres() {
        $query = "SELECT p.*, a.judul_laporan, s.nama as nama_siswa 
                  FROM progres_laporanaspirasi p 
                  JOIN aspirasi a ON p.id_aspirasi = a.id_aspirasi 
                  JOIN siswa s ON a.nis = s.nis 
                  ORDER BY p.tanggal DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil progres berdasarkan ID aspirasi
     * @param int $idAspirasi
     * @return array
     */
    public function getProgresByAspirasi($idAspirasi) {
        $query = "SELECT * FROM progres_laporanaspirasi 
                  WHERE id_aspirasi = :id_aspirasi 
                  ORDER BY tanggal DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_aspirasi', $idAspirasi, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Menambah progres baru
     * @param array $data
     * @return bool
     */
    public function tambahProgres($data) {
        $query = "INSERT INTO progres_laporanaspirasi (id_aspirasi, deskripsi_progres, foto_bukti, tanggal) 
                  VALUES (:id_aspirasi, :deskripsi, :foto, CURDATE())";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':id_aspirasi', $data['id_aspirasi'], PDO::PARAM_INT);
        $stmt->bindParam(':deskripsi', $data['deskripsi_progres'], PDO::PARAM_STR);
        $stmt->bindParam(':foto', $data['foto_bukti'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Update progres
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateProgres($id, $data) {
        $query = "UPDATE progres_laporanaspirasi SET deskripsi_progres = :deskripsi";
        
        if (!empty($data['foto_bukti'])) {
            $query .= ", foto_bukti = :foto";
        }
        
        $query .= " WHERE id_progres = :id";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':deskripsi', $data['deskripsi_progres'], PDO::PARAM_STR);
        
        if (!empty($data['foto_bukti'])) {
            $stmt->bindParam(':foto', $data['foto_bukti'], PDO::PARAM_STR);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Hapus progres
     * @param int $id
     * @return bool
     */
    public function hapusProgres($id) {
        $query = "DELETE FROM progres_laporanaspirasi WHERE id_progres = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
