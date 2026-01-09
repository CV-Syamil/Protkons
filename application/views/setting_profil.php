<script src="https://cdnjs.cloudflare.com/ajax/libs/jSignature/2.1.3/jSignature.min.js"></script>
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header"><h3 class="card-title">User Profil</h3></div>
			<div class="card-body" id="body_form_profil">
				<?=form_open_multipart('profil/simpan',['id'=>'form_profil'])?>
					<center>
						<label>
							<input type="file" accept=".jpg,.png,.jpeg" name="foto" id="foto_profil" style="display:none">
							<img src="<?=getSession('foto')?>" id="foto_profil_prev" style="width:200px; height:200px; border-radius:500px;cursor:pointer; border:thin solid #eee;" alt="">
						</label>
						<p class="small">klik foto untuk mengganti</p>
					</center>
					<div class="form-group">
						<label>Username</label>
						<input type="text" class="form-control" value="<?=$data->username?>" disabled>
					</div>
					<div class="form-group">
						<label>NIP</label>
						<input type="text" class="form-control" name="nip" value="<?=$data->nip?>">
					</div>
					<div class="form-group">
						<label>Nama</label>
						<input type="text" class="form-control" minlength="3" name="nama" value="<?=$data->nama?>" required>
					</div>
					<div class="form-group">
						<label>Jabatan</label>
						<input type="text" class="form-control" name="jabatan" value="<?=$data->jabatan?>">
					</div>
					<div class="form-group" align="right">
						<button type="submit" class="btn btn-success">Simpan</button>
					</div>
				<?=form_close()?>
			</div>
		</div>	
	</div>

	<div class="col-md-6">
		<div class="card">
			<div class="card-header"><h3 class="card-title">Ubah Password</h3></div>
			<div class="card-body" id="body_form_ubah_pwd">
				<form action="#" onsubmit="return false;" id="form_ubah_pwd">
					<div class="form-group">
						<label>Password Lama</label>
						<div class="input-group">
							<input type="password" name="old_pwd" class="form-control inp_pwd" required>
							<div class="input-group-append">
								<button type="button" class="btn btn-secondary btn_show_pwd"><i class="fa fa-eye"></i></button>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Password Baru</label>
						<div class="input-group">
							<input type="password" name="new_pwd" class="form-control inp_pwd" required>
							<div class="input-group-append">
								<button type="button" class="btn btn-secondary btn_show_pwd"><i class="fa fa-eye"></i></button>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Konfirmasi Password Baru</label>
						<div class="input-group">
							<input type="password" name="confirm_new_pwd" class="form-control inp_pwd" required>
							<div class="input-group-append">
								<button type="button" class="btn btn-secondary btn_show_pwd"><i class="fa fa-eye"></i></button>
							</div>
						</div>
					</div>
					<div class="form-group" align="right">
						<button type="submit" class="btn btn-success">Ubah Password</button>
					</div>
				</form>
			</div>
		</div>	
	<?php if(getSession('akses')=='hs'){ ?>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title mt-1">E-SIGN</h4>
				<?php if(!empty($data->tte_nik)){ ?>
					<div class="card-tools">
						<button type="button" id="btn-loading" class="btn btn-primary btn-xs" style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;&nbsp;&nbsp;<i>loading...</i></button>
						<button type="button" id="btn-cek-sts" class="btn btn-primary btn-xs">Cek Status</button>
					</div>
					<script>
						$('#btn-cek-sts').on('click',function(e){
							e.preventDefault();
							var el = $(this);
							$.ajax({
								url: '<?=site_url("profil/cek_status_nik/".$data->tte_nik)?>', type:'GET', dataType: 'JSON',
								beforeSend:()=>loading_cek(true), complete:()=>loading_cek(false),
								success:(r)=>{
									if(r.status==200){
										Swal.fire('SUCCESS',r.message,'success');
									}else{
										toastr.error(r.message);
									}
								}
							});
						});
						function loading_cek(l=true){
							if(l){
								$('#btn-loading').show();
								$('#btn-cek-sts').hide();
							}else{
								$('#btn-loading').hide();
								$('#btn-cek-sts').show();
							}
						}
					</script>
				<?php } ?>
			</div>
			<div class="card-body">
				<form action="<?=site_url('profil/simpan_form_tte')?>" method="post">
					<div class="form-group">
						<label>NIK <span class="text-danger">*</span></label>
						<input type="text" name="nik" class="form-control" value="<?=$data->tte_nik?>" required>
					</div>
					<div class="form-group">
						<label>PASSPHRASE <span class="text-danger">*</span></label>
						<div class="input-group">
							<input type="password" name="pwd" id="inp_pwd" class="form-control" value="<?=$data->tte_pwd?>" required>
							<div class="input-group-append">
								<button onclick="$('#inp_pwd').attr('type',(i,a)=>{ return (a=='password')?'text':'password'; })" class="btn btn-default" type="button" title="show/hide password"><i class="fa fa-eye"></i></button>
							</div>
						</div>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-success">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	<?php } ?>

	</div>

</div>

<script>
	$('.btn_show_pwd').on('click',function(){
		$(this).toggleClass('btn-secondary btn-danger','btn-danger');
		$(this).find('.fa').toggleClass('fa-eye fa-eye-slash','fa-eye-slash');
		$(this).parents('.input-group').find('.inp_pwd').attr('type',(_,attr)=>{return (attr=='text'?'password':'text');});
	});
	$('#form_profil').on('submit',()=>blockUI('#body_form_profil'));
	$('#foto_profil').on('change', function(){
		if(this.files.length>0){ $('#foto_profil_prev').attr('src',URL.createObjectURL(this.files[0]));}
	});
	$('#form_ubah_pwd').on('submit',function(){
		var data = $(this).serializeArray();
		$.ajax({
			url: '<?=site_url('profil/ubah-password')?>', type: 'POST', data: data, dataType: 'JSON',
			beforeSend:()=> blockUI('#body_form_ubah_pwd'),
			error: (e)=>{
				toast('error',e.status+': '+e.statusText);
				blockUI('#body_form_ubah_pwd',false);
			},success:(r)=>{
				blockUI('#body_form_ubah_pwd',false);
				if(r.status==200){
					$('#form_ubah_pwd')[0].reset();
					logout();
				}else{
					toast('error','Ubah Password',r.message);
				}
			}
		})
	});
</script>