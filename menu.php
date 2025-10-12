<?php 
// Pastikan sesi sudah dimulai sebelum file ini di-include di file utama
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_level = $_SESSION['level'] ?? 'user';
?>

<section class="menu-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="navbar-collapse collapse ">
                    <ul id="menu-top" class="nav navbar-nav navbar-right">
                        <li><a href="index.php" id="home">Dashboard</a></li>
                        
                        <li>
                            <a href="" data-toggle="dropdown" > Master</a>
                            <ul class="dropdown-menu">
                                
                                <?php if ($user_level == 'admin'): ?>
                                    <li><a href="tampil_admin.php" id="AD">Data Admin</a></li>
                                <?php endif; ?>
                                
                                <li><a href="tampil_karyawan.php" id="ck">Data Karyawan</a></li>
                                <li><a href="tampil_kriteria.php" id="ds">Data Kriteria</a></li>
                                <li><a href="tampil_subkriteria.php" id="sk">Data Sub Kriteria</a></li>
                            </ul>
                        </li>
                        
                        <?php if ($user_level == 'admin'): ?>
                            <li><a href="tampil_tpa.php" id="tpa">Penilaian Karyawan</a></li>
                        <?php endif; ?>
                        
                        <li><a href="proses_spk.php" id="proses">Hasil SPK</a></li> 
                        
                        <li><a href="ubah_password.php" id="ubah_password">Ubah Password</a></li> 
                        
                        <?php if ($user_level == 'admin'): ?>
                            <li>
                                <a href="" data-toggle="dropdown" > Laporan</a>
                                <ul class="dropdown-menu">
                                    <li><a href="lap_admin.php" id="lap_admin">Lap Admin</a></li>
                                    <li><a href="lap_karyawan.php" id="lap_karyawan">Lap Karyawan</a></li>
                                    <li><a href="lap_kriteria.php" id="lap_kriteria">Lap Kriteria</a></li>
                                    <li><a href="lap_subkriteria.php" id="lap_subkriteria">Lap Sub Kriteria</a></li>
                                    <li><a href="lap_penilaian.php" id="lap_penilaian">Lap Penilaian</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        
                        <li><a href="logout.php" id="logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>