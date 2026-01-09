<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_identitas extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('DataIdentitas','identitas');
		$this->load->model('Users_model','user_m');
		$this->load->model('Master_pelayanan','ms_pl');
		$this->load->model('Tr_pelayanan','tr_pl');
	}

	function index(){
		if($this->input->is_ajax_request()){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables
			->select("id, no_identitas, nama, tempat_lahir, tgl_lahir, riwayat_layanan as history_pl")
			->editColumn('tgl_lahir',function($data){return tanggal_indo($data);})
			->editColumn('history_pl',function($data){
				if(!empty($data)){
					$rw = explode(';',$data);
					$this->db->join($this->ms_pl->table.' ms','ms.pelayanan_id=tr.pelayanan_id');
					$this->db->where_in('tr.id',$rw)->limit(3);
					$data = $this->tr_pl->alias('tr')->select('ms.pelayanan')->get();
					$ret = '';
					foreach ($data as $key => $value) {
						$ret.="<li class=\"small\">$value->pelayanan</li>";
					}
					return '<ul class="small" style="padding-left:15px; !important">'.$ret.'</ul>';
				}else{
					return '<center><b>-</b></center>';
				}
			})->removeColumns(['id'])
			->addColumn('act',function($row){
				$btn = "";
				$btn.= '<a title="Detail Data" href="'.site_url('data-identitas/detail/').implode('/',[$row['id'],url_title($row['nama'])]).'" class="btn btn-sm btn-info ml-1 mr-1 mb-1"><i class="far fa-eye"></i></a>';
				$btn.= '<button type="button" title="Edit Data" data-href="'.site_url('data-identitas/ubah-data/').implode('/',[$row['id'],url_title($row['nama'])]).'" class="btn btn-sm btn-warning modal_edit_identitas ml-1 mr-1 mb-1"><i class="far fa-edit"></i></button>';
				$btn.= '<button type="button" title="Hapus Data" data-href="'.site_url('data-identitas/hapus-data/').implode('/',[$row['id'],url_title($row['nama'])]).'" class="btn btn-sm btn-danger modal_delete_identitas ml-1 mr-1 mb-1"><i class="fa fa-trash"></i></button>';
				return $btn;
			})
			->table('layanan_identitas')->draw();
		}else{
			$this->active_menu = 'master_identitas';
			$this->view('data_identitas/table');
		}
	}

	function table(){
		if($this->input->is_ajax_request()){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables
			->select("id, no_identitas, nama, tempat_lahir, tgl_lahir, '-' as history_pl")
			->editColumn('tgl_lahir',function($data){return tanggal_indo($data);})
			// ->editColumn('history_pl',function($data){
				// return '-';
				// if(!empty($data)){
				// 	$rw = explode(';',$data);
				// 	$this->db->join($this->ms_pl->table.' ms','ms.pelayanan_id=tr.pelayanan_id');
				// 	$this->db->where_in('tr.id',$rw)->limit(3);
				// 	$data = $this->tr_pl->alias('tr')->select('ms.pelayanan')->get();
				// 	$ret = '';
				// 	foreach ($data as $key => $value) {
				// 		$ret.="<li class=\"small\">$value->pelayanan</li>";
				// 	}
				// 	return '<ul class="small" style="padding-left:15px; !important">'.$ret.'</ul>';
				// }else{
				// 	return '<center><b>-</b></center>';
				// }
			// })
			->removeColumns(['id'])
			->addColumn('act',function($row){
				return '<button type="button" title="Edit Data" data-href="'.site_url('data-identitas/ubah-data/').implode('/',[$row['id'],url_title($row['nama'])]).'" class="btn btn-sm btn-warning modal_edit_identitas"><i class="far fa-edit"></i></button>
				
				<a href="'.site_url('data-identitas/detail/'.implode('/',[$row['id'],url_title($row['nama'])])).'" title="Pilih Data" class="btn btn-sm btn-success"><i class="far fa-check-circle"></i></a>';
			})
			// ->table('layanan_identitas')->draw(true);
			->table($this->identitas->table)->draw(true);
		}else{
			show_404();
		}
	}

	function table_pelayanan(){
		if($this->input->is_ajax_request()){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables
			->select("id, no_identitas, nama, tempat_lahir, tgl_lahir, '-' as history_pl")
			->editColumn('tgl_lahir',function($data){return tanggal_indo($data);})
			// ->editColumn('history_pl',function($data){
			// 	if(!empty($data)){
			// 		$rw = explode(';',$data);
			// 		$this->db->join($this->ms_pl->table.' ms','ms.pelayanan_id=tr.pelayanan_id');
			// 		$this->db->where_in('tr.id',$rw)->limit(3);
			// 		$data = $this->tr_pl->alias('tr')->select('ms.pelayanan')->get();
			// 		$ret = '';
			// 		foreach ($data as $key => $value) {
			// 			$ret.="<li class=\"small\">$value->pelayanan</li>";
			// 		}
			// 		return '<ul class="small" style="padding-left:15px; !important">'.$ret.'</ul>';
			// 	}else{
			// 		return '<center><b>-</b></center>';
			// 	}
			// })
			->removeColumns(['id'])
			->addColumn('act',function($row){
				return '<button type="button" title="Edit Data" data-href="'.site_url('data-identitas/ubah-data/').implode('/',[$row['id'],url_title($row['nama']),'pl']).'" class="btn btn-sm btn-warning modal_edit_identitas"><i class="far fa-edit"></i></button>
				
				<button type="button" data-href="'.site_url('data-identitas/data-json/'.implode('/',[$row['id'],url_title($row['nama'])])).'" title="Pilih Data" class="btn btn-sm btn-success btn-pilih-data"><i class="far fa-check-circle"></i></button>';
			})
			// ->table('layanan_identitas')->draw();
			->table($this->identitas->table)->draw(true);
		}else{
			show_404();
		}
	}
	function detail($ref,$name){
		$user = $this->user_m->findOrFail(getSession('ref'));
		$akses_pl = json_decode($user->akses_pelayanan,TRUE);

		$data = $this->identitas->findOrFail(['id'=>$ref]);
		$slc_sts=status_layanan();
		$options_sts="";
		foreach ($slc_sts as $key => $value) {
			$options_sts.="<option value=\"$key\">$value</option>";
		}
		$this->identitas->table.='_log';
		$history = $this->identitas->get(['parent'=>$data->id]);

		if(!in_array('all',$akses_pl)){$this->db->where_in('pelayanan_id',$akses_pl); }
		if($user->akses!='su'){ $this->db->where('fungsi',getSession('fungsi')); }
		$pl = $this->ms_pl->get();
		$this->view('data_identitas/view',compact('data','options_sts','pl','history'));
	}
	function data_json($ref,$name){
		$data = $this->identitas->findOrFail(['id'=>$ref]);
		$dat = [];
		$data->umur = intval(date('Y')) - intval(date('Y',strtotime($data->tgl_lahir)));
		$data->tgl_lahir = tanggal_indo($data->tgl_lahir);
		foreach($data as $key => $v){ $dat[]=[$key,$v]; }
		header('Content-Type: application/json');
		echo json_encode(['data'=>$dat]);
	}
	function tampilkan(){
		if($this->input->is_ajax_request()){
			$data = $this->identitas->findOrFail($this->input->post('ref'));
			$this->load->view('data_identitas/view_ajax', compact('data'));
		}else{
			show_404();
		}

	}
	function tambah_data($pl=""){
		if($this->input->is_ajax_request()){
			$pelapor=$this->identitas->first(['id'=>@$_GET['pelapor']]);
			$this->load->view('data_identitas/form_ajax', compact('pl','pelapor'));
		}else{
			show_404();
		}
	}
	function ubah_data($ref,$p,$pl=""){
		if($this->input->is_ajax_request()){
			$data = $this->identitas->findOrFail(['id'=>$ref]);
			$pelapor=$this->identitas->first(['id'=>@$_GET['pelapor']]);
			$this->load->view('data_identitas/form_ajax',compact('data','pl','pelapor'));
		}else{
			show_404();
		}
	}
	function simpan_data($pl=""){
		// print_r($this->input->post()); exit();t
		$this->load->library('form_validation');
		$resp = ['status'=>500,'message'=>'Internal Server Errors','data'=>NULL];
		$ref = $this->input->post('ref');
		$rules = [
			// [
			// 	'field' => 'no_identitas',
            //     'label' => 'No Identitas',
            //     'rules' => 'trim|required|min_length[1]'
			// ],
			[
				'field' => 'jenis_identitas',
                'label' => 'Jenis Identitas',
                'rules' => 'trim|required|min_length[1]'
			],
			[
				'field' => 'nama',
                'label' => 'Nama Lengkap',
                'rules' => 'trim|required|min_length[3]'
			],
		];
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$data = $this->input->post();
			// foreach (['negeri_provinsi','daerah_kota','distrik_kecamatan'] as $key => $value) {
			// 	$data[$value] = str_replace(['/','Indonesia','indonesia','Malaysia','malaysia'],'',$data[$value]);
			// }
			unset($data['ref']);
			$cek = $this->identitas->first(['no_identitas'=>$data['no_identitas'],'id !='=>$ref]);
			if(empty($cek)){
				if(empty($ref)){
					$data['created_at'] = date('Y-m-d H:i:s');
					if($this->identitas->insert($data)){
						$resp['status'] = 200;
						$resp['message'] = 'OK';
						$ref = $this->db->insert_id();
					}
				}else{
					$c_data = $this->identitas->findOrFail(['id'=>$ref]);
					$data['updated_at'] = date('Y-m-d H:i:s');
					if($this->identitas->update(['id'=>$ref],$data)){
						// if($c_data!=$data['no_identitas']){
						$ins = (array) $c_data;
						$ins['created_at'] = date('Y-m-d H:i:s');
						unset($ins['id']);
						$ins['parent'] = $c_data->id;
						$this->identitas->table.='_log';
						$this->identitas->insert($ins);
						// }
						$resp['status'] = 200;
						$resp['message'] = 'OK';
					}
				}

				if($resp['status']==200){
					if($pl=='pl'){
						$resp['href'] = site_url('data-identitas/data-json/'.$ref.'/'.url_title($data['nama']));
					}else{
						$resp['href'] = site_url('data-identitas/detail/'.$ref.'/'.url_title($data['nama']));
					}
				}
			}else{
				$resp['status'] = 202;
				$resp['message'] = 'No Identitas `'.$data['no_identitas'].'` sudah ada.';
			}
		}else{
			$resp['status'] = 301;
			$resp['message'] = $this->form_validation->error_string();
		}
		echo json_encode($resp);
	}
}
