<?php
session_start();
require_once '../../CONTROLLER/c_aspirasi.php';
require_once '../../MODEL/m_umpanbalik.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: v_DataAspirasi.php");
    exit();
}

$aspirasiController = new AspirasiController();
$umpanBalikModel = new UmpanBalik();

$aspirasi = $aspirasiController->getDetailAspirasi($_GET['id']);
$umpanBalik = $umpanBalikModel->getUmpanBalikByAspirasi($_GET['id']);

if (!$aspirasi) {
    header("Location: v_DataAspirasi.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umpan Balik - Admin Panel</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../../template/NavbarAdmin.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-comment"></i> Umpan Balik Aspirasi</h2>
                <a href="v_DataAspirasi.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- Info Aspirasi -->
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h4>Informasi Aspirasi</h4>
                <p><strong>Judul:</strong> <?php echo htmlspecialchars($aspirasi['judul_laporan']); ?></p>
                <p><strong>Siswa:</strong> <?php echo htmlspecialchars($aspirasi['nama_siswa']); ?> (<?php echo htmlspecialchars($aspirasi['kelas']); ?>)</p>
                <p><strong>Status:</strong> 
                    <span class="badge badge-<?php echo $aspirasi['status']; ?>">
                        <?php echo ucfirst($aspirasi['status']); ?>
                    </span>
                </p>
            </div>

            <!-- Form Umpan Balik -->
            <div class="card" style="margin-bottom: 20px;">
                <h4><?php echo $umpanBalik ? 'Edit' : 'Tambah'; ?> Umpan Balik</h4>
                <form action="../../CONTROLLER/AdminController.php" method="POST">
                    <input type="hidden" name="id_aspirasi" value="<?php echo $aspirasi['id_aspirasi']; ?>">
                    <?php if ($umpanBalik): ?>
                    <input type="hidden" name="id_UmpanBalik" value="<?php echo $umpanBalik['id_UmpanBalik']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Isi Umpan Balik</label>
                        <textarea name="isi_UmpanBalik" rows="5" required style="width: 100%; padding: 10px;"><?php echo $umpanBalik ? htmlspecialchars($umpanBalik['isi_UmpanBalik']) : ''; ?></textarea>
                    </div>
                    <button type="submit" name="<?php echo $umpanBalik ? 'update_umpanbalik' : 'tambah_umpanbalik'; ?>" class="btn btn-success">
                        <i class="fas fa-save"></i> <?php echo $umpanBalik ? 'Update' : 'Simpan'; ?>
                    </button>
                </form>
            </div>

            <!-- Daftar Umpan Balik -->
            <?php if ($umpanBalik): ?>
            <div class="card">
                <h4>Umpan Balik Saat Ini</h4>
                <div style="background: #e3f2fd; padding: 15px; border-radius: 5px; margin-top: 10px;">
                    <p><strong>Dari:</strong> <?php echo htmlspecialchars($umpanBalik['nama_admin']); ?></p>
                    <p><strong>Tanggal:</strong> <?php echo date('d/m/Y', strtotime($umpanBalik['tanggal'])); ?></p>
                    <hr style="margin: 10px 0;">
                    <p><?php echo nl2br(htmlspecialchars($umpanBalik['isi_UmpanBalik'])); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
