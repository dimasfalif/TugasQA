<?php
include 'db/db_config.php';
session_start();

// Aktifkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ambil data dari form
$input_username = isset($_POST['username']) ? trim($_POST['username']) : '';
$input_password = isset($_POST['password']) ? $_POST['password'] : '';

// Cek input kosong
if (empty($input_username) || empty($input_password)) {
    $_SESSION['error_message'] = 'Username dan password harus diisi!';
    header('location:login.php');
    exit();
}

try {
    $login_success = false;
    $data = null;
    $level = null;

    // 1. Cek user di tabel 'admin'
    $admin_sql = $db->select('*', 'admin')->where("username='" . $input_username . "'");

    if ($admin_sql->count() == 1) {
        $data = $admin_sql->get()[0];
        
        // Verifikasi password untuk admin (menggunakan hashing)
        if (password_verify($input_password, $data['password'])) {
            // JIKA TIDAK MENGGUNAKAN HASHING, ganti baris di atas menjadi:
            // if ($input_password == $data['password']) {
            
            $level = $data['level'] ?? 'admin';
            $login_success = true;
        }
    }

    // Pengecekan Karyawan (Bagian 2 dihilangkan)

    // 3. Proses Hasil Login dan Penentuan Redirect
    if ($login_success) {
        // Login berhasil, set Session untuk Admin
        $_SESSION['id'] = $data['id']; // ID admin
        $_SESSION['nama'] = $data['username']; // Username admin
        $_SESSION['level'] = $level; // Level harus 'admin'
        $_SESSION['login_status'] = true;

        // Redirect selalu ke halaman admin
        header('location:index.php');
        exit();
    } else {
        // Jika cek admin gagal
        $_SESSION['error_message'] = 'Username atau password admin salah!';
        header('location:login.php');
        exit();
    }
    
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
    header('location:login.php');
    exit();
}
?>