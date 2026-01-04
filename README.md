
#  LabWeb13 & LabWeb14

# Praktikum 13 - Implementasi Pagination dengan PHP

**Nama:** Alipiani Dwi Putri  
**NIM:** 312410691  
**Kelas:** TI 24 A2  
**Mata Kuliah:** Pemrograman Web 1  
**Dosen:** Agung Nugroho, S.Kom., M.Kom.

---

##  Deskripsi

Praktikum ini merupakan implementasi sistem **Pagination** pada aplikasi manajemen data barang menggunakan PHP dan MySQL. Pagination digunakan untuk membatasi tampilan data menjadi beberapa halaman dengan navigasi yang user-friendly.

Proyek ini merupakan lanjutan dari [Praktikum 11&12 (PHP OOP)](https://github.com/Alipianidwiputri/Lab11Web) dengan penambahan fitur pagination dan berbagai enhancement UI/UX.



## Fitur Utama 

###  **Pagination System**
- Menampilkan **10 data per halaman**
- Navigasi **Previous** dan **Next** button
- **Nomor halaman** yang dapat diklik langsung
- **Active page indicator** (highlight halaman aktif)
- **Disabled state** untuk Previous/Next di halaman pertama/terakhir
- Info jumlah data: *"Menampilkan 1-10 dari 35 data"*
- Smart page range: `1 ... 3 4 [5] 6 7 ... 10`

###  **CRUD Operations**
-  **Create** - Tambah data barang baru
-  **Read** - Tampilkan data dengan pagination

###  **Authentication System**
- **Login** dengan session management
- **Logout** dengan session destroy
- **Dynamic menu** berubah sesuai status login
- **Welcome message** setelah login berhasil
- **Demo account** untuk testing

---

## Struktur Project

```
LAB11_PHP_OOP/
├── 📁 assets/
│   └── style.css                    # CSS dengan tema Pink Soft
├── 📁 class/
│   ├── Database.php                 # Class untuk koneksi database
│   └── Form.php                     # Class untuk handling form
├── 📁 images/
│   ├── hp_oppo.jpg
│   ├── hp_samsung.jpg
│   └── hp_xiaomi.jpg
├── 📁 module/
│   ├── 📁 artikel/
│   │   ├── index.php               # Halaman data barang (dengan pagination)
│   │   ├── tambah.php              # Form tambah barang
│   │   ├── ubah.php                # Form edit barang
│   │   └── hapus.php               # Proses hapus barang
│   └── 📁 user/
│       ├── login.php               # Halaman login
│       ├── logout.php              # Proses logout
│       └── profile.php             # Halaman profile user
├── 📁 template/
│   ├── header.php                  # Header dengan sidebar
│   ├── footer.php                  # Footer template
│   └── sidebar.php                 # Sidebar navigation
├── .htaccess                       # URL rewriting
├── config.php                      # Konfigurasi database
├── index.php                       # Landing page
└── README.md                       # Dokumentasi project
```

---

### 3️ **Setup Database**

1. Buka **phpMyAdmin**: `http://localhost/phpmyadmin`
2. Buat database baru: `latihan1`
3. Import SQL atau jalankan query berikut:

```sql
-- Buat tabel data_barang
CREATE TABLE `data_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `harga_jual` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Buat tabel users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert demo users
INSERT INTO `users` (`username`, `password`, `nama`, `email`) VALUES
('admin', 'admin123', 'Administrator', 'admin@example.com'),
('alipiani', 'password123', 'Alipiani Dwi Putri', 'alipiani@example.com');

-- Insert data dummy (untuk testing pagination)
INSERT INTO `data_barang` (`kategori`, `nama`, `harga_beli`, `harga_jual`, `stok`) VALUES
('Elektronik', 'HP Samsung Galaxy A54', 4500000.00, 5200000.00, 25),
('Elektronik', 'HP Oppo Reno 8', 3800000.00, 4500000.00, 30),
('Elektronik', 'HP Xiaomi Redmi Note 12', 2500000.00, 3000000.00, 40),
('Komputer', 'Laptop Asus VivoBook', 6500000.00, 7500000.00, 15),
('Komputer', 'Monitor LG 24 Inch', 1800000.00, 2200000.00, 28);
-- ... tambahkan 25 data lagi untuk total 30+ data
```

### 4️ **Konfigurasi Database**

Edit file `config.php`:
```php
<?php
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'latihan1');
}
?>
```

### 5️ **Jalankan Aplikasi**

1. Start **Apache** dan **MySQL** di XAMPP Control Panel
2. Buka browser
3. Akses: `http://localhost/LAB11_PHP_OOP/module/artikel/`

