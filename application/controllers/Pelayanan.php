<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Settings;
use \PhpOffice\PhpWord\Element\Link;
class Pelayanan extends BASE_Controller {
	function __construct(){
		parent::__construct();
		$this->active_menu = 'pelayanan';
		$this->load->model('Tr_pelayanan','tr_pl');
		$this->load->model('Tr_pelayanan_item','tr_pl_item');
		$this->load->model('Master_pelayanan','ms_pl');
		$this->load->model('Master_pelayanan_field','pl_field');
		$this->load->model('DataIdentitas','identitas');
		$this->load->model('Users_model','user_m');
	}
	function index(){
		if($this->input->is_ajax_request()&&$this->input->method()=='post'){
			$user = $this->user_m->findOrFail(getSession('ref'));
			$akses_pl = json_decode($user->akses_pelayanan,TRUE);
			$this->load->library('Datatables', 'datatables');
			header('Content-Type: application/json');
			$mtable = $this->tr_pl->table;
			$jtable = $this->ms_pl->table;
			if(!in_array('all',$akses_pl)){$this->db->where_in($mtable.'.pelayanan_id',$akses_pl); }
			switch ($user->akses) {
				case 'loket':
						// $this->db->where('petugas_loket');
					break;
				case 'verifikasi':
						// $this->db->group_start()->where('petugas_verifikasi',getSession('ref'))->or_where('status',1)->group_end();
						$this->db->group_start()->or_where(['status !='=>0,'status !='=>91])->group_end();
						break;
				case 'hs':
							$this->db->group_start()->or_where(['status !='=>0,'status !='=>91])->group_end();
						// $this->db->where('hs',getSession('ref'));
					break;
				case 'kasir':
						// $this->db->group_start()->where('kasir',getSession('ref'))->or_where('status',3)->group_end()->where($mtable.'.biaya >',0);
						$this->db->group_start()->where('petugas_loket',getSession('ref'))->or_where('status >=',3)->group_end()->where($mtable.'.biaya >',0);
					break;
			}
			$sts = $this->input->post('sts',TRUE);
			$tgl = $this->input->post('tgl',TRUE);
			$tgl2 = $this->input->post('tgl2',TRUE);
			$tgl_tipe = $this->input->post('tgl_type',TRUE);
			$tgl_tipe = ($tgl_tipe=='updated_at')?'updated_at':'created_at';

			$pl = $this->input->post('pl',TRUE);
			$kodepl = $this->input->post('kodepl',TRUE);
			$pelapor = $this->input->post('pelapor',TRUE);
			$plitem = $this->input->post('plitem',TRUE);
			$plival = $this->input->post('plval',TRUE);
			
			if($sts!=""&&$sts!='recycle'){ $this->db->where($mtable.'.status',intval($sts)); }
			if(!empty($tgl)){ 
				$tgl2 = (empty($tgl2)?$tgl:$tgl2);
				$this->db->where('DATE('.$mtable.'.'.$tgl_tipe.') BETWEEN "'.$tgl.'" AND "'.$tgl2.'"');
			}
			if(!empty($kodepl)){ $this->db->where($mtable.'.id',$kodepl); }
			if(!empty($pl)){ $this->db->where($mtable.'.pelayanan_id',$pl); }
			if(!empty($pelapor)){ $this->db->where($mtable.'.pelapor',$pelapor); }

			if(!empty($plitem)&&!empty($plival)){
				$q = "SELECT tpix.tr_pelayanan_id FROM tr_pelayanan_item tpix WHERE tpix.field_name='$plitem' AND tpix.field_value LIKE '%$plival%'";
				$this->db->where($mtable.".id IN ($q)");
			}

			$this->db->where($mtable.'.deleted_at IS '.(($sts=='recycle')?'NOT NULL':'NULL'));
			if(can_access('su')){
				$fsg = $this->input->post('fungsi');
				if(!empty($fsg)){
					$this->db->where($mtable.'.fungsi',$fsg);
				}
			}else{
				$this->db->where($mtable.'.fungsi',getSession('fungsi'));
			}
			echo $this->datatables
				->select("$mtable.id as kode, $mtable.hs, getNoDokumen($mtable.id) as no_dokumen, $mtable.petugas_loket, $jtable.pelayanan as layanan, $mtable.biaya, $mtable.status,$mtable.jml_berkas, $mtable.keterangan, $mtable.created_at, $mtable.updated_at, $mtable.deleted_at, $mtable.pelapor, mi.nama as nm_pelapor, $jtable.template_file, $jtable.template_kwitansi, $jtable.template_bukti, $mtable.file_berkas, $mtable.file_berkas_kasir, us.nama as nm_hs, $mtable.file_esign")
				->join($jtable,"$jtable.pelayanan_id=$mtable.pelayanan_id",'inner')
				->join('master_identitas mi',"mi.id=$mtable.pelapor",'inner')
				->join('users us',"us.user_id=$mtable.hs",'left')
				->editColumn('biaya',function($data){return 'Rp.'.numb($data).',-';})
				->editColumn('created_at',function($data){return tanggal_indo($data);})
				->editColumn('updated_at',function($data){return tanggal_indo($data);})
				->editColumn('status',function($data,$row){
					if(!empty($row['deleted_at'])){
						return '<center><span class="badge badge-secondary">DIHAPUS</span></center>';
					}
					return status_layanan($data,TRUE).(empty($row['keterangan'])?'':"<p class=\"small text-red\"><br>* $row[keterangan]</p>");
				})
				->editColumn('nm_hs',function($data){
					return empty($data)?'<center><i class="fa fa-minus text-danger"></i></center>':$data;
				})
				->addColumn('act',function($row){
					$params = implode('/', [$row['kode'],url_title($row['nm_pelapor'])]);
					if(!empty($row['deleted_at'])){
						return '<a href="'.site_url('pelayanan/restore/'.$params).'" class="btn-restore btn btn-success btn-sm" title="Kembalikan"><i class="fa fa-recycle"></i></a>
								<a href="'.site_url('pelayanan/hapus-permanen/'.$params).'" class="del-permanen btn btn-danger btn-sm" title="Hapus Permanen"><i class="fa fa-times"></i></a>';
					}

					$btn = '<a href="'.site_url('pelayanan/detail/'.$params).'" class="btn btn-info btn-sm" title="Detail Data"><i class="fa fa-eye"></i></a> ';
					if(can_access(['admin'])){
						$btn.= '<a href="'.site_url('pelayanan/edit2/'.$params).'" class="btn bg-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a> ';
						$btn.= '<a href="'.site_url('pelayanan/hapus2/'.$params).'" class="btn bg-danger btn-sm" title="Hapus"><i class="fa fa-trash"></i></a> ';
					}
					if(can_access(['loket','kasir'])){
						if($row['status']>0&&$row['status']<90){
							if(empty($row['template_bukti'])){
								$btn.= '<a href="'.site_url('pelayanan/bukti-pengambilan/'.$params).'" target="_blank" class="btn bg-purple btn-sm" title="Bukti Pengambilan"><i class="fa fa-receipt"></i></a> ';
							}else{
								$btn.= '<a href="'.site_url('pelayanan/file-bukti/'.$params).'" class="btn bg-purple btn-sm btn-export-file" title="Bukti Pengambilan"><i class="fa fa-receipt"></i></a> ';
							}
						}
						if(in_array($row['status'],[0,91])){
							$btn.= '<a href="'.site_url('pelayanan/kirim-data/'.$params).'" class="btn btn-primary btn-sm" title="Kirim Data"><i class="fa fa-paper-plane"></i></a> ';
							if(getSession('ref')==$row['petugas_loket']){
								$btn.= '<a href="'.site_url('pelayanan/edit/'.$params).'" class="btn btn-warning btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';
								$btn.= '<a href="'.site_url('pelayanan/hapus/'.$params).'" class="btn btn-danger btn-sm" title="Hapus Data" onclick="return confirm(\'Hapus Data ?\')"><i class="fa fa-trash"></i></a>';
							}
						}
					}
					if(getSession('ref')==$row['hs']&&$row['status']>=3){
						$btn.= '<button data-href="'.site_url('pelayanan/kembalikan-verifikasi/'.$params).'" class="btn btn-warning btn-sm btn_kembalikan" title="Kembalikan ke Verifikasi" ><i class="fa fa-redo"></i></button>';
					}
					if(can_access(['verifikasi'])){
						if(in_array($row['status'],[0,92])&&empty($row['petugas_loket'])){
							$btn.= '<a href="'.site_url('pelayanan/edit/'.$params).'" class="btn btn-warning btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';
							$btn.= '<a href="'.site_url('pelayanan/hapus/'.$params).'" class="btn btn-danger btn-sm" title="Hapus Data" onclick="return confirm(\'Hapus Data ?\')"><i class="fa fa-trash"></i></a>';
						}elseif(in_array($row['status'],[1,92])){
							$btn.= '<a href="'.site_url('pelayanan/edit/'.$params).'" class="btn btn-warning btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';
						}
						
						if($row['status']>1&&empty($row['petugas_loket'])){
							$btn.= '<a href="'.site_url('pelayanan/bukti-pengambilan/'.$params).'" class="btn bg-purple btn-sm" title="Bukti Pengambilan"><i class="fa fa-receipt"></i></a> ';
						}
					}
					if(in_array($row['status'],[4,5])&&can_access('kasir')){
						$link='#';
						if(!empty($row['template_kwitansi'])){
							$link = site_url('pelayanan/file-kwitansi/'.$params);
						}else{
							$link = site_url('pelayanan/kwitansi/'.$params);
						}
						$btn.= '<a href="'.$link.'" class="btn bg-indigo btn-sm" title="Tanda Terima"><i class="fa fa-money-check-alt"></i></a> ';
					}
					if(!empty($row['template_file'])&&in_array($row['status'],[3,4,5])&&can_access(['loket','verifikasi','hs'])){
						$btn.= '<a href="'.site_url('pelayanan/file/'.$params).'" target="_BLANK" class="btn btn-primary btn-sm btn_file" data-nodoc="'.intval($row['no_dokumen']).'" title="Lihat File Pelayanan"><i class="fa fa-file-word"></i></a> ';
					}
					if(!empty($row['file_esign'])){
						$btn.= '<a href="'.base_url('assets/'.$row['file_esign']).'" target="_BLANK" class="btn btn-primary btn-sm" title="Lihat File E-SIGN"><i class="fa fa-file-pdf"></i></a> ';
					}
					if(!empty($row['file_berkas'])){
						$btn.= '<a href="'.base_url($row['file_berkas']).'" target="_blank" class="btn bg-success btn-sm" title="Berkas File dari Loket"><i class="fa fa-file-archive"></i></a> ';
					}
					if(!empty($row['file_berkas_kasir'])){
						$btn.= '<a href="'.base_url($row['file_berkas_kasir']).'" target="_blank" class="btn bg-teal btn-sm" title="Berkas File dari Kasir"><i class="fa fa-file-archive"></i></a> ';
					}
					return $btn;
				})
				->removeColumns(['petugas_loket','jml_berkas','hs','template_kwitansi','template_file','template_bukti'])
				->table($this->tr_pl->table)->draw();
		}else{

			$slc_sts=status_layanan();
			$options_sts="";
			$selected_sts=989898;
			switch (getSession('akses')) {
				case 'verifikasi':
						$selected_sts=1;
					break;
				case 'hs':
						$selected_sts=2;
						unset($slc_sts[0]);unset($slc_sts[1]);unset($slc_sts[91]);
					break;
				case 'kasir':
						$selected_sts=3;
						unset($slc_sts[1]);unset($slc_sts[2]);unset($slc_sts[91]);unset($slc_sts[92]);
					break;
			}
			foreach ($slc_sts as $key => $value) {
				$selected = ($key==$selected_sts)?'selected':'';
				$options_sts.="<option value=\"$key\" $selected>$value</option>";
			}
			if(can_access('admin')){
				$options_sts.="<option value=\"recycle\">Recycle Bin</option>";
			}
			$fungsi = can_access('su')?$this->db->get('fungsi')->result():[];
			$this->view('pelayanan/table', compact('options_sts','selected_sts','fungsi'));
		}
	}
	
