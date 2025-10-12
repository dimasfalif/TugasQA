<?php
    session_start();
    error_reporting(0);
    
    // 1. Cek apakah user sudah login
    if(empty($_SESSION['id'])){
        header('location:login.php?error_login=1');
        exit();
    }
    
    // 2. KRITIS: Cek apakah user adalah ADMIN. Jika bukan, alihkan.
    if($_SESSION['level'] !== 'admin'){
        header('location:index.php'); // Atau kar_index.php
        exit();
    }
?>
<?php include 'header.php';?>
<?php include 'menu.php';?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Master Data Admin</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if (!empty($_GET['error_msg'])): ?>
                      <div class="alert alert-danger">
                          <?= $_GET['error_msg']; ?>
                      </div>
                    <?php endif ?>
                </div>
            </div>  
            <div class="row">
                <div><a href="input_admin.php" class="btn btn-info">Tambah Data</a></div>
                <br>
                <div  class="table-responsive">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($db->select('*','admin')->get() as $data): ?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= $data['nama']?></td>
                                <td><?= $data['alamat']?></td>
                                <td><?= $data['telepon']?></td>
                                <td><?= $data['email']?></td>
                                <td>
                                    <?php
                                    // Kontrol aksi (Edit/Hapus) dipertahankan, tidak bisa mengubah user 'admin' utama
                                    if($data['username']!="admin"){
                                    ?>
                                    <a class="btn btn-warning" href="edit_admin.php?id=<?php echo $data[0]?>">Edit</a>
                                    <a class="btn btn-danger" onclick="return confirm('Yakin Hapus?')" href="delete_admin.php?id=<?php echo $data[0]?>">Hapus</a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php $no++; endforeach; ?>
                        </tbody>
                    </table>    
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
<script type="text/javascript">
    $(function(){
        $("#AD").addClass('menu-top-active');
    });
</script>
<script type="text/javascript">
    $(function() {
        $('#example1').dataTable();
    });
</script>