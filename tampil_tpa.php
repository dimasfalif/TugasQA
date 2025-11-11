<?php
    require_once 'db/db_config.php';
    session_start();
    error_reporting(0);
    
    // 1. Cek apakah user sudah login
    if(empty($_SESSION['id'])){
        header('location:login.php?error_login=1');
        exit();
    }
    
    // 2. Ambil level user
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
                                
                                <?php 
                                // Ambil daftar kriteria untuk HEADER KOLOM
                                $kriteria_list = $db->select('kriteria','kriteria')->get();
                                foreach ($kriteria_list as $kr ): 
                                ?>
                                <th><?= $kr['kriteria']?></th>
                                <?php endforeach ?>
                                
                                <?php if ($user_level == 'admin'): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no=1; 
                            
                            // KOREKSI KRITIS: Ambil data langsung dari hasil_tpa
                            $hasil_tpa_data = $db->select('*','hasil_tpa')->get();
                            
                            foreach($hasil_tpa_data as $data): 
                                
                                // Lookup Nama Karyawan di dalam loop
                                $karyawan_info = $db->select('nama','karyawan')->where('id_calon_kr='.$data['id_calon_kr'])->get();
                                $nama_karyawan = $karyawan_info[0]['nama'] ?? 'N/A'; // Jika nama tidak ditemukan
                            ?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= $nama_karyawan ?></td>
                                
                                <?php 
                                // Loop untuk mengisi setiap kolom kriteria
                                foreach ($kriteria_list as $k): 
                                ?>
                                <td>
                                    <?php 
                                    // Mengamankan pengambilan data dengan ?? (null coalescing)
                                    $kriteria_key = $k['kriteria'];
                                    
                                    // Mengambil ID subkriteria dari array hasil_tpa. 
                                    // Jika kolom tidak ada atau NULL, berikan nilai 0.
                                    $id_subkriteria = $data[$kriteria_key] ?? 0;
                                    
                                    // Jika ID subkriteria valid (bukan 0 atau null), tampilkan namanya
                                    if ($id_subkriteria > 0) {
                                        echo $db->getnamesubkriteria($id_subkriteria);
                                        if ($user_level == 'admin'): 
                                            echo " (Nilai = " . $db->getnilaisubkriteria($id_subkriteria) . ")";
                                        endif; 
                                    } else {
                                        echo '-'; // Tampilkan '-' jika nilai kosong/tidak valid
                                    }
                                    ?>
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