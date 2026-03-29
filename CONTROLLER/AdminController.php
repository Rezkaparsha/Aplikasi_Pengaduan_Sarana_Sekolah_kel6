<?php
session_start();
require_once '../MODEL/m_siswa.php';
require_once '../MODEL/m_admin.php';
require_once '../MODEL/m_aspirasi.php';
require_once '../MODEL/m_umpanbalik.php';
require_once '../MODEL/m_histori.php';
require_once '../MODEL/m_progres.php';

/**
 * Class AdminController
 * Controller untuk mengelola operasi admin
 */
class AdminController {
    private $siswaModel;
    private $adminModel;
    private $aspirasiModel;
    private $umpanBalikModel;
    private $historiModel;
    private $progresModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->siswaModel = new Siswa();
        $this->adminModel = new Admin();
        $this->aspirasiModel = new Aspirasi();
        $this->umpanBalikModel = new UmpanBalik();
        $this->historiModel = new HistoriAspirasi();
        $this->progresModel = new Progres();
        $this->cekLogin();
    }
    
    /**
     * Cek apakah admin sudah login
     */
    private function cekLogin() {
        if (!isset($_SESSION['admin'])) {
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        }
    }
    
    // ==================== MANAJEMEN SISWA ====================
    
    /**
     * Tambah siswa baru
     * @param array $data
     */
    public function tambahSiswa($data) {
        if ($this->siswaModel->cekNis($data['nis'])) {
            $_SESSION['error'] = "NIS sudah terdaftar!";
            header("Location: ../VIEW/ADMIN/FormTambahSiswa.php");
            exit();
        }
        
        if ($this->siswaModel->tambahSiswa($data)) {
            $_SESSION['success'] = "Siswa berhasil ditambahkan!";
            header("Location: ../VIEW/ADMIN/v_DaftarSiswa.php");
        } else {
            $_SESSION['error'] = "Gagal menambahkan siswa!";
            header("Location: ../VIEW/ADMIN/FormTambahSiswa.php");
        }
        exit();
    }
    
    /**
     * Update data siswa
     * @param array $data
     */
    public function updateSiswa($data) {
        if ($this->siswaModel->updateSiswa($data)) {
            $_SESSION['success'] = "Data siswa berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate data siswa!";
        }
        header("Location: ../VIEW/ADMIN/v_DaftarSiswa.php");
        exit();
    }
    
    /**
     * Hapus siswa
     * @param int $nis
     */
    public function hapusSiswa($nis) {
        if ($this->siswaModel->hapusSiswa($nis)) {
            $_SESSION['success'] = "Siswa berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus siswa!";
        }
        header("Location: ../VIEW/ADMIN/v_DaftarSiswa.php");
        exit();
    }
    
    // ==================== MANAJEMEN ADMIN ====================
    
    /**
     * Tambah admin baru
     * @param array $data
     */
    public function tambahAdmin($data) {
        if ($this->adminModel->cekUsername($data['username'])) {
            $_SESSION['error'] = "Username sudah digunakan!";
            header("Location: ../VIEW/ADMIN/v_dasbord_admin.php");
            exit();
        }
        
        if ($this->adminModel->tambahAdmin($data)) {
            $_SESSION['success'] = "Admin berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan admin!";
        }
        header("Location: ../VIEW/ADMIN/v_dasbord_admin.php");
        exit();
    }
    
    /**
     * Update data admin
     * @param array $data
     */
    public function updateAdmin($data) {
        if ($this->adminModel->updateAdmin($data)) {
            $_SESSION['success'] = "Data admin berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate data admin!";
        }
        header("Location: ../VIEW/ADMIN/v_dasbord_admin.php");
        exit();
    }
    
    /**
     * Hapus admin
     * @param int $id
     */
    public function hapusAdmin($id) {
        // Cek jangan hapus admin yang sedang login
        if ($id == $_SESSION['id_admin']) {
            $_SESSION['error'] = "Tidak dapat menghapus akun sendiri!";
            header("Location: ../VIEW/ADMIN/v_dasbord_admin.php");
            exit();
        }
        
        if ($this->adminModel->hapusAdmin($id)) {
            $_SESSION['success'] = "Admin berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus admin!";
        }
        header("Location: ../VIEW/ADMIN/v_dasbord_admin.php");
        exit();
    }
    
    // ==================== MANAJEMEN ASPIRASI ====================
    
    /**
     * Update status aspirasi
     * @param int $id
     * @param string $status
     */
    public function updateStatusAspirasi($id, $status) {
        $idAdmin = $_SESSION['id_admin'];
        
        // Ambil data aspirasi lama untuk histori
        $aspirasi = $this->aspirasiModel->getAspirasiById($id);
        $statusLama = $aspirasi['status'];
        
        // Mapping status
        $statusMap = [
            'menunggu' => 'belum ditangani',
            'diproses' => 'dalam proses',
            'selesai' => 'selesai'
        ];
        
        if ($this->aspirasiModel->updateStatus($id, $status, $idAdmin)) {
            // Tambah histori
            $historiData = [
                'id_aspirasi' => $id,
                'status_sebelum' => $statusMap[$statusLama],
                'status_sesudah' => $statusMap[$status]
            ];
            $this->historiModel->tambahHistori($historiData);
            
            $_SESSION['success'] = "Status aspirasi berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate status!";
        }
        
        header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
        exit();
    }
    
    /**
     * Update prioritas aspirasi
     * @param int $id
     * @param string $prioritas
     */
    public function updatePrioritas($id, $prioritas) {
        if ($this->aspirasiModel->updatePrioritas($id, $prioritas)) {
            $_SESSION['success'] = "Prioritas berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate prioritas!";
        }
        header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
        exit();
    }
    
    // ==================== UMPAN BALIK ====================
    
    /**
     * Tambah umpan balik
     * @param array $data
     */
    public function tambahUmpanBalik($data) {
        $data['id_admin'] = $_SESSION['id_admin'];
        
        if ($this->umpanBalikModel->tambahUmpanBalik($data)) {
            $_SESSION['success'] = "Umpan balik berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan umpan balik!";
        }
        
        header("Location: ../VIEW/ADMIN/UmpanBalik.php?id=" . $data['id_aspirasi']);
        exit();
    }
    
    /**
     * Update umpan balik
     * @param int $id
     * @param array $data
     */
    public function updateUmpanBalik($id, $data) {
        $data['id_admin'] = $_SESSION['id_admin'];
        
        if ($this->umpanBalikModel->updateUmpanBalik($id, $data)) {
            $_SESSION['success'] = "Umpan balik berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate umpan balik!";
        }
        
        header("Location: ../VIEW/ADMIN/UmpanBalik.php?id=" . $data['id_aspirasi']);
        exit();
    }
    
    // ==================== PROGRES ====================
    
    /**
     * Tambah progres
     * @param array $data
     * @param array $file
     */
    public function tambahProgres($data, $file) {
        // Handle upload foto bukti
        $fotoBukti = null;
        if ($file['foto_bukti']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $file['foto_bukti']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $newName = 'progres_' . time() . '_' . $filename;
                $uploadDir = '../ASSETS/GAMBAR/';
                
                if (move_uploaded_file($file['foto_bukti']['tmp_name'], $uploadDir . $newName)) {
                    $fotoBukti = $newName;
                }
            }
        }
        
        $progresData = [
            'id_aspirasi' => $data['id_aspirasi'],
            'deskripsi_progres' => $data['deskripsi_progres'],
            'foto_bukti' => $fotoBukti
        ];
        
        if ($this->progresModel->tambahProgres($progresData)) {
            $_SESSION['success'] = "Progres berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan progres!";
        }
        
        header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
        exit();
    }
}

