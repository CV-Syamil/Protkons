<?php
function css($css){ foreach ($css as $v) { echo '<link rel="stylesheet" href="'.base_url($v).'.css">'; } }
function js($js){ foreach ($js as $v) { echo '<script src="'.base_url($v).'.js"></script>'; } }
function dd($x){print_r($x); exit();}
function CI(){ $ci =& get_instance(); return $ci; }
function numb($n,$nk=0,$s1=',',$s2='.'){
	return number_format(doubleval($n),$nk,$s1,$s2);
}
function umur_th_bl($tgl1,$tgl2=""){
	$d=0; $m=0; $y=0;
	$diff = null;
	$tgl1 = new DateTime($tgl1);
	$tgl2 = new DateTime(empty($tgl2)?'today':$tgl2);
	if($tgl1 <= $tgl2){ $diff = $tgl2->diff($tgl1); $d = $diff->d; $m = $diff->m; $y = $diff->y; }
	return json_decode(json_encode(compact('d','m','y','diff')));
}
function back_link($default_link='#'){
	$target = @$_SERVER['HTTP_REFERER'];
	return empty($target)?$default_link:$target;
}
function e404(){
	CI()->load->view('errors/html/error_404',[
		'heading' => '404 Page Not Found',
		'message' => '<p>The page you requested was not found.<p>'
	]);
}
function pwd_enc($s){
	return password_hash($s, PASSWORD_DEFAULT);
}
function ci_session_name(){return "kbri_kl";}
function getSession($s="",$d=""){
	if(CI()->session->has_userdata(ci_session_name())){
		$session = CI()->session->userdata(ci_session_name());
		return (empty($s)?$session:(empty($session[$s])?$d:$session[$s]));
	}else return "";
}
function setSession($s){
	CI()->session->set_userdata(ci_session_name(),$s);
}
function destroySession(){
	// CI()->session->set_userdata(ci_session_name(),'');
	CI()->session->sess_destroy();
}
function getFlash($x=""){
	$sf = CI()->session->flashdata();
	return (empty($x))?$sf:@$sf[$x];
}
function setFlash($k,$v){
	CI()->session->set_flashdata($k,$v);
}

