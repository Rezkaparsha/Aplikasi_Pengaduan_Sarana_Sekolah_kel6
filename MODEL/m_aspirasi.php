<?php
require_once 'm_koneksi.php';

class Aspirasi {
    private $koneksi;

    // Properti 
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

    // constructor
    public function __construct() {
        $db = new Koneksi();
        $this->koneksi = $db->koneksi;
    }


    public function getAllAspirasi() {
        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas
                  FROM aspirasi a
                  JOIN siswa s ON a.nis = s.nis
                  ORDER BY a.tanggal_dikirim DESC, a.id_aspirasi DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getAspirasiById($id) {
        $id = intval($id);

        $query = "SELECT a.*, s.nama as nama_siswa, s.kelas, s.email
                  FROM aspirasi a
                  JOIN siswa s ON a.nis = s.nis
                  WHERE a.id_aspirasi = $id";

        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_assoc($result);
    }

    public function getAspirasiByNis($nis) {
        $nis = intval($nis);

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

    // TAMBAH

    public function tambahAspirasi($data) {
        $nis = intval($data['nis']);
        $judul = $data['judul_laporan'];
        $ket = $data['keterangan'];
        $prioritas = $data['kategori_prioritas'];
        $lokasi = $data['lokasi'];
        $foto = $data['foto_gambar'];

        $query = "INSERT INTO aspirasi 
                  (nis, judul_laporan, keterangan, kategori_prioritas, lokasi, foto_gambar, tanggal_dikirim)
                  VALUES ('$nis', '$judul', '$ket', '$prioritas', '$lokasi', '$foto', CURDATE())";

        $result = mysqli_query($this->koneksi, $query);

        if ($result) {
            return mysqli_insert_id($this->koneksi);
        }
        return false;
    }

    // UPDATE

    public function updateStatus($id, $status, $idAdmin) {
        $id = intval($id);
        $idAdmin = intval($idAdmin);

        $query = "UPDATE aspirasi 
                  SET status = '$status', id_admin = '$idAdmin'
                  WHERE id_aspirasi = $id";

        return mysqli_query($this->koneksi, $query);
    }

    public function updatePrioritas($id, $prioritas) {
        $id = intval($id);

        $query = "UPDATE aspirasi 
                  SET kategori_prioritas = '$prioritas'
                  WHERE id_aspirasi = $id";

        return mysqli_query($this->koneksi, $query);
    }

    //DELETE

    public function hapusAspirasi($id, $nis) {
        $id = intval($id);
        $nis = intval($nis);

        $query = "DELETE FROM aspirasi 
                  WHERE id_aspirasi = $id AND nis = $nis";

        return mysqli_query($this->koneksi, $query);
    }

    //FILTER

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

    //STATISTIK

    public function getStatistik() {
        $statistik = [];

        $result = mysqli_query($this->koneksi, "SELECT COUNT(*) as total FROM aspirasi");
        $statistik['total'] = mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi, "SELECT COUNT(*) as total FROM aspirasi WHERE status='menunggu'");
        $statistik['menunggu'] = mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi, "SELECT COUNT(*) as total FROM aspirasi WHERE status='diproses'");
        $statistik['diproses'] = mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi, "SELECT COUNT(*) as total FROM aspirasi WHERE status='selesai'");
        $statistik['selesai'] = mysqli_fetch_assoc($result)['total'];

        return $statistik;
    }

    public function getStatistikBySiswa($nis) {
        $nis = intval($nis);
        $statistik = [];

        $result = mysqli_query($this->koneksi, "SELECT COUNT(*) as total FROM aspirasi WHERE nis=$nis");
        $statistik['total'] = mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi, "SELECT COUNT(*) as total FROM aspirasi WHERE nis=$nis AND status='menunggu'");
        $statistik['menunggu'] = mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi, "SELECT COUNT(*) as total FROM aspirasi WHERE nis=$nis AND status='diproses'");
        $statistik['diproses'] = mysqli_fetch_assoc($result)['total'];

        $result = mysqli_query($this->koneksi, "SELECT COUNT(*) as total FROM aspirasi WHERE nis=$nis AND status='selesai'");
        $statistik['selesai'] = mysqli_fetch_assoc($result)['total'];

        return $statistik;
    }
}
?>