	function buat_layanan($ref1,$ref2,$name){
		$user = $this->user_m->findOrFail(getSession('ref'));
		$akses_pl = json_decode($user->akses_pelayanan,TRUE);
		if(!in_array('all',$akses_pl)&&!in_array($ref2,$akses_pl)){setFlash('error','Access Danied'); redirect(back_link('pelayanan'));}
		$pl = $this->ms_pl->findOrFail($ref2);
		if($pl->fungsi!=getSession('fungsi')){setFlash('error','Access Danied'); redirect(back_link('pelayanan'));}
		
		$user_hs = $this->user_m->get(['akses'=>'hs']);
		$pelapor = $this->identitas->findOrFail(['id'=>$ref1]);
		$this->db->order_by('urut','ASC');
		$fields = $this->pl_field->get(['pelayanan_id'=>$pl->pelayanan_id]);
		$this->view('pelayanan/form',compact('pl','fields','pelapor','user_hs'));
	}
	function edit($ref,$name){
		$data = $this->tr_pl->findOrFail($ref);
		if(!in_array($data->status,[0,1,92])){e404();return;}
		$data_item = [];
		foreach ($this->tr_pl_item->get(['tr_pelayanan_id'=>$data->id]) as $item) {
			$data_item[$item->field_name] = $item->field_value;
		}
		// print_r($data_item); exit();
		$user_hs = $this->user_m->get(['akses'=>'hs']);
		$pl = $this->ms_pl->findOrFail($data->pelayanan_id);
		$this->db->order_by('urut','ASC');
		$fields = $this->pl_field->get(['pelayanan_id'=>$pl->pelayanan_id]);
		$pelapor = $this->identitas->first(['id'=>$data->pelapor]);
		$this->view('pelayanan/form',compact('pl','fields','data','data_item','pelapor','user_hs'));
	}
	function edit2($ref,$name){
		$data = $this->tr_pl->findOrFail($ref);
		$data_item = [];
		foreach ($this->tr_pl_item->get(['tr_pelayanan_id'=>$data->id]) as $item) {
			$data_item[$item->field_name] = $item->field_value;
		}
		// print_r($data); exit();
		$user_hs = $this->user_m->get(['akses'=>'hs']);
		$pl = $this->ms_pl->findOrFail($data->pelayanan_id);
		$this->db->order_by('urut','ASC');
		$fields = $this->pl_field->get(['pelayanan_id'=>$pl->pelayanan_id]);
		$pelapor = $this->identitas->first(['id'=>$data->pelapor]);
		$this->view('pelayanan/form_edit_admin',compact('pl','fields','data','data_item','pelapor','user_hs'));
	}
	function kirim_data($ref,$name){
		$data = $this->tr_pl->findOrFail($ref);
		if($this->tr_pl->update($data->id,['status'=>1, 'keterangan'=>''])){
			setFlash('success','Data Pelayanan Berhasil dikirim.');
		}else{
			setFlash('success','Data Pelayanan Gagal dikirim.');
		}
		redirect('pelayanan');
	}
	function bukti_pengambilan($ref,$name){
		$this->db->select($this->tr_pl->table.'.*, us1.nama as nama_petugas_loket');
		$this->db->join('users us1','us1.user_id='.$this->tr_pl->table.'.petugas_loket','left');
		$data = $this->tr_pl->findOrFail($ref);
		$pl = $this->ms_pl->findOrFail($data->pelayanan_id);
		$pelapor = $this->identitas->findOrFail(['id'=>$data->pelapor]);
		$this->load->view('pelayanan/bukti_pengambilan',compact('data','pl','pelapor'));
	}
	function kwitansi($ref,$name){
		$this->db->select($this->tr_pl->table.'.*, getNoDokumen('.$this->tr_pl->table.'.id) as no_dokumen2, us1.nama as nama_petugas');
		$this->db->join('users us1','us1.user_id='.$this->tr_pl->table.'.kasir','left');
		$data = $this->tr_pl->findOrFail($ref);
		$pl = $this->ms_pl->findOrFail($data->pelayanan_id);
		$pelapor = $this->identitas->findOrFail(['id'=>$data->pelapor]);
		$petugas = $this->user_m->select('nama')->findOrFail(getSession('ref'));
		$this->load->view('pelayanan/kwitansi',compact('data','pl','pelapor','petugas'));
	}
	function detail($ref,$name){
		$this->db->select($this->tr_pl->table.'.*, us1.nama as nama_petugas_loket, us2.nama as nama_petugas_verifikasi, us3.nama as nama_hs, us4.nama as nama_kasir');
		$this->db->join('users us1','us1.user_id='.$this->tr_pl->table.'.petugas_loket','left');
		$this->db->join('users us2','us2.user_id='.$this->tr_pl->table.'.petugas_verifikasi','left');
		$this->db->join('users us3','us3.user_id='.$this->tr_pl->table.'.hs','left');
		$this->db->join('users us4','us4.user_id='.$this->tr_pl->table.'.kasir','left');
		$data = $this->tr_pl->findOrFail($ref);

		$data_item = [];
		foreach ($this->tr_pl_item->get(['tr_pelayanan_id'=>$data->id]) as $item) {
			$value = $item->field_value;
			switch ($item->field_type) {
				case 'file': $value=link_file($value,'',0);break;
				case 'date' : 
						$value = (empty($item->field_data)?tanggal_indo($value):convert_tgl_indo(date($item->field_data,strtotime($value))));
					break;
			}
			$data_item[$item->field_name] = $value;
		}
		// print_r($data_item); exit();
		$pl = $this->ms_pl->findOrFail($data->pelayanan_id);
		// dd(compact('data','data_item','pl'));
		$this->db->order_by('urut','ASC');
		$fields = $this->pl_field->get(['pelayanan_id'=>$pl->pelayanan_id,'field_type !='=>'separator']);

		$pelapor = $this->identitas->first(['id'=>$data->pelapor]);
		$view_pelapor = $this->load->view('data_identitas/view_ajax',['data'=>$pelapor],TRUE);
		$verif_edit='';
		if(!empty($data->verif_edit)){
			if($data->petugas_verifikasi==$data->verif_edit){
				$verif_edit = $data->nama_petugas_verifikasi;
			}else{
				$verif_edit = @$this->user_m->first($data->verif_edit)->nama;
			}
		}
		$qx = "SELECT fl.*, u.nama as nama_user FROM tr_pelayanan_file fl JOIN users u ON(u.user_id=fl.user_id) WHERE fl.tr_pelayanan_id='$data->id'";
		$arsip_file = $this->db->query($qx)->result();
		$this->view('pelayanan/detail',compact('pl','fields','data','data_item','view_pelapor','verif_edit','arsip_file'));
	}
	function hapus($ref,$nama){
		$data = $this->tr_pl->findOrFail($ref);
		if($this->tr_pl->delete($data->id)){
			$this->tr_pl_item->delete(['tr_pelayanan_id'=>$data->id]);
			setFlash('success','Data berhasil dihapus.');
		}else{
			setFlash('error','Data Gagal dihapus.');
		}
		redirect('pelayanan');
	}
	function tolak_verifikasi($ref,$nama){
		$hs = false;
		$data = $this->tr_pl->findOrFail($ref);
		$update = [
					'status'=>$this->input->post('status',TRUE),
					'keterangan'=> $this->input->post('msg',TRUE),
				];
		if(can_access('verifikasi')){
			$update['petugas_verifikasi'] = getSession('ref');
		}elseif(can_access('hs')){
			$update['hs'] = getSession('ref');
			$hs=true;
		}else{ show_404(); }
		

		if($this->tr_pl->update($data->id,$update)){
			setFlash('success','Data berhasil ditolak.');
		}else{
			setFlash('error','Data Gagal ditolak.');
		}
		redirect('pelayanan');
	}
	function verifikasi_data($ref,$nama){
		$this->load->library('ciqrcode');
		$hs = false;
		$data = $this->tr_pl->findOrFail($ref);
		$ms = $this->ms_pl->findOrFail($data->pelayanan_id);
		$update = [
			'status'=>$this->input->post('status',TRUE),
			'keterangan'=>''
		];
		if(empty($data->no_dokumen)){
			$this->tr_pl->insert_no_dokumen($data->id,$ms->kode_layanan,$data->jml_berkas);
		}
		if(can_access('verifikasi')){
			$update['petugas_verifikasi'] = getSession('ref');
			$update['hs'] = $this->input->post('pejabat',TRUE);
			if(empty($data->qrcode)){
				$update['qrcode'] = generate_qr(site_url_qrcode('informasi/pelayanan/'.implode('/',[$data->id,timeCode()])));
			}
		}elseif(can_access('hs')){
			$update['hs'] = getSession('ref');
			if(empty($data->qrcode)){
				$update['qrcode'] = generate_qr(site_url_qrcode('informasi/pelayanan/'.implode('/',[$data->id,timeCode()])));
			}
			$hs = true;
		}else{ show_404(); }

		if($this->tr_pl->update($data->id,$update)){
			setFlash('success','Data berhasil diverifikasi.');
			if($hs){
				sendNotif($data->petugas_verifikasi,"Data Pelayanan Telah di verifikasi oleh ".getSession('nama').'(Home Staff)','Pelayanan diverifikasi',site_url('pelayanan/detail/'.$data->id.'/verifikasi_pelayanan'));
				response_json(['status'=>200,'message'=>'OK']);
				return;
			}else{
				sendNotif($data->petugas_loket,"Data Pelayanan Telah di verifikasi oleh ".getSession('nama').'(Verifikator)','Pelayanan diverifikasi',site_url('pelayanan/detail/'.$data->id.'/verifikasi_pelayanan'));
			}
		}else{
			setFlash('error','Data Gagal diverifikasi.');
			if($hs){
				response_json(['status'=>500,'message'=>'Data Gagal diverifikasi.']);
				return;
			}
		}
		if(!$hs){
			redirect('pelayanan');
		}
	}
	function selesai(){
		$target = 'pelayanan';
		$ref = $this->input->post('ref',TRUE);
		$tr = $this->tr_pl->findOrFail($ref);
		if(!empty($_FILES['berkas']['name'])){
			$ex = explode('.', $_FILES['berkas']['name']);
			$ext = end($ex);
			$name_file = $tr->id.date('YmdHis').'.'.$ext;
			$path = 'assets/uploads/berkas';
			$this->load->library('upload');
			if(upload('berkas',$name_file,$path,'pdf|doc|docx')){
				if(can_access(['loket','verifikasi'])){
					$update = [
						'file_berkas' => implode('/', [$path,$name_file])
					];
					if(intval($tr->biaya)==0){
						$update['status']=5;
					}
				}elseif(can_access('kasir')){
					$update = [
						'status' => 5,
						'kasir' => getSession('ref'),
						'file_berkas_kasir' => implode('/', [$path,$name_file])
					];
				}
				if($this->tr_pl->update($tr->id,$update)){
					setFlash('success','Data Berhasil diperbarui.');
					redirect($target);
				}else{
					setFlash('error','Gagal menyimpan data.');
					redirect(back_link($target));
				}
			}else{
				setFlash('error',$this->upload->display_errors());
				redirect(back_link($target));
			}
		}else{
			setFlash('error','File Tidak ditemukan.');
			redirect(back_link($target));
		}
	}
	function telah_diambil($ref,$nama){
		$data = $this->tr_pl->findOrFail($ref);
		if($this->tr_pl->update($data->id,['status'=>4,'keterangan'=>'','kasir'=>getSession('ref'),'tgl_ambil'=>date('Y-m-d H:i:s')])){
			setFlash('success','Data Telah Selesai.');
		}else{
			setFlash('error','Data Gagal diperbarui.');
		}
		redirect('pelayanan');
	}
	function simpan_data(){
		// print_r($_POST); exit();

		$this->db->trans_start();

		$target = 'pelayanan';
		$ref = $this->input->post('ref',TRUE);
		$xref = (empty($ref)?'TRL'.timeCode():$ref);
		$pl = $this->ms_pl->findOrFail($this->input->post('pl_ref',TRUE));

		$this->load->library('form_validation');
	 	$this->load->library('upload');

		$rules = [
			[
				'field' => 'pl_ref',
                'label' => 'Pelayanan',
                'rules' => 'trim|required|min_length[20]'
			],[
				'field' => 'pelapor',
                'label' => 'Pelapor',
                'rules' => 'trim|required'
			]
		];
		$data_pl=[
			'id' => $xref,
			'pelayanan_id' => $pl->pelayanan_id,
			'biaya' => $pl->biaya,
			'fungsi' => getSession('fungsi'),
			// 'nama' => $this->input->post('nama',TRUE),
			'pelapor' => $this->input->post('pelapor',TRUE),
			'jml_berkas' => (empty($this->input->post('jml_berkas',TRUE))?1:$this->input->post('jml_berkas',TRUE)),
			'created_at' => date('Y-m-d H:i:s'),
		];

		$btn = $this->input->post('btn');
		$data_pl['status']=intval($btn);

		if(can_access(['loket','kasir'])){
			$data_pl['petugas_loket'] = getSession('ref');
		}
		if(can_access('verifikasi')){
			if(empty($ref)){
				$this->load->library('ciqrcode');
				$data_pl['petugas_verifikasi'] = getSession('ref');
				$data_pl['qrcode'] = generate_qr(site_url_qrcode('informasi/pelayanan/'.implode('/',[$xref,timeCode()])));
			}else{
				$x = $this->tr_pl->first($ref);
				if($x->status==1){
					$data_pl['verif_edit'] = getSession('ref');
					if(!empty($x->petugas_loket)){
						sendNotif($x->petugas_loket,"Data Pelayanan Telah diubah oleh ".getSession('nama').'(Verifikator)');
					}
				}	
			}
		}

		$hs = $this->input->post('hs',TRUE);
		if(!empty($hs)&&intval($btn)==2){
			$data_pl['hs']=$hs;
			sendNotif($hs,"Permintaan Verifikasi Pelayanan ",'Notifikasi',site_url('pelayanan/detail/'.$xref.'/permintaan-verifikasi'));
		}

		$data_pl_item=[];
		$deleted_item_ref=[];
		foreach ($this->pl_field->get(['pelayanan_id'=>$pl->pelayanan_id,'field_type !='=>'separator']) as $field) {
			$required = (intval($field->required)==1);
			$cek_idn = [];
			$cek_mys = [];
			if($required){
				if($field->field_type=='file'){
					if(empty($_FILES[$field->field_name]['name'])&&empty($ref)){
						$rules[]=[
							'field' => $field->field_name,
			                'label' => $field->label,
			                'rules' => 'required'
						];
					}
				}elseif($field->field_type=='db_identitas'){
					foreach (explode('||', $field->data) as $fl) {
						$rules[]=[
							'field' => $fl.'_'.$field->field_name,
			                'label' => $field->label.' - '.identitas_field($fl),
			                'rules' => 'required'
						];
					}
				}elseif($field->field_type=='db_wilayah_id'){
					if($field->data=='provinsi'){$cek_idn=['provinsi'];}
					elseif($field->data=='kota'){$cek_idn=['provinsi','kota'];}
					elseif($field->data=='kecamatan'){$cek_idn=['provinsi','kota','kecamatan'];}

					foreach (['provinsi'=>'Provinsi','kota'=>'Kota / Kabupaten','kecamatan'=>'Kecamatan'] as $fl=>$label) {
						if(in_array($fl,$cek_idn)){
							$rules[]=[
								'field' => $fl.'_'.$field->field_name,
								'label' => $field->label.' - '.$label,
								'rules' => 'required'
							];
						}
					}
				}elseif($field->field_type=='db_wilayah_my'){
					if($field->data=='negeri'){$cek_mys=['negeri'];}
					elseif($field->data=='daerah'){$cek_mys=['negeri','daerah'];}
					elseif($field->data=='distrik'){$cek_mys=['negeri','daerah','distrik'];}
					
					foreach (['negeri'=>'Negeri','daerah'=>'Daerah','distrik'=>'Distrik'] as $fl=>$label) {
						if(in_array($fl,$cek_mys)){
							if($fl!='distrik'){
								$rules[]=[
									'field' => $fl.'_'.$field->field_name,
									'label' => $field->label.' - '.$label,
									'rules' => 'required'
								];
							}
						}
					}
				}elseif($field->field_type=='list'){
					$rules[]=[
						'field' => $field->field_name.'[]',
		                'label' => $field->label,
		                'rules' => 'required'
					];
				}else{
					$rules[]=[
						'field' => $field->field_name,
		                'label' => $field->label,
		                'rules' => 'required'
					];
				}
			}
			$field_value = (in_array($field->field_type,['file','db_identitas','db_wilayah_id','db_wilayah_my','list']))?'-':$this->input->post($field->field_name,TRUE);
			if(!empty($field_value)){
				if($field->field_type=='file'){
					if(!empty($_FILES[$field->field_name]['name'])){
						$data_pl_item[$field->field_name]=[
							'tr_pelayanan_id' => $xref,
							'field_label' => $field->label,
							'field_name' => $field->field_name,
							'field_type' => $field->field_type,
							'field_data' => $field->data,
							'field_value' => '',
						];
					}
				}elseif($field->field_type=='db_identitas'){
					foreach (explode('||', $field->data) as $fl) {
						$fieldname = $fl.'_'.$field->field_name;
						$field_value = $this->input->post($fieldname,TRUE);
						$deleted_item_ref[]=$fieldname;
						$data_pl_item[$fieldname]=[
							'tr_pelayanan_id' => $xref,
							'field_label' => $field->label.' - '.identitas_field($fl),
							'field_name' => $fieldname,
							'field_type' => 'text',
							'field_data' => json_encode($field),
							'field_value' => $field_value,
						];
					}
				}elseif($field->field_type=='db_wilayah_id'){
					foreach (['provinsi'=>'Provinsi','kota'=>'Kota / Kabupaten','kecamatan'=>'Kecamatan'] as $fl=>$label) {
						$fieldname = $fl.'_'.$field->field_name;
						$deleted_item_ref[]=$fieldname;
						if(in_array($fl,$cek_idn)){
							$field_value = $this->input->post($fieldname,TRUE);
							$data_pl_item[$fieldname]=[
								'tr_pelayanan_id' => $xref,
								'field_label' => $field->label.' - '.$label,
								'field_name' => $fieldname,
								'field_type' => 'text',
								'field_data' => json_encode($field),
								'field_value' => $field_value,
							];
						}
					}
				}elseif($field->field_type=='db_wilayah_my'){
					foreach (['negeri'=>'Negeri','daerah'=>'Daerah','distrik'=>'Distrik'] as $fl=>$label) {
						$fieldname = $fl.'_'.$field->field_name;
						$deleted_item_ref[]=$fieldname;
						if(in_array($fl,$cek_mys)){
							$field_value = $this->input->post($fieldname,TRUE);
							$data_pl_item[$fieldname]=[
								'tr_pelayanan_id' => $xref,
								'field_label' => $field->label.' - '.$label,
								'field_name' => $fieldname,
								'field_type' => 'text',
								'field_data' => json_encode($field),
								'field_value' => $field_value,
							];
						}
					}
				}elseif($field->field_type=='list'){
					$deleted_item_ref[]=$field->field_name;
					$field_value = $this->input->post($field->field_name,TRUE);
					$data_pl_item[$field->field_name]=[
						'tr_pelayanan_id' => $xref,
						'field_label' => $field->label,
						'field_name' => $field->field_name,
						'field_type' => $field->field_type,
						'field_data' => $field->data,
						'field_value' => implode(';;',$field_value),
					];
				}else{
					$deleted_item_ref[]=$field->field_name;
					$data_pl_item[$field->field_name]=[
						'tr_pelayanan_id' => $xref,
						'field_label' => $field->label,
						'field_name' => $field->field_name,
						'field_type' => $field->field_type,
						'field_data' => $field->data,
						'field_value' => $field_value,
					];
				}
			}
		}
		// print_r([$_POST,$data_pl_item]); exit();
		// if(count($data_pl_item)<=0){ setFlash('error','Invalid Form Validation'); redirect(back_link($target));}
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$err=FALSE;
			foreach ($_FILES as $key => $file) {
				if(array_key_exists($key,$data_pl_item)){
					if(upload($key,'','assets/uploads','*',FALSE)){
						$data_pl_item[$key]['field_value'] = 'assets/uploads/'.$this->upload->data('file_name');
						$deleted_item_ref[]=$key;
					}else{
						setFlash('errors',$this->upload->display_errors());
						$err=TRUE;
						break;
					}
				}
			}
			if(!$err){
				$data_pl['updated_at'] = date('Y-m-d H:i:s');
				if(empty($ref)){
					$data_pl['created_at'] = date('Y-m-d H:i:s');
					$this->tr_pl->insert($data_pl);
					if(!empty($data_pl_item)){
						$this->tr_pl_item->insertBatch($data_pl_item);
					}
				}else{
					$this->tr_pl->update($xref,$data_pl);
					if(!empty($data_pl_item)){
						$this->db->where_in('field_name',$deleted_item_ref);
						$this->tr_pl_item->delete(['tr_pelayanan_id'=>$xref]);
						$this->tr_pl_item->insertBatch($data_pl_item);
					}
				}
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					setFlash('error','Data Pelayanan gagal diSimpan');
				}else{
					setFlash('success','Data Berhasil diSimpan.');
				}
			}else{
				setFlash('error','Invalid Form');
			}
		}else{
			setFlash('error',$this->form_validation->error_string());
			$target = back_link($target);
		}
		redirect($target);
	}

	function simpan_data2(){
		// print_r($_POST); exit();
		$this->db->trans_start();

		$target = 'pelayanan';
		$ref = $this->input->post('ref',TRUE);
		$xref = (empty($ref)?'TRL'.timeCode():$ref);
		$pl = $this->ms_pl->findOrFail($this->input->post('pl_ref',TRUE));

		$this->load->library('form_validation');
	 	$this->load->library('upload');

		$rules = [
			[
				'field' => 'pl_ref',
                'label' => 'Pelayanan',
                'rules' => 'trim|required|min_length[20]'
			],[
				'field' => 'pelapor',
                'label' => 'Pelapor',
                'rules' => 'trim|required'
			]
		];
		$data_pl=[
			'id' => $xref,
			'pelayanan_id' => $pl->pelayanan_id,
			'biaya' => $pl->biaya,
			'fungsi' => getSession('fungsi'),
			'hs' => $this->input->post('user_hs',TRUE),
			'status' => $this->input->post('status',TRUE),
			'pelapor' => $this->input->post('pelapor',TRUE),
			'jml_berkas' => (empty($this->input->post('jml_berkas',TRUE))?1:$this->input->post('jml_berkas',TRUE)),
		];

		$btn = $this->input->post('btn');
		$data_pl['status']=intval($btn);

		if(can_access(['loket','kasir'])){
			$data_pl['petugas_loket'] = getSession('ref');
		}
		if(can_access('verifikasi')){
			if(empty($ref)){
				$this->load->library('ciqrcode');
				$data_pl['petugas_verifikasi'] = getSession('ref');
				$data_pl['qrcode'] = generate_qr(site_url_qrcode('informasi/pelayanan/'.implode('/',[$xref,timeCode()])));
			}else{
				$x = $this->tr_pl->first($ref);
				if($x->status==1){
					$data_pl['verif_edit'] = getSession('ref');
				}	
			}
		}

		$hs = $this->input->post('hs',TRUE);
		if(!empty($hs)&&intval($btn)==2){
			$data_pl['hs']=$hs;
		}

		$data_pl_item=[];
		$deleted_item_ref=[];
		foreach ($this->pl_field->get(['pelayanan_id'=>$pl->pelayanan_id,'field_type !='=>'separator']) as $field) {
			$required = (intval($field->required)==1);
			$cek_idn = [];
			$cek_mys = [];
			if($required){
				if($field->field_type=='file'){
					if(empty($_FILES[$field->field_name]['name'])&&empty($ref)){
						$rules[]=[
							'field' => $field->field_name,
			                'label' => $field->label,
			                'rules' => 'required'
						];
					}
				}elseif($field->field_type=='db_identitas'){
					foreach (explode('||', $field->data) as $fl) {
						$rules[]=[
							'field' => $fl.'_'.$field->field_name,
			                'label' => $field->label.' - '.identitas_field($fl),
			                'rules' => 'required'
						];
					}
				}elseif($field->field_type=='db_wilayah_id'){
					if($field->data=='provinsi'){$cek_idn=['provinsi'];}
					elseif($field->data=='kota'){$cek_idn=['provinsi','kota'];}
					elseif($field->data=='kecamatan'){$cek_idn=['provinsi','kota','kecamatan'];}

					foreach (['provinsi'=>'Provinsi','kota'=>'Kota / Kabupaten','kecamatan'=>'Kecamatan'] as $fl=>$label) {
						if(in_array($fl,$cek_idn)){
							$rules[]=[
								'field' => $fl.'_'.$field->field_name,
								'label' => $field->label.' - '.$label,
								'rules' => 'required'
							];
						}
					}
				}elseif($field->field_type=='db_wilayah_my'){
					if($field->data=='negeri'){$cek_mys=['negeri'];}
					elseif($field->data=='daerah'){$cek_mys=['negeri','daerah'];}
					elseif($field->data=='distrik'){$cek_mys=['negeri','daerah','distrik'];}
					
					foreach (['negeri'=>'Negeri','daerah'=>'Daerah','distrik'=>'Distrik'] as $fl=>$label) {
						if(in_array($fl,$cek_mys)){
							if($fl!='distrik'){
								$rules[]=[
									'field' => $fl.'_'.$field->field_name,
									'label' => $field->label.' - '.$label,
									'rules' => 'required'
								];
							}
						}
					}
				}elseif($field->field_type=='list'){
					$rules[]=[
						'field' => $field->field_name.'[]',
		                'label' => $field->label,
		                'rules' => 'required'
					];
				}else{
					$rules[]=[
						'field' => $field->field_name,
		                'label' => $field->label,
		                'rules' => 'required'
					];
				}
			}
			$field_value = (in_array($field->field_type,['file','db_identitas','db_wilayah_id','db_wilayah_my','list']))?'-':$this->input->post($field->field_name,TRUE);
			if(!empty($field_value)){
				if($field->field_type=='file'){
					if(!empty($_FILES[$field->field_name]['name'])){
						$data_pl_item[$field->field_name]=[
							'tr_pelayanan_id' => $xref,
							'field_label' => $field->label,
							'field_name' => $field->field_name,
							'field_type' => $field->field_type,
							'field_data' => $field->data,
							'field_value' => '',
						];
					}
				}elseif($field->field_type=='db_identitas'){
					foreach (explode('||', $field->data) as $fl) {
						$fieldname = $fl.'_'.$field->field_name;
						$field_value = $this->input->post($fieldname,TRUE);
						$deleted_item_ref[]=$fieldname;
						$data_pl_item[$fieldname]=[
							'tr_pelayanan_id' => $xref,
							'field_label' => $field->label.' - '.identitas_field($fl),
							'field_name' => $fieldname,
							'field_type' => 'text',
							'field_data' => json_encode($field),
							'field_value' => $field_value,
						];
					}
				}elseif($field->field_type=='db_wilayah_id'){
					foreach (['provinsi'=>'Provinsi','kota'=>'Kota / Kabupaten','kecamatan'=>'Kecamatan'] as $fl=>$label) {
						$fieldname = $fl.'_'.$field->field_name;
						$deleted_item_ref[]=$fieldname;
						if(in_array($fl,$cek_idn)){
							$field_value = $this->input->post($fieldname,TRUE);
							$data_pl_item[$fieldname]=[
								'tr_pelayanan_id' => $xref,
								'field_label' => $field->label.' - '.$label,
								'field_name' => $fieldname,
								'field_type' => 'text',
								'field_data' => json_encode($field),
								'field_value' => $field_value,
							];
						}
					}
				}elseif($field->field_type=='db_wilayah_my'){
					foreach (['negeri'=>'Negeri','daerah'=>'Daerah','distrik'=>'Distrik'] as $fl=>$label) {
						$fieldname = $fl.'_'.$field->field_name;
						$deleted_item_ref[]=$fieldname;
						if(in_array($fl,$cek_mys)){
							$field_value = $this->input->post($fieldname,TRUE);
							$data_pl_item[$fieldname]=[
								'tr_pelayanan_id' => $xref,
								'field_label' => $field->label.' - '.$label,
								'field_name' => $fieldname,
								'field_type' => 'text',
								'field_data' => json_encode($field),
								'field_value' => $field_value,
							];
						}
					}
				}elseif($field->field_type=='list'){
					$deleted_item_ref[]=$field->field_name;
					$field_value = $this->input->post($field->field_name,TRUE);
					$data_pl_item[$field->field_name]=[
						'tr_pelayanan_id' => $xref,
						'field_label' => $field->label,
						'field_name' => $field->field_name,
						'field_type' => $field->field_type,
						'field_data' => $field->data,
						'field_value' => implode(';;',$field_value),
					];
				}else{
					$deleted_item_ref[]=$field->field_name;
					$data_pl_item[$field->field_name]=[
						'tr_pelayanan_id' => $xref,
						'field_label' => $field->label,
						'field_name' => $field->field_name,
						'field_type' => $field->field_type,
						'field_data' => $field->data,
						'field_value' => $field_value,
					];
				}
			}
		}
		// print_r([$_POST,$data_pl_item]); exit();
		// if(count($data_pl_item)<=0){ setFlash('error','Invalid Form Validation'); redirect(back_link($target));}
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()){
			$err=FALSE;
			foreach ($_FILES as $key => $file) {
				if(array_key_exists($key,$data_pl_item)){
					if(upload($key,'','assets/uploads','*',FALSE)){
						$data_pl_item[$key]['field_value'] = 'assets/uploads/'.$this->upload->data('file_name');
						$deleted_item_ref[]=$key;
					}else{
						setFlash('errors',$this->upload->display_errors());
						$err=TRUE;
						break;
					}
				}
			}
			if(!$err){
				if(empty($ref)){
					$data_pl['created_at'] = date('Y-m-d H:i:s');
					$this->tr_pl->insert($data_pl);
					if(!empty($data_pl_item)){
						$this->tr_pl_item->insertBatch($data_pl_item);
					}
				}else{
					$this->tr_pl->update($xref,$data_pl);
					if(!empty($data_pl_item)){
						$this->db->where_in('field_name',$deleted_item_ref);
						$this->tr_pl_item->delete(['tr_pelayanan_id'=>$xref]);
						$this->tr_pl_item->insertBatch($data_pl_item);
					}
				}
				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE){
					setFlash('error','Data Gagal diSimpan.');
				}else{
					setFlash('success','Data Berhasil diSimpan.');
				}
			}else{
				setFlash('error','Invalid Form');
			}
		}else{
			setFlash('error',$this->form_validation->error_string());
			$target = back_link($target);
		}
		redirect($target);
	}

	function upload_file_pelayanan(){
		if(!empty($_FILES['file']['name'])){
			$this->load->library('upload');
			$ref = $this->input->post('ref');
			$kode_pl = $this->input->post('kode_pl');
			$nm_file = $this->input->post('nm_file');
			$data = $this->tr_pl->findOrFail($ref);
			if(upload('file','','assets/uploads/berkas','pdf|doc|docx',false)){
				$up_name = $this->upload->data('file_name');
				if($this->db->insert('tr_pelayanan_file',[
					'tr_pelayanan_id' => $ref,
					'nama_file' => $nm_file,
					'file' => 'assets/uploads/berkas/'.$up_name,
					'user_id' => getSession('ref')
				])){
					setFlash('success','File Berhasil diUpload');
				}else{
					setFlash('error','File Gagal diUpload');
				}
			}else{
				setFlash('error',$this->upload->display_errors());
			}
			$ref = @$_SERVER['HTTP_REFERER'];
			$target = (empty($ref)?'pelayanan':$ref);
			redirect($target);
		}else show404();
	}
	function delete_file_pelayanan(){
		$w = [
			'tr_pelayanan_id' => $this->input->post('ref'),
			'nama_file' => $this->input->post('nm'),
			'file' => $this->input->post('file'),
		];
		$data = $this->db->where($w)->get('tr_pelayanan_file')->row();
		if(empty($data)) show404();
		else{
			if($this->db->where($w)->delete('tr_pelayanan_file')){
				setFlash('success','File Berhasil diHapus.');
				@unlink(FCPATH.$data->file);
			}else{
				setFlash('error','File Gagal diHapus.');
			}
			$ref = @$_SERVER['HTTP_REFERER'];
			$target = (empty($ref)?'pelayanan':$ref);
			redirect($target);
		}
	}
	function scan_log(){
		$this->load->model('ScanLog','scan_log');
		$this->load->library('Datatables', 'datatables');
		header('Content-Type: application/json');
		echo $this->datatables
			->editColumn('tanggal',function($data,$row){return tanggal_indo($data);})
			->where(['pelayanan_id'=>$this->input->post('ref')])
			->editColumn('viewer',function($data,$row){return numb($data).' view';})
			->table($this->scan_log->table)->draw();
	}
	private function getNoDokumen($kode_layanan){
		$q = "SELECT MAX(no_dokumen) as no FROM tr_pelayanan WHERE pelayanan_id IN (SELECT ms.pelayanan_id FROM master_pelayanan ms WHERE ms.kode_layanan='$kode_layanan')";
		$x = intval(@$this->db->query($q)->row()->no);
		$no = ($x+1);
		return $no;
	}
	function kembalikan_verifikasi($ref,$n){
		$data = $this->tr_pl->findOrFail($ref);
		$alasan = $this->input->post('alasan',true);
		if($this->tr_pl->update($ref,['keterangan'=>$alasan,'status'=>92])){
			setFlash('success','Data Berhasil dikembalikan');
		}else{
			setFlash('error','Data Gagal dikembalikan');
		}
		$ref = @$_SERVER['HTTP_REFERER'];
		$target = (empty($ref)?'pelayanan':$ref);
		redirect($target);
	}

	function hapus2($ref){
		if(can_access(['admin'])){
			$this->tr_pl->findOrFail($ref);
			if($this->tr_pl->update(['id'=>$ref],['deleted_at'=>date('Y-m-d H:i:s')])){
				setFlash('success','Data Berhasil dihapus');
			}else{
				setFlash('error','Data Gagal dihapus');
			}
			$ref = @$_SERVER['HTTP_REFERER'];
			$target = (empty($ref)?'pelayanan':$ref);
			redirect($target);
		}else{
			e404();
		}

	}

	function restore($ref){
		if(can_access(['admin'])){
			$this->tr_pl->findOrFail($ref);
			if($this->tr_pl->update(['id'=>$ref],['deleted_at'=>null])){
				response_json(['status'=>200,'message'=>'Data berhasil dikembalikan']);
			}else{
				response_json(['status'=>500,'message'=>'Data gagal dikembalikan']);
			}
		}else{
			response_json(['status'=>404,'message'=>'Page Not Found']);
			// e404();
		}
	}

	function hapus_permanen($ref){
		if(can_access(['admin'])){
			$this->tr_pl->findOrFail($ref);
			if($this->tr_pl->delete(['id'=>$ref])){
				$this->db->where('tr_pelayanan_id',$ref)->delete('tr_pelayanan_item');
				response_json(['status'=>200,'message'=>'Data berhasil dihapus secara permanen']);
			}else{
				response_json(['status'=>500,'message'=>'Data gagal dihapus']);
			}
		}else{
			response_json(['status'=>404,'message'=>'Page Not Found']);
			// e404();
		}
	}
	
}
