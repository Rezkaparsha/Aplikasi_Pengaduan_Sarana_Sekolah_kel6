<?php
// Memanggil file koneksi database
require_once 'm_koneksi.php';

// Class Progres digunakan untuk mengelola data progres pengerjaan laporan aspirasi
class Progres {

    // Property private untuk menyimpan koneksi database
    private $koneksi;

    // CONSTRUCTOR Otomatis berjalan saat object dibuat
    // Mengambil koneksi dari class Koneksi
    public function __construct() {

        // Membuat object koneksi database
        $db = new Koneksi();

        // Menyimpan koneksi ke property $koneksi
        $this->koneksi = $db->koneksi;
    }

    // FUNCTION GET ALL PROGRES untuk Mengambil seluruh data progres laporan aspirasi
    public function getAllProgres() {

        // Query mengambil semua progres + judul laporan + nama siswa
        $query = "SELECT p.*, a.judul_laporan, s.nama as nama_siswa
                  FROM progres_laporanaspirasi p
                  JOIN aspirasi a 
                    ON p.id_aspirasi = a.id_aspirasi
                  JOIN siswa s 
                    ON a.nis = s.nis
                  ORDER BY p.tanggal DESC";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Menyiapkan array kosong
        $data = [];

        // Mengambil semua data satu per satu
        while ($row = mysqli_fetch_assoc($result)) {

            // Menyimpan ke dalam array
            $data[] = $row;
        }

        // Mengembalikan seluruh data progres
        return $data;
    }

    // FUNCTION GET PROGRES BY ASPIRASI
    // Mengambil progres berdasarkan ID aspirasi tertentu
    public function getProgresByAspirasi($idAspirasi) {

        // Query mengambil progres berdasarkan id_aspirasi
        $query = "SELECT * FROM progres_laporanaspirasi
                  WHERE id_aspirasi = '$idAspirasi'
                  ORDER BY tanggal DESC";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Menyiapkan array kosong
        $data = [];

        // Mengambil semua hasil query
        while ($row = mysqli_fetch_assoc($result)) {

            // Menyimpan ke array
            $data[] = $row;
        }

        // Mengembalikan data progres
        return $data;
    }

    // FUNCTION TAMBAH PROGRES untuk Menyimpan progres baru dari pengerjaan aspirasi
    public function tambahProgres($data) {

        // Mengambil data dari parameter
        $id_aspirasi = $data['id_aspirasi'];
        $deskripsi = $data['deskripsi_progres'];
        $foto = $data['foto_bukti'];

        // Query insert progres baru
        $query = "INSERT INTO progres_laporanaspirasi
                  (
                    id_aspirasi,
                    deskripsi_progres,
                    foto_bukti,
                    tanggal
                  )
                  VALUES
                  (
                    '$id_aspirasi',
                    '$deskripsi',
                    '$foto',
                    CURDATE()
                  )";

        // Menjalankan query insert
        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION UPDATE PROGRES
    // Mengubah data progres yang sudah ada
    // Jika foto baru diisi maka foto ikut diupdate
    // Jika kosong maka hanya deskripsi yang diubah
    public function updateProgres($id, $data) {

        // Mengambil deskripsi progres baru
        $deskripsi = $data['deskripsi_progres'];

        // Jika foto bukti baru diisi
        if (!empty($data['foto_bukti'])) {

            // Ambil nama foto baru
            $foto = $data['foto_bukti'];

            // Query update deskripsi + foto
            $query = "UPDATE progres_laporanaspirasi SET
                        deskripsi_progres = '$deskripsi',
                        foto_bukti = '$foto'
                      WHERE id_progres = '$id'";

        } else {

            // Query update hanya deskripsi saja
            $query = "UPDATE progres_laporanaspirasi SET
                        deskripsi_progres = '$deskripsi'
                      WHERE id_progres = '$id'";
        }

        // Menjalankan query update
        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION HAPUS PROGRES
    // Menghapus data progres berdasarkan ID progres
    public function hapusProgres($id) {

        // Query delete berdasarkan id_progres
        $query = "DELETE FROM progres_laporanaspirasi
                  WHERE id_progres = '$id'";

        // Menjalankan query hapus
        return mysqli_query($this->koneksi, $query);
    }
}

?>