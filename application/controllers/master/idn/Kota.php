<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kota extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('IdnKota','md_kota');
		$this->load->model('IdnProvinsi','prov');
		$this->active_menu = 'master_idn_kota';
	}

	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			echo $this->datatables->select('idkot.id as kode, idkot.name as kota_name, idp.id as prov_kode, idp.name as prov_name')
						->where(['province_id'=>$this->input->post('prov')])
						->join('indonesia_provinsi idp','idp.id=idkot.province_id','left')
						->addColumn('act',function($row){
							$params = implode('/', [$row['kode'],url_title($row['kota_name'])]);
							$btn = '<button type="button" title="Ubah Data" class="btn mb-2 btn-warning btn-sm btn-form-x" data-ref="'.$row['kode'].'" data-nama="'.$row['kota_name'].'" data-prov="'.$row['prov_kode'].'"><i class="fa fa-edit"></i></button> ';
							$btn.= '<button type="button" data-href="'.base_url("master/idn/kota/hapus/".$params).'" class="btn mb-2 btn-danger btn-sm btn-del" title="Hapus Data"><i class="fa fa-trash"></i></button>';
							return $btn;
						})
						->table($this->md_kota->table.' idkot')->draw();
		}else{
			$this->db->select('id,name');
			$prov = $this->prov->get();
			$this->view('master/idn/table_kota',compact('prov'));
		}
	}
	function simpan(){
		$status = 500; $message = "Data gagal disimpan.";
		$ref = $this->input->post('ref');
		$this->load->library('form_validation');
		$this->form_validation->set_rules([
			[
				'field' => 'kode',
                'label' => 'Kode Kota',
                'rules' => 'trim|required|max_length[4]|min_length[4]'
			],[
				'field' => 'prov',
                'label' => 'PROVINSI',
                'rules' => 'trim|required|max_length[2]'
			],[
				'field' => 'nama',
                'label' => 'Nama Kota',
                'rules' => 'trim|required|min_length[2]'
			]
		]);
		if ($this->form_validation->run()){
			$id = $this->input->post('kode',TRUE);
			$province_id = $this->input->post('prov',TRUE);
			$prov = $province_id;
			$name = $this->input->post('nama',TRUE);
			$cek = $this->md_kota->first(['id !='=>$ref,'id'=>$id]);
			if(!empty($cek)||@$cek->name==$name){
				$status = 202; $message = ($cek->name==$name)?"Nama Kota `$name` Telah Tersedia.":"Kode Kota `$id` Telah Tersedia.";
			}else{
				$data = compact('id','province_id','name');
				$data['meta'] = '';
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['updated_at'] = date('Y-m-d H:i:s');
				if(empty($ref)){
					// $this->db->select('MAX(id) as max');
					// $max = intval($this->md_kota->first(['province_id'=>$prov])->max);
					// $data['id'] = empty($max)?$prov.'01':($max+1);
					if($this->md_kota->insert($data)){
						$status = 200;
						$message = 'Data berhasil disimpan.';
					}
				}else{
					unset($data['meta']);
					unset($data['created_at']);
					// $d = $this->md_kota->findOrFail(['id'=>$ref]);
					// if($d->province_id!=$prov){
					// 	$x = substr($d->id,strlen($prov));
					// 	$idx = $prov.$x;
					// 	$cek = $this->md_kota->first(['id'=>$idx]);
					// 	if(!empty($cek)){
					// 		$max = intval($this->md_kota->first(['province_id'=>$prov])->max);
					// 		$idx = empty($max)?$prov.'01':($max+1);
					// 	}
					// 	$data['id'] = $idx;
					// }
					if($this->md_kota->update($ref,$data)){
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
		if($this->md_kota->delete($ref)){$status=200;$message='Data berhasil dihapus.';}
		header("Content-Type: application/json");
		echo json_encode(compact('status','message'));
	}
}
