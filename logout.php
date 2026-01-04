<?php
// File logout.php - HANYA untuk logout, tidak ada tampilan
session_start();

// Hapus semua session
$_SESSION = array();

// Destroy session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirect ke halaman login dengan pesan
header("Location: login.php?logout=success");
exit();
?>