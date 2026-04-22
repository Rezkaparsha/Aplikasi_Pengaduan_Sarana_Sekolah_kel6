<?php
session_start();

require_once '../MODEL/m_siswa.php';
require_once '../MODEL/m_admin.php';

/**
 * Class AuthController
 * Controller untuk mengelola autentikasi (login, register, logout)
 * Menggunakan konsep OOP
 */
class AuthController {
    private $siswaModel;
    private $adminModel;
    

    public function __construct() {
        // Inisialisasi Model. 
        // Secara otomatis, Model-model ini sekarang bekerja menggunakan mysqli.
        $this->siswaModel = new Siswa();
        $this->adminModel = new Admin();
    }
    
    //Proses login siswa

    public function loginSiswa($nis, $password) {
        // Memanggil fungsi login di model siswa
        $siswa = $this->siswaModel->login($nis, $password);
        
        if ($siswa) {
            // Jika data ditemukan (hasil mysqli_fetch_assoc di model tidak null)
            $_SESSION['siswa'] = true;
            $_SESSION['nis'] = $siswa['nis'];
            $_SESSION['nama'] = $siswa['nama'];
            $_SESSION['kelas'] = $siswa['kelas'];
            
            header("Location: ../VIEW/SISWA/dasbord_siswa.php");
            exit();
        } else {
            $_SESSION['error'] = "NIS atau password salah!";
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        }
    }
    
    //Proses login admin

    public function loginAdmin($username, $password) {
        // Memanggil fungsi login di model admin
        $admin = $this->adminModel->login($username, $password);
        
        if ($admin) {
            $_SESSION['admin'] = true;
            $_SESSION['id_admin'] = $admin['id_admin'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['nama_lengkap'] = $admin['nama_lengkap'];
            
            header("Location: ../VIEW/ADMIN/v_dasbord_admin.php");
            exit();
        } else {
            $_SESSION['error'] = "Username atau password salah!";
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        }
    }
    
    //Proses register siswa

    public function registerSiswa($data) {
        // Validasi Untuk register
        if (empty($data['nis']) || empty($data['nama']) || empty($data['kelas']) || 
            empty($data['email']) || empty($data['password'])) {
            $_SESSION['error'] = "Semua field harus diisi!";
            header("Location: ../VIEW/auth/v_register.php");
            exit();
        }
        
        // Cek NIS menggunakan fungsi cekNis yang sudah  dibuat di Model
        if ($this->siswaModel->cekNis($data['nis'])) {
            $_SESSION['error'] = "NIS sudah terdaftar!";
            header("Location: ../VIEW/auth/v_register.php");
            exit();
        }
        
        // Simpan data melalui model
        if ($this->siswaModel->tambahSiswa($data)) {
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        } else {
            $_SESSION['error'] = "Registrasi gagal!";
            header("Location: ../VIEW/auth/v_register.php");
            exit();
        }
    }
    
    //Proses logout

    public function logout() {
        session_destroy();
        header("Location: ../VIEW/auth/v_login.php");
        exit();
    }
}

//BAGIAN HANDLE REQUEST (Pemicu aksi dari Form)

$auth = new AuthController();

if (isset($_POST['login_siswa'])) {
    $auth->loginSiswa($_POST['nis'], $_POST['password']);
} elseif (isset($_POST['login_admin'])) {
    $auth->loginAdmin($_POST['username'], $_POST['password']);
} elseif (isset($_POST['register_siswa'])) {
    $auth->registerSiswa($_POST);
} elseif (isset($_GET['logout'])) {
    $auth->logout();
}
?>