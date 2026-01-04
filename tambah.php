<?php
// Include config dan Database class
require_once('../../config.php');
require_once('../../class/Database.php');

// Buat instance Database
$db = new Database();
$conn = $db->getConnection();

// Proses form ketika di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga_beli = mysqli_real_escape_string($conn, $_POST['harga_beli']);
    $harga_jual = mysqli_real_escape_string($conn, $_POST['harga_jual']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    
    // Query insert
    $sql = "INSERT INTO data_barang (kategori, nama, harga_beli, harga_jual, stok) 
            VALUES ('$kategori', '$nama', '$harga_beli', '$harga_jual', '$stok')";
    
    if (mysqli_query($conn, $sql)) {
        // Berhasil, redirect ke halaman index
        header("Location: index.php?status=success");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Include header
include('../../template/header.php');
?>

<div class="content">
    <h2>Tambah Barang</h2>
    
    <a href="index.php" class="btn-kembali">← Kembali ke Daftar Barang</a>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" action="" class="form-barang">
        <div class="form-group">
            <label for="kategori">Kategori:</label>
            <select name="kategori" id="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Komputer">Komputer</option>
                <option value="Aksesoris">Aksesoris</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="nama">Nama Barang:</label>
            <input type="text" name="nama" id="nama" required placeholder="Masukkan nama barang">
        </div>
        
        <div class="form-group">
            <label for="harga_beli">Harga Beli:</label>
            <input type="number" name="harga_beli" id="harga_beli" required placeholder="0" step="0.01">
        </div>
        
        <div class="form-group">
            <label for="harga_jual">Harga Jual:</label>
            <input type="number" name="harga_jual" id="harga_jual" required placeholder="0" step="0.01">
        </div>
        
        <div class="form-group">
            <label for="stok">Stok:</label>
            <input type="number" name="stok" id="stok" required placeholder="0">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn-submit">Simpan</button>
            <a href="index.php" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

<?php
// Include footer
include('../../template/footer.php');

// Close connection
mysqli_close($conn);
?>