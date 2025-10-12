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
                    <h4 class="page-head-line">Hasil Keputusan SPK</h4>
                </div>
            </div>
            
            <?php if ($user_level == 'user'): ?>
                <div class="alert alert-warning">
                    Akses Anda terbatas hanya untuk melihat hasil akhir perankingan. Proses perhitungan detail dan tombol PROSES hanya dapat dilihat oleh Administrator.
                </div>
            <?php endif; ?>

            <div class="row">
                <h3>Tabel Hasil TPA</h3>
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama </th>
                                <?php foreach ($db->select('kriteria','kriteria')->get() as $k): ?>
                                <th>
                                    <?php
                                        $tmp = explode('_',$k['kriteria']);
                                        echo ucwords(implode(' ',$tmp));
                                    ?>
                                </th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($db->select('karyawan.nama,hasil_tpa.*','karyawan,hasil_tpa')->where('karyawan.id_calon_kr=hasil_tpa.id_calon_kr')->get() as $data):
                            ?>
                                <tr>
                                    <td><?= $data['nama']?></td>
                                    <?php foreach ($db->select('kriteria','kriteria')->get() as $td): ?>
                                    <td><?= $db->getnilaisubkriteria($data[$td['kriteria']])?></td>
                                    <?php endforeach ?>
                                </tr>
                            <?php
                                endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php if ($user_level == 'admin'): ?>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-primary btn-lg" onclick="tpl()">PROSES PERHITUNGAN</button>
                </div>
            </div>
            <?php endif; ?>
            
            <br>
            
            <?php if ($user_level == 'admin'): ?>
            <div id="proses_spk" style="display: none;">
                
                <div class="row">
                <h3>Normalisasi</h3>
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama </th>
                                <?php foreach ($db->select('kriteria','kriteria')->get() as $k): ?>
                                <th>
                                    <?php
                                        $tmp = explode('_',$k['kriteria']);
                                        echo ucwords(implode(' ',$tmp));
                                    ?>
                                </th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($db->select('karyawan.nama,hasil_tpa.*','karyawan,hasil_tpa')->where('karyawan.id_calon_kr=hasil_tpa.id_calon_kr')->get() as $data):
                            ?>
                                <tr>
                                    <td><?= $data['nama']?></td>
                                    <?php foreach ($db->select('kriteria','kriteria')->get() as $td): ?>
                                    <td><?= number_format($db->rumus($db->getnilaisubkriteria($data[$td['kriteria']]),$td['kriteria']),2);?></td>
                                    <?php endforeach ?>
                                </tr>
                            <?php
                                endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
                </div>
            <?php endif; // END ADMIN BLOCK UNTUK NORMALISASI ?>

            <div class="row">
                <h3>Proses Penentuan (Hasil Akhir)</h3>
                <div class="table-responsive">
                    <table id="example3" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama </th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($db->select('karyawan.id_calon_kr,karyawan.nama,hasil_tpa.*','karyawan,hasil_tpa')->where('karyawan.id_calon_kr=hasil_tpa.id_calon_kr')->get() as $data):
                            ?>
                                <tr>
                                    <td><?= $data['nama']?></td>
                                    <td>
                                    <?php 
                                        $hasil = [];
                                        $bulan = date('m'); 
                                        $tahun = date('Y'); 
                                        $tanggal = date('Y-m-d');
                                        $minggu = $db->weekOfMonth($tanggal);

                                        foreach($db->select('kriteria','kriteria')->get() as $dt){
                                            array_push($hasil,$db->rumus($db->getnilaisubkriteria($data[$dt['kriteria']]),$dt['kriteria'])*$db->bobot($dt['kriteria']));
                                        }
                                        echo $h = number_format(array_sum($hasil),2);
                                        
                                        // KRITIS: LOGIKA INSERT/UPDATE HANYA BERJALAN JIKA USER ADALAH ADMIN
                                        if ($user_level == 'admin') {
                                            if($db->select('id_calon_kr','hasil_spk')->where("id_calon_kr='$data[id_calon_kr]' and minggu='$minggu' and bulan='$bulan' and tahun='$tahun'")->count() == 0){
                                                // INSERT
                                                $db->insert('hasil_spk',"'','$data[id_calon_kr]','$h','$minggu','$bulan','$tahun'")->count();
                                            } else {
                                                // UPDATE
                                                $db->update('hasil_spk',"hasil_spk='$h',minggu='$minggu',bulan='$bulan',tahun='$tahun'")->where("id_calon_kr='$data[id_calon_kr]' and minggu='$minggu' and bulan='$bulan' and tahun='$tahun'")->count();
                                            }
                                        }
                                    ?>
                                    </td>
                                </tr>
                            <?php
                                endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <h3>Perankingan</h3>
                <div class="table-responsive">
                    <table id="example4" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Hasil </th>
                                <?php $no = 1; foreach ($db->select('kriteria','kriteria')->get() as $th): ?>
                                <th>K<?= $no?></th>
                                <?php $no++; endforeach ?>
                                <th rowspan="2" style="padding-bottom:25px">Hasil</th>
                                <th rowspan="2" style="padding-bottom:25px">Ranking</th>
                            </tr>
                            <tr>
                                <th>Bobot </th>
                                <?php foreach ($db->select('bobot','kriteria')->get() as $th): ?>
                                <th><?= $th['bobot']?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $bulan = date('m'); 
                                $tahun = date('Y'); 
                                $tanggal = date('Y-m-d');
                                $minggu = $db->weekOfMonth($tanggal);
                                
                                // Query perankingan dipertahankan agar mengambil data terbaru dari hasil_spk
                                $query_ranking = $db->select('distinct(karyawan.nama),hasil_tpa.*,hasil_spk.*','karyawan,hasil_tpa,hasil_spk')
                                                     ->where('karyawan.id_calon_kr=hasil_tpa.id_calon_kr and karyawan.id_calon_kr=hasil_spk.id_calon_kr and hasil_spk.minggu='.$minggu.' and hasil_spk.bulan='.$bulan.' and hasil_spk.tahun='.$tahun.'')
                                                     ->order_by('hasil_spk.hasil_spk','desc')
                                                     ->get();
                                
                                foreach ($query_ranking as $data):
                            ?>
                                <tr>
                                    <td><?= $data['nama']?></td>
                                    <?php foreach ($db->select('kriteria','kriteria')->get() as $td): ?>
                                    <td><?= number_format($db->rumus($db->getnilaisubkriteria($data[$td['kriteria']]),$td['kriteria']),2);?></td>
                                    <?php endforeach ?>
                                    <td>
                                    <?php 
                                        $hasil = [];
                                        foreach($db->select('kriteria','kriteria')->get() as $dt){
                                            array_push($hasil,$db->rumus($db->getnilaisubkriteria($data[$dt['kriteria']]),$dt['kriteria'])*$db->bobot($dt['kriteria']));
                                        }
                                        echo $r = number_format(array_sum($hasil),2);
                                    ?>
                                    </td>
                                    <td><?= $no?></td>
                                </tr>
                            <?php
                                $no++;
                                endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>     
            <?php if ($user_level == 'admin'): ?>
            </div> <?php endif; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
<script type="text/javascript">
    $(function(){
        $("#proses").addClass('menu-top-active');
    });
    
    // Fungsi tpl() kini dikunci agar hanya berfungsi jika admin
    function tpl(){
        if ('<?php echo $user_level; ?>' === 'admin') {
             $("#proses_spk").show(); 
        }
    }
</script>
<script type="text/javascript">
    $(function() {
        // DataTable diinisialisasi hanya jika user adalah admin dan tabelnya terlihat,
        // atau jika Anda ingin menginisialisasi tabel-tabel yang selalu terlihat.
        $('#example1').dataTable();
        $('#example4').dataTable();
    });
</script>