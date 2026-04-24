<?php

require_once 'm_koneksi.php';

// Class Admin digunakan untuk mengelola data admin
// seperti login, tambah admin, edit admin, hapus admin, dll
class Admin
{

    // Property private untuk menyimpan koneksi database
    private $koneksi;

    // PROPERTI DATA ADMIN
    // Digunakan untuk menampung data dari tabel admin

    public $id_admin;       // ID unik admin
    public $username;       // Username untuk login
    public $password;       // Password admin
    public $nama_lengkap;   // Nama lengkap admin
    public $email;          // Email admin

    // CONSTRUCTOR
    // Otomatis berjalan saat object dibuat
    // Bertugas mengambil koneksi dari class Koneksi
    public function __construct()
    {

        // Membuat object koneksi database
        $db = new Koneksi();

        // Menyimpan koneksi ke property $koneksi
        $this->koneksi = $db->koneksi;
    }

    // FUNCTION LOGIN untuk Admin

    // Mengecek username dan password
    public function login($username, $password)
    {

        // Query mencari admin berdasarkan username
        $query = "SELECT * FROM admin 
                  WHERE username = '$username'";

        // Menjalankan query ke database
        $result = mysqli_query($this->koneksi, $query);

        // Mengambil hasil query menjadi array associative
        $admin = mysqli_fetch_assoc($result);

        // Jika data ditemukan dan password cocok
        if ($admin && password_verify($password, $admin['password'])) {

            // Mengembalikan data admin
            return $admin;
        }

        // Jika gagal login 
        return false;
    }

    // FUNCTION GET ALL ADMIN
    // Mengambil seluruh data admin
    // Biasanya digunakan untuk halaman daftar admin
    public function getAllAdmin()
    {

        // Query mengambil semua data admin
        $query = "SELECT 
                    id_admin,
                    username,
                    nama_lengkap,
                    email,
                    created_at
                  FROM admin
                  ORDER BY id_admin ASC";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Menyiapkan array kosong untuk menampung hasil
        $data = [];

        // Mengambil semua data satu per satu
        while ($row = mysqli_fetch_assoc($result)) {

            // Memasukkan setiap data ke array
            $data[] = $row;
        }

        // Mengembalikan semua data admin
        return $data;
    }

    // FUNCTION GET ADMIN BY ID
    // Mengambil 1 data admin berdasarkan ID
    public function getAdminById($id)
    {

        // Query mencari admin berdasarkan id_admin
        $query = "SELECT 
                    id_admin,
                    username,
                    nama_lengkap,
                    email
                  FROM admin
                  WHERE id_admin = '$id'";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Mengembalikan 1 data admin
        return mysqli_fetch_assoc($result);
    }

    // FUNCTION TAMBAH ADMIN
    // Digunakan untuk menambahkan admin baru
    public function tambahAdmin($data)
    {

        // Mengambil data dari form
        $username = $data['username'];
        $nama = $data['nama_lengkap'];
        $email = $data['email'];

        // Password di-hash agar lebih aman
        $password = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        // Query insert data admin baru
        $query = "INSERT INTO admin (
                    username,
                    password,
                    nama_lengkap,
                    email
                  ) VALUES (
                    '$username',
                    '$password',
                    '$nama',
                    '$email'
                  )";

        // Menjalankan query dan mengembalikan hasil
        return mysqli_query($this->koneksi, $query);
    }
    // FUNCTION UPDATE ADMIN
    // Digunakan untuk mengubah data admin
    // Jika password diisi maka password ikut diubah
    // Jika kosong maka password tetap
    public function updateAdmin($data)
    {

        // Mengambil data dari form
        $id = $data['id_admin'];
        $username = $data['username'];
        $nama = $data['nama_lengkap'];
        $email = $data['email'];

        // Jika password baru diisi
        if (!empty($data['password'])) {

            // Password baru di-hash
            $password = password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            );

            // Query update termasuk password
            $query = "UPDATE admin SET
                        username = '$username',
                        nama_lengkap = '$nama',
                        email = '$email',
                        password = '$password'
                      WHERE id_admin = '$id'";
        } else {

            // Query update tanpa mengubah password
            $query = "UPDATE admin SET
                        username = '$username',
                        nama_lengkap = '$nama',
                        email = '$email'
                      WHERE id_admin = '$id'";
        }

        // Menjalankan query update
        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION HAPUS ADMIN
    // Digunakan untuk menghapus data admin
    public function hapusAdmin($id)
    {

        // Query menghapus admin berdasarkan ID
        $query = "DELETE FROM admin
                  WHERE id_admin = '$id'";

        // Menjalankan query delete
        return mysqli_query($this->koneksi, $query);
    }

    // FUNCTION CEK USERNAME
    // Mengecek apakah username sudah terdaftar
    // Digunakan agar tidak ada username yang sama
    public function cekUsername($username)
    {

        // Query mencari username yang sama
        $query = "SELECT * FROM admin
                  WHERE username = '$username'";

        // Menjalankan query
        $result = mysqli_query($this->koneksi, $query);

        // Jika jumlah baris lebih dari 0
        // berarti username sudah digunakan
        return mysqli_num_rows($result) > 0;
    }
}
