<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Pengaduan Sarana Sekolah</title>
    <!-- Font Poppins untuk kesan modern -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #0b0f1a; /* Dark base background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            color: #ffffff;
            position: relative;
        }

        /* Latar Belakang Bercahaya (Sesuai gambar referensi) */
        .bg-glow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.5;
            animation: pulse 8s infinite alternate;
        }

        .glow-1 { width: 400px; height: 400px; background: #3b82f6; top: -100px; left: -100px; }
        .glow-2 { width: 350px; height: 350px; background: #8b5cf6; bottom: -50px; right: -50px; animation-delay: -2s; }
        .glow-3 { width: 300px; height: 300px; background: #ec4899; bottom: 20%; left: 10%; animation-delay: -4s; }

        @keyframes pulse {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.1) translate(20px, 30px); }
        }

        /* Container Glassmorphism */
        .login-container {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            z-index: 10;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Logo Area - Disesuaikan agar pas dan terbaca */
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }

        .brand-logo {
            width: 120px; /* Ukuran diperbesar agar teks di logo terbaca */
            height: 120px;
            background: #fff;
            padding: 8px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        .brand-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain; /* Agar logo tidak terpotong atau gepeng */
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 5px;
        }

        .login-header p {
            color: #94a3b8;
            font-size: 13px;
        }

        /* Role Selector (Switch) */
        .role-selector {
            display: flex;
            background: rgba(0, 0, 0, 0.2);
            padding: 6px;
            border-radius: 14px;
            margin-bottom: 25px;
            gap: 5px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .role-selector button {
            flex: 1;
            padding: 10px;
            border: none;
            background: transparent;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-selector button.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* Forms */
        .hidden { display: none; }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #cbd5e1;
            font-size: 12px;
            font-weight: 500;
            margin-left: 4px;
        }

        .input-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-box i {
            position: absolute;
            left: 16px;
            color: #64748b;
            font-size: 14px;
        }

        .input-box input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            color: white;
            font-size: 14px;
            transition: all 0.3s;
        }

        .input-box input:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
            filter: brightness(1.1);
        }

        /* Alerts */
        .alert {
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            border-left: 4px solid;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            border-color: #22c55e;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border-color: #ef4444;
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: #94a3b8;
            font-size: 13px;
        }

        .login-footer a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Glow Background -->
    <div class="bg-glow">
        <div class="glow glow-1"></div>
        <div class="glow glow-2"></div>
        <div class="glow glow-3"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="brand-logo-container">
                <div class="brand-logo">
                    <!-- Ukuran logo sudah disesuaikan agar pas -->
                    <img src="../../ASSETS/GAMBAR/logoapk.jpeg" alt="Logo Sekolah">
                </div>
            </div>
            <h1>Selamat Datang</h1>
            <p>Portal Pengaduan Sarana Sekolah</p>
        </div>

        <!-- PHP Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> &nbsp;
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i> &nbsp;
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Role Selector -->
        <div class="role-selector">
            <button type="button" id="btn-siswa" class="active" onclick="showForm('siswa')">Siswa</button>
            <button type="button" id="btn-admin" onclick="showForm('admin')">Admin</button>
        </div>

        <!-- Form Login Siswa -->
        <form id="form-siswa" action="../../CONTROLLER/c_auth.php" method="POST">
            <div class="form-group">
                <label>Nomor Induk Siswa</label>
                <div class="input-box">
                    <i class="fas fa-id-card"></i>
                    <input type="number" name="nis" placeholder="Masukkan NIS Anda" required>
                </div>
            </div>
            <div class="form-group">
                <label>Kata Sandi</label>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>
            <button type="submit" name="login_siswa" class="btn-login">
                Mulai Sesi Siswa <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <!-- Form Login Admin -->
        <form id="form-admin" action="../../CONTROLLER/c_auth.php" method="POST" class="hidden">
            <div class="form-group">
                <label>Username Admin</label>
                <div class="input-box">
                    <i class="fas fa-user-shield"></i>
                    <input type="text" name="username" placeholder="Masukkan username" required>
                </div>
            </div>
            <div class="form-group">
                <label>Kata Sandi</label>
                <div class="input-box">
                    <i class="fas fa-key"></i>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>
            <button type="submit" name="login_admin" class="btn-login" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                Akses Panel Admin <i class="fas fa-shield-alt"></i>
            </button>
        </form>

        <div class="login-footer">
            <p>Belum punya akun? <a href="v_register.php">Daftar sebagai Siswa</a></p>
        </div>
    </div>

    <script>
        function showForm(role) {
            const formSiswa = document.getElementById('form-siswa');
            const formAdmin = document.getElementById('form-admin');
            const btnSiswa = document.getElementById('btn-siswa');
            const btnAdmin = document.getElementById('btn-admin');

            if (role === 'siswa') {
                formSiswa.classList.remove('hidden');
                formAdmin.classList.add('hidden');
                btnSiswa.classList.add('active');
                btnAdmin.classList.remove('active');
            } else {
                formSiswa.classList.add('hidden');
                formAdmin.classList.remove('hidden');
                btnSiswa.classList.remove('active');
                btnAdmin.classList.add('active');
            }
        }
    </script>
</body>
</html>