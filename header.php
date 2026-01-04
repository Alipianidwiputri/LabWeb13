<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang - Lab11 PHP OOP</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <!-- Header Top -->
    <header class="top-header">
        <div class="header-content">
            <h1>💕 Sistem Manajemen Barang</h1>
            <div class="user-info">
                <?php
                // Cek apakah user sudah login
                if (isset($_SESSION['nama'])) {
                    echo '<span>👋 ' . htmlspecialchars($_SESSION['nama']) . '</span>';
                } else {
                    echo '<span>Alipiani Dwi Putri</span>';
                }
                ?>
            </div>
        </div>
    </header>

    <!-- Layout dengan Sidebar -->
    <div class="main-layout">
        <!-- Sidebar Kiri -->
        <aside class="sidebar">
            <div class="sidebar-menu">
                <h3>Menu Utama</h3>
                <ul>
                    <li>
                        <a href="../artikel/index.php" class="menu-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['PHP_SELF'], '/artikel/') !== false) ? 'active' : ''; ?>">
                            <span class="icon">📦</span>
                            <span>Data Barang</span>
                        </a>
                    </li>
                    <li>
                        <a href="../artikel/tambah.php" class="menu-link <?php echo (basename($_SERVER['PHP_SELF']) == 'tambah.php') ? 'active' : ''; ?>">
                            <span class="icon">➕</span>
                            <span>Tambah Barang</span>
                        </a>
                    </li>
                </ul>
                
                <h3 class="menu-title-secondary">Akun</h3>
                <ul>
                    <?php if (isset($_SESSION['username'])): ?>
                        <!-- Jika sudah login -->
                        <li>
                            <a href="../user/profile.php" class="menu-link">
                                <span class="icon">👤</span>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="../user/logout.php" class="menu-link">
                                <span class="icon">🚪</span>
                                <span>Logout</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Jika belum login -->
                        <li>
                            <a href="../user/login.php" class="menu-link <?php echo (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : ''; ?>">
                                <span class="icon">🔐</span>
                                <span>Login</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </aside>

        <!-- Main Content Area (Kanan) -->
        <main class="main-content">