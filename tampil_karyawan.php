<?php
    session_start();
    error_reporting(0);
    
    // --- PERBAIKAN CEK SESI ---
    // 1. Cek apakah user sudah login
    if(empty($_SESSION['id'])){
        header('location:login.php?error_login=1');
        exit();
    }
    
    // 2. Tentukan level user untuk kontrol tampilan
    $user_level = $_SESSION['level'] ?? 'user';
    
    // Fungsi hitung_lama_bergabung dipindahkan di atas agar tidak ada masalah saat include
    function hitung_lama_bergabung($tgl_lahir)
    {
        $today = date('Y-m-d');
        $now = time();
        list($thn, $bln, $tgl) = explode('-',$tgl_lahir);
        // Diasumsikan TglBergabung di database adalah TGL LAHIR, namun namanya TglBergabung.
        // Fungsi ini menghitung usia berdasarkan tanggal lahir. Karena Anda menggunakan
        // TglBergabung untuk menghitung lama bergabung, kita anggap itu adalah tanggal bergabung.
        $time_lahir = mktime(0,0,0,$bln, $tgl, $thn);

        $selisih = $now - $time_lahir;

        // Perhitungan ini adalah untuk usia/lama, hasilnya tetap 'tahun' dan 'bulan'
        $tahun = floor($selisih/(60*60*24*365));
        // Perbaikan kecil pada perhitungan bulan agar lebih akurat:
        $bulan_sisa = floor(($selisih % (60*60*24*365)) / (60*60*24 * 30.4375)); // Rata-rata hari per bulan
        
        return $tahun.' tahun '.$bulan_sisa.' bulan';
    }
?>
<?php include 'header.php';?>
<?php include 'menu.php';?>
<div class="content-wrapper">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Master Data Karyawan (View Only: <?php echo ($user_level == 'admin' ? 'No' : 'Yes') ?>)</h4>
                    
                    <?php if (isset($_GET['success_msg']) && $_GET['success_msg'] == 'delete_success'): ?>
                        <div class="alert alert-success">
                            Data karyawan berhasil dihapus.
                        </div>
                    <?php endif ?>

                    <?php if (isset($_GET['error_msg'])): ?>
                        <div class="alert alert-danger">
                            <?php if ($_GET['error_msg'] == 'error_delete'): ?>
                                Gagal menghapus data karyawan.
                            <?php elseif ($_GET['error_msg'] == 'masih_ada_data_terkait'): ?>
                                Tidak dapat menghapus karyawan ini karena masih terdapat data terkait di hasil penilaian.
                            <?php elseif ($_GET['error_msg'] == 'id_tidak_valid'): ?>
                                ID karyawan tidak valid.
                            <?php else: ?>
                                Terjadi kesalahan.
                            <?php endif; ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
            <div class="row">
                <?php if ($user_level == 'admin'): ?>
                    <div><a href="input_karyawan.php" class="btn btn-info">Tambah Data</a></div>
                <?php else: ?>
                    <?php endif; ?>
                <br>
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Foto</th>
                                <th>Tempat lahir</th>
                                <th>Tanggal lahir</th>
                                <th>Pendidikan</th>
                                <th>Jabatan</th>
                                <th>Tgl Bergabung</th>
                                <th>Lama Bergabung</th>
                                <?php if ($user_level == 'admin'): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($db->select('*','karyawan')->get() as $data): ?>
                            <tr>
                                <td><?= $data['NIK'];?></td>
                                <td><?= $data['nama']?></td>
                                <td><?= $data['jeniskelamin']?></td>
                                <td><?= $data['alamat']?></td>
                                <td><?= $data['telepon']?></td>
                                <td>
                                    <?php if($data['foto'] != ""): ?>
                                        <img src="assets/foto_calon_karyawan/<?php echo $data['foto']; ?>" class="img-thumbnail" width="75px" height="70px">
                                        <?php else: ?>
                                        <img src="assets/foto_calon_karyawan/image_not_available.jpg" class="img-thumbnail" width="75px" height="70px">
                                    <?php endif; ?>
                                </td>
                                <td><?= $data['TempatLahir']?></td>
                                <td><?= $data['ttl']?></td>
                                <td><?= $data['PendidikanTerakhir']?></td>
                                <td><?= $data['Jabatan']?></td>
                                <td><?= $data['TglBergabung']?></td>
                                <td><?php echo hitung_lama_bergabung($data['TglBergabung'])?></td>
                                
                                <?php if ($user_level == 'admin'): ?>
                                    <td>
                                        <a class="btn btn-warning" href="edit_karyawan.php?id=<?php echo $data[0]?>">Edit</a>
                                        <a class="btn btn-danger"  onclick="return confirm('Yakin Hapus?')" href="delete_karyawan.php?id=<?php echo $data[0]?>">Hapus</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php $no++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php include 'footer.php'; ?>
<script type="text/javascript">
    $(function(){
        $("#ck").addClass('menu-top-active');
    });
</script>
<script type="text/javascript">
    $(function() {
        $('#example1').dataTable();
    });
</script>