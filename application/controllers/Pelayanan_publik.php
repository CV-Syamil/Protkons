<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Settings;
use \PhpOffice\PhpWord\Element\Link;
class Pelayanan_publik extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('KonsulerPublic');
		$this->active_menu = 'pelayanan_public';
	}
	function index(){
		$this->view('pelayanan_public/table');
	}

	function data(){
		$req = $_REQUEST;
		$kp = new KonsulerPublic;
		$kp->getDataPelayanan([
			'tgls' => $req['tgl'],
			'tgle' => $req['tgl2'],
			'pelayanan' => $req['pl'],
			'pelapor' => $req['pelapor'],
			'status' => $req['sts'],
		]);
		$data = $kp->getObject();
		// dd($data);
		$this->load->view('pelayanan_public/table_row',compact('data'));
	}

	function detail($ref){
		$kp = new KonsulerPublic;
		$kp->getDataPelayananItem($ref);
		$data = $kp->getObject();
		if(!empty($data->data)){
			$pl = $data->data->pelayanan;
			$items = $data->data->items;
			$this->load->view('pelayanan_public/detail',compact('pl','items'));
		}else{
			e404();
		}
	}
	function terima($ref){
		$kp = new KonsulerPublic;
		$kp->getDataPelayananItem($ref);
		$data = $kp->getObject();
		if(!empty($data->data)){
			$pl = $data->data->pelayanan;
			$plp = $data->data->pelayanan->pelapor;
			$items = $data->data->items;
			$this->db->trans_start();
			$pelapor = $this->db->where('no_identitas',$plp->no_identitas)->get('master_identitas')->row();
			$idpelapor = "";
			if(empty($pelapor)){
				$this->db->insert('master_identitas',$plp);
				$idpelapor = $this->db->insert_id();
			}else{
				$this->db->where('id',$pelapor->id)->update('master_identitas',$plp);
				$idpelapor = $pelapor->id;
			}
			$id = 'TRO'.timeCode();
			$this->db->insert('tr_pelayanan',[
				'id' => $id,
				'pelapor' => $idpelapor,
				'pelayanan_id' => $pl->pelayanan_id,
				'petugas_verifikasi' => getSession('ref'),
				'status' => 2,
				'jml_berkas' => 1,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			]);
			$insert_items = [];
			foreach($items as $v){
				$insert_items[]=[
					'tr_pelayanan_id' => $id,
					'field_label' => $v->field_label,
					'field_name' => $v->field_name,
					'field_type' => $v->field_type,
					'field_value' => $v->field_value,
					'field_data' => "",
				];
			}
			$this->db->insert_batch('tr_pelayanan_item',$insert_items);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				setFlash('error','Data gagal diperbarui');
			}else{
				$kp->terimaPelayanan($ref,$id);
				setFlash('success','Data berhasil diperbarui');
			}
			redirect(back_link('pelayanan-publik'));
		}else{
			e404();
		}
	}
	function tolak($ref){
		$kp = new KonsulerPublic;
		$kp->tolakPelayanan($ref,@$_GET['alasan']);
		if($kp->isError){
			setFlash('error','Data gagal diperbarui');
		}else{
			$resp = $kp->getObject();
			if($resp->status==200){
				setFlash('success','Data telah ditolak');
			}else{
				setFlash('error',$resp->message);
			}
		}
		redirect(back_link('pelayanan-publik'));
	}

}
