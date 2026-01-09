<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelayanan extends BASE_Controller {
	var $options_type;
	function __construct(){
		parent::__construct();
		allow_access(['admin','su']);
		$this->load->model('Master_pelayanan','ms_pl');
		$this->load->model('Master_pelayanan_field','pl_field');
		$this->load->model('DataIdentitas','identitas');
		$this->active_menu = 'master_pelayanan';
		$this->options_type = [
			'text'=>'Input Teks',
			'number'=>'Input Angka',
			'date'=>'Input Tanggal',
			'time'=>'Input Waktu',
			'select'=>'Pilihan',
			// 'file'=>'Upload File',
			'list'=>'List',
			'db_identitas'=>'Data Identitas (Database)',
			'db_wilayah_id'=>'Wilayah Indonesia (Database)',
			'db_wilayah_my'=>'Wilayah Malaysia (Database)',
			'separator' => 'Line Separator',
		];
	}
	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$this->load->library('Datatables', 'datatables');
			if(!can_access('su')){
				$this->db->where('fungsi',getSession('fungsi'));
			}else{
				$this->db->where('fungsi',$this->input->post('slcfungsi',getSession('fungsi')));
			}
			header('Content-Type: application/json');
			echo $this->datatables
						->editColumn('biaya',function($data){
							return numb($data);
						})
						->editColumn('template_file',function($data,$row){
							return (empty($data)?'<i>-- no file --</i>':'<a href="'.base_url($data).'"><i class="fa fa-file"></i> File</a>');
						})
						->addColumn('act',function($row){
							$params = implode('/', [$row['pelayanan_id'],url_title($row['pelayanan'])]);
							$btn = '<a href="'.site_url('master/pelayanan/lihat/'.$params).'" class="btn btn-warning btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';
							$btn .= '<a href="'.site_url('master/pelayanan/copy-data/'.$params).'" class="btn bg-purple btn-sm" title="Duplicate Data"><i class="fa fa-copy"></i></a> ';
							$btn.= '<a href="'.site_url('master/pelayanan/hapus/'.$params).'" class="btn btn-danger btn-sm" title="Hapus Data" onclick="return confirm(\'Hapus Data ?\')"><i class="fa fa-trash"></i></a>';
							return $btn;
						})
						->removeColumns(['pelayanan_id'])
						->table($this->ms_pl->table)->draw();
		}else{
			$fungsi = $this->db->get('fungsi')->result();
			$this->view('master/pelayanan/table',compact('fungsi'));
		}
	}
	function lihat($id,$nama){
		$data = $this->ms_pl->findOrFail($id);
		$this->db->order_by('urut','ASC');
		$items=$this->pl_field->get(['pelayanan_id'=>$data->pelayanan_id]);
		$options_type=$this->options_type; 
		$fungsi = $this->db->get('fungsi')->result();
		$this->view('master/pelayanan/form', compact('data','items','options_type','fungsi'));
	}
	function copy_data($id,$nama){
		$data = $this->ms_pl->findOrFail($id);
		$items=$this->pl_field->get(['pelayanan_id'=>$data->pelayanan_id]);
		$data_post = (array) $data;
		$data_post['pelayanan_id'] = 'PL'.timeCode();
		$data_post['kode_layanan'].= '-copy';
		$data_post['pelayanan'].= '-copy';
		foreach($items as $dt){
			$temp = (array) $dt;
			unset($temp['id']);
			$temp['pelayanan_id'] = $data_post['pelayanan_id'];
			$item_post[] = (array) $temp;
		}
		if($this->ms_pl->insert($data_post)){
			$this->pl_field->insertBatch($item_post);
			setFlash('success','Duplicate Data Berhasil');
		}else{
			setFlash('error','Duplicate Data Gagal');
		}
		redirect(back_link('master/pelayanan'));
	}
	function hapus($id,$nama){
		$data = $this->ms_pl->findOrFail($id);
		if($this->ms_pl->delete($id)){
			$this->pl_field->delete(['pelayanan_id'=>$id]);
			setFlash('success','Data Pelayanan `'.$data->pelayanan.'` berhasil dihapus.');
		}else{
			setFlash('error','Data Pelayanan `'.$data->pelayanan.'` gagal dihapus.');
		}
		redirect('master/pelayanan');
	}
	function simpan_data(){
		// print_r([$_POST,$_FILES]); exit();
		// print_r($_POST);exit();
		// print_r($this->input->post('sort_field')); exit();
		$target = 'master/pelayanan';
		$ref = @$_SERVER['HTTP_REFERER'];
		$target = (empty($ref)?$target:$ref);
		$id = $this->input->post('ref');
		$xid = empty($id)?'PL'.timeCode():$id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules([
			[
				'field' => 'kode_layanan',
                'label' => 'Kode Pelayanan',
                'rules' => 'trim|required|min_length[1]'
			],[
				'field' => 'nama',
                'label' => 'Nama Pelayanan',
                'rules' => 'trim|required|min_length[3]'
			],[
				'field' => 'biaya',
                'label' => 'Biaya pelayanan',
                'rules' => 'required|numeric'
			]
		]);
		if ($this->form_validation->run()){
			$kode_layanan = $this->input->post('kode_layanan',TRUE);
			// $cek = $this->ms_pl->first(['pelayanan_id !='=>$id,'kode_layanan'=>$kode_layanan]);
			// if(!empty($cek)){
			// 	setFlash('error','Kode Pelayanan `'.$kode_layanan.'` already exists.');
			// 	redirect($target);
			// }
			$data = [
				'kode_layanan' => $kode_layanan,
				'pelayanan' => $this->input->post('nama',TRUE),
				'biaya' => $this->input->post('biaya',TRUE),
				'show_jml' => ($this->input->post('show_jml',TRUE)=='on'?1:0),
			];

			if(can_access('su')){
				$data['fungsi'] = $this->input->post('slc_fungsi');
			}else{
				$data['fungsi'] = getSession('fungsi');
			}

			$this->load->library('upload');
			if (!empty($_FILES['template']['name'])){
				$path = $_FILES['template']['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
			 	$nm = 'template_'.$xid.'.'.$ext;
			 	$path = 'assets/template';
			 	if(upload('template',$nm,$path,'doc|docx')){
			 		$data['template_file'] = $path.'/'.$nm;
			 		$up_data = $this->upload->data();
			 		// chmod($up_data['full_path'],0777);
			 	}else{
			 		setFlash('error',$this->upload->display_errors());
			 		redirect($target);
			 	}
			}

			if (!empty($_FILES['file_kwi']['name'])){
				$path = $_FILES['file_kwi']['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
			 	$nm = 'template_kwitansi_'.$xid.'.'.$ext;
			 	$path = 'assets/template';
			 	if(upload('file_kwi',$nm,$path,'doc|docx')){
			 		$data['template_kwitansi'] = $path.'/'.$nm;
			 		$up_data = $this->upload->data();
			 		// chmod($up_data['full_path'],0777);
			 	}else{
			 		setFlash('error',$this->upload->display_errors());
			 		redirect($target);
			 	}
			}

			if (!empty($_FILES['file_bukti']['name'])){
				$path = $_FILES['file_bukti']['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
			 	$nm = 'template_bukti_'.$xid.'.'.$ext;
			 	$path = 'assets/template';
			 	if(upload('file_bukti',$nm,$path,'doc|docx')){
			 		$data['template_bukti'] = $path.'/'.$nm;
			 		$up_data = $this->upload->data();
			 		// chmod($up_data['full_path'],0777);
			 	}else{
			 		setFlash('error',$this->upload->display_errors());
			 		redirect($target);
			 	}
			}
			if(empty($id)){
				$data['pelayanan_id'] = $xid;
				if($this->ms_pl->insert($data)){
					$target = 'master/pelayanan/lihat/'.$data['pelayanan_id'].'/'.url_title($data['pelayanan']);
					setFlash('success','Master Pelayanan berhasil disimpan.');
				}else{
					setFlash('error','Master Pelayanan gagal disimpan.');
				}
			}else{
				if($this->ms_pl->update($id,$data)){
					setFlash('success','Master Pelayanan berhasil disimpan.');
				}else{
					setFlash('error','Master Pelayanan gagal disimpan..');
				}
			}
			$sort_field = $this->input->post('sort_field');
			if(!empty($sort_field)){
				foreach ($this->input->post('sort_field') as $key => $v) {
					$this->pl_field->update($v,['urut'=>($key+1)]);	
				}
			}
		}else{
			setFlash('error',$this->form_validation->error_string());
		}
		redirect($target);
	}
	function add_item($pelayanan_id){
		$x = $this->ms_pl->findOrFail($pelayanan_id);
		$options_type = $this->options_type;
		$pelayanan_id = $x->pelayanan_id;
		$this->load->view('master/pelayanan/form_item',compact('options_type','pelayanan_id'));
	}
	function edit_item($id,$name,$label){
		$data = $this->pl_field->findOrFail(['id'=>$id,'field_name'=>$name]);
		$pelayanan_id = $data->pelayanan_id;
		$options_type = $this->options_type;
		$this->load->view('master/pelayanan/form_item',compact('options_type','data','pelayanan_id'));
	}
	function hapus_item($id,$name,$label){
		if($this->pl_field->delete(['id'=>$id,'field_name'=>$name])){
			setFlash('success','Data berhasil dihapus.');
		}else{
			setFlash('error','Data gagal dihapus.');
		}
		$ref = @$_SERVER['HTTP_REFERER'];
		$target = (empty($ref)?'master/pelayanan':$ref);
		redirect($target);
	}
	function simpan_item(){
		$name = $this->input->post('field_name',TRUE);
		$ref = @$_SERVER['HTTP_REFERER'];
		$target = (empty($ref)?'master/pelayanan':$ref);
		$this->load->library('form_validation');
		$ftype = $this->input->post('field_type',TRUE);
		if($ftype=='separator'){
			$rules = [
				[
					'field' => 'pelayanan',
					'label' => 'Reference',
					'rules' => 'trim|required|min_length[20]'
				],[
					'field' => 'field_type',
					'label' => 'Field Type',
					'rules' => 'required'
				]
			];
		}else{
			$rules = [
				[
					'field' => 'pelayanan',
					'label' => 'Reference',
					'rules' => 'trim|required|min_length[20]'
				],[
					'field' => 'field_name',
					'label' => 'Field ID',
					'rules' => 'trim|required|min_length[2]'
				],[
					'field' => 'label',
					'label' => 'Label',
					'rules' => 'required'
				],[
					'field' => 'field_type',
					'label' => 'Field Type',
					'rules' => 'required'
				]
			];
		}
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()){
			$pelayanan_id = $this->input->post('pelayanan',TRUE);
			$id = $this->input->post('ref',TRUE);
			$required = (strtolower($this->input->post('required'))=='on')?1:0;
			if($ftype=='separator'){
				$name = 'separator_'.date('HdiYsm');
				$data = [
					'pelayanan_id' => $pelayanan_id,
					'field_name' => $name,
					'label' => ucwords($name),
					'notes' => '',
				];
				$required = 0;
			}else{
				$data = [
					'pelayanan_id' => $pelayanan_id,
					'field_name' => url_title($this->input->post('field_name'),'_'),
					'label' => $this->input->post('label'),
					'notes' => $this->input->post('notes'),
				];
			}
			if(in_array($data['field_name'],array_keys(phpword_auto_items()))){
				setFlash('error','Field `'.$data['field_name'].'` tidak dapat ditambahkan. Mohon gunakan nama lain.');
				redirect(back_link($target));
				exit();
			}
			// if($name!='nama'){
				$ftype = $this->input->post('field_type');
				$data = array_merge($data,[
					'field_type' => $ftype,
					'required' => $required
				]);
				if(in_array($ftype, ['select','date','db_identitas','db_wilayah_id','db_wilayah_my'])){
					$data['data'] = implode('||',$this->input->post('datas',TRUE));
				}
			// }
			$cek = $this->pl_field->first(['pelayanan_id'=>$pelayanan_id,'field_name'=>$data['field_name'],'id !='=>$id]);

			if(!empty($cek)){
				setFlash('error','Field ID `'.$data['field_name'].'` Sudah tersedia.');
			}else{
				if(empty($id)){
					$this->db->order_by('urut','DESC');
					$data['urut'] = intval(@$this->pl_field->first(['pelayanan_id'=>$pelayanan_id])->urut)+1;
					if($this->pl_field->insert($data)){
						setFlash('success','Field Pelayanan berhasil disimpan.');
					}else{
						setFlash('error','Field Pelayanan gagal disimpan.');
					}
				}else{
					if($this->pl_field->update($id,$data)){
						setFlash('success','Field Pelayanan berhasil disimpan.');
					}else{
						setFlash('error','Field Pelayanan gagal disimpan..');
					}
				}
			}
		}else{
			setFlash('error',$this->form_validation->error_string());
		}

		redirect($target);
	}
	function test_form($ref){
		$tipe_form = "view-form";
		$item_p=[];
		foreach($this->identitas->get_fields() as $field){
			$item_p[$field->name] = '<i>['.ucwords(str_replace('_',' ',$field->name)).' Pelapor]</i>';
		}
		$pelapor = (object) $item_p;
		$pl = $this->ms_pl->findOrFail($ref);
		$this->db->order_by('urut','ASC');
		$fields = $this->pl_field->get(['pelayanan_id'=>$pl->pelayanan_id]);
		$this->view('pelayanan/form',compact('pl','fields','pelapor','tipe_form'));
	}
	function hapus_template_kwitansi($ref){
		$data = $this->ms_pl->findOrFail($ref);
		if($this->ms_pl->update($ref,['template_kwitansi'=>''])){
			@unlink(FCPATH.$data->template_kwitansi);
			setFlash('success','Template berhasil dihapus.');
		}else{
			setFlash('error','Gagal menghapus template');
		}
		$ref = @$_SERVER['HTTP_REFERER'];
		$target = (empty($ref)?'master/pelayanan':$ref);
		redirect($target);
	}
	function hapus_template_bukti($ref){
		$data = $this->ms_pl->findOrFail($ref);
		if($this->ms_pl->update($ref,['template_bukti'=>''])){
			@unlink(FCPATH.$data->template_bukti);
			setFlash('success','Template berhasil dihapus.');
		}else{
			setFlash('error','Gagal menghapus template');
		}
		$ref = @$_SERVER['HTTP_REFERER'];
		$target = (empty($ref)?'master/pelayanan':$ref);
		redirect($target);
	}
}
