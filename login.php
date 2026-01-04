<?php
// Start session hanya jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include config dan Database class
require_once('../../config.php');
require_once('../../class/Database.php');

// Buat instance Database
$db = new Database();
$conn = $db->getConnection();

// Variabel untuk message
$error = '';
$success = '';

// Cek jika ada parameter logout
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $success = 'Anda telah berhasil logout! 👋';
}

// Proses login ketika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Query untuk cek user
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password
        if ($password === $user['password']) {
            // Login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            
            // Redirect ke halaman data barang
            header("Location: ../artikel/index.php?login=success");
            exit();
        } else {
            $error = "Password salah! 🔒";
        }
    } else {
        $error = "Username tidak ditemukan! 👤";
    }
}

// Include header
include('../../template/header.php');
?>

<div class="content">
    <div class="login-container">
        <h2>🔐 Login</h2>
        <p class="login-subtitle">Silakan login untuk melanjutkan</p>
        
        <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="form-login">
            <div class="form-group">
                <label for="username">👤 Username:</label>
                <input type="text" name="username" id="username" required placeholder="Masukkan username" autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">🔒 Password:</label>
                <input type="password" name="password" id="password" required placeholder="Masukkan password">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-login">Login</button>
            </div>
        </form>
        
        <div class="login-footer">
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            <p><a href="../artikel/index.php">← Kembali ke Data Barang</a></p>
        </div>
        
        <div class="demo-account">
            <p><strong>Demo Account:</strong></p>
            <p>Username: <code>admin</code></p>
            <p>Password: <code>admin123</code></p>
        </div>
    </div>
</div>

<?php
// Include footer
include('../../template/footer.php');

// Close connection
mysqli_close($conn);
?>