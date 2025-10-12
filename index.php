<?php
    session_start();
    error_reporting(0);
    
    // 1. Cek apakah ada sesi login (untuk Admin atau User)
    if(empty($_SESSION['id'])){
        header('location:login.php');
        exit();
    }
    
    // Variabel level akan digunakan untuk kontrol akses
    $user_level = $_SESSION['level'] ?? 'guest'; // Default ke 'guest' jika level belum diset

    // PENTING: Untuk admin, dashboard ini akan menampilkan konten admin.
    // Jika user adalah Karyawan ('user'), mereka akan melihat konten user.
?>
<?php include 'header.php';?>
<?php include 'menu.php';?>

<div class="content-wrapper">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Dashboard <?php echo ($user_level == 'admin' ? 'Administrator' : 'Karyawan'); ?></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        Selamat Datang <?php echo $_SESSION['nama'] ?>! (Level: <?php echo $user_level ?>)
                    </div>
                </div>
            </div>
            
            <?php if ($user_level == 'admin'): ?>
            <div class="row">
                <div class="col-lg-6 col-md-6"> <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-group fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $db->totaladmin() ?></div>
                            <div>Total Admin</div>
                        </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                        <span class="pull-left"><a href="tampil_admin.php" id="AD">View Details</a></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                        </div>
                    </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6"> <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $db->totalkriteria() ?></div>
                            <div>Total Kriteria</div>
                        </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                        <span class="pull-left"><a href="tampil_kriteria.php" id="ds">View Details</a></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                        </div>
                    </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6"> <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $db->totalsubkriteria() ?></div>
                            <div>Total Sub Kriteria</div>
                        </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                        <span class="pull-left"><a href="tampil_subkriteria.php" id="sk">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                        </div>
                    </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6"> <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-group fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $db->totalkaryawan() ?></div>
                            <div>Total Karyawan</div>
                        </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                        <span class="pull-left"><a href="tampil_karyawan.php" id="ck">View Details</a></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                        </div>
                    </a>
                    </div>
                </div>
            </div>
            
            <?php else: // Konten untuk Karyawan (user) ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">Profil Anda</div>
                        <div class="panel-body">
                            <p><strong>NIK:</strong> <?php echo $_SESSION['id'] ?></p>
                            <p>Selamat datang di area Karyawan. Akses Anda terbatas untuk melihat data pribadi dan informasi umum.</p>
                            <a href="tampil_karyawan.php" class="btn btn-warning">Lihat Data Karyawan (View Only)</a>
                        </div>
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