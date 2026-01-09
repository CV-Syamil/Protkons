<form action="javascript:void(0)" id="form_identitas">
	<input type="hidden" name="ref" value="<?=@$data->no_identitas?>">
	<div class="form-group">
		<label>No Identitas</label>
		<input type="text" minlength="3" name="no_identitas" class="form-control" value="<?=@$data->no_identitas?>" required>
	</div>
	<div class="form-group">
		<label>Nama Lengkap</label>
		<input type="text" minlength="3" name="nama" class="form-control" value="<?=@$data->nama?>" required>
	</div>
	<div class="form-group">
		<label>Jenis Kelamin</label>
		<select class="form-control" name="jk">
			<option <?=(@$data->jk=='Laki-Laki'?'selected':'') ?> value="Laki-Laki">Laki-Laki</option>
			<option <?=(@$data->jk=='Perempuan'?'selected':'') ?> value="Perempuan">Perempuan</option>
		</select>
	</div>
	<div class="form-group">
		<label>Alamat</label>
		<select class="form-control" name="negara" id="form_identitas_negara" required>
			<option value="">-- Pilih Negara --</option>
			<option <?=(@$data->negara=='Indonesia'?'selected':'') ?> value="Indonesia">Indonesia</option>
			<option <?=(@$data->negara=='Malaysia'?'selected':'') ?> value="Malaysia">Malaysia</option>
		</select><br>
		<select class="form-control select2" name="negeri_provinsi" id="form_identitas_negeri_provinsi" oninvalid="toast('warning','Harap Pilih Negeri  / Provinsi Terlebih Dahulu')" required>
			<option value="">-- Pilih Negeri / Provinsi --</option>
		</select><br>
		<select class="form-control select2" name="daerah_kota" id="form_identitas_daerah_kota" oninvalid="toast('warning','Harap Pilih Daerah / Kota / Kabupaten Terlebih Dahulu')" required>
			<option value="">-- Pilih Daerah / Kota / Kabupaten --</option>
		</select><br>
		<select class="form-control select2" name="distrik_kecamatan" id="form_identitas_distrik_kecamatan">
			<option value="">-- Pilih Distrik / Kecamatan --</option>
		</select><br>
		<textarea class="form-control" name="alamat_lengkap" rows="5" placeholder="Alamat Lengkap" required><?=@$data->alamat_lengkap?></textarea>
	</div>
	<div class="form-group" align="right">
		<?php if(!empty($data->no_identitas)){ ?>
			<button type="button" data-dismiss="modal" onclick="pilih_identitas('<?=$data->no_identitas;?>')" class="btn btn-primary">Pilih Data</button>
		<?php } ?>
		<button type="submit" class="btn btn-success">Simpan Data</button>
	</div>
</form>
<script type="text/javascript">
	$('#form_identitas').on('submit',function(){
		var data = $(this).serializeArray();
		$.ajax({
			url: '<?=site_url('data-identitas/simpan-data/pl')?>',
			type: 'POST', dataType: 'JSON', data: data,
			beforeSend: ()=>{
				blockUI('#modal_identitas .modal-dialog');
			}, error: (e)=>{
				blockUI('#modal_identitas .modal-dialog',false);
				toast('error',e.status+': '+e.statusText);
			}, success: (r)=>{
				blockUI('#modal_identitas .modal-dialog',false);
				if(r.status==200){
					toast('success',r.message);
					$('#modal_form_identitas').modal('hide');
					pilih_identitas(r.href);
				}else{
					toast('error',r.message);
				}
			}
		});
	});
	$('#form_identitas_negeri_provinsi').jCombo("<?=site_url('api/negeri-provinsi/')?>",{
		parent: "#form_identitas_negara",
		selected_value: '<?=@$data->negeri_provinsi?>'
	});
	$('#form_identitas_daerah_kota').jCombo("<?=site_url('api/daerah-kota/')?>",{
		parent: "#form_identitas_negeri_provinsi",
		selected_value: '<?=@$data->daerah_kota;?>'
	});
	$('#form_identitas_distrik_kecamatan').jCombo("<?=site_url('api/distrik_kecamatan/')?>",{
		parent: "#form_identitas_daerah_kota",
		selected_value: '<?=@$data->distrik_kecamatan;?>'
	});
	<?php if(!empty($data)){ echo "$('#form_identitas_negara').trigger('change');"; } ?>
	$('.select2').select2({theme: 'bootstrap4'});
</script>