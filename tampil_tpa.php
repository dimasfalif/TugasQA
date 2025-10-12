<?php
    session_start();
    error_reporting(0);
    
    // 1. Cek apakah user sudah login
    if(empty($_SESSION['id'])){
        header('location:login.php?error_login=1');
        exit();
    }
    
    // 2. Ambil level user untuk kontrol tampilan dan aksi
    $user_level = $_SESSION['level'] ?? 'user';
?>
<?php include 'header.php';?>
<?php include 'menu.php';?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Penilaian Karyawan</h4>
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
                <?php if ($user_level == 'admin'): ?>
                    <div><a href="input_tpa.php" class="btn btn-info">Tambah Data</a></div>
                <?php endif; ?>
                <br>
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <?php foreach ($db->select('kriteria','kriteria')->get() as $kr ): ?>
                                <th><?= $kr['kriteria']?></th>
                                <?php endforeach ?>
                                
                                <?php if ($user_level == 'admin'): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($db->select('karyawan.id_calon_kr,karyawan.nama,hasil_tpa.*','karyawan,hasil_tpa')->where('karyawan.id_calon_kr=hasil_tpa.id_calon_kr')->get() as $data): ?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= $data['nama']?></td>
                                
                                <?php foreach ($db->select('kriteria','kriteria')->get() as $k): ?>
                                    <td>
                                        <?= $db->getnamesubkriteria($data[$k['kriteria']])?> 
                                        <?php if ($user_level == 'admin'): ?>
                                            (Nilai = <?= $db->getnilaisubkriteria($data[$k['kriteria']])?>)
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach ?>
                                
                                <?php if ($user_level == 'admin'): ?>
                                    <td>
                                        <a class="btn btn-warning" href="edit_tpa.php?id=<?php echo $data['id_calon_kr']?>">Edit</a>
                                        <a class="btn btn-danger" onclick="return confirm('Yakin Hapus?')" href="delete_tpa.php?id=<?php echo $data['id_calon_kr']?>">Hapus</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php $no++; endforeach; ?>
                        </tbody>
                    </table>    
                </div>
            </div>
            
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        Untuk melihat hasil akhir proses pengangkatan karyawan, silakan buka halaman **<a href="proses_spk.php">Proses SPK</a>** atau halaman **<a href="lap_penilaian.php">Laporan Penilaian</a>**.
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php include 'footer.php'; ?>
<script type="text/javascript">
    $(function(){
        $("#tpa").addClass('menu-top-active');
    });
</script>
<script type="text/javascript">
    $(function() {
        $('#example1').dataTable();
    });
</script>