// Handle request
$controller = new AdminController();

// Manajemen Siswa
if (isset($_POST['tambah_siswa'])) {
    $controller->tambahSiswa($_POST);
} elseif (isset($_POST['update_siswa'])) {
    $controller->updateSiswa($_POST);
} elseif (isset($_GET['hapus_siswa'])) {
    $controller->hapusSiswa($_GET['hapus_siswa']);
}

// Manajemen Admin
elseif (isset($_POST['tambah_admin'])) {
    $controller->tambahAdmin($_POST);
} elseif (isset($_POST['update_admin'])) {
    $controller->updateAdmin($_POST);
} elseif (isset($_GET['hapus_admin'])) {
    $controller->hapusAdmin($_GET['hapus_admin']);
}

// Manajemen Aspirasi
elseif (isset($_POST['update_status'])) {
    $controller->updateStatusAspirasi($_POST['id_aspirasi'], $_POST['status']);
} elseif (isset($_POST['update_prioritas'])) {
    $controller->updatePrioritas($_POST['id_aspirasi'], $_POST['kategori_prioritas']);
}

// Umpan Balik
elseif (isset($_POST['tambah_umpanbalik'])) {
    $controller->tambahUmpanBalik($_POST);
} elseif (isset($_POST['update_umpanbalik'])) {
    $controller->updateUmpanBalik($_POST['id_UmpanBalik'], $_POST);
}

// Progres
elseif (isset($_POST['tambah_progres'])) {
    $controller->tambahProgres($_POST, $_FILES);
}
?>
