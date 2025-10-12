<?php
// Aktifkan Error Reporting untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Pastikan file koneksi database Anda (db/db_config.php) sudah benar
include 'db/db_config.php'; 

// Hentikan penggunaan extract($_POST) yang berbahaya
// Ambil data POST secara eksplisit
$nik= trim($_POST['nik'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jeniskelamin = trim($_POST['jeniskelamin'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$telepon= trim($_POST['telepon'] ?? '');
$ttl_form = trim($_POST['ttl'] ?? ''); // Dari form
$tempatlahir = trim($_POST['tempatlahir'] ?? '');
$pendidikan  = trim($_POST['pendidikan'] ?? '');
$jabatan_form = trim($_POST['jabatan'] ?? ''); // Dari form
$ttb_form = trim($_POST['ttb'] ?? ''); // Dari form
$skill = trim($_POST['skill'] ?? '');
$pengalaman = trim($_POST['pengalaman'] ?? '');
$password = $_POST['password'] ?? ''; 

// 1. PASSWORD HASHING
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// --- Proses Upload File ---
$target_dir = "assets/foto_calon_karyawan/";
$file_name = $_FILES['foto']['name'] ?? ''; 
$target_file = $target_dir . basename($file_name);
$file_size = $_FILES['foto']['size'] ?? 0;
$file_tmp_name = $_FILES['foto']['tmp_name'] ?? '';
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// 2. LOGIKA VALIDASI FILE YANG BENAR
if (!empty($file_name) && $file_size > 0) {
if ($file_size > 2000000) {
 header('location:input_karyawan.php?error_msg=error_ukuran');
 exit();
}
 
 // Izinkan format tertentu
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
 header('location:input_karyawan.php?error_msg=error_type');
 exit();
}

  // Pindahkan file
  if (!move_uploaded_file($file_tmp_name, $target_file)) {
    header('location:input_karyawan.php?error_msg=error_upload');
    exit();
  }
}
// ----------------------------

// 3. MENGGUNAKAN PREPARED STATEMENTS (Koreksi nama kolom agar sesuai dengan struktur database)
$sql = "INSERT INTO karyawan 
    (id_calon_kr, NIK, nama, jeniskelamin, alamat, telepon, foto, ttl, TempatLahir, PendidikanTerakhir, Jabatan, TglBergabung, skill, pengalaman, password) 
    VALUES (NULL, :nik, :nama, :jeniskelamin, :alamat, :telepon, :foto, :ttl_db, :tempatlahir_db, :pendidikan_db, :jabatan_db, :ttb_db, :skill, :pengalaman, :password)";

try {
  $stmt = $db->prepare($sql);
  
  // Binding parameters: Sesuaikan nama placeholder dengan nama variabel yang diambil dari POST
  $stmt->bindParam(':nik', $nik);
  $stmt->bindParam(':nama', $nama);
  $stmt->bindParam(':jeniskelamin', $jeniskelamin);
  $stmt->bindParam(':alamat', $alamat);
  $stmt->bindParam(':telepon', $telepon);
  $stmt->bindParam(':foto', $file_name); 
  $stmt->bindParam(':ttl_db', $ttl_form); // Mengikat ttl dari form
  $stmt->bindParam(':tempatlahir_db', $tempatlahir); // Mengikat tempatlahir dari form
  $stmt->bindParam(':pendidikan_db', $pendidikan); // Mengikat pendidikan dari form
  $stmt->bindParam(':jabatan_db', $jabatan_form); // Mengikat jabatan dari form
  $stmt->bindParam(':ttb_db', $ttb_form); // Mengikat ttb dari form
  $stmt->bindParam(':skill', $skill);
  $stmt->bindParam(':pengalaman', $pengalaman);
  $stmt->bindParam(':password', $hashed_password); 

  if ($stmt->execute()) {
    header('location:tampil_karyawan.php');
    exit();
  } else {
    header('location:input_karyawan.php?error_msg=error_insert');
    exit();
  }

} catch (PDOException $e) {
  // Tangani error database
  // Hapus file yang mungkin sudah terunggah jika insert gagal
  if (!empty($file_name) && file_exists($target_file)) {
    unlink($target_file);
  }
  // Tampilkan pesan error database spesifik
  header('location:input_karyawan.php?error_msg=db_error&detail=' . urlencode($e->getMessage()));
  exit();
}
?>