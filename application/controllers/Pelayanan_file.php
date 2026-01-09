<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Settings;
use \PhpOffice\PhpWord\Element\Link;
use \PhpOffice\PhpWord\IOFactory;
use Mpdf\Mpdf;
class Pelayanan_file extends CI_Controller {
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
	function file($ref,$name,$type='doc',$act='read'){
		// print_r(compact('ref','name','type','act')); exit();
		$this->db->select('master_pelayanan.template_file, master_pelayanan.pelayanan, master_pelayanan.kode_layanan, tr_pelayanan.*');
		$this->db->join('master_pelayanan','master_pelayanan.pelayanan_id=tr_pelayanan.pelayanan_id','inner');
		$data = $this->tr_pl->findOrFail($ref);
		$refID = $data->id;
		$data_item = $this->tr_pl_item->get(['tr_pelayanan_id'=>"$data->id"]);

		if(empty($data->no_dokumen)){
			// $no = $this->getNoDokumen($data->kode_layanan);
			// $x = $this->db->where('kode_layanan',$data->kode_layanan)->get('master_pelayanan')->result();
			// $pl_idx = [];
			// foreach ($x as $xid) { $pl_idx[]=$xid->pelayanan_id; }
			// $this->db->select('max(no_dokumen) as x');
			// $this->db->where_in('pelayanan_id',$pl_idx);
			// $x = (int) $this->tr_pl->first(['YEAR(created_at)'=>date('Y')])->x;
			// $no = ($x+1);
			// // print_r([$this->db->last_query(),$no,$data]); exit();
			// $this->tr_pl->update($data->id,['no_dokumen'=>$no]);
			// $data->no_dokumen = $no;
			$data->no_dokumen = $this->tr_pl->insert_no_dokumen($data->id,$data->kode_layanan,$data->jml_berkas);
		}
		$user_hs = $this->user_m->first($data->hs);
		$items = [];
		$items['kode_tr'] = $data->id;
		$items['no_register'] = $data->no_dokumen;
		foreach (range(2, 6) as $n) {$items['no_register_'.$n] = str_pad($data->no_dokumen, $n,0,STR_PAD_LEFT);}
		$items['nip_hs'] = @$user_hs->nip;
		$items['nama_hs'] = @$user_hs->nama;
		$items['kode_report_hs'] = @$user_hs->kode_report;
		$items['jabatan_hs'] = @$user_hs->jabatan;
		$items['jabatan_hs_en'] = @$user_hs->jabatan_en;
		
		$time_tr = strtotime($data->created_at);
		$items['date_tr_short'] = date('d-m-Y',$time_tr);
		$items['date_tr_long'] = tanggal_indo(date('Y-m-d',$time_tr));
		$items['date_tr_day'] = tanggal_indo(date('Y-m-d',$time_tr),TRUE);
		$items['day_tr'] = hari_indo(date('N',$time_tr));
		$pelapor = $this->identitas->findOrFail(['id'=>$data->pelapor]);
		foreach ($pelapor as $key => $value) {
			if(in_array($key,['negeri','provinsi','daerah','kota','distrik','kecamatan'])){
				$items[$key.'_pelapor'] = strtoupper($value);
			}elseif(in_array($key,['tgl_lahir'])){
				$items[$key.'_pelapor'] = tanggal_indo($value);
			}else{
				$items[$key.'_pelapor'] = strtoupper($value);
			}
		}
		$umur = umur_th_bl($pelapor->tgl_lahir);
		$items['umur_pelapor'] = $umur->y.' Tahun '.(empty($umur->m)?'':$umur->m.' Bulan');
		// $items['umur_pelapor'] = intval(date('Y')) - intval(date('Y',strtotime($pelapor->tgl_lahir)));
		if(!empty($data->qrcode)){
			if(file_exists(FCPATH.$data->qrcode)){
				$items['qr_code'] = base_url($data->qrcode);
			}
		}
		$items = phpword_auto_items($items);
		// print_r($items); exit();
		$nama_file = url_title(strtoupper($data->kode_layanan.'-'.$refID));
		$file_template = FCPATH.$data->template_file;
		if(!is_file($file_template)){show_404(); exit();}
		else{
			Settings::setTempDir(FCPATH.'assets/template/tmp_file');
			Settings::setOutputEscapingEnabled(true);
			$word = new PhpWord;
			$word->getCompatibility()->setOoxmlVersion(15);
			$tem = $word->loadTemplate($file_template);

			$var_count = $tem->getVariableCount();

			phpword_autoval($tem,$items);
			// $tem->setValue('nama', $data->nama);
			foreach ($data_item as $item) {
				$value = $item->field_value;
				$field_name = $item->field_name;
				switch ($item->field_type) {
					case 'file': 
							$url = base_url($value);
							if(iki_gambar($value)){
								$tem->setImageValue($field_name,$url);
							}else{
								$tem->setValue($field_name, $url);
							}
						break;
					case 'list' :
							$data = []; 
							if(!empty($value)){
								$loop = intval(@$var_count[$field_name]);
								if($loop>0){
									foreach (explode(';;',$value) as $v) {
										$data[]=[$field_name => strtoupper($v)];
									}
									for($i=0; $i<$loop;$i++){
										$tem->cloneBlock($field_name.'_block',0,true,false,$data);
									}
								}
							}
							$tem->cloneBlock($field_name.'_block',0,true,false,$data);
						break;
					case 'date' : $tem->setValue($field_name, strtoupper( (empty($item->field_data)?tanggal_indo($value):convert_tgl_indo(date($item->field_data,strtotime($value)))) ) );break;
					default : $tem->setValue($field_name,strtoupper($value)); break;
				}
			}

			// $path = $tem->save();
			$path = FCPATH.'assets/template/tmp_file/'.$refID.'.docx';
			$tem->saveAs($path);
			if($type=='pdf'){
				$path_lib = FCPATH.'/vendor/mpdf/mpdf';
				$pathpdf = FCPATH.'assets/pdf_tte/'.$refID.'.pdf';
				Settings::setPdfRenderer(Settings::PDF_RENDERER_MPDF, $path_lib);
	
				$iof = IOFactory::load($path);
				$iofw = IOFactory::createWriter($iof, 'PDF');
				$iofw->save($pathpdf);
				if($act=='tte'){
					if(file_exists($pathpdf)){
						$this->load->library('TTE');
						$tte = new TTE;
						$resp = $tte->signPDF([
							'file' => $pathpdf,
							'tampilan' => 'invisible',
						]);
						if(empty($resp)||$tte->isError){
							response_json(['status'=>500,'message'=>$tte->errorMessage]);
						}else{
							if($this->tr_pl->update(['id'=>$ref],['file_esign'=>$resp])){
								if(substr($ref,0,3)=='TRO'){
									$this->load->library('KonsulerPublic');
									$kp = new KonsulerPublic;
									$kp->updateFileESign($ref,base_url('assets/'.$resp));
								}
								response_json(['status'=>200,'message'=>'Success']);
							}else{
								response_json(['status'=>500,'message'=>'Gagal memperbarui data pelayanan']);
							}
						}
					}else{
						response_json(['status'=>500,'message'=>'Generate File Error']);
					}
				}else{
					header("Cache-Control: public");
					header('Content-Type: application/pdf');
					header("Content-Disposition: $disposition; filename=$nama_file.pdf");
					ob_clean();
					flush();
					readfile($pathpdf);
					unlink($path);
				}

			}else{
				// print_r(['path'=>$path,'iof'=>$x,'tmpl'=>$data->template_file]); 
				// exit();
				// $arr_fl = explode('.',$data->template_file);
				// $nama_file.='.'.end($arr_fl);
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header('Content-Type: application/octet-stream');
				// header("Content-Disposition: attachment; filename=helloWorld.docx");
				// $nama_file = 
				header("Content-Disposition: $disposition; filename=$nama_file.docx");
				// // header("Content-Type: application/docx");
				header("Content-Transfer-Encoding: binary");
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($path));
				ob_clean();
				flush();
				readfile($path);
				unlink($path);
				// exit();
			}

		}
	}

    // --------------- test file ----------------------- //

	function test_file($ref,$name){
		$disposition = 'attachment';
        // print_r($ref);exit('test');
		$data = $this->ms_pl->findOrFail($ref);
		$data_item = $this->pl_field->get(['pelayanan_id'=>$data->pelayanan_id,'field_type !='=>'separator']);
		
		$items = [];
		$items['kode_tr'] = 'TRL1234556789012345678901234';
		$items['no_register'] = rand(1,9);
		foreach (range(2, 6) as $n) {$items['no_register_'.$n] = str_pad($items['no_register'], $n,0,STR_PAD_LEFT);}
		$items['nip_hs'] = 1234567890;
		$items['nama_hs'] = 'Pejabat Penanda Tangan';
		$items['kode_report_hs'] = 'KodeReportHS';
		$items['jabatan_hs'] = 'Jabatan HS';
		$items['jabatan_hs_en'] = 'Jabatan HS ENGLISH';
		
		$items['date_tr_short'] = date('d-m-Y');
		$items['date_tr_long'] = tanggal_indo(date('Y-m-d'));
		$items['date_tr_day'] = tanggal_indo(date('Y-m-d'),TRUE);
		$items['day_tr'] = hari_indo(date('N'));
        
		foreach ($this->identitas->get_fields() as $field) {
			if($field->name=='tgl_lahir'){
				$items[$field->name.'_pelapor'] = tanggal_indo(date('Y-m-d'));
			}else{
				$items[$field->name.'_pelapor'] = "~".$field->name."_pelapor~";
			}
		}
		
		$umur = umur_th_bl(rand(1990,intval(date('Y'))).'-'.date('m-d', strtotime('- '.rand(1,20).' month')));
		$items['umur_pelapor'] = $umur->y.' Tahun '.(empty($umur->m)?'':$umur->m.' Bulan');

		$items = phpword_auto_items($items);
		$nama_file = url_title(strtoupper($data->kode_layanan.'_temp'));
		$file_template = FCPATH.$data->template_file;
		if(!is_file($file_template)){show_404(); exit();}
		else{
			Settings::setTempDir(FCPATH.'assets/template/tmp_file');
			$word = new PhpWord;
			$word->getCompatibility()->setOoxmlVersion(15);
			$tem = $word->loadTemplate($file_template);

			$var_count = $tem->getVariableCount();

			phpword_autoval($tem,$items);
			foreach ($data_item as $item) {
				$field_name = $item->field_name;
				switch ($item->field_type) {
					case 'number' : $tem->setValue($field_name,rand(1,9));break;
					case 'list' :
							$data = []; 
							$loop = intval(@$var_count[$field_name]);
							if($loop>0){
								foreach (range(1,rand(2,5)) as $v) {
									$data[]=[$field_name => "Contoh List $v"];
								}
								for($i=0; $i<$loop;$i++){
									$tem->cloneBlock($field_name.'_block',0,true,false,$data);
								}
							}
						break;
					case 'date' : $value = date('Y-m-d'); $tem->setValue($field_name,(empty($item->data)?tanggal_indo($value):convert_tgl_indo(date($item->data,strtotime($value)))));break;
					case 'db_identitas' : 
							foreach (explode('||',$item->data) as $vx) {
								$fl_name = $vx.'_'.$item->field_name;
								$tem->setValue($fl_name,"~$fl_name~");
							}
						break;
					default : $tem->setValue($field_name,'~'.$item->label.'~'); break;
				}
			}
			$path = $tem->save();
			// $arr_fl = explode('.',$data->template_file);
			// $nama_file.='.'.end($arr_fl);
			header("Content-Disposition: $disposition; filename=$nama_file.docx");
			readfile($path);
			unlink($path);
		}
	}

	function file_kwitansi($ref,$name,$type='download'){
		$disposition = ($type=='read'?'inline':'attachment');

		$this->db->select('master_pelayanan.template_kwitansi, master_pelayanan.pelayanan, master_pelayanan.kode_layanan, tr_pelayanan.*, getNoDokumen(tr_pelayanan.id) as no_doc');
		$this->db->join('master_pelayanan','master_pelayanan.pelayanan_id=tr_pelayanan.pelayanan_id','inner');
		$data = $this->tr_pl->findOrFail($ref);
		$data_item = $this->tr_pl_item->get(['tr_pelayanan_id'=>"$data->id"]);

		$user_hs = $this->user_m->first($data->hs);
		$items = [];
		$items['kode_tr'] = $data->id;
		$items['no_register'] = $data->no_dokumen;
		foreach (range(2, 6) as $n) {$items['no_register_'.$n] = str_pad($data->no_dokumen, $n,0,STR_PAD_LEFT);}
		$items['nip_hs'] = @$user_hs->nip;
		$items['nama_hs'] = @$user_hs->nama;
		$items['jabatan_hs'] = @$user_hs->jabatan;
		$items['jabatan_hs_en'] = @$user_hs->jabatan_en;
		
		$time_tr = strtotime($data->created_at);
		$items['date_tr_short'] = date('d-m-Y',$time_tr);
		$items['date_tr_long'] = tanggal_indo(date('Y-m-d',$time_tr));
		$items['date_tr_day'] = tanggal_indo(date('Y-m-d',$time_tr),TRUE);
		$items['day_tr'] = hari_indo(date('N',$time_tr));

        $jml  = intval($data->jml_berkas);
		$biaya = intval($data->biaya);
		$total = $jml * $biaya;
		$terbilang = empty($total)?'BEBAS BIAYA':penyebut($total);
		$items['no_dokumen'] = $data->no_doc;
		$items['biaya_doc'] = $biaya;
		$items['jumlah_doc'] = $jml;
		$items['total_doc'] = numb($total);
		$items['total_doc_terbilang'] = strtoupper($terbilang);

		$pelapor = $this->identitas->findOrFail(['id'=>$data->pelapor]);
		foreach ($pelapor as $key => $value) {
			if(in_array($key,['negeri','provinsi','daerah','kota','distrik','kecamatan'])){
				$items[$key.'_pelapor'] = strtoupper($value);
			}elseif(in_array($key,['tgl_lahir'])){
				$items[$key.'_pelapor'] = tanggal_indo($value);
			}else{
				$items[$key.'_pelapor'] = strtoupper($value);
			}
		}

		$umur = umur_th_bl($pelapor->tgl_lahir);
		$items['umur_pelapor'] = $umur->y.' Tahun '.(empty($umur->m)?'':$umur->m.' Bulan');

		// $items['umur_pelapor'] = intval(date('Y')) - intval(date('Y',strtotime($pelapor->tgl_lahir)));
		if(!empty($data->qrcode)){
			if(file_exists(FCPATH.$data->qrcode)){
				$items['qr_code'] = base_url($data->qrcode);
			}
		}
		$items = phpword_auto_items($items);
		$nama_file = 'KWITANSI-'.url_title(strtoupper($data->kode_layanan.'-'.$data->id));
		$file_template = FCPATH.$data->template_kwitansi;
		if(!is_file($file_template)){show_404(); exit();}
		else{
			Settings::setTempDir(FCPATH.'assets/template/tmp_file');
			Settings::setOutputEscapingEnabled(true);
			$word = new PhpWord;
			$word->getCompatibility()->setOoxmlVersion(15);
			$tem = $word->loadTemplate($file_template);

			$var_count = $tem->getVariableCount();

			phpword_autoval($tem,$items);
			// $tem->setValue('nama', $data->nama);
			foreach ($data_item as $item) {
				$value = $item->field_value;
				$field_name = $item->field_name;
				switch ($item->field_type) {
					case 'file': 
							$url = base_url($value);
							if(iki_gambar($value)){
								$tem->setImageValue($field_name,$url);
							}else{
								$tem->setValue($field_name, $url);
							}
						break;
					case 'list' :
							$data = []; 
							if(!empty($value)){
								$loop = intval(@$var_count[$field_name]);
								if($loop>0){
									foreach (explode(';;',$value) as $v) {
										$data[]=[$field_name => strtoupper($v)];
									}
									for($i=0; $i<$loop;$i++){
										$tem->cloneBlock($field_name.'_block',0,true,false,$data);
									}
								}
							}
							$tem->cloneBlock($field_name.'_block',0,true,false,$data);
						break;
					case 'date' : $tem->setValue($field_name, strtoupper( (empty($item->field_data)?tanggal_indo($value):convert_tgl_indo(date($item->field_data,strtotime($value)))) ) );break;
					default : $tem->setValue($field_name,strtoupper($value)); break;
				}
			}
			$path = $tem->save();
			// print_r([$tem,'path'=>$path]); exit();
			// $arr_fl = explode('.',$data->template_file);
			// $nama_file.='.'.end($arr_fl);
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header('Content-Type: application/octet-stream');
			// header("Content-Disposition: attachment; filename=helloWorld.docx");
			header("Content-Disposition: $disposition; filename=$nama_file.docx");
			// header("Content-Type: application/docx");
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			ob_clean();
			flush();
			readfile($path);
			unlink($path);
			exit();
		}
	}

    // --------------- test file ----------------------- //

	function test_file_kwitansi($ref,$name){
		$disposition = 'attachment';
        // print_r($ref);exit('test');
		$data = $this->ms_pl->findOrFail($ref);
		$data_item = $this->pl_field->get(['pelayanan_id'=>$data->pelayanan_id,'field_type !='=>'separator']);
		
		$items = [];
		$items['kode_tr'] = 'TRL1234556789012345678901234';
		$items['no_register'] = rand(1,9);
		foreach (range(2, 6) as $n) {$items['no_register_'.$n] = str_pad($items['no_register'], $n,0,STR_PAD_LEFT);}
		$items['nip_hs'] = 1234567890;
		$items['nama_hs'] = 'Pejabat Penanda Tangan';
		$items['jabatan_hs'] = 'Jabatan HS';
		$items['jabatan_hs_en'] = 'Jabatan HS ENGLISH';
		
		$items['date_tr_short'] = date('d-m-Y');
		$items['date_tr_long'] = tanggal_indo(date('Y-m-d'));
		$items['date_tr_day'] = tanggal_indo(date('Y-m-d'),TRUE);
		$items['day_tr'] = hari_indo(date('N'));

        $jml  = rand(1,10);
		$biaya = rand(0,9) * 100;
		$total = $jml * $biaya;
		$terbilang = empty($total)?'BEBAS BIAYA':penyebut($total);
		$items['biaya_doc'] = $biaya;
		$items['jumlah_doc'] = $jml;
		$items['total_doc'] = numb($total);
		$items['total_doc_terbilang'] = strtoupper($terbilang);

		foreach ($this->identitas->get_fields() as $field) {
			if($field->name=='tgl_lahir'){
				$items[$field->name.'_pelapor'] = tanggal_indo(date('Y-m-d'));
			}else{
				$items[$field->name.'_pelapor'] = "~".$field->name."_pelapor~";
			}
		}
		$umur = umur_th_bl(rand(1990,intval(date('Y'))).'-'.date('m-d', strtotime('- '.rand(1,20).' month')));
		$items['umur_pelapor'] = $umur->y.' Tahun '.(empty($umur->m)?'':$umur->m.' Bulan');
		
		$items = phpword_auto_items($items);
		$nama_file = 'KWITANSI-'.url_title(strtoupper($data->kode_layanan.'_temp'));
		$file_template = FCPATH.$data->template_kwitansi;
		if(!is_file($file_template)){show_404(); exit();}
		else{
			Settings::setTempDir(FCPATH.'assets/template/tmp_file');
			$word = new PhpWord;
			$word->getCompatibility()->setOoxmlVersion(15);
			$tem = $word->loadTemplate($file_template);

			$var_count = $tem->getVariableCount();

			phpword_autoval($tem,$items);
			foreach ($data_item as $item) {
				$field_name = $item->field_name;
				switch ($item->field_type) {
					case 'number' : $tem->setValue($field_name,rand(1,9));break;
					case 'list' :
							$data = []; 
							$loop = intval(@$var_count[$field_name]);
							if($loop>0){
								foreach (range(1,rand(2,5)) as $v) {
									$data[]=[$field_name => "Contoh List $v"];
								}
								for($i=0; $i<$loop;$i++){
									$tem->cloneBlock($field_name.'_block',0,true,false,$data);
								}
							}
						break;
					case 'date' : $value = date('Y-m-d'); $tem->setValue($field_name,(empty($item->data)?tanggal_indo($value):convert_tgl_indo(date($item->data,strtotime($value)))));break;
					case 'db_identitas' : 
							foreach (explode('||',$item->data) as $vx) {
								$fl_name = $vx.'_'.$item->field_name;
								$tem->setValue($fl_name,"~$fl_name~");
							}
						break;
					default : $tem->setValue($field_name,'~'.$item->label.'~'); break;
				}
			}
			$path = $tem->save();
			// $arr_fl = explode('.',$data->template_file);
			// $nama_file.='.'.end($arr_fl);
			header("Content-Disposition: $disposition; filename=$nama_file.docx");
			readfile($path);
			unlink($path);
		}
	}
	// ------------------ file bukti ----------------------------
	function file_bukti($ref,$name,$type='download'){
		$disposition = ($type=='read'?'inline':'attachment');

		$this->db->select('master_pelayanan.template_bukti, master_pelayanan.pelayanan, master_pelayanan.kode_layanan, tr_pelayanan.*, getNoDokumen(tr_pelayanan.id) as no_doc');
		$this->db->join('master_pelayanan','master_pelayanan.pelayanan_id=tr_pelayanan.pelayanan_id','inner');
		$data = $this->tr_pl->findOrFail($ref);
		$data_item = $this->tr_pl_item->get(['tr_pelayanan_id'=>"$data->id"]);

		$user_hs = $this->user_m->first($data->hs);
		$items = [];
		$tgl_req = @$_REQUEST['tgl'];
		$items['set_tgl'] = empty($tgl_req)?date('d F Y H:i'):$tgl_req;
		$items['kode_tr'] = $data->id;
		$items['no_register'] = $data->no_dokumen;
		foreach (range(2, 6) as $n) {$items['no_register_'.$n] = str_pad($data->no_dokumen, $n,0,STR_PAD_LEFT);}
		$items['nip_hs'] = @$user_hs->nip;
		$items['nama_hs'] = @$user_hs->nama;
		$items['jabatan_hs'] = @$user_hs->jabatan;
		$items['jabatan_hs_en'] = @$user_hs->jabatan_en;
		
		$time_tr = strtotime($data->created_at);
		$items['date_tr_short'] = date('d-m-Y',$time_tr);
		$items['date_tr_long'] = tanggal_indo(date('Y-m-d',$time_tr));
		$items['date_tr_day'] = tanggal_indo(date('Y-m-d',$time_tr),TRUE);
		$items['day_tr'] = hari_indo(date('N',$time_tr));

        $jml  = intval($data->jml_berkas);
		$biaya = intval($data->biaya);
		$total = $jml * $biaya;
		$terbilang = empty($total)?'BEBAS BIAYA':penyebut($total);
		$items['no_dokumen'] = $data->no_doc;
		$items['biaya_doc'] = $biaya;
		$items['jumlah_doc'] = $jml;
		$items['total_doc'] = numb($total);
		$items['total_doc_terbilang'] = strtoupper($terbilang);

		$pelapor = $this->identitas->findOrFail(['id'=>$data->pelapor]);
		foreach ($pelapor as $key => $value) {
			if(in_array($key,['negeri','provinsi','daerah','kota','distrik','kecamatan'])){
				$items[$key.'_pelapor'] = strtoupper($value);
			}elseif(in_array($key,['tgl_lahir'])){
				$items[$key.'_pelapor'] = tanggal_indo($value);
			}else{
				$items[$key.'_pelapor'] = strtoupper($value);
			}
		}

		$umur = umur_th_bl($pelapor->tgl_lahir);
		$items['umur_pelapor'] = $umur->y.' Tahun '.(empty($umur->m)?'':$umur->m.' Bulan');
		// $items['umur_pelapor'] = intval(date('Y')) - intval(date('Y',strtotime($pelapor->tgl_lahir)));
		if(!empty($data->qrcode)){
			if(file_exists(FCPATH.$data->qrcode)){
				$items['qr_code'] = base_url($data->qrcode);
			}
		}
		$items = phpword_auto_items($items);
		// print_r($items); exit();
		$nama_file = 'BUKTI-PENGAMBILAN-'.url_title(strtoupper($data->kode_layanan.'-'.$data->id));
		$file_template = FCPATH.$data->template_bukti;
		if(!is_file($file_template)){show_404(); exit();}
		else{
			Settings::setTempDir(FCPATH.'assets/template/tmp_file');
			Settings::setOutputEscapingEnabled(true);
			$word = new PhpWord;
			$word->getCompatibility()->setOoxmlVersion(15);
			$tem = $word->loadTemplate($file_template);

			$var_count = $tem->getVariableCount();

			phpword_autoval($tem,$items);
			// $tem->setValue('nama', $data->nama);
			foreach ($data_item as $item) {
				$value = $item->field_value;
				$field_name = $item->field_name;
				switch ($item->field_type) {
					case 'file': 
							$url = base_url($value);
							if(iki_gambar($value)){
								$tem->setImageValue($field_name,$url);
							}else{
								$tem->setValue($field_name, $url);
							}
						break;
					case 'list' :
							$data = []; 
							if(!empty($value)){
								$loop = intval(@$var_count[$field_name]);
								if($loop>0){
									foreach (explode(';;',$value) as $v) {
										$data[]=[$field_name => strtoupper($v)];
									}
									for($i=0; $i<$loop;$i++){
										$tem->cloneBlock($field_name.'_block',0,true,false,$data);
									}
								}
							}
							$tem->cloneBlock($field_name.'_block',0,true,false,$data);
						break;
					case 'date' : $tem->setValue($field_name, strtoupper( (empty($item->field_data)?tanggal_indo($value):convert_tgl_indo(date($item->field_data,strtotime($value)))) ) );break;
					default : $tem->setValue($field_name,strtoupper($value)); break;
				}
			}
			$path = $tem->save();
			// print_r([$tem,'path'=>$path]); exit();
			// $arr_fl = explode('.',$data->template_file);
			// $nama_file.='.'.end($arr_fl);
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header('Content-Type: application/octet-stream');
			// header("Content-Disposition: attachment; filename=helloWorld.docx");
			header("Content-Disposition: $disposition; filename=$nama_file.docx");
			// header("Content-Type: application/docx");
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			ob_clean();
			flush();
			readfile($path);
			unlink($path);
			exit();
		}
	}

    // --------------- test file ----------------------- //

	function test_file_bukti($ref,$name){
		$disposition = 'attachment';
        // print_r($ref);exit('test');
		$data = $this->ms_pl->findOrFail($ref);
		$data_item = $this->pl_field->get(['pelayanan_id'=>$data->pelayanan_id,'field_type !='=>'separator']);
		
		$items = [];
		$items['set_tgl'] = date('d F Y');
		$items['kode_tr'] = 'TRL1234556789012345678901234';
		$items['no_register'] = rand(1,9);
		foreach (range(2, 6) as $n) {$items['no_register_'.$n] = str_pad($items['no_register'], $n,0,STR_PAD_LEFT);}
		$items['nip_hs'] = 1234567890;
		$items['nama_hs'] = 'Pejabat Penanda Tangan';
		$items['jabatan_hs'] = 'Jabatan HS';
		$items['jabatan_hs_en'] = 'Jabatan HS ENGLISH';
		
		$items['date_tr_short'] = date('d-m-Y');
		$items['date_tr_long'] = tanggal_indo(date('Y-m-d'));
		$items['date_tr_day'] = tanggal_indo(date('Y-m-d'),TRUE);
		$items['day_tr'] = hari_indo(date('N'));

        $jml  = rand(1,10);
		$biaya = rand(0,9) * 100;
		$total = $jml * $biaya;
		$terbilang = empty($total)?'BEBAS BIAYA':penyebut($total);
		$items['biaya_doc'] = $biaya;
		$items['jumlah_doc'] = $jml;
		$items['total_doc'] = numb($total);
		$items['total_doc_terbilang'] = strtoupper($terbilang);

		foreach ($this->identitas->get_fields() as $field) {
			if($field->name=='tgl_lahir'){
				$items[$field->name.'_pelapor'] = tanggal_indo(date('Y-m-d'));
			}else{
				$items[$field->name.'_pelapor'] = "~".$field->name."_pelapor~";
			}
		}
		$umur = umur_th_bl(rand(1990,intval(date('Y'))).'-'.date('m-d', strtotime('- '.rand(1,20).' month')));
		$items['umur_pelapor'] = $umur->y.' Tahun '.(empty($umur->m)?'':$umur->m.' Bulan');
		
		$items = phpword_auto_items($items);
		$nama_file = 'BUKTI-PENGAMBILAN-'.url_title(strtoupper($data->kode_layanan.'_temp'));
		$file_template = FCPATH.$data->template_bukti;
		if(!is_file($file_template)){show_404(); exit();}
		else{
			Settings::setTempDir(FCPATH.'assets/template/tmp_file');
			$word = new PhpWord;
			$word->getCompatibility()->setOoxmlVersion(15);
			$tem = $word->loadTemplate($file_template);

			$var_count = $tem->getVariableCount();

			phpword_autoval($tem,$items);
			foreach ($data_item as $item) {
				$field_name = $item->field_name;
				switch ($item->field_type) {
					case 'number' : $tem->setValue($field_name,rand(1,9));break;
					case 'list' :
							$data = []; 
							$loop = intval(@$var_count[$field_name]);
							if($loop>0){
								foreach (range(1,rand(2,5)) as $v) {
									$data[]=[$field_name => "Contoh List $v"];
								}
								for($i=0; $i<$loop;$i++){
									$tem->cloneBlock($field_name.'_block',0,true,false,$data);
								}
							}
						break;
					case 'date' : $value = date('Y-m-d'); $tem->setValue($field_name,(empty($item->data)?tanggal_indo($value):convert_tgl_indo(date($item->data,strtotime($value)))));break;
					case 'db_identitas' : 
							foreach (explode('||',$item->data) as $vx) {
								$fl_name = $vx.'_'.$item->field_name;
								$tem->setValue($fl_name,"~$fl_name~");
							}
						break;
					default : $tem->setValue($field_name,'~'.$item->label.'~'); break;
				}
			}
			$path = $tem->save();
			// $arr_fl = explode('.',$data->template_file);
			// $nama_file.='.'.end($arr_fl);
			header("Content-Disposition: $disposition; filename=$nama_file.docx");
			readfile($path);
			unlink($path);
		}
	}
	private function getNoDokumen($kode_layanan){
		$q = "SELECT MAX(no_dokumen) as no FROM tr_pelayanan WHERE pelayanan_id IN (SELECT ms.pelayanan_id FROM master_pelayanan ms WHERE ms.kode_layanan='$kode_layanan')";
		$x = intval(@$this->db->query($q)->row()->no);
		$no = ($x+1);
		return $no;
	}
}
