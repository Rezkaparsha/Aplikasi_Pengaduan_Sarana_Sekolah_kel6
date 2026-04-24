<?php
// Memanggil file koneksi database
require_once 'm_koneksi.php';

// Class HistoriAspirasi digunakan untuk mengelola
// data riwayat perubahan status aspirasi
class HistoriAspirasi {

    // Property private untuk menyimpan koneksi database
    private $koneksi;

    // CONSTRUCTOR
    // Otomatis berjalan saat object dibuat
    // Mengambil koneksi dari class Koneksi
    public function __construct() {

        // Membuat object koneksi database
        $db = new Koneksi();

        // Menyimpan koneksi ke property $koneksi
        $this->koneksi = $db->koneksi;
    }

    // FUNCTION GET ALL HISTORI untuk Mengambil seluruh data histori perubahan aspirasi
    public function getAllHistori() {

        // Query mengambil semua histori + judul laporan + nama siswa
        $query = "SELECT h.*, a.judul_laporan, s.nama as nama_siswa
                  FROM histori_aspirasi h
                  JOIN aspirasi a 
                    ON h.id_aspirasi = a.id_aspirasi
                  JOIN siswa s 
                    ON a.nis = s.nis
                  ORDER BY h.tanggal_perubahan DESC";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Menyiapkan array kosong
        $data = [];

        // Mengambil semua data satu per satu
        while ($row = mysqli_fetch_assoc($result)) {

            // Memasukkan data ke array
            $data[] = $row;
        }

        // Mengembalikan seluruh data histori
        return $data;
    }

    // FUNCTION GET HISTORI BY ASPIRASI
    // Mengambil histori berdasarkan ID aspirasi tertentu
    public function getHistoriByAspirasi($idAspirasi) {

        // Query mencari histori berdasarkan id_aspirasi
        $query = "SELECT * FROM histori_aspirasi
                  WHERE id_aspirasi = '$idAspirasi'
                  ORDER BY tanggal_perubahan DESC";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Menyiapkan array kosong
        $data = [];

        // Mengambil semua data hasil query
        while ($row = mysqli_fetch_assoc($result)) {

            // Memasukkan ke array
            $data[] = $row;
        }

        // Mengembalikan histori berdasarkan aspirasi
        return $data;
    }

    // FUNCTION GET HISTORI BY SISWA
    // Mengambil seluruh histori milik 1 siswa tertentu
    public function getHistoriBySiswa($nis) {

        // Query mengambil histori berdasarkan NIS siswa
        $query = "SELECT h.*, a.judul_laporan
                  FROM histori_aspirasi h
                  JOIN aspirasi a 
                    ON h.id_aspirasi = a.id_aspirasi
                  WHERE a.nis = '$nis'
                  ORDER BY h.tanggal_perubahan DESC";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Array penampung data
        $data = [];

        // Mengambil semua data satu per satu
        while ($row = mysqli_fetch_assoc($result)) {

            // Menyimpan ke array
            $data[] = $row;
        }

        // Mengembalikan data histori siswa
        return $data;
    }

    // FUNCTION TAMBAH HISTORI untuk Menyimpan riwayat perubahan status aspirasi
    public function tambahHistori($data) {

        // Mengambil data dari parameter
        $id_aspirasi = $data['id_aspirasi'];
        $status_sebelum = $data['status_sebelum'];
        $status_sesudah = $data['status_sesudah'];

        // Query insert histori baru
        $query = "INSERT INTO histori_aspirasi
                  (
                    id_aspirasi,
                    status_sebelum,
                    status_sesudah,
                    tanggal_perubahan
                  )
                  VALUES
                  (
                    '$id_aspirasi',
                    '$status_sebelum',
                    '$status_sesudah',
                    CURDATE()
                  )";

        // Menjalankan query insert
        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION UPDATE ASPIRASI HISTORI
    // Menghubungkan data histori terakhir ke tabel aspirasi
    // dengan menyimpan id_histori
    public function updateAspirasiHistori($idAspirasi, $idHistori) {

        // Query update id_histori pada tabel aspirasi
        $query = "UPDATE aspirasi
                  SET id_histori = '$idHistori'
                  WHERE id_aspirasi = '$idAspirasi'";

        // Menjalankan query update
        return mysqli_query($this->koneksi, $query);
    }
}

?>