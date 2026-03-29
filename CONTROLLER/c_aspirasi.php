<?php


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../MODEL/m_aspirasi.php';
require_once __DIR__ . '/../MODEL/m_umpanbalik.php';
require_once __DIR__ . '/../MODEL/m_histori.php';
require_once __DIR__ . '/../MODEL/m_progres.php';

/**
 * Class AspirasiController
 * Controller untuk mengelola data aspirasi (view/fetching)
 */
class AspirasiController
{
    private $aspirasiModel;
    private $umpanBalikModel;
    private $historiModel;
    private $progresModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->aspirasiModel = new Aspirasi();
        $this->umpanBalikModel = new UmpanBalik();
        $this->historiModel = new HistoriAspirasi();
        $this->progresModel = new Progres();
    }

    /**
     * Get aspirasi dengan filter
     * @param string $filterType
     * @param string $filterValue
     * @return array
     */
    
    public function getAspirasiFiltered($filterType = '', $filterValue = '')
    {
        if (empty($filterValue)) {
            return $this->aspirasiModel->getAllAspirasi();
        }

        switch ($filterType) {
            case 'tanggal':
                return $this->aspirasiModel->filterByTanggal($filterValue);
            case 'bulan':
                return $this->aspirasiModel->filterByBulan($filterValue);
            case 'siswa':
                return $this->aspirasiModel->filterBySiswa($filterValue);
            case 'lokasi':
                return $this->aspirasiModel->filterByLokasi($filterValue);
            case 'status':
                return $this->aspirasiModel->filterByStatus($filterValue);
            default:
                return $this->aspirasiModel->getAllAspirasi();
        }
    }

    /**
     * Get detail aspirasi lengkap dengan relasi
     * @param int $id
     * @return array
     */
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

    /**
     * Get statistik untuk dashboard
     * @param string $role
     * @param int $nis
     * @return array
     */
    public function getStatistik($role = 'admin', $nis = 0)
    {
        if ($role == 'siswa') {
            return $this->aspirasiModel->getStatistikBySiswa($nis);
        }
        return $this->aspirasiModel->getStatistik();
    }
}
