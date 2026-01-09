<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Identitas extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->active_menu = 'master_identitas_main_server';
		$this->load->model('Master_pelayanan','ms_pl');
		$this->load->model('Tr_pelayanan','tr_pl');
	}

	function index(){
		$this->view('server_master/table_identitas');
	}

	function data(){
		if($this->input->is_ajax_request()){
			$s = $this->input->post('cari',TRUE);
			header('Content-Type: application/json; charset=utf-8');
			echo $this->send_curl($s);
		}else{
			show_404();
		}
	}
	private function send_curl($s){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,MAIN_SERVER.'identitas');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, ['cari'=>$s,'token'=>TOKEN_SERVER]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$result=curl_exec ($ch);
		if(curl_errno($ch)){
			return curl_error($ch);
		}
		curl_close ($ch);
		return $result;
	}
	function tambah(){
		$post = $this->input->post();
		if(empty($post)){ response_json(['status'=>406,'message'=>"INVALID REQUEST"]); die(); }
		$noid = $this->input->post('no_identitas',TRUE);
		$cek = $this->db->where('no_identitas',$noid)->get('master_identitas')->row();
		if(empty($cek)){
			if($this->db->insert('master_identitas',$post)){
				response_json(['status'=>200,'message'=>"Data Berhasil ditambahkan"]); die();
			}
		}else{
			if($this->db->where('no_identitas',$noid)->update('master_identitas',$post)){
				response_json(['status'=>200,'message'=>"Data Berhasil diperbarui"]); die();
			}
		}
		response_json(['status'=>500,'message'=>"Data Gagal disimpan"]); die();
	}

}
