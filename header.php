<?php 
    // Memanggil session_start() hanya jika sesi belum aktif.
    // Solusi ini mencegah Notice session_start() ganda.
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    include 'db/db_config.php';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>Sistem Penunjang Keputusan</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FONT AWESOME ICONS  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
     <!-- HTML5 Shiv and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="assets/css/datatable.css">
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/datatable.js"></script>
</head>
<body>
    <div class="navbar navbar-inverse set-radius-zero brand-bar">
        <div class="container">
            <div class="navbar-header brand-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="brand-identity">
                    <div class="brand-icon">
                        <i class="fa fa-gift"></i>
                    </div>
                    <div class="brand-text">
                        <span class="brand-subtitle">PT. Grafika Kreatif Indonesia</span>
                        <span class="brand-title">Pabrik Packaging Hampers</span>
                        <span class="brand-slogan">Sistem Penunjang Keputusan Pengangkatan Karyawan</span>
                    </div>
                </div>
            </div>
            <div class="brand-motto hidden-xs hidden-sm">
                <span class="brand-badge">
                    <i class="fa fa-dropbox"></i>
                    Custom Box Production &amp; Finishing Excellence
                </span>
            </div>
        </div>
    </div>
   
