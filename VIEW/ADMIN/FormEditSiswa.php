<?php
session_start();
require_once '../../MODEL/m_siswa.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

if (!isset($_GET['nis'])) {
    header("Location: v_DaftarSiswa.php");
    exit();
}

$siswaModel = new Siswa();
$siswa = $siswaModel->getSiswaByNis($_GET['nis']);

if (!$siswa) {
    header("Location: v_DaftarSiswa.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa - Admin Panel</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../../template/NavbarAdmin.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-edit"></i> Edit Data Siswa</h2>
            </div>

            <form action="../../CONTROLLER/AdminController.php" method="POST">
                <input type="hidden" name="nis" value="<?php echo $siswa['nis']; ?>">
                
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> NIS</label>
                    <input type="number" value="<?php echo $siswa['nis']; ?>" disabled>
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
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="Masukkan password baru">
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="update_siswa" class="btn btn-success">
                        <i class="fas fa-save"></i> Update
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