---

##  Cara Penggunaan

### **1. Akses Halaman Data Barang**
```
http://localhost/LAB11_PHP_OOP/module/artikel/index.php
```

### **2. Login (Optional)**
- Klik menu **"Login"** di sidebar
- Gunakan demo account:
  - Username: `admin`
  - Password: `admin123`

### **3. Kelola Data Barang**
- **Tambah:** Klik tombol "➕ Tambah Barang"
- **Edit:** Klik tombol "Ubah" pada data yang ingin diedit
- **Hapus:** Klik tombol "Hapus" (akan ada konfirmasi)

### **4. Navigasi Pagination**
- Klik **Previous** untuk halaman sebelumnya
- Klik **Next** untuk halaman berikutnya
- Klik **nomor halaman** untuk langsung ke halaman tersebut

---

##  Logika Pagination

### **Konsep Dasar**

Pagination membagi data menjadi beberapa halaman dengan menggunakan SQL `LIMIT` dan `OFFSET`.

### **Rumus Perhitungan**

```php
// Data per halaman
$per_page = 10;

// Halaman saat ini
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Hitung offset (data mulai dari mana)
$offset = ($page - 1) * $per_page;

// Hitung total halaman
$total_pages = ceil($row_count / $per_page);

// Query dengan LIMIT dan OFFSET
$sql = "SELECT * FROM data_barang LIMIT $per_page OFFSET $offset";
```

### **Contoh Perhitungan**

| Halaman | Per Page | Offset | Data Ditampilkan |
|---------|----------|--------|------------------|
| 1 | 10 | 0 | Record 1-10 |
| 2 | 10 | 10 | Record 11-20 |
| 3 | 10 | 20 | Record 21-30 |
| 4 | 10 | 30 | Record 31-40 |

**Jika total data = 35:**
- Total halaman = `ceil(35 / 10)` = **4 halaman**
- Halaman 1: Data 1-10
- Halaman 2: Data 11-20
- Halaman 3: Data 21-30
- Halaman 4: Data 31-35 (hanya 5 data)

### **Implementasi Previous & Next**

```php
// Previous Button
<?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?>">Previous</a>
<?php else: ?>
    <span class="disabled">Previous</span>
<?php endif; ?>

// Next Button
<?php if ($page < $total_pages): ?>
    <a href="?page=<?php echo $page + 1; ?>">Next</a>
<?php else: ?>
    <span class="disabled">Next</span>
<?php endif; ?>
```

---

##  Fitur Tambahan Praktikum 13 (Enhancement)

Berikut adalah fitur-fitur tambahan yang saya implementasikan di luar requirement praktikum:

###  **1. Pink Soft Theme**

**Deskripsi:** Tema visual dengan warna pink yang soft dan profesional.

**Implementasi:**
- Gradient pink: `#ff85c0` → `#ffb3d9`
- Background: `#ffeef8` → `#ffe4f1` → `#ffd4ea`
- Rounded corners: 10-25px radius
- Soft shadows dengan warna pink
- Custom pink scrollbar

**File:** `assets/style.css`

**Preview:**
```css
.top-header {
    background: linear-gradient(135deg, #ff85c0 0%, #ffb3d9 100%);
}
```

---

