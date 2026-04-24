<?php

// Mengecek apakah session sudah aktif atau belum
// Jika belum aktif, maka session akan dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Memanggil file model yang dibutuhkan oleh controller
// require_once digunakan agar file hanya dipanggil 1 kali
require_once __DIR__ . '/../MODEL/m_aspirasi.php';     // Model data aspirasi
require_once __DIR__ . '/../MODEL/m_umpanbalik.php';   // Model data umpan balik
require_once __DIR__ . '/../MODEL/m_histori.php';      // Model data histori aspirasi
require_once __DIR__ . '/../MODEL/m_progres.php';      // Model data progres aspirasi

// Class controller untuk mengatur proses data aspirasi
class AspirasiController
{
    // Property private untuk menyimpan object model
    private $aspirasiModel;
    private $umpanBalikModel;
    private $historiModel;
    private $progresModel;

    // Constructor otomatis berjalan saat object dibuat
    public function __construct()
    {
        // Membuat object dari masing-masing model
        $this->aspirasiModel = new Aspirasi();
        $this->umpanBalikModel = new UmpanBalik();
        $this->historiModel = new HistoriAspirasi();
        $this->progresModel = new Progres();
    }


    // FUNCTION FILTER ASPIRASI
    //Mengambil data aspirasi berdasarkan jenis filter

    public function getAspirasiFiltered($filterType = '', $filterValue = '')
    {
        // Jika nilai filter kosong, tampilkan semua data
        if (empty($filterValue)) {
            return $this->aspirasiModel->getAllAspirasi();
        }

        // Filter berdasarkan tanggal
        if ($filterType == 'tanggal') {
            return $this->aspirasiModel->filterByTanggal($filterValue);

        // Filter berdasarkan bulan
        } elseif ($filterType == 'bulan') {
            return $this->aspirasiModel->filterByBulan($filterValue);

        // Filter berdasarkan nama siswa
        } elseif ($filterType == 'siswa') {
            return $this->aspirasiModel->filterBySiswa($filterValue);

        // Filter berdasarkan lokasi
        } elseif ($filterType == 'lokasi') {
            return $this->aspirasiModel->filterByLokasi($filterValue);

        // Filter berdasarkan status aspirasi
        } elseif ($filterType == 'status') {
            return $this->aspirasiModel->filterByStatus($filterValue);

        // Jika filter tidak dikenali, tampilkan semua data
        } else {
            return $this->aspirasiModel->getAllAspirasi();
        }
    }


    // FUNCTION DETAIL ASPIRASI
    // Mengambil detail lengkap 1 aspirasi berdasarkan ID

    public function getDetailAspirasi($id)
    {
        // Mengambil data utama aspirasi berdasarkan ID
        $aspirasi = $this->aspirasiModel->getAspirasiById($id);

        // Jika data ditemukan
        if ($aspirasi) {

            // Menambahkan data umpan balik ke dalam array aspirasi
            $aspirasi['umpan_balik'] =
                $this->umpanBalikModel->getUmpanBalikByAspirasi($id);

            // Menambahkan data histori perubahan aspirasi
            $aspirasi['histori'] =
                $this->historiModel->getHistoriByAspirasi($id);

            // Menambahkan data progres pengerjaan aspirasi
            $aspirasi['progres'] =
                $this->progresModel->getProgresByAspirasi($id);
        }

        // Mengembalikan hasil detail aspirasi
        return $aspirasi;
    }


    // FUNCTION STATISTIK
    // Mengambil data statistik dashboard
    // Admin melihat semua data
    // Siswa hanya melihat data miliknya sendiri

    public function getStatistik($role = 'admin', $nis = 0)
    {
        // Jika user adalah siswa
        if ($role == 'siswa') {

            // Ambil statistik berdasarkan NIS siswa
            return $this->aspirasiModel->getStatistikBySiswa($nis);

        } else {

            // Jika admin, ambil semua statistik
            return $this->aspirasiModel->getStatistik();
        }
    }
}

?>