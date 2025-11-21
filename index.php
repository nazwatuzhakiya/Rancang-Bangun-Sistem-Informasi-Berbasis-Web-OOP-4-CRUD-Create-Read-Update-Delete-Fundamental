<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>Form Login</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="kotak_login">
<h3><b>Sistem Informasi Penjualan Barang </b> <br/> Politeknik Negeri Subang </h3>
<center>
    <?php
    // Cek di kedua lokasi: gambar/ dan gambar/gambar/
    $logoPath = '';
    if(file_exists('gambar/logo_aplikasi.png')){
        $logoPath = 'gambar/logo_aplikasi.png';
    } else if(file_exists('gambar/gambar/logo_aplikasi.png')){
        $logoPath = 'gambar/gambar/logo_aplikasi.png';
    }

    if($logoPath != ''){
        echo '<img src="'.htmlspecialchars($logoPath).'" width="200" height="200">';
    } else {
        echo '<img src="https://via.placeholder.com/200x200?text=Logo" width="200" height="200">';
    }
    ?>
</center>
</div>
<div class="kotak_login2">
<p class="tulisan_login">Silahkan Login</p>
<?php
if(isset($_GET['pesan']) && $_GET['pesan'] == 'belum_login'){
    echo "<p style='color:red; text-align:center;'>Anda harus login terlebih dahulu!</p>";
}
?>
<form name="form1" method="post" action="proses_barang.php?action=login">
 <label>Username</label>
<input name="username" type="text" id="username" class="form_login" placeholder="Username" required/>
<label>Password</label>
<input name="password" type="password" id="password" class="form_login" placeholder="Password" required/>
<input type="submit" name="Submit" class="tombol_login" value="Login"/>&nbsp;
<input type="reset" name="Reset" class="tombol_reset" value="Reset"/>
</form>
</div>
</body>
</html>