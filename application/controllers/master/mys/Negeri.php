<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Negeri extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('MysNegeri','neg');
		$this->active_menu = 'master_mys_negeri';
	}

	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables->select('id as kode, nama')
						->addColumn('act',function($row){
							$params = implode('/', [$row['kode'],url_title($row['nama'])]);
							$btn = '<button type="button" title="Ubah Data" class="btn mb-2 btn-warning btn-sm btn-form-x" data-ref="'.$row['kode'].'" data-nama="'.$row['nama'].'"><i class="fa fa-edit"></i></button> ';
							$btn.= '<button type="button" data-href="'.base_url("master/mys/negeri/hapus/".$params).'" class="btn mb-2 btn-danger btn-sm btn-del" title="Hapus Data"><i class="fa fa-trash"></i></button>';
							return $btn;
						})
						->table($this->neg->table)->draw();
		}else{
			$this->view('master/mys/table_negeri');
		}
	}
	function simpan(){
		$status = 500; $message = "Data gagal disimpan.";
		$ref = $this->input->post('ref');
		$this->load->library('form_validation');
		$this->form_validation->set_rules([[
				'field' => 'nama',
                'label' => 'Nama Negeri',
                'rules' => 'trim|required|min_length[2]'
			]
		]);
		if ($this->form_validation->run()){
			$nama = $this->input->post('nama',TRUE);
			$cek = $this->neg->first(['id !='=>$ref,'nama'=>$nama]);
			if(!empty($cek)){
				$status = 202; $message = "Nama Negeri `$nama` Telah tersedia.";
			}else{
				$data = [];
				$data['nama'] = $nama;
				$data['kode'] = '';
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['updated_at'] = date('Y-m-d H:i:s');
				if(empty($ref)){
					if($this->neg->insert($data)){
						$status = 200;
						$message = 'Data berhasil disimpan.';
					}
				}else{
					unset($data['kode']);
					unset($data['created_at']);
					if($this->neg->update($ref,$data)){
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
		if($this->neg->delete($ref)){$status=200;$message='Data berhasil dihapus.';}
		header("Content-Type: application/json");
		echo json_encode(compact('status','message'));
	}
}
