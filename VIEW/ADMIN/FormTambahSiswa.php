<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/v_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - Admin Panel</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../../template/NavbarAdmin.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-user-plus"></i> Tambah Siswa Baru</h2>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="../../CONTROLLER/AdminController.php" method="POST">
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> NIS</label>
                    <input type="number" name="nis" required placeholder="Masukkan NIS">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Masukkan nama lengkap">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-graduation-cap"></i> Kelas</label>
                    <input type="text" name="kelas" required placeholder="Contoh: XII RPL 1">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" required placeholder="Masukkan email">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password">
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="tambah_siswa" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="v_DaftarSiswa.php" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
