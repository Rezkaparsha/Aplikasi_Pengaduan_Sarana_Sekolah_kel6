<?php
require_once 'm_koneksi.php';

// Class Aspirasi digunakan untuk mengelola data aspirasi/laporan
class Aspirasi {

    // Property private untuk menyimpan koneksi database
    private $koneksi;

    // PROPERTI DATA ASPIRASI
    // Digunakan untuk menampung data dari tabel aspirasi
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

    // CONSTRUCTOR
    // Otomatis berjalan saat object dibuat
    // Mengambil koneksi dari class Koneksi
    public function __construct() {

        // Membuat object koneksi database
        $db = new Koneksi();

        // Menyimpan koneksi ke property $koneksi
        $this->koneksi = $db->koneksi;
    }

    // FUNCTION GET ALL ASPIRASI
    // Mengambil seluruh data aspirasi beserta data siswa
    public function getAllAspirasi() {

        // Query mengambil semua data aspirasi + nama siswa + kelas
        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas
                  FROM aspirasi a
                  JOIN siswa s ON a.nis = s.nis
                  ORDER BY a.tanggal_dikirim DESC, a.id_aspirasi DESC";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Menyiapkan array kosong
        $data = [];

        // Mengambil semua data satu per satu
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        // Mengembalikan seluruh data
        return $data;
    }

    // FUNCTION GET ASPIRASI BY ID
    // Mengambil detail 1 aspirasi berdasarkan ID
    public function getAspirasiById($id) {

        // Mengubah menjadi integer untuk keamanan
        $id = intval($id);

        // Query detail aspirasi + data siswa
        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas, s.email
                  FROM aspirasi a
                  JOIN siswa s ON a.nis = s.nis
                  WHERE a.id_aspirasi = $id";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Mengembalikan 1 data
        return mysqli_fetch_assoc($result);
    }

