<?php
    require_once 'db/db_config.php';
    extract($_POST);
    
    // 1. Sanitasi Nama Kriteria
    $crt_tmp = explode(' ',$kriteria);
    $crt = str_replace(str_split('\\/:*?"<>|+-()'), '', implode('_', $crt_tmp)); 
    
    // --- 2. PREPARE DATA DAN KOLOM UNTUK INSERT ---
    
    // ASUMSI: Tabel kriteria memiliki 4 kolom yang diisi
    $column_names = [
        'id_kriteria', 
        'kriteria',    
        'bobot',       
        'type'         
    ]; 
    
    $data_to_insert = [
        NULL,         // id_kriteria (AUTO_INCREMENT)
        $crt,          // Nama kriteria yang sudah disanitasi
        (float)$bobot,    
        $type      
    ];
    
    $columns = '`' . implode('`, `', $column_names) . '`';
    
    // 3. EKSEKUSI INSERT (Gunakan execute_dml())
    $db->insert('kriteria', $columns);
    
    if($db->execute_dml($data_to_insert) > 0){
        
        // ALTER TABLE dijalankan setelah INSERT berhasil
        $db->alter('hasil_tpa','add column',"`$crt`",'float(10,2)')->get();
        
        header('location:tampil_kriteria.php');
        exit;
    } else {
        header('location:tampil_kriteria.php?error_msg=Gagal menginput data kriteria.');
        exit;
    }
?>