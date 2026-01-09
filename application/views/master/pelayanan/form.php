<link rel="stylesheet" type="text/css" href="<?=base_url('style/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<style type="text/css">#field_x tr{cursor: move;}</style>
<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title"><a href="<?=site_url('master/pelayanan')?>" id="btn_back"><i class="fa fa-arrow-left"></i></a> Form Pelayanan</h3>
		<div class="card-tools">
			<button type="button" data-toggle="modal" data-target="#modal_kode" class="btn btn-tool bg-purple"><i class="fa fa-file-code"></i> Kode Report</button>
		</div>
	</div>
	<div class="card-body">
		<?=form_open_multipart('master/pelayanan/simpan-data') ?>
			<input type="hidden" name="ref" value="<?=@$data->pelayanan_id?>">
			<?php if(can_access('su')){ ?>
				<div class="form-group">
					<label>Fungsi <span class="text-red">*</span></label>
					<select name="slc_fungsi" class="form-control" required>
						<?php foreach($fungsi as $fs){ $slc = ($fs->id==$data->fungsi)?'selected':''; echo "<option value=\"$fs->id\" $slc>$fs->nama</option>"; } ?>
					</select>
				</div>
			<?php } ?>
			<div class="form-group">
				<label>Kode Pelayanan <span class="text-red">*</span></label>
				<input type="text" value="<?=$data->kode_layanan?>" name="kode_layanan" class="form-control" placeholder="Kode Pelayanan" required>
			</div>
			<div class="form-group">
				<label>Nama Pelayanan <span class="text-red">*</span></label>
				<input type="text" minlength="3" value="<?=$data->pelayanan?>" name="nama" class="form-control" placeholder="Nama Pelayanan" required>
			</div>
			<div class="row">
				<div class="col-6 col-md-8">
					<div class="form-group">
						<label>Biaya Pelayanan <span class="text-red">*</span></label>
						<input type="number" min="0" name="biaya" class="form-control" placeholder="Biaya Pelayanan"  value="<?=$data->biaya?>" required>
					</div>
				</div>
				<div class="col-6 col-md-4">
					<div class="form-group">
						<p class="">&nbsp;</p>
						<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-primary">
							<input style="cursor: pointer;" <?=($data->show_jml==1?'checked':'')?> type="checkbox" name="show_jml" class="custom-control-input" id="sw_show_jml">
							<label style="cursor: pointer;" class="custom-control-label" for="sw_show_jml"> Show Kolom Jumlah</label>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Template Dokumen</label>
						<input type="file" name="template" accept=".docx" class="form-control" placeholder="Template Dokumen Cetak">
						<?php if(!empty($data->template_file)){ ?>
							<div style="margin: 5px;">
								File Template: <a href="<?=base_url($data->template_file)?>" target="_blank"><i class="fa fa-file"></i> File</a>&nbsp;&nbsp;&nbsp;
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Template Kwitansi</label>
						<input type="file" name="file_kwi" accept=".docx" class="form-control" placeholder="Template Dokumen Cetak">
						<?php if(!empty($data->template_kwitansi)){ ?>
							<div style="margin: 5px;">
								File Template: <a href="<?=base_url($data->template_kwitansi)?>" target="_blank"><i class="fa fa-file"></i> File</a>&nbsp;&nbsp;&nbsp;
								<a href="<?=site_url('master/pelayanan/hapus_template_kwitansi/'.$data->pelayanan_id)?>" class="text-danger"><i class="fas fa-times"></i></a>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Template Bukti</label>
						<input type="file" name="file_bukti" accept=".docx" class="form-control" placeholder="Template Dokumen Cetak">
						<?php if(!empty($data->template_bukti)){ ?>
							<div style="margin: 5px;">
								File Template: <a href="<?=base_url($data->template_bukti)?>" target="_blank"><i class="fa fa-file"></i> File</a>&nbsp;&nbsp;&nbsp;
								<a href="<?=site_url('master/pelayanan/hapus_template_bukti/'.$data->pelayanan_id)?>" class="text-danger"><i class="fas fa-times"></i></a>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="row" style="margin-bottom: 5px;">
				<div class="col-sm-12 pb-5" align="right">
					<?php if(!empty($data->template_file)){ ?>
						<a target="_blank" rel="noopener noreferrer" href="<?=site_url('pelayanan/test-template/'.$data->pelayanan_id.'/'.$data->kode_layanan)?>" class="btn btn-sm bg-teal mb-1"><i class="fa fa-file-alt"></i> Test Template</a>
						<?php } 
					if(!empty($data->template_kwitansi)){ ?>
						<a target="_blank" rel="noopener noreferrer" href="<?=site_url('pelayanan/test-template-kwitansi/'.$data->pelayanan_id.'/'.$data->kode_layanan)?>" class="btn btn-sm bg-pink mb-1"><i class="fa fa-file-alt"></i> Test Template Kwitansi</a>
						<?php } 
					if(!empty($data->template_bukti)){ ?>
						<a target="_blank" rel="noopener noreferrer" href="<?=site_url('pelayanan/test-template-bukti/'.$data->pelayanan_id.'/'.$data->kode_layanan)?>" class="btn btn-sm bg-primary mb-1"><i class="fa fa-file-alt"></i> Test Template Bukti</a>
						<?php } ?>
						<a target="_blank" rel="noopener noreferrer" href="<?=site_url('master/pelayanan/test-form/'.$data->pelayanan_id.'/'.$data->kode_layanan)?>" class="btn btn-sm btn-info mb-1"><i class="fa fa-eye"></i> View Form</a>
				</div>
				<div class="col-sm-6"><label>Kolom Isian</label></div>
				<div class="col-sm-6" align="right">
					<button type="button" data-ref="<?=site_url('master/pelayanan/add-item/'.$data->pelayanan_id)?>" data-title="Form Tambah Kolom Isian" class="btn_add btn btn-block btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th width="100">Hapus</th>
							<th>ID Kolom</th>
							<th>Label</th>
							<th>Tipe Kolom</th>
							<th>Attribute</th>
							<th width="100">Wajib Isi</th>
							<th>Notes</th>
						</tr>
					</thead>
					<tbody id="field_x">
						<?php foreach($items as $item){ 
							$params = implode('/', [$item->id,url_title($item->field_name),url_title($item->label)]); ?>
							<tr>
								<td>
									<input type="hidden" name="sort_field[]" value="<?=$item->id?>">
									<label class="fas fa-sort" style="cursor:move;"></label>&nbsp;&nbsp;
									<button type="button" data-ref="<?=site_url('master/pelayanan/edit-item/'.$params)?>" class="btn btn-warning btn-xs btn_edit" data-title="Form Ubah Kolom Isian"><i class="fa fa-edit"></i></button>
									<?php //if($item->field_name!='nama'){?>
									<button type="button" onclick="hapus_item(this)" data-href="<?=site_url('master/pelayanan/hapus-item/'.$params)?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
									<?php //} ?>
								</td>
								<?php if($item->field_type=='separator'){
									echo '<td colspan="6" align="center">-- '.$item->field_name.' --</td>';
								}else{ ?>
									<td>
										<?php if($item->field_type=='db_identitas'){  foreach (explode('||', $item->data) as $flx){ ?>
											<button type="button" class="btn btn-default btn-copy mb-1" data-clipboard-text="${<?=$flx.'_'.$item->field_name?>}">${<?=$flx.'_'.$item->field_name?>}</button>
										<?php } }elseif($item->field_type=='db_wilayah_id'){
											if(in_array($item->data,['kecamatan','kota','provinsi'])){ ?>
												<button type="button" class="btn btn-default btn-copy" data-clipboard-text="${<?=$flx='provinsi_'.$item->field_name?>}">${<?=$flx?>}</button>
											<?php } if(in_array($item->data,['kecamatan','kota'])){ ?>
												<button type="button" class="btn btn-default btn-copy" data-clipboard-text="${<?=$flx='kota_'.$item->field_name?>}">${<?=$flx?>}</button>
											<?php } if(in_array($item->data,['kecamatan'])){ ?>
												<button type="button" class="btn btn-default btn-copy" data-clipboard-text="${<?=$flx='kecamatan_'.$item->field_name?>}">${<?=$flx?>}</button>
											<?php } ?>
										<?php }elseif($item->field_type=='db_wilayah_my'){ 
											if(in_array($item->data,['negeri','daerah','distrik'])){ ?>
												<button type="button" class="btn btn-default btn-copy" data-clipboard-text="${<?=$flx='negeri_'.$item->field_name?>}">${<?=$flx?>}</button>
											<?php } if(in_array($item->data,['daerah','distrik'])){ ?>	
												<button type="button" class="btn btn-default btn-copy" data-clipboard-text="${<?=$flx='daerah_'.$item->field_name?>}">${<?=$flx?>}</button>
											<?php } if(in_array($item->data,['distrik'])){ ?>	
												<button type="button" class="btn btn-default btn-copy" data-clipboard-text="${<?=$flx='distrik_'.$item->field_name?>}">${<?=$flx?>}</button>
											<?php } ?>	
										<?php }elseif($item->field_type=='list'){ ?>
											<button type="button" class="btn btn-default btn-copy" 
											data-clipboard-text="
											${<?=$item->field_name?>_block}
											${<?=$item->field_name?>}
											${/<?=$item->field_name?>_block}">
												${<?=$item->field_name?>_block}</br>
												${<?=$item->field_name?>}</br>
												${/<?=$item->field_name?>_block}
											</button>
										<?php }else{ ?>
											<button type="button" class="btn btn-default btn-copy" data-clipboard-text="${<?=$item->field_name?>}">${<?=$item->field_name?>}</button>
										<?php } ?>
									</td>
									<td><?=$item->label?></td>
									<td align="center"><?=@$options_type[$item->field_type]?></td>
									<td>
										<?php
											$s='<center>-</center>';
											if(!empty($item->data)){
												switch ($item->field_type) {
													case 'select':
															$s = str_replace('||',', ', $item->data);
														break;
													case 'date':
															$s = 'Format Print: '.date($item->data);
														break;
												}
											}
											echo $s;
										?>
									</td>
									<td align="center"><?=($item->required==1)?'YA':'TIDAK'?></td>
									<td align="justify"><?=$item->notes?></td>
								<?php } ?>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="form-group" align="right">
				<button type="submit" class="btn btn-success">Simpan Data</button>
			</div>
		</form>
	</div>
</div>
<div class="modal fade" id="modal_form">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="modal_form_body"></div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="modal fade" id="modal_kode" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Daftar Report Code</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="fa fa-search"></i></span>
				</div>
				<input type="search" id="cari_kode_x" placeholder="Cari Kode" class="form-control">
			</div>
		</div>
      	<div class="table-responsive">
      		<table class="table table-bordered table-striped table-hover">
      			<thead>
      				<tr>
      					<th>Kode</th>
      					<th>Keterangan</th>
      				</tr>
      			</thead>
      			<tbody id="kode_x">
      				<?php foreach(phpword_auto_items() as $key => $item){ 
						switch ($key) {
							case 'kode_tr':echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><label>Kode Transaksi Pelayanan</label></td></tr>';break;
							case 'nip_hs':echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><label>Kode Pejabat Penandatangan / HS</label></td></tr>';break;
							case 'no_identitas_pelapor':echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><label>Kode Data Pelapor</label></td></tr>';break;
							case 'th_num_long':echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><label>Auto Kode</label></td></tr>';break;
							case 'biaya_doc':echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><label>Hanya Template Kwitansi</label></td></tr>';break;
							default:break;
						}   ?>
      					<tr>
      						<td>
								<button type="button" class="btn btn-default btn-copy" data-clipboard-text="${<?=$key?>}">${<?=$key?>}</button>
      						</td>
      						<td><?=$item['label']?></td>
      					</tr>
      				<?php } ?>
      			</tbody>
      		</table>
      	</div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
	function hapus_item(el){
		var href = $(el).data('href');
		Swal.fire({
      title: 'Hapus Kolom Isian',
      text: "Hapus Kolom isian secara permanen.",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Hapus',
      showLoaderOnConfirm: true,
      preConfirm: ()=>{
        return new Promise(function(resolve, reject) {
          window.location = href;
        });
      },allowOutsideClick:false
    }).then((result)=>{ });
	}
	$(document).ready(()=>{
		$('.btn_add, .btn_edit').on('click', function(){
			var ref = $(this).data('ref');
			var title = $(this).data('title');
			title = (title)?title:'Form';
			$('#modal_form .modal-title').html(title);
			$.ajax({
				url: ref,
				type: 'GET',
				beforeSend: ()=>{$('#modal_form').modal('show');},
				error:(e)=>{$('#modal_form').modal('hide');toast('error',e.status+': '+e.statusText);},
				success: (r)=>{
					$('#modal_form_body').html(r);
				}
			});
		});
		$('#field_x').sortable();
		var clipboard = new ClipboardJS('.btn-copy');
		clipboard.on('success', function(e) {
			toast('success',e.text,'Copied!!',1000);
		  e.clearSelection();
		});

		clipboard.on('error', function(e) {
				toast('error',e.text,'Copy Error!!',1000);
		});
		$("#cari_kode_x").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#kode_x tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	});
</script>