<?php
session_start();
require_once '../../MODEL/m_histori.php';

if (!isset($_SESSION['siswa'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

$historiModel = new HistoriAspirasi();
$historiList = $historiModel->getHistoriBySiswa($_SESSION['nis']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Aspirasi - Siswa Panel</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../../template/NavbarSiswa.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-history"></i> Histori Aspirasi</h2>
            </div>

            <?php if (empty($historiList)): ?>
            <div class="empty-state" style="text-align: center; padding: 40px;">
                <i class="fas fa-history" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                <p style="color: #666;">Belum ada histori perubahan status.</p>
            </div>
            <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Judul Laporan</th>
                            <th>Status Sebelum</th>
                            <th>Status Sesudah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($historiList as $histori): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($histori['tanggal_perubahan'])); ?></td>
                            <td><?php echo htmlspecialchars($histori['judul_laporan']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $histori['status_sebelum'] == 'belum ditangani' ? 'menunggu' : ($histori['status_sebelum'] == 'dalam proses' ? 'diproses' : 'selesai'); ?>">
                                    <?php echo $histori['status_sebelum']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $histori['status_sesudah'] == 'belum ditangani' ? 'menunggu' : ($histori['status_sesudah'] == 'dalam proses' ? 'diproses' : 'selesai'); ?>">
                                    <?php echo $histori['status_sesudah']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
