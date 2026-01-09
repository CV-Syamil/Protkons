<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?=base_url('style/lte')?>/plugins/select2/js/select2.full.min.js"></script>
<script src="<?=base_url('style/jcombo/jquery.jCombo.min.js')?>"></script>
<script src="<?=base_url('style/lte')?>/plugins/moment/moment.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="http://t00rk.github.io/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css">
<script src="http://t00rk.github.io/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
<style type="text/css">
	.form-control, .select2-selection__rendered, .select2-results__option{
		text-transform:uppercase;
	}
	/* .select2-container .select2-selection--single{height: 38px !important;} */
	/* .select2-container--default .select2-selection--single .select2-selection__arrow{top: 5px !important; right: 5px !important;} */
	.modal{overflow-y: auto !important;}
</style>
<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">
			<a href="<?=back_link(site_url('pelayanan'))?>"><i class="fa fa-arrow-left"></i></a>&nbsp;
			<?=$pl->pelayanan?>
		</h3>
	</div>
	<div class="card-body">
		<?php if(@$tipe_form=='view-form'){echo "<form action=\"#\" id=\"form_tr_pelayanan\" onsubmit=\"return false;\">";}else{echo form_open_multipart('pelayanan/simpan-data',['id'=>'form_tr_pelayanan'],['ref'=>@$data->id,'pl_ref'=>$pl->pelayanan_id,'pelapor'=>$pelapor->id]);}?>
		<div class="table-responsive">
			<table class="table table-striped table-hover" style="text-transform:uppercase;">
				<tr>
					<td colspan="3" class="h5">Data Pelapor</td>
				</tr>
				<tr>
					<td width="175">No. Identitas</td>
					<td width="5">:</td>
					<td><?=$pelapor->no_identitas?></td>
				</tr>
				<tr>
					<td>Nama Lengkap</td>
					<td>:</td>
					<td><?=$pelapor->nama?></td>
				</tr>
				<tr>
					<td>Jenis Kelamin</td>
					<td>:</td>
					<td><?=$pelapor->jk?></td>
				</tr>
				<tr>
					<td>Tempat, Tanggal Lahir</td>
					<td>:</td>
					<td><?=$pelapor->tempat_lahir.', '.tanggal_indo($pelapor->tgl_lahir)?></td>
				</tr>
				<tr>
					<td>Umur</td>
					<td>:</td>
					<td><?=get_umur($pelapor->tgl_lahir)?> th</td>
				</tr>
				<tr>
					<td>Agama</td>
					<td>:</td>
					<td><?=$pelapor->agama?></td>
				</tr>
				<tr>
					<td>Pekerjaan</td>
					<td>:</td>
					<td><?=$pelapor->pekerjaan?></td>
				</tr>
				<tr>
					<td>Kewarganegaraan</td>
					<td>:</td>
					<td><?=$pelapor->kewarganegaraan?></td>
				</tr>
				<tr>
					<td>Kewarganegaraan</td>
					<td>:</td>
					<td><?=$pelapor->kewarganegaraan?></td>
				</tr>
				<tr>
					<td>Provinsi</td>
					<td>:</td>
					<td><?=strtoupper($pelapor->provinsi)?></td>
				</tr>
				<tr>
					<td>Kota / Kabupaten</td>
					<td>:</td>
					<td><?=strtoupper($pelapor->kota)?></td>
				</tr>
				<tr>
					<td>Kecamatan</td>
					<td>:</td>
					<td><?=strtoupper($pelapor->kecamatan)?></td>
				</tr>
				<tr>
					<td>Alamat Lengkap</td>
					<td>:</td>
					<td><?=$pelapor->alamat_idn?></td>
				</tr>
				<tr><td colspan="3">&nbsp;</td></tr>
				<tr>
					<td>Negeri</td>
					<td>:</td>
					<td><?=strtoupper($pelapor->negeri)?></td>
				</tr>
				<tr>
					<td>Daerah</td>
					<td>:</td>
					<td><?=strtoupper($pelapor->daerah)?></td>
				</tr>
				<tr>
					<td>Distrik</td>
					<td>:</td>
					<td><?=strtoupper($pelapor->distrik)?></td>
				</tr>
				<tr>
					<td>Alamat Lengkap</td>
					<td>:</td>
					<td><?=$pelapor->alamat_mys?></td>
				</tr>
			</table>
		</div>
		<?php $modal_identitas=FALSE; $jcombo=[]; $date_input=[]; $time_input=[]; foreach($fields as $field){ 
			if($field->field_type=='db_identitas'){ $modal_identitas=TRUE; ?>
			<div class="table-responsive" id="<?=$field->field_name?>">
				<table class="mt-4" border="0" style="width:100%;" cellspacing="5">
					<tr style="border-top:thin solid #eee;">
						<td class="pt-4">
							<label><?=$field->label.(($required=($field->required==1))?' <span class="text-red">*</span>':'')?></label>
							<?php if(!empty($field->notes)){ echo "<div class=\"small mb-2\" style=\"margin-top:-10px;\">* $field->notes</div>"; } ?>
						</td>
						<td class="pt-4" align="right"><button type="button" onclick="cari_identitas('<?=$field->field_name?>')" class="btn btn-sm btn-info"><i class="fas fa-search"></i></button></td>
					</tr>
					<tr>
						<td colspan="2">
							<?php foreach(explode('||',$field->data) as $fl){ $fieldname = $fl.'_'.$field->field_name; $value = @$data_item[$fieldname]; ?>
									<div class="form-group">
										<label><?=identitas_field($fl)?></label>
										<input type="text" class="form-control" id="<?=$fieldname?>" placeholder="..." name="<?=$fieldname?>" <?=(($required)?'required':'')?> value="<?=$value?>" readonly>
									</div>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>
		<?php }elseif($field->field_type=='separator'){echo "<div class=\"mt-4 mb-4\"><hr></div>";}else{ ?>
			<div class="form-group">
				<label><?=$field->label.(($required=($field->required==1))?' <span class="text-red">*</span>':'')?></label>
				<?php
					$value = @$data_item[$field->field_name];
					$form_input="";
					switch ($field->field_type) {
						case 'text': case 'number': case 'file': case 'date':  case 'time':
								$form_input = '<input type="'.$field->field_type.'" placeholder="..." class="form-control" name="'.$field->field_name.'" value="'.(($field->field_type=='file')?'':$value).'" '.(($required)?'required':'').'>';
								if($field->field_type=='file'&&!empty($value)){
									$form_input = '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'.link_file($value).'</span></div>'.str_replace('required>', '>', $form_input).'</div>';
								}
								break;
						// case 'date':
						// 		$form_input = '<input type="hidden" name="'.$field->field_name.'" id="inp_'.$field->field_name.'_inp" '.(($required)?'required':'').'>';
						// 		$form_input.= '<input type="text" placeholder="..." class="form-control" id="inp_'.$field->field_name.'" '.(($required)?'required':'').'>';
						// 		$date_input[]=['name'=>'inp_'.$field->field_name,'value'=>(empty($value)?date('Y-m-d'):$value)];
						// 		break;
						// case 'time':
						// 		$form_input = '<input type="text" placeholder="..." class="form-control" name="'.$field->field_name.'"  id="inp_'.$field->field_name.'" '.(($required)?'required':'').'>';
						// 		$time_input[]=['name'=>'inp_'.$field->field_name,'value'=>(empty($value)?date('H:i'):$value)];
						// 		break;
						case 'list':
								if(!empty($data_item[$field->field_name])){
									$inp='';
									foreach (explode(';;',$data_item[$field->field_name]) as $v) {
										$inp.= '<div class="input-group mb-2"><div class="input-group-prepend"><button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-danger"><i class="fa fa-trash"></i></button></div><input type="text" placeholder="..." value="'.$v.'" class="form-control" name="'.$field->field_name.'[]" '.(($required)?'required':'').'></div>';
									}
								}else{
									$inp = '<div class="input-group mb-2"><div class="input-group-prepend"><button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-danger"><i class="fa fa-trash"></i></button></div><input type="text" placeholder="..." class="form-control" name="'.$field->field_name.'[]" '.(($required)?'required':'').'></div>';
								}
								$body_name = $field->field_name.'_body_list';
								$form_input = '<div id="'.$body_name.'">'.$inp.'</div><div><button type="button" onclick="add_list_item(\'#'.$body_name.'\', \''.$field->field_name.'\', \''.(($required)?'required':'').'\')" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Item</button></div>';
								break;
						case 'db_wilayah_id':
							if(in_array($field->data,['kecamatan','kota','provinsi'])){
								$jcombo[]= ['name' => 'provinsi_'.$field->field_name,'value'=>@$data_item['provinsi_'.$field->field_name],'parent'=>'','url'=>'api/provinsi-txt'];
								$form_input = '<div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">Provinsi</span></div><select class="form-control select2" name="'.($nm='provinsi_'.$field->field_name).'" id="'.$nm.'"'.(($required)?'required':'').'></select></div>';
							} if(in_array($field->data,['kecamatan','kota'])){
								$jcombo[]= ['name' => 'kota_'.$field->field_name,'value'=>@$data_item['kota_'.$field->field_name],'parent'=>'provinsi_'.$field->field_name,'url'=>'api/kota-txt?ref='];
								$form_input .= '<div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">Kota / Kabupaten</span></div><select class="form-control select2" name="'.($nm='kota_'.$field->field_name).'" id="'.$nm.'"'.(($required)?'required':'').'></select></div>';
							} if(in_array($field->data,['kecamatan'])){
								$jcombo[]= ['name' => 'kecamatan_'.$field->field_name,'value'=>@$data_item['kecamatan_'.$field->field_name],'parent'=>'kota_'.$field->field_name,'url'=>'api/kecamatan-txt?ref='];
								$form_input .= '<div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">Kecamatan</span></div><select class="form-control select2" name="'.($nm='kecamatan_'.$field->field_name).'" id="'.$nm.'"'.(($required)?'required':'').'></select></div>';
							}
							break;
						case 'db_wilayah_my':
							if(in_array($field->data,['negeri','daerah','distrik'])){ 
								$jcombo[]= ['name' => 'negeri_'.$field->field_name,'value'=>@$data_item['negeri_'.$field->field_name],'parent'=>'','url'=>'api/negeri-txt'];
								$form_input = '<div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">Negeri</span></div><select class="form-control select2" name="'.($nm='negeri_'.$field->field_name).'" id="'.$nm.'"'.(($required)?'required':'').'></select></div>';
							} if(in_array($field->data,['daerah','distrik'])){ 
								$jcombo[]= ['name' => 'daerah_'.$field->field_name,'value'=>@$data_item['daerah_'.$field->field_name],'parent'=>'negeri_'.$field->field_name,'url'=>'api/daerah-txt?ref='];
								$form_input .= '<div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">Daerah</span></div><select class="form-control select2" name="'.($nm='daerah_'.$field->field_name).'" id="'.$nm.'"'.(($required)?'required':'').'></select></div>';
							} if(in_array($field->data,['distrik'])){ 
								$jcombo[]= ['name' => 'distrik_'.$field->field_name,'value'=>@$data_item['distrik_'.$field->field_name],'parent'=>'daerah_'.$field->field_name,'url'=>'api/distrik-txt?ref='];
								$form_input .= '<div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text">Distrik</span></div><select class="form-control select2" name="'.($nm='distrik_'.$field->field_name).'" id="'.$nm.'"></select></div>';
							}
							break;
						case 'select':
								$form_input = '<select style="width:100%;" class="form-control select2" name="'.$field->field_name.'" '.(($required)?'required':'').'>';
								$form_input.='<option value="">-- Pilih Option --</option>';
								foreach (explode('||', $field->data) as $val) {
									$selected = ($value==$val)?'selected':'';
									$form_input.='<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
								}
								$form_input.= '</select>';
							break;
					}
					echo $form_input.(empty($field->notes)?'':'<span class="small">* '.$field->notes.'</span>');
				?>
			</div>
		<?php } 
			} ?>
		<?php if(intval($pl->show_jml)==1){ ?>
			<div class="form-group">
				<label>Jumlah Dokumen</label>
				<input type="number" min="1" name="jml_berkas" class="form-control" value="<?=(empty($data->jml_berkas)?1:$data->jml_berkas)?>" required>
			</div>
		<?php } ?>
		<div class="form-group" align="right">
			<?php if(in_array(intval(@$data->status),[0])){ ?>
				<button type="submit" name="btn" value="0" id="btn_submit_1" class="btn btn-info"><i class="fa fa-save"></i> Simpan Draft</button>
				<?php if(can_access(['loket','kasir'])){ ?>
					<button type="submit" name="btn" value="1" class="btn btn-success"><i class="fa fa-save"></i> Simpan & Kirim</button>
				<?php }elseif(can_access('verifikasi')){ ?>
					<input type="hidden" name="hs" id="petugas_hs">
					<button type="button" class="btn btn-success" onclick="show_modal_hs()"><i class="fa fa-save"></i> Simpan & Kirim</button>
				<?php } ?>
			<?php }else{ ?>
				<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Data</button>
			<?php } ?>
		</div>
		<?=form_close(); ?>
	</div>
</div>
<?php if($modal_identitas){ ?>
	<div class="modal fade" id="modal_pilih_identitas" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Data Identitas</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span></button>
	      </div>
	      <div class="modal-body" id="modal_form_body">
	      	<div class="mb-3 text-right">
		      	<button class="btn btn-primary btn-sm" data-href="<?=site_url('data-identitas/tambah-data/pl')?>" id="modal_add_identitas" type="button">Tambah Data</button>
	      	</div>
	      	<table class="table table-bordered table-striped table-hover" width="100%" id="datatable" style="text-transform:uppercase;">
	      		<thead>
	      			<tr>
	      				<td>No Identitas</td>
	      				<td>Nama</td>
	      				<td>Tempat Lahir</td>
	      				<td>Tanggal Lahir</td>
	      				<td>Riwayat Pelayanan</td>
	      				<td width="50">Actions</td>
	      			</tr>
	      		</thead>
	      	</table>
	      </div>
	      <div class="modal-footer" align="right">
	      	<button class="btn btn-default" data-dismiss="modal" type="button">Batal</button>
	      </div>
	    </div>
	    <!-- /.modal-content -->
	  </div>
	  <!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<div class="modal fade" id="modal_form_identitas" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Form Identitas</h5>
	        <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#modal_pilih_identitas" aria-label="Close">
	          <span aria-hidden="true">&times;</span></button>
	      </div>
	      <div class="modal-body" style="min-height: 200px;"></div>
	    </div>
	    <!-- /.modal-content -->
	  </div>
	  <!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<script type="text/javascript">
		var tipe_identitas;
		function cari_identitas(tipe){
			tipe_identitas = tipe;
			$('#modal_pilih_identitas').modal('show');
		}
		var ajax_form_identitas;
		$(document).on('click','#modal_add_identitas, .modal_edit_identitas',function(){
			var href = $(this).data('href');
			if(ajax_form_identitas){ajax_form_identitas.abort();}
			ajax_form_identitas = $.ajax({
				url: href, type: 'GET', data:{pelapor:'<?=$pelapor->id?>'}, beforeSend: ()=>{
					$('#modal_form_identitas .modal-body').html('');
					blockUI('#modal_form_identitas .modal-body');
					$('#modal_pilih_identitas').modal('hide');
					$('#modal_form_identitas').modal('show');
				},error: (e)=>{
					setTimeout(function() {
						blockUI('#modal_form_identitas .modal-body',false);
						$('#modal_form_identitas').modal('hide');
						$('#modal_pilih_identitas').modal('hide');
						toast('error',e.status+ ': '+e.statusText);
					}, 500);
				}, success: (r)=>{
					$('#modal_form_identitas .modal-body').html(r);
					blockUI('#modal_form_identitas .modal-body',false);
				}
			});
		});
		$(document).on('click','.btn-pilih-data',function(){
			var href = $(this).data('href');
			pilih_identitas(href);
		});
		var pilih_identitas = (href)=>{
			$('#modal_pilih_identitas').modal('hide');
			var tipe = tipe_identitas;
			$.ajax({
				url: href, type: 'GET',
				beforeSend: ()=>{ blockUI('#'+tipe);},
				error: (e)=>{toast('error',e.status+": "+e.statusText);blockUI('#'+tipe,false);},
				success: (r)=>{
					blockUI('#'+tipe,false);
					if(r.data){
						r.data.forEach((e,i)=>{
							$('#'+e[0]+'_'+tipe).val((e[1])?e[1]:'-');
						});
					}
				}
			});
		}
		var tbl_identitas;
		$(document).ready(()=>{
			tbl_identitas = $('#datatable').DataTable({
				processing: true,
		        serverSide: true,
				// deferLoading:0,
		        ajax: { url: '<?=site_url('data-identitas/table-pelayanan')?>', type: 'POST' },
		        columns: [
		        	{data: 'no_identitas',},
		        	{data: 'nama',},
		        	{data: 'tempat_lahir',},
		        	{data: 'tgl_lahir',},
		        	{data: 'history_pl', searchable: false, orderable: false,},
		        	{data: 'act', searchable: false, orderable: false, className: 'text-center'},
		        ]
			});
			$('#datatable_filter').html('<form id="form_filter" onsubmit="return false"><div class="input-group"> <div class="input-group-prepend"><span class="input-group-text">Search</span></div> <input type="search" class="form-control ml-0" required> <div class="input-group-append"><button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button></div> </div></form>');
			$('#form_filter').on('submit',function(){
				var inp = $(this).find('input');
				var s = inp.val().trim().trim();
				if(s){ tbl_identitas.search(s); tbl_identitas.ajax.reload(); }
				else{ inp.val(s); this.reportValidity(); }
			});
		});
	</script>
<?php } if(can_access('verifikasi')){ ?>
	<div class="modal fade" id="modal_pilih_hs" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Pilih Pejabat Penanda Tangan</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span></button>
	      </div>
	      <div class="modal-body">
			<div class="form-group">
				<label>Pejabat Penanda Tangan</label>
				<select class="form-control select2" style="width: 100%;" name="pejabat" required id="pj_hs_xx">
					<option value="">-- Pilih Pejabat Penanda Tangan --</option>
					<?php foreach($user_hs as $v){ echo "<option value=\"$v->user_id\">$v->nama</option>"; }?>
				</select>
			</div>
			<div class="form-group" align="right">
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				<button type="button" id="btn_kirim_ke_hs" class="btn btn-success">Kirim Data</button>
			</div>
		  </div>
	    </div>
	    <!-- /.modal-content -->
	  </div>
	  <!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<script type="text/javascript">
		function show_modal_hs(){
			var x = $('#form_tr_pelayanan')[0].reportValidity();
			if(x){
				$('#modal_pilih_hs').modal('show');
			}
		}
		$('#btn_kirim_ke_hs').on('click',()=>{
			var x = $('#pj_hs_xx')[0].reportValidity();
			if(x){
				$('#petugas_hs').val($('#pj_hs_xx').val())
				$('#btn_submit_1').val('2');
				$('#btn_submit_1')[0].click();
			}
		});
	</script>
<?php } ?>
<script type="text/javascript">
	function add_list_item(t,n,r){
		$(t).append('<div class="input-group mb-2"><div class="input-group-prepend"><button type="button" onclick="$(this).parent().parent().remove()"  class="btn btn-danger"><i class="fa fa-trash"></i></button></div><input type="text" placeholder="..." class="form-control" name="'+n+'[]" '+r+'></div>');	
	}
	function setDate(t,target){
		let day = ((t.date()>9)?'':'0')+t.date();
		let bl = ((t.month()<9)?'0':'')+(t.month()+1);
		let th = t.year();
		let tgl_str = day+'/'+bl+'/'+th;
		$(target).val(tgl_str);
		$(target+'_inp').val(t.format('YYYY-MM-DD'));
	}

	$(document).on('select2:open', () => {
		document.querySelector('.select2-search__field').focus();
	});
	
	$(document).ready(()=>{
		$('.select2').select2({
			theme: 'bootstrap4'
		});
		<?php foreach($jcombo as $dt){ ?>
			$('#<?=$dt['name']?>').jCombo("<?=site_url($dt['url'])?>",{
				<?php if(!empty($dt['parent'])){echo 'parent: "#'.$dt['parent'].'",';} ?>
				selected_value: '<?=@$dt['value']?>'
			});
		<?php } 
		foreach($date_input as $inp){ 
			echo "var temp_$inp[name] = moment('$inp[value]');var date_$inp[name] = (temp_$inp[name]._isValid)?temp_$inp[name]:moment();";
		?>
		$('#<?=$inp['name']?>').bootstrapMaterialDatePicker({
			format: 'DD/MM/YYYY',
			time: false,
			currentDate: date_<?=$inp['name']?>,
		}).on('change',(e,d)=>{$('#<?=$inp['name']?>_inp').val(d.format('YYYY-MM-DD'))});
		$('#<?=$inp['name']?>_inp').val(date_<?=$inp['name']?>.format('YYYY-MM-DD'));
		<?php } 
		foreach($time_input as $inp){ 
			echo "var temp_t$inp[name] = moment('$inp[value]','HH:mm');var time_$inp[name] = (temp_t$inp[name]._isValid)?temp_t$inp[name]:moment();";
		?>
		$('#<?=$inp['name']?>').bootstrapMaterialDatePicker({
			format: 'HH:mm',
			date: false,
			time: true,
			currentDate: time_<?=$inp['name']?>,
		});
		<?php } ?>
	});
</script>