<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Siswa - Aplikasi Pengaduan Sarana Sekolah</title>
    <!-- Menggunakan font Poppins agar lebih modern -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base & Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #0f172a; /* Dark background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden; 
            overflow-y: auto;
            padding: 40px 20px;
            color: #fff;
        }

        /* Animated Background Shapes */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 10s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
        }

        .shape-1 { background: rgba(99, 102, 241, 0.3); width: 400px; height: 400px; top: -150px; left: -100px; }
        .shape-2 { background: rgba(236, 72, 153, 0.2); width: 350px; height: 350px; bottom: -100px; right: -50px; animation-delay: -3s; }
        .shape-3 { background: rgba(6, 182, 212, 0.2); width: 300px; height: 300px; top: 40%; right: 20%; animation-duration: 12s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 50px) scale(1.1); }
        }

        /* Glassmorphism Container */
        .login-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUpFade 0.8s ease forwards;
            position: relative;
            z-index: 1;
        }

        @keyframes slideUpFade {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Logo Area (Fixing the White Background Issue) */
        .brand-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            background: #fff; /* White background only for the logo box */
            border-radius: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .brand-logo img {
            max-width: 100%;
            max-height: 100%;
          
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #fff;
        }

        .login-header p {
            color: #94a3b8;
            font-size: 13px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #cbd5e1;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .form-group input::placeholder {
            color: #475569;
        }

        /* Button Style from Image */
        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.5);
        }

        /* Alert Box */
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #94a3b8;
        }

        .login-footer a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    </style>
</head>
<body>

    <!-- Animasi Background -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <!-- Logo yang sudah diperbaiki -->
            <div class="brand-logo">
                <img src="../../ASSETS/GAMBAR/logoapk.jpeg" alt="Logo Sekolah">
            </div>
            <h1>Daftar Akun</h1>
            <p>Bergabunglah untuk sekolah yang lebih baik</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="../../CONTROLLER/c_auth.php" method="POST">
            <div class="form-group">
                <label>Nomor Induk Siswa</label>
                <div class="input-wrapper">
                    <i class="fas fa-id-card"></i>
                    <input type="number" name="nis" placeholder="Masukkan NIS" required>
                </div>
            </div>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nama" placeholder="Masukkan nama" required>
                </div>
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <div class="input-wrapper">
                    <i class="fas fa-graduation-cap"></i>
                    <input type="text" name="kelas" placeholder="Contoh: XII RPL 1" required>
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email aktif" required>
                </div>
            </div>
            <div class="form-group">
                <label>Kata Sandi</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Buat password" required>
                </div>
            </div>
            
            <button type="submit" name="register_siswa" class="btn-register">
                Daftar Sekarang <i class="fas fa-chevron-right"></i>
            </button>
        </form>

        <div class="login-footer">
            <p>Sudah punya akun? <a href="v_login.php">Masuk Sini</a></p>
        </div>
    </div>

</body>
</html>