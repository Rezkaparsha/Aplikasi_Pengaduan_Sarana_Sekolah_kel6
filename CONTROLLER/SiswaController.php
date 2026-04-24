<?php

// Memulai session untuk menyimpan data login siswa
session_start();

// Memanggil file model yang dibutuhkan
require_once '../MODEL/m_siswa.php';     // Model data siswa
require_once '../MODEL/m_aspirasi.php';  // Model data aspirasi

// Class controller untuk mengatur semua proses siswa
class SiswaController {

    // Property private untuk menyimpan object model
    private $siswaModel;
    private $aspirasiModel;

    // Constructor otomatis berjalan saat object dibuat
    public function __construct() {

        // Membuat object model siswa
        $this->siswaModel = new Siswa();

        // Membuat object model aspirasi
        $this->aspirasiModel = new Aspirasi();

        // Mengecek apakah siswa sudah login
        $this->cekLogin();
    }

    // FUNCTION CEK LOGIN
    // Memastikan hanya siswa yang sudah login bisa masuk

    private function cekLogin() {

        // Jika session siswa tidak ada
        if (!isset($_SESSION['siswa'])) {

            // Redirect ke halaman login
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        }
    }

    // FUNCTION UPDATE PROFIL
    // Digunakan untuk mengubah data profil siswa
    public function updateProfil($data) {

        // Mengambil NIS dari session login
        $nis = $_SESSION['nis'];

        // Menjalankan update profil melalui model
        if ($this->siswaModel->updateProfil($nis, $data)) {

            // Update nama di session agar langsung berubah
            $_SESSION['nama'] = $data['nama'];

            // Pesan berhasil
            $_SESSION['success'] = "Profil berhasil diupdate!";

        } else {

            // Pesan gagal
            $_SESSION['error'] = "Gagal mengupdate profil!";
        }

        // Redirect ke halaman profil siswa
        header("Location: ../VIEW/SISWA/profil_siswa.php");
        exit();
    }

    // FUNCTION UPDATE PASSWORD
    // Digunakan untuk mengganti password siswa
    public function updatePassword($passwordLama, $passwordBaru) {

        // Mengambil NIS dari session
        $nis = $_SESSION['nis'];

        // Mengambil data siswa berdasarkan NIS
        $siswa = $this->siswaModel->getSiswaByNis($nis);

        // Mengecek apakah password lama benar
        if (!password_verify($passwordLama, $siswa['password'])) {

            // Jika salah
            $_SESSION['error'] = "Password lama salah!";

            // Kembali ke halaman profil
            header("Location: ../VIEW/SISWA/profil_siswa.php");
            exit();
        }

        // Jika password lama benar, update password baru
        if ($this->siswaModel->updatePassword($nis, $passwordBaru)) {

            // Pesan berhasil
            $_SESSION['success'] = "Password berhasil diubah!";

        } else {

            // Pesan gagal
            $_SESSION['error'] = "Gagal mengubah password!";
        }

        // Redirect kembali ke halaman profil
        header("Location: ../VIEW/SISWA/profil_siswa.php");
        exit();
    }

    // FUNCTION KIRIM ASPIRASI
    // Digunakan untuk mengirim laporan/aspirasi baru
    public function kirimAspirasi($data, $file) {

        // Mengambil NIS siswa dari session
        $nis = $_SESSION['nis'];

        // Default foto kosong
        $fotoGambar = null;

        // Jika user upload file dan tidak error
        if ($file['foto_gambar']['error'] == 0) {

            // Format file yang diperbolehkan
            $allowed = ['jpg', 'jpeg', 'png'];

            // Nama file asli
            $filename = $file['foto_gambar']['name'];

            // Mengambil ekstensi file
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            // Jika ekstensi file valid
            if (in_array($ext, $allowed)) {

                // Membuat nama file baru agar tidak bentrok
                $newName = time() . '_' . $filename;

                // Folder tujuan upload
                $uploadDir = '../ASSETS/GAMBAR/';

                // Memindahkan file ke folder tujuan
                if (move_uploaded_file(
                    $file['foto_gambar']['tmp_name'],
                    $uploadDir . $newName
                )) {

                    // Simpan nama file baru ke database
                    $fotoGambar = $newName;
                }
            }
        }

        // Menyusun data aspirasi untuk dikirim ke model
        $aspirasiData = [
            'nis' => $nis,
            'judul_laporan' => $data['judul_laporan'],
            'keterangan' => $data['keterangan'],
            'kategori_prioritas' => $data['kategori_prioritas'],
            'lokasi' => $data['lokasi'],
            'foto_gambar' => $fotoGambar
        ];

        // Menyimpan data aspirasi ke database
        if ($this->aspirasiModel->tambahAspirasi($aspirasiData)) {

            // Pesan berhasil
            $_SESSION['success'] = "Aspirasi berhasil dikirim!";

        } else {

            // Pesan gagal
            $_SESSION['error'] = "Gagal mengirim aspirasi!";
        }

        // Redirect ke daftar laporan
        header("Location: ../VIEW/SISWA/daftar_laporan.php");
        exit();
    }

    // FUNCTION HAPUS ASPIRASI
    // Digunakan untuk menghapus aspirasi milik siswa
    public function hapusAspirasi($id) {

        // Mengambil NIS siswa dari session
        $nis = $_SESSION['nis'];

        // Mengambil data aspirasi berdasarkan ID
        $aspirasi = $this->aspirasiModel->getAspirasiById($id);

        // Cek apakah data ada dan milik siswa yang login
        if ($aspirasi && $aspirasi['nis'] == $nis) {

            // Jika ada foto dan file benar-benar ada
            if (
                $aspirasi['foto_gambar'] &&
                file_exists('../ASSETS/GAMBAR/' . $aspirasi['foto_gambar'])
            ) {

                // Hapus file gambar dari folder
                unlink('../ASSETS/GAMBAR/' . $aspirasi['foto_gambar']);
            }

            // Menghapus data aspirasi dari database
            if ($this->aspirasiModel->hapusAspirasi($id, $nis)) {

                // Pesan berhasil
                $_SESSION['success'] = "Aspirasi berhasil dihapus!";

            } else {

                // Pesan gagal
                $_SESSION['error'] = "Gagal menghapus aspirasi!";
            }

        } else {

            // Jika data tidak ditemukan
            $_SESSION['error'] = "Aspirasi tidak ditemukan!";
        }

        // Redirect ke daftar laporan
        header("Location: ../VIEW/SISWA/daftar_laporan.php");
        exit();
    }
}


// ROUTING
// Menentukan aksi berdasarkan tombol form yang ditekan

// Membuat object controller siswa
$controller = new SiswaController();


// Jika tombol update profil ditekan
if (isset($_POST['update_profil'])) {

    // Jalankan function update profil
    $controller->updateProfil($_POST);


// Jika tombol update password ditekan
} elseif (isset($_POST['update_password'])) {

    // Jalankan function update password
    $controller->updatePassword(
        $_POST['password_lama'],
        $_POST['password_baru']
    );


// Jika tombol kirim aspirasi ditekan
} elseif (isset($_POST['kirim_aspirasi'])) {

    // Jalankan function kirim aspirasi
    $controller->kirimAspirasi($_POST, $_FILES);


// Jika tombol hapus aspirasi dipanggil
} elseif (isset($_GET['hapus_aspirasi'])) {

    // Jalankan function hapus aspirasi
    $controller->hapusAspirasi($_GET['hapus_aspirasi']);
}

?>