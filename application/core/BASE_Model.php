<?php
class BASE_Model extends CI_Model {
	var $as='';
	function get($cond=NULL){return $this->condition($cond)->get($this->table.$this->as)->result();}
	function getArray($cond=NULL){return $this->condition($cond)->get($this->table.$this->as)->result_array();}
	function first($cond=NULL){return $this->condition($cond)->get($this->table.$this->as)->row();}
	function findOrFail($cond){$data = $this->first($cond); if(empty($data)){show_404(); return NULL;}else{return $data;}}
	function select($select){$this->db->select($select);return $this;}
	function where($where){$this->db->where($where);return $this;}
	function insert($data){return $this->db->insert($this->table,$data);}
	function insertBatch($data){return $this->db->insert_batch($this->table,$data);}
	function update($cond=NULL,$data=NULL){return $this->condition($cond)->update($this->table,$data);}
	function delete($cond=NULL){return $this->condition($cond)->delete($this->table);}
	function count_rows($cond=NULL){return $this->condition($cond)->get($this->table)->num_rows();}
	function get_fields(){return $this->db->field_data($this->table);}
	function alias($as){ $this->as = ' '.$as; return $this;}
	private function condition($cond){
		if(!empty($cond)){
			if(is_array($cond)){return $this->db->where($cond);}
			else{return $this->db->where($this->id,$cond);}
		}
		return $this->db;
	}
}
