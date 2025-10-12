<?php
include 'db/db_config.php';
session_start();

// Aktifkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ambil data dari form lg_kar.php (menggunakan 'NIK' dan 'password')
// CATATAN PENTING: Menggunakan variabel $input_nik (huruf kecil)
$input_nik = isset($_POST['NIK']) ? trim($_POST['NIK']) : '';
$input_password = isset($_POST['password']) ? $_POST['password'] : '';

// Cek input kosong
// Variabel yang dicek kini menggunakan $input_nik (sesuai dengan yang baru diambil)
if (empty($input_nik) || empty($input_password)) {
    $_SESSION['error_message'] = 'NIK dan password harus diisi!';
    header('location:lg_kar.php'); // Redirect kembali ke halaman login karyawan
    exit();
}

try {
    // 1. Cek user di tabel 'karyawan' menggunakan NIK
    // Asumsi kolom NIK di DB adalah 'NIK' (huruf besar) sesuai query Anda
    $kolom_NIK = 'NIK'; 
    $karyawan_sql = $db->select('*', 'karyawan')->where("{$kolom_NIK}='" . $input_nik . "'");

    if ($karyawan_sql->count() == 1) {
        $data = $karyawan_sql->get()[0];
        
        // 2. Verifikasi password untuk karyawan (MENGGUNAKAN HASHING)
        if (password_verify($input_password, $data['password'])) {
            
            // Login berhasil
            // Asumsi Primary Key: 'id_calon_kr' dan kolom nama: 'nama'
            $_SESSION['id'] = $data['id_calon_kr']; 
            $_SESSION['nama'] = $data['nama']; 
            $_SESSION['level'] = 'user'; // Level ditetapkan 'user'
            $_SESSION['login_status'] = true;

            // Redirect ke halaman karyawan (kar_index.php)
            header('location:index.php'); // Menggunakan 'kar_index.php' untuk konsistensi
            exit();
        } else {
            // Password salah
            $_SESSION['error_message'] = 'Password salah!';
            header('location:lg_kar.php');
            exit();
        }
    } else {
        // NIK tidak ditemukan
        $_SESSION['error_message'] = 'NIK tidak ditemukan!';
        header('location:lg_kar.php');
        exit();
    }
    
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
    header('location:lg_kar.php');
    exit();
}
?>