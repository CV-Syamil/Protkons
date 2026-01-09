<style>
	.form-control, .select2-selection__rendered, .select2-results__option{
		text-transform:uppercase;
	}
</style>
<script src="<?=base_url('assets/countries.js')?>"></script>
<form action="javascript:void(0)" id="form_identitas">
	<input type="hidden" name="ref" value="<?=@$data->id?>">
	<div class="form-group">
		<label>Jenis Identitas *</label>
		<select name="jenis_identitas" class="form-control select2" id="jenis_identitas_slc" required></select>
	</div>
	<div class="form-group">
		<label>Kewarganegaraan</label>
		<select name="kewarganegaraan" id="kwn" class="form-control select2"></select>
	</div>
	<div class="form-group">
		<label>No Identitas</label>
		<input type="text" minlength="1" name="no_identitas" class="form-control" value="<?=@$data->no_identitas?>">
	</div>
	<div class="form-group">
		<label>Nama Lengkap *</label>
		<input type="text" minlength="3" name="nama" class="form-control" value="<?=@$data->nama?>" required>
	</div>
	<div class="form-group">
		<label>Jenis Kelamin</label>
		<select class="form-control" name="jk">
			<option <?=(@$data->jk=='Laki-Laki'?'selected':'') ?> value="Laki-Laki">Laki-Laki</option>
			<option <?=(@$data->jk=='Perempuan'?'selected':'') ?> value="Perempuan">Perempuan</option>
		</select>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label>Tempat Lahir</label>
				<input list="list_tmp_lahir" oninput="getListTmpLahir()" id="form_identitas_tmp_lahir" name="tempat_lahir" class="form-control" value="<?=@$data->tempat_lahir?>">
				<datalist id="list_tmp_lahir"></datalist>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label>Tanggal Lahir</label>
				<input type="date" name="tgl_lahir" value="<?=(empty($data->tgl_lahir)?date('Y-m-d'):date('Y-m-d',strtotime($data->tgl_lahir)))?>" class="form-control">
			</div>
		</div>
	</div>
	<div class="form-group">
		<label>Agama</label>
		<select class="form-control" name="agama">
			<?php foreach(agama() as $v){
				$slc = ($v==@$data->agama)?'selected':'';
				echo "<option value=\"$v\" $slc>$v</option>";
			}?>
		</select>
	</div>
	<div class="form-group">
		<label>Pekerjaan</label>
		<input type="text" name="pekerjaan" class="form-control" value="<?=@$data->pekerjaan?>">
	</div>
	<div class="form-group">
		<?php if(!empty($pelapor)){ ?>
			<button type="button" id="btn_no_p" style="float: right" data-type="indo" class="btn btn-xs btn-info">Sama dengan Pelapor</button>
		<?php } ?>
		<label>No. Telp</label>
		<input type="text" name="no_telp" id="no_telp_p" class="form-control" value="<?=@$data->no_telp?>">
	</div>
	<div class="form-group">
		<?php if(!empty($pelapor)){ ?>
			<button type="button" style="float: right" data-type="indo" class="btn btn-xs btn-info btn_sama_pelapor">Sama dengan Pelapor</button>
		<?php } ?>
		<label>Alamat Indonesia</label>
		<select class="form-control select2" name="provinsi" id="form_identitas_provinsi">
			<option value="">-- Pilih Provinsi --</option>
		</select><br>
		<select class="form-control select2" name="kota" id="form_identitas_kota">
			<option value="">-- Pilih Kota / Kabupaten --</option>
		</select><br>
		<select class="form-control select2" name="kecamatan" id="form_identitas_kecamatan">
			<option value="">-- Pilih Kecamatan --</option>
		</select><br>
		<select class="form-control select2" name="desa" id="form_identitas_desa">
			<option value="">-- Pilih Desa --</option>
		</select><br>
		<textarea class="form-control" name="alamat_idn" id="form_identitas_alamat_idn" rows="5" placeholder="Alamat Lengkap"><?=@$data->alamat_idn?></textarea>
	</div>
	<div class="form-group">
		<?php if(!empty($pelapor)){ ?>
			<button type="button" style="float: right" data-type="malay" class="btn btn-xs btn-info btn_sama_pelapor">Sama dengan Pelapor</button>
		<?php } ?>
		<label>Alamat Malaysia</label>
		<select class="form-control select2" name="negeri" id="form_identitas_negeri">
			<option value="">-- Pilih Negeri --</option>
		</select><br>
		<select class="form-control select2" name="daerah" id="form_identitas_daerah">
			<option value="">-- Pilih Daerah --</option>
		</select><br>
		<select class="form-control select2" name="distrik" id="form_identitas_distrik">
			<option value="">-- Pilih Distrik --</option>
		</select><br>
		<textarea class="form-control" name="alamat_mys" id="form_identitas_alamat_mys" rows="5" placeholder="Alamat Lengkap"><?=@$data->alamat_mys?></textarea>
	</div>
	<div class="form-group" align="right">
		<button type="submit" class="btn btn-success">Simpan Data</button>
	</div>
