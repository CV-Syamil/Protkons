<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Informasi extends CI_Controller {

	function pelayanan($ref){
		$this->load->model('Tr_pelayanan','tr_pl');
		$this->load->model('Master_pelayanan','ms_pl');
		$this->load->model('DataIdentitas','identitas');
		$this->load->model('ScanLog','scan_log');
		
		$this->load->library('user_agent');

		$data = $this->tr_pl->findOrFail(['id'=>$ref,'status >='=>2]);
		
		if ($this->agent->is_browser()){
			$agent = $this->agent->browser().' '.$this->agent->version();
		}elseif ($this->agent->is_robot()){
			$agent = $this->agent->robot();
		}elseif ($this->agent->is_mobile()){
			$agent = $this->agent->mobile();
		}else{ $agent = 'Unidentified User Agent'; }
		$platform = $this->agent->platform();
		$ip = $this->input->ip_address();
		
		$this->scan_log->add_log($data->id,$agent,$platform,$ip);

		$pl = $this->ms_pl->findOrFail($data->pelayanan_id);
		
		$pelapor = $this->identitas->first(['id'=>$data->pelapor]);

		$hs = @$this->db->where('user_id',$data->hs)->select('nama')->get('users')->row()->nama;

		$tte = [];
		if(!empty($data->file_esign)){
			$this->load->library('TTE');
			$tte = (new TTE)->verifyPDF($data->file_esign,30);
		}
		// print_r($tte); exit();
		$this->load->view('informasi_pelayanan',compact('pl','data','pelapor','tte','hs'));
	}
}
