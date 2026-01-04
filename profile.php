<?php
// Cek session sudah start atau belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include config dan Database class
require_once('../../config.php');
require_once('../../class/Database.php');

// Buat instance Database
$db = new Database();
$conn = $db->getConnection();

// Ambil data user dari database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

include('../../template/header.php');
?>

<div class="content">
    <h2>👤 Profile User</h2>
    
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <span><?php echo strtoupper(substr($user['nama'], 0, 2)); ?></span>
            </div>
            <h3><?php echo htmlspecialchars($user['nama']); ?></h3>
            <p class="username">@<?php echo htmlspecialchars($user['username']); ?></p>
        </div>
        
        <div class="profile-info">
            <div class="info-group">
                <label>👤 Username</label>
                <div class="info-value"><?php echo htmlspecialchars($user['username']); ?></div>
            </div>
            
            <div class="info-group">
                <label>✨ Nama Lengkap</label>
                <div class="info-value"><?php echo htmlspecialchars($user['nama']); ?></div>
            </div>
            
            <div class="info-group">
                <label>📧 Email</label>
                <div class="info-value">
                    <?php echo isset($user['email']) && !empty($user['email']) ? htmlspecialchars($user['email']) : '<em>Belum diisi</em>'; ?>
                </div>
            </div>
            
            <div class="info-group">
                <label>📅 Terdaftar Sejak</label>
                <div class="info-value">
                    <?php 
                    if (isset($user['created_at'])) {
                        echo date('d F Y, H:i', strtotime($user['created_at']));
                    } else {
                        echo '<em>-</em>';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="profile-actions">
            <a href="../artikel/index.php" class="btn btn-secondary">← Kembali ke Data Barang</a>
            <a href="logout.php" class="btn btn-logout">🚪 Logout</a>
        </div>
    </div>
</div>

<?php
include('../../template/footer.php');
mysqli_close($conn);
?>