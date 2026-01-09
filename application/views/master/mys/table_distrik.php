<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">Data Distrik Malaysia</h3>
		<div class="card-tools">
			<button class="btn btn-success btn-sm btn-form-x" id="btn-add" type="button" data-ref="" data-nama="" data-daerah="" data-refdaerah="" title="Tambah Data"><i class="fa fa-plus"></i> Tambah</button>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Negeri</label>
					<select class="form-control" id="filter1">
						<?php foreach($negeri as $v){ echo "<option value=\"$v->id\">$v->nama</option>"; } ?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Daerah</label>
					<select class="form-control daerah-x" id="filter2">
						<?php if(!empty($daerah_first)){ echo "<option value=\"$daerah_first->id\">$daerah_first->nama</option>"; } ?>
					</select>
				</div>
			</div>
		</div>
		<table width="100%" class="table table-bordered table-striped table-hover" id="datatable">
			<thead>
				<tr>
					<th width="100">ID Distrik</th>
					<th>Nama Daerah</th>
					<th>Nama Distrik</th>
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
				<form action="<?=site_url('master/mys/distrik/simpan')?>" method="post" id="form-x">
					<input type="hidden" name="ref" class="ref-x">
					<div class="form-group">
						<label>DAERAH</label>
						<select class="form-control daerah-x" id="daerah-f" name="daerah">
							<?php if(!empty($daerah_first)){ echo "<option value=\"$daerah_first->id\">$daerah_first->nama</option>"; } ?>
						</select>
					</div>

					<div class="form-group">
						<label>NAMA DISTRIK</label>
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
			var ref = $(this).data('ref'), nama = $(this).data('nama'), daerah = $(this).data('daerah'),refdaerah = $(this).data('refdaerah'), title = $(this).prop('title');
			$('#modal-form .modal-title').html(title??'Form');
			$('.ref-x').val(ref);
			$('#nama_x').val(nama);
			if(refdaerah&&daerah){
				$('#daerah-f').html(`<option value="${refdaerah}">${daerah}</option>`).change();
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
	        ajax: { type: 'POST', data:(d)=>{d.daerah=$('#filter2').val();return d;}  },
	        columns: [
	        	{data: 'kode', className: 'text-center'},
	        	{data: 'nama2'},
	        	{data: 'nama1'},
	        	{data: 'act', className: 'text-center', orderable: false, searchable: false},
	        ]
		});
		$('#filter1').select2({
			width: '100%',
			theme: "bootstrap"
		}).on('change',function(){
			$('.daerah-x').html('').change();
			$('#filter2').select2('open');
		});
		$('.daerah-x').select2({
			width: '100%',
			theme: "bootstrap",
			ajax: {
				url: '<?=site_url("master/mys/distrik/data-daerah")?>',
				type: 'POST',
				data:(d)=>{return{s:d.term,negeri:$('#filter1').val()}},
				dataType: 'JSON', 
			}
		}).on('change',function(){
			if(this.value&&this.id=='filter2'){
				var x = $(this).select2('data');
				if(x){
					x = x[0];
					$('#btn-add').data('daerah',x.text);
					$('#btn-add').data('refdaerah',x.id);
				}
				tbl.ajax.reload();
			}
		});
	});
</script>