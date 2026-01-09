<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function index(){
		$this->load->database();
		$this->cek_akses();
		$this->load->view('login');
	}

	function submit(){
		$this->cek_akses();
		$this->load->library('form_validation');
		$this->form_validation->set_rules([
			[
				'field' => 'usr',
                'label' => 'Username',
                'rules' => 'trim|required|min_length[3]'
			],[
				'field' => 'pwd',
                'label' => 'Password',
                'rules' => 'required|min_length[3]'
			]
		]);
		if ($this->form_validation->run()){
			$usr = $this->input->post('usr',TRUE);
			$pwd = $this->input->post('pwd',TRUE);
			$data = $this->db->select('users.*, fungsi.nama as nmf')
							 ->where('users.username',$usr)
							 ->join('fungsi','fungsi.id=users.fungsi','left')
							 ->get('users')->row();
							 
			if(!empty($data)){
				if(password_verify($pwd, $data->password)){
					$date = date('Y-m-d H:i:s');
					$this->db->where('user_id',$data->user_id)->update('users',['last_login'=>$date]);
					$nmf = ($data->akses=='su')?"Super User":$data->nmf;
					setSession([
						'login' => 'wes_login',
						'akses' => $data->akses,
						'login_at' => $date,
						'nama' => $data->nama,
						'ref' => $data->user_id,
						'tte_nik' => $data->tte_nik,
						'tte_pwd' => $data->tte_pwd,
						'fungsi' => $data->fungsi,
						'nama_fungsi' => $nmf,
						'foto' => (empty($data->foto)?'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTbNOpai32_rwcRrMxmpF4sNJG3CIR7yTPv7MD9qK4Ft5OdltMU6DymiRqxXRb0qtgGJoE&usqp=CAU':base_url($data->foto)),
					]);
					redirect('dashboard');
				}else{
					redirect('login?'.http_build_query(['e'=>'Password yang anda masukkan salah.','usr'=>$this->input->post('usr')]));
				}
			}else{
				redirect('login?'.http_build_query(['e'=>'Username Tidak ditemukan','usr'=>$this->input->post('usr')]));
			}
        }else{
			redirect('login?'.http_build_query(['e'=>$this->form_validation->error_string('- ','<br><br>')]));
        }

	}

	private function cek_akses(){
		$is_login = getSession('login');
		if(!empty($is_login)){
			$akses = getSession('akses');
			redirect('dashboard');
		}
	}

	function logout(){
		destroySession();
		redirect('/');
	}
}
