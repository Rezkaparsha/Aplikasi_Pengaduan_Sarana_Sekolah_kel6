<?php
require_once 'm_koneksi.php';

class UmpanBalik {
    private $koneksi;

    // constructor
    public function __construct() {
        $db = new Koneksi();
        $this->koneksi = $db->koneksi;
    }

    // GET ALL UMPAN BALIK
    public function getAllUmpanBalik() {
        $query = "SELECT u.*, a.judul_laporan, ad.nama_lengkap as nama_admin
                  FROM umpan_balik u
                  JOIN aspirasi a ON u.id_aspirasi = a.id_aspirasi
                  JOIN admin ad ON u.id_admin = ad.id_admin
                  ORDER BY u.tanggal DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // GET UMPAN BALIK BY ASPIRASI
    public function getUmpanBalikByAspirasi($idAspirasi) {
        $query = "SELECT u.*, ad.nama_lengkap as nama_admin
                  FROM umpan_balik u
                  JOIN admin ad ON u.id_admin = ad.id_admin
                  WHERE u.id_aspirasi = '$idAspirasi'
                  ORDER BY u.tanggal DESC";

        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_assoc($result);
    }

    // TAMBAH UMPAN BALIK
    public function tambahUmpanBalik($data) {
        $id_aspirasi = $data['id_aspirasi'];
        $id_admin = $data['id_admin'];
        $isi = $data['isi_UmpanBalik'];

        $query = "INSERT INTO umpan_balik 
                  (id_aspirasi, id_admin, isi_UmpanBalik, tanggal)
                  VALUES ('$id_aspirasi', '$id_admin', '$isi', CURDATE())";

        return mysqli_query($this->koneksi, $query);
    }

    // UPDATE UMPAN BALIK
    public function updateUmpanBalik($id, $data) {
        $id_admin = $data['id_admin'];
        $isi = $data['isi_UmpanBalik'];

        $query = "UPDATE umpan_balik SET 
                    isi_UmpanBalik = '$isi',
                    id_admin = '$id_admin'
                  WHERE id_UmpanBalik = '$id'";

        return mysqli_query($this->koneksi, $query);
    }

    // HAPUS UMPAN BALIK
    public function hapusUmpanBalik($id) {
        $query = "DELETE FROM umpan_balik WHERE id_UmpanBalik = '$id'";
        return mysqli_query($this->koneksi, $query);
    }
}
?>