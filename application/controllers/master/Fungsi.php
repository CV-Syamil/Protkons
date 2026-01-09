<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fungsi extends BASE_Controller {
	
	function __construct(){
		parent::__construct();
		allow_access('su');
		$this->load->model('MasterFungsi','msfungsi');
		$this->active_menu = 'master_fungsi';
	}

	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables
						->addColumn('act',function($row){
							$params = implode('/', [$row['id'],url_title($row['nama'])]);
							$btn = '<button type="button" data-ref="'.$row['id'].'" data-nama="'.$row['nama'].'" data-title="Form Ubah Fungsi" class="btn btn-warning btn_modal_form btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></button> ';
							$btn.= '<button type="button" data-href="'.site_url('master/fungsi/hapus/'.$params).'" class="btn btn-danger btn-del btn-sm" title="Hapus Data" ><i class="fa fa-trash"></i></button>';
							return $btn;
						})
						->removeColumns(['id'])
						->table($this->msfungsi->table)->draw();
		}else{
			$this->view('master/fungsi/table');
		}
	}
	function hapus($id,$nama){
		$data = $this->msfungsi->findOrFail($id);
		if($this->msfungsi->delete($id)){
			setFlash('success','Data `'.$data->nama.'` berhasil dihapus.');
		}else{
			setFlash('error','Data `'.$data->nama.'` gagal dihapus.');
		}
		redirect('master/fungsi');
	}
	function simpan(){
		$id = $this->input->post('ref');
		$ret = ['status'=>'500','message'=>'Internal Server Errors.'];
		$this->load->library('form_validation');
		$this->form_validation->set_rules([
			[
				'field' => 'nama',
                'label' => 'Nama Fungsi',
                'rules' => 'trim|required|min_length[2]'
			]
		]);
		if ($this->form_validation->run()){
			$nama = $this->input->post('nama',TRUE);
			$cek = $this->msfungsi->first(['id !='=>$id,'nama'=>$nama]);
			if(!empty($cek)){
				$ret['message'] = 'Data `'.$nama.'` already exists.';
			}else{
				$data = ['nama' => $nama];
				if(empty($id)){
					$data['created_at'] = date('Y-m-d H:i:s');
					$data['updated_at'] = date('Y-m-d H:i:s');
					if($this->msfungsi->insert($data)){
						$ret = ['status'=>200,'message'=>'Data berhasil ditambahkan.'];
					}else{
						$ret['message'] = 'Data gagal ditambahkan.';
					}
				}else{
					if($this->msfungsi->update($id,$data)){
						$ret = ['status'=>200,'message'=>'Data berhasil diperbarui.'];
					}else{
						$ret['message'] = 'Data gagal diperbarui.';
					}
				}
			}
		}else{
			$ret['message'] = $this->form_validation->error_string();
		}
		echo json_encode($ret);
	}
}
