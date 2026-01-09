<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?=base_url('style/lte')?>/plugins/select2/js/select2.full.min.js"></script>
<style type="text/css">
	/*.select2-container .select2-selection--single{height: auto !important;}
	.select2-container--default .select2-selection--single .select2-selection__arrow{top: 7px !important; right: 5px !important;}
	.select2-container--default .select2-selection--single .select2-selection__rendered{padding-top: 4px;}*/
</style>

<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">
			<a href="<?=back_link(site_url('pelayanan'))?>"><i class="fa fa-arrow-left"></i></a>&nbsp;
			<?=$pl->pelayanan?>
		</h3>
		<div class="card-tools">
			<?php 
			$boolModalSelesai=FALSE;
			if(can_access('loket')){
				if(in_array(intval($data->status),[0,91])){ ?>
					<a href="<?=site_url('pelayanan/kirim-data/'.$data->id.'/pelayanan')?>" onclick="return confirm('Kirim Pengajuan ?')" class="btn btn-success btn-sm"><i class="fa fa-send"></i> Kirim Data</a>
				<?php }
				if(in_array(intval($data->status),[1,2,3])){ ?>
					<a href="<?=site_url('pelayanan/bukti-pengambilan/'.$data->id.'/pelayanan')?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Surat Pengambilan</a>
				<?php } 
				if(in_array(intval($data->status),[3,4,5])&&empty($data->file_berkas)){ $boolModalSelesai=TRUE; ?>
					<button type="button" data-toggle="modal" data-target="#modal_selesai" class="btn bg-teal btn-sm"><i class="fa fa-upload"></i> Upload Berkas <?=(intval($data->biaya)>0)?'':'&amp; Selesai Pelayanan'?></a>
					<?php } ?>
			<?php } if(can_access('verifikasi')){ 
				if(intval($data->biaya)==0&&in_array(intval($data->status),[3,4])&&empty($data->file_berkas)&&empty($data->petugas_loket)&&$data->petugas_verifikasi==getSession('ref')){ $boolModalSelesai=TRUE; ?>
					<button type="button" data-toggle="modal" data-target="#modal_selesai" class="btn bg-teal btn-sm"><i class="fa fa-upload"></i> Upload Berkas &amp; Selesai Pelayanan</a>
				<?php } if(in_array(intval($data->status),[1,92])){
					if(empty($data->petugas_loket)&&$data->petugas_verifikasi==getSession('ref')){?>
						<button type="button" onclick="show_modal(false)" class="btn btn-danger btn-sm">Kembalikan ke Loket <i class="fa fa-thumbs-down"></i></button>
					<?php } ?>
					<buttom type="button" onclick="show_modal(true);" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up"></i> Kirim Data</buttom>
				<?php } if(in_array(intval($data->status),[1,2,3])&&empty($data->petugas_loket)&&$data->petugas_verifikasi==getSession('ref')){ ?>
					<a href="<?=site_url('pelayanan/bukti-pengambilan/'.$data->id.'/pelayanan')?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Surat Pengambilan</a>
				<?php } ?>
			<?php } if($sendVerif=(in_array(intval($data->status),[2])&&can_access('hs')/*&&getSession('ref')==$data->hs*/)){ ?>
				<button type="button" onclick="show_modal(false)" class="btn btn-danger btn-sm">Kembali ke Verifikasi <i class="fa fa-thumbs-down"></i></button>
				<buttom type="button" onclick="show_modal(true);" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up"></i> Verifikasi Data</buttom>
			<?php } if(in_array(intval($data->status),[3,4,5])&&can_access('kasir')){ ?>
				<a href="<?=site_url('pelayanan/kwitansi/'.$data->id.'/pelayanan')?>" target="_blank" class="btn bg-indigo btn-sm"><i class="fa fa-check"></i> Kwitansi</a>
			<?php //} if(!empty($data->file_berkas)){ ?>
				<!-- <a href="<?php//base_url($data->file_berkas)?>" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-file"></i> Berkas File</a> -->
			<?php //} if(!empty($data->file_berkas_kasir)){ ?>
				<!-- <a href="<?php//base_url($data->file_berkas_kasir)?>" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-file"></i> Berkas File (Kasir)</a> -->
			<?php } if(intval($data->status)>2&&can_access('verifikasi')&&empty($data->file_berkas)&&$data->biaya<=0){ $boolModalSelesai=TRUE; ?>
				<button type="button" data-toggle="modal" data-target="#modal_selesai" class="btn bg-teal btn-sm"><i class="fa fa-upload"></i> Upload Berkas Pelayanan</a>
			<?php } if(intval($data->status)>2&&can_access('kasir')&&empty($data->file_berkas_kasir)&&$data->biaya>0){ $boolModalSelesai=TRUE; ?>
				<button type="button" data-toggle="modal" data-target="#modal_selesai" class="btn bg-teal btn-sm"><i class="fa fa-upload"></i> Upload Berkas Pelayanan</a>
			<?php }
			if(can_access('hs')&&in_array(intval($data->status),[3,4,5])&&!empty(getSession('tte_nik'))&&empty($data->file_esign)){ ?>
				<buttom type="button" onclick="show_modal(true);" class="btn bg-indigo btn-sm"><i class="fa fa-key"></i> Re-Send E-Sign</buttom>
			<?php } ?>
			
		</div>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover" style="text-transform: uppercase;">
				<tr>
					<td width="200">Kode Pelayanan</td>
					<td width="5">:</td>
					<td><?=$data->id?></td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td>:</td>
					<td><?=tanggal_indo($data->created_at)?></td>
				</tr>
				<tr>
					<td colspan="3">Data Pelapor</td>
				</tr>
				<tr style="background: white;">
					<td colspan="3" style="padding: 0!important;">
						<div style="margin-left: 30px; padding-bottom: 15px; border-left: thin solid #eee;">
							<?=$view_pelapor?>
						</div>
					</td>
				</tr>
				<?php foreach($fields as $field){ if($field->field_type=='db_identitas'){ ?>
					<tr>
						<td colspan="3"><?=$field->label?></td>
					</tr>
					<tr style="background: white;">
						<td colspan="3" style="padding: 0!important;">
							<div style="margin-left: 30px; padding-bottom: 15px; border-left: thin solid #eee;">
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<?php foreach (explode('||',$field->data) as $fl) {
											$fieldname = $fl.'_'.$field->field_name;
											$val = @$data_item[$fieldname]; ?>
											<tr>
												<td width="150"><?=identitas_field($fl)?></td>
												<td width="5">:</td>
												<td ><?=$val?></td>
											</tr>
										<?php } ?>
									</table>
								</div>
							</div>
						</td>
					</tr>
				<?php }elseif($field->field_type=='db_wilayah_id'){?>
					<tr>
						<td colspan="3"><?=$field->label?></td>
					</tr>
					<tr style="background: white;">
						<td colspan="3" style="padding: 0!important;">
							<div style="margin-left: 30px; padding-bottom: 15px; border-left: thin solid #eee;">
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<?php 
										$cek_idn=[];
										if($field->data=='provinsi'){$cek_idn=['provinsi'];}
										elseif($field->data=='kota'){$cek_idn=['provinsi','kota'];}
										elseif($field->data=='kecamatan'){$cek_idn=['provinsi','kota','kecamatan'];}

										foreach (['provinsi'=>'Provinsi','kota'=>'Kota / Kabupaten','kecamatan'=>'Kecamatan'] as $fl=>$label) {
											if(in_array($fl,$cek_idn)){
												$fieldname = $fl.'_'.$field->field_name;
												$val = @$data_item[$fieldname]; ?>
												<tr>
													<td width="150"><?=$label?></td>
													<td width="5">:</td>
													<td ><?=$val?></td>
												</tr>
										<?php }  } ?>
									</table>
								</div>
							</div>
						</td>
					</tr>
				<?php }elseif($field->field_type=='db_wilayah_my'){?>
					<tr>
						<td colspan="3"><?=$field->label?></td>
					</tr>
					<tr style="background: white;">
						<td colspan="3" style="padding: 0!important;">
							<div style="margin-left: 30px; padding-bottom: 15px; border-left: thin solid #eee;">
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<?php 
										$cek_mys = [];
										if($field->data=='negeri'){$cek_mys=['negeri'];}
										elseif($field->data=='daerah'){$cek_mys=['negeri','daerah'];}
										elseif($field->data=='distrik'){$cek_mys=['negeri','daerah','distrik'];}

										foreach (['negeri'=>'Negeri','daerah'=>'Daerah','distrik'=>'Distrik'] as $fl=>$label) {
											if(in_array($fl,$cek_mys)){
												$fieldname = $fl.'_'.$field->field_name;
												$val = @$data_item[$fieldname]; ?>
												<tr>
													<td width="150"><?=$label?></td>
													<td width="5">:</td>
													<td ><?=$val?></td>
												</tr>
										<?php } } ?>
									</table>
								</div>
							</div>
						</td>
					</tr>
				<?php }else{ ?>
					<tr>
						<td><?=$field->label?></td>
						<td>:</td>
						<td><?php
							$val = @$data_item[$field->field_name];
							if(!empty($val)&&$field->field_type=='list'){
								$ec = ""; foreach(explode(';;',$val) as $v){$ec.="<li>$v</li>";}
								echo "<ul style=\"padding-left:15px;\">$ec</ul>";
							}else{
								echo $val;
							}
						?></td>
					</tr>
				<?php }  } ?>
				<tr>
					<td>Jumlah Dokumen</td>
					<td>:</td>
					<td><?=numb($data->jml_berkas)?></td>
				</tr>
				<tr>
					<td>Status</td>
					<td>:</td>
					<td><?=status_layanan($data->status,TRUE).(empty($data->keterangan)?'':"<p class=\"text-red\">* $data->keterangan</p>")?></td>
				</tr>
				<?php if(!empty($data->file_esign)){ ?>
					<tr>
						<td>File E-SIGN</td>
						<td>:</td>
						<td><a href="<?=base_url('assets/'.$data->file_esign)?>" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer"> <i class="fa fa-file"></i> FILE</a></td>
					</tr>
				<?php } ?>
				<?php foreach([
					'nama_petugas_loket' => 'Dibuat Oleh',
					'nama_petugas_verifikasi' => 'Petugas Verifikasi',
					'nama_hs' => 'Pejabat Penanda Tangan',
					'nama_kasir' => 'Petugas Kasir'
				] as $key => $label){ $nama = @$data->$key; if(!empty($nama)){  ?>
					<tr>
						<td><?=$label?></td>
						<td>:</td>
						<td><?=$nama?></td>
					</tr>
				<?php } } if(!empty($verif_edit)){echo '<tr class="bg-warning">
						<td>Edited By</td>
						<td>:</td>
						<td>'.$verif_edit.'</td>
					</tr>';} if(!empty($data->qrcode) && in_array($data->status, [3,4,5])){ ?>
					<tr>
						<td colspan="3" align="center">
							<img src="<?=base_url($data->qrcode)?>" style="max-width: 300px;" class="img-responsive">
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>
<?php if($data->status>3){ ?>
<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">Arsip File</h3>
		<div class="card-tools">
			<button type="button" class="btn btn-tool text-primary" data-toggle="collapse" data-target="#form_upload_div"><i class="fa fa-plus"></i> Tambah File</button>
		</div>
	</div>
	<div class="card-body collapse" id="form_upload_div">
		<?=form_open_multipart('pelayanan/upload-file-pelayanan',[],['ref'=>$data->id,'kode_pl'=>$pl->kode_layanan])?>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama File</label>
						<input type="text" name="nm_file" class="form-control" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>File</label>
						<input type="file" name="file" accept=".pdf,.docx,.doc" class="form-control" required>
					</div>
				</div>
			</div>
			<div class="form-group text-right">
				<button type="reset" data-toggle="collapse" data-target="#form_upload_div" class="btn btn-secondary">Batal</button>
				<button type="submit" class="btn btn-primary">Upload File</button>
			</div>
		<?=form_close()?>
	</div>
	<div class="card-body p-0">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<td width="50">No</td>
					<td>File</td>
					<td>User</td>
				</tr>
			</thead>
			<tbody>
				<?php $no=0; if(!empty($data->file_berkas)){ $no++; ?><tr><td align="center"><?=$no?></td><td><a href="<?=base_url($data->file_berkas)?>" target="_blank">Dokumen (Verifikator)</a></td><td><?=$data->nama_petugas_verifikasi?></td></tr> <?php } ?>
				<?php if(!empty($data->file_berkas_kasir)){ $no++; ?><tr><td align="center"><?=$no?></td><td><a href="<?=base_url($data->file_berkas_kasir)?>" target="_blank">Dokumen (Kasir)</a></td><td><?=$data->nama_kasir?></td></tr> <?php } ?>

				<?php foreach($arsip_file as $v){ $no++; ?>
					<tr>
						<td align="center"><?=$no?></td>
						<td>
							<?=form_open('pelayanan/delete_file_pelayanan',['id'=>'form_x'.$no],[
								'ref' => $v->tr_pelayanan_id,
								'nm' => $v->nama_file,
								'file' => $v->file
							]).form_close()?>
							<a href="#" onclick="hapus_file_pelayanan(this);return false;"><i class="fa fa-trash text-danger"></i></a>&nbsp;&nbsp;
							<a href="<?=base_url($v->file)?>" target="_blank"><?=$v->nama_file?></a>
						</td>
						<td><?=$v->nama_user?></td></tr> 
					<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php } if(can_access('admin')){ ?>
	<div class="card card-solid">
		<div class="card-header with-border">
			<h3 class="card-title">History Scan</h3>
		</div>
		<div class="card-body">
			<table class="table table-striped table-bordered table-hover" id="tbl_scan_log">
				<thead>
					<tr>
						<td>Tanggal</td>
						<td>Agent</td>
						<td>Platform</td>
						<td>IP Address</td>
						<td>Viewing</td>
					</tr>
				</thead>
			</table>
		</div>
	</div>
<?php } ?>
<?php 
$params = implode('/', [$data->id,'pelayanan']);
if(can_access(['verifikasi','hs'])){?>
	<div class="modal fade" id="modal_verifikasi">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title"></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span></button>
	      </div>
	      <div class="modal-body">
	      	<?php if(can_access('verifikasi')){ ?>
		      	<div id="form_verifikasi">
			      	<?=form_open('pelayanan/verifikasi-data/'.$params,'',['status'=>2])?>
				      	<div class="form-group">
				      		<label>Pejabat Penanda Tangan</label>
				      		<select class="form-control select2" style="width: 100%;" name="pejabat" required id="pj_hs">
				      			<option value="">-- Pilih Pejabat Penanda Tangan --</option>
				      		</select>
				      	</div>
				      	<div class="form-group" align="right">
				      		<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				      		<button type="submit" class="btn btn-success">Kirim Data</button>
				      	</div>
				    <?=form_close(); ?>
		      	</div>
		      	<div id="form_tolak">
			      	<?=form_open('pelayanan/tolak-verifikasi/'.$params,'',['status'=>91])?>
				      	<div class="form-group">
				      		<label>Keterangan Penolakan</label>
				      		<textarea name="msg" class="form-control" minlength="5" placeholder="Keterangan Penolakan. min 5 karakter." rows="5" required><?=$data->keterangan?></textarea>
				      	</div>
				      	<div class="form-group" align="right">
				      		<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				      		<button type="submit" class="btn btn-danger">Tolak Verifikasi</button>
				      	</div>
				    <?=form_close(); ?>
		      	</div>
		    <?php }elseif(can_access('hs')){ ?>
		      	<div id="form_verifikasi">
			      	<?php //form_open('pelayanan/verifikasi-data/'.$params,'',['status'=>3])?>
				      	<p>Kami mematuhi seluruh ketentuan perundangundangan, standar, praktik terbaik mengenai perolehan, pemrosesan, dan penggunaan Data Pribadi, serta hak-hak dari Subyek Data, sebagai berikut:<br>
				      	<ul>
				      		<li>UU No. 11 tahun 2018 tentang Informasi dan Transaksi Elektronik, sebagaimana diubah dengan UU No. 19 tahun 2016</li>
				      		<li>Peraturan Pemerintah No. 82 tahun 2012 tentang Penyelenggaraan Sistem dan Transaksi Elektronik</li>
				      		<li>Peraturan Menteri Kominfo No. 20 tahun 2016 tentang Perlindungan Data Pribadi dalam Sistem Elektronik</li>
				      		<li>Peraturan Menteri Kominfo No. 11 tahun 2018 tentang Penyelenggaraan Sertifikasi Elektronik</li>
				      	</ul>
				      	<div class="form-group" align="right">
				      		<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				      		<button type="button" class="btn btn-success" onclick="hsSetuju()">Ya, Saya Setuju</button>
				      	</div>
				    <?php //form_close(); ?>
					<script>
						var sendtte=<?=(empty(getSession('tte_nik'))?'false':'true')?>;
						var sendverif = <?=(empty($sendVerif)?'false':'true')?>;
						function hsSetuju(){
							if(sendtte){ sendVerif(); }else{
								Swal.fire({
									title: 'NIK Kosong. Lanjut Verifikasi Pelayanan ?',
									text: "NIK Kosong. Data Pelayanan tidak dapat kirim ke Aplikasi E-Sign.",
									icon: 'warning',
									showCancelButton: true,
									confirmButtonText: 'Tetap Verifikasi',
								}).then((r)=>{
									if(r.isConfirmed){
										sendVerif();
									}
								});
							}
						}
						async function sendVerif(){
							blockUI('.modal');
							if(sendverif){
								var tr = await new Promise(sendTR);
								if(tr.status!=200){
									toastr.error(tr.message,'Update Pelayanan ERRORS');
									blockUI('.modal',false);
									return;
								}
								await new Promise((r,rj)=>setTimeout(() => { r(''); }, 1000));
							}
							if(sendtte){
								var tte = await new Promise(sendTTE);
								if(tte.status!=200){
									toastr.error(tte.message,'Request E-SIGN ERROR');
								}else{
									toastr.success('Data Pelayanan berhasil dikirim ke Server E-SIGN ','E-SIGN SUCCESS');
								}

								await new Promise((r,rj)=>setTimeout(() => { r(''); }, 3000));
							}
							blockUI('.modal',false);
							window.location.reload();
						}
						function sendTTE(res,rej){
							$.ajax({
								url: '<?=site_url('pelayanan/file/'.$params.'/pdf/tte')?>', type:'GET', dataType: 'JSON',
								beforeSend:()=>$('.modal').block({message:'Mengirim data ke server E-SIGN...'}),
								error:(e)=>{ res({status:(e.status==200)?202:e.status,message:e.statusText}); },
								success:(r)=>{res(r);}
							});
						}
						function sendTR(res,rej){
							$.ajax({
								url: '<?=site_url('pelayanan/verifikasi-data/'.$params)?>', type:'POST', data:{status:3}, dataType: 'JSON',
								beforeSend:()=>$('.modal').block({message:'Updating Pelayanan...'}),
								error:(e)=>{ res({status:(e.status==200)?202:e.status,message:e.statusText}); },
								success:(r)=>{res(r);}
							});
						}
					</script>
		      	</div>
		      	<div id="form_tolak">
			      	<?=form_open('pelayanan/tolak-verifikasi/'.$params,'',['status'=>92])?>
				      	<div class="form-group">
				      		<label>Keterangan Penolakan</label>
				      		<textarea name="msg" class="form-control" minlength="5" placeholder="Keterangan Penolakan. min 5 karakter." rows="5" required><?=$data->keterangan?></textarea>
				      	</div>
				      	<div class="form-group" align="right">
				      		<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				      		<button type="submit" class="btn btn-danger">Tolak Verifikasi</button>
				      	</div>
				    <?=form_close(); ?>
		      	</div>
		    <?php } ?>
	      </div>
	    </div>
	    <!-- /.modal-content -->
	  </div>
	  <!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<script type="text/javascript">
		function show_modal(verif){
			if(verif){
				$('#modal_verifikasi .modal-title').html('Verifikasi Data');
				$('#modal_verifikasi #form_verifikasi').show();
				$('#modal_verifikasi #form_tolak').hide();
				$('#modal_verifikasi').modal('show');
			}else{
				$('#modal_verifikasi .modal-title').html('Kembalikan Data');
				$('#modal_verifikasi #form_verifikasi').hide();
				$('#modal_verifikasi #form_tolak').show();
				$('#modal_verifikasi').modal('show');
			}
		}
		$(document).ready(()=>{
			$('#pj_hs').select2({
				theme: 'bootstrap4',
				ajax:{
					delay: 300,
					url: '<?=site_url('api/petugas/hs')?>',
				}
			});
		});
	</script>
<?php } if($boolModalSelesai){ //if(can_access(['kasir','verifikasi','loket'])&&intval($data->status)>2){ ?>
	<div class="modal fade" id="modal_selesai">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Upload Berkas</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<?=form_open_multipart('pelayanan/selesai',NULL,['ref'=>$data->id]); ?>
						<div class="form-group">
							<input type="file" accept=".pdf,.docx,.doc" name="berkas" required>
						</div>
						<div class="form-group" align="right">
							<button class="btn btn-success btn-block">Upload Berkas <?=((can_access(['kasir','verifikasi'])||intval($data->biaya)==0)?'&amp; Selesai Pelayanan':'')?></button>
						</div>
					<?=form_close(); ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<script type="text/javascript">
	$('#tbl_scan_log').DataTable({
		processing: true,
		serverSide: true,
		ajax: {url: '<?=site_url('pelayanan/scan-log')?>', type: 'POST', data:{ref:'<?=$data->id?>'}},
		columns: [
			{data: 'tanggal',},
			{data: 'agent',},
			{data: 'platform'},
			{data: 'ip_addr'},
			{data: 'viewer', searchable: false, className: 'text-right'},
		]
	});
	function hapus_file_pelayanan(el){
		var form = $(el).parents('td').find('form')[0];
		Swal.fire({
			title: 'Hapus File',
			text: "File akan terhapus secara permanen.",
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Hapus',
			showLoaderOnConfirm: true,
			preConfirm: ()=>{
				return new Promise(function(resolve, reject) {
					form.submit();
			});
			},allowOutsideClick:false
		});
	}
	function ambil_doc(href){
		Swal.fire({
			title: 'Verifikasi Pengambilan',
			text: "Pengambilan Dokumen & Pembayaran Telah Selesai",
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Telah diambil',
			showLoaderOnConfirm: true,
			preConfirm: ()=>{
				return new Promise(function(resolve, reject) {
					window.location = href;
			});
			},allowOutsideClick:false
		});
	};
</script>