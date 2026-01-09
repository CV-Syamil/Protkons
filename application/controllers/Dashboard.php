<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// use Mpdf\Mpdf;
class Dashboard extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->active_menu = 'dashboard';
		$this->load->model('Tr_pelayanan','tr_pl');
		$this->load->model('Users_model','user_m');
		$this->load->model('Master_pelayanan','ms_pl');
	}
	function index(){
		// $d = new Mpdf;
		// print_r($d);
		// exit();
		// $this->load->library('TTE');
		// $file = FCPATH.'assets/uploads/TESPDF.pdf';
		// $data = (new TTE)->cekStatus('3674042810950002')->getBody();
		// $data = (new TTE)->signPDF([
		// 	'file' => $file,
		// 	'tampilan' => 'invisible',
		// 	// 'image' => 'false',
		// 	// 'linkQR' => site_url_qrcode('informasi/pelayanan/')
		// ]);
		// print_r($data);
		// exit();

		$user = $this->user_m->findOrFail(getSession('ref'));
		$akses_pl = json_decode($user->akses_pelayanan,TRUE);

		if(!in_array('all',$akses_pl)){$this->db->where_in('pelayanan_id',$akses_pl); }
		$ms_pl = $this->ms_pl->select('pelayanan_id, pelayanan')->count_rows();
		
		$count_pl=['pl'=>$ms_pl,'tr_pl'=>0,'tr_pl2'=>0];
		$slc_sts=status_layanan();
		if(!in_array('all',$akses_pl)){$this->db->where_in('pelayanan_id',$akses_pl); }
		if($user->akses=='loket'){
			$count_pl['tr_pl']=$this->tr_pl->select('id')->count_rows();
		}else{
			$count_pl['tr_pl']=$this->tr_pl->select('id')->count_rows();
		}
		if(!in_array('all',$akses_pl)){$this->db->where_in('pelayanan_id',$akses_pl); }
		switch ($user->akses) {
			case 'loket': 
					$count_pl['tr_pl2'] = $this->tr_pl->select('id')->count_rows(['status'=>5,]);
				break;
			case 'verifikasi':
					unset($slc_sts[0]);
					$count_pl['tr_pl2'] = $this->tr_pl->select('id')->count_rows(['status'=>5,'petugas_verifikasi'=>getSession('ref')]);
				break;
			case 'hs':
					unset($slc_sts[0]);unset($slc_sts[1]);unset($slc_sts[91]);
					$count_pl['tr_pl2'] = $this->tr_pl->select('id')->count_rows(['status'=>5,'hs'=>getSession('ref')]);
				break;
			case 'kasir':
					unset($slc_sts[0]);unset($slc_sts[1]);unset($slc_sts[2]);unset($slc_sts[91]);unset($slc_sts[92]);
					$count_pl['tr_pl2'] = $this->tr_pl->select('id')->count_rows(['status'=>5,'kasir'=>getSession('ref')]);
				break;
			case 'admin': 
					$count_pl['tr_pl2'] = $this->tr_pl->select('id')->count_rows(['status'=>5]);
				break;
		}
		$options_sts="";
		foreach ($slc_sts as $key => $value) {
			$options_sts.="<option value=\"$key\">$value</option>";
		}

		// if(!in_array('all',$akses_pl)){$this->db->where_in('pelayanan_id',$akses_pl); }
		// $pl = $this->ms_pl->get();
		$this->view('admin_dashboard',compact('options_sts','count_pl'));
	}
}
