<?php
    session_start();
    error_reporting(0);
    
    // 1. Cek apakah ada sesi login (untuk Admin atau User)
    if(empty($_SESSION['id'])){
        header('location:login.php');
        exit();
    }
?>
<?php include 'header.php';?>
<?php include 'menu.php';?>
<?php
    // Variabel level akan digunakan untuk kontrol akses
    $user_level = $_SESSION['level'] ?? 'guest'; // Default ke 'guest' jika level belum diset
    $role_label = ($user_level === 'admin') ? 'Administrator' : 'Karyawan';
    $user_name = htmlspecialchars($_SESSION['nama'] ?? 'Pengguna', ENT_QUOTES, 'UTF-8');
    $user_id_display = htmlspecialchars($_SESSION['id'] ?? '', ENT_QUOTES, 'UTF-8');
    $current_date = date('d M Y');

    $stats = [];
    if ($user_level === 'admin') {
        // Gunakan objek $db yang disediakan oleh header.php setelah include.
        $stats = [
            'admin' => (int) $db->totaladmin(),
            'kriteria' => (int) $db->totalkriteria(),
            'subkriteria' => (int) $db->totalsubkriteria(),
            'karyawan' => (int) $db->totalkaryawan(),
        ];
    }

    // PENTING: Untuk admin, dashboard ini akan menampilkan konten admin.
    // Jika user adalah Karyawan ('user'), mereka akan melihat konten user.
?>

<div class="content-wrapper">
    <div class="content">
        <div class="container">
            <div class="dashboard-hero">
                <span class="hero-highlight"><i class="fa fa-gift"></i> Manufaktur Hampers</span>
                <h2 class="hero-title">Selamat datang, <?php echo $user_name; ?>!</h2>
                <p class="hero-text">
                    <?php if ($user_level === 'admin'): ?>
                        Kelola strategi SDM untuk mendukung lini produksi hampers premium. Pastikan parameter penilaian selalu mutakhir sehingga setiap box terkirim tepat waktu.
                    <?php else: ?>
                        Pantau progres penilaian Anda dan kontribusi terhadap lini produksi hampers. Data yang akurat membantu tim memastikan hasil packaging terbaik.
                    <?php endif; ?>
                </p>
                <div class="hero-meta">
                    <span class="hero-badge"><i class="fa <?php echo ($user_level === 'admin') ? 'fa-shield' : 'fa-user'; ?>"></i> <?php echo $role_label; ?> Aktif</span>
                    <span class="hero-badge"><i class="fa fa-calendar"></i> <?php echo $current_date; ?></span>
                </div>
            </div>

            <?php if ($user_level === 'admin'): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <strong><i class="fa fa-lightbulb-o"></i> Catatan Produksi:</strong> Pastikan data penilaian karyawan diperbarui sebelum menjalankan proses SPK agar jadwal packaging tetap presisi.
                    </div>
                </div>
            </div>
            <div class="row stat-row">
                <div class="col-sm-6 col-lg-3">
                    <a class="stat-card" href="tampil_admin.php" id="AD">
                        <span class="stat-card-icon"><i class="fa fa-users"></i></span>
                        <span class="stat-card-label">Admin Aktif</span>
                        <span class="stat-card-number"><?php echo $stats['admin']; ?></span>
                        <span class="stat-card-cta">Kelola Administrator <i class="fa fa-long-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a class="stat-card" href="tampil_kriteria.php" id="ds">
                        <span class="stat-card-icon"><i class="fa fa-sliders"></i></span>
                        <span class="stat-card-label">Kriteria Seleksi</span>
                        <span class="stat-card-number"><?php echo $stats['kriteria']; ?></span>
                        <span class="stat-card-cta">Atur Parameter Penilaian <i class="fa fa-long-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a class="stat-card" href="tampil_subkriteria.php" id="sk">
                        <span class="stat-card-icon"><i class="fa fa-th-large"></i></span>
                        <span class="stat-card-label">Sub Kriteria</span>
                        <span class="stat-card-number"><?php echo $stats['subkriteria']; ?></span>
                        <span class="stat-card-cta">Detail Sub Kriteria <i class="fa fa-long-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a class="stat-card" href="tampil_karyawan.php" id="ck">
                        <span class="stat-card-icon"><i class="fa fa-id-card-o"></i></span>
                        <span class="stat-card-label">Karyawan Terdata</span>
                        <span class="stat-card-number"><?php echo $stats['karyawan']; ?></span>
                        <span class="stat-card-cta">Pantau Talent <i class="fa fa-long-arrow-right"></i></span>
                    </a>
                </div>
            </div>
            <?php else: ?>
            <div class="row">
                <div class="col-md-7">
                    <div class="stat-card">
                        <span class="stat-card-icon"><i class="fa fa-id-card-o"></i></span>
                        <span class="stat-card-label">Profil Karyawan</span>
                        <span class="stat-card-number"><?php echo $user_id_display; ?></span>
                        <p class="stat-card-desc">Selamat datang di area karyawan. Pantau status penilaian dan kesiapan produksi hampers kapan pun dibutuhkan.</p>
                        <a href="tampil_karyawan.php" class="stat-card-cta">Lihat Data Karyawan <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="stat-card secondary">
                        <span class="stat-card-icon"><i class="fa fa-lightbulb-o"></i></span>
                        <span class="stat-card-label">Hasil SPK</span>
                        <p class="stat-card-desc">Ketahui prioritas penempatan tim untuk mendukung alur produksi hampers secara menyeluruh.</p>
                        <a href="proses_spk.php" class="stat-card-cta">Lihat Hasil SPK <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script type="text/javascript">
    $(function(){
        $("#home").addClass('menu-top-active');
    });
</script>
