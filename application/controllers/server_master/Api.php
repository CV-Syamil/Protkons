<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->active_menu = 'identitas';
		$this->load->model('Master_pelayanan','ms_pl');
		$this->load->model('Tr_pelayanan','tr_pl');
	}

	function singkron_pelayanan(){
		$tm = microtime(TRUE);

		$this->load->helper('file');
		$tgl_i = $this->input->post_get('tgl',TRUE);
		$tgl = ((bool)strtotime($tgl_i))?date('Y-m-d',strtotime($tgl_i)):date('Y-m-d',strtotime('-1 days'));
		$perpage = 100;
		$page = 0;
		$loop = true;
		$response = [];

		$path = FCPATH.'assets/json/';
		if (!is_dir($path)) { mkdir($path); }

		while ($loop) {
			$page++;
			$s = ($page*$perpage)-$perpage;

			$q = "SELECT
					tr.*,
					i.jenis_identitas as ji_pelapor,
					i.no_identitas as noid_pelapor,
					i.nama as nama_pelapor,
					pl.kode_layanan,
					pl.pelayanan
				FROM
					tr_pelayanan tr
					JOIN master_pelayanan pl ON ( pl.pelayanan_id = tr.pelayanan_id )
					JOIN master_identitas i ON (
					i.id = tr.pelapor)
				WHERE DATE(tr.updated_at)='$tgl' AND tr.status > 0 LIMIT $s, $perpage";

			$q2 = "SELECT
				j.id as idx, j.tanggal, j.jumlah, j.created_at, j.updated_at, j.deleted_at, pl.kode_layanan, pl.pelayanan as nama_layanan
			FROM
				jml_pelayanan j
				JOIN master_pelayanan pl ON ( pl.pelayanan_id = j.pelayanan )
			WHERE DATE(j.updated_at)>='$tgl' LIMIT $s, $perpage";

			$dpl = $this->db->query($q)->result();
			$itempl=[];
			$dplids = [];
			if(!empty($dpl)){
				$dplids = array_column($dpl,'id');
				$itempl = $this->db->where_in('tr_pelayanan_id',$dplids)->get('tr_pelayanan_item')->result();
			}
			$jpl = $this->db->query($q2)->result();

			if(count($dplids)<$perpage&&count($jpl)<$perpage){$loop=FALSE;}

			$data = json_encode([
				'pelayanan' => $dpl,
				'pelayanan_item' => $itempl,
				'identitas' => [],
				'jml_pl' => $jpl,
			]);
			// $response[]=$data;
			// header('Content-Type: application/json; charset=utf-8');
			// echo $data;
			// exit();
			// print_r(json_decode($data,true)); exit();
			
			$file = date('ymd',strtotime($tgl)).$page.'.json';
			if(file_put_contents($path.$file,$data)){
				// chmod($path.$file, 0777); 
				$r = $this->send_curl($path.$file,$file);
				if(!empty($r)&&is_object($r)){
					$dt = [
						'tanggal' => $tgl,
						'sending' => (($r->status==200)?1:0),
						'file' => @$r->file,
						'message' => $r->status.': '.$r->message
					];
					$this->db->insert('singkronisasi',$dt);
					unlink($path.$file);
					// echo $r->message;
					if($r->status!=200){ $loop=FALSE; }
					$response['response'][] = ['status'=>$r->status,'message'=>$r->message];
				}else{
					$this->db->insert('singkronisasi',[
						'tanggal' => $tgl,
						'sending' => 0,
						'file' => $path.$file,
						'message' => 'error uploading file'
					]);
					// echo 'error uploading file';
					// echo $r;
					$response['response'][] = ['status'=>500,'message'=>'Error Uploading File','html'=>$r];
					$loop = FALSE;
				}
			}else{
				$response['response'][] = ['status'=>500,'message'=>'Error Generate File JSON'];
				$loop = FALSE;
				// echo "ERROR Generate File";
			}
		}
		$response['sucess'] = TRUE;
		$response['excecution_date'] = $tgl;
		$response['excecution_time'] = round(microtime(TRUE) - $tm,2).'ms';
		// print_r($response);
		response_json($response);
	}

	function singkron_identitas(){
		$tm = microtime(TRUE);

		$this->load->helper('file');
		$tgl_i = $this->input->post_get('tgl',TRUE);
		$tgl = ((bool)strtotime($tgl_i))?date('Y-m-d',strtotime($tgl_i)):date('Y-m-d',strtotime('-1 days'));
		$perpage = 500;
		$page = 0;
		$loop = true;
		$response = [];

		$path = FCPATH.'assets/json/';
		if (!is_dir($path)) { mkdir($path); }

		while ($loop) {
			$page++;
			$s = ($page*$perpage)-$perpage;
			$q = "SELECT * FROM master_identitas WHERE DATE(updated_at)>='$tgl' LIMIT $s, $perpage";
			$data = $this->db->query($q)->result();

			if(count($data)<$perpage){$loop=FALSE;}
			foreach ($data as $key => $value) {
				unset($data[$key]->id);
				$data[$key]->tgl_lahir = (intval(date('Y',strtotime($value->tgl_lahir)))<1970)?'1990-01-01':$value->tgl_lahir;
			}
			$data = json_encode([
				'identitas' => $data
			]);
			$file = 'identitas'.date('ymd',strtotime($tgl)).$page.'.json';
			if(file_put_contents($path.$file,$data)){
				if(!file_exists($path.$file)){ $response[] = ['status'=>404,'message'=>'No FILE']; $loop = FALSE; }
				chmod($path.$file, 0777);
				$r = $this->send_curl($path.$file,$file);
				// echo $r;
				// $loop = false;
				if(!empty($r)&&is_object($r)){
					$dt = [
						'tanggal' => $tgl,
						'sending' => (($r->status==200)?1:0),
						'file' => @$r->file,
						'message' => $r->status.': '.$r->message
					];
					$this->db->insert('singkronisasi',$dt);
					if($r->status==200){ unlink($path.$file); }
					// echo $r->message;
					if($r->status!=200){ $loop=FALSE; }
					$response[] = ['status'=>$r->status,'message'=>$r->message];
				}else{
					$this->db->insert('singkronisasi',[
						'tanggal' => $tgl,
						'sending' => 0,
						'file' => $path.$file,
						'message' => 'error uploading file'
					]);
					// echo 'error uploading file';
					// echo $r;
					$response[] = ['status'=>500,'message'=>'Error Uploading File','html'=>$r];
					$loop = FALSE;
				}
			}else{
				$response[] = ['status'=>500,'message'=>'Error Generate File JSON'];
				$loop = FALSE;
				// echo "ERROR Generate File";
			}
		}
		$response['excecution_time'] = round(microtime(TRUE) - $tm,2).'ms';
		// print_r($response);
		response_json($response);
	}

	private function send_curl($path_file,$nmfile){
		$cFile = curl_file_create($path_file,'application/json',$nmfile);
		$post = array('token'=>TOKEN_SERVER,'file'=> $cFile);
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,MAIN_SERVER.'singkron');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$result=curl_exec ($ch);
		// print_r($result); die();
		if(curl_errno($ch)){
			return curl_error($ch);
		}
		curl_close ($ch);
		$d = json_decode($result);
		if(json_last_error() === JSON_ERROR_NONE){
			return $d;
		}else{
			return $result;
		}
	}

	private function get_token(){
		if($this->session->has_userdata('main_server_token')){
			$this->token_server = $this->session->userdata('main_server_token');
		}
		if(empty($this->token_server)){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$this->url_server.'/token');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, ['kode'=>$this->kode]);
			$result=curl_exec ($ch);
			if(!empty($result)){ 
				$this->session->sess_expiration = '14400';
				$this->session->set_userdata('main_server_token',$result); 
			}
			$this->token_server = $result;
			curl_close ($ch);
		}
	}

}
