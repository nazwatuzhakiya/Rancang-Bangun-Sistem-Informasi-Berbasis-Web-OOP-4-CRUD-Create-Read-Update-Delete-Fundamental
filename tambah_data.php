<?php
include('koneksi.php');

// Validasi session
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:index.php?pesan=belum_login");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Tambah Data Barang</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script>
function validasiForm() {
    var kd_barang = document.getElementById("kd_barang").value;
    var nama_barang = document.getElementById("nama_barang").value;
    var stok = document.getElementById("stok").value;
    var harga_beli = document.getElementById("harga_beli").value;
    var harga_jual = document.getElementById("harga_jual").value;
    
    if(kd_barang == "" || nama_barang == "" || stok == "" || harga_beli == "" || harga_jual == "") {
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
<p class="tulisan_login">Form Tambah Data Barang</p>
<form name="form1" method="post" action="proses_barang.php?action=add" enctype="multipart/form-data" onsubmit="return validasiForm()">
 <label>Kode Barang</label>
<input name="kd_barang" type="text" id="kd_barang" class="form_login" placeholder="Kode Barang" required/>
<label>Nama Barang</label>
<input name="nama_barang" type="text" id="nama_barang" class="form_login" placeholder="Nama Barang" required/>
<label>Stok</label>
<input name="stok" type="number" id="stok" class="form_login" placeholder="Stok" required min="1"/>
<label>Harga Beli</label>
<input name="harga_beli" type="number" id="harga_beli" class="form_login" placeholder="Harga Beli" required min="1" step="0.01"/>
<label>Harga Jual</label>
<input name="harga_jual" type="number" id="harga_jual" class="form_login" placeholder="Harga Jual" required min="1" step="0.01"/>
<label>Gambar Produk</label>
<input name="gambar_produk" type="file" id="gambar_produk" class="form_login" accept=".jpg,.jpeg,.png"/>
<div style="margin-top: 20px; text-align: center;">
<input type="submit" name="Submit" class="tombol_login" value="Simpan Data"/>&nbsp;
<input type="reset" name="Reset" class="tombol_reset" value="Reset"/>
</div>
<div style="margin-top: 15px; text-align: center;">
  <a href="tampil.php">Kembali ke Data Barang</a>
</div>
</form>
</div>
</body>
</html>