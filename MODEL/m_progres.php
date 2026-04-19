<?php
require_once 'm_koneksi.php';

class Progres {
    private $koneksi;

    // constructor
    public function __construct() {
        $db = new Koneksi();
        $this->koneksi = $db->koneksi;
    }

    // GET ALL PROGRES
    public function getAllProgres() {
        $query = "SELECT p.*, a.judul_laporan, s.nama as nama_siswa
                  FROM progres_laporanaspirasi p
                  JOIN aspirasi a ON p.id_aspirasi = a.id_aspirasi
                  JOIN siswa s ON a.nis = s.nis
                  ORDER BY p.tanggal DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // GET PROGRES BY ASPIRASI
    public function getProgresByAspirasi($idAspirasi) {
        $query = "SELECT * FROM progres_laporanaspirasi
                  WHERE id_aspirasi = '$idAspirasi'
                  ORDER BY tanggal DESC";

        $result = mysqli_query($this->koneksi, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // TAMBAH PROGRES
    public function tambahProgres($data) {
        $id_aspirasi = $data['id_aspirasi'];
        $deskripsi = $data['deskripsi_progres'];
        $foto = $data['foto_bukti'];

        $query = "INSERT INTO progres_laporanaspirasi 
                  (id_aspirasi, deskripsi_progres, foto_bukti, tanggal)
                  VALUES ('$id_aspirasi', '$deskripsi', '$foto', CURDATE())";

        return mysqli_query($this->koneksi, $query);
    }

    // UPDATE PROGRES
    public function updateProgres($id, $data) {
        $deskripsi = $data['deskripsi_progres'];

        if (!empty($data['foto_bukti'])) {
            $foto = $data['foto_bukti'];

            $query = "UPDATE progres_laporanaspirasi SET 
                        deskripsi_progres = '$deskripsi',
                        foto_bukti = '$foto'
                      WHERE id_progres = '$id'";
        } else {
            $query = "UPDATE progres_laporanaspirasi SET 
                        deskripsi_progres = '$deskripsi'
                      WHERE id_progres = '$id'";
        }

        return mysqli_query($this->koneksi, $query);
    }

    // HAPUS PROGRES
    public function hapusProgres($id) {
        $query = "DELETE FROM progres_laporanaspirasi WHERE id_progres = '$id'";
        return mysqli_query($this->koneksi, $query);
    }
}
?>