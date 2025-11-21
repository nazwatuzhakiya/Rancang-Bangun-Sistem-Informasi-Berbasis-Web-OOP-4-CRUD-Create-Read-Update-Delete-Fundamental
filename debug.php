
<?php
echo "=== DEBUG INFO CRUD OOP ===\n\n";

// Cek folder gambar
echo "1. CEK FOLDER GAMBAR:\n";
$gambar_dir = 'gambar/';
if(is_dir($gambar_dir)){
    echo "   ✓ Folder 'gambar/' ada\n";
    
    // Cek permission
    $perms = fileperms($gambar_dir);
    if($perms & 0x0010){  // others can read
        echo "   ✓ Folder readable\n";
    }
    if($perms & 0x0080){  // others can write
        echo "   ✓ Folder writable\n";
    }
    
    // List files
    $files = scandir($gambar_dir);
    echo "   Files in folder: " . count($files) - 2 . "\n";
    foreach($files as $file){
        if($file !== '.' && $file !== '..'){
            echo "     - " . $file . "\n";
        }
    }
} else {
    echo "   ✗ Folder 'gambar/' TIDAK ADA\n";
    echo "   - Membuat folder...\n";
    mkdir($gambar_dir);
    echo "   ✓ Folder sudah dibuat\n";
}

echo "\n2. CEK DATABASE:\n";
include('koneksi.php');
$db = new database();

// Cek tabel
$check_table = mysqli_query($db->koneksi, "SHOW TABLES LIKE 'tb_barang'");
if(mysqli_num_rows($check_table) > 0){
    echo "   ✓ Tabel 'tb_barang' ada\n";
    
    // Hitung barang
    $result = mysqli_query($db->koneksi, "SELECT COUNT(*) as total FROM tb_barang");
    $row = mysqli_fetch_assoc($result);
    echo "   - Total barang: " . $row['total'] . "\n";
    
    // List barang
    $barang = $db->tampil_data();
    if(count($barang) > 0){
        echo "   - Daftar barang:\n";
        foreach($barang as $b){
            echo "     * " . $b['nama_barang'] . " (gambar: " . ($b['gambar_produk'] ? $b['gambar_produk'] : 'KOSONG') . ")\n";
        }
    }
} else {
    echo "   ✗ Tabel 'tb_barang' TIDAK ADA\n";
}

// Cek user table
$check_user = mysqli_query($db->koneksi, "SHOW TABLES LIKE 'user'");
$check_user2 = mysqli_query($db->koneksi, "SHOW TABLES LIKE 'tb_user'");
if(($check_user && mysqli_num_rows($check_user) > 0) || ($check_user2 && mysqli_num_rows($check_user2) > 0)){
    echo "   ✓ Tabel user ditemukan ('user' atau 'tb_user')\n";
    // list users count
    $tbl = ($check_user && mysqli_num_rows($check_user) > 0) ? 'user' : 'tb_user';
    $r = mysqli_query($db->koneksi, "SELECT COUNT(*) as total FROM $tbl");
    $row = mysqli_fetch_assoc($r);
    echo "   - Total user: " . $row['total'] . "\n";
} else {
    echo "   ✗ Tabel user tidak ditemukan (cari 'user' atau 'tb_user')\n";
}

// Cek apakah kolom gambar_produk ada pada tb_barang
$col_check = mysqli_query($db->koneksi, "SHOW COLUMNS FROM tb_barang LIKE 'gambar_produk'");
if($col_check && mysqli_num_rows($col_check) > 0){
    echo "\n5. Kolom 'gambar_produk' ada pada 'tb_barang'.\n";
} else {
    echo "\n5. Kolom 'gambar_produk' TIDAK ADA pada 'tb_barang'.\n";
    echo "   - Untuk menampilkan/upload gambar, jalankan file migrasi 'migrate_add_gambar_and_admin.sql' di phpMyAdmin atau jalankan:\n";
    echo "     ALTER TABLE tb_barang ADD COLUMN gambar_produk VARCHAR(255) NULL AFTER harga_jual;\n";
    echo "   - File migrasi juga dapat menambahkan akun admin default.\n";
}

echo "\n3. CEK SESSION:\n";
if(isset($_SESSION['status'])){
    echo "   ✓ Session aktif: " . $_SESSION['status'] . "\n";
    echo "   - Username: " . $_SESSION['username'] . "\n";
} else {
    echo "   ✗ Session tidak aktif\n";
}

echo "\n4. CEK SERVER:\n";
echo "   - PHP Version: " . phpversion() . "\n";
echo "   - OS: " . php_uname() . "\n";
echo "   - Memory: " . ini_get('memory_limit') . "\n";
echo "   - Max Upload: " . ini_get('upload_max_filesize') . "\n";

echo "\n=== END DEBUG ===\n";
?>
