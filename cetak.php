<?php
include('koneksi.php');
$db = new database();

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
    <title>Laporan Data Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Laporan Data Barang</h2>
    <p class="text-center">
        <button onclick="window.print()">Print</button>
        <button onclick="window.history.back()">Kembali</button>
    </p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stok</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $data = $db->tampil_data_print();
            if(count($data) > 0){
                foreach($data as $x){
            ?>
            <tr>
                <td class="text-center"><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($x['kd_barang']); ?></td>
                <td><?php echo htmlspecialchars($x['nama_barang']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($x['stok']); ?></td>
                <td>Rp <?php echo number_format($x['harga_beli'], 2, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($x['harga_jual'], 2, ',', '.'); ?></td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Tidak ada data barang</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
