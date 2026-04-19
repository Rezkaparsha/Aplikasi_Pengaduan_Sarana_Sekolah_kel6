<?php
require_once 'm_koneksi.php';

class HistoriAspirasi {
    private $koneksi;

    // constructor
    public function __construct() {
        $db = new Koneksi();
        $this->koneksi = $db->koneksi;
    }

    // GET ALL HISTORI
    public function getAllHistori() {
        $query = "SELECT h.*, a.judul_laporan, s.nama as nama_siswa
                  FROM histori_aspirasi h
                  JOIN aspirasi a ON h.id_aspirasi = a.id_aspirasi
                  JOIN siswa s ON a.nis = s.nis
                  ORDER BY h.tanggal_perubahan DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // GET HISTORI BY ASPIRASI
    public function getHistoriByAspirasi($idAspirasi) {
        $query = "SELECT * FROM histori_aspirasi
                  WHERE id_aspirasi = '$idAspirasi'
                  ORDER BY tanggal_perubahan DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // GET HISTORI BY SISWA
    public function getHistoriBySiswa($nis) {
        $query = "SELECT h.*, a.judul_laporan
                  FROM histori_aspirasi h
                  JOIN aspirasi a ON h.id_aspirasi = a.id_aspirasi
                  WHERE a.nis = '$nis'
                  ORDER BY h.tanggal_perubahan DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // TAMBAH HISTORI
    public function tambahHistori($data) {
        $id_aspirasi = $data['id_aspirasi'];
        $status_sebelum = $data['status_sebelum'];
        $status_sesudah = $data['status_sesudah'];

        $query = "INSERT INTO histori_aspirasi 
                  (id_aspirasi, status_sebelum, status_sesudah, tanggal_perubahan)
                  VALUES ('$id_aspirasi', '$status_sebelum', '$status_sesudah', CURDATE())";

        return mysqli_query($this->koneksi, $query);
    }

    // UPDATE ID HISTORI KE ASPIRASI
    public function updateAspirasiHistori($idAspirasi, $idHistori) {
        $query = "UPDATE aspirasi 
                  SET id_histori = '$idHistori'
                  WHERE id_aspirasi = '$idAspirasi'";

        return mysqli_query($this->koneksi, $query);
    }
}
?>