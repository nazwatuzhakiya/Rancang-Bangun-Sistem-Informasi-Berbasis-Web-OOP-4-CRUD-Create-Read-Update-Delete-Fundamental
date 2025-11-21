<?php
include('koneksi.php');
$db = new database();

// Validasi session
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:index.php?pesan=belum_login");
    exit();
}

// Prevent caching for real-time updates
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

$data_barang = array();
$pesan_cari = "";

// Pengecekan apakah ada pencarian
if(isset($_POST['Submit'])){
    $kode = isset($_POST['kode_barang']) ? $_POST['kode_barang'] : '';
    if($kode != ""){
        $data_barang = $db->cari_data($kode);
        if(count($data_barang) == 0){
            $pesan_cari = "Data tidak ditemukan";
        }
    } else {
        $data_barang = $db->tampil_data();
    }
} else {
    $data_barang = $db->tampil_data();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="style.css">
<title>Data Barang</title>
<style type="text/css">
/* ... (Style CSS yang panjang di dokumen, diabaikan di sini) ... */
</style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>DATA BARANG</h1>
<div style="background: #f5f5f5; padding: 10px; margin: 10px auto; max-width: 1000px; border-radius: 3px; text-align: center;">
<a href="tambah_data.php" style="margin: 0 5px;">Tambah Data</a>
<a href="cetak.php" target="_blank" style="margin: 0 5px;">Print Data Barang</a>
<a href="proses_barang.php?action=logout" style="margin: 0 5px;">Keluar Aplikasi</a>
</div>
<?php
if(isset($_GET['pesan'])){
    if($_GET['pesan'] == "belum_login"){
        echo "<p style='color:red; text-align:center;'>Anda harus login terlebih dahulu!</p>";
    }
}
?>
<form id="background_border" method="post">
<input type="text" placeholder="Cari berdasarkan Kode Barang" name="kode_barang" id="kode_barang">
<input type="submit" name="Submit" value="Cari Data">
<a href="tampil.php">Tampil Semua Data</a>
</form>
<?php if($pesan_cari != ""): ?>
    <p style="color: orange; text-align:center;"><?php echo $pesan_cari; ?></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Gambar Produk</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        if(count($data_barang) > 0){
            foreach($data_barang as $x){
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($x['kd_barang']); ?></td>
            <td><?php echo htmlspecialchars($x['nama_barang']); ?></td>
            <td><?php echo htmlspecialchars($x['stok']); ?></td>
            <td>Rp <?php echo number_format($x['harga_beli'], 2, ',', '.'); ?></td>
            <td>Rp <?php echo number_format($x['harga_jual'], 2, ',', '.'); ?></td>
            <td>
                <?php 
                $imgUrl = $db->get_gambar_url($x['gambar_produk']);
                if($imgUrl){
                    echo '<img src="'.htmlspecialchars($imgUrl).'" style="width: 100px; height: auto;" alt="'.htmlspecialchars($x['nama_barang']).'">';
                } else {
                    echo '<p style="color:#999; font-size:10px;">Tidak ada gambar</p>';
                }
                ?>
            </td>
            <td style="text-align: center;">
                <a href="edit_data.php?id_barang=<?php echo $x['id_barang']; ?>" style="margin: 2px;">Edit</a>
                <a href="proses_barang.php?action=delete&id_barang=<?php echo $x['id_barang']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" style="margin: 2px;">Hapus</a>
            </td>
        </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='8' style='text-align: center;'>Tidak ada data</td></tr>";
        }
        ?>
    </tbody>
</table>

<div class="pagination">
    <a href="#">Previous</a>
    <a href="#" class="active">1</a>
    <a href="#">2</a>
    <a href="#">Next</a>
</div>


<form name="form1" method="post" action="proses_barang.php?action=print_satuan" id="print_satuan">
    <input type="text" placeholder="Masukkan Nama Barang" name="nama_barang" id="nama_barang" required>
    <input type="submit" name="Submit" value="Print Satuan Barang">
</form>

</body>
</html>