</form>
<script type="text/javascript">
	var pelapor = <?=json_encode(empty($pelapor)?[]:$pelapor);?>;
	$('#form_identitas').on('submit',function(){
		var data = $(this).serializeArray();
		$.ajax({
			url: '<?=site_url('data-identitas/simpan-data/'.@$pl)?>',
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
					<?php if(@$pl=='pl'){echo 'pilih_identitas(r.href); tbl_identitas.ajax.reload();';}else{echo 'window.location = r.href;';} ?>
					$('#modal_form_identitas').modal('hide');

				}else{
					toast('error',r.message);
				}
			}
		});
	});
	var selected_prov = '<?=@$data->provinsi?>';
	var selected_kota = '<?=@$data->kota?>';
	var selected_kecamatan = '<?=@$data->kecamatan?>';
	var selected_desa = '<?=@$data->desa?>';

	var selected_negeri = '<?=@$data->negeri?>';
	var selected_daerah = '<?=@$data->daerah?>';
	var selected_distrik = '<?=@$data->distrik?>';

	$('.btn_sama_pelapor').on('click',function(){
		var tipe = $(this).data('type');
		if(tipe=='indo'){
			selected_prov = pelapor.provinsi;
			selected_kota = pelapor.kota;
			selected_kecamatan = pelapor.kecamatan;
			selected_desa = pelapor.desa;
			JcomboAlamatID();
			$('#form_identitas_alamat_idn').val(pelapor.alamat_idn);
		}else if(tipe=='malay'){
			selected_negeri = pelapor.negeri;
			selected_daerah = pelapor.daerah;
			selected_distrik = pelapor.distrik;
			JcomboAlamatMY();
			$('#form_identitas_alamat_mys').val(pelapor.alamat_mys);
		}
	});
	function JcomboAlamatID() {
		$('#form_identitas_provinsi').jCombo("<?=site_url('api/negeri-provinsi/indonesia/')?>",{
			selected_value: selected_prov
		});
		$('#form_identitas_kota').jCombo("<?=site_url('api/daerah-kota/indonesia/')?>",{
			parent: "#form_identitas_provinsi",
			selected_value: selected_kota
		});
		$('#form_identitas_kecamatan').jCombo("<?=site_url('api/distrik-kecamatan/indonesia/')?>",{
			parent: "#form_identitas_kota",
			selected_value: selected_kecamatan
		});
	}
	JcomboAlamatID();
	// function link_desa
	$('#form_identitas_kecamatan, #form_identitas_kota, #form_identitas_provinsi').on('change', function(){
		var val1 = $('#form_identitas_kecamatan').val();
		var val2 = $('#form_identitas_kota').val();
		if(!val1||!val2){
			$('#form_identitas_desa').html('<option value="">-- Pilih Desa --</option>');
			$('#form_identitas_desa').prop('disabled',true);
		}else{
			$.ajax({
				url: '<?=site_url('api/desa-txt/')?>',
				type:'GET',dataType: 'json',
				data:{
					ref: $('#form_identitas_kecamatan').val(),
					refp: $('#form_identitas_kota').val(),
				},
				error:()=>{toast('error','Get Data Desa Error');},
				success:(r)=>{
					var x = "";
					var s = selected_desa;
					r.forEach((d,i)=>{
						x+='<option value="'+d.id+'" '+((d.id==s?'selected':''))+'>'+d.text+'</option>';
					});
					$('#form_identitas_desa').html(x);
					$('#form_identitas_desa').prop('disabled',false);
				}
			});
		}
	});

	function JcomboAlamatMY() {
		$('#form_identitas_negeri').jCombo("<?=site_url('api/negeri-provinsi/malaysia/')?>",{
			selected_value: selected_negeri
		});
		$('#form_identitas_daerah').jCombo("<?=site_url('api/daerah-kota/malaysia/')?>",{
			parent: "#form_identitas_negeri",
			selected_value: selected_daerah
		});
		$('#form_identitas_distrik').jCombo("<?=site_url('api/distrik-kecamatan/malaysia/')?>",{
			parent: "#form_identitas_daerah",
			selected_value: selected_distrik
		});
	}
	JcomboAlamatMY();
	function getListTmpLahir(){
		let s = $('#form_identitas_tmp_lahir').val();
		$.ajax({
			url: '<?=site_url('api/list-tempat-lahir')?>',
			type: 'POST', data:{s:s}, dataType: 'JSON',
			success: (r)=>{
				if(r.data){
					let ls='';
					r.data.forEach((d,i)=>{ls+='<option value="'+d+'">'});
					$('#list_tmp_lahir').html(ls);
				}
			}
		})
	}
	var opt_kwn = "<option value=\"\">-- Please Select --</option>";
	var slc_kwn = '<?=@$data->kewarganegaraan?>';
	slc_kwn = (slc_kwn)?slc_kwn:'Indonesia';
	negoro.forEach((d,i)=>{opt_kwn+="<option value=\""+d.name+"\" "+((d.name==slc_kwn)?'selected':'')+">"+d.name+"</option>"; });
	$('#kwn').html(opt_kwn);
	<?php if(!empty($data)){ echo "$('#form_identitas_negara').trigger('change');"; } ?>
	$('.select2').select2({theme: 'bootstrap4'});
	$('#jenis_identitas_slc').jCombo("<?=site_url('api/jenis-identitas-str')?>",{
		selected_value: '<?=@$data->jenis_identitas?>'
	});
	getListTmpLahir();
	$('#btn_no_p').on('click',function(){
		console.log(pelapor);
		$('#no_telp_p').val(pelapor.no_telp);
	});
	$(document).on('select2:open', () => {
		document.querySelector('.select2-search__field').focus();
	});
</script>