<?php
    require_once 'db/db_config.php';
    
    // 1. Verifikasi dan Ambil Nilai dari POST
    if (!isset($_POST['id_kriteria'], $_POST['subkriteria'], $_POST['nilai'])) {
        header('location:input_subkriteria.php?error_msg=Data form tidak lengkap.');
        exit;
    }

    // Mengambil dan membersihkan/mengkonversi nilai
    $id_kriteria = (int)$_POST['id_kriteria'];
    $subkriteria = trim($_POST['subkriteria']); 
    $nilai       = (float)$_POST['nilai'];
    
    // Cek jika subkriteria kosong (untuk kolom NOT NULL)
    if (empty($subkriteria)) {
         header('location:input_subkriteria.php?error_msg=Nama Sub Kriteria tidak boleh kosong.');
         exit;
    }

    // --- 2. PREPARE DATA DAN KOLOM UNTUK INSERT ---
    $column_names = [
        'id_subkriteria', 
        'id_kriteria', 
        'subkriteria', 
        'nilai'
    ]; 
    
    $columns = '`' . implode('`, `', $column_names) . '`';
    
    $data_to_insert = [
        NULL,              // id_subkriteria (AUTO_INCREMENT)
        $id_kriteria,      // id_kriteria
        $subkriteria,      // subkriteria
        $nilai             // nilai
    ];
    
    // --- 3. EKSEKUSI (Gunakan execute_dml()) ---
    $db->insert('sub_kriteria', $columns);
    
    if($db->execute_dml($data_to_insert) > 0){
        header('location:tampil_subkriteria.php');
        exit;
    } else {
        header('location:input_subkriteria.php?error_msg=Gagal menginput subkriteria. Pastikan ID Kriteria valid.');
        exit;
    }
?>