<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	/*
	asds
	*/
	function jenis_identitas_str(){
		$this->load->model('MasterJenisIdentitas','ms_jenis');
		$ret=[];
		foreach ($this->ms_jenis->get() as $v) {
			$ret[]=[$v->jenis, $v->jenis];
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}

	function petugas($tipe){
		$this->load->model('Users_model','m_user');
		$term = $this->input->get('term',TRUE);
		$ret=[];
		if(!empty($term)){$this->db->where('nama like',"%$term%");}
		foreach ($this->m_user->get(['akses'=>$tipe]) as $user) {
			$ret[]=[
				'id' => $user->user_id,
				'text' => $user->nama
			];
		}
		header('Content-Type: application/json');
		echo json_encode(['results'=>$ret]);
	}
	function negeri_provinsi($ref=""){
		switch (strtolower($ref)) {
		 	case 'indonesia':
		 			$this->load->model('IdnProvinsi','model');
		 			// $resp = [['',"-- Pilih Provinsi Indonesia --"]];
		 			$resp = [];
		 			foreach ($this->model->get() as $data) {
		 				$resp[]=[$data->name,$data->name];
		 			}
					header('Content-Type: application/json');
		 			echo json_encode($resp);
		 		break;
		 	case 'malaysia':
		 			$this->load->model('MysNegeri','model');
		 			// $resp = [['',"-- Pilih Negeri Malaysia --"]];
		 			$resp = [];
		 			foreach ($this->model->get() as $data) {
		 				$resp[]=[$data->nama,$data->nama];
		 			}
					header('Content-Type: application/json');
		 			echo json_encode($resp);
		 		break;

		 	default:
		 			if($this->input->is_ajax_request()){echo "";}else{show_404();}
		 		break;
		 }
	}
	function daerah_kota($ref="",$ref2=""){
		$ref2 = urldecode($ref2);
		switch (strtolower($ref)) {
		 	case 'indonesia':
		 			$this->load->model('IdnProvinsi','model_ref');
		 			$this->load->model('IdnKota','model');
		 			// $resp = [['',"-- Pilih Kota/Kabupaten Indonesia --"]];
		 			$resp = [];
		 			$ref_x = $this->model_ref->first(['name'=>$ref2]);
					if(!empty($ref_x)){
						foreach ($this->model->get(['province_id'=>$ref_x->id]) as $data) {
							$resp[]=[$data->name,$data->name];
						}
					}
					header('Content-Type: application/json');
		 			echo json_encode($resp);
		 		break;
		 	case 'malaysia':
		 			$this->load->model('MysNegeri','model_ref');
		 			$this->load->model('MysDaerah','model');
		 			$ref_x = $this->model_ref->first(['nama'=>$ref2]);
		 			// $resp = [['',"-- Pilih Daerah Malaysia --"]];
		 			$resp = [];
					if(!empty($ref_x)){
						foreach ($this->model->get(['negeri_id'=>$ref_x->id]) as $data) {
							$resp[]=[$data->nama,$data->nama];
						}
					}
					header('Content-Type: application/json');
		 			echo json_encode($resp);
		 		break;

		 	default:
		 			if($this->input->is_ajax_request()){echo "";}else{show_404();}
		 		break;
		 }
	}
	function distrik_kecamatan($ref="",$ref2=""){
		$ref2 = urldecode($ref2);
		switch (strtolower($ref)) {
		 	case 'indonesia':
		 			$this->load->model('IdnKota','model_ref');
		 			$this->load->model('IdnKecamatan','model');
		 			// $resp = [['',"-- Pilih Kota/Kabupaten Indonesia --"]];
		 			$resp = [];
		 			$ref_x = $this->model_ref->first(['name'=>$ref2]);
					if(!empty($ref_x)){
						foreach ($this->model->get(['city_id'=>$ref_x->id]) as $data) {
							$resp[]=[$data->name,$data->name];
						}
					}
					header('Content-Type: application/json');
		 			echo json_encode($resp);
		 		break;
		 	case 'malaysia':
		 			$this->load->model('MysDaerah','model_ref');
		 			$this->load->model('MysDistrik','model');
		 			$ref_x = $this->model_ref->first(['nama'=>$ref2]);
		 			// $resp = [['',"-- Pilih Daerah Malaysia --"]];
		 			$resp = [];
					if(!empty($ref_x)){
						foreach ($this->model->get(['daerah_id'=>$ref_x->id]) as $data) {
							$resp[]=[$data->nama,$data->nama];
						}
					}
					header('Content-Type: application/json');
		 			echo json_encode($resp);
		 		break;

		 	default:
		 			if($this->input->is_ajax_request()){echo "";}else{show_404();}
		 		break;
		 }
	}
	function provinsi_txt(){
		$this->load->model('IdnProvinsi','model');
		$resp = [];
		foreach ($this->model->get() as $data) {
			$resp[]=[$data->name,$data->name];
		}
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	function kota_txt(){
		$this->load->model('IdnProvinsi','model_ref');
		$this->load->model('IdnKota','model');
		$resp = [];
		$ref_x = $this->model_ref->first(['name'=>@$_GET['ref']]);
		if(!empty($ref_x)){
			foreach ($this->model->get(['province_id'=>@$ref_x->id]) as $data) {
				$resp[]=[$data->name,$data->name];
			}
		}
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	function kecamatan_txt(){
		$this->load->model('IdnKota','model_ref');
		$this->load->model('IdnKecamatan','model');
		$resp = [];
		$ref_x = $this->model_ref->first(['name'=>@$_GET['ref']]);
		if(!empty($ref_x)){
			foreach ($this->model->get(['city_id'=>@$ref_x->id]) as $data) {
				$resp[]=[$data->name,$data->name];
			}
		}
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	function desa_txt(){
		// print_r($this->input->get());exit();
		$this->load->model('IdnKecamatan','model_ref');
		$this->load->model('IdnKota','model_ref2');
		$resp = [];
		$ref_x2 = $this->model_ref2->first(['name'=>@$_GET['refp']]);
		$ref_x = $this->model_ref->first(['name'=>@$_GET['ref'],'city_id'=>@$ref_x2->id]);
		if(!empty($ref_x)){
			foreach ($this->db->where('district_id',$ref_x->id)->get('indonesia_desa')->result() as $data) {
				$resp[]=['id'=>$data->name,'text'=>$data->name];
			}
		}
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	function negeri_txt(){
		$this->load->model('MysNegeri','model');
		$resp = [];
		foreach ($this->model->get() as $data) {
			$resp[]=[$data->nama,$data->nama];
		}
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	function daerah_txt(){
		$this->load->model('MysNegeri','model_ref');
		$this->load->model('MysDaerah','model');
		$ref_x = $this->model_ref->first(['nama'=>@$_GET['ref']]);
		$resp = [];
		if(!empty($ref_x)){
			foreach ($this->model->get(['negeri_id'=>@$ref_x->id]) as $data) {
				$resp[]=[$data->nama,$data->nama];
			}
		}
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	function distrik_txt(){
		$this->load->model('MysDaerah','model_ref');
		$this->load->model('MysDistrik','model');
		$ref_x = $this->model_ref->first(['nama'=>@$_GET['ref']]);
		$resp = [];
		if(!empty($ref_x)){
			$dt = $this->model->get(['daerah_id'=>@$ref_x->id]);
			if(empty($dt)){
				$resp[]=['','-----------'];
			}else{
				foreach ($dt as $data) {
					$resp[]=[$data->nama,$data->nama];
				}
			}
		}
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	function list_tempat_lahir(){
		$s = $this->input->post('s');
		$where = "";
		if(!empty($s)){
			$where = 'WHERE nama LIKE "%'.$s.'%"';
		}
		$data = $this->db->query("SELECT nama FROM (SELECT ik.name as nama FROM indonesia_kota ik UNION SELECT mn.nama FROM master_negeri mn) tbl $where LIMIT 10");
		$resp = [];
		foreach ($data->result() as $key => $value) { $resp[] = strtoupper($value->nama);}
		echo json_encode(['status'=>200,'message'=>'ok','data'=>$resp]);
	}

	function get_layanan(){
		$this->load->model('Master_pelayanan','pl');
		$s = $this->input->post('s');
		if(!empty($s)){
			$this->db->where('kode_layanan LIKE',"%$s%");
			$this->db->or_where('pelayanan LIKE',"%$s%");
		}
		$this->db->limit(15);
		$results = $this->pl->select('pelayanan_id as id, CONCAT(kode_layanan," - ",pelayanan) as text')->get();
		if(!empty($this->input->post('i'))){
			$idn = ['provinsi'=>'Provinsi','kota'=>'Kota / Kabupaten','kecamatan'=>'Kecamatan'];
			$mys = ['negeri'=>'Negeri','daerah'=>'Daerah','distrik'=>'Distrik'];
			foreach($results as $i => $d){
				$items = [];
				$q = "SELECT `field_name`, `label`, `field_type`, `data` FROM master_pelayanan_field WHERE pelayanan_id='$d->id' AND field_type NOT IN ('date','time','separator')";
				foreach($this->db->query($q)->result() as $item){
					switch ($item->field_type) {
						case 'db_identitas':
								foreach(explode('||',$item->data) as $x){
									$items[] = [
										'field_name' => $x.'_'.$item->field_name,
										'label' => $item->label.' - '.identitas_field($x)
									];
								}
							break;
						case 'db_wilayah_id':
								foreach(explode('||',$item->data) as $x){
									$items[] = [
										'field_name' => $x.'_'.$item->field_name,
										'label' => $item->label.' - '.@$idn[$x]
									];
								}
							break;
						case 'db_wilayah_my':
								foreach(explode('||',$item->data) as $x){
									$items[] = [
										'field_name' => $x.'_'.$item->field_name,
										'label' => $item->label.' - '.@$mys[$x]
									];
								}
							break;
						default:
								$items[] = [
									'field_name' => $item->field_name,
									'label' => $item->label
								];
							break;
					}
				}
				$results[$i]->data_items = $items;
			}
		}
		response_json(compact('results'));
	}

	function get_person(){
		$this->load->model('DataIdentitas','dt');
		$s = $this->input->post('s');
		if(!empty($s)){
			$this->db->where('no_identitas LIKE',"%$s%");
			$this->db->or_where('nama LIKE',"%$s%");
		}
		$this->db->limit(15);
		$ref = ($this->input->post('ref')=='noid')?'no_identitas as id':'id';
		$results = $this->dt->select($ref.', CONCAT(no_identitas," - ",nama) as text')->get();
		response_json(compact('results'));
	}
	function get_kode_layanan(){
		$this->load->model('Master_pelayanan','dt');
		$s = $this->input->post('s');
		if(!empty($s)){
			$this->db->where('kode_layanan LIKE',"%$s%");
			$this->db->or_where('pelayanan LIKE',"%$s%");
		}
		$this->db->limit(15);
		$results = $this->dt->select('kode_layanan as id, CONCAT("( ",kode_layanan," ) - ",pelayanan) as text')->get();
		response_json(compact('results'));
	}
	function get_field_pelayanan($kode){
		// $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$this->load->model('Master_pelayanan_field','fl');
		$this->db->select('field_name, field_type, label, data');
		$this->db->where('field_type!=','separator');
		$this->db->where('pelayanan_id',$kode);
		$data = $this->fl->get();
		$results=[];
		foreach ($data as $v) {
			$fname = $v->field_name;
			$icon = ($v->field_type=='db_identitas')?'fa-user':((in_array($v->field_type,['db_wilayah_id','db_wilayah_my']))?'fa-globe':'fa-font');
			$results[]=[
				'id' => $fname,
				'icon' => "fa $icon text-success",
				'parent' => '#',
				'text' => $v->label,
			];
			if(in_array($v->field_type,['db_identitas','db_wilayah_id','db_wilayah_my'])){
				if(empty($v->data)){
					array_pop($results);
				}else{
					foreach (explode('||',$v->data) as $fl) {
						$results[]=[
							'id' => $fl.'_'.$fname,
							'icon' => "fa $icon text-info",
							'parent' => $fname,
							'text' => identitas_field($fl)
						];
					}
				}
			}
		}
		response_json($results);
	}

	function generate_password(){
		$p = $this->input->get_post('pwd');
		$p = empty($p)?'1234':$p;
		
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode([
			'text' => $p,
			'hash' => pwd_enc($p)
		]);
	}

	function get_wilayah(){
		$s = $this->input->post('s',TRUE);
		$q = "SELECT p.`name` as prov, k.`name` as kota FROM indonesia_kota k LEFT JOIN indonesia_provinsi p ON(p.id=k.province_id) WHERE k.name LIKE '%$s%' LIMIT 15";
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($this->db->query($q)->result());
	}

	function get_identitas(){
		$this->load->model('DataIdentitas','di');
		$w = $this->input->post_get('no',TRUE);
		$data = $this->di->first(['no_identitas'=>$w]);
		response_json($data);
	}

	function auth_user(){
		$usr = $this->input->post('user',TRUE);
		$pwd = $this->input->post('pwd',TRUE);
		$data = $this->db->where('username',$usr)->where('aktif',1)->get('users')->row();
		$status = 404; $message = 'Username / Password yang anda masukkan salah';
		if(!empty($data)){
			if(password_verify($pwd, $data->password)){
				$status = 200;
				$message = 'OK';
				$data = [
					'ref' => $data->user_id,
					'username' => $data->username,
					'nama' => $data->nama,
					'akses' => $data->akses
				];
			}else{
				$data = NULL;
			}
		}else{
			$data = NULL;
		}

		response_json(compact('status','message','data'));
	}

	function get_all_pelayanan(){
		$status = 200; $message = 'OK';
		$this->load->model('Master_pelayanan','pl');
		$data = $this->pl->select('pelayanan_id,kode_layanan,pelayanan,biaya,show_jml')->get();
		response_json(compact('status','message','data'));
	}
	function get_layanan_item(){
		$this->load->model('Master_pelayanan','pl');
		$pl = $this->input->post('pl');
		$idn = ['provinsi'=>'Provinsi','kota'=>'Kota / Kabupaten','kecamatan'=>'Kecamatan'];
		$mys = ['negeri'=>'Negeri','daerah'=>'Daerah','distrik'=>'Distrik'];
		
		$items = [];
		$q = "SELECT `field_name`, `label`, `field_type`, `data`, `required`,`notes` FROM master_pelayanan_field WHERE pelayanan_id='$pl' AND field_type NOT IN ('separator')";
		foreach($this->db->query($q)->result() as $item){
			switch ($item->field_type) {
				case 'db_identitas':
						$fields=[];
						foreach(explode('||',$item->data) as $x){
							$fields[] = [
								'field_name' => $x.'_'.$item->field_name,
								'label' => identitas_field($x),
								'required' => (!empty($item->required))
							];
						}
						$items[] = [
							'label' => $item->label,
							'fields' => $fields,
							'notes' => $item->notes,
							'type' => 'identitas',
						];
					break;
				case 'db_wilayah_id':
						$fields=[];
						foreach(explode('||',$item->data) as $x){
							$fields[] = [
								'field_name' => $x.'_'.$item->field_name,
								'label' => @$idn[$x],
								'required' => (!empty($item->required))
							];
						}
						$items[] = [
							'label' => $item->label,
							'fields' => $fields,
							'notes' => $item->notes,
							'type' => 'wilayah_id',
						];
					break;
				case 'db_wilayah_my':
						$fields=[];
						foreach(explode('||',$item->data) as $x){
							$fields[] = [
								'field_name' => $x.'_'.$item->field_name,
								'label' => @$mys[$x],
								'required' => (!empty($item->required))
							];
						}
						$items[] = [
							'label' => $item->label,
							'fields' => $fields,
							'notes' => $item->notes,
							'type' => 'wilayah_my',
						];
					break;
				case 'select':
						$opt=[];
						$items[] = [
							'field_name' => $item->field_name,
							'label' => $item->label,
							'opts' => explode('||',$item->data),
							'notes' => $item->notes,
							'required' => (!empty($item->required)),
							'type' => 'select',
						];
					break;
				default:
						$items[] = [
							'field_name' => $item->field_name,
							'label' => $item->label,
							'notes' => $item->notes,
							'required' => (!empty($item->required)),
							'type' => $item->field_type,
						];
					break;
			}
		}
		response_json(['data'=>$items]);
	}
}