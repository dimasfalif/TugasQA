<?php
    session_start();
    error_reporting(E_ALL); // Tampilkan semua error PHP
    ini_set('display_errors', 1);
    
    // 1. Cek apakah user sudah login
    if(empty($_SESSION['id'])){
        header('location:login.php?error_login=1');
        exit();
    }
    
    // 2. Ambil level user untuk kontrol tampilan dan aksi
    // Diasumsikan objek $db sudah diinisialisasi di salah satu file include (misalnya header.php atau menu.php)
    $user_level = $_SESSION['level'] ?? 'user';
    
    // Debug: Tampilkan user level
    // echo "Debug: User Level = " . $user_level . "<br>";
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
                <h3>Tabel Hasil TPA (Data Input)</h3>
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
                    <button class="btn btn-primary btn-lg" onclick="tpl()">PROSES PERHITUNGAN DETAIL</button>
                    <hr>
                </div>
            </div>
            <?php endif; ?>
            
            <br>
            
            <?php if ($user_level == 'admin'): ?>
            <div id="proses_spk" style="display: none;">
                
                <div class="row">
                    <h3>Normalisasi (R)</h3>
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
                                        <td>
                                            <?php 
                                                // Cek ketersediaan fungsi untuk menghindari Fatal Error
                                                if (method_exists($db, 'rumus') && method_exists($db, 'getnilaisubkriteria')) {
                                                    echo number_format($db->rumus($db->getnilaisubkriteria($data[$td['kriteria']]),$td['kriteria']),2);
                                                } else {
                                                    echo "Error: Fungsi DB tidak ditemukan";
                                                }
                                            ?>
                                        </td>
                                        <?php endforeach ?>
                                    </tr>
                                <?php
                                    endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <h3>Proses Penentuan (Nilai V)</h3>
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
                                            // --- LOGIKA PERHITUNGAN HASIL AKHIR (V) ---
                                            $hasil = [];
                                            $bulan = date('m'); 
                                            $tahun = date('Y'); 
                                            $tanggal = date('Y-m-d');
                                            $minggu = $db->weekOfMonth($tanggal);

                                            foreach($db->select('kriteria','kriteria')->get() as $dt){
                                                // Rumus: V = Sum(Normalisasi * Bobot Kriteria)
                                                array_push($hasil, $db->rumus($db->getnilaisubkriteria($data[$dt['kriteria']]), $dt['kriteria']) * $db->bobot($dt['kriteria']));
                                            }
                                            $h = number_format(array_sum($hasil), 2);
                                            echo $h; // Tampilkan hasil V

                                            // --- LOGIKA INSERT/UPDATE HANYA BERJALAN JIKA USER ADALAH ADMIN ---
                                            // Jika level admin, simpan hasil perhitungan ke database
                                            if ($user_level == 'admin') {
                                                if($db->select('id_calon_kr','hasil_spk')->where("id_calon_kr='$data[id_calon_kr]' and minggu='$minggu' and bulan='$bulan' and tahun='$tahun'")->count() == 0){
                                                    // INSERT
                                                    // Kolom tabel: id_calon_kr, hasil_spk, minggu, bulan, tahun (id_spk auto_increment)
                                                    $columns = 'id_calon_kr,hasil_spk,minggu,bulan,tahun';
                                                    $values = [$data['id_calon_kr'], $h, $minggu, $bulan, $tahun];
                                                    $db->insert('hasil_spk', $columns);
                                                    $db->execute_dml($values);
                                                } else {
                                                    // UPDATE
                                                    $db->update('hasil_spk',"hasil_spk='$h',minggu='$minggu',bulan='$bulan',tahun='$tahun'")->where("id_calon_kr='$data[id_calon_kr]' and minggu='$minggu' and bulan='$bulan' and tahun='$tahun'")->execute_dml();
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
            </div> <?php endif; // END ADMIN BLOCK UNTUK NORMALISASI dan PENENTUAN V ?>
            
            <hr>

            <div class="row">
                <h3>Perankingan Akhir</h3>
                <div class="table-responsive">
                    <table id="example4" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama </th>
                                <?php $no = 1; foreach ($db->select('kriteria','kriteria')->get() as $th): ?>
                                <th>K<?= $no?></th>
                                <?php $no++; endforeach ?>
                                <th rowspan="2" style="padding-bottom:25px">Nilai V</th>
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
                                
                                // Query perankingan: mengambil data hasil_spk yang sudah disimpan, diurutkan DESC
                                $query_ranking = $db->select('distinct(karyawan.nama),hasil_tpa.*,hasil_spk.hasil_spk','karyawan,hasil_tpa,hasil_spk')
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
                                    <?= number_format($data['hasil_spk'], 2) ?>
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
        </div>
    </div>
<?php include 'footer.php'; ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#proses").addClass('menu-top-active');

        // Inisialisasi DataTable untuk tabel yang selalu terlihat
        $('#example1').dataTable(); 
        $('#example4').dataTable(); 

        // Inisialisasi DataTable untuk tabel yang tersembunyi
        // Penting: Inisialisasi tabel tersembunyi harus dilakukan setelah tabel ditampilkan (dalam fungsi tpl), 
        // atau jika diinisialisasi di sini, harus menggunakan opsi yang tepat.
        // Untuk amannya, kita panggil ulang DataTable di tpl()
    });
    
    // Fungsi tpl() untuk menampilkan detail perhitungan (Hanya untuk admin)
    function tpl(){
        console.log('Fungsi tpl() dipanggil. Menampilkan detail perhitungan SPK.'); 
        
        // Cek apakah user adalah admin
        if ('<?php echo $user_level; ?>' === 'admin') {
            // Tampilkan div proses_spk
            $("#proses_spk").show(500, function() {
                // Inisialisasi DataTable setelah div ditampilkan sepenuhnya untuk menghindari error lebar kolom
                $('#example2').dataTable(); 
                $('#example3').dataTable();
            });
            
            // Opsional: Sematkan pesan sukses setelah proses
            // alert('Proses perhitungan telah selesai dan hasil V sudah disimpan di database.');
        } else {
            alert('Akses ditolak: Anda bukan Administrator.');
        }
    }
</script>