<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends BASE_Controller {
	function __construct(){
		parent::__construct();
		allow_access(['admin','su']);
		$this->load->model('Users_model','m_user');
		$this->load->model('Master_pelayanan','ms_pl');
		$this->active_menu = 'master_user';
	}

	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			$user_akses = user_akses();
			$this->db->where_in('users.akses',array_keys($user_akses));
			if(!can_access('su')){
				$this->db->where_in('users.fungsi',getSession('fungsi'));
			}
			echo $this->datatables
						->select('users.*, fungsi.nama as nm_fungsi')
						->where(['users.user_id !='=>getSession('ref')])
						->join('fungsi','fungsi.id=users.fungsi','left')
						->editColumn('akses',function($data) use ($user_akses){
							return $user_akses[$data];
						})
						->editColumn('aktif',function($data){
							return ((intval($data)==1)?'<label class="badge bg-green">Aktif</label>':'<label class="badge bg-red">Tidak Aktif</label>');
						})
						->editColumn('foto',function($data,$row){
							$url = base_url((empty($data)?'assets/images/duser.png':$data));
							return '<a href="'.$url.'" target="_blank"><img width="50" class="img img-responsive" src="'.$url.'"></>';
						})
						->addColumn('act',function($row){
							$params = implode('/', [$row['user_id'],$row['nama']]);
							$btn = '<button type="button" data-title="Form Ubah User" data-href="'.site_url('master/users/edit/'.$params).'" class="btn mb-2 btn-warning btn-sm btn_modal_form" title="Ubah Data"><i class="fa fa-edit"></i></button> ';
							$btn.= '<button type="button" data-title="Detail User" data-href="'.site_url('master/users/detail/'.$params).'" class="btn mb-2 btn-info btn-sm btn_modal_form" title="Detail Data"><i class="fa fa-eye"></i></button> ';
							$btn.= '<button type="button" data-href="'.site_url('master/users/reset-password/'.$params).'" class="btn mb-2 bg-indigo btn-sm btn_reset_pwd" title="Reset Password"><i class="fa fa-lock"></i></button> ';
							$btn.= '<button type="button" data-href="'.base_url("master/users/hapus/".$params).'" class="btn mb-2 btn-danger btn-sm btn-del" title="Hapus Data"><i class="fa fa-trash"></i></button>';
							return $btn;
						})
						->removeColumns(['user_id','password'])
						->table($this->m_user->table)->draw();
		}else{
			$this->view('master/users/table');
		}
	}
	function tambah(){
		$layanan = $this->ms_pl->get();
		$fungsi=(!can_access('su'))?[]:$this->db->get('fungsi')->result();
		$this->load->view('master/users/form',compact('layanan','fungsi'));
	}
	function edit($id,$nama){
		$layanan = $this->ms_pl->get();
		$data = $this->m_user->findOrFail($id);
		$data->akses_pelayanan = json_decode($data->akses_pelayanan,true);
		$fungsi=(!can_access('su'))?[]:$this->db->get('fungsi')->result();
		$this->load->view('master/users/form', compact('data','layanan','fungsi'));
	}
	function detail($id,$nama){
		$this->db->select('users.*, fungsi.nama as nm_fungsi');
		$this->db->join('fungsi','fungsi.id=users.fungsi','left');
		$data = $this->m_user->findOrFail($id);
		$data->akses_pelayanan = json_decode($data->akses_pelayanan,true);
		$layanan = [];
		$data->foto = base_url((empty($data->foto)?'assets/images/duser.png':$data->foto));
		if(!in_array('all',$data->akses_pelayanan)){
			$this->db->where_in('pelayanan_id',$data->akses_pelayanan);
			$layanan = $this->ms_pl->get();
		}
		$this->load->view('master/users/detail', compact('data','layanan'));
	}
	function hapus($id,$nama){
		$data = $this->m_user->findOrFail($id);
		if($this->m_user->delete($id)){
			setFlash('success','Data User `'.$data->nama.'` berhasil dihapus.');
		}else{
			setFlash('error','Data User `'.$data->nama.'` gagal dihapus.');
		}
		redirect('master/users');
	}
	function simpan_data(){
		$target = 'master/users';
		$id = $this->input->post('ref');
		//print_r($_POST); exit();
		$this->load->library('form_validation');
		$this->form_validation->set_rules([
			[
				'field' => 'user',
                'label' => 'Username',
                'rules' => 'trim|required|min_length[3]'
			],[
				'field' => 'nama',
                'label' => 'Nama User',
                'rules' => 'trim|required|min_length[3]'
			],[
				'field' => 'akses',
                'label' => 'User Akses',
                'rules' => 'required'
			],[
				'field' => 'nip',
                'label' => 'NIP',
                'rules' => 'required'
			],[
				'field' => 'jabatan',
                'label' => 'Jabatan',
                'rules' => 'required'
			]
		]);
		if ($this->form_validation->run()){
			$user = $this->input->post('user',TRUE);
			$cek = $this->m_user->first(['user_id !='=>$id,'username'=>$user]);
			if(!empty($cek)){
				setFlash('error','Username `'.$user.'` already exists.');
				redirect($target);
			}
			$akses_pelayanan = [];
			if($this->input->post('slc_akses',TRUE)=='all'){
				$akses_pelayanan=['all'];
			}else{
				$akses_pelayanan = $this->input->post('akses_layanan',TRUE);
			}
			$data = [
				'username' => $user,
				'nama' => $this->input->post('nama',TRUE),
				'nip' => $this->input->post('nip',TRUE),
				'jabatan' => $this->input->post('jabatan',TRUE),
				'jabatan_en' => $this->input->post('jabatan_en',TRUE),
				'akses' => $this->input->post('akses',TRUE),
				'aktif' => intval($this->input->post('status',TRUE)),
				'akses_pelayanan' => json_encode($akses_pelayanan),
				'kode_report' => $this->input->post('kode_report'),
			];
			if(empty($id)){
				$data['user_id'] = 'US'.timeCode();
				$data['password'] = pwd_enc($data['username']);
				$data['fungsi'] = can_access('su')?$this->input->post('fungsi'):getSession('fungsi');
				if($this->m_user->insert($data)){
					setFlash('success','Master User berhasil disimpan. default password menggunakan username.');
				}else{
					setFlash('error','Master User gagal disimpan.');
				}
			}else{
				if($this->m_user->update($id,$data)){
					setFlash('success','Master User berhasil disimpan.');
				}else{
					setFlash('error','Master User gagal disimpan..');
				}
			}
		}else{
			setFlash('error',$this->form_validation->error_string());
		}
		redirect($target);
	}
	function reset_password($id,$nama){
		$dt = $this->m_user->findOrFail($id);
		$data = ['password'=>pwd_enc($dt->username)];
		$target = 'master/users';
		if($this->m_user->update($dt->user_id,$data)){
			setFlash('success','Password User `'.$dt->username.'` berhasil direset ke default. default password adalah username.');
		}else{
			setFlash('error','Password User `'.$dt->username.'` gagal direset ke default.');
		}
		redirect($target);
	}
}
