<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kecamatan extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('IdnKecamatan','kec');
		$this->load->model('IdnKota','kota');
		$this->load->model('IdnProvinsi','prov');
		$this->active_menu = 'master_idn_kecamatan';
	}

	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables->select('idkec.id as kode, idkec.name as kec_name, idkot.id as kota_id, idkot.name as kota_name')
						->join('indonesia_kota idkot','idkot.id=idkec.city_id','left')
						->where(['city_id'=>$this->input->post('kota')])
						->addColumn('act',function($row){
							$params = implode('/', [$row['kode'],url_title($row['kec_name'])]);
							$btn = '<button type="button" title="Ubah Data" class="btn mb-2 btn-warning btn-sm btn-form-x" data-ref="'.$row['kode'].'" data-nama="'.$row['kec_name'].'" data-kota="'.$row['kota_name'].'" data-refkota="'.$row['kota_id'].'"><i class="fa fa-edit"></i></button> ';
							$btn.= '<button type="button" data-href="'.base_url("master/idn/kecamatan/hapus/".$params).'" class="btn mb-2 btn-danger btn-sm btn-del" title="Hapus Data"><i class="fa fa-trash"></i></button>';
							return $btn;
						})
						->table($this->kec->table.' idkec')->draw();
		}else{
			$prov = $this->prov->select('id,name')->get();
			$kota_first = $this->kota->select('id,name')->first(['province_id'=>(empty($prov)?0:$prov[0]->id)]);
			$this->view('master/idn/table_kecamatan',compact('prov','kota_first'));
		}
	}

	function simpan(){
		$status = 500; $message = "Data gagal disimpan.";
		$ref = $this->input->post('ref');
		$this->load->library('form_validation');
		$this->form_validation->set_rules([
			[
				'field' => 'kode',
                'label' => 'KODE KECAMATAN',
                'rules' => 'trim|required|max_length[7]|min_length[7]'
			],[
				'field' => 'kota',
                'label' => 'KOTA',
                'rules' => 'trim|required'
			],[
				'field' => 'nama',
                'label' => 'NAMA KOTA',
                'rules' => 'trim|required|min_length[2]'
			]
		]);
		if ($this->form_validation->run()){
			$id = $this->input->post('kode',TRUE);
			$city_id = $this->input->post('kota',TRUE);
			$name = $this->input->post('nama',TRUE);
			$cek = $this->kec->first(['id !='=>$ref,'id'=>$id]);
			if(!empty($cek)||@$cek->name==$name){
				$status = 202; $message = ($cek->name==$name)?"Nama Kecamatan `$name` Telah Tersedia.":"Kode Kecamatan `$id` Telah Tersedia.";
			}else{
				$data = compact('id','city_id','name');
				$data['meta'] = '';
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['updated_at'] = date('Y-m-d H:i:s');
				if(empty($ref)){
					if($this->kec->insert($data)){
						$status = 200;
						$message = 'Data berhasil disimpan.';
					}
				}else{
					unset($data['meta']);
					unset($data['created_at']);
					if($this->kec->update($ref,$data)){
						$status = 200;
						$message = 'Data berhasil disimpan.';
					}
				}
			}
			
		}else{
			$status = 302; $message = $this->form_validation->error_string();
		}
		header("Content-Type: application/json");
		echo json_encode(compact('status','message'));
	}
	function hapus($ref,$nama){
		$status = 500; $message = 'Data gagal dihapus.';
		if($this->kec->delete($ref)){$status=200;$message='Data berhasil dihapus.';}
		header("Content-Type: application/json");
		echo json_encode(compact('status','message'));
	}
	function data_kota(){
		header('Content-Type: application/json');
		$s = $this->input->post('s',TRUE);
		if(!empty($s)){ $this->db->where('name LIKE',"%$s%"); }
		$results = $this->kota->select('id, name as text')->get(['province_id'=>$this->input->post('prov',TRUE)]); 
		echo json_encode(compact('results'));
	}
}
