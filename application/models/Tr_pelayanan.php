<?php
class Tr_pelayanan extends BASE_Model {
	public $table = "tr_pelayanan";
	public $id = "id";
	function getNoDokumen($ref){
		$this->db->select('max(no_dokumen+0) as x');
		$x = (int) $this->db->where(['YEAR(created_at)'=>date('Y'),'pelayanan_id'=>$ref])
							->get($this->table)->row()->x;
		return ($x+1);
	}
	function insert_no_dokumen($tr_pelayanan,$kode,$jumlah){
		$q = "SELECT MAX(no_dokumen) AS no FROM tr_pelayanan_no WHERE kode_pelayanan='$kode' AND tahun=YEAR(NOW())";
		$c_no =  intval(@$this->db->query($q)->row()->no);
		$values=[];
		foreach (range(1,$jumlah) as $v) {
			$c_no++;
			$th = date('Y');
			$values[]="('$tr_pelayanan',$c_no,$th,'$kode')";
		}
		$this->db->query("INSERT INTO tr_pelayanan_no VALUES ".implode(',',$values));
		$this->update($tr_pelayanan,['no_dokumen'=>$c_no]);
		return $c_no;
	}
}