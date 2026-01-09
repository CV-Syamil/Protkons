<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distrik extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('MysDistrik','model');
		$this->load->model('MysDaerah','daerah');
		$this->load->model('MysNegeri','negeri');
		$this->active_menu = 'master_mys_distrik';
	}

	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables->select('t1.id as kode, t1.nama as nama1, t1.daerah_id, t2.nama as nama2')
						->join('master_daerah'.' t2','t2.id=t1.daerah_id','left')
						->where(['daerah_id'=>$this->input->post('daerah')])
						->addColumn('act',function($row){
							$params = implode('/', [$row['kode'],url_title($row['nama1'])]);
							$btn = '<button type="button" title="Ubah Data" class="btn mb-2 btn-warning btn-sm btn-form-x" data-ref="'.$row['kode'].'" data-nama="'.$row['nama1'].'" data-daerah="'.$row['nama2'].'" data-refdaerah="'.$row['daerah_id'].'"><i class="fa fa-edit"></i></button> ';
							$btn.= '<button type="button" data-href="'.base_url("master/mys/distrik/hapus/".$params).'" class="btn mb-2 btn-danger btn-sm btn-del" title="Hapus Data"><i class="fa fa-trash"></i></button>';
							return $btn;
						})
						->table($this->model->table.' t1')->draw();
		}else{
			$negeri = $this->negeri->select('id,nama')->get();
			$daerah_first = $this->daerah->select('id,nama')->first(['negeri_id'=>(empty($negeri)?0:$negeri[0]->id)]);
			$this->view('master/mys/table_distrik',compact('negeri','daerah_first'));
		}
	}

	function simpan(){
		$status = 500; $message = "Data gagal disimpan.";
		$ref = $this->input->post('ref');
		$this->load->library('form_validation');
		$this->form_validation->set_rules([
			[
				'field' => 'daerah',
                'label' => 'DAERAH',
                'rules' => 'trim|required'
			],[
				'field' => 'nama',
                'label' => 'NAMA DAERAH',
                'rules' => 'trim|required|min_length[2]'
			]
		]);
		if ($this->form_validation->run()){
			$daerah_id = $this->input->post('daerah',TRUE);
			$nama = $this->input->post('nama',TRUE);
			$cek = $this->model->first(['id !='=>$ref,'nama'=>$nama]);
			if(!empty($cek)){
				$status = 202; $message = "Nama Daerah `$nama` Telah Tersedia.";
			}else{
				$data = compact('daerah_id','nama');
				$data['kode_pos'] = '-';
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['updated_at'] = date('Y-m-d H:i:s');
				if(empty($ref)){
					if($this->model->insert($data)){
						$status = 200;
						$message = 'Data berhasil disimpan.';
					}
				}else{
					unset($data['kode_pos']);
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

	function data_daerah(){
		header('Content-Type: application/json');
		$s = $this->input->post('s',TRUE);
		if(!empty($s)){ $this->db->where('nama LIKE',"%$s%"); }
		$results = $this->daerah->select('id, nama as text')->get(['negeri_id'=>$this->input->post('negeri',TRUE)]); 
		echo json_encode(compact('results'));
	}
}
