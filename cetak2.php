<?php
include('koneksi.php');
$db = new database();

// Validasi session
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:index.php?pesan=belum_login");
    exit();
}

$nama_barang = isset($_GET['nama_barang']) ? $_GET['nama_barang'] : '';
$data = $db->satuan_print($nama_barang);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Detail Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid black;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        .info-row {
            display: flex;
            margin: 15px 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .info-value {
            flex: 1;
        }
        .text-center {
            text-align: center;
        }
        .gambar {
            text-align: center;
            margin: 20px 0;
        }
        .gambar img {
            max-width: 300px;
            height: auto;
        }
        .button-group {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Detail Barang</h2>
        
        <?php
        if($data && is_array($data) && count($data) > 0) {
        ?>
            <div class="info-row">
                <div class="info-label">Kode Barang:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['kd_barang']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Nama Barang:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['nama_barang']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Stok:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['stok']); ?> Unit</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Harga Beli:</div>
                <div class="info-value">Rp <?php echo number_format($data['harga_beli'], 2, ',', '.'); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Harga Jual:</div>
                <div class="info-value">Rp <?php echo number_format($data['harga_jual'], 2, ',', '.'); ?></div>
            </div>
            
            <?php $imgUrl = $db->get_gambar_url($data['gambar_produk']); if($imgUrl): ?>
            <div class="gambar">
                <strong>Gambar Produk:</strong>
                <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($data['nama_barang']); ?>">
            </div>
            <?php endif; ?>
            
            <div class="button-group">
                <button onclick="window.print()">Print</button>
                <button onclick="window.history.back()">Kembali</button>
            </div>
        <?php
        } else {
            echo "<p class='text-center'>Data barang tidak ditemukan</p>";
        }
        ?>
    </div>
</body>
</html>
