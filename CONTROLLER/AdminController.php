ie codingan Admin conttroller
<?php
// Memulai session untuk menyimpan data login dan pesan (success/error)
session_start();


// IMPORT MODEL (DATABASE)
// Menghubungkan controller dengan file model
require_once '../MODEL/m_siswa.php';
require_once '../MODEL/m_admin.php';
require_once '../MODEL/m_aspirasi.php';
require_once '../MODEL/m_umpanbalik.php';
require_once '../MODEL/m_histori.php';
require_once '../MODEL/m_progres.php';

// CLASS CONTROLLER
class AdminController {

    // Property untuk menyimpan object model
    private $siswaModel;
    private $adminModel;
    private $aspirasiModel;
    private $umpanBalikModel;
    private $historiModel;
    private $progresModel;


    // CONSTRUCTOR (OTOMATIS DIJALANKAN)
    public function __construct() {

        // Membuat object dari masing-masing model
        $this->siswaModel = new Siswa();
        $this->adminModel = new Admin();
        $this->aspirasiModel = new Aspirasi();
        $this->umpanBalikModel = new UmpanBalik();
        $this->historiModel = new HistoriAspirasi();
        $this->progresModel = new Progres();

        // Cek apakah admin sudah login
        $this->cekLogin();
    }

    // CEK LOGIN ADMIN
    private function cekLogin() {

        // Jika session admin tidak ada (belum login)
        if (!isset($_SESSION['admin'])) {

            // Redirect ke halaman login
            header("Location: ../VIEW/auth/v_login.php");

            // Hentikan program
            exit();
        }
    }

    // BAGIAN SISWA

    // Fungsi untuk menambah siswa
    public function tambahSiswa($data) {

        // Cek apakah NIS sudah ada di database
        if ($this->siswaModel->cekNis($data['nis'])) {

            // Simpan pesan error ke session
            $_SESSION['error'] = "NIS sudah terdaftar!";

            // Redirect ke form tambah siswa
            header("Location: ../VIEW/ADMIN/FormTambahSiswa.php");
            exit();
        }

        // Jika NIS belum ada, coba simpan ke database
        if ($this->siswaModel->tambahSiswa($data)) {

            // Jika berhasil
            $_SESSION['success'] = "Siswa berhasil ditambahkan!";
        } else {

            // Jika gagal
            $_SESSION['error'] = "Gagal menambahkan siswa!";
        }

        // Redirect ke halaman daftar siswa
        header("Location: ../VIEW/ADMIN/v_DaftarSiswa.php");
        exit();
    }

    // Fungsi untuk update data siswa
    public function updateSiswa($data) {

        // Panggil model untuk update
        if ($this->siswaModel->updateSiswa($data)) {

            $_SESSION['success'] = "Data siswa berhasil diupdate!";
        } else {

            $_SESSION['error'] = "Gagal update siswa!";
        }

        // Redirect kembali ke daftar siswa
        header("Location: ../VIEW/ADMIN/v_DaftarSiswa.php");
        exit();
    }

    // Fungsi untuk menghapus siswa
    public function hapusSiswa($nis) {

        // Hapus berdasarkan NIS
        if ($this->siswaModel->hapusSiswa($nis)) {

            $_SESSION['success'] = "Siswa berhasil dihapus!";
        } else {

            $_SESSION['error'] = "Gagal hapus siswa!";
        }

        // Redirect ke daftar siswa
        header("Location: ../VIEW/ADMIN/v_DaftarSiswa.php");
        exit();
    }

    // ASPIRASI

    // Fungsi untuk update status aspirasi
    public function updateStatusAspirasi($id, $status) {

        // Ambil ID admin dari session
        $idAdmin = $_SESSION['id_admin'];

        // Ambil data aspirasi berdasarkan ID
        $aspirasi = $this->aspirasiModel->getAspirasiById($id);

        // Jika data tidak ditemukan
        if (!$aspirasi) {
            $_SESSION['error'] = "Data tidak ditemukan!";
            header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
            exit();
        }

        // Simpan status lama
        $statusLama = $aspirasi['status'];

        // Mapping status ke format yang lebih jelas
        $statusMap = [
            'menunggu' => 'belum ditangani',
            'diproses' => 'dalam proses',
            'selesai' => 'selesai'
        ];

        // Validasi status baru dan lama
        if (!isset($statusMap[$status]) || !isset($statusMap[$statusLama])) {
            $_SESSION['error'] = "Status tidak valid!";
            header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
            exit();
        }

        // Update status di database
        if ($this->aspirasiModel->updateStatus($id, $status, $idAdmin)) {

            // Data histori perubahan status
            $historiData = [
                'id_aspirasi' => $id,
                'status_sebelum' => $statusMap[$statusLama],
                'status_sesudah' => $statusMap[$status]
            ];

            // Simpan histori perubahan
            $this->historiModel->tambahHistori($historiData);

            $_SESSION['success'] = "Status berhasil diupdate!";
        } else {

            $_SESSION['error'] = "Gagal update status!";
        }

        // Redirect ke halaman aspirasi
        header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
        exit();
    }

