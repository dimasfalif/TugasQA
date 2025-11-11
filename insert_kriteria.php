<?php
    include 'db/db_config.php';
    extract($_POST);
    
    // 1. Sanitasi Nama Kriteria (Diperlukan karena nama kriteria menjadi nama kolom di hasil_tpa)
    $crt_tmp = explode(' ',$kriteria);
    $crt = str_replace(str_split('\\/:*?"<>|+-()'), '', implode('_', $crt_tmp)); 
    
    // --- 2. PREPARE DATA DAN KOLOM UNTUK INSERT ---
    
    // ASUMSI: Tabel kriteria memiliki 4 kolom yang diisi (id_kriteria, kriteria, bobot, type)
    $column_names = [
        'id_kriteria', 
        'kriteria',    
        'bobot',       
        'type'         
    ]; 
    
    // Array Nilai (4 Nilai)
    $data_to_insert = [
        NULL,         // id_kriteria (NULL jika AUTO_INCREMENT)
        $crt,          // Nama kriteria yang sudah disanitasi
        (float)$bobot,    
        $type      
    ];
    
    // Membentuk string kolom dengan backtick (`)
    $columns = '`' . implode('`, `', $column_names) . '`';
    
    // 3. EKSEKUSI INSERT (Gunakan execute_dml())
    $db->insert('kriteria', $columns);
    
    if($db->execute_dml($data_to_insert) > 0){
        
        // ALTER TABLE dijalankan setelah INSERT berhasil
        // Nama kolom baru yang ditambahkan di-backtick juga untuk keamanan
        $db->alter('hasil_tpa','add column',"`$crt`",'float(10,2)')->get();
        
        header('location:tampil_kriteria.php');
        exit;
    } else {
        header('location:tampil_kriteria.php?error_msg=Gagal menginput data kriteria.');
        exit;
    }
?>