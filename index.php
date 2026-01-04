<?php
session_start();

include "config.php";
include "class/Database.php";
include "class/Form.php";

// ===== ROUTING =====
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/home/index';

// Hapus slash di awal dan akhir, lalu pecah menjadi array
$segments = explode('/', trim($path, '/'));

// Tentukan modul dan halaman
$mod = isset($segments[0]) ? $segments[0] : 'home';
$page = isset($segments[1]) ? $segments[1] : 'index';

// Halaman yang boleh diakses tanpa login
$public_pages = ['home', 'user'];

// CEK SESSION UNTUK HALAMAN TERPROTEKSI
if (!in_array($mod, $public_pages)) {
    if (!isset($_SESSION['is_login'])) {
        // Redirect ke login
        header('Location: /lab11_php_oop/index.php/user/login');
        exit();
    }
}

// Load template atau module
$file = "module/{$mod}/{$page}.php";

if (file_exists($file)) {
    // Jangan load header/footer untuk halaman login
    if ($mod == 'user' && $page == 'login') {
        include $file;
    } else {
        include "template/header.php";
        include $file;
        include "template/footer.php";
    }
} else {
    echo "Halaman tidak ditemukan.";
    echo "<br>Mencari file: " . $file;
}
?>