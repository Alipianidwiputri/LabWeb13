# LabWeb13

 - **Nama    : Alipiani Dwi Putri**
 - **Nim     : 312410691**
 - **Matkul  : Pemrograman Web**
 - **Dosen   : Agung Nugroho S.Kom,.M.Kom**

# *1. Pengertian Pagination*
Pagination adalah teknik untuk membagi data yang banyak menjadi beberapa halaman agar lebih mudah dibaca dan diakses. Tujuannya adalah:
 - Membatasi jumlah data per halaman
 - Meningkatkan kecepatan loading
 - Memudahkan navigasi data

# *2. Prinsip Dasar Pagination di MySQL*
Menggunakan dua klausa SQL:
 - **LIMIT**: Membatasi jumlah data yang ditampilkan
 - **OFFSET:** Menentukan posisi awal pengambilan data
   Conntoh Query:
```sql
-- Ambil 10 data pertama
SELECT * FROM table_barang LIMIT 10;

-- Ambil data ke-11 sampai ke-20
SELECT * FROM table_barang LIMIT 10 OFFSET 10;
-- atau
SELECT * FROM table_barang LIMIT 10, 20;
```

# *3. Logika Perhitungan Halaman*
Langkah-langkah menentukan jumlah halaman:
1. Hitung total data: `SELECT COUNT(*) FROM table_barang`
2. Tentukan jumlah data per halaman (misal: 10)
3. Hitung jumlah halaman: `total_data ÷ data_per_halaman`
4. Bulatkan ke atas jika hasilnya desimal
   **Contoh**
    - Total data: 30
    - Data per halaman: 10
    - Jumlah halaman: `30 ÷ 10 = 3`

# *4. Implementasi di PHP (index.php)*
**Bagian 1: Menghitung Jumlah Halaman**
```php
$sql_count = "SELECT COUNT(*) FROM data_barang";
$result_count = mysqli_query($conn, $sql_count);
$r_data = mysqli_fetch_row($result_count);
$count = $r_data[0]; // Total data

$per_page = 1; // Data per halaman (bisa diubah)
$num_page = ceil($count / $per_page); // Jumlah halaman
```

**Bagian 2: Menentukan Halaman Aktif**
```php
if (isset($_GET['page'])) {
    $page = $_GET['page']; // Halaman dari URL
    $offset = ($page - 1) * $per_page; // Hitung offset
} else {
    $offset = 0; // Default: halaman pertama
    $page = 1;
}

$sql .= " LIMIT {$offset}, {$limit}"; // Tambah LIMIT ke query
```

# *5. Membuat Tampilan Pagination*
**Tombol Nomor Halaman**
``php
<ul class="pagination">
    <?php for ($i = 1; $i <= $num_page; $i++): ?>
        <?php 
        $link = "?page={$i}";
        if (!empty($q)) $link .= "&q={$q}"; // Pertahankan parameter pencarian
        $class = ($page == $i ? 'active' : ''); // Tandai halaman aktif
        ?>
        <li><a class="<?= $class ?>" href="<?= $link ?>"><?= $i ?></a></li>
    <?php endfor; ?>
</ul>
```

**CSS untuk Styling**
```css
ul.pagination li a {
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
}

ul.pagination li a.active {
    background-color: #428bca; /* Warna biru untuk halaman aktif */
    color: white;
}
```

# *6. Latihan: Menambahkan Tombol Previous dan Next*
Untuk melengkapi pagination:
 - Tombol Previous `(&laquo;)`: harus menuju ke halaman `$page - 1`
 - Tombol Next `(&raquo;)`: harus menuju ke halaman `$page + 1`
**Implementasi yang harus ditambahkan:**
1. Cek apakah halaman sebelumnya ada (halaman > 1)
2. Cek apakah halaman berikutnya ada (halaman < total_halaman)
3. Nonaktifkan tombol jika sudah di halaman pertama/terakhir

# *7. Keuntungan Pagination*
 - Performansi: Query lebih cepat karena mengambil data terbatas
 - User Experience: Navigasi data lebih terstruktur
 - SEO Friendly: Halaman terpisah untuk konten yang banyak
