<?php
session_start();
require_once '../../MODEL/m_siswa.php';

if (!isset($_SESSION['siswa'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

$siswaModel = new Siswa();
$siswa = $siswaModel->getSiswaByNis($_SESSION['nis']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa - Siswa Panel</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="website icon" href="../../ASSETS/GAMBAR/logoapk.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../../template/NavbarSiswa.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-user"></i> Profil Saya</h2>
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

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Update Profil -->
                <div class="card" style="background: #f8f9fa;">
                    <h3><i class="fas fa-edit"></i> Update Profil</h3><br>
                    <form action="../../CONTROLLER/SiswaController.php" method="POST">
                        <div class="form-group">
                            <label><i class="fas fa-id-card"></i> NIS</label>
                            <input type="text" value="<?php echo $siswa['nis']; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Nama Lengkap</label>
                            <input type="text" name="nama" value="<?php echo htmlspecialchars($siswa['nama']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-graduation-cap"></i> Kelas</label>
                            <input type="text" name="kelas" value="<?php echo htmlspecialchars($siswa['kelas']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($siswa['email']); ?>" required>
                        </div>
                        <button type="submit" name="update_profil" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Profil
                        </button>
                    </form>
                </div>

                <!-- Update Password -->
                <div class="card" style="background: #f8f9fa;">
                    <h3><i class="fas fa-lock"></i> Ubah Password</h3><br>
                    <form action="../../CONTROLLER/SiswaController.php" method="POST">
                        <div class="form-group">
                            <label><i class="fas fa-lock"></i> Password Lama</label>
                            <input type="password" name="password_lama" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-key"></i> Password Baru</label>
                            <input type="password" name="password_baru" required>
                        </div>
                        <button type="submit" name="update_password" class="btn btn-primary">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
