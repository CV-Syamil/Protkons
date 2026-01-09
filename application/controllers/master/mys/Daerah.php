<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daerah extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('MysDaerah','model');
		$this->load->model('MysNegeri','negeri');
		$this->active_menu = 'master_mys_daerah';
	}

	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables->select('t1.id as kode, t1.nama as nama1, t2.nama as nama2, t1.negeri_id')
						->join('master_negeri'.' t2','t2.id=t1.negeri_id','left')
						->where(['negeri_id'=>$this->input->post('negeri',TRUE)])
						->addColumn('act',function($row){
							$params = implode('/', [$row['kode'],url_title($row['nama2'])]);
							$btn = '<button type="button" title="Ubah Data" class="btn mb-2 btn-warning btn-sm btn-form-x" data-ref="'.$row['kode'].'" data-nama="'.$row['nama1'].'" data-negeri="'.$row['negeri_id'].'"><i class="fa fa-edit"></i></button> ';
							$btn.= '<button type="button" data-href="'.base_url("master/mys/daerah/hapus/".$params).'" class="btn mb-2 btn-danger btn-sm btn-del" title="Hapus Data"><i class="fa fa-trash"></i></button>';
							return $btn;
						})
						->table($this->model->table.' t1')->draw();
		}else{
			$negeri = $this->negeri->select('id, nama')->get();
			$this->view('master/mys/table_daerah',compact('negeri'));
		}
	}

	function simpan(){
		$status = 500; $message = "Data gagal disimpan.";
		$ref = $this->input->post('ref');
		$this->load->library('form_validation');
		$this->form_validation->set_rules([[
				'field' => 'negeri',
                'label' => 'NEGERI',
                'rules' => 'trim|required'
			],[
				'field' => 'nama',
				'label' => 'NAMA DAERAH',
				'rules' => 'trim|required|min_length[2]'
			]
		]);
		if ($this->form_validation->run()){
			$negeri = $this->input->post('negeri',TRUE);
			$nama = $this->input->post('nama',TRUE);
			$cek = $this->model->first(['id !='=>$ref,'nama'=>$nama]);
			if(!empty($cek)){
				$status = 202; $message = "Nama Daerah `$nama` Telah tersedia.";
			}else{
				$data = [];
				$data['nama'] = $nama;
				$data['negeri_id'] = $negeri;
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['updated_at'] = date('Y-m-d H:i:s');
				if(empty($ref)){
					if($this->model->insert($data)){
						$status = 200;
						$message = 'Data berhasil disimpan.';
					}
				}else{
					unset($data['created_at']);
					if($this->model->update($ref,$data)){
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
		if($this->model->delete($ref)){$status=200;$message='Data berhasil dihapus.';}
		header("Content-Type: application/json");
		echo json_encode(compact('status','message'));
	}
}
