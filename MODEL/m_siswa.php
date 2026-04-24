<?php

require_once 'm_koneksi.php';

// Class Siswa digunakan untuk mengelola data siswa seperti login, register, edit profil, ubah password, dll
class Siswa {

    // Property private untuk menyimpan koneksi database
    private $koneksi;

    // PROPERTI DATA SISWA
    // Digunakan untuk menampung data dari tabel siswa
    public $nis;        
    public $nama;       
    public $kelas; 
    public $email; 
    public $password;   

    // CONSTRUCTOR
    // Otomatis berjalan saat object dibuat
    // Mengambil koneksi dari class Koneksi

    public function __construct() {

        // Membuat object koneksi database
        $db = new Koneksi();

        // Menyimpan koneksi ke property $koneksi
        $this->koneksi = $db->koneksi;
    }

    // FUNCTION GET ALL SISWA
    // Mengambil seluruh data siswa dari database
    // Biasanya digunakan untuk halaman daftar sisw
    public function getAllSiswa() {

        // Query mengambil semua data siswa
        // Diurutkan berdasarkan nama A-Z
        $query = "SELECT * FROM siswa
                  ORDER BY nama ASC";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Menyiapkan array kosong
        $data = [];

        // Mengambil semua data satu per satu
        while ($row = mysqli_fetch_assoc($result)) {

            // Menyimpan ke dalam array
            $data[] = $row;
        }

        // Mengembalikan seluruh data siswa
        return $data;
    }

    // FUNCTION GET SISWA BY NIS
    // Mengambil 1 data siswa berdasarkan NIS
    // Biasanya digunakan untuk edit atau profi
    public function getSiswaByNis($nis) {

        // Query mencari siswa berdasarkan NIS
        $query = "SELECT * FROM siswa
                  WHERE nis = '$nis'";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Mengembalikan 1 data siswa
        return mysqli_fetch_assoc($result);
    }

    // FUNCTION LOGIN
    // Digunakan untuk proses login siswa
    // Mengecek NIS dan password
    public function login($nis, $password) {

        // Query mencari siswa berdasarkan NIS
        $query = "SELECT * FROM siswa
                  WHERE nis = '$nis'";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Mengambil hasil query
        $siswa = mysqli_fetch_assoc($result);

        // Jika data ditemukan dan password cocok
        if ($siswa && password_verify($password, $siswa['password'])) {

            // Mengembalikan data siswa
            return $siswa;
        }

        // Jika login gagal
        return false;
    }
    // FUNCTION TAMBAH SISWA
    public function tambahSiswa($data) {

        // Mengambil data dari form
        $nis = $data['nis'];
        $nama = $data['nama'];
        $kelas = $data['kelas'];
        $email = $data['email'];

        // Password di-hash agar lebih aman
        $password = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        // Query insert data siswa baru
        $query = "INSERT INTO siswa
                  (
                    nis,
                    nama,
                    kelas,
                    email,
                    password
                  )
                  VALUES
                  (
                    '$nis',
                    '$nama',
                    '$kelas',
                    '$email',
                    '$password'
                  )";

        // Menjalankan query insert
        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION UPDATE SISWA
    // Mengubah data siswa
    // Jika password diisi maka password ikut diubah
    public function updateSiswa($data) {

        // Mengambil data dari form
        $nis = $data['nis'];
        $nama = $data['nama'];
        $kelas = $data['kelas'];
        $email = $data['email'];

        // Jika password baru diisi
        if (!empty($data['password'])) {

            // Password baru di-hash
            $password = password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            );

            // Query update termasuk password
            $query = "UPDATE siswa SET
                        nama = '$nama',
                        kelas = '$kelas',
                        email = '$email',
                        password = '$password'
                      WHERE nis = '$nis'";

        } else {

            // Query update tanpa password
            $query = "UPDATE siswa SET
                        nama = '$nama',
                        kelas = '$kelas',
                        email = '$email'
                      WHERE nis = '$nis'";
        }

        // Menjalankan query update
        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION HAPUS SISWA
    public function hapusSiswa($nis) {

        // Query delete siswa
        $query = "DELETE FROM siswa
                  WHERE nis = '$nis'";

        // Menjalankan query hapus
        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION CEK NIS
    // Mengecek apakah NIS sudah terdaftar
    // Digunakan saat proses registrasi
    public function cekNis($nis) {

        // Query mencari NIS yang sama
        $query = "SELECT * FROM siswa
                  WHERE nis = '$nis'";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Jika jumlah data > 0
        // berarti NIS sudah digunakan
        return mysqli_num_rows($result) > 0;
    }

    // FUNCTION UPDATE PROFIL
    // Digunakan siswa untuk mengubah profil sendiri
    // Tanpa mengubah password
    public function updateProfil($nis, $data) {

        // Mengambil data baru dari form
        $nama = $data['nama'];
        $kelas = $data['kelas'];
        $email = $data['email'];

        // Query update profil
        $query = "UPDATE siswa SET
                    nama = '$nama',
                    kelas = '$kelas',
                    email = '$email'
                  WHERE nis = '$nis'";

        // Menjalankan query update
        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION UPDATE PASSWORD
    // Digunakan siswa untuk mengganti password akun
    public function updatePassword($nis, $passwordBaru) {

        // Password baru di-hash agar aman
        $password = password_hash(
            $passwordBaru,
            PASSWORD_DEFAULT
        );

        // Query update password
        $query = "UPDATE siswa SET
                    password = '$password'
                  WHERE nis = '$nis'";

        // Menjalankan query update
        return mysqli_query($this->koneksi, $query);
    }
}
?>