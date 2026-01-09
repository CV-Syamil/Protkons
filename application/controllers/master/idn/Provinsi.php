<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provinsi extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('IdnProvinsi','prov');
		$this->active_menu = 'master_idn_provinsi';
	}

	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables->select('id as kode, name')
						->addColumn('act',function($row){
							$params = implode('/', [$row['kode'],url_title($row['name'])]);
							$btn = '<button type="button" title="Ubah Data" class="btn mb-2 btn-warning btn-sm btn-form-x" data-ref="'.$row['kode'].'" data-nama="'.$row['name'].'"><i class="fa fa-edit"></i></button> ';
							$btn.= '<button type="button" data-href="'.base_url("master/idn/provinsi/hapus/".$params).'" class="btn mb-2 btn-danger btn-sm btn-del" title="Hapus Data"><i class="fa fa-trash"></i></button>';
							return $btn;
						})
						->table($this->prov->table)->draw();
		}else{
			$this->view('master/idn/table_provinsi');
		}
	}
	function simpan(){
		$status = 500; $message = "Data gagal disimpan.";
		$ref = $this->input->post('ref');
		$this->load->library('form_validation');
		$this->form_validation->set_rules([
			[
				'field' => 'id',
                'label' => 'ID PROVINSI',
                'rules' => 'trim|required|max_length[2]'
			],[
				'field' => 'nama',
                'label' => 'Nama Provinsi',
                'rules' => 'trim|required|min_length[2]'
			]
		]);
		if ($this->form_validation->run()){
			$id = $this->input->post('id',TRUE);
			$name = $this->input->post('nama',TRUE);
			$cek = $this->prov->first(['id !='=>$ref,'id'=>$id]);
			if(!empty($cek)||@$cek->name==$name){
				$status = 202; $message = ($cek->name==$name)?"Nama Provinsi `$name` Telah tersedia.":"ID Provinsi `$id` telah tersedia.";
			}else{
				$data = compact('id','name');
				$data['meta'] = '';
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['updated_at'] = date('Y-m-d H:i:s');
				if(empty($ref)){
					if($this->prov->insert($data)){
						$status = 200;
						$message = 'Data berhasil disimpan.';
					}
				}else{
					unset($data['meta']);
					unset($data['created_at']);
					if($this->prov->update($ref,$data)){
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
		if($this->prov->delete($ref)){$status=200;$message='Data berhasil dihapus.';}
		header("Content-Type: application/json");
		echo json_encode(compact('status','message'));
	}
}
