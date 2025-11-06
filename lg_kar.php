<?php
// KRITIS: Memulai sesi agar bisa membaca $_SESSION['error_message']
session_start();
?>
<?php include 'header.php'; ?>


<div class="content-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h4 class="page-head-line">Login Karyawan / User </h4>
        </div>
      </div>
      <div class="row">
        
        <div class="col-md-6 login-column">
          <div class="alert alert-info login-card">
          <br />
          <?php if (isset($_GET['error_login']) && $_GET['error_login']==1): ?>
            <div class="alert alert-danger">
              Anda Harus Login Terlebih Dahulu !
            </div>
            
          <?php endif ?>
          <?php
          // Bagian ini sekarang akan berfungsi karena session_start() sudah dipanggil
          if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']); 
          }
          ?>
          
          <form method="post" action="proses_login_kar.php">
            <label>Masukkan NIK Anda: </label>
            <input required type="text" name="NIK" class="form-control" />
            
            <label>Masukkan Password : </label>
            <input required type="password" name="password" class="form-control" />
            <hr />
            <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-user"></span> &nbsp;Login Karyawan </button>&nbsp;
          </form>
          </div>
        </div>
        
        <div class="col-md-6 login-column">
          <div class="alert alert-search login-card">
            <div class="login-search-header">
              <strong>Cari Rekan Kerja</strong>
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
              <img src="assets/img/ambatron.jpg" alt="Ilustrasi kolaborasi karyawan" class="login-gif">
              <p class="login-gif-caption">Temukan profil rekan kerja Anda dan kolaborasikan proses penilaian dengan mudah.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
<?php include 'footer.php';?>
