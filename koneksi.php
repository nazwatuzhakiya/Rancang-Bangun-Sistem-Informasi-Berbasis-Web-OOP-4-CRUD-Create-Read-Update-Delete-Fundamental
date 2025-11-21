<?php
session_start();

class database{
    var $host = "localhost";
    var $username = "root";
    var $password = "";
    var $database = "belajar_oop";
    var $koneksi;

    function __construct(){
        $this->koneksi = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        if(mysqli_connect_error()){
            echo "Koneksi database gagal : " . mysqli_connect_error();
        }
        mysqli_set_charset($this->koneksi, "utf8");
    }

    // Fungsi untuk menampilkan semua data
    function tampil_data(){
        $data = mysqli_query($this->koneksi, "select * from tb_barang");
        $hasil = array();
        if($data){
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
        }
        return $hasil;
    }

    // Fungsi untuk menambah data (Create)
    function tambah_data($kd_barang, $nama_barang, $stok, $harga_beli, $harga_jual, $gambar_produk){
        // Cek jika ada gambar produk
        if($gambar_produk != "" && $_FILES['gambar_produk']['error'] === UPLOAD_ERR_OK) {
            $ekstensi_diperbolehkan = array('png','jpg','jpeg'); 
            $x = explode('.', $gambar_produk); 
            $ekstensi = strtolower(end($x));
            $file_tmp = $_FILES['gambar_produk']['tmp_name'];
            
            // Generate nama unik menggunakan timestamp + random untuk menghindari duplikasi
            $timestamp = time();
            $angka_acak = rand(1000, 9999);
            $nama_gambar_baru = $timestamp . '_' . $angka_acak . '.' . $ekstensi; 

            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                // Validasi ukuran file (max 2MB)
                if($_FILES['gambar_produk']['size'] > 2097152) {
                    echo "<script>alert('Ukuran gambar terlalu besar (max 2MB).');window.location='tambah_data.php';</script>";
                    return;
                }
                
                if(move_uploaded_file($file_tmp, 'gambar/'.$nama_gambar_baru)) {
                    $query = "INSERT INTO tb_barang (id_barang, kd_barang, nama_barang, stok, harga_beli, harga_jual, gambar_produk) VALUES ('', '$kd_barang', '$nama_barang', '$stok', '$harga_beli', '$harga_jual', '$nama_gambar_baru')";
                    $result = mysqli_query($this->koneksi, $query);
                    
                    if(!$result){
                        die ("Query gagal dijalankan: ".mysqli_errno($this->koneksi). " - ".mysqli_error($this->koneksi));
                    } else {
                        echo "<script>alert('Data berhasil ditambah.');window.location='tampil.php';</script>";
                    }
                } else {
                    echo "<script>alert('Gagal upload gambar. Periksa permission folder gambar/');window.location='tambah_data.php';</script>";
                }
            } else {
                echo "<script>alert('Ekstensi gambar yang boleh hanya jpg, jpeg atau png.');window.location='tambah_data.php';</script>";
            }
        } else {
            // Jika tidak ada gambar
            $query = "INSERT INTO tb_barang (id_barang, kd_barang, nama_barang, stok, harga_beli, harga_jual, gambar_produk) VALUES ('', '$kd_barang', '$nama_barang', '$stok', '$harga_beli', '$harga_jual', '')";
            $result = mysqli_query($this->koneksi, $query);
            
            if(!$result){
                die ("Query gagal dijalankan: ".mysqli_errno($this->koneksi). " - ".mysqli_error($this->koneksi));
            } else {
                echo "<script>alert('Data berhasil ditambah.');window.location='tampil.php';</script>";
            }
        }
    }

    // Fungsi untuk mengambil data spesifik sebelum edit
    function tampil_edit_data($id_barang){
        $data = mysqli_query($this->koneksi, "select * from tb_barang where id_barang='$id_barang'");
        while($d = mysqli_fetch_array($data)){
            $hasil[] = $d;
        }
        return $hasil;
    }

    // Fungsi untuk mengubah data (Update)
    function edit_data($id_barang, $nama_barang, $stok, $harga_beli, $harga_jual, $gambar_produk){
        // Cek jika ada gambar yang diupload
        if($gambar_produk != "" && $_FILES['gambar_produk']['error'] === UPLOAD_ERR_OK) {
            $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg'); 
            $x = explode('.', $gambar_produk); 
            $ekstensi = strtolower(end($x));
            $file_tmp = $_FILES['gambar_produk']['tmp_name'];
            
            // Generate nama unik menggunakan timestamp + random
            $timestamp = time();
            $angka_acak = rand(1000, 9999);
            $nama_gambar_baru = $timestamp . '_' . $angka_acak . '.' . $ekstensi; 

            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                // Validasi ukuran file (max 2MB)
                if($_FILES['gambar_produk']['size'] > 2097152) {
                    echo "<script>alert('Ukuran gambar terlalu besar (max 2MB).');window.location='edit_data.php?id_barang=$id_barang';</script>";
                    return;
                }
                
                // Ambil gambar lama untuk dihapus
                $query_lama = mysqli_query($this->koneksi, "SELECT gambar_produk FROM tb_barang WHERE id_barang = '$id_barang'");
                $data_lama = mysqli_fetch_array($query_lama);
                
                if(move_uploaded_file($file_tmp, 'gambar/'.$nama_gambar_baru)) {
                    // Hapus gambar lama jika ada
                    if($data_lama['gambar_produk'] && file_exists('gambar/'.$data_lama['gambar_produk'])){
                        unlink('gambar/'.$data_lama['gambar_produk']);
                    }
                    
                    $query = "UPDATE tb_barang SET nama_barang = '$nama_barang', stok = '$stok', harga_beli = '$harga_beli', harga_jual = '$harga_jual', gambar_produk = '$nama_gambar_baru' WHERE id_barang = '$id_barang'";
                    $result = mysqli_query($this->koneksi, $query);
                    
                    if(!$result){
                        die ("Query gagal dijalankan: ".mysqli_errno($this->koneksi). " - ".mysqli_error($this->koneksi));
                    } else {
                        echo "<script>alert('Data berhasil diubah.');window.location='tampil.php';</script>";
                    }
                } else {
                    echo "<script>alert('Gagal upload gambar. Periksa permission folder gambar/');window.location='edit_data.php?id_barang=$id_barang';</script>";
                }
            } else {
                echo "<script>alert('Ekstensi gambar yang boleh hanya jpg, jpeg atau png.');window.location='edit_data.php?id_barang=$id_barang';</script>";
            }
        } else {
            // Jika tidak ada gambar yang diupload
            $query = "UPDATE tb_barang SET nama_barang = '$nama_barang', stok = '$stok', harga_beli = '$harga_beli', harga_jual = '$harga_jual' WHERE id_barang = '$id_barang'";
            $result = mysqli_query($this->koneksi, $query);
            
            if(!$result){
                die ("Query gagal dijalankan: ".mysqli_errno($this->koneksi). " - ".mysqli_error($this->koneksi));
            } else {
                echo "<script>alert('Data berhasil diubah.');window.location='tampil.php';</script>";
            }
        }
    }

    // Fungsi untuk menghapus data (Delete)
    function delete_data($id_barang){
        mysqli_query($this->koneksi, "delete from tb_barang where id_barang = '$id_barang'");
    }

    // Fungsi untuk login user
    function login($username, $password){
        // Quick check: find which user table exists (tb_user preferred, else user)
        $userTable = null;
        $check = mysqli_query($this->koneksi, "SHOW TABLES LIKE 'tb_user'");
        if($check && mysqli_num_rows($check) > 0) $userTable = 'tb_user';
        else {
            $check2 = mysqli_query($this->koneksi, "SHOW TABLES LIKE 'user'");
            if($check2 && mysqli_num_rows($check2) > 0) $userTable = 'user';
        }
        if(!$userTable){
            error_log("[login] no user table found (tb_user or user)");
            echo "<script>alert('Tabel user tidak ditemukan. Pastikan database di-import.');window.location='index.php';</script>";
            return;
        }
        // Use prepared statements to avoid SQL injection and handle query errors
        // Prepare select for password and additional info
        $stmtSql = "SELECT password FROM $userTable WHERE username=? LIMIT 1";
        $stmt = mysqli_prepare($this->koneksi, $stmtSql);
        if(!$stmt){
            // Query prepare failed - try fallback non-prepared query
            error_log("[login] prepare failed: " . mysqli_error($this->koneksi));
            // Fallback: attempt normal query with escaping
            $u = mysqli_real_escape_string($this->koneksi, $username);
            $p = mysqli_real_escape_string($this->koneksi, $password);
            $q = "SELECT * FROM " . $userTable . " WHERE username='$u' AND password='$p'";
            $r = mysqli_query($this->koneksi, $q);
            if(!$r){
                // Show helpful dev info
                $err = mysqli_error($this->koneksi);
                error_log("[login] fallback query failed: $err");
                echo "<script>alert('Terjadi kesalahan pada sistem: $err');window.location='index.php';</script>";
                return;
            } else {
                $hasil = mysqli_num_rows($r);
                if($hasil > 0){
                    $_SESSION['status'] = "login";
                    $_SESSION['username'] = $username;
                    echo "<script>alert('Login berhasil.');window.location='tampil.php';</script>";
                    return;
                } else {
                    echo "<script>alert('Username atau password salah.');window.location='index.php';</script>";
                    return;
                }
            }
        }
        mysqli_stmt_bind_param($stmt, 's', $username);
        $exec = mysqli_stmt_execute($stmt);
        if(!$exec){
            error_log("[login] execute failed: " . mysqli_stmt_error($stmt));
            echo "<script>alert('Terjadi kesalahan pada sistem. Silakan coba lagi.');window.location='index.php';</script>";
            mysqli_stmt_close($stmt);
            return;
        }
        // Try to use get_result (requires mysqlnd); if not available, fetch by bind
        $result = @mysqli_stmt_get_result($stmt);
        if($result !== false){
            $row = mysqli_fetch_assoc($result);
            $stored = $row['password'] ?? '';
        } else {
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) > 0){
                // bind single 'password' column
                mysqli_stmt_bind_result($stmt, $stored);
                mysqli_stmt_fetch($stmt);
            } else {
                $stored = '';
            }
        }
        // Verify password (supports hashed passwords using password_verify, fallback to plaintext)
        if(!empty($stored) && (password_verify($password, $stored) || $password === $stored)){
            $_SESSION['status'] = "login";
            $_SESSION['username'] = $username;
            echo "<script>alert('Login berhasil.');window.location='tampil.php';</script>";
        } else {
            echo "<script>alert('Username atau password salah.');window.location='index.php';</script>";
        }
        mysqli_stmt_close($stmt);
    }

    // Fungsi untuk logout
    function logout(){
        session_destroy();
        echo "<script>alert('Anda telah logout.');window.location='index.php';</script>";
    }

    // Fungsi untuk print satuan barang
    function satuan_print($nama_barang){
        $query = mysqli_query($this->koneksi, "SELECT * FROM tb_barang WHERE nama_barang='$nama_barang' LIMIT 1");
        $hasil = mysqli_fetch_array($query);
        return $hasil;
    }

    // Fungsi untuk menampilkan semua data untuk print
    function tampil_data_print(){
        $data = mysqli_query($this->koneksi, "SELECT * FROM tb_barang");
        $hasil = array();
        if($data){
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
        }
        return $hasil;
    }

    // Fungsi untuk mencari data barang
    function cari_data($kode){
        $data = mysqli_query($this->koneksi, "SELECT * FROM tb_barang WHERE kd_barang LIKE '%$kode%'");
        $hasil = array();
        if($data){
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
        }
        return $hasil;
    }

    // Helper: get image URL (checks common folders)
    function get_gambar_url($filename){
        if(!$filename) return '';
        // Prefer root gambar folder, then nested gambar/gambar
        $paths = array('gambar/'. $filename, 'gambar/gambar/'. $filename);
        foreach($paths as $p){
            if(file_exists($p)){
                return $p; // return relative path for HTML
            }
        }
        return '';
    }
}
?>