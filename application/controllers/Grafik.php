<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grafik extends BASE_Controller {

	function ch_pl(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
            $this->load->model('Tr_pelayanan','tr_pl');
            $pl = $this->input->post('pl',TRUE);
            $tgl = $this->input->post('tgl',TRUE);
            $data=['labels'=>[],'datas'=>[]];
            $this->filter_accs();
            if(!empty($pl)){
                $this->db->where('pelayanan_id',$pl);
            }
            if(!empty($tgl)){
                $tgl = explode(' - ',$tgl);
                $tgl_s = date('Y-m-d',strtotime($tgl[0]));
                $tgl_e = date('Y-m-d',strtotime($tgl[1]));
                $this->db->where('DATE(created_at) BETWEEN "'.$tgl_s.'" AND "'.$tgl_e.'"');
            }
            $this->db
                    ->order_by('DATE(created_at)','ASC')
                    ->group_by('DATE(created_at)');
            $dt_pl = $this->tr_pl
                        ->select('DATE(created_at) as tgl, COUNT(id) as jml')
                        ->get(['status'=>5]);
            foreach ($dt_pl as $v) {
                if(!empty($v->jml)){
                    $data['labels'][] = date('d M y', strtotime($v->tgl));
                    $data['datas'][] = intval($v->jml);
                }
            }
            header('Content-Type: application/json');
            echo json_encode(['status'=>200,'message'=>'OK','data'=>$data,]);
        }else show_404();
	}

    private function filter_accs(){
        switch (getSession('akses')) {
            case 'loket':
                    $this->db->where('petugas_loket',getSession('ref'));
                break;
            case 'verifikasi':
                    $this->db->where('petugas_verifikasi',getSession('ref'));
                break;
            case 'hs':
                    $this->db->where('hs',getSession('ref'));
                break;
            case 'kasir':
                    $this->db->where('kasir',getSession('ref'));
                break;
        }
    }

	function ch_keu(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
            $this->load->model('Tr_pelayanan','tr_pl');
            $pl = $this->input->post('pl',TRUE);
            $tgl = $this->input->post('tgl',TRUE);
            $this->filter_accs();
            $data=['labels'=>[],'datas'=>[]];
            $where=[];
            if(!empty($pl)){
                $where[]="pelayanan_id='$pl'";
            }
            if(!empty($tgl)){
                $tgl = explode(' - ',$tgl);
                $tgl_s = date('Y-m-d',strtotime($tgl[0]));
                $tgl_e = date('Y-m-d',strtotime($tgl[1]));
                $where[] = 'tgl BETWEEN "'.$tgl_s.'" AND "'.$tgl_e.'"';
            }
            $where[] = 'status = 5';
            $where = implode(' AND ', $where);
            $sql = "SELECT tgl, SUM( total ) AS sum_total FROM (SELECT mtr.id, mtr.pelayanan_id, DATE( mtr.created_at ) AS tgl, mtr.status, ( mtr.biaya * mtr.jml_berkas ) AS total FROM tr_pelayanan mtr ) AS tbl WHERE $where GROUP BY tgl ORDER BY tgl ASC";
            // print_r($sql);
            $dt_pl = $this->db->query($sql)->result();
            // print_r($dt_pl); exit();
            foreach ($dt_pl as $v) {
                if(!empty($v->sum_total)){
                    $data['labels'][] = date('d M y', strtotime($v->tgl));
                    $data['datas'][] = intval($v->sum_total);
                }
            }
            header('Content-Type: application/json');
            echo json_encode(['status'=>200,'message'=>'OK','data'=>$data]);
        }else show_404();
	}
}