    // FUNCTION GET ASPIRASI BY NIS
    // Mengambil semua aspirasi milik siswa tertentu
    public function getAspirasiByNis($nis) {

        // Mengubah menjadi integer
        $nis = intval($nis);

        // Query berdasarkan NIS siswa
        $query = "SELECT * FROM aspirasi
                  WHERE nis = $nis
                  ORDER BY tanggal_dikirim DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // FUNCTION TAMBAH ASPIRASI
    // Menyimpan laporan aspirasi baru ke database
    public function tambahAspirasi($data) {

        // Mengambil data dari form
        $nis = intval($data['nis']);
        $judul = $data['judul_laporan'];
        $ket = $data['keterangan'];
        $prioritas = $data['kategori_prioritas'];
        $lokasi = $data['lokasi'];
        $foto = $data['foto_gambar'];

        // Query insert data baru
        $query = "INSERT INTO aspirasi
                  (nis, judul_laporan, keterangan,
                  kategori_prioritas, lokasi,
                  foto_gambar, tanggal_dikirim)
                  VALUES
                  ('$nis', '$judul', '$ket',
                  '$prioritas', '$lokasi',
                  '$foto', CURDATE())";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Jika berhasil, kembalikan ID terakhir
        if ($result) {
            return mysqli_insert_id($this->koneksi);
        }

        // Jika gagal
        return false;
    }

    // FUNCTION UPDATE STATUS
    // Mengubah status aspirasi dan admin penangan
    public function updateStatus($id, $status, $idAdmin) {

        $id = intval($id);
        $idAdmin = intval($idAdmin);

        $query = "UPDATE aspirasi
                  SET status = '$status',
                      id_admin = '$idAdmin'
                  WHERE id_aspirasi = $id";

        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION UPDATE PRIORITAS
    // Mengubah kategori prioritas aspirasi
    public function updatePrioritas($id, $prioritas) {

        $id = intval($id);

        $query = "UPDATE aspirasi
                  SET kategori_prioritas = '$prioritas'
                  WHERE id_aspirasi = $id";

        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION HAPUS ASPIRASI
    // Menghapus aspirasi milik siswa tertentu
    public function hapusAspirasi($id, $nis) {

        $id = intval($id);
        $nis = intval($nis);

        $query = "DELETE FROM aspirasi
                  WHERE id_aspirasi = $id
                  AND nis = $nis";

        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION FILTER BY TANGGAL
    // Menampilkan aspirasi berdasarkan tanggal kirim
    public function filterByTanggal($tanggal) {

        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas
                  FROM aspirasi a
                  JOIN siswa s ON a.nis = s.nis
                  WHERE a.tanggal_dikirim = '$tanggal'
                  ORDER BY a.id_aspirasi DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // FUNCTION FILTER BY BULAN
    // Menampilkan aspirasi berdasarkan bulan tertentu
    public function filterByBulan($bulan) {

        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas
                  FROM aspirasi a
                  JOIN siswa s ON a.nis = s.nis
                  WHERE DATE_FORMAT(a.tanggal_dikirim, '%Y-%m') = '$bulan'
                  ORDER BY a.id_aspirasi DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // FUNCTION FILTER BY SISWA
    // Menampilkan aspirasi berdasarkan NIS siswa
    public function filterBySiswa($nis) {

        $nis = intval($nis);

        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas
                  FROM aspirasi a
                  JOIN siswa s ON a.nis = s.nis
                  WHERE a.nis = $nis
                  ORDER BY a.tanggal_dikirim DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // FUNCTION FILTER BY LOKASI
    // Menampilkan aspirasi berdasarkan lokasi
    public function filterByLokasi($lokasi) {

        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas
                  FROM aspirasi a
                  JOIN siswa s ON a.nis = s.nis
                  WHERE a.lokasi = '$lokasi'
                  ORDER BY a.tanggal_dikirim DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // FUNCTION FILTER BY STATUS
    // Menampilkan aspirasi berdasarkan status
    public function filterByStatus($status) {

        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas
                  FROM aspirasi a
                  JOIN siswa s ON a.nis = s.nis
                  WHERE a.status = '$status'
                  ORDER BY a.tanggal_dikirim DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // FUNCTION GET STATISTIK
    // Mengambil statistik semua aspirasi untuk admin
    public function getStatistik() {

        $statistik = [];

        $result = mysqli_query($this->koneksi,
            "SELECT COUNT(*) as total FROM aspirasi");
        $statistik['total'] =
            mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi,
            "SELECT COUNT(*) as total FROM aspirasi
             WHERE status='menunggu'");
        $statistik['menunggu'] =
            mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi,
            "SELECT COUNT(*) as total FROM aspirasi
             WHERE status='diproses'");
        $statistik['diproses'] =
            mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi,
            "SELECT COUNT(*) as total FROM aspirasi
             WHERE status='selesai'");
        $statistik['selesai'] =
            mysqli_fetch_assoc($result)['total'];

        return $statistik;
    }

    // FUNCTION GET STATISTIK BY SISWA
    // Mengambil statistik aspirasi milik 1 siswa
    public function getStatistikBySiswa($nis) {

        $nis = intval($nis);
        $statistik = [];

        $result = mysqli_query($this->koneksi,
            "SELECT COUNT(*) as total FROM aspirasi
             WHERE nis=$nis");
        $statistik['total'] =
            mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi,
            "SELECT COUNT(*) as total FROM aspirasi
             WHERE nis=$nis AND status='menunggu'");
        $statistik['menunggu'] =
            mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi,
            "SELECT COUNT(*) as total FROM aspirasi
             WHERE nis=$nis AND status='diproses'");
        $statistik['diproses'] =
            mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi,
            "SELECT COUNT(*) as total FROM aspirasi
             WHERE nis=$nis AND status='selesai'");
        $statistik['selesai'] =
            mysqli_fetch_assoc($result)['total'];

        return $statistik;
    }
}

?>