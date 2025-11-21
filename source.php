<?php
/**
 * File: source.php
 * Fungsi-fungsi tambahan atau API untuk aplikasi
 */

include('koneksi.php');

// Cek apakah ada request dari AJAX atau API
if(isset($_REQUEST['action'])){
    $action = $_REQUEST['action'];
    $db = new database();
    
    // Action untuk autocomplete cari barang
    if($action == 'autocomplete_barang'){
        $term = isset($_REQUEST['term']) ? $_REQUEST['term'] : '';
        $data = $db->cari_data($term);
        
        $results = array();
        foreach($data as $barang){
            $results[] = array(
                'id' => $barang['id_barang'],
                'label' => $barang['kd_barang'] . ' - ' . $barang['nama_barang'],
                'value' => $barang['kd_barang']
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($results);
    }
    
    // Action untuk mendapatkan detail barang
    else if($action == 'get_barang'){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $data = $db->tampil_edit_data($id);
        
        header('Content-Type: application/json');
        if(count($data) > 0){
            echo json_encode($data[0]);
        } else {
            echo json_encode(array('error' => 'Data tidak ditemukan'));
        }
    }
    
    // Action untuk check stok
    else if($action == 'check_stok'){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $data = $db->tampil_edit_data($id);
        
        header('Content-Type: application/json');
        if(count($data) > 0){
            echo json_encode(array(
                'stok' => $data[0]['stok'],
                'nama_barang' => $data[0]['nama_barang']
            ));
        } else {
            echo json_encode(array('error' => 'Data tidak ditemukan'));
        }
    }
}
?>
