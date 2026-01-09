<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">Data Daerah Malaysia</h3>
		<div class="card-tools">
			<button class="btn btn-success btn-sm btn-form-x" type="button" id="btn-add-x" data-ref="" data-nama="" data-negeri="" title="Tambah Data"><i class="fa fa-plus"></i> Tambah</button>
		</div>
	</div>
	<div class="card-body">
		<table width="100%" class="table table-bordered table-striped table-hover" id="datatable">
			<thead>
				<tr>
					<th width="100">ID Daerah</th>
					<th>Nama Negeri</th>
					<th>Nama Daerah</th>
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
				<form action="<?=site_url('master/mys/daerah/simpan')?>" method="post" id="form-x">
					<input type="hidden" name="ref" class="ref-x">
					<div class="form-group">
						<label>NEGERI</label>
						<select class="form-control" name="negeri" id="negeri_x" required>
							<?php foreach($negeri as $v){ echo "<option value=\"$v->id\">$v->nama</option>"; } ?>
						</select>
					</div>
					<div class="form-group">
						<label>NAMA DAERAH</label>
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


<script type="text/javascript">
	$(document).ready(()=>{
		$(document).on('click','.btn-form-x', function(e){
			e.preventDefault();
			var ref = $(this).data('ref'), nama = $(this).data('nama'), negeri = $(this).data('negeri'), title = $(this).prop('title');
			$('#modal-form .modal-title').html(title??'Form');
			$('.ref-x').val(ref);
			$('#negeri_x').val(negeri);
			$('#nama_x').val(nama);
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
			deferLoading: 0,
	        ajax: { type: 'POST', data:(d)=>{d.negeri=$('#slc_neg_x').val();return d;} },
	        columns: [
	        	{data: 'kode', className: 'text-center'},
	        	{data: 'nama2',orderable: false, searchable: false},
	        	{data: 'nama1'},
	        	{data: 'act', className: 'text-center', orderable: false, searchable: false},
	        ]
		});
		$('#datatable_filter').append(`<select class="form-control form-control-sm" id="slc_neg_x" style="width:175px; display:inline-block;">
			<?php foreach($negeri as $v){ echo "<option value=\"$v->id\">$v->nama</option>"; } ?>
		</select>`);
		$('#slc_neg_x').on('change',()=>{$('#btn-add-x').data('negeri',$('#slc_neg_x').val());tbl.ajax.reload();}).change();
	});
</script>