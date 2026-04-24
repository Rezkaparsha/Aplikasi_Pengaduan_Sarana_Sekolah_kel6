<?php

// Memulai session untuk menyimpan data login user
// Session digunakan untuk menyimpan status login admin maupun siswa
session_start();

// Memanggil file model yang dibutuhkan
require_once '../MODEL/m_siswa.php'; // Model data siswa
require_once '../MODEL/m_admin.php'; // Model data admin


//Class AuthController
//Controller untuk mengatur proses autentikasi:
class AuthController
{

    // Property private untuk menyimpan object model
    private $siswaModel;
    private $adminModel;

    // Constructor otomatis berjalan saat object dibuat
    public function __construct()
    {

        // Membuat object model siswa
        $this->siswaModel = new Siswa();

        // Membuat object model admin
        $this->adminModel = new Admin();
    }

    // FUNCTION LOGIN SISWA
    public function loginSiswa($nis, $password)
    {

        // Memanggil function login pada model siswa
        // untuk mengecek NIS dan password di database
        $siswa = $this->siswaModel->login($nis, $password);

        // Jika data siswa ditemukan
        if ($siswa) {

            // Hapus session admin jika masih ada
            unset($_SESSION['admin']);
            unset($_SESSION['id_admin']);
            unset($_SESSION['username']);
            unset($_SESSION['nama_lengkap']);

            // Menyimpan data login ke dalam session
            $_SESSION['siswa'] = true;
            $_SESSION['nis'] = $siswa['nis'];
            $_SESSION['nama'] = $siswa['nama'];
            $_SESSION['kelas'] = $siswa['kelas'];

            // Redirect ke dashboard siswa
            header("Location: ../VIEW/SISWA/dasbord_siswa.php");
            exit();
        } else {

            // Jika login gagal, simpan pesan error
            $_SESSION['error'] = "NIS atau password salah!";

            // Kembali ke halaman login
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        }
    }


    // FUNCTION LOGIN ADMIN

    public function loginAdmin($username, $password)
    {

        // Memanggil function login pada model admin
        $admin = $this->adminModel->login($username, $password);

        // Jika data admin ditemukan
        if ($admin) {

            // Hapus session siswa jika masih ada
            unset($_SESSION['siswa']);
            unset($_SESSION['nis']);
            unset($_SESSION['nama']);
            unset($_SESSION['kelas']);

            // Menyimpan data login admin ke session
            $_SESSION['admin'] = true;
            $_SESSION['id_admin'] = $admin['id_admin'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['nama_lengkap'] = $admin['nama_lengkap'];

            // Redirect ke dashboard admin
            header("Location: ../VIEW/ADMIN/v_dasbord_admin.php");
            exit();
        } else {

            // Jika login gagal
            $_SESSION['error'] = "Username atau password salah!";

            // Kembali ke halaman login
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        }
    }

    // FUNCTION REGISTER SISWA
    // Digunakan untuk proses pendaftaran akun siswa

    public function registerSiswa($data)
    {

        // Validasi jika ada field yang kosong
        if (
            empty($data['nis']) ||
            empty($data['nama']) ||
            empty($data['kelas']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {

            // Jika ada yang kosong, tampilkan error
            $_SESSION['error'] = "Semua field harus diisi!";

            // Kembali ke halaman register
            header("Location: ../VIEW/auth/v_register.php");
            exit();
        }

        // Mengecek apakah NIS sudah terdaftar sebelumnya
        if ($this->siswaModel->cekNis($data['nis'])) {

            // Jika NIS sudah ada
            $_SESSION['error'] = "NIS sudah terdaftar!";

            // Kembali ke halaman register
            header("Location: ../VIEW/auth/v_register.php");
            exit();
        }

        // Menyimpan data siswa baru ke database melalui model
        if ($this->siswaModel->tambahSiswa($data)) {

            // Jika berhasil
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";

            // Redirect ke halaman login
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        } else {

            // Jika gagal menyimpan data
            $_SESSION['error'] = "Registrasi gagal!";

            // Kembali ke halaman register
            header("Location: ../VIEW/auth/v_register.php");
            exit();
        }
    }

    // FUNCTION LOGOUT
    // Digunakan untuk keluar dari sistem

    public function logout()
    {

        // Menghapus semua session login
        session_destroy();

        // Redirect ke halaman login
        header("Location: ../VIEW/auth/v_login.php");
        exit();
    }
}


// BAGIAN HANDLE REQUEST
// Berfungsi sebagai pemicu aksi dari form login/register

// Membuat object controller auth
$auth = new AuthController();


// Jika tombol login siswa ditekan
if (isset($_POST['login_siswa'])) {

    // Jalankan function login siswa
    $auth->loginSiswa($_POST['nis'], $_POST['password']);


    // Jika tombol login admin ditekan
} elseif (isset($_POST['login_admin'])) {

    // Jalankan function login admin
    $auth->loginAdmin($_POST['username'], $_POST['password']);


    // Jika tombol register siswa ditekan
} elseif (isset($_POST['register_siswa'])) {

    // Jalankan function register siswa
    $auth->registerSiswa($_POST);


    // Jika tombol logout dipanggil melalui URL
} elseif (isset($_GET['logout'])) {

    // Jalankan function logout
    $auth->logout();
}