function timeCode(){
	return date('YmdHis').str_replace([' ','.'],'',time());
}
function dateDiff($d1,$d2,$abs=FALSE){ return date_diff(date_create($d1),date_create($d2),$abs); }
function bulan_id($k=0){
	$bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
	return empty($k)?$bulan:(empty($bulan[$k-1])?'UNKNOWN':$bulan[$k-1]);
}
function hari_id($d){
	$d = strtolower($d);
	$hari = ['sun'=>'minggu','mon'=>'senin','tue'=>'selasa','wed'=>'rabu','thu'=>'kamis','fri'=>'jumat','sat'=>'sabtu'];
	return (empty($d)?$hari:@$hari[$d]);
}
function convert_tgl_indo($x){
	$hari = ["Saturday"=>"Sabtu", "Sunday"=>"Minggu", "Monday"=>"Senin", "Tuesday"=>"Selasa", "Wednesday"=>"Rabu", "Thursday"=>"Kamis", "Friday"=>"Jum`at"];
	$bulan = ["November"=>'November', "December"=>'Desember', "January"=>'Januari', "February"=>'Februari', "March"=>'Maret', "April"=>'April', "May"=>'Mei', "June"=>'Juni', "July"=>'Juli', "August"=>'Agustus', "September"=>'September', "October"=>'Oktober'];
	foreach($hari as $h_en => $h_id){$x = str_replace($h_en,$h_id,$x);}
	foreach($bulan as $bl_en => $bl_id){$x = str_replace($bl_en,$bl_id,$x);}
	return $x;
}
function setTxt($file,$txt){
	$f = FCPATH."assets/txt/".$file.'.txt';
	$r = fopen($f,'w');
		fwrite($r,$txt);
		fclose($r);
}
function upload($inp,$nama='',$lokasi='assets/images',$tipe='gif|jpg|png',$over=true){
	$ci =& get_instance();
	$config['upload_path'] = FCPATH.$lokasi;
	$config['allowed_types'] = $tipe;
	$config['overwrite'] = $over;
	if(!empty($nama)){$config['file_name'] = $nama;}
	$ci->upload->initialize($config);
	return $ci->upload->do_upload($inp);
}
function pagination_set($base,$rows,$perpage,$configs=[]){
	$config=[
		'base_url' => site_url($base),
		'total_rows' => $rows,
    	'num_links' => 2,
		'per_page' => $perpage,
    	'first_link' => '<<',
    	'last_link' => '>>',
    	'use_page_numbers' => TRUE,
    	'cur_tag_open' => '<li class="active"><a class="bg-brown" href="javascript:void(0)">',
    	'cur_tag_close' => '</a></li>',
    	'next_tag_open' => '<li>',
    	'next_tag_close' => '</li>',
    	'last_tag_open' => '<li>',
    	'last_tag_close' => '</li>',
    	'first_tag_open' => '<li>',
    	'first_tag_close' => '</li>',
    	'prev_tag_open' => '<li>',
    	'prev_tag_close' => '</li>',
    	'num_tag_open' => '<li>',
    	'num_tag_close' => '</li>',
    	'prefix' => 'page-',
    	'suffix' => (empty($_GET)?'':'?'.http_build_query($_GET))
    ];
    foreach ($configs as $key => $value) {$config[$key] = $value;}
	CI()->pagination->initialize($config);
}
function link_pagination(){
	return '<ul class="pagination m-t-30">'.CI()->pagination->create_links().'</ul>';
}
function penyebut($nilai) {
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " ". $huruf[$nilai];
	} else if ($nilai <20) {
		$temp = penyebut($nilai - 10). " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
	}     
	return $temp;
}
function penyebut2($nilai,$first=false) {
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = ($first)?"ke":'';
	if ($nilai == 1) {
		$temp = ($first)?"Pertama":'satu';
	} else if ($nilai < 12) {
		$temp .= "". $huruf[$nilai];
	} else if ($nilai <20) {
		$temp .= penyebut($nilai - 10). " belas";
	} else if ($nilai < 100) {
		$temp .= penyebut($nilai/10)." puluh". penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp .= " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp .= penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp .= " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp .= penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp .= penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp .= penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp .= penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
	}     
	return $temp;
}

function agama(){
	return ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghuchu'];
}
function hari_indo($n=''){
	$hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);
	return empty($n)?$hari:@$hari[$n];
}
function tanggal_indo($tanggal, $cetak_hari = false){
	$hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);

	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$tanggal = date('Y-m-d', strtotime($tanggal));
	$split 	  = explode('-', $tanggal);
	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}
