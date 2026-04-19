<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Model
require_once '../MODEL/m_siswa.php';
require_once '../MODEL/m_admin.php';
require_once '../MODEL/m_aspirasi.php';
require_once '../MODEL/m_umpanbalik.php';
require_once '../MODEL/m_histori.php';
require_once '../MODEL/m_progres.php';

class AdminController {
    private $siswaModel;
    private $adminModel;
    private $aspirasiModel;
    private $umpanBalikModel;
    private $historiModel;
    private $progresModel;

    public function __construct() {
        $this->siswaModel = new Siswa();
        $this->adminModel = new Admin();
        $this->aspirasiModel = new Aspirasi();
        $this->umpanBalikModel = new UmpanBalik();
        $this->historiModel = new HistoriAspirasi();
        $this->progresModel = new Progres();

        $this->cekLogin();
    }

    private function cekLogin() {
        if (!isset($_SESSION['admin'])) {
            header("Location: ../VIEW/auth/v_login.php");
            exit();
        }
    }

    //SISWA 

    public function tambahSiswa($data) {
        if ($this->siswaModel->cekNis($data['nis'])) {
            $_SESSION['error'] = "NIS sudah terdaftar!";
            header("Location: ../VIEW/ADMIN/FormTambahSiswa.php");
            exit();
        }

        if ($this->siswaModel->tambahSiswa($data)) {
            $_SESSION['success'] = "Siswa berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan siswa!";
        }

        header("Location: ../VIEW/ADMIN/v_DaftarSiswa.php");
        exit();
    }

    public function updateSiswa($data) {
        if ($this->siswaModel->updateSiswa($data)) {
            $_SESSION['success'] = "Data siswa berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal update siswa!";
        }

        header("Location: ../VIEW/ADMIN/v_DaftarSiswa.php");
        exit();
    }

    public function hapusSiswa($nis) {
        if ($this->siswaModel->hapusSiswa($nis)) {
            $_SESSION['success'] = "Siswa berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal hapus siswa!";
        }

        header("Location: ../VIEW/ADMIN/v_DaftarSiswa.php");
        exit();
    }

    //ASPIRASI

    public function updateStatusAspirasi($id, $status) {
        $idAdmin = $_SESSION['id_admin'];

        $aspirasi = $this->aspirasiModel->getAspirasiById($id);

        if (!$aspirasi) {
            $_SESSION['error'] = "Data tidak ditemukan!";
            header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
            exit();
        }

        $statusLama = $aspirasi['status'];

        $statusMap = [
            'menunggu' => 'belum ditangani',
            'diproses' => 'dalam proses',
            'selesai' => 'selesai'
        ];

        if (!isset($statusMap[$status]) || !isset($statusMap[$statusLama])) {
            $_SESSION['error'] = "Status tidak valid!";
            header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
            exit();
        }

        if ($this->aspirasiModel->updateStatus($id, $status, $idAdmin)) {

            $historiData = [
                'id_aspirasi' => $id,
                'status_sebelum' => $statusMap[$statusLama],
                'status_sesudah' => $statusMap[$status]
            ];

            $this->historiModel->tambahHistori($historiData);

            $_SESSION['success'] = "Status berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal update status!";
        }

        header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
        exit();
    }

    // UMPAN BALIK

    public function tambahUmpanBalik($data) {
        $data['id_admin'] = $_SESSION['id_admin'];

        if ($this->umpanBalikModel->tambahUmpanBalik($data)) {
            $_SESSION['success'] = "Berhasil tambah umpan balik!";
        } else {
            $_SESSION['error'] = "Gagal tambah umpan balik!";
        }

        header("Location: ../VIEW/ADMIN/UmpanBalik.php?id=" . $data['id_aspirasi']);
        exit();
    }

    public function updateUmpanBalik($id, $data) {
        $data['id_admin'] = $_SESSION['id_admin'];

        if ($this->umpanBalikModel->updateUmpanBalik($id, $data)) {
            $_SESSION['success'] = "Berhasil update umpan balik!";
        } else {
            $_SESSION['error'] = "Gagal update umpan balik!";
        }

        header("Location: ../VIEW/ADMIN/UmpanBalik.php?id=" . $data['id_aspirasi']);
        exit();
    }

    //PROGRES

    public function tambahProgres($data, $file) {

        $foto = null;

        if (isset($file['foto_bukti']) && $file['foto_bukti']['error'] == 0) {

            $allowed = ['jpg','jpeg','png'];
            $ext = strtolower(pathinfo($file['foto_bukti']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $newName = time() . '_' . $file['foto_bukti']['name'];

                if (move_uploaded_file($file['foto_bukti']['tmp_name'], '../ASSETS/GAMBAR/' . $newName)) {
                    $foto = $newName;
                }
            }
        }

        $dataProgres = [
            'id_aspirasi' => $data['id_aspirasi'],
            'deskripsi_progres' => $data['deskripsi_progres'],
            'foto_bukti' => $foto
        ];

        if ($this->progresModel->tambahProgres($dataProgres)) {
            $_SESSION['success'] = "Progres berhasil!";
        } else {
            $_SESSION['error'] = "Gagal tambah progres!";
        }

        header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
        exit();
    }
}

//HANDLE REQUEST

$controller = new AdminController();

if (isset($_POST['tambah_siswa'])) {
    $controller->tambahSiswa($_POST);
}
elseif (isset($_POST['update_siswa'])) {
    $controller->updateSiswa($_POST);
}
elseif (isset($_GET['hapus_siswa'])) {
    $controller->hapusSiswa($_GET['hapus_siswa']);
}
elseif (isset($_POST['update_status'])) {
    $controller->updateStatusAspirasi($_POST['id_aspirasi'], $_POST['status']);
}
elseif (isset($_POST['tambah_umpanbalik'])) {
    $controller->tambahUmpanBalik($_POST);
}
elseif (isset($_POST['update_umpanbalik'])) {
    $controller->updateUmpanBalik($_POST['id_UmpanBalik'], $_POST);
}
elseif (isset($_POST['tambah_progres'])) {
    $controller->tambahProgres($_POST, $_FILES);
}
?>