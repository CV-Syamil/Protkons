<style>
	.bootstrap-duallistbox-container option:hover{background:#9e9e9e; color: white;}
	.bootstrap-duallistbox-container option{cursor: pointer;}
</style>
<?=form_open('master/users/simpan-data') ?>
	<input type="hidden" name="ref" value="<?=@$data->user_id?>">
	<?php if(can_access('su')){ ?>
		<div class="form-group">
			<label>Fungsi <span class="text-red">*</span></label>
			<select name="fungsi" class="form-control" required>
				<?php foreach($fungsi as $f){ $slc = ($f->id==@$data->fungsi)?'selected':''; echo "<option value=\"$f->id\" $slc>$f->nama</option>"; } ?>
			</select>
		</div>
	<?php } ?>
	<div class="form-group">
		<label>Username <span class="text-red">*</span></label>
		<input type="text" value="<?=@$data->username?>" minlength="3" name="user" class="form-control" placeholder="Username" required>
	</div>
	<div class="form-group">
		<label>NIP<span class="text-red">*</span></label>
		<input type="text" value="<?=@$data->nip?>" name="nip" class="form-control" placeholder="NIP User" required>
		<span class="small">* isi dengan tanda strip (-) jika kosong.</span>
	</div>
	<div class="form-group">
		<label>Nama User<span class="text-red">*</span></label>
		<input type="text" value="<?=@$data->nama?>" minlength="3" name="nama" class="form-control" placeholder="Nama User" required>
	</div>
	<div class="form-group">
		<label>Report Code<span class="text-red">*</span></label>
		<input type="text" value="<?=@$data->kode_report?>" maxlength="30" name="kode_report" class="form-control" placeholder="Report Code" required>
		<span class="small">* isi dengan tanda strip (-) jika kosong.</span>
	</div>
	<div class="form-group">
		<label>Jabatan<span class="text-red">*</span></label>
		<input type="text" value="<?=@$data->jabatan?>" name="jabatan" class="form-control" placeholder="Jabatan" required>
		<span class="small">* isi dengan tanda strip (-) jika kosong.</span>
	</div>
	<div class="form-group">
		<label>Jabatan (English)<span class="text-red">*</span></label>
		<input type="text" value="<?=@$data->jabatan_en?>" name="jabatan_en" class="form-control" placeholder="Jabatan English" required>
		<span class="small">* isi dengan tanda strip (-) jika kosong.</span>
	</div>
	<div class="form-group">
		<label>Akses <span class="text-red">*</span></label>
		<select class="form-control" name="akses" required>
			<?php foreach (user_akses() as $key => $value) { echo '<option '.(@$data->akses==$key?'selected':'').' value="'.$key.'">'.$value.'</option>';} ?>
		</select>
	</div>
	<div class="form-group">
		<label>Status</label>
		<select class="form-control" name="status">
			<?php foreach (['Tidak Aktif','Aktif'] as $key => $value) { echo '<option '.(intval(@$data->aktif)==$key?'selected':'').' value="'.$key.'">'.$value.'</option>';} ?>
		</select>
	</div>
	<div class="form-group">
		<label>Akses Layanan</label>
		<select class="form-control" onchange="cek_slc_akses()" name="slc_akses" id="slc_akses">
			<option value="all" <?=((in_array('all',(empty($data->akses_pelayanan)?[]:$data->akses_pelayanan)))?'selected':'')?>>Semua Pelayanan</option>
			<option value="slc" <?=((!in_array('all',(empty($data->akses_pelayanan)?[]:$data->akses_pelayanan)))?'selected':'')?>>Pilih Pelayanan</option>
		</select>
		<div id="div_list_layanan">
			<p></p>
			<select name="akses_layanan[]" id="list_layanan" multiple>
				<?php foreach ($layanan as $v) { echo '<option value="'.$v->pelayanan_id.'" '.((in_array($v->pelayanan_id,(empty($data->akses_pelayanan)?[]:$data->akses_pelayanan)))?'selected':'').'>'.$v->pelayanan.' ('.$v->kode_layanan.')</option>'; } ?>
			</select>
		</div>
	</div>
	<div class="form-group" align="right">
		<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
		<button type="submit" class="btn btn-success">Simpan Data</button>
	</div>
</form>
<script>
	$('#list_layanan').bootstrapDualListbox({
        nonSelectedListLabel: 'Daftar Layanan',
        selectedListLabel: 'Daftar Layanan Terpilih',
		filterPlaceHolder: 'Cari Pelayanan',
		moveAllLabel: 's',
		selectorMinimalHeight: 200,
		infoText: false,
	});
	function cek_slc_akses(){
		var val = $('#slc_akses').val();
		if(val=='all'||val.length<=0){$('#div_list_layanan').hide();}
		else{$('#div_list_layanan').show('fade');}
	}
	cek_slc_akses();
</script>