function user_akses($key=""){
	$accs = ['loket'=> 'Petugas Loket','verifikasi'=>'Petugas Verifikasi','hs'=>'Pejabat Penanda Tangan (HS)','kasir'=>'Petugas Kasir','admin'=>'Administrator'];
	return ((empty($key)?$accs:@$accs[$key]));
}
function status_layanan($status="all",$badge=FALSE){
	$sts = [
		0 => 'Draft',
		1 => 'Pengajuan',
		2 => 'Terverifikasi',
		3 => 'Pengambilan Dokumen',
		4 => 'Dokumen Diambil',
		5 => 'TerArsip',
		91 => 'Tolak Verifikasi',
		92 => 'Tolak Penanda Tangan'
	];
	if($status=='all'){
		return $sts;
	}else{
		$status = intval($status);
		$ret = @$sts[$status];
		if($badge){
			$bg='';
			switch ($status) {
				case 0: $bg='bg-secondary'; break;
				case 1: $bg='bg-warning'; break;
				case 2: $bg='bg-indigo'; break;
				case 3: $bg='bg-primary'; break;
				case 4: $bg='bg-teal'; break;
				case 5: $bg='bg-green'; break;
				case 91: case 92: $bg='bg-red'; break;
			}
			return '<span class="badge '.$bg.'">'.$ret.'</span>';
		}
		return $ret;

	}
}
function link_file($path,$prefix="File : ",$limit=16){
	$link = base_url($path);
	$exp = explode('/', $path);
	$name = end($exp);
	if($limit>0){$name=ellipsize($name,$limit,0.5);}
	return '<a href="'.$link.'" target="_blank">'.$prefix.$name.' </a>';
}
function link_file_upload($path,$name=""){
	$mime = mime_content_type(FCPATH.$path);
	$mime = strtolower(str_replace('image/', '', $mime));
	$link = base_url($path);
	if(empty($name)){
		$exp = explode('/', $path);
		$name = end($exp);
	}
	if(in_array($mime, ['png','jpeg','jpg','gif'])){
		return '<a href="'.$link.'" target="_blank"><img class="img-responsive" src="'.$link.'" alt="'.$name.'"></a>';
	}else{
		return '<a href="'.$link.'" target="_blank">File: '.$name.' </a>';
	}
}
function phpword_auto_items($v=[]){
	$items = [
		'no_register'=>['label'=>'No Register (auto increament) reset setiap tahun', 'value'=>''],
		'no_register_2'=>['label'=>'No Register 2 digit angka (auto increament) reset setiap tahun. ex: 01', 'value'=>''],
		'no_register_3'=>['label'=>'No Register 3 digit angka (auto increament) reset setiap tahun. ex: 001', 'value'=>''],
		'no_register_4'=>['label'=>'No Register 4 digit angka (auto increament) reset setiap tahun. ex: 0001', 'value'=>''],
		'no_register_5'=>['label'=>'No Register 5 digit angka (auto increament) reset setiap tahun. ex: 00001', 'value'=>''],
		'no_register_6'=>['label'=>'No Register 6 digit angka (auto increament) reset setiap tahun. ex: 000001', 'value'=>''],
		'kode_tr'=>['label'=>'Kode Transaksi Pelayanan ex: TRL202109230539201632368360', 'value'=>''],
		'date_tr_short'=>['label' => 'Tanggal Pelayanan (pendek). ex: 03-09-2021', 'value'=>date('d-m-Y')],
		'date_tr_long'=>['label' => 'Tanggal Pelayanan (panjang). ex: 03 September 2021', 'value'=>tanggal_indo(date('Y-m-d'))],
		'date_tr_day'=>['label' => 'Tanggal Pelayanan dengan hari. ex: Selasa, 03 September 2021', 'value'=>tanggal_indo(date('Y-m-d'),TRUE)],
		'day_tr'=>['label' => 'Hari Pelayanan. ex: Selasa', 'value'=>hari_indo(date('N'))],
		'nip_hs'=>['label'=>'NIP Petugas HS', 'value'=>''],
		'nama_hs'=>['label'=>'Nama Petugas HS', 'value'=>''],
		'kode_report_hs'=>['label'=>'Kode Report HS', 'value'=>''],
		'jabatan_hs'=>['label'=>'Jabatan Petugas HS', 'value'=>''],
		'jabatan_hs_en'=>['label'=>'Jabatan Petugas HS (ENGLISH)', 'value'=>''],
		'no_identitas_pelapor'=>['label'=>'No. Identitas Pelapor', 'value'=>''],
		'jenis_identitas_pelapor'=>['label'=>'Jenis Identitas Pelapor', 'value'=>''],
		'nama_pelapor'=>['label'=>'Nama Pelapor', 'value'=>''],
		'jk_pelapor'=>['label'=>'Jenis Kelamin Pelapor', 'value'=>''],
		'tempat_lahir_pelapor'=>['label'=>'Tempat Lahir Pelapor', 'value'=>''],
		'tgl_lahir_pelapor'=>['label'=>'Tanggal Lahir Pelapor ex: 20 Oktober 1945', 'value'=>''],
		'umur_pelapor'=>['label'=>'Umur tahun & bulan ex: 2 Tahun 10 Bulan', 'value'=>''],
		'agama_pelapor'=>['label'=>'Agama Pelapor', 'value'=>''],
		'pekerjaan_pelapor'=>['label'=>'Pekerjaan Pelapor', 'value'=>''],
		'kewarganegaraan_pelapor'=>['label'=>'Kewarganegaraan Pelapor', 'value'=>''],
		'provinsi_pelapor'=>['label'=>'Provinsi Pelapor', 'value'=>''],
		'kota_pelapor'=>['label'=>'Kota / Kabupaten Pelapor', 'value'=>''],
		'kecamatan_pelapor'=>['label'=>'Kecamatan Pelapor', 'value'=>''],
		'desa_pelapor'=>['label'=>'Desa Pelapor', 'value'=>''],
		'alamat_idn_pelapor'=>['label'=>'Alamat Indonesia Pelapor', 'value'=>''],
		'negeri_pelapor'=>['label'=>'Negeri Pelapor', 'value'=>''],
		'daerah_pelapor'=>['label'=>'Daerah Pelapor', 'value'=>''],
		'distrik_pelapor'=>['label'=>'Distrik Pelapor', 'value'=>''],
		'alamat_mys_pelapor'=>['label'=>'Alamat Malaysia Pelapor', 'value'=>''],
		'th_num_long'=>['label' => 'Angka Tahun Sekarang (Panjang). ex: 2021', 'value'=>date('Y')],
		'th_num_short'=>['label' => 'Angka Tahun Sekarang (Pendek). ex: 21', 'value'=>date('y')],
		'bl_num_long'=>['label' => 'Angka Bulan Sekarang huruf. ex: Maret', 'value'=>bulan_id(date('m'))],
		'bl_num_long_en'=>['label' => 'Angka Bulan Sekarang huruf dalam bahasa inggris. ex: December', 'value'=>date('F')],
		'bl_num_short'=>['label' => 'Angka Bulan Sekarang 2 digit. ex: 01', 'value'=>date('m')],
		'date_now_short'=>['label' => 'Tanggal Sekarang (pendek). ex: 03-09-2021', 'value'=>date('d-m-Y')],
		'date_now_long'=>['label' => 'Tanggal Sekarang (panjang). ex: 03 September 2021', 'value'=>tanggal_indo(date('Y-m-d'))],
		'date_now_day'=>['label' => 'Tanggal Sekarang dengan hari. ex: Selasa, 03 September 2021', 'value'=>tanggal_indo(date('Y-m-d'),TRUE)],
		'date_now_long_en'=>['label' => 'Tanggal Sekarang (panjang)  dalam bahasa inggris. ex: December 03, 2021', 'value'=>date('F d, Y')],
		'date_now_day_en'=>['label' => 'Tanggal Sekarang dengan hari dalam bahasa inggris. ex: Sunday, December 03, 2021', 'value'=>date('l, F d, Y')],
		'day_now'=>['label' => 'Hari Sekarang. ex: Selasa', 'value'=>hari_indo(date('N'))],
		'day_now_en'=>['label' => 'Hari Sekarang dalam bahasa inggris. ex: Sunday', 'value'=>date('l')],
		'set_tgl'=>['label' => 'Input Tanggal (Hanya pada Template Bukti Pengambilan)', 'value'=>date('d F Y')],
		'qr_code'=>['label' => 'Generate QR Code ${qr_code:[width]:[height]} ex: ${qr_code:5cm:5cm} | ${qr_code:500:500}', 'value'=>base_url('assets/images/qrcode.png')],
		'biaya_doc'=>['label' => 'Biaya Dokumen (Template Kwitansi)', 'value'=>'-'],
		'jumlah_doc'=>['label' => 'Jumlah Dokumen (Template Kwitansi)', 'value'=>'-'],
		'total_doc'=>['label' => 'Total Biaya Dokumen (jumlah_doc x biaya_doc) (Template Kwitansi)', 'value'=>'-'],
		'total_doc_terbilang'=>['label' => 'Terbilang Total Biaya Dokumen (jumlah_doc x biaya_doc) (Template Kwitansi)', 'value'=>'Bebas Biaya'],
		'no_dokumen'=>['label' => 'No Dokumen ex: 0012-0014 (Template Kwitansi)', 'value'=>'0001'],
	];
	foreach ($v as $key => $value) {
		$items[$key]['value']=$value;
	}
	return $items;
}
function phpword_autoval($word,$word_item){
	foreach ($word_item as $name => $item) {
		switch ($name) {
			case 'qr_code':
					$word->setImageValue($name,$item['value']);
				break;
			
			default:
					$word->setValue($name,$item['value']);
				break;
		}
	}
}
function iki_gambar($path,$ext=['png','jpg','jpeg','gif']){
	$ext = (is_array($ext)?$ext:[$ext]);
	$mime = mime_content_type(FCPATH.$path);
	$mime = strtolower(str_replace('image/', '', $mime));
	return in_array($mime, $ext);
}
function allow_access($role){
	$akses = getSession('akses');
	$role = (is_array($role)?$role:[$role]);
	if(!in_array($akses, $role)){
		show_404();
	}
}
function identitas_field($key=""){
	$fields = [
		'no_identitas' => 'No Identitas',
		'jenis_identitas' => 'Jenis Identitas',
		'nama' => 'Nama Lengkap',
		'jk' => 'Jenis Kelamin',
		'tempat_lahir' => 'Tempat Lahir',
		'tgl_lahir' => 'Tanggal Lahir',
		'agama' => 'Agama',
		'pekerjaan' => 'Pekerjaan',
		'umur' => 'Umur',
		'kewarganegaraan' => 'Kewarganegaraan',
		'provinsi' => 'Provinsi',
		'kota' => 'Kota / Kabupaten',
		'kecamatan' => 'Kecamatan',
		'desa' => 'Desa',
		'alamat_idn' => 'Alamat Indonesia',
		'negeri' => 'Negeri',
		'daerah' => 'Daerah',
		'distrik' => 'Distrik',
		'alamat_mys' => 'Alamat Malaysia',
	];
	return (empty($key)?$fields:@$fields[$key]);
}
function can_access($role){
	$akses = getSession('akses');
	$role = (is_array($role)?$role:[$role]);
	return in_array($akses, $role);
}
function btn_status_layanan(){
	return [
		['btn-info','fa fa-eye','Detail Data',['loket','admin','hs','verifikasi','kasir']],
		['btn-warning','fa fa-edit','Ubah Data',['loket','verifikasi']],
		['btn-danger','fa fa-trash','Hapus Data','loket'],
		['btn-primary','fa fa-paper-plane','Kirim Data','loket'],
		['bg-purple','fa fa-receipt','Bukti Pengambilan',['loket','hs','verifikasi','kasir']],
		['bg-indigo','fa fa-money-check-alt','Tanda Terima',['loket','hs','verifikasi','kasir']],
		['btn-primary','fa fa-file-word','Download File Pelayanan',['loket','hs','verifikasi']],
		['bg-teal','fa fa-file-archive','File Berkas',['loket','hs','verifikasi','kasir','admin']],
	];
}
function generate_qr($data,$name="",$path='assets/images/qrcode'){
	if(empty($name)){
		$name = 'QR'.timeCode();
	}
	$path.='/'.$name.'.png';
	$params['data'] = $data;
	$params['level'] = 'H';
	$params['size'] = 10;
	$params['savename'] = FCPATH.$path;
	try {
		CI()->ciqrcode->generate($params);
		return $path;
	} catch (Exception $e) {
		return "";
	}
}
function site_url_qrcode($u){
	return CI()->config->item('base_url_qrcode').$u;
}
function get_umur($val){
	return ( intval(date('Y')) - intval(date('Y',strtotime($val))) );
}

function response_json($data=[]){
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data);
}

function uuid(){ return vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)); }

function sendNotif($u,$m,$t="Notifikasi",$link="",$d=""){
	$d = empty($d)?date('Y-m-d H:i:s'):$d;
	CI()->db->insert('notifikasi',[
		'ke' => $u,
		'title' => $t,
		'message' => $m,
		'href'=> $link,
		'waktu' => $d
	]);
}