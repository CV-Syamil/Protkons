<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_identitas extends BASE_Controller {
	var $options_type;
	function __construct(){
		parent::__construct();
		allow_access(['admin','su']);
		$this->load->model('MasterJenisIdentitas','ms_jenis');
		$this->active_menu = 'master_jenis_identitas';
	}

	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables
						->addColumn('act',function($row){
							$params = implode('/', [$row['id'],url_title($row['jenis'])]);
							$btn = '<button type="button" data-ref="'.$row['id'].'" data-jenis="'.$row['jenis'].'" data-title="Form Ubah Jenis Identitas" class="btn btn-warning btn_modal_form btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></button> ';
							$btn.= '<button type="button" data-href="'.site_url('master/jenis-identitas/hapus/'.$params).'" class="btn btn-danger btn-del btn-sm" title="Hapus Data" ><i class="fa fa-trash"></i></button>';
							return $btn;
						})
						->removeColumns(['id'])
						->table($this->ms_jenis->table)->draw();
		}else{
			$this->view('master/jenis_identitas/table');
		}
	}
	function hapus($id,$nama){
		$data = $this->ms_jenis->findOrFail($id);
		if($this->ms_jenis->delete($id)){
			setFlash('success','Data Jenis Identitas `'.$data->jenis.'` berhasil dihapus.');
		}else{
			setFlash('error','Data Jenis Identitas `'.$data->jenis.'` gagal dihapus.');
		}
		redirect('master/jenis-identitas');
	}
	function simpan(){
		$id = $this->input->post('ref');
		$ret = ['status'=>'500','message'=>'Internal Server Errors.'];
		$this->load->library('form_validation');
		$this->form_validation->set_rules([
			[
				'field' => 'jenis',
                'label' => 'Jenis Identitas',
                'rules' => 'trim|required|min_length[2]'
			]
		]);
		if ($this->form_validation->run()){
			$jenis = $this->input->post('jenis',TRUE);
			$cek = $this->ms_jenis->first(['id !='=>$id,'jenis'=>$jenis]);
			if(!empty($cek)){
				$ret['message'] = 'Jenis Identitas `'.$jenis.'` already exists.';
			}else{
				$data = ['jenis' => $jenis];
				if(empty($id)){
					if($this->ms_jenis->insert($data)){
						$ret = ['status'=>200,'message'=>'Data jenis identitas berhasil ditambahkan.'];
					}else{
						$ret['message'] = 'Data jenis identitas gagal ditambahkan.';
					}
				}else{
					if($this->ms_jenis->update($id,$data)){
						$ret = ['status'=>200,'message'=>'Data jenis identitas berhasil diperbarui.'];
					}else{
						$ret['message'] = 'Data jenis identitas gagal diperbarui.';
					}
				}
			}
		}else{
			$ret['message'] = $this->form_validation->error_string();
		}
		echo json_encode($ret);
	}
}
