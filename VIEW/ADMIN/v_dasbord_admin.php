<?php
session_start();
require_once '../../CONTROLLER/c_aspirasi.php';
require_once '../../MODEL/m_admin.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

$aspirasiController = new AspirasiController();
$adminModel = new Admin();

// Get statistics
$statistik = $aspirasiController->getStatistik('admin');
$admins = $adminModel->getAllAdmin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Aplikasi Pengaduan</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="stylesheet" href="../../ASSETS/dasbordAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../../template/NavbarAdmin.php'; ?>

    <div class="container">
        <div class="dashboard-header">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Welcome Card -->
        <div class="welcome-card">
            <h2><i class="fas fa-hand-wave"></i> Selamat Datang di Panel Admin</h2>
            <p>Kelola aspirasi dan data siswa dengan mudah melalui dashboard ini.</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $statistik['total']; ?></h3>
                    <p>Total Aspirasi</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $statistik['menunggu']; ?></h3>
                    <p>Menunggu</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $statistik['diproses']; ?></h3>
                    <p>Diproses</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $statistik['selesai']; ?></h3>
                    <p>Selesai</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <div class="action-card">
                <i class="fas fa-users"></i>
                <h3>Data Siswa</h3>
                <p>Kelola data siswa yang terdaftar</p>
                <a href="v_DaftarSiswa.php" class="btn btn-primary">Lihat Data</a>
            </div>
            <div class="action-card">
                <i class="fas fa-clipboard-list"></i>
                <h3>Data Aspirasi</h3>
                <p>Lihat dan kelola semua aspirasi</p>
                <a href="v_DataAspirasi.php" class="btn btn-primary">Lihat Data</a>
            </div>
            <div class="action-card">
                <i class="fas fa-user-shield"></i>
                <h3>Tambah Admin</h3>
                <p>Tambahkan admin baru</p>
                <button onclick="openModal('modalTambahAdmin')" class="btn btn-success">Tambah Admin</button>
            </div>
        </div>

        <!-- Admin List -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-user-shield"></i> Daftar Admin</h2>
            </div>
            <div class="admin-list">
                <?php foreach ($admins as $admin): ?>
                <div class="admin-item">
                    <div class="admin-info">
                        <div class="admin-avatar">
                            <?php echo strtoupper(substr($admin['nama_lengkap'], 0, 1)); ?>
                        </div>
                        <div class="admin-details">
                            <h4><?php echo htmlspecialchars($admin['nama_lengkap']); ?></h4>
                            <p><?php echo htmlspecialchars($admin['username']); ?> • <?php echo htmlspecialchars($admin['email']); ?></p>
                        </div>
                    </div>
                    <?php if ($admin['id_admin'] != $_SESSION['id_admin']): ?>
                    <a href="../../CONTROLLER/AdminController.php?hapus_admin=<?php echo $admin['id_admin']; ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('Yakin ingin menghapus admin ini?')">
                        <i class="fas fa-trash"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Admin -->
    <div id="modalTambahAdmin" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Admin Baru</h2>
                <span class="modal-close" onclick="closeModal('modalTambahAdmin')">&times;</span>
            </div>
            <form action="../../CONTROLLER/AdminController.php" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <button type="submit" name="tambah_admin" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }
        
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
        
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }
    </script>
</body>
</html>
