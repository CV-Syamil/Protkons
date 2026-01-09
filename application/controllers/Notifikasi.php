<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi extends BASE_Controller {
	function __construct(){
		parent::__construct();
	}
    function index(){
		$usr = getSession('ref');
		$data = $this->db->where('ke',$usr)->order_by('has_read','ASC')->order_by('waktu','DESC')->get('notifikasi')->result();
		$this->view('notifikasi_table',compact('data'));
    }

	function read_all(){
		$usr = getSession('ref');
		$t = $_SERVER['HTTP_REFERER'];
		if($this->db->where('ke',$usr)->update('notifikasi',['has_read'=>1])){
			setFlash('success','Semua Notifikasi telah dibaca.');
		}else{
			setFlash('error','Gagal membaca semua Notifikasi.');
		}

		redirect(empty($t)?'dashboard':$t);
	}

    function baca($ref,$d){
        $data = $this->db->where('id',$ref)->get('notifikasi')->row();
        if(empty($data)){ e404(); }
        else{
            $t = $_SERVER['HTTP_REFERER'];
            $this->db->where('id',$ref)->update('notifikasi',['has_read'=>1]);
            if(empty($data->href)){
                setFlash('success','Notifikasi telah dibaca');
            }else{
                $t = $data->href;
            }
            redirect(empty($t)?'dashboard':$t);
        }
    }

	function getnotifikasi(){
		$last = intval($this->input->post('last'));
        $usr = getSession('ref');
		$msg=[];
		$dmsg = $this->db->where('ke',$usr)->where('id >',$last)->where('has_read',0)->order_by('waktu','DESC')->limit(5)->get('notifikasi')->result();
		$cmsg = $this->db->select('1')->where('ke',$usr)->where('has_read',0)->get('notifikasi')->num_rows();
		$last = (empty($dmsg))?$last:$dmsg[0]->id;
		foreach ($dmsg as $v) {
			$msg[] = [
				't' => $v->title,
				'm' => $v->message,
				'w' => date('d F Y | H:i',strtotime($v->waktu)),
                'l' => site_url('notifikasi/baca/'.$v->id.'/notifikasi')
			];
		}
		response_json(compact('cmsg','msg','last'));
	}

	function testnotif($u=""){
        $u = empty($u)?getSession('ref'):$u;
		sendNotif($u,'Test Message from API','Test Notifikaksi');
	}
}
