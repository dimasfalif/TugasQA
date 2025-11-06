<?php 
// KRITIS: Memulai sesi agar bisa membaca $_SESSION['error_message']
session_start();
?>
<?php include 'header.php'; ?>


<div class="content-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h4 class="page-head-line">Login System </h4>
        </div>
      </div>
      <div class="row">
        
        <div class="col-md-6">
          <div class="alert alert-info">
          <br />
          <?php if (isset($_GET['error_login']) && $_GET['error_login']==1): ?>
            <div class="alert alert-danger">
              Anda Harus Login Terlebih Dahulu !
            </div>
            
          <?php endif ?>
          <?php
          // Menampilkan pesan error dari session
          if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']); 
          }
          ?>
          
          <h5 class="page-head-line">LOGIN ADMINISTRATOR</h5>
          <form method="post" action="proses_login.php">
            <label>Enter Username (Admin): </label>
            <input required type="text" name="username" class="form-control" />
            <label>Enter Password (Admin): </label>
            <input required type="password" name="password" class="form-control" />
            <hr />
            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-lock"></span> &nbsp;Login Admin </button>&nbsp;
          </form>
          
          <hr />
          
          <p>Jika Anda seorang Karyawan, silakan login melalui halaman berikut:</p>
          <a href="lg_kar.php" class="btn btn-success btn-lg">
              <span class="glyphicon glyphicon-user"></span> &nbsp;Masuk Sebagai Karyawan
          </a>

          </div>
        </div>
        
        <div class="col-md-6">
          <div class="alert alert-search">
            <div class="login-search-header">
              <strong>Cari Karyawan</strong>
            </div>
            <form method="get" action="cari_karyawan.php" class="login-search-form">
              <div class="input-group">
                <input type="text" class="form-control" name="nama" placeholder="Search">
                <div class="input-group-btn">
                 <button class="btn btn-default" type="submit">
                  <i class="fa fa-search"></i>
                 </button>
                </div>
               </div>
            </form>
            <div class="login-gif-wrapper">
              <img src="assets/img/tangga.gif" alt="Ilustrasi tangga kesuksesan" class="login-gif">
              <p class="login-gif-caption">Naikkan performa tim dengan mencari data karyawan secara cepat.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
<?php include 'footer.php';?>
