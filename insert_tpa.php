   <?php
       require_once 'db/db_config.php';
       
       // Validasi input dasar
       if (!isset($_POST['id_calon_kr']) || !is_numeric($_POST['id_calon_kr']) || !is_array($_POST['place']) || count($_POST['place']) !== 10) {
           die("Input tidak valid. Pastikan ID karyawan (angka) dan 10 kriteria dikirim dari form.");
       }
       
       $id_calon_kr = (int) $_POST['id_calon_kr'];
       
       // Ambil dan pisahkan nilai dari form (10 nilai kriteria)
       $kriteria_values = [];
       foreach ($_POST['place'] as $val) {
           if (!is_numeric($val)) {
               die("Nilai kriteria harus berupa angka (termasuk desimal).");
           }
           $kriteria_values[] = (float) $val;  // Cast ke float untuk mendukung desimal (sesuai DB float(10,2))
       }
       
       // Urutan kriteria di form (berdasarkan tabel 'kriteria'):
       // [0] ABSENSI, [1] SOP_Packing, [2] Kerja_Sama, [3] Tanggung_jawab, [4] Inisiatif, 
       // [5] Kerapian, [6] Ketelitian, [7] Kedisiplinan, [8] Kecepatan, [9] Atribut_pakaian_lengkap
       
       // Daftar kolom berdasarkan urutan di tabel hasil_tpa (sesuai struktur DB)
       $kolom_db_order = [
           'SOP_Packing',
           'Tanggung_jawab',
           'Inisiatif',
           'Kerapian',
           'Ketelitian',
           'kecepatan',  // Diperbaiki: huruf kecil sesuai DB (bukan 'Kecepatan')
           'Atribut_pakaian_lengkap',
           'ABSENSI',
           'kedisiplinan',
           'kerja_sama'
       ];
       
       $final_column_names = array_merge(['id_calon_kr'], $kolom_db_order);
       $columns = '`' . implode('`, `', $final_column_names) . '`';
       
       // Buat array data untuk INSERT (mapping sesuai urutan DB)
       $data_to_insert = [$id_calon_kr];
       $data_to_insert[] = $kriteria_values[1]; // SOP_Packing
       $data_to_insert[] = $kriteria_values[3]; // Tanggung_jawab
       $data_to_insert[] = $kriteria_values[4]; // Inisiatif
       $data_to_insert[] = $kriteria_values[5]; // Kerapian
       $data_to_insert[] = $kriteria_values[6]; // Ketelitian
       $data_to_insert[] = $kriteria_values[8]; // kecepatan
       $data_to_insert[] = $kriteria_values[9]; // Atribut_pakaian_lengkap
       $data_to_insert[] = $kriteria_values[0]; // absensi
       $data_to_insert[] = $kriteria_values[7]; // kedisiplinan
       $data_to_insert[] = $kriteria_values[2]; // kerja_sama
       
       // Verifikasi jumlah data
       if (count($data_to_insert) !== 11) {
           die("FATAL ERROR: Jumlah data setelah mapping salah. Ada " . (count($data_to_insert) - 1) . " kriteria yang terambil.");
       }
       
       // Bangun dan eksekusi query
       $db->insert('hasil_tpa', $columns);
       $result = $db->execute_dml($data_to_insert);
       
       if ($result > 0) {
           header('Location: tampil_tpa.php');
           exit;
       } else {
           // Log error untuk debug (opsional)
           error_log("Gagal insert ke hasil_tpa: " . print_r($data_to_insert, true));
           $error_msg = urlencode("Gagal memasukkan data. Periksa ID Karyawan (mungkin duplikat) atau data subkriteria.");
           header('Location: tampil_tpa.php?error_msg=' . $error_msg);
           exit;
       }
   ?>
   