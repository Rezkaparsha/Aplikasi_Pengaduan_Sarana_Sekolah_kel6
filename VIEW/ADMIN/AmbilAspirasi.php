<?php
session_start();
require_once '../../CONTROLLER/c_aspirasi.php';

if (!isset($_GET['id'])) {
    echo 'ID tidak valid';
    exit();
}

$aspirasiController = new AspirasiController();
$aspirasi = $aspirasiController->getDetailAspirasi($_GET['id']);

if (!$aspirasi) {
    echo 'Data tidak ditemukan';
    exit();
}
?>

<div style="margin-bottom: 20px;">
    <h4>Informasi Pengirim</h4>
    <p><strong>Nama:</strong> <?php echo htmlspecialchars($aspirasi['nama_siswa']); ?></p>
    <p><strong>Kelas:</strong> <?php echo htmlspecialchars($aspirasi['kelas']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($aspirasi['email']); ?></p>
    <p><strong>Tanggal:</strong> <?php echo date('d/m/Y', strtotime($aspirasi['tanggal_dikirim'])); ?></p>
</div>

<div style="margin-bottom: 20px;">
    <h4>Detail Aspirasi</h4>
    <p><strong>Judul:</strong> <?php echo htmlspecialchars($aspirasi['judul_laporan']); ?></p>
    <p><strong>Lokasi:</strong> <?php echo ucfirst($aspirasi['lokasi']); ?></p>
    <p><strong>Prioritas:</strong> 
        <span class="badge badge-<?php echo explode(' ', $aspirasi['kategori_prioritas'])[0]; ?>">
            <?php echo $aspirasi['kategori_prioritas']; ?>
        </span>
    </p>
    <p><strong>Status:</strong> 
        <span class="badge badge-<?php echo $aspirasi['status']; ?>">
            <?php echo ucfirst($aspirasi['status']); ?>
        </span>
    </p>
    <p><strong>Keterangan:</strong></p>
    <p style="background: #f5f5f5; padding: 10px; border-radius: 5px;">
        <?php echo nl2br(htmlspecialchars($aspirasi['keterangan'])); ?>
    </p>
</div>

<?php if ($aspirasi['foto_gambar']): ?>
<div style="margin-bottom: 20px;">
    <h4>Foto/Gambar</h4>
    <img src="../../ASSETS/GAMBAR/<?php echo $aspirasi['foto_gambar']; ?>" 
         style="max-width: 100%; border-radius: 5px;" alt="Foto Aspirasi">
</div>
<?php endif; ?>

<!-- Update Status Form -->
<div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
    <h4>Update Status</h4>
    <form action="../../CONTROLLER/AdminController.php" method="POST" style="display: flex; gap: 10px; margin-top: 10px;">
        <input type="hidden" name="id_aspirasi" value="<?php echo $aspirasi['id_aspirasi']; ?>">
        <select name="status" class="form-control" style="flex: 1; padding: 10px;">
            <option value="menunggu" <?php echo $aspirasi['status'] == 'menunggu' ? 'selected' : ''; ?>>Menunggu</option>
            <option value="diproses" <?php echo $aspirasi['status'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
            <option value="selesai" <?php echo $aspirasi['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
        </select>
        <button type="submit" name="update_status" class="btn btn-primary">Update</button>
    </form>
</div>

<!-- Update Prioritas Form -->
<div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
    <h4>Update Prioritas</h4>
    <form action="../../CONTROLLER/AdminController.php" method="POST" style="display: flex; gap: 10px; margin-top: 10px;">
        <input type="hidden" name="id_aspirasi" value="<?php echo $aspirasi['id_aspirasi']; ?>">
        <select name="kategori_prioritas" class="form-control" style="flex: 1; padding: 10px;">
            <option value="high priority" <?php echo $aspirasi['kategori_prioritas'] == 'high priority' ? 'selected' : ''; ?>>High Priority</option>
            <option value="medium priority" <?php echo $aspirasi['kategori_prioritas'] == 'medium priority' ? 'selected' : ''; ?>>Medium Priority</option>
            <option value="low priority" <?php echo $aspirasi['kategori_prioritas'] == 'low priority' ? 'selected' : ''; ?>>Low Priority</option>
        </select>
        <button type="submit" name="update_prioritas" class="btn btn-warning">Update</button>
    </form>
</div>

<!-- Tambah Progres -->
<div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
    <h4>Tambah Progres</h4>
    <form action="../../CONTROLLER/AdminController.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_aspirasi" value="<?php echo $aspirasi['id_aspirasi']; ?>">
        <div class="form-group">
            <textarea name="deskripsi_progres" placeholder="Deskripsi progres..." required style="width: 100%; padding: 10px;"></textarea>
        </div>
        <div class="form-group">
            <input type="file" name="foto_bukti" accept="image/*">
        </div>
        <button type="submit" name="tambah_progres" class="btn btn-success">Tambah Progres</button>
    </form>
</div>

<!-- Progres List -->
<?php if (!empty($aspirasi['progres'])): ?>
<div style="margin-bottom: 20px;">
    <h4>Riwayat Progres</h4>
    <?php foreach ($aspirasi['progres'] as $progres): ?>
    <div style="padding: 10px; background: #f0f0f0; border-radius: 5px; margin-bottom: 10px;">
        <p><strong><?php echo date('d/m/Y', strtotime($progres['tanggal'])); ?></strong></p>
        <p><?php echo nl2br(htmlspecialchars($progres['deskripsi_progres'])); ?></p>
        <?php if ($progres['foto_bukti']): ?>
        <img src="../../ASSETS/GAMBAR/<?php echo $progres['foto_bukti']; ?>" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Histori -->
<?php if (!empty($aspirasi['histori'])): ?>
<div style="margin-bottom: 20px;">
    <h4>Histori Perubahan Status</h4>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Status Sebelum</th>
                <th>Status Sesudah</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aspirasi['histori'] as $histori): ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($histori['tanggal_perubahan'])); ?></td>
                <td><?php echo $histori['status_sebelum']; ?></td>
                <td><?php echo $histori['status_sesudah']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
