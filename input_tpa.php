<?php
    session_start();
    error_reporting(0);
    
    // 1. Cek Sesi
    if(empty($_SESSION['id'])){
        header('location:login.php?error_login=1');
        exit(); // Tambahkan exit()
    }

    // 2. KOREKSI KRITIS: INCLUDE DB DI SINI! 
    // Gunakan require_once untuk menghindari masalah Fatal Error (class already in use)
    // dan pastikan $db terdefinisi sebelum digunakan di header/menu.php atau di form ini.
    require_once 'db/db_config.php'; 
?>

<?php 
    // Baris ini akan berjalan tanpa error karena $db sudah terdefinisi
    include 'header.php';
?>
<?php include 'menu.php';?>
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="panel-body">
                <form method="post" action="insert_tpa.php" enctype="multipart/form-data">
                    <?php if (!empty($_GET['error_msg'])): ?>
                    <?php endif ?>
                    
                    <div class="form-group col-md-12">
                        <label for="nama">Nama Karyawan</label>
                        <select required class="form-control" name="id_calon_kr">
                        <?php 
                            // Query di sini (sekarang $db sudah ada)
                            foreach ($db->select('*','karyawan')->where('id_calon_kr not in (select id_calon_kr from hasil_tpa)')->get() as $val): 
                        ?> 
                            <option value="<?= $val['id_calon_kr']?>"><?= $val['nama'] ?></option>
                        <?php endforeach ?>
                        </select>
                    </div>
                    
                    <?php 
                        // Loop Kriteria (sekarang $db sudah ada)
                        foreach ($db->select('id_kriteria,kriteria','kriteria')->get() as $r): 
                    ?>
                    <div class="form-group col-md-3">
                        <label><?= $r['kriteria']?></label>
                        <select required class="form-control" name="place[]">
                        <?php 
                            // Loop Sub Kriteria (sekarang $db sudah ada)
                            foreach ($db->select('*','sub_kriteria')->where('id_kriteria = '.$r['id_kriteria'].'')->get() as $val): 
                        ?> 
                            <option value="<?= $val['id_subkriteria']?>"><?= $val['subkriteria'] ?> (Nilai = <?= $val['nilai'] ?>)</option>
                        <?php endforeach ?>
                        </select>
                    </div>
                    <?php endforeach ?>
                    
                    <div class="form-group col-md-12">
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
    $(function(){
        $("#tpa").addClass('menu-top-active');
    });
</script>