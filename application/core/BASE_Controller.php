<?php
class BASE_Controller extends CI_Controller {
	var $layout="layouts/admin";
	var $active_menu="dashboard";
	function __construct(){
		parent::__construct();
		if(getSession('login')!='wes_login'){
			if($this->input->is_ajax_request()){ header("HTTP/1.1 401 UNAUTHORIZED"); echo json_encode(['status'=>401,'message'=>'UNAUTHORIZED']);}
			else{redirect('login');}
		}elseif(!in_array(getSession('akses'),array_keys(user_akses()))&&!can_access('su')){
			redirect('logout');
		}
	}
	function view($view,$data=[]){
		$data['konten'] =(empty($view)?'':$this->load->view($view,$data,true));
		$data['active_menu'] = $this->active_menu;
		$this->load->view($this->layout,$data);
	}
}
