<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Tr_pelayanan','tr_pl');
		$this->load->model('Tr_pelayanan_item','tr_pl_item');
		$this->load->model('Master_pelayanan','ms_pl');
		$this->load->model('Master_pelayanan_field','pl_field');
		$this->load->model('DataIdentitas','identitas');
		$this->load->model('Users_model','user_m');
	}
	
	function pelayanan($act="view"){
		$this->active_menu = 'lap_pelayanan';
		$start = $this->input->post_get('start_date',TRUE);
		$end = $this->input->post_get('end_date',TRUE);
		$kond="";
		$user = getSession('ref');
		
		if(can_access('loket')){
			$kond.= "AND tr.petugas_loket='$user' ";
		}elseif(can_access('verifikasi')){
			// $kond.= "AND tr.petugas_verifikasi='$user' ";
		}elseif(can_access('hs')){
			// $kond.= "AND tr.hs='$user' ";
		}elseif(can_access('kasir')){
			// $kond.= "AND tr.kasir='$user' ";
		}

		$user_data = $this->user_m->findOrFail($user);
		$akses_pl = json_decode($user_data->akses_pelayanan,TRUE);
		$in_x = "('".implode("', '",$akses_pl)."')";
		if(!in_array('all',$akses_pl)){ $this->db->where_in('tr.pelayanan_id',$akses_pl); }

		if(!empty($start)&&!empty($end)){
			// $kond.= "AND DATE(tr.created_at) BETWEEN '$start' AND '$end'";
			$kond.= "AND DATE(tr.updated_at) BETWEEN '$start' AND '$end'";
		}
		if(strtolower($act)=='cetak'){
			$this->tr_pl->table = $this->tr_pl->table.' tr';
			$this->ms_pl->table.=' mp';
			$this->db->group_by('mp.pelayanan_id')
					 ->join('tr_pelayanan tr','(tr.pelayanan_id=mp.pelayanan_id AND tr.status=5 '.$kond.')','LEFT');
			$data = $this->ms_pl->select("mp.kode_layanan, mp.pelayanan, mp.biaya, count(tr.id) as jumlah, sum(tr.jml_berkas) as jml_berkas")->get();
			foreach ($data as $key => $value) {
				$data[$key]->total = intval($value->biaya) * intval($value->jml_berkas);
			}
			$this->load->view('laporan/pelayanan_cetak', compact('data','start','end'));
		}else{
			if($this->input->is_ajax_request()&&$this->input->method()=='post'){
				$this->load->library('Datatables', 'datatables');
				header('Content-Type: application/json');
				$this->db->group_by('mp.pelayanan_id');
				echo $this->datatables
					->select("mp.kode_layanan, mp.pelayanan, mp.biaya, count(tr.id) as jumlah, sum(tr.jml_berkas) as jml_berkas")
					->join("tr_pelayanan tr",'(tr.pelayanan_id=mp.pelayanan_id AND tr.status=5 '.$kond.')','LEFT')
					->editColumn('jumlah',function($data){ return numb($data); })
					->editColumn('jml_berkas',function($data){ return numb(intval($data)); })
					->editColumn('created_at',function($data){return tanggal_indo($data);})
					->addColumn('total_biaya',function($data){
						$total = intval($data['biaya']) * intval($data['jml_berkas']);
						return numb($total);
					})
					->table($this->ms_pl->table.' mp')->draw();
			}else{
				$this->view('laporan/pelayanan');
			}
		}
	}
	function keuangan($act="view"){
		$this->active_menu = 'lap_keuangan';
		$start = $this->input->post_get('start_date',TRUE);
		$end = $this->input->post_get('end_date',TRUE);
		$kond="";
		$user = getSession('ref');
		if(can_access('loket')){
			$kond.= "AND tr.petugas_loket='$user' ";
		}elseif(can_access('verifikasi')){
			// $kond.= "AND tr.petugas_verifikasi='$user' ";
		}elseif(can_access('hs')){
			$kond.= "AND tr.hs='$user' ";
		}elseif(can_access('kasir')){
			// $kond.= "AND tr.kasir='$user' ";
		}
		if(!empty($start)&&!empty($end)){
			// $kond.= "AND DATE(tr.created_at) BETWEEN '$start' AND '$end'";
			$kond.= "AND DATE(tr.updated_at) BETWEEN '$start' AND '$end'";
		}
		
		$user_data = $this->user_m->findOrFail($user);
		$akses_pl = json_decode($user_data->akses_pelayanan,TRUE);
		if(!in_array('all',$akses_pl)){ $this->db->where_in('tr.pelayanan_id',$akses_pl); }
		
		if(strtolower($act)=='cetak'){
			$this->tr_pl->table = $this->tr_pl->table.' tr';
			
			$this->ms_pl->table.=' mp';
			$this->db->group_by('mp.pelayanan_id')
			->join('tr_pelayanan tr','(tr.pelayanan_id=mp.pelayanan_id AND tr.status=5 '.$kond.')','LEFT');
			$data = $this->ms_pl->select("mp.pelayanan_id,mp.kode_layanan, mp.pelayanan, sum(tr.biaya * tr.jml_berkas) as jumlah")->get();
			// print_r($data); exit();
			$this->load->view('laporan/keuangan_cetak', compact('data','start','end'));
		}else{
			if($this->input->is_ajax_request()&&$this->input->method()=='post'){
				$this->load->library('Datatables', 'datatables');
				header('Content-Type: application/json');
				$this->db->group_by('mp.pelayanan_id');
				echo $this->datatables
				->select("mp.pelayanan_id,mp.kode_layanan, mp.pelayanan, sum(tr.biaya * tr.jml_berkas) as jumlah")
				->join("tr_pelayanan tr",'(tr.pelayanan_id=mp.pelayanan_id AND tr.status=5 '.$kond.')','LEFT')
				->editColumn('jumlah',function($data){return numb($data);})
				->table($this->ms_pl->table.' mp')->draw(TRUE);
			}else{
				$this->view('laporan/keuangan');
			}
		}
	}
	function per_pelayanan($v='view'){
		switch (strtolower($v)) {
			case 'view':
					$this->active_menu = 'lap_per_pelayanan';
					$pl=$this->ms_pl->get();
					$this->view('laporan/per_pelayanan',compact('pl'));
				break;
				case 'export':
					if($this->input->method()=='post'){
						$this->load->library('form_validation');
						$this->form_validation->set_rules([
							[
								'field' => 'tgl_s',
								'label' => 'Start Date',
								'rules' => 'trim|required'
							],[
								'field' => 'tgl_e',
								'label' => 'End Date',
								'rules' => 'trim|required'
							],[
								'field' => 'pl',
								'label' => 'Pelayanan',
								'rules' => 'trim|required'
							],
						]);
						$tgl_s = $this->input->post('tgl_s',TRUE);
						$tgl_e = $this->input->post('tgl_e',TRUE);
						$fields = $this->input->post('fields',TRUE);
						$tanggal = tanggal_indo($tgl_s).(($tgl_s==$tgl_e)?'':' - '.tanggal_indo($tgl_e));
						$pl = $this->input->post('pl',TRUE);
						$ms_pl = $this->ms_pl->findOrFail($pl);
						$where_field = empty($fields)?'':'AND tpi.field_name IN ("'.str_replace('|','","',$fields).'")';
						$query = "SELECT trp.id, trp.jml_berkas, trp.biaya, trp.created_at,
									mi.nama AS nm_pelapor, us.nama AS nm_hs, getNoDokumen(trp.id) as no_dokumen,";
						$query.= empty($fields)?'"[]" as dt_js ':" (
										SELECT JSON_ARRAYAGG(JSON_OBJECT('name',tpi.field_name,'value',tpi.field_value)) 
									 	FROM tr_pelayanan_item tpi WHERE tpi.tr_pelayanan_id=trp.id $where_field
									) AS dt_js ";
						$query .= " FROM tr_pelayanan trp 
										LEFT JOIN master_identitas mi ON (binary mi.id= binary trp.pelapor)
										LEFT JOIN users us ON (binary us.user_id= binary trp.hs)
									WHERE trp.status='5' AND trp.pelayanan_id='$pl' 
									AND DATE(trp.updated_at) BETWEEN '$tgl_s' AND '$tgl_e'
								";
								
						$user = getSession('ref');
						if(can_access('loket')){
							$query.= " AND trp.petugas_loket='$user' ";
						}elseif(can_access('verifikasi')){
							// $query.= " AND trp.petugas_verifikasi='$user' ";
						}elseif(can_access('hs')){
							$query.=" AND trp.hs='$user'";
						}elseif(can_access('kasir')){
							// $query.= " AND trp.kasir='$user' ";
						}
						$query.=" ORDER BY trp.no_dokumen ASC";
						$data_pl = $this->db->query($query)->result();
						// print_r([$data_pl,$query]);exit();
						$kolom = ['no_dokumen','jumlah_berkas','biaya','tanggal','nama_pelapor'];
						$kolom2 = [];
						$data_item=[];
						if(!empty($data_pl)){
							foreach ($data_pl as $dt) {
								$dt_js = json_decode($dt->dt_js);
								foreach ($dt_js as $v) {
									$data_item[$dt->id][$v->name]=$v->value;
									if(!in_array($v->name,$kolom2)){
										$kolom2[]=$v->name;
									}
								}
							}
						}
						$name = 'EXPORT-'.url_title(implode('',[$ms_pl->kode_layanan,$ms_pl->pelayanan,$tanggal]));
						header("Content-type: application/vnd-ms-excel");
						header("Content-Disposition: attachment; filename=$name.xls");
						$this->load->view('laporan/per_pelayanan_print',compact('data_pl','data_item','kolom','kolom2','ms_pl','tanggal'));
					}else{show404();}
				break;
			default: show404(); break;
		}
	}

	function pelapor($v='view'){
		$this->active_menu = 'lap_pelapor';
		$start = $this->input->post_get('tgl_s',TRUE);
		$end = $this->input->post_get('tgl_e',TRUE);
		$pl = $this->input->post_get('pl',TRUE);
		$jk = $this->input->post_get('jk',TRUE);
		$kota = $this->input->post_get('kota',TRUE);
		$wn = $this->input->post_get('wn',TRUE);

		$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		if(strtolower($v)=='cetak'){
			$w = "";
			$w.= empty($pl)?'':' AND tr.pelayanan_id="'.$pl.'"';
			$w.= empty($jk)?'':' AND mi.jk="'.$jk.'"';
			$w.= empty($kota)?'':' AND mi.kota="'.$kota.'"';

			$q = 	"SELECT
				tr.id, tr.created_at, tr.updated_at, tr.no_dokumen,
				mp.pelayanan,
				mi.no_identitas as no_pelapor, mi.nama as nm_pelapor,
				mi.provinsi, mi.kota, mi.kecamatan, mi.alamat_idn
			FROM
				tr_pelayanan tr
				JOIN master_pelayanan mp ON ( mp.pelayanan_id = tr.pelayanan_id )
				LEFT JOIN master_identitas mi ON ( mi.id = tr.pelapor )
			WHERE tr.status=5 $w
			";

			if(!empty($start)&&!empty($end)){
				$q.= " AND DATE(tr.updated_at) BETWEEN '$start' AND '$end'";
			}
			$data = $this->db->query($q." GROUP BY tr.pelapor")->result();

			$name = 'EXPORT-PELAYANAN-PELAPOR-'.url_title($start.$end);
			header("Content-type: application/vnd-ms-excel");
			header("Content-Disposition: attachment; filename=$name.xls");
			$this->load->view('laporan/per_pelapor_cetak', compact('data','start','end'));
		}else{
			if($this->input->is_ajax_request()&&$this->input->method()=='post'){
				$this->load->library('Datatables', 'datatables');
				header('Content-Type: application/json');
				$this->db->where("tr.status=5 AND DATE(tr.updated_at) BETWEEN '$start' AND '$end'");
				if(!empty($pl)){ $this->db->where('tr.pelayanan_id',$pl); }
				if(!empty($jk)){ $this->db->where('mi.jk',$jk); }
				if(!empty($kota)){ $this->db->where('mi.kota',$kota); }
				if(!empty($wn)){ $this->db->where("mi.kewarganegaraan ".(($wn=='wni')?"= 'Indonesia'":"!= 'Indonesia'")); }
				$this->db->group_by('tr.pelapor');
				echo $this->datatables
					->select("tr.id as kode_pl, 
								tr.created_at, tr.updated_at, tr.no_dokumen, 
								mp.pelayanan, mi.no_identitas as no_pelapor, 
								mi.nama as nm_pelapor,
								mi.provinsi, mi.kota")
					->join("master_pelayanan mp",'(tr.pelayanan_id=mp.pelayanan_id)')
					->join("master_identitas mi",'(mi.id = tr.pelapor)','LEFT')
					->editColumn('created_at',function($data){return tanggal_indo($data);})
					->editColumn('updated_at',function($data){return tanggal_indo($data);})
					->table($this->tr_pl->table.' tr')->draw(true);
			}else{
				$pl = $this->ms_pl->select('pelayanan_id, kode_layanan, pelayanan')->get();
				$this->view('laporan/per_pelapor', compact('pl'));
			}
		}
	}
}