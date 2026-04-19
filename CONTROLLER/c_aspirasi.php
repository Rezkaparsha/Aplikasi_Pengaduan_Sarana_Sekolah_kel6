<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../MODEL/m_aspirasi.php';
require_once __DIR__ . '/../MODEL/m_umpanbalik.php';
require_once __DIR__ . '/../MODEL/m_histori.php';
require_once __DIR__ . '/../MODEL/m_progres.php';

class AspirasiController
{
    private $aspirasiModel;
    private $umpanBalikModel;
    private $historiModel;
    private $progresModel;

    public function __construct()
    {
        $this->aspirasiModel = new Aspirasi();
        $this->umpanBalikModel = new UmpanBalik();
        $this->historiModel = new HistoriAspirasi();
        $this->progresModel = new Progres();
    }

    // ==================== FILTER ASPIRASI ====================
    public function getAspirasiFiltered($filterType = '', $filterValue = '')
    {
        if (empty($filterValue)) {
            return $this->aspirasiModel->getAllAspirasi();
        }

        if ($filterType == 'tanggal') {
            return $this->aspirasiModel->filterByTanggal($filterValue);
        } elseif ($filterType == 'bulan') {
            return $this->aspirasiModel->filterByBulan($filterValue);
        } elseif ($filterType == 'siswa') {
            return $this->aspirasiModel->filterBySiswa($filterValue);
        } elseif ($filterType == 'lokasi') {
            return $this->aspirasiModel->filterByLokasi($filterValue);
        } elseif ($filterType == 'status') {
            return $this->aspirasiModel->filterByStatus($filterValue);
        } else {
            return $this->aspirasiModel->getAllAspirasi();
        }
    }

    // ==================== DETAIL ASPIRASI ====================
    public function getDetailAspirasi($id)
    {
        $aspirasi = $this->aspirasiModel->getAspirasiById($id);

        if ($aspirasi) {
            $aspirasi['umpan_balik'] = $this->umpanBalikModel->getUmpanBalikByAspirasi($id);
            $aspirasi['histori'] = $this->historiModel->getHistoriByAspirasi($id);
            $aspirasi['progres'] = $this->progresModel->getProgresByAspirasi($id);
        }

        return $aspirasi;
    }

    // ==================== STATISTIK ====================
    public function getStatistik($role = 'admin', $nis = 0)
    {
        if ($role == 'siswa') {
            return $this->aspirasiModel->getStatistikBySiswa($nis);
        } else {
            return $this->aspirasiModel->getStatistik();
        }
    }
}
?>