<?php
class ScanLog extends BASE_Model {
	public $table = "scan_log";
	public $id = "id";

	function add_log($tr_pl,$agent,$platform,$ip){
		$log = $this->first([
			'pelayanan_id' => $tr_pl,
			'platform' => $platform,
			'ip_addr' => $ip,
			'DATE(tanggal)' => date('Y-m-d')
		]);
		$post_data = [
			'pelayanan_id' => $tr_pl,
			'agent' => $agent,
			'platform' => $platform,
			'ip_addr' => $ip,
			'tanggal' => date('Y-m-d H:i:s'),
			'viewer' => 1,
		];
		if(empty($log)){
			return $this->insert($post_data);
		}else{
			$post_data['viewer'] = intval($log->viewer)+1;
			return $this->update($log->id,$post_data);
		}
	}
}