<?php
    session_start();
    error_reporting(0);
    // Cek Login Dasar
    if(empty($_SESSION['id'])){
        header('location:login.php?error_login=1');
        exit();
    }
?>
<?php include 'header.php';?>
<?php include 'menu.php';?>
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <br/>  
              <div class="panel panel-default">
                  <div class="panel-heading">
                    Form Ubah Password
                  </div>
                  <div class="panel-body">
                      <form method="post" action="update_password.php">
                          
                          <?php if (!empty($_GET['success_msg'])): ?>
                              <div class="alert alert-success">
                                  <?= $_GET['success_msg']; ?>
                              </div>
                          <?php endif ?> 
                          
                          <?php if (!empty($_GET['error_msg'])): ?>
                              <div class="alert alert-danger">
                                  <?= $_GET['error_msg']; ?>
                              </div>
                          <?php endif ?>  
                          
                          <div class="form-group">
                                <label for="inputNewPassword">New Password</label>
                                <input required type="password" name="np" class="form-control" id="inputNewPassword" placeholder="New Password">
                            </div>
                            <div class="form-group">
                                <label for="inputReTypePassword">Re-type Password</label>
                                <input required type="password" name="rp" class="form-control" id="inputReTypePassword" placeholder="Re-type Password">
                            </div>

                          <div class="form-group">
                              <button class="btn btn-primary">Simpan</button>
                          </div>
                      </form>
                  </div>
              </div>
            </div>
        </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>
<script type="text/javascript">
    $(function(){
        // Mengubah ID menu yang aktif agar sesuai dengan menu 'Ubah Password'
        $("#ubah_password").addClass('menu-top-active'); 
    });
</script>