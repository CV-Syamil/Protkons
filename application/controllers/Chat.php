<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->active_menu = 'chat_menu';
	}
    function index(){
        $user = $this->db->select('user_id, nama, foto')->where('user_id !=',getSession('ref'))->get('users')->result();
        $userChat=$this->getDataChatUser();
        // foreach ($user as $key => $value) { $user[$key]->foto = empty($value->foto)?'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTbNOpai32_rwcRrMxmpF4sNJG3CIR7yTPv7MD9qK4Ft5OdltMU6DymiRqxXRb0qtgGJoE&amp;usqp=CAU" class="img-circle elevation-1':$value->foto; }
        $this->view('chat/view',compact('user','userChat'));
    }

    private function getDataChatUser(){
        $ref = getSession('ref');
        $q = "SELECT * FROM (
            SELECT u.user_id, u.nama, u.foto 
            FROM chat_message cm
            LEFT JOIN users u ON ( u.user_id = cm.dari OR u.user_id = cm.ke ) 
            WHERE u.user_id != '$ref'
            ORDER BY cm.created_at ) t
            GROUP BY user_id";
        return $this->db->query($q)->result();
    }

    function cek_online(){
        $ref = getSession('ref');
        $cek = $this->db->where('user_id',$ref)->get('chat_user_online')->row();
        if(empty($cek)){
            $this->db->insert('chat_user_online',['user_id'=>$ref,'is_online'=>1,'updated_at'=>date('Y-m-d H:i:s')]);
        }else{
            $this->db->where('user_id',$ref)->update('chat_user_online',['is_online'=>1,'updated_at'=>date('Y-m-d H:i:s')]);
        }
        echo 'ok';
    }
    
    function get_chat(){
        $usr = getSession('ref');
        $usr2 = $this->input->post('usr');
        $lastchat = intval($this->input->post('last'));
        $chats = [];
        $last = $lastchat;
        $dt = $this->db->query("SELECT * FROM chat_message WHERE ((dari='$usr' AND ke='$usr2') OR (dari='$usr2' AND ke='$usr')) AND id > $lastchat ORDER BY created_at DESC LIMIT 100")->result();
        foreach (array_reverse($dt) as $v) {
            $chats[] = [
                'position' => ($v->dari==$usr?'right':'left'),
                'message' => $v->message,
                'time' => date('d F Y | H:i',strtotime($v->created_at))
            ];
            $last = $v->id;
        }
        $usr_is_online = FALSE;
        $dx = $this->db->where('user_id',$usr2)->get('chat_user_online')->row();
        if(!empty($dx)){
            $s = $this->dateDiffSec($dx->updated_at,date('Y-m-d H:i:s'));
            if($s<30){$usr_is_online=TRUE;}
        }
        response_json(compact('chats','usr_is_online','last'));
    }

    function send_message(){
        $usr = getSession('ref');
        $usr2 = $this->input->post('usr');
        $msg = $this->input->post('msg',TRUE);
        if($this->db->insert('chat_message',[
            'dari' => $usr,
            'ke' => $usr2,
            'message' => $msg,
            'created_at' => date('Y-m-d H:i:s')
        ])){
            response_json(['status'=>200,'message'=>'OK']);
        }else{
            response_json(['status'=>500,'message'=>'Gagal mengirim pesan']);
        }
    }
    private function dateDiffSec($d1,$d2){
        $start = new DateTime($d1);
        $end = new DateTime($d2);
        $diff = $start->diff($end);

        $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
        $hoursInSecs = $diff->h * 60 * 60;
        $minsInSecs = $diff->i * 60;

        return $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
    }
}
