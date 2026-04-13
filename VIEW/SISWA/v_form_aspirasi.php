<?php
session_start();

if (!isset($_SESSION['siswa'])) {
    header("Location: ../auth/v_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Aspirasi - Siswa Panel</title>
    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="website icon" href="../../ASSETS/GAMBAR/logoapk.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../../template/NavbarSiswa.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-plus-circle"></i> Kirim Aspirasi Baru</h2>
            </div>

            <form action="../../CONTROLLER/SiswaController.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Judul Laporan</label>
                    <input type="text" name="judul_laporan" placeholder="Masukkan judul laporan" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Lokasi</label>
                    <select name="lokasi" required>
                        <option value="">Pilih Lokasi</option>
                        <option value="kelas">Kelas</option>
                        <option value="toilet">Toilet</option>
                        <option value="mushola">Mushola</option>
                        <option value="lapangan">Lapangan</option>
                        <option value="koridor">Koridor</option>
                        <option value="lab">Lab</option>
                    </select>
                </div>

                <div class="form-group">
                    <p>High Priority (Prioritas Tinggi) = menyangkut kemanan</p><br>
                    <p>Medium Priority (Prioritas Sedang) = menyangkut kenyaman</p><br>
                    <p>Low Priority (Prioritas Rendah) = menyangkut hal lainnya</p><br>
                    <label><i class="fas fa-flag"></i> Prioritas</label>
                    <select name="kategori_prioritas" required>
                        <option value="high priority">High Priority (Prioritas Tinggi)</option>
                        <option value="medium priority" selected>Medium Priority (Prioritas Sedang)</option>
                        <option value="low priority">Low Priority (Prioritas Rendah)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Keterangan</label>
                    <textarea name="keterangan" rows="5" placeholder="Jelaskan detail aspirasi atau keluhan Anda..." required></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-image"></i> Foto/Gambar (Opsional)</label>
                    <input type="file" name="foto_gambar" accept="image/*" onchange="previewImage(this)">
                    <small>Format: JPG, JPEG, PNG (Max 2MB)</small>
                    <img id="preview" class="img-preview" style="display: none;">
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="kirim_aspirasi" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Kirim Aspirasi
                    </button>
                    <a href="dasbord_siswa.php" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
