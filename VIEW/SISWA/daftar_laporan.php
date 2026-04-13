<?php
session_start();
require_once '../../MODEL/m_aspirasi.php';
require_once '../../MODEL/m_umpanbalik.php';

if (!isset($_SESSION['siswa'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

$aspirasiModel = new Aspirasi();
$umpanBalikModel = new UmpanBalik();
$aspirasiList = $aspirasiModel->getAllAspirasi();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laporan - Siswa Panel</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="website icon" href="../../ASSETS/GAMBAR/logoapk.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include '../../template/NavbarSiswa.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Daftar Laporan Semua Siswa</h2>
                <a href="v_form_aspirasi.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Kirim Baru
                </a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (empty($aspirasiList)): ?>
                <div class="empty-state" style="text-align: center; padding: 40px;">
                    <i class="fas fa-inbox" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                    <p style="color: #666;">Belum ada laporan. Kirim aspirasi pertama Anda!</p>
                    <a href="v_form_aspirasi.php" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i> Kirim Aspirasi
                    </a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Judul</th>
                                <th>Lokasi</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($aspirasiList as $aspirasi):
                                $umpanBalik = $umpanBalikModel->getUmpanBalikByAspirasi($aspirasi['id_aspirasi']);
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($aspirasi['tanggal_dikirim'])); ?></td>
                                    <td><?php echo htmlspecialchars($aspirasi['judul_laporan']); ?></td>
                                    <td><?php echo ucfirst($aspirasi['lokasi']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo explode(' ', $aspirasi['kategori_prioritas'])[0]; ?>">
                                            <?php echo $aspirasi['kategori_prioritas']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $aspirasi['status']; ?>">
                                            <?php echo ucfirst($aspirasi['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button onclick="openDetailModal(<?php echo $aspirasi['id_aspirasi']; ?>)" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if (
                                            $aspirasi['nis'] == $_SESSION['nis']
                                            && $aspirasi['status'] == 'menunggu'
                                        ): ?>
                                            <a href="../../CONTROLLER/SiswaController.php?hapus_aspirasi=<?php echo $aspirasi['id_aspirasi']; ?>"
                                                class="btn btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus aspirasi ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2>Detail Laporan</h2>
                <span class="modal-close" onclick="closeModal('detailModal')">&times;</span>
            </div>
            <div id="detailContent"></div>
        </div>
    </div>

    <script>
        function openDetailModal(id) {
            document.getElementById('detailModal').classList.add('active');
            fetch('DetailAspirasi.php?id=' + id)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('detailContent').innerHTML = data;
                });
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