    // UMPAN BALIK

    // Tambah umpan balik
    public function tambahUmpanBalik($data) {

        // Tambahkan ID admin ke data
        $data['id_admin'] = $_SESSION['id_admin'];

        // Simpan ke database
        if ($this->umpanBalikModel->tambahUmpanBalik($data)) {

            $_SESSION['success'] = "Berhasil tambah umpan balik!";
        } else {

            $_SESSION['error'] = "Gagal tambah umpan balik!";
        }

        // Redirect ke halaman umpan balik sesuai ID aspirasi
        header("Location: ../VIEW/ADMIN/UmpanBalik.php?id=" . $data['id_aspirasi']);
        exit();
    }

    // Update umpan balik
    public function updateUmpanBalik($id, $data) {

        // Tambahkan ID admin
        $data['id_admin'] = $_SESSION['id_admin'];

        // Update ke database
        if ($this->umpanBalikModel->updateUmpanBalik($id, $data)) {

            $_SESSION['success'] = "Berhasil update umpan balik!";
        } else {

            $_SESSION['error'] = "Gagal update umpan balik!";
        }

        // Redirect kembali
        header("Location: ../VIEW/ADMIN/UmpanBalik.php?id=" . $data['id_aspirasi']);
        exit();
    }

// PROGRES 

    // Tambah progres + upload foto
    public function tambahProgres($data, $file) {

        // Default foto kosong
        $foto = null;

        // Cek apakah file diupload dan tidak error
        if (isset($file['foto_bukti']) && $file['foto_bukti']['error'] == 0) {

            // Format file yang diizinkan
            $allowed = ['jpg','jpeg','png'];

            // Ambil ekstensi file
            $ext = strtolower(pathinfo($file['foto_bukti']['name'], PATHINFO_EXTENSION));

            // Cek apakah ekstensi valid
            if (in_array($ext, $allowed)) {

                // Buat nama file unik
                $newName = time() . '_' . $file['foto_bukti']['name'];

                // Pindahkan file ke folder tujuan
                if (move_uploaded_file($file['foto_bukti']['tmp_name'], '../ASSETS/GAMBAR/' . $newName)) {

                    // Simpan nama file
                    $foto = $newName;
                }
            }
        }

        // Data yang akan disimpan
        $dataProgres = [
            'id_aspirasi' => $data['id_aspirasi'],
            'deskripsi_progres' => $data['deskripsi_progres'],
            'foto_bukti' => $foto
        ];

        // Simpan ke database
        if ($this->progresModel->tambahProgres($dataProgres)) {

            $_SESSION['success'] = "Progres berhasil!";
        } else {

            $_SESSION['error'] = "Gagal tambah progres!";
        }

        // Redirect
        header("Location: ../VIEW/ADMIN/v_DataAspirasi.php");
        exit();
    }
}

// HANDLE REQUEST (ROUTING SEDERHANA)

// Membuat object controller
$controller = new AdminController();

// Jika tombol tambah siswa ditekan
if (isset($_POST['tambah_siswa'])) {
    $controller->tambahSiswa($_POST);
}

// Jika update siswa
elseif (isset($_POST['update_siswa'])) {
    $controller->updateSiswa($_POST);
}

// Jika hapus siswa (via URL)
elseif (isset($_GET['hapus_siswa'])) {
    $controller->hapusSiswa($_GET['hapus_siswa']);
}

// Jika update status aspirasi
elseif (isset($_POST['update_status'])) {
    $controller->updateStatusAspirasi($_POST['id_aspirasi'], $_POST['status']);
}

// Jika tambah umpan balik
elseif (isset($_POST['tambah_umpanbalik'])) {
    $controller->tambahUmpanBalik($_POST);
}

// Jika update umpan balik
elseif (isset($_POST['update_umpanbalik'])) {
    $controller->updateUmpanBalik($_POST['id_UmpanBalik'], $_POST);
}

// Jika tambah progres
elseif (isset($_POST['tambah_progres'])) {
    $controller->tambahProgres($_POST, $_FILES);
}
?>