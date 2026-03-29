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
    <title>Admin - Aplikasi Pengaduan Sarana Sekolah</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <i class="fas fa-school"></i>
            <span>Admin Panel</span>
        </div>
        <ul class="nav-menu">
            <li><a href="v_dasbord_admin.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="v_DaftarSiswa.php"><i class="fas fa-users"></i> Data Siswa</a></li>
            <li><a href="v_DataAspirasi.php"><i class="fas fa-clipboard-list"></i> Data Aspirasi</a></li>
            <li><a href="../../CONTROLLER/c_auth.php?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="nav-user">
            <i class="fas fa-user-circle"></i>
            <span><?php echo isset($_SESSION['nama_lengkap']) ? htmlspecialchars($_SESSION['nama_lengkap']) : 'Admin'; ?></span>
        </div>
    </nav>
</body>
</html>
