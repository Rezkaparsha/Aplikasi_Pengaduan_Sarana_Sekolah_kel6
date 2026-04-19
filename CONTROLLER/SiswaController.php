<?php
session_start();
require_once '../MODEL/m_siswa.php';
require_once '../MODEL/m_aspirasi.php';

class SiswaController {
    private $siswaModel;
    private $aspirasiModel;

    public function __construct() {
        $this->siswaModel = new Siswa();
        $this->aspirasiModel = new Aspirasi();
        $this->cekLogin();
    }

    private function cekLogin() {
        if (!isset($_SESSION['siswa'])) {
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        }
    }

    public function updateProfil($data) {
        $nis = $_SESSION['nis'];

        if ($this->siswaModel->updateProfil($nis, $data)) {
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['success'] = "Profil berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate profil!";
        }

        header("Location: ../VIEW/SISWA/profil_siswa.php");
        exit();
    }

    public function updatePassword($passwordLama, $passwordBaru) {
        $nis = $_SESSION['nis'];

        $siswa = $this->siswaModel->getSiswaByNis($nis);

        if (!password_verify($passwordLama, $siswa['password'])) {
            $_SESSION['error'] = "Password lama salah!";
            header("Location: ../VIEW/SISWA/profil_siswa.php");
            exit();
        }

        if ($this->siswaModel->updatePassword($nis, $passwordBaru)) {
            $_SESSION['success'] = "Password berhasil diubah!";
        } else {
            $_SESSION['error'] = "Gagal mengubah password!";
        }

        header("Location: ../VIEW/SISWA/profil_siswa.php");
        exit();
    }

    public function kirimAspirasi($data, $file) {
        $nis = $_SESSION['nis'];

        $fotoGambar = null;

        if ($file['foto_gambar']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $file['foto_gambar']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $newName = time() . '_' . $filename;
                $uploadDir = '../ASSETS/GAMBAR/';

                if (move_uploaded_file($file['foto_gambar']['tmp_name'], $uploadDir . $newName)) {
                    $fotoGambar = $newName;
                }
            }
        }

        $aspirasiData = [
            'nis' => $nis,
            'judul_laporan' => $data['judul_laporan'],
            'keterangan' => $data['keterangan'],
            'kategori_prioritas' => $data['kategori_prioritas'],
            'lokasi' => $data['lokasi'],
            'foto_gambar' => $fotoGambar
        ];

        if ($this->aspirasiModel->tambahAspirasi($aspirasiData)) {
            $_SESSION['success'] = "Aspirasi berhasil dikirim!";
        } else {
            $_SESSION['error'] = "Gagal mengirim aspirasi!";
        }

        header("Location: ../VIEW/SISWA/daftar_laporan.php");
        exit();
    }

    public function hapusAspirasi($id) {
        $nis = $_SESSION['nis'];

        $aspirasi = $this->aspirasiModel->getAspirasiById($id);

        if ($aspirasi && $aspirasi['nis'] == $nis) {

            if ($aspirasi['foto_gambar'] && file_exists('../ASSETS/GAMBAR/' . $aspirasi['foto_gambar'])) {
                unlink('../ASSETS/GAMBAR/' . $aspirasi['foto_gambar']);
            }

            // PANGGIL MODEL
            if ($this->aspirasiModel->hapusAspirasi($id, $nis)) {
                $_SESSION['success'] = "Aspirasi berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus aspirasi!";
            }

        } else {
            $_SESSION['error'] = "Aspirasi tidak ditemukan!";
        }

        header("Location: ../VIEW/SISWA/daftar_laporan.php");
        exit();
    }
}

// ROUTING
$controller = new SiswaController();

if (isset($_POST['update_profil'])) {
    $controller->updateProfil($_POST);
} elseif (isset($_POST['update_password'])) {
    $controller->updatePassword($_POST['password_lama'], $_POST['password_baru']);
} elseif (isset($_POST['kirim_aspirasi'])) {
    $controller->kirimAspirasi($_POST, $_FILES);
} elseif (isset($_GET['hapus_aspirasi'])) {
    $controller->hapusAspirasi($_GET['hapus_aspirasi']);
}
?>