<?php
    session_start();
    
    // Aktifkan error reporting sementara untuk debugging (sebaiknya dimatikan setelah produksi)
    error_reporting(E_ALL); 
    ini_set('display_errors', 1); 

    if(empty($_SESSION['id'])){
        header('location:login.php?error_login=1');
        exit();
    }
    
    // KUNCI PERBAIKAN: Gunakan require_once untuk memuat class database hanya sekali.
    require_once 'db/db_config.php'; 
    
    // Inisialisasi variabel POST untuk menghindari "Undefined variable" warnings
    // Variabel ini digunakan untuk mempertahankan input jika terjadi error
    $id_kriteria_post = $_POST['id_kriteria'] ?? ''; 
    $subkriteria_post = $_POST['subkriteria'] ?? '';
    $nilai_post = $_POST['nilai'] ?? '';
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
                    Form Sub Kriteria
                  </div>
                  <div class="panel-body">
                      <form method="post" action="insert_subkriteria.php" enctype="multipart/form-data">
                          <?php if (!empty($_GET['error_msg'])): ?>
                              <div class="alert alert-danger">
                                  <?= $_GET['error_msg']; ?>
                              </div>
                          <?php endif ?> 			          
                          <div class="form-group">
                              <label for="subkriteria">Nama Sub Kriteria</label>
                              <input required type="text" class="form-control" id="subkriteria" name="subkriteria" value="<?= htmlspecialchars($subkriteria_post) ?>">
                          </div>
                          <div class="form-group">
                                <label for="id_kriteria">Nama Kriteria</label>
                                <select required class="form-control" name="id_kriteria">
                                <?php  
                                // Query untuk mengisi dropdown Kriteria
                                // Dipastikan bahwa $db sudah tersedia karena require_once di atas
                                foreach ($db->select('*','kriteria')->get() as $val): 
                                ?> 
                                    <option value="<?= $val['id_kriteria']?>" 
                                            <?= ($id_kriteria_post == $val['id_kriteria']) ? 'selected' : '' ?>>
                                        <?= $val['kriteria'] ?>
                                    </option>
                                <?php endforeach ?>
                                </select>
                          </div>
                          <div class="form-group">
                              <label>Nilai</label>
                              <input required type="number" name="nilai" class="form-control " pattern="^[0-9\.\-\/]+$" value="<?= htmlspecialchars($nilai_post) ?>">
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

<?php include 'footer.php';?>
<script type="text/javascript">
    $(function(){
        $("#sk").addClass('menu-top-active');
    });
</script>