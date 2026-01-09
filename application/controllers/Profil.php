<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Users_model','user');
		$this->active_menu = 'user_profil';
	}

	function index(){
		// print_r(getSession()); exit();
		$data = $this->user->findOrFail(getSession('ref'));
		$this->view('setting_profil',compact('data'));
	}

	function simpan(){
		$sess = getSession();
		$user_data = $this->user->findOrFail($sess['ref']);
		$this->load->library('form_validation');
		$this->load->library('upload');

		$rules = [
			[
				'field' => 'nip',
				'label' => 'NIP',
				'rules' => 'trim'
			],[
				'field' => 'nama',
				'label' => 'Nama',
				'rules' => 'trim|required|min_length[3]'
			],[
				'field' => 'jabatan',
				'label' => 'Jabatan',
				'rules' => 'trim'
			]
		];
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$data = [
				'nip' => $this->input->post('nip',TRUE),
				'nama' => $this->input->post('nama',TRUE),
				'jabatan' => $this->input->post('jabatan',TRUE),
			];
			if(!empty($_FILES['foto']['name'])){
				$nm = $sess['ref'].'.png';
				$path = 'assets/foto';
				if(upload('foto',$nm,$path,'png|jpg|jpeg|gif')){
					$data['foto'] = $path.'/'.$nm;
					$sess['foto'] = base_url($data['foto']);
				}else{
					setFlash('error',$this->upload->display_errors());
					redirect(back_link(site_url('profil')));
				}
			}
			if($this->user->update($user_data->user_id,$data)){
				$sess['nama'] = $data['nama'];
				setSession($sess);
				setFlash('success','Berhasil Update User.');
			}else{
				setFlash('error','Gagal Update User. Mohon ulangi beberapa saat lagi.');
			}
		}else{
			setFlash('error',$this->form_validation->error_string());
		}

		redirect(back_link(site_url('profil')));
	}
	function ubah_password(){
		$resp=['status'=>500,'message'=>'Internal Server Errors.'];
		$sess = getSession();
		$user_data = $this->user->findOrFail($sess['ref']);
		$this->load->library('form_validation');
		$this->load->library('upload');

		$rules = [
			[
				'field' => 'old_pwd',
				'label' => 'Password Lama',
				'rules' => 'trim|required|min_length[4]'
			],[
				'field' => 'new_pwd',
				'label' => 'Password Baru',
				'rules' => 'trim|required|min_length[4]'
			],[
				'field' => 'confirm_new_pwd',
				'label' => 'Konfirmasi Password Baru',
				'rules' => 'trim|required|min_length[4]|matches[new_pwd]'
			]
		];
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			if(!password_verify($this->input->post('old_pwd'),$user_data->password)){
				$resp['message'] = 'Password Lama yang Anda masukkan salah. Mohon periksa kembali Password Anda.';
			}else{
				if($this->user->update($user_data->user_id,['password'=>pwd_enc($this->input->post('new_pwd'))])){
					$resp['status'] = 200;
					$resp['message'] = 'Password Berhasil diPerbarui.';
				}else{
					$resp['message'] = 'Password Gagal diPerbarui.';
				}
			}
		}else{
			$resp['status']=400;
			$resp['message']=$this->form_validation->error_string(' ',' ');
		}
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	function simpan_form_tte(){
		$sess = getSession();
		if($this->user->update(['user_id'=>$sess['ref']],['tte_nik'=>$this->input->post('nik'),'tte_pwd'=>$this->input->post('pwd')])){
			$sess['tte_nik'] = $this->input->post('nik');
			$sess['tte_pwd'] = $this->input->post('pwd');
			setSession($sess);
			setFlash('success','Data berhasil disimpan.');
		}else{
			setFlash('error','Data gagal disimpan');
		}
		redirect(site_url('profil'));
	}
	function cek_status_nik($nik=""){
		if(empty($nik)){ e404(); }
		else{
			$this->load->library('TTE');
			$tte = new TTE;
			$tte->cekStatus($nik,30);
			$status = 500; $message = "Internal Server Errors.";
			if($tte->isError){
				$message = $tte->errorMessage;
			}else{
				$resp = $tte->getObject();
				$status = ($resp->status_code==1111)?200:$resp->status_code; 
				$message = $resp->message;
			}
			response_json(compact('status','message'));
		}
	}
}