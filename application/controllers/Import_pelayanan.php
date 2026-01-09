<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Settings;
use \PhpOffice\PhpWord\Element\Link;
class Import_pelayanan extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->active_menu = 'import_pelayanan';
		$this->load->model('Tr_pelayanan','tr_pl');
		$this->load->model('Tr_pelayanan_item','tr_pl_item');
		$this->load->model('Master_pelayanan','ms_pl');
		$this->load->model('Master_pelayanan_field','pl_field');
		$this->load->model('DataIdentitas','identitas');
		$this->load->model('Users_model','user_m');
	}
	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->proses();
		}else{
			$pl = $this->ms_pl->get();
			$this->view('import/table', compact('pl'));
		}
	}

	function template($id="",$type="json"){
		if(empty($id)){e404(); die();}
		$fields = ['no_dokumen','tgl_dokumen','jml_dokumen'];
		foreach (identitas_field() as $key => $value) { $fields[] = "pelapor_$key"; }

		$plf = $this->pl_field->get(['pelayanan_id'=>$id,'field_type !='=>'separator']);
		$meta=[];
		$wil_label = [
			'provinsi'=>'Provinsi','kota'=>'Kota / Kabupaten','kecamatan'=>'Kecamatan',
			'negeri'=>'Negeri','daerah'=>'Daerah','distrik'=>'Distrik'
		];
		foreach ($plf as $v) {
			switch ($v->field_type) {
				case 'db_identitas': case 'db_wilayah_my': case 'db_wilayah_id':
						if(!empty($v->data)){
							foreach (explode('||',$v->data) as $v2) {
								if(!in_array($v2,['umur'])){
									$fname = $v->field_name."_".$v2;
									$fields[]=$fname;
									$meta[$fname] = [
										'label' => $v->label.' - '.(($v->field_type=='db_identitas')?identitas_field($v2):@$wil_label[$v2]),
										'name' => $fname,
										'type' => $v->field_type,
									];
								}
							}
						}
					break;
				
				default:
						$fields[]=$v->field_name;
						$meta[$v->field_name] = [
							'label' => $v->label,
							'name' => $v->field_name,
							'type' => $v->field_type,
						];
					break;
			}
		}
		if($type=='json'){response_json(compact('fields'));}
		else{ return compact('fields','meta'); }
	}
	function proses(){
		// print_r('OK'); exit();
		$pl = $this->input->post('pl');
		$dt = $this->input->post('data');
		$pl = $this->ms_pl->first($pl);
		if(empty($pl)||empty($dt)){
			response_json(['status'=>204,'message'=>'Data Pelayanan / Data Import Kosong']);
		}else{
			$template = $this->template($pl->pelayanan_id,'array');
			$fields = $template['fields'];
			$meta_data = $template['meta'];
			// print_r($meta_data); exit();
			unset($fields['no_dokumen']);
			unset($fields['tgl_dokumen']);
			unset($fields['jml_dokumen']);
			$plpx = [];
			$plpdt = [];
			$trpl=[];
			$trplitem=[];
			foreach ($dt as $i => $v) {
				$trid = "TRI".timeCode().$i;
				if(!empty($v['pelapor_no_identitas'])){ $plpx[] = $v['pelapor_no_identitas']; }
				$tmp_item = [];
				foreach ($fields as $field) {
					if (strpos($field, 'pelapor_') !== false) {
						if(!in_array($field,['pelapor_umur'])){
							$plpdt[$i][str_replace('pelapor_','', $field)] = @$v[$field];
						}
					}elseif(!empty($v[$field])&&!empty($meta_data[$field])){
						$meta = $meta_data[$field];
						$tmp_item[]=[
							'tr_pelayanan_id' => $trid,
							'field_label' => $meta['label'],
							'field_name' => $meta['name'],
							'field_value' => $v[$field]
						];
					}
				}
				if(!empty($tmp_item)){
					$trpl[$i]=[
						'id'=> $trid,
						'pelayanan_id' => $pl->pelayanan_id,
						'biaya' => $pl->biaya,
						'status' => 5,
						'jml_berkas' => empty(intval($v['jml_dokumen']))?1:intval($v['jml_dokumen']),
						'no_dokumen' => empty($v['no_dokumen'])?0:intval($v['no_dokumen']),
						'created_at' => empty($v['tgl_dokumen'])?date('Y-m-d H:i:s'):date('Y-m-d H:i:s',strtotime($v['tgl_dokumen'])),
					];
					$trplitem = array_merge($trplitem,$tmp_item);
				}
			}
			$noids = [];
			$this->db->select('no_identitas')->where_in('no_identitas',$plpx);
			foreach ($this->identitas->get() as $v) { $noids[]=$v->no_identitas; }
			$plpdt = array_filter($plpdt,function($d)use($noids){
				return empty($noids)?TRUE:!in_array($d['no_identitas'],$noids);
			});
			// print_r(compact('plpx','plpdt','trpl','trplitem'));
			$this->db->trans_start();

				if(!empty($plpdt)){
					$this->db->insert_batch($this->identitas->table,$plpdt);
				}
				
				if(!empty($trpl)){
					$this->db->insert_batch($this->tr_pl->table,$trpl);
					if(!empty($trplitem)){
						$this->db->insert_batch($this->tr_pl_item->table,$trplitem);
					}
				}

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				response_json(['status'=>500,'message'=>'Data gagal disimpan. INTERNAL SERVER ERRORS.']);
			}else{
				if(empty($trpl)){
					response_json(['status'=>204,'message'=>'Transaksi Pelayanan kosong']);
				}else{
					response_json(['status'=>200,'message'=>'OK']);
				}
			}
		}
	}

	function panduan(){
		$v = $this->input->post('pl',TRUE);
		if(empty($v)){ echo ""; }
		else{
			$field = $this->pl_field->select('field_name, label, data, notes')->get(['field_type'=>'select','pelayanan_id'=>$v]);
			$this->load->view('import/panduan', compact('field'));
		}
	}
	function getwilayah(){
		$s = $this->input->post('s',TRUE);
		$t = $this->input->post('t',TRUE);
		$data = [];
		if($t=="wil_id"){
			$sql = "SELECT
					pv.name as v1,
					ko.name as v2,
					kc.name as v3
				FROM
					indonesia_kecamatan kc
					LEFT JOIN indonesia_kota ko ON ( ko.id = kc.city_id )
					LEFT JOIN indonesia_provinsi pv ON (pv.id = ko.province_id)
				WHERE 
					pv.name LIKE '%$s%' OR
					ko.name LIKE '%$s%' OR
					kc.name LIKE '%$s%'
				LIMIT 10
			";
			$data = $this->db->query($sql)->result();
		}
		if($t=="wil_my"){
			$sql = "SELECT
					n.nama as v1,
					d.nama as v2,
					ds.nama as v3
				FROM
					master_negeri n
					JOIN master_daerah d ON ( d.negeri_id = n.id )
					JOIN master_distrik ds ON (ds.daerah_id=d.id)
				WHERE 
					n.nama LIKE '%$s%' OR
					d.nama LIKE '%$s%' OR
					ds.nama LIKE '%$s%'
				LIMIT 10
			";
			$data = $this->db->query($sql)->result();
		}
		response_json(compact('t','data'));
	}
}
