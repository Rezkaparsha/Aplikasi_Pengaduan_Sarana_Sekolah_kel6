<?php

// Memanggil file koneksi database
require_once 'm_koneksi.php';

// Class UmpanBalik digunakan untuk mengelola
// data umpan balik dari admin terhadap aspirasi siswa
class UmpanBalik {

    // Property private untuk menyimpan koneksi database
    private $koneksi;

    // CONSTRUCTOR Otomatis berjalan saat object dibuat 
    //Mengambil koneksi dari class Koneksi
    public function __construct() {

        // Membuat object koneksi database
        $db = new Koneksi();

        // Menyimpan koneksi ke property $koneksi
        $this->koneksi = $db->koneksi;
    }

        // FUNCTION GET ALL UMPAN BALIK untuk Mengambil seluruh data umpan balik
        // beserta judul laporan dan nama admin
        public function getAllUmpanBalik() {

        // Query mengambil semua umpan balik
        // judul laporan aspirasi
        // nama lengkap admin
        $query = "SELECT u.*, a.judul_laporan,
                         ad.nama_lengkap as nama_admin
                  FROM umpan_balik u
                  JOIN aspirasi a
                    ON u.id_aspirasi = a.id_aspirasi
                  JOIN admin ad
                    ON u.id_admin = ad.id_admin
                  ORDER BY u.tanggal DESC";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Menyiapkan array kosong
        $data = [];

        // Mengambil semua data satu per satu
        while ($row = mysqli_fetch_assoc($result)) {

            // Menyimpan ke dalam array
            $data[] = $row;
        }

        // Mengembalikan seluruh data umpan balik
        return $data;
    }

    // FUNCTION GET UMPAN BALIK BY ASPIRASI
    // Mengambil umpan balik berdasarkan ID aspirasi
    public function getUmpanBalikByAspirasi($idAspirasi) {

        // Query mencari umpan balik dari 1 aspirasi
        $query = "SELECT u.*, ad.nama_lengkap as nama_admin
                  FROM umpan_balik u
                  JOIN admin ad
                    ON u.id_admin = ad.id_admin
                  WHERE u.id_aspirasi = '$idAspirasi'
                  ORDER BY u.tanggal DESC";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Mengambil 1 data umpan balik terbaru
        return mysqli_fetch_assoc($result);
    }

    // FUNCTION TAMBAH UMPAN BALIK
    // Menyimpan umpan balik baru dari admin
    // terhadap laporan aspirasi siswa
    public function tambahUmpanBalik($data) {

        // Mengambil data dari parameter
        $id_aspirasi = $data['id_aspirasi'];
        $id_admin = $data['id_admin'];
        $isi = $data['isi_UmpanBalik'];

        // Query insert umpan balik baru
        $query = "INSERT INTO umpan_balik
                  (
                    id_aspirasi,
                    id_admin,
                    isi_UmpanBalik,
                    tanggal
                  )
                  VALUES
                  (
                    '$id_aspirasi',
                    '$id_admin',
                    '$isi',
                    CURDATE()
                  )";

        // Menjalankan query insert
        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION UPDATE UMPAN BALIK
    // Mengubah isi umpan balik yang sudah ada
    public function updateUmpanBalik($id, $data) {

        // Mengambil data baru
        $id_admin = $data['id_admin'];
        $isi = $data['isi_UmpanBalik'];

        // Query update umpan balik
        $query = "UPDATE umpan_balik SET
                    isi_UmpanBalik = '$isi',
                    id_admin = '$id_admin'
                  WHERE id_UmpanBalik = '$id'";

        // Menjalankan query update
        return mysqli_query($this->koneksi, $query);
    }


    // FUNCTION HAPUS UMPAN BALIK
    // Menghapus data umpan balik berdasarkan ID
    public function hapusUmpanBalik($id) {

        // Query delete berdasarkan id_UmpanBalik
        $query = "DELETE FROM umpan_balik
                  WHERE id_UmpanBalik = '$id'";

        // Menjalankan query hapus
        return mysqli_query($this->koneksi, $query);
    }
}

?>