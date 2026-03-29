<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa - Aplikasi Pengaduan Sarana Sekolah</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <i class="fas fa-school"></i>
            <span>Siswa Panel</span>
        </div>
        <ul class="nav-menu">
            <li><a href="dasbord_siswa.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="v_form_aspirasi.php"><i class="fas fa-plus-circle"></i> Kirim Aspirasi</a></li>
            <li><a href="daftar_laporan.php"><i class="fas fa-list"></i> Laporan Semua Siswa</a></li>
            <li><a href="v_HistoryAspirasiSiswa.php"><i class="fas fa-history"></i> Histori</a></li>
            <li><a href="profil_siswa.php"><i class="fas fa-user"></i> Profil</a></li>
            <li><a href="../../CONTROLLER/c_auth.php?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="nav-user">
            <i class="fas fa-user-circle"></i>
            <span><?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Siswa'; ?></span>
        </div>
    </nav>
</body>
</html>
