<?php
session_start();
require_once '../../CONTROLLER/c_aspirasi.php';
require_once '../../MODEL/m_aspirasi.php';

if (!isset($_SESSION['siswa'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

$aspirasiController = new AspirasiController();
$aspirasiModel = new Aspirasi();

$statistik = $aspirasiController->getStatistik('siswa', $_SESSION['nis']);
$recentAspirasi = $aspirasiModel->getAspirasiByNis($_SESSION['nis']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Aplikasi Pengaduan</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="stylesheet" href="../../ASSETS/dasbordSiswa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../../template/NavbarSiswa.php'; ?>

    <div class="container siswa-dashboard">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-text">
                <h1>Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h1>
                <p>Selamat datang di Aplikasi Pengaduan Sarana Sekolah</p>
            </div>
            <div class="welcome-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="status-cards">
            <div class="status-card">
                <div class="status-icon total">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="status-info">
                    <h3><?php echo $statistik['total']; ?></h3>
                    <p>Total Laporan</p>
                </div>
            </div>
            <div class="status-card">
                <div class="status-icon waiting">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="status-info">
                    <h3><?php echo $statistik['menunggu']; ?></h3>
                    <p>Menunggu</p>
                </div>
            </div>
            <div class="status-card">
                <div class="status-icon process">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="status-info">
                    <h3><?php echo $statistik['diproses']; ?></h3>
                    <p>Diproses</p>
                </div>
            </div>
            <div class="status-card">
                <div class="status-icon done">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="status-info">
                    <h3><?php echo $statistik['selesai']; ?></h3>
                    <p>Selesai</p>
                </div>
            </div>
        </div>

        <!-- Quick Menu -->
        <div class="quick-menu">
            <a href="v_form_aspirasi.php" class="menu-item">
                <i class="fas fa-plus-circle"></i>
                <h3>Kirim Aspirasi</h3>
                <p>Sampaikan keluhan atau masukan Anda</p>
            </a>
            <a href="daftar_laporan.php" class="menu-item">
                <i class="fas fa-list"></i>
                <h3>Daftar Laporan</h3>
                <p>Lihat semua laporan yang telah dikirim</p>
            </a>
            <a href="v_HistoryAspirasiSiswa.php" class="menu-item">
                <i class="fas fa-history"></i>
                <h3>Histori</h3>
                <p>Lihat riwayat perubahan status laporan</p>
            </a>
            <a href="profil_siswa.php" class="menu-item">
                <i class="fas fa-user-cog"></i>
                <h3>Profil</h3>
                <p>Kelola informasi akun Anda</p>
            </a>
        </div>

        <!-- Recent Reports -->
        <div class="recent-reports">
            <h2><i class="fas fa-clock"></i> Laporan Terbaru</h2>
            
            <?php if (empty($recentAspirasi)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada laporan. Kirim aspirasi pertama Anda!</p>
            </div>
            <?php else: ?>
                <?php 
                $limitedAspirasi = array_slice($recentAspirasi, 0, 5);
                foreach ($limitedAspirasi as $aspirasi): 
                ?>
                <div class="report-item">
                    <div class="report-info">
                        <h4><?php echo htmlspecialchars($aspirasi['judul_laporan']); ?></h4>
                        <p><?php echo date('d/m/Y', strtotime($aspirasi['tanggal_dikirim'])); ?> • <?php echo ucfirst($aspirasi['lokasi']); ?></p>
                    </div>
                    <div class="report-status">
                        <span class="badge badge-<?php echo $aspirasi['status']; ?>">
                            <?php echo ucfirst($aspirasi['status']); ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
