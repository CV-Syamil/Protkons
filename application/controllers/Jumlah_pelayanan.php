<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jumlah_pelayanan extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->active_menu = 'inp_jml_pelayanan';
		$this->load->model('Master_pelayanan','ms_pl');
		$this->load->model('Jml_pelayanan','jm_pl');
	}
	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');

			$mtable = $this->jm_pl->table;
			$jtable = $this->ms_pl->table;
			$this->db->where('deleted_at IS NULL');
			echo $this->datatables
				->select("$mtable.id, $mtable.tanggal, $mtable.jumlah, $jtable.kode_layanan, $jtable.pelayanan_id, $jtable.pelayanan")
				->join($jtable,"$jtable.pelayanan_id=$mtable.pelayanan",'left')
				->editColumn('tanggal',function($data){return tanggal_indo($data);})
				->editColumn('pelayanan',function($data,$row){return "($row[kode_layanan]) $data";})
				->editColumn('jumlah',function($data){return numb($data);})
				->addColumn('act',function($row){
					$params = implode('/', [$row['id'],url_title($row['kode_layanan'].'-'.$row['pelayanan'])]);
					$btn = '<button type="button" data-ref="'.$row['id'].'" data-tgl="'.$row['tanggal'].'" data-pl="'.$row['pelayanan_id'].'" data-jml="'.$row['jumlah'].'" class="btn btn-warning btn-sm btn-edit" title="Ubah Jumlah Pelayanan"><i class="fa fa-edit"></i></button> ';
					$btn.= '<a href="'.site_url('jumlah_pelayanan/delete/'.$params).'" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus Data ?\')" title="Hapus Data"><i class="fa fa-trash"></i></a> ';
					return $btn;
				})
				->removeColumns(['id','kode_layanan','pelayanan_id'])
				->table($mtable)->draw();
		}else{
			$pl = $this->ms_pl->get();
			$this->view('table_jumlah_pelayanan',compact('pl'));
		}
	}
	function simpan(){
		$ref = $this->input->post('ref',TRUE);
		$tgl = $this->input->post('tgl',TRUE);
		$pl = $this->input->post('pelayanan',TRUE);
		$jml = $this->input->post('jml',TRUE);

		$plx = $this->ms_pl->findOrFail($pl);
		$cek = $this->jm_pl->count_rows(['id !='=>$ref,'tanggal'=>$tgl,'pelayanan'=>$pl]);
		if($cek>0){
			response_json(['status'=>204,'message'=>'Pelayanan `<b>'.$plx->pelayanan.'</b>` pada Tanggal <b>'.tanggal_indo($tgl).'</b> telah tersedia.']);
			die();
			return;
		}
		if(!empty($plx)){
			$status = 500; $message = "Data Gagal disimpan";
			$post = [
				'pelayanan' => $pl,
				'tanggal' => $tgl,
				'jumlah' => $jml,
			];
			if(empty($ref)){
				$post['id'] = uuid();
				$post['created_at'] = date('Y-m-d H:i:s');
				$post['updated_at'] = date('Y-m-d H:i:s');
				if($this->jm_pl->insert($post)){
					$status=200; $message = "Data berhasil disimpan";
				}
			}else{
				if($this->jm_pl->update($ref,$post)){
					$status=200; $message = "Data berhasil diperbarui";
				}

			}

			if($this->input->is_ajax_request()){
				response_json(compact('status','message'));
			}else{
				setFlash(($status==200)?'success':'error',$message);
				redirect('/jumlah_pelayanan','refresh');
			}
		}
	}
	function delete($ref,$n){
		$d = $this->jm_pl->findOrFail($ref);
		if($this->jm_pl->update($ref,['deleted_at'=>date('Y-m-d H:i:s')])){
			setFlash('success','Data berhasil dihapus.');
		}else{
			setFlash('error','Data gagal dihapus.');
		}
		redirect('/jumlah_pelayanan','refresh');
	}
}
