<?php
include 'db/db_config.php'; // Pastikan file koneksi database Anda di-include
session_start();

// Aktifkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ambil data dari POST
$new_password = $_POST['np'] ?? '';
$retype_password = $_POST['rp'] ?? '';

// Ambil data sesi
$user_id = $_SESSION['id'] ?? null;
$user_level = $_SESSION['level'] ?? null;

// 1. Cek Login dan Sesi Level
if (empty($user_id) || empty($user_level)) {
    header('location:login.php?error_login=1');
    exit();
}

// 2. Validasi Input Kosong
if (empty($new_password) || empty($retype_password)) {
    header('location:ubah_password.php?error_msg=Password baru harus diisi.');
    exit();
}

// 3. Validasi Password Cocok
if ($new_password !== $retype_password) {
    header('location:ubah_password.php?error_msg=Konfirmasi password tidak cocok.');
    exit();
}

try {
    // 4. KRITIS: HASHING PASSWORD (Keamanan)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    $table = '';
    $id_column = '';

    // 5. Tentukan Tabel dan Kolom ID berdasarkan Level
    if ($user_level === 'admin') {
        $table = 'admin';
        $id_column = 'id'; 
    } elseif ($user_level === 'user') {
        $table = 'karyawan';
        $id_column = 'id_calon_kr'; 
    } else {
        header('location:ubah_password.php?error_msg=Level user tidak dikenal.');
        exit();
    }
    
    // 6. Update Database
    // Menggunakan array untuk data update, lebih aman.
    $result = $db->update($table, ['password' => $hashed_password])
                 ->where("{$id_column}='{$user_id}'")
                 ->count();
    
    if($result == 1){
        header('location:ubah_password.php?success_msg=Password berhasil diubah.');
        exit();
    } else {
        // Ini menangkap kasus di mana query berhasil, tetapi 0 baris terpengaruh (misalnya, ID user tidak ada)
        header('location:ubah_password.php?error_msg=Gagal menyimpan password baru. Data user mungkin tidak ditemukan.');
        exit();
    }
    
} catch (Exception $e) {
    // Tangani error sistem atau koneksi
    header('location:ubah_password.php?error_msg=Terjadi kesalahan sistem: ' . $e->getMessage());
    exit();
}
?>