###  **2. Authentication System**

**Deskripsi:** Sistem login dan logout dengan session management.

**Fitur:**
- Login form dengan validasi
- Session untuk tracking user yang login
- Logout dengan session destroy
- Demo account untuk testing
- Alert notifikasi login berhasil/gagal

**File:**
- `module/user/login.php` - Form login
- `module/user/logout.php` - Proses logout

**Demo Account:**
```
Username: admin
Password: admin123
```

**Flow:**
```
Login → Set Session → Redirect → Welcome Message
Logout → Destroy Session → Redirect → Logout Message
```

---

###  **3. Dynamic Menu Navigation**

**Deskripsi:** Menu sidebar yang berubah otomatis berdasarkan status login.

**Kondisi:**
- **Belum Login:** Menu menampilkan " Login"
- **Sudah Login:** Menu menampilkan " Profile" + "Logout"

**Implementasi:**
```php
<?php if (isset($_SESSION['username'])): ?>
    <!-- Menu untuk user yang sudah login -->
    <li><a href="profile.php">Profile</a></li>
    <li><a href="logout.php">Logout</a></li>
<?php else: ?>
    <!-- Menu untuk user yang belum login -->
    <li><a href="login.php"> Login</a></li>
<?php endif; ?>
```

**File:** `template/header.php`

---

###  **4. Info Pagination**

**Deskripsi:** Informasi jumlah data yang sedang ditampilkan.

**Format:** *"Menampilkan 1 - 10 dari 35 data"*

**Implementasi:**
```php
<div class="info-pagination">
    Menampilkan <?php echo $offset + 1; ?> - 
    <?php echo min($offset + $per_page, $row_count); ?> 
    dari <?php echo $row_count; ?> data
</div>
```

**Manfaat:**
- User tahu posisi data yang sedang dilihat
- User tahu total keseluruhan data
- Meningkatkan UX

---

**Fitur:**
- Sticky sidebar (tetap saat scroll)
- Responsive (mobile → full width)
- Active menu indicator
- Icon untuk setiap menu

**File:** `template/header.php`, `assets/style.css`

---

###  **6. Alert Notifications**

**Deskripsi:** Notifikasi visual untuk feedback user action.

**Jenis Alert:**
- **Success** (hijau) - Data berhasil ditambah/edit/hapus
- **Error** (merah) - Login gagal, error sistem
- **Info** (biru) - Informasi umum

**Implementasi:**
```php
<?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
    <div class="alert alert-success">
        Data barang berhasil ditambahkan! 
    </div>
<?php endif; ?>
```

**Styling:**
```css
.alert-success {
    background: linear-gradient(135deg, #66d9aa 0%, #8ee5bf 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
}
```

---

###  **7. Hover & Animation Effects**

**Deskripsi:** Animasi smooth untuk meningkatkan interaktivitas.

**Implementasi:**

**Button Hover:**
```css
.btn-tambah:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 133, 192, 0.4);
}
```

**Table Row Hover:**
```css
table.data tbody tr:hover {
    background-color: #fff0f8;
}
```

**Page Link Hover:**
```css
.page-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(255, 133, 192, 0.4);
}
```

---

###  **8. Smart Page Range**

**Deskripsi:** Menampilkan range halaman yang smart, tidak semua nomor ditampilkan.

**Contoh:**
- Total 20 halaman, di halaman 10:
  ```
  Previous  1 ... 8 9 [10] 11 12 ... 20  Next
  ```

**Logic:**
```php
$range = 2; // Jumlah halaman di kiri & kanan
$start = max(1, $page - $range);
$end = min($total_pages, $page + $range);

// Tampilkan dots jika ada gap
if ($start > 1) {
    echo '<a href="?page=1">1</a>';
    if ($start > 2) {
        echo '<span>...</span>';
    }
}
```

---

### **9. Responsive Design**

**Deskripsi:** Tampilan menyesuaikan ukuran layar device.

