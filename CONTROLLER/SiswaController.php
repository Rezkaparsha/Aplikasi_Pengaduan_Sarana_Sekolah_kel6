<?php
session_start();
require_once '../MODEL/m_siswa.php';
require_once '../MODEL/m_aspirasi.php';

/**
 * Class SiswaController
 * Controller untuk mengelola operasi siswa
 */
class SiswaController {
    private $siswaModel;
    private $aspirasiModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->siswaModel = new Siswa();
        $this->aspirasiModel = new Aspirasi();
        $this->cekLogin();
    }
    
    /**
     * Cek apakah siswa sudah login
     */
    private function cekLogin() {
        if (!isset($_SESSION['siswa'])) {
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        }
    }
    
    /**
     * Update profil siswa
     * @param array $data
     */
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
    
    /**
     * Update password siswa
     * @param string $passwordLama
     * @param string $passwordBaru
     */
    public function updatePassword($passwordLama, $passwordBaru) {
        $nis = $_SESSION['nis'];
        
        // Verifikasi password lama
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
    
    /**
     * Kirim aspirasi baru
     * @param array $data
     * @param array $file
     */
    public function kirimAspirasi($data, $file) {
        $nis = $_SESSION['nis'];
        
        // Handle upload gambar
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
    
    /**
     * Hapus aspirasi siswa
     * @param int $id
     */
    public function hapusAspirasi($id) {
        $nis = $_SESSION['nis'];
        
        // Cek kepemilikan aspirasi
        $aspirasi = $this->aspirasiModel->getAspirasiById($id);
        if ($aspirasi && $aspirasi['nis'] == $nis) {
            // Hapus foto jika ada
            if ($aspirasi['foto_gambar'] && file_exists('../ASSETS/GAMBAR/' . $aspirasi['foto_gambar'])) {
                unlink('../ASSETS/GAMBAR/' . $aspirasi['foto_gambar']);
            }
            
            // Query untuk hapus aspirasi (perlu dibuat di model)
            $pdo = Koneksi::getInstance()->getPdo();
            $query = "DELETE FROM aspirasi WHERE id_aspirasi = :id AND nis = :nis";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nis', $nis, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
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

// Handle request
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
