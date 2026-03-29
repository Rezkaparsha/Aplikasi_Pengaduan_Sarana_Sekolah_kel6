<?php
session_start();
require_once '../../CONTROLLER/c_aspirasi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

$aspirasiController = new AspirasiController();

// Handle filter
$filterType = isset($_GET['filter']) ? $_GET['filter'] : '';
$filterValue = isset($_GET['value']) ? $_GET['value'] : '';
$aspirasiList = $aspirasiController->getAspirasiFiltered($filterType, $filterValue);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Aspirasi - Admin Panel</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../../template/NavbarAdmin.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-clipboard-list"></i> Data Aspirasi</h2>
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

            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <select name="filter" onchange="this.form.submit()">
                        <option value="">Semua Data</option>
                        <option value="status" <?php echo $filterType == 'status' ? 'selected' : ''; ?>>Status</option>
                        <option value="lokasi" <?php echo $filterType == 'lokasi' ? 'selected' : ''; ?>>Lokasi</option>
                        <option value="tanggal" <?php echo $filterType == 'tanggal' ? 'selected' : ''; ?>>Tanggal</option>
                        <option value="bulan" <?php echo $filterType == 'bulan' ? 'selected' : ''; ?>>Bulan</option>
                    </select>
                    
                    <?php if ($filterType == 'status'): ?>
                        <select name="value" onchange="this.form.submit()">
                            <option value="">Pilih Status</option>
                            <option value="menunggu" <?php echo $filterValue == 'menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                            <option value="diproses" <?php echo $filterValue == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                            <option value="selesai" <?php echo $filterValue == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    <?php elseif ($filterType == 'lokasi'): ?>
                        <select name="value" onchange="this.form.submit()">
                            <option value="">Pilih Lokasi</option>
                            <option value="kelas" <?php echo $filterValue == 'kelas' ? 'selected' : ''; ?>>Kelas</option>
                            <option value="toilet" <?php echo $filterValue == 'toilet' ? 'selected' : ''; ?>>Toilet</option>
                            <option value="mushola" <?php echo $filterValue == 'mushola' ? 'selected' : ''; ?>>Mushola</option>
                            <option value="lapangan" <?php echo $filterValue == 'lapangan' ? 'selected' : ''; ?>>Lapangan</option>
                            <option value="koridor" <?php echo $filterValue == 'koridor' ? 'selected' : ''; ?>>Koridor</option>
                            <option value="lab" <?php echo $filterValue == 'lab' ? 'selected' : ''; ?>>Lab</option>
                        </select>
                    <?php elseif ($filterType == 'tanggal'): ?>
                        <input type="date" name="value" value="<?php echo $filterValue; ?>" onchange="this.form.submit()">
                    <?php elseif ($filterType == 'bulan'): ?>
                        <input type="month" name="value" value="<?php echo $filterValue; ?>" onchange="this.form.submit()">
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
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
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($aspirasi['tanggal_dikirim'])); ?></td>
                            <td><?php echo htmlspecialchars($aspirasi['nama_siswa']); ?><br>
                                <small><?php echo htmlspecialchars($aspirasi['kelas']); ?></small>
                            </td>
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
                                <a href="UmpanBalik.php?id=<?php echo $aspirasi['id_aspirasi']; ?>" class="btn btn-warning">
                                    <i class="fas fa-comment"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail Aspirasi -->
    <div id="detailModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h2>Detail Aspirasi</h2>
                <span class="modal-close" onclick="closeModal('detailModal')">&times;</span>
            </div>
            <div id="detailContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(id) {
            document.getElementById('detailModal').classList.add('active');
            // Load detail content
            fetch('AmbilAspirasi.php?id=' + id)
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
