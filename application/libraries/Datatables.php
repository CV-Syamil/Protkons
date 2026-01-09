<?php
class Datatables{
	private $db;
	private $input;
	private $table;
	private $alias = [];
	private $whereFields = [];
	private $whereData;
	private $joins = [];
	private $modif = [];
	private $unset_columns = [];
	private $total = 0;
	private $filtered = 0;

	public function __construct(){
		$ci = &get_instance();
		$this->db = $ci->db;
		$this->input = $ci->input;
	}

	public function table($table){
		$this->table = $table;
		return $this;
	}

	public function select($fields){
		$this->db->select($fields);
		$this->set_alias($fields);
		return $this;
	}

	public function where($data){
		$this->db->where($data);
		foreach($data as $field => $value){
			$this->whereFields[] = $field;
		}
		$this->whereData = $data;
		return $this;
	}

	public function join($table, $cond, $type = ''){
		$this->joins[] = ['table' => $table, 'cond' => $cond, 'type' => $type];
		$this->db->join($table, $cond, $type);
		return $this;
	}

	public function draw($log=FALSE){
		$last_query=[];
		$keyword = $this->input->post('search')['value'];
		$result = $this->get_result($keyword);

		if($log){
			$last_query[] = $this->db->last_query();
			$last_query[]=$this->total;
			$last_query[]=$this->filtered;
		} 
		
		// $paging = $this->get_paging($keyword);
		
		$result = $this->modifyColumn($result);
		$resp = [
			'draw' => $this->input->post('draw'),
			// 'recordsTotal' => $paging['total'],
			'recordsTotal' => $this->total,
			// 'recordsFiltered' => $paging['filtered'],
			'recordsFiltered' => $this->filtered,
			'data' => $result
		];
		if(!empty($log)){$resp['last_query'] = $last_query; }
		return json_encode($resp);
	}

	public function removeColumns($columns){
		if(is_array($columns)){
			$this->unset_columns=$columns;
		}
		return $this;
	}

	public function addColumn($name,$func){
		$type = 'add';
		$this->modif[]=compact('name','func','type');
		return $this;
	}

	public function editColumn($name,$func){
		$type = 'edit';
		$this->modif[]=compact('name','func','type');
		return $this;
	}

	private function modifyColumn($results){
		if(!empty($this->modif)||!empty($this->unset_columns)){
			foreach ($results as $key => $value) {
				if(!empty($this->modif)){
					foreach ($this->modif as $modif) {
						$name = $modif['name'];
						if($modif['type']=='add'){
							$results[$key][$name] = $modif['func']($value);
						}else{
							if(array_key_exists($name,$results[$key])){
								$results[$key][$name] = $modif['func']($value[$name],$value);
							}
						}
					}
				}
				if(!empty($this->unset_columns)){
					foreach ($this->unset_columns as $column) {
						unset($results[$key][$column]);
					}
				}
			}
		}
		return $results;
	}

	private function set_alias($data){
		foreach(explode(',', $data) as $val){
			if(stripos($val, 'as')){
				$alias = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
				$field = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $val));
				$this->alias[$alias] = $field;
			}
		}
	}

	private function do_join(){
		foreach($this->joins as $join){
			$this->db->join($join['table'], $join['cond'], $join['type']);
		}
	}

	private function get_filtering($keyword){
		$fields = $this->input->post('columns');
		$this->db->group_start();
		for($i = 0; $i < count($fields); $i++){
			$where = false;
			if(strtolower((string)$fields[$i]['searchable'])=='true') {
				$field = $fields[$i]['data'];
				foreach($this->whereFields as $data){
					$where = ($field == $data) ? true : false;
				}
				if($where) continue;
				if(array_key_exists($field, $this->alias)){
					$field = $this->alias[$field];
					($i < 1) ? $this->db->like($field, $keyword) : $this->db->or_like($field, $keyword);
				}else{
					($i < 1) ? $this->db->like($field, $keyword) : $this->db->or_like($field, $keyword);
				}
			}
		}
		$this->db->group_end();
	}

	private function get_ordering(){
		$orderField = $this->input->post('order')[0]['column'];
		$orderAD = $this->input->post('order')[0]['dir'];
		$orderColumn = $this->input->post('columns')[$orderField]['data'];
		$this->db->order_by($orderColumn, $orderAD);
	}

	private function get_result($keyword){
		$this->total = $this->db->count_all_results($this->table,FALSE);
		if(!empty($keyword)) {
			$this->get_filtering($keyword);
			$this->filtered = $this->db->count_all_results('',FALSE);
		}else{
			$this->filtered = $this->total;
		}
		$this->get_ordering();
		$this->get_limiting();
		return $this->db->get()->result_array();
	}

	private function get_limiting(){
		$limit = $this->input->post('length', true);
		$start = $this->input->post('start', true);
		$this->db->limit($limit, $start);
	}

	private function get_paging($keyword){
		if(count($this->joins) > 0) $this->do_join();
		if(!empty($this->whereData)) $this->where($this->whereData);
		$total = $this->db->count_all_results($this->table);
		$keyword = $this->input->post('search')['value'];
		if(!empty($keyword)){
			$this->get_filtering($keyword);
			if(count($this->joins) > 0) $this->do_join();
			if(!empty($this->whereData)) $this->where($this->whereData);
			$filtered = $this->db->get($this->table)->num_rows();
		}else{
			$filtered = $total;
		}
		
		return [
			'total' => $total,
			'filtered' => $filtered
		];
	}
}