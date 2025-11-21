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
<meta charset="utf-8">
<title>Data Pengguna</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1>PROFIL PENGGUNA SISTEM</h1>

<div style="margin: 20px auto; max-width: 500px; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <div style="margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
        <strong>Username:</strong><br/>
        <?php echo htmlspecialchars($_SESSION['username']); ?>
    </div>
    
    <div style="margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
        <strong>Status:</strong><br/>
        <span style="color: green;">✓ Login</span>
    </div>
    
    <div style="margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
        <strong>Tanggal dan Waktu:</strong><br/>
        <?php echo date('d-m-Y H:i:s'); ?>
    </div>
    
    <div style="margin-top: 25px; text-align: center;">
        <a href="tampil.php" style="padding: 10px 20px; background: #47C0DB; color: white; text-decoration: none; border-radius: 3px; margin-right: 10px;">← Kembali ke Data Barang</a>
        <a href="proses_barang.php?action=logout" style="padding: 10px 20px; background: #d9534f; color: white; text-decoration: none; border-radius: 3px;">Logout</a>
    </div>
</div>

</body>
</html>
