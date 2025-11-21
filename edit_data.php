<?php
include('koneksi.php');
$db = new database();

// Validasi session
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:index.php?pesan=belum_login");
    exit();
}

if(!isset($_GET['id_barang'])){
    header("location:tampil.php");
    exit();
}

$id_barang = $_GET['id_barang'];
$data = $db->tampil_edit_data($id_barang);

if(count($data) == 0){
    header("location:tampil.php");
    exit();
}

// Mengambil data pertama
$x = $data[0];
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Data Barang</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script>
function validasiForm() {
    var nama_barang = document.getElementById("nama_barang").value;
    var stok = document.getElementById("stok").value;
    var harga_beli = document.getElementById("harga_beli").value;
    var harga_jual = document.getElementById("harga_jual").value;
    
    if(nama_barang == "" || stok == "" || harga_beli == "" || harga_jual == "") {
        alert("Semua field harus diisi!");
        return false;
    }
    
    if(isNaN(stok) || stok <= 0) {
        alert("Stok harus berupa angka positif!");
        return false;
    }
    
    if(isNaN(harga_beli) || harga_beli <= 0) {
        alert("Harga beli harus berupa angka positif!");
        return false;
    }
    
    if(isNaN(harga_jual) || harga_jual <= 0) {
        alert("Harga jual harus berupa angka positif!");
        return false;
    }
    
    return true;
}
</script>
</head>
<body>
<div class="kotak_login">
<p class="tulisan_login">Form Edit Data Barang</p>
<form name="form1" method="post" action="proses_barang.php?action=edit" enctype="multipart/form-data" onsubmit="return validasiForm()">
<label>Kode Barang</label>
<input name="kd_barang" type="text" id="kd_barang" class="form_login" value="<?php echo htmlspecialchars($x['kd_barang']); ?>" readonly/>
<input type="hidden" name="id_barang" value="<?php echo htmlspecialchars($x['id_barang']); ?>"> 
<label>Nama Barang</label>
<input name="nama_barang" type="text" id="nama_barang" class="form_login" value="<?php echo htmlspecialchars($x['nama_barang']); ?>" required/>
<label>Stok</label>
<input name="stok" type="number" id="stok" class="form_login" value="<?php echo htmlspecialchars($x['stok']); ?>" required min="1"/>
<label>Harga Beli</label>
<input name="harga_beli" type="number" id="harga_beli" class="form_login" value="<?php echo htmlspecialchars($x['harga_beli']); ?>" required min="1" step="0.01"/>
<label>Harga Jual</label>
<input name="harga_jual" type="number" id="harga_jual" class="form_login" value="<?php echo htmlspecialchars($x['harga_jual']); ?>" required min="1" step="0.01"/>
<label>Gambar Produk Saat Ini</label>
<?php
$imgUrl = $db->get_gambar_url($x['gambar_produk']);
if($imgUrl){
    echo '<img src="'.htmlspecialchars($imgUrl).'" style="width: 150px; height: auto; margin: 10px 0; border: 1px solid #ddd; padding: 5px; border-radius: 3px;">';
} else {
    echo '<p style="color:#999;">Tidak ada gambar</p>';
}
?>
<label>Ganti Gambar Produk (Opsional)</label>
<input name="gambar_produk" type="file" id="gambar_produk" class="form_login" accept=".jpg,.jpeg,.png"/>
<div style="margin-top: 20px; text-align: center;">
<input type="submit" name="Submit" class="tombol_login" value="Update Data"/>&nbsp;
<input type="reset" name="Reset" class="tombol_reset" value="Reset"/>
</div>
<div style="margin-top: 15px; text-align: center;">
  <a href="tampil.php">Kembali ke Data Barang</a>
</div>
</form>
</div>
</body>
</html>