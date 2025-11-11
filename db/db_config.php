<?php
    class database extends PDO{
        // Deklarasikan semua properti yang akan digunakan
        protected $dsn = 'mysql:host=localhost;dbname=db_spk';
        protected $dsu = 'root';
        protected $dsp = '';
        private $cmd = '';

        function __construct(){
            try {
                parent::__construct($this->dsn, $this->dsu, $this->dsp);
                $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Koneksi database gagal: " . $e->getMessage()); 
            }
        }

        // --- Core Query Builder ---
        function select($c,$t){ $this->cmd = "select $c from $t"; return $this; }
        
        // ** Metode insert() yang aman: Menerima daftar kolom dan membuat placeholders '?' **
        function insert($t, $columns){ 
            $col_count = count(explode(',', $columns));
            $placeholders = implode(',', array_fill(0, $col_count, '?'));
            $this->cmd = "INSERT INTO $t ($columns) VALUES ($placeholders)"; 
            return $this; 
        }
        
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
            $q = $this->prepare($this->cmd);
            $q->execute();
            return $q->fetchAll();
        }
        
        // Metode count() - HANYA untuk SELECT
        function count(){
            $q = $this->prepare($this->cmd); 
            $q->execute();
            return $q->rowCount(); 
        }
        
        // ** Metode eksekusi DML (INSERT/UPDATE/DELETE) yang aman **
        function execute_dml($values = []){
            $q = $this->prepare($this->cmd);
            $q->execute($values); // Mengikat nilai ke placeholders '?'
            return $q->rowCount(); 
        }

        // (Semua fungsi lain seperti rumus, bobot, totaladmin, dll. tetap sama)
        // ... (Fungsi-fungsi lain yang Anda miliki) ...
        
        function rumus($data,$kemampuan){ 
            foreach($this->select('type','kriteria')->where("kriteria='$kemampuan'")->get() as $crt){
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
    
    $db = new database();
