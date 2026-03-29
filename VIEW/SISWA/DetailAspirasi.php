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
    <h4>Informasi Laporan</h4>
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
    <p><strong>Tanggal:</strong> <?php echo date('d/m/Y', strtotime($aspirasi['tanggal_dikirim'])); ?></p>
</div>

<div style="margin-bottom: 20px;">
    <h4>Keterangan</h4>
    <p style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
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

<?php if ($aspirasi['umpan_balik']): ?>
<div style="margin-bottom: 20px;">
    <h4>Umpan Balik dari Admin</h4>
    <div style="background: #e3f2fd; padding: 15px; border-radius: 5px;">
        <p><strong>Dari:</strong> <?php echo htmlspecialchars($aspirasi['umpan_balik']['nama_admin']); ?></p>
        <p><strong>Tanggal:</strong> <?php echo date('d/m/Y', strtotime($aspirasi['umpan_balik']['tanggal'])); ?></p>
        <hr style="margin: 10px 0;">
        <p><?php echo nl2br(htmlspecialchars($aspirasi['umpan_balik']['isi_UmpanBalik'])); ?></p>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($aspirasi['progres'])): ?>
<div style="margin-bottom: 20px;">
    <h4>Progres Perbaikan</h4>
    <?php foreach ($aspirasi['progres'] as $progres): ?>
    <div style="padding: 15px; background: #f0f0f0; border-radius: 5px; margin-bottom: 10px;">
        <p><strong><?php echo date('d/m/Y', strtotime($progres['tanggal'])); ?></strong></p>
        <p><?php echo nl2br(htmlspecialchars($progres['deskripsi_progres'])); ?></p>
        <?php if ($progres['foto_bukti']): ?>
        <img src="../../ASSETS/GAMBAR/<?php echo $progres['foto_bukti']; ?>" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

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
