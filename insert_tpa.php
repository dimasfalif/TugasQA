<?php
    require_once 'db/db_config.php';
    extract($_POST);
    
    // --- 1. AMBIL DAN PISAHKAN NILAI DARI FORM (10 nilai kriteria) ---
    
    $kriteria_values = [];
    if (is_array($_POST['place'])) {
        foreach($_POST['place'] as $val) {
            $kriteria_values[] = (int) $val; 
        }
    }
    
    // Asumsi urutan kriteria di form (berdasarkan tabel 'kriteria'):
    // [0] ABSENSI, [1] SOP_Packing, [2] Kerja_Sama, [3] Tanggung_jawab, [4] Inisiatif, 
    // [5] Kerapian, [6] Ketelitian, [7] Kedisiplinan, [8] Kecepatan, [9] Atribut_pakaian_lengkap
    
    // --- 2. TENTUKAN DAFTAR KOLOM BERDASARKAN URUTAN DI TABEL hasil_tpa ---
    // Daftar ini diambil PERSIS dari struktur hasil_tpa (screenshot terakhir Anda)
    $kolom_db_order = [
        'SOP_Packing',      // Urutan 2 di DB
        'Tanggung_jawab',   // Urutan 3 di DB
        'Inisiatif',        // Urutan 4 di DB
        'Kerapian',         // Urutan 5 di DB
        'Ketelitian',       // Urutan 6 di DB
        'Kecepatan',        // Urutan 7 di DB
        'Atribut_pakaian_lengkap', // Urutan 8 di DB
        'absensi',          // Urutan 9 di DB
        'kedisiplinan',     // Urutan 10 di DB
        'kerja_sama'        // Urutan 11 di DB
    ];
    
    // Tambahkan ID di awal daftar kolom
    $final_column_names = array_merge(['id_calon_kr'], $kolom_db_order);
    $columns = '`' . implode('`, `', $final_column_names) . '`';
    
    // --- 3. BUAT ARRAY DATA UNTUK INSERT (Mapping Manual) ---
    
    $data_to_insert = [];
    $data_to_insert[] = (int) $id_calon_kr; // Nilai 1: ID Karyawan

    // Mapping Nilai Form (Input) ke Posisi Kolom DB (Output)
    $data_to_insert[] = $kriteria_values[1]; // 2. SOP_Packing (Nilai dari index 1)
    $data_to_insert[] = $kriteria_values[3]; // 3. Tanggung_jawab (Nilai dari index 3)
    $data_to_insert[] = $kriteria_values[4]; // 4. Inisiatif (Nilai dari index 4)
    $data_to_insert[] = $kriteria_values[5]; // 5. Kerapian (Nilai dari index 5)
    $data_to_insert[] = $kriteria_values[6]; // 6. Ketelitian (Nilai dari index 6)
    $data_to_insert[] = $kriteria_values[8]; // 7. Kecepatan (Nilai dari index 8)
    $data_to_insert[] = $kriteria_values[9]; // 8. Atribut_pakaian_lengkap (Nilai dari index 9)
    $data_to_insert[] = $kriteria_values[0]; // 9. absensi (Nilai dari index 0)
    $data_to_insert[] = $kriteria_values[7]; // 10. kedisiplinan (Nilai dari index 7)
    $data_to_insert[] = $kriteria_values[2]; // 11. kerja_sama (Nilai dari index 2)

    // Debugging
    if (count($data_to_insert) !== 11) {
        die("FATAL ERROR: Jumlah data setelah mapping salah. Ada " . (count($data_to_insert) - 1) . " kriteria yang terambil.");
    }
    
    // --- 4. Bangun dan Eksekusi Query ---
    $db->insert('hasil_tpa', $columns);
    
    if($db->execute_dml($data_to_insert) > 0){
        header('location:tampil_tpa.php');
        exit;
    } else {
        header('location:tampil_tpa.php?error_msg=Gagal memasukkan data. Periksa ID Karyawan atau data subkriteria.');
        exit;
    }
?>