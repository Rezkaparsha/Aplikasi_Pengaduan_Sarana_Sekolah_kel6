<?php
session_start();
require_once '../../MODEL/m_aspirasi.php';

if (!isset($_SESSION['siswa'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

$aspirasiModel = new Aspirasi();
$aspirasiList = $aspirasiModel->getAspirasiByNis($_SESSION['nis']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>History Laporan Saya</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #333; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        
        h2 { margin-bottom: 25px; font-weight: 700; color: #2d3436; border-left: 5px solid #007bff; padding-left: 15px; }

        table { width: 100%; border-collapse: collapse; border-radius: 8px; overflow: hidden; }
        th { background-color: #f1f3f5; padding: 15px; text-align: left; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 15px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }

        /* Status Badges */
        .badge { padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; }
        .status-diproses { background: #e3f2fd; color: #007bff; }
        .status-selesai { background: #d1e7dd; color: #198754; }

        .btn-icon { background: #f0f2f5; border: none; color: #007bff; padding: 10px; border-radius: 8px; cursor: pointer; transition: all 0.2s; }
        .btn-icon:hover { background: #007bff; color: #fff; transform: translateY(-2px); }

        /* Modal Structure */
        .modal-overlay { 
            display: none; position: fixed; z-index: 1000; left: 0; top: 0; 
            width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(5px);
        }
        .modal-box { 
            background: #fff; margin: 5vh auto; width: 90%; max-width: 650px; 
            border-radius: 15px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: modalSlide 0.3s ease-out;
        }
        @keyframes modalSlide { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .modal-header { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 25px; max-height: 75vh; overflow-y: auto; }
        .close-x { font-size: 24px; cursor: pointer; color: #aaa; transition: 0.2s; }
        .close-x:hover { color: #ff4757; }
    </style>
</head>
<body>

<?php include '../../template/NavbarSiswa.php'; ?>

<div class="container">
    <h2>History Laporan Saya</h2>

    <?php if (empty($aspirasiList)): ?>
        <p style="text-align:center; padding: 20px; color: #888;">Belum ada laporan yang dikirim.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Judul Laporan</th>
                    <th>Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($aspirasiList as $aspirasi): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= date('d/m/Y', strtotime($aspirasi['tanggal_dikirim'])); ?></td>
                    <td><strong><?= htmlspecialchars($aspirasi['judul_laporan']); ?></strong></td>
                    <td><span class="badge status-<?= strtolower($aspirasi['status']); ?>"><?= ucfirst($aspirasi['status']); ?></span></td>
                    <td style="text-align: center;">
                        <button class="btn-icon" onclick="openDetail(<?= $aspirasi['id_aspirasi']; ?>)">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div id="modalDetail" class="modal-overlay" onclick="if(event.target == this) closeModal()">
    <div class="modal-box">
        <div class="modal-header">
            <h3 style="margin:0">Detail Laporan</h3>
            <span class="close-x" onclick="closeModal()">&times;</span>
        </div>
        <div id="modalContent" class="modal-body">
            </div>
    </div>
</div>

<script>
function openDetail(id) {
    const modal = document.getElementById('modalDetail');
    const content = document.getElementById('modalContent');
    
    modal.style.display = 'block';
    content.innerHTML = '<div style="text-align:center; padding: 20px;"><i class="fas fa-circle-notch fa-spin fa-2x"></i><p>Memuat...</p></div>';

    // Mengambil konten dari DetailAspirasi.php
    fetch('DetailAspirasi.php?id=' + id)
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
        })
        .catch(err => {
            content.innerHTML = '<p style="color:red">Gagal memuat detail laporan.</p>';
        });
}

function closeModal() {
    document.getElementById('modalDetail').style.display = 'none';
}
</script>

</body>
</html>