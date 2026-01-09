<div class="card">
	<div class="card-header">
		<h3 class="card-title">Data Provinsi Indonesia</h3>
		<div class="card-tools">
			<button class="btn btn-success btn-sm btn-form-x" type="button" data-ref="" data-nama="" title="Tambah Data"><i class="fa fa-plus"></i> Tambah</button>
		</div>
	</div>
	<div class="card-body">
		<table width="100%" class="table table-bordered table-striped table-hover" id="datatable">
			<thead>
				<tr>
					<th width="100">ID Provinsi</th>
					<th>Nama Provinsi</th>
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
				<form action="<?=site_url('master/idn/provinsi/simpan')?>" method="post" id="form-x">
					<input type="hidden" name="ref" class="ref-x">
					<div class="form-group">
						<label>ID PROVINSI</label>
						<input type="text" name="id" class="form-control ref-x" maxlength="2" minlenght="2" required>
					</div>
					<div class="form-group">
						<label>NAMA PROVINSI</label>
						<input type="text" name="nama" id="nama_prov" class="form-control" minlenght="2" required>
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


<script type="text/javascript">
	$(document).ready(()=>{
		$(document).on('click','.btn-form-x', function(e){
			e.preventDefault();
			var ref = $(this).data('ref'), nama = $(this).data('nama'), title = $(this).prop('title');
			$('#modal-form .modal-title').html(title??'Form');
			$('.ref-x').val(ref);
			$('#nama_prov').val(nama);
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
	        ajax: { type: 'POST' },
	        columns: [
	        	{data: 'kode', className: 'text-center'},
	        	{data: 'name'},
	        	{data: 'act',searchable: false, orderable: false, className: 'text-center'},
	        ]
		});
	});
</script>