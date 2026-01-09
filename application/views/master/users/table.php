<link rel="stylesheet" href="<?=base_url('style/lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')?>">
<script src="<?=base_url('style/lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')?>"></script>
<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">Data Master Users</h3>
		<div class="card-tools">
			<button type="button" data-title="Form Tambah User" data-href="<?=base_url('master/users/tambah')?>" class="btn btn-tool bg-success btn_modal_form"><i class="fa fa-plus"></i> Tambah Data</button>
		</div>
	</div>
	<div class="card-body">
		<table width="100%" class="table table-bordered table-striped table-hover" id="datatable">
			<thead>
				<tr>
					<th width="70">Foto</th>
					<?=((can_access('su')))?'<th>Fungsi</th>':''; ?>
					<th>Username</th>
					<th>Nama</th>
					<th width="100">Akses</th>
					<th>Last Login</th>
					<th width="50">Status</th>
					<th width="150">Actions</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<div class="modal fade" id="modal_form">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body"></div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
	$(document).ready(()=>{
		var tbl = $('#datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: {type: 'POST'},
	        columns: [
	        	{data: 'foto', className: 'text-center', searchable: false, orderable: false},
				<?=((can_access('su')))?'{data: \'nm_fungsi\',searchable: false},':''; ?>
	        	{data: 'username'},
	        	{data: 'nama'},
	        	{data: 'akses'},
	        	{data: 'last_login',searchable: false},
	        	{data: 'aktif', className: 'text-center', searchable: false},
	        	{data: 'act', orderable: false, searchable: false, className: 'text-center'},
	        ]
		});
		$(document).on('click','.btn-del', function(){
			var href = $(this).data('href');
			Swal.fire({
				title: 'Hapus Data User ?',
				text: "Data User akan terhapus secara permanen.",
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Hapus',
				showLoaderOnConfirm: true,
				preConfirm: ()=>{
					return new Promise(function(resolve, reject) {
						window.location = href;
					});
				},allowOutsideClick:false
			});
		});
		$(document).on('click','.btn_reset_pwd', function(){
			var href = $(this).data('href');
			Swal.fire({
				title: 'Reset Password User ?',
				text: "Password User akan direset ke username",
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Reset Password',
				showLoaderOnConfirm: true,
				preConfirm: ()=>{
					return new Promise(function(resolve, reject) {
						window.location = href;
					});
				},allowOutsideClick:false
			});
		});
		$(document).on('click','.btn_modal_form', function(){
			var ref = $(this).data('href');
			var title = $(this).data('title');
			title = (title)?title:'Form';
			$('#modal_form .modal-title').html(title);
			$('#modal_form .modal-body').html('');
			$.ajax({
				url: ref,
				type: 'GET',
				beforeSend: ()=>{$('#modal_form').modal('show');},
				error:(e)=>{setTimeout(() => { $('#modal_form').modal('hide');alert(e.status+': '+e.statusText); }, 500);},
				success: (r)=>{
					$('#modal_form .modal-body').html(r);
				}
			});
		});
	});
	
</script>