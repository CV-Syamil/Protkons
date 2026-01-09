<?=form_open('master/pelayanan/simpan-item'); ?>
	<input type="hidden" name="pelayanan" value="<?=@$pelayanan_id?>">
	<input type="hidden" name="ref" value="<?=@$data->id?>">
	<div class="form-group">
		<label>Field Type <span class="text-red">*</span></label>
		<select class="form-control" id="field_type_form" name="field_type" required>
			<?php foreach($options_type as $key => $label){ echo '<option value="'.$key.'" '.(($key==@$data->field_type)?'selected':'').'>'.$label.'</option>'; }?>
		</select>
		<div id="field_options" <?=(in_array(@$data->field_type, ['select','checkcard'])?'':'style="display:none;"') ?>>
			<div align="right" style="margin-top: 5px;" id="btn_add_option">
				<button  type="button" class="btn btn-default btn-sm" onclick="add_item_options()"><i class="fa fa-plus"></i> Tambah Option</button>
			</div>
			<table class="table table-striped table-hover">
				<tbody id="options_tbody">
					<?php $options=[];if(!empty($data->data)){$options = explode('||',$data->data);} if(@$data->field_type=='select'){foreach($options as $v){ ?>
						<tr>
							<td align="center" valign="middle" width="20"><a href="javascript:void(0)" onclick="$(this).parents('tr').remove();" class="text-danger"><i class="fa fa-times"></i></a></td>
							<td><input type="text" min="1" name="datas[]" value="<?=$v?>" placeholder="Options" class="form-control" required></td>
						</tr>
					<?php }} ?>
				</tbody>
			</table>
			<hr>
		</div>
	</div>
	<div class="form-group form_field_x">
		<label>Field ID <span class="text-red">*</span></label>
		<input type="text" minlength="3" name="field_name" id="field_name_item" pattern="[a-zA-Z0-9_]{2,}" class="form-control" value="<?=@$data->field_name?>">
	</div>
	<div class="form-group form_field_x">
		<label>Label <span class="text-red">*</span></label>
		<input type="text" minlength="3" name="label" id="field_label_item" class="form-control" value="<?=@$data->label?>">
	</div>
	<div class="form-group form_field_x">
		<label>Notes</label>
		<textarea class="form-control" name="notes" id="field_note_item" placeholder="Keterangan Tambahan"><?=@$data->notes?></textarea>
	</div>
	<div class="form-group form_field_x">
		<div class="icheck-success d-inline">
			<input type="checkbox"  id="ckbx_required" <?=(empty(@$data->required)?'':'checked') ?> name="required">
			<label for="ckbx_required"> Wajib Isi</label>
		</div>
	</div>
	<div class="form-group" align="right">
		<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
		<button type="submit" class="btn btn-success">Simpan Data</button>
	</div>
</form>
<script type="text/javascript">
	function add_item_options(){
		$('#options_tbody').append('<tr> <td align="center" valign="middle" width="20"><a href="javascript:void(0)" onclick="$(this).parents(\'tr\').remove();" class="text-danger"><i class="fa fa-times"></i></a></td> <td><input type="text" min="1" name="datas[]" value="" placeholder="Options" class="form-control" required></td></tr>');
	}
	$('#field_type_form').on('change',function(){
		$('.form_field_x').show();
		if(this.value=='select'){
			$('#field_options').show();
			$('#btn_add_option').show();
			$('#options_tbody').html('');
		}else if(this.value=='db_identitas'){
			$('#field_options').show();
			$('#btn_add_option').hide();
			$('#options_tbody').html('<?php foreach(identitas_field() as $key => $label){ $ch = (in_array($key,$options)?'checked':'');  echo '<tr><td width="10" align="center"><div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success"> <input type="checkbox" name="datas[]" '.$ch.' class="custom-control-input" value="'.$key.'" id="sw_'.$key.'"> <label class="custom-control-label" for="sw_'.$key.'">&nbsp;</label></div></td><td>'.$label.'</td></tr>';} ?>');
		}else if(this.value=='date'){
			$('#field_options').show();
			$('#btn_add_option').hide();
			$('#options_tbody').html('<tr><td>Format Print</td><td><select id="slc_opt" class="form-control" name="datas[]"><option value="d F Y">ex: <?=date('d F Y')?></option><option value="d m Y">ex: <?=date('d m Y')?></option><option value="d-m-Y">ex: <?=date('d-m-Y')?></option><option value="l, d F Y">ex: <?=date('l, d F Y')?></option></select></td></tr>');
			$('#slc_opt').val('<?=@$data->data?>');
		}else if(this.value=='separator'){
			$('#field_options').hide();
			$('#btn_add_option').hide();
			$('#options_tbody').html('');
			$('.form_field_x').hide();
		}else if(this.value=='db_wilayah_id'){
			$('#field_options').show();
			$('#btn_add_option').hide();
			$('#options_tbody').html('<tr><td>Option</td><td><select id="slc_opt" class="form-control" name="datas[]"><option value="provinsi">Hanya Provinsi</option><option value="kota">Provinsi -> Kota / Kabupaten</option><option value="kecamatan">Provinsi -> Kota / Kabupaten -> Kecamatan</option></select></td></tr>');
			$('#slc_opt').val('<?=@$data->data?>');
		}else if(this.value=='db_wilayah_my'){
			$('#field_options').show();
			$('#btn_add_option').hide();
			$('#options_tbody').html('<tr><td>Option</td><td><select id="slc_opt" class="form-control" name="datas[]"><option value="negeri">Hanya Negeri</option><option value="daerah">Negeri -> Daerah</option><option value="distrik">Negeri -> Daerah -> Distrik</option></select></td></tr>');
			$('#slc_opt').val('<?=@$data->data?>');
		}else{
			$('#field_options').hide();
			$('#options_tbody input').each((i,el)=>{if(el.value.trim().length<=0){$(el).parents('tr').remove();}});
		}
	});
	$('#field_name_item').on('keypress', function (event) {
	    var regex = new RegExp("^[a-zA-Z0-9_]+$");
	    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
	    if (!regex.test(key)) {
	       event.preventDefault();
	       return false;
	    }
	});
	$('#field_name_item').on('input', function() {
	  $(this).val($(this).val().replace(/[^a-zA-Z0-9_]/gi, ''));
	});
	<?php if(in_array(@$data->field_type,['db_identitas','date','separator','db_wilayah_id','db_wilayah_my'])){ echo "$('#field_type_form').trigger('change');";} ?>
</script>