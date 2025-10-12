<?php
    class database extends PDO{
        // Deklarasikan semua properti yang akan digunakan
        protected $dsn = 'mysql:host=localhost;dbname=db_spk';
        protected $dsu = 'root';
        protected $dsp = '';
        private $cmd = '';

        // Hapus properti private $db. Koneksi sekarang adalah objek $this itu sendiri.

        function __construct(){
            try {
                // PERBAIKAN KRITIS #1: Memanggil constructor induk PDO.
                // Ini menghilangkan Fatal Error "PDO object is not initialized".
                parent::__construct($this->dsn, $this->dsu, $this->dsp);
                
                // Atur error mode untuk debugging yang lebih baik
                $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                // Jika koneksi gagal, hentikan program dan tampilkan pesan
                die("Koneksi database gagal: " . $e->getMessage()); 
            }
        }

        // --- Core Query Builder ---
        function select($c,$t){ $this->cmd = "select $c from $t"; return $this; }
        function insert($t,$v){ $this->cmd = "insert into $t values($v)"; return $this; }
        function update($t,$v){ $this->cmd = "update $t set $v"; return $this; }
        function truncate($table){ $this->cmd = "truncate $table"; return $this; }
        function delete($t){ $this->cmd = "delete from $t"; return $this; }
        function alter($t,$act,$c,$format){ $this->cmd = "alter table $t $act $c $format"; return $this; }

        // --- Additional Query ---
        function where($params){ $this->cmd .= " where $params"; return $this; }
        function group_by($c){ $this->cmd .= " group by $c"; return $this; }
        function order_by($c,$t){ $this->cmd .= " order by $c $t"; return $this; }
        function like($c){ $this->cmd .= " like '%$c%'"; return $this; }

        // --- Executable Methods ---
        function get(){
            // PERBAIKAN KRITIS #2: Gunakan $this->prepare() (bukan $this->db->prepare())
            $q = $this->prepare($this->cmd);
            $q->execute();
            return $q->fetchAll();
        }
        function count(){
            // PERBAIKAN KRITIS #3: Gunakan $this->prepare()
            $q = $this->prepare($this->cmd);
            $q->execute();
            return $q->rowCount(); 
        }

        // --- Sisanya (Metode Khusus SPK) ---
        function rumus($data,$kemampuan){ 
            foreach($this->select('type','kriteria')->where("kriteria='$kemampuan'")->get() as $crt){
                // Kode rumus Anda...
                if($crt[0]=='Benefit'){
                    foreach ($this->select("max(sub_kriteria.nilai)",'hasil_tpa,sub_kriteria')->where('hasil_tpa.'.$kemampuan.'=sub_kriteria.id_subkriteria')->get() as $nm) {
                        $nilai_max = $nm[0];
                    }
                    return $rumus = $data / $nilai_max;
                } else {
                    foreach ($this->select("min(sub_kriteria.nilai)",'hasil_tpa,sub_kriteria')->where('hasil_tpa.'.$kemampuan.'=sub_kriteria.id_subkriteria')->get() as $nm) {
                        $nilai_min = $nm[0];
                    }
                    return $rumus = $nilai_min / $data;
                }
            } 
        }
        function bobot($kemampuan){ 
            foreach ($this->select('bobot','kriteria')->where("kriteria='$kemampuan'")->get() as $bb) { return $bb[0]; } 
        }
        function totaladmin(){ 
            foreach ($this->select('count(*)','admin')->get() as $bb) { return $bb[0]; } 
        }
        function totalkaryawan(){ 
            foreach ($this->select('count(*)','karyawan')->get() as $bb) { return $bb[0]; } 
        }
        function totalkriteria(){ 
            foreach ($this->select('count(*)','kriteria')->get() as $bb) { return $bb[0]; } 
        }
        function totalsubkriteria(){ 
            foreach ($this->select('count(*)','sub_kriteria')->get() as $bb) { return $bb[0]; } 
        }
        function getnamesubkriteria($subkriteria){ 
            foreach ($this->select('subkriteria','sub_kriteria')->where("id_subkriteria='$subkriteria'")->get() as $value) { return $value[0]; } 
        }
        function getnilaisubkriteria($subkriteria){ 
            foreach ($this->select('nilai','sub_kriteria')->where("id_subkriteria='$subkriteria'")->get() as $value) { return $value[0]; } 
        }
        function weekOfMonth($qDate) {
            $dt = strtotime($qDate);
            $day = date('j',$dt);
            $month = date('m',$dt);
            $year = date('Y',$dt);
            $totalDays = date('t',$dt);
            $weekCnt = 1;
            $retWeek = 0;
            for($i=1;$i<=$totalDays;$i++) {
                $curDay = date("N", mktime(0,0,0,$month,$i,$year));
                if($curDay==7) {
                    if($i==$day) { $retWeek = $weekCnt+1; }
                    $weekCnt++;
                } else {
                    if($i==$day) { $retWeek = $weekCnt; }
                }
            }
            return $retWeek;
        }
    }
    
    // Inisialisasi objek database yang sekarang merupakan objek PDO yang valid
    $db = new database();
?>