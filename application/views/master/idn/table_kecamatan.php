<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">Data Kecamatan Indonesia</h3>
		<div class="card-tools">
			<button class="btn btn-success btn-sm btn-form-x" id="btn-add" type="button" data-ref="" data-nama="" data-kota="" data-refkota="" title="Tambah Data"><i class="fa fa-plus"></i> Tambah</button>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Provinsi</label>
					<select class="form-control" id="filter_prov">
						<?php foreach($prov as $v){ echo "<option value=\"$v->id\">$v->name</option>"; } ?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Kota</label>
					<select class="form-control kota-x" id="filter_kota">
						<?php if(!empty($kota_first)){ echo "<option value=\"$kota_first->id\">$kota_first->name</option>"; } ?>
					</select>
				</div>
			</div>
		</div>
		<table width="100%" class="table table-bordered table-striped table-hover" id="datatable">
			<thead>
				<tr>
					<th width="100">ID Kecamatan</th>
					<th>Nama Kota</th>
					<th>Nama Kecamatan</th>
					<th width="100">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="modal fade" id="modal-form">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Default Modal</h4>
				<button type="reset" form="form-x" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="<?=site_url('master/idn/kecamatan/simpan')?>" method="post" id="form-x">
					<input type="hidden" name="ref" class="ref-x">
					<div class="form-group">
						<label>KOTA</label>
						<select class="form-control kota-x" id="kota-f" name="kota">
							<?php if(!empty($kota_first)){ echo "<option value=\"$kota_first->id\">$kota_first->name</option>"; } ?>
						</select>
					</div>
					<div class="form-group">
						<label>KODE KECAMATAN</label>
						<input type="text" name="kode" class="form-control ref-x" minlength="7" maxlength="7" required>
					</div>

					<div class="form-group">
						<label>NAMA KECAMATAN</label>
						<input type="text" name="nama" id="nama_x" class="form-control" minlenght="2" required>
					</div>
				</form>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="reset" form="form-x" class="btn btn-default" data-dismiss="modal">Batal</button>
				<button type="submit" form="form-x" class="btn btn-primary">Simpan</button>
			</div>
		</div>
	</div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
	$(document).ready(()=>{
		$(document).on('click','.btn-form-x', function(e){
			e.preventDefault();
			var ref = $(this).data('ref'), nama = $(this).data('nama'), kota = $(this).data('kota'),refkota = $(this).data('refkota'), title = $(this).prop('title');
			$('#modal-form .modal-title').html(title??'Form');
			$('.ref-x').val(ref);
			$('#nama_x').val(nama);
			if(refkota&&kota){
				console.log(true);
				$('#kota-f').html(`<option value="${refkota}">${kota}</option>`).change();
			}
			$('#modal-form').modal('show');
		});
		$('#form-x').on('submit', function(e){
			e.preventDefault();
			$.ajax({
				url: $(this).prop('action'), type: 'POST', data: $(this).serializeArray(), dataType: 'JSON',
				beforeSend:()=>blockUI('.modal'),
				complete:()=>blockUI('.modal',false),
				success:(r)=>{
					if(r.status==200){
						toast('success',r.message,'SUCCESS');
						$('.modal').modal('hide');
						tbl.ajax.reload();
					}else{
						toast('error',r.message,'RESPONSE ERROR');
					}
				}
			});
		});
		$(document).on('click','.btn-del', function(){
			var href = $(this).data('href');
			Swal.fire({
				title: 'Hapus Data ?',
				text: "Data akan terhapus secara permanen.",
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Hapus',
				showLoaderOnConfirm: true,
				preConfirm: ()=>{
					return new Promise(function(resolve, reject) {
						$.ajax({url:href,type:'GET', dataType:'JSON',error:(e)=>resolve(''),success:(r)=>resolve(r)});
					});
				},allowOutsideClick:false
			}).then((r)=>{
				if(r.isConfirmed){
					if(r.value){
						var d = r.value;
						if(d.status==200){toast('success',d.message,'SUCCESS');tbl.ajax.reload();}
						else{toast('error',d.message,'RESPONSE ERROR: '+d.status);}
					}
				}
			});
		});
		var tbl = $('#datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: { type: 'POST', data:(d)=>{ d.kota=$('#filter_kota').val(); return d; }},
	        columns: [
	        	{data: 'kode', className: 'text-center'},
	        	{data: 'kota_name', orderable: false, searchable: false},
	        	{data: 'kec_name',},
	        	{data: 'act', className: 'text-center', orderable: false, searchable: false},
	        ]
		});
		$('#filter_prov').select2({
			width: '100%',
			theme: "bootstrap"
		}).on('change',function(){
			$('.kota-x').html('').change();
			$('#filter_kota').select2('open');
		});
		$('.kota-x').select2({
			width: '100%',
			theme: "bootstrap",
			ajax: {
				url: '<?=site_url("master/idn/kecamatan/data-kota")?>',
				type: 'POST',
				data:(d)=>{return{s:d.term,prov:$('#filter_prov').val()}},
				dataType: 'JSON', 
			}
		}).on('change',function(){
			if(this.value&&this.id=='filter_kota'){
				var x = $(this).select2('data');
				if(x){
					x = x[0];
					$('#btn-add').data('kota',x.text);
					$('#btn-add').data('refkota',x.id);
				}
				tbl.ajax.reload();
			}
		});
	});
</script>