**Breakpoints:**
```css
@media screen and (max-width: 768px) {
    .sidebar {
        width: 100%;
        position: relative;
    }
    
    .main-content {
        padding: 15px;
    }
}
```

**Fitur Responsive:**
- Sidebar full width di mobile
- Font size lebih kecil di mobile
- Padding & margin menyesuaikan
- Table scrollable horizontal

---

###  **10. Active Page Indicator**

**Deskripsi:** Halaman yang sedang aktif diberi highlight khusus.

**Implementasi:**
```php
<?php if ($i == $page): ?>
    <span class="page-link active"><?php echo $i; ?></span>
<?php else: ?>
    <a href="?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
<?php endif; ?>
```

**Styling:**
```css
.page-link.active {
    background: linear-gradient(135deg, #ff66a3 0%, #ff85c0 100%);
    font-weight: bold;
    transform: scale(1.05);
}
```

---

##  Screenshots

### 1. Halaman Data Barang (dengan Pagination)
<img width="801" height="889" alt="image" src="https://github.com/user-attachments/assets/7f37ddcb-2a69-44ce-8aa5-04d454ae4f40" />


- Tabel data dengan 10 record per halaman
- Pagination buttons di bawah
- Info jumlah data ditampilkan

### 2. Halaman Login
<img width="729" height="498" alt="Cuplikan layar 2026-01-04 093645" src="https://github.com/user-attachments/assets/f879bd1f-93bd-4237-ad36-4080d17730b0" />

- Form login dengan tema pink
- Demo account info
- Link kembali ke data barang

### 3. Form Tambah Barang
<img width="725" height="505" alt="Cuplikan layar 2026-01-04 093634" src="https://github.com/user-attachments/assets/48ff00e8-2c24-4dd6-a04f-949fb96658c4" />

- Form input dengan styling pink
- Validation pada setiap field
- Button submit & cancel

### 4. Pagination Navigation
<img width="1919" height="951" alt="image" src="https://github.com/user-attachments/assets/816195cd-3488-407f-b364-2ef3b686eafa" />


- Previous & Next buttons
- Nomor halaman dengan active indicator
- Disabled state untuk first/last page

---

**© 2026 - Praktikum Pemrograman Web 1**

</div>








## **Fitur Pencarian Data - Praktikum 14**

##  Deskripsi Singkat

Fitur pencarian memungkinkan user untuk mencari data barang berdasarkan **nama** atau **kategori** dengan cepat. Pencarian menggunakan SQL `LIKE` dengan wildcard untuk mencocokkan substring, dan hasil pencarian di-highlight untuk kemudahan identifikasi.

---

##  Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
|  **Search Box** | Form pencarian dengan tema pink soft |
|  **Multi-Column Search** | Cari berdasarkan nama atau kategori |
|  **Highlight Keyword** | Keyword di-highlight dengan background kuning |
|  **Info Hasil** | Menampilkan jumlah data yang ditemukan |
|  **Reset Button** | Kembali ke semua data dengan 1 klik |
|  **Pagination** | Hasil pencarian tetap ter-paginasi |
|  **Empty State** | Pesan user-friendly jika tidak ada hasil |

---

## Screenshots

### 1. Form Pencarian (Tampilan Awal)

<img width="1644" height="948" alt="image" src="https://github.com/user-attachments/assets/2a18fc66-349a-4250-8d29-d0002990fc7c" />


**Deskripsi:**
- Search box dengan border pink rounded
- Placeholder: "Cari nama barang atau kategori..."
- Button "Cari" dengan gradient pink
- Posisi di atas tabel data

---

### 2. Hasil Pencarian - Data Ditemukan

<img width="1125" height="810" alt="image" src="https://github.com/user-attachments/assets/5a2e3916-fedf-4512-a71f-65f43a0eea1a" />


