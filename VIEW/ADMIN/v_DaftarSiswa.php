<?php
session_start();
require_once '../../MODEL/m_siswa.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

$siswaModel = new Siswa();
$siswaList = $siswaModel->getAllSiswa();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Admin Panel</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="website icon" href="../../ASSETS/GAMBAR/logoapk.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../../template/NavbarAdmin.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-users"></i> Data Siswa</h2>
                <a href="FormTambahSiswa.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Siswa
                </a>
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

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($siswaList as $siswa): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($siswa['nis']); ?></td>
                            <td><?php echo htmlspecialchars($siswa['nama']); ?></td>
                            <td><?php echo htmlspecialchars($siswa['kelas']); ?></td>
                            <td><?php echo htmlspecialchars($siswa['email']); ?></td>
                            <td>
                                <a href="FormEditSiswa.php?nis=<?php echo $siswa['nis']; ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="../../CONTROLLER/AdminController.php?hapus_siswa=<?php echo $siswa['nis']; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Yakin ingin menghapus siswa ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
