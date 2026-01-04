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

// Proses form ketika di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga_beli = mysqli_real_escape_string($conn, $_POST['harga_beli']);
    $harga_jual = mysqli_real_escape_string($conn, $_POST['harga_jual']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    
    // Query update
    $sql = "UPDATE data_barang SET 
            kategori = '$kategori',
            nama = '$nama',
            harga_beli = '$harga_beli',
            harga_jual = '$harga_jual',
            stok = '$stok'
            WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        // Berhasil, redirect ke halaman index
        header("Location: index.php?status=updated");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Ambil data barang berdasarkan ID
$sql = "SELECT * FROM data_barang WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

// Include header
include('../../template/header.php');
?>

<div class="content">
    <h2>Ubah Barang</h2>
    
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
                <option value="Elektronik" <?php echo ($row['kategori'] === 'Elektronik') ? 'selected' : ''; ?>>Elektronik</option>
                <option value="Komputer" <?php echo ($row['kategori'] === 'Komputer') ? 'selected' : ''; ?>>Komputer</option>
                <option value="Aksesoris" <?php echo ($row['kategori'] === 'Aksesoris') ? 'selected' : ''; ?>>Aksesoris</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="nama">Nama Barang:</label>
            <input type="text" name="nama" id="nama" required value="<?php echo htmlspecialchars($row['nama']); ?>">
        </div>
        
        <div class="form-group">
            <label for="harga_beli">Harga Beli:</label>
            <input type="number" name="harga_beli" id="harga_beli" required value="<?php echo $row['harga_beli']; ?>" step="0.01">
        </div>
        
        <div class="form-group">
            <label for="harga_jual">Harga Jual:</label>
            <input type="number" name="harga_jual" id="harga_jual" required value="<?php echo $row['harga_jual']; ?>" step="0.01">
        </div>
        
        <div class="form-group">
            <label for="stok">Stok:</label>
            <input type="number" name="stok" id="stok" required value="<?php echo $row['stok']; ?>">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn-submit">Update</button>
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