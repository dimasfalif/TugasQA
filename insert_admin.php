<?php
// Tiga baris ini membantu debugging!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pastikan db/db_config.php sudah menggunakan PDO murni atau class yang diperbaiki.
include 'db/db_config.php'; 

// Ambil data dari POST (Ganti ini jika nama field form Anda berbeda)
$nama       = trim($_POST['nama'] ?? '');
$alamat     = trim($_POST['alamat'] ?? '');
$telepon    = trim($_POST['telepon'] ?? '');
$email      = trim($_POST['email'] ?? '');
$username   = trim($_POST['username'] ?? '');
$password   = $_POST['password'] ?? '';

// 1. PASSWORD HASHING (KUNCI PERBAIKAN LOGIN)
// Ini menghasilkan hash yang cocok dengan password_verify() saat login.
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Pastikan semua field sudah diisi
if (empty($username) || empty($password)) {
    die("Username dan password tidak boleh kosong.");
}

// 2. MENGGUNAKAN PREPARED STATEMENT UNTUK KEAMANAN SQL INJECTION
$sql = "INSERT INTO admin (id, nama, alamat, telepon, email, username, password) 
        VALUES (NULL, :nama, :alamat, :telepon, :email, :username, :password)";

try {
    // $db harus berupa objek PDO yang valid (seperti yang kita perbaiki sebelumnya)
    $stmt = $db->prepare($sql);
    
    // Bind parameter
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':alamat', $alamat);
    $stmt->bindParam(':telepon', $telepon);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password); // SIMPAN HASH AMAN

    // Jalankan query
    if ($stmt->execute()) {
        header('location:tampil_admin.php');
        exit();
    } else {
        die("Pendaftaran gagal dieksekusi.");
    }
} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>