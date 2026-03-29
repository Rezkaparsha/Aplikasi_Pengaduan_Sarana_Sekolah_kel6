<?php
require_once 'm_koneksi.php';

/**
 * Class Aspirasi
 * Model untuk mengelola data aspirasi/pengaduan
 * Menggunakan OOP dengan PDO
 */
class Aspirasi {
    private $pdo;
    
    // Properties
    public $id_aspirasi;
    public $nis;
    public $id_admin;
    public $judul_laporan;
    public $keterangan;
    public $kategori_prioritas;
    public $lokasi;
    public $foto_gambar;
    public $tanggal_dikirim;
    public $status;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->pdo = Koneksi::getInstance()->getPdo();
    }
    
    /**
     * Mengambil semua aspirasi
     * @return array
     */
    public function getAllAspirasi() {
        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas 
                  FROM aspirasi a 
                  JOIN siswa s ON a.nis = s.nis 
                  ORDER BY a.tanggal_dikirim DESC, a.id_aspirasi DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil aspirasi berdasarkan ID
     * @param int $id
     * @return array|false
     */
    public function getAspirasiById($id) {
        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas, s.email 
                  FROM aspirasi a 
                  JOIN siswa s ON a.nis = s.nis 
                  WHERE a.id_aspirasi = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Mengambil aspirasi berdasarkan NIS siswa
     * @param int $nis
     * @return array
     */
    public function getAspirasiByNis($nis) {
        $query = "SELECT * FROM aspirasi WHERE nis = :nis ORDER BY tanggal_dikirim DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Menambah aspirasi baru
     * @param array $data
     * @return int|false ID aspirasi yang baru dibuat
     */
    public function tambahAspirasi($data) {
        $query = "INSERT INTO aspirasi (nis, judul_laporan, keterangan, kategori_prioritas, lokasi, foto_gambar, tanggal_dikirim) 
                  VALUES (:nis, :judul, :keterangan, :prioritas, :lokasi, :foto, CURDATE())";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':nis', $data['nis'], PDO::PARAM_INT);
        $stmt->bindParam(':judul', $data['judul_laporan'], PDO::PARAM_STR);
        $stmt->bindParam(':keterangan', $data['keterangan'], PDO::PARAM_STR);
        $stmt->bindParam(':prioritas', $data['kategori_prioritas'], PDO::PARAM_STR);
        $stmt->bindParam(':lokasi', $data['lokasi'], PDO::PARAM_STR);
        $stmt->bindParam(':foto', $data['foto_gambar'], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }
    
    /**
     * Update status aspirasi
     * @param int $id
     * @param string $status
     * @param int $idAdmin
     * @return bool
     */
    public function updateStatus($id, $status, $idAdmin) {
        $query = "UPDATE aspirasi SET status = :status, id_admin = :id_admin WHERE id_aspirasi = :id";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id_admin', $idAdmin, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Update kategori prioritas
     * @param int $id
     * @param string $prioritas
     * @return bool
     */
    public function updatePrioritas($id, $prioritas) {
        $query = "UPDATE aspirasi SET kategori_prioritas = :prioritas WHERE id_aspirasi = :id";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':prioritas', $prioritas, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Filter aspirasi berdasarkan tanggal
     * @param string $tanggal
     * @return array
     */
    public function filterByTanggal($tanggal) {
        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas 
                  FROM aspirasi a 
                  JOIN siswa s ON a.nis = s.nis 
                  WHERE a.tanggal_dikirim = :tanggal 
                  ORDER BY a.id_aspirasi DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':tanggal', $tanggal, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Filter aspirasi berdasarkan bulan
     * @param string $bulan (format: YYYY-MM)
     * @return array
     */
    public function filterByBulan($bulan) {
        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas 
                  FROM aspirasi a 
                  JOIN siswa s ON a.nis = s.nis 
                  WHERE DATE_FORMAT(a.tanggal_dikirim, '%Y-%m') = :bulan 
                  ORDER BY a.id_aspirasi DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':bulan', $bulan, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Filter aspirasi berdasarkan siswa
     * @param int $nis
     * @return array
     */
    public function filterBySiswa($nis) {
        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas 
                  FROM aspirasi a 
                  JOIN siswa s ON a.nis = s.nis 
                  WHERE a.nis = :nis 
                  ORDER BY a.tanggal_dikirim DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Filter aspirasi berdasarkan kategori/lokasi
     * @param string $lokasi
     * @return array
     */
    public function filterByLokasi($lokasi) {
        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas 
                  FROM aspirasi a 
                  JOIN siswa s ON a.nis = s.nis 
                  WHERE a.lokasi = :lokasi 
                  ORDER BY a.tanggal_dikirim DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':lokasi', $lokasi, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Filter aspirasi berdasarkan status
     * @param string $status
     * @return array
     */
    public function filterByStatus($status) {
        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas 
                  FROM aspirasi a 
                  JOIN siswa s ON a.nis = s.nis 
                  WHERE a.status = :status 
                  ORDER BY a.tanggal_dikirim DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Mendapatkan statistik aspirasi
     * @return array
     */
    public function getStatistik() {
        $statistik = [];
        
        // Total aspirasi
        $query = "SELECT COUNT(*) FROM aspirasi";
        $statistik['total'] = $this->pdo->query($query)->fetchColumn();
        
        // Aspirasi menunggu
        $query = "SELECT COUNT(*) FROM aspirasi WHERE status = 'menunggu'";
        $statistik['menunggu'] = $this->pdo->query($query)->fetchColumn();
        
        // Aspirasi diproses
        $query = "SELECT COUNT(*) FROM aspirasi WHERE status = 'diproses'";
        $statistik['diproses'] = $this->pdo->query($query)->fetchColumn();
        
        // Aspirasi selesai
        $query = "SELECT COUNT(*) FROM aspirasi WHERE status = 'selesai'";
        $statistik['selesai'] = $this->pdo->query($query)->fetchColumn();
        
        return $statistik;
    }
    
    /**
     * Mendapatkan statistik aspirasi per siswa
     * @param int $nis
     * @return array
     */
    public function getStatistikBySiswa($nis) {
        $statistik = [];
        
        // Total aspirasi siswa
        $query = "SELECT COUNT(*) FROM aspirasi WHERE nis = :nis";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->execute();
        $statistik['total'] = $stmt->fetchColumn();
        
        // Aspirasi menunggu
        $query = "SELECT COUNT(*) FROM aspirasi WHERE nis = :nis AND status = 'menunggu'";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->execute();
        $statistik['menunggu'] = $stmt->fetchColumn();
        
        // Aspirasi diproses
        $query = "SELECT COUNT(*) FROM aspirasi WHERE nis = :nis AND status = 'diproses'";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->execute();
        $statistik['diproses'] = $stmt->fetchColumn();
        
        // Aspirasi selesai
        $query = "SELECT COUNT(*) FROM aspirasi WHERE nis = :nis AND status = 'selesai'";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
        $stmt->execute();
        $statistik['selesai'] = $stmt->fetchColumn();
        
        return $statistik;
    }
}
?>
