<?php
// Include config dan Database class
require_once('../../config.php');
require_once('../../class/Database.php');

// Buat instance Database
$db = new Database();
$conn = $db->getConnection();

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header("Location: index.php");
    exit();
}

// Query delete
$sql = "DELETE FROM data_barang WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    // Berhasil, redirect ke halaman index
    header("Location: index.php?status=deleted");
} else {
    // Gagal, redirect dengan error
    header("Location: index.php?status=error");
}

// Close connection
mysqli_close($conn);
exit();
?>