**Deskripsi:**
- Keyword "Samsung" ditemukan
- Info box menampilkan: "Hasil pencarian untuk: **Samsung** (4 data ditemukan)"
- Data yang match ditampilkan dalam tabel
- Keyword di-highlight dengan background kuning
- Button "✖ Reset" muncul untuk clear pencarian
- Pagination muncul jika hasil > 10 data

---

## Implementasi Teknis

### Query SQL

**Tanpa Pencarian:**
```sql
SELECT * FROM data_barang 
ORDER BY id DESC 
LIMIT 10 OFFSET 0;
```

**Dengan Pencarian:**
```sql
SELECT * FROM data_barang 
WHERE nama LIKE '%Samsung%' 
   OR kategori LIKE '%Samsung%'
ORDER BY id DESC 
LIMIT 10 OFFSET 0;
```

### PHP Logic

```php
// Terima input
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Conditional query
if (!empty($search)) {
    $sql = "SELECT * FROM data_barang 
            WHERE nama LIKE '%$search%' 
            OR kategori LIKE '%$search%'
            ORDER BY id DESC 
            LIMIT $per_page OFFSET $offset";
} else {
    $sql = "SELECT * FROM data_barang 
            ORDER BY id DESC 
            LIMIT $per_page OFFSET $offset";
}

// Highlight hasil
if (!empty($search)) {
    $nama_display = str_ireplace($search, '<mark>' . $search . '</mark>', $nama_display);
}
```

### HTML Form

```html
<form method="GET" action="">
    <input 
        type="text" 
        name="search" 
        placeholder="Cari nama barang atau kategori..."
        value="<?php echo htmlspecialchars($search); ?>"
    >
    <button type="submit">Cari</button>
    <?php if (!empty($search)): ?>
        <a href="index.php">✖ Reset</a>
    <?php endif; ?>
</form>
```

### CSS Styling

```css
.search-input {
    padding: 12px 20px;
    border: 2px solid #ffe4f1;
    border-radius: 25px;
    font-size: 15px;
}

.search-input:focus {
    border-color: #ff99cc;
    box-shadow: 0 0 15px rgba(255, 153, 204, 0.3);
}

mark {
    background: linear-gradient(135deg, #fff59d 0%, #ffe082 100%);
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 600;
}
```

---

## Cara Penggunaan

### Langkah 1: Buka Halaman Data Barang
```
http://localhost/LAB11_PHP_OOP/module/artikel/
```

### Langkah 2: Input Keyword
- Ketik keyword di search box
- Contoh: "Samsung", "HP", "Komputer", "Elektronik"

### Langkah 3: Klik Tombol "Cari"
- Atau tekan Enter

### Langkah 4: Lihat Hasil
- Data yang match akan ditampilkan
- Keyword ter-highlight
- Info jumlah hasil muncul

### Langkah 5: Reset (Optional)
- Klik button "✖ Reset"
- Kembali ke tampilan semua data

---

## Contoh Pencarian

| Keyword | Hasil | Keterangan |
|---------|-------|------------|
| `Samsung` | 3 data | HP Samsung Galaxy A54, HP Samsung S23, dll |
| `HP` | 15 data | Semua HP (Samsung, Oppo, Xiaomi) |
| `Komputer` | 8 data | Laptop, Monitor, Keyboard, dll |
| `Elektronik` | 20 data | Semua kategori Elektronik |
| `iPhone` | 0 data | Empty state (data tidak ada) |
| `Rp` | 0 data | Pencarian tidak di kolom harga |

---

##  Color Scheme

| Element | Color | Hex Code |
|---------|-------|----------|
| Search Box Border | Pink Light | `#ffe4f1` |
| Search Box Focus | Pink Medium | `#ff99cc` |
| Button Background | Pink Gradient | `#ff85c0` → `#ffb3d9` |
| Highlight Background | Yellow Gradient | `#fff59d` → `#ffe082` |
| Info Box Background | Pink Very Light | `#ffe4f1` → `#fff0f8` |
| Reset Button | Pink Soft | `#ffa6d5` → `#ffc2e0` |


---

</div>
