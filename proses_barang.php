<?php
include('koneksi.php');
$koneksi = new database();

// Validasi session untuk halaman selain login
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Cek session untuk action selain login dan logout
if($action != "login" && $action != "logout" && $action != ""){
    if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
        header("location:index.php?pesan=belum_login");
        exit();
    }
}

if($action == "add")
{
    if($_FILES['gambar_produk']['name'] != ""){
        $koneksi->tambah_data($_POST['kd_barang'], $_POST['nama_barang'], $_POST['stok'], $_POST['harga_beli'], $_POST['harga_jual'], $_FILES['gambar_produk']['name']);
    } else {
        $koneksi->tambah_data($_POST['kd_barang'], $_POST['nama_barang'], $_POST['stok'], $_POST['harga_beli'], $_POST['harga_jual'], "");
    }
}
else if($action == "edit")
{
    if($_FILES['gambar_produk']['name'] != ""){
        $koneksi->edit_data($_POST['id_barang'], $_POST['nama_barang'], $_POST['stok'], $_POST['harga_beli'], $_POST['harga_jual'], $_FILES['gambar_produk']['name']);
    } else {
        $koneksi->edit_data($_POST['id_barang'], $_POST['nama_barang'], $_POST['stok'], $_POST['harga_beli'], $_POST['harga_jual'], "");
    }
}
else if($action == "delete")
{
    $id_barang = $_GET['id_barang'];
    $koneksi->delete_data($id_barang);
    header('location:tampil.php');
}

// Fitur print satuan
else if($action == "print_satuan")
{
    $nama_barang = $_POST['nama_barang'];
    header('location:cetak2.php?nama_barang='.urlencode($nama_barang));
}

// Fitur Login
else if($action == "login")
{
    if(isset($_POST['username']) && isset($_POST['password'])){
        $koneksi->login($_POST['username'], $_POST['password']);
    }
}

// Fitur Logout
else if($action == "logout")
{
    $koneksi->logout();
}
?>