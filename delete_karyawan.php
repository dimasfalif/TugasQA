<?php
include 'db/db_config.php';

if (isset($_GET['id'])) {
    $id_karyawan_to_delete = $_GET['id'];

    // 1. Cek apakah ada data terkait di tabel 'hasil_spk'
    $jumlah_hasil_spk = $db->select('COUNT(*)', 'hasil_spk')->where("id_calon_kr = '$id_karyawan_to_delete'")->get()[0][0];

    if ($jumlah_hasil_spk > 0) {
        header('location:tampil_karyawan.php?error_msg=masih_ada_data_terkait');
    } else {
        // 2. Jika tidak ada data terkait, baru hapus data karyawan
        if ($db->delete('karyawan')->where('id_calon_kr=' . $id_karyawan_to_delete)->count() == 1) {
            header('location:tampil_karyawan.php?success_msg=delete_success');
        } else {
            header('location:tampil_karyawan.php?error_msg=error_delete');
        }
    }
} else {
    header('location:tampil_karyawan.php?error_msg=id_tidak_valid');
}
?>