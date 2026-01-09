<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">Data Jenis Identitas</h3>
		<div class="card-tools">
			<button type="button" data-title="Form Tambah Jenis Identitas" data-ref="" data-jenis="" class="btn btn-tool bg-success btn_modal_form"><i class="fa fa-plus"></i> Tambah Data</button>
		</div>
	</div>
	<div class="card-body">
		<table width="100%" class="table table-bordered table-striped table-hover" id="datatable">
			<thead>
				<tr>
					<th width="50">No</th>
					<th>Jenis Identitas</th>
					<th width="75">Actions</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<div class="modal fade" id="modal_form" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
		  <form action="#" onsubmit="return false;" id="form_jenis_identitas">
			  <input type="hidden" name="ref" id="ref">
			  <div class="form-group">
				  <label>Jenis Identitas</label>
				  <input type="text" name="jenis" id="jenis" class="form-control" required>
			  </div>
			  <div class="form-group" align="right">
				  <button type="reset" data-dismiss="modal" class="btn btn-secondary">Batal</button>
				  <button type="submit" class="btn btn-success">Simpan</button>
			  </div>
		  </form>
	  </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
	var tbl;
	$(document).ready(()=>{
		tbl = $('#datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: {type: 'POST'},
	        columns: [
	        	{className: 'text-center', searchable: false, orderable: false, render: (d,t,r,m)=>{
                    return m.row+m.settings._iDisplayStart+1;
                }},
	        	{data: 'jenis'},
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
            }).then((result)=>{ });
		});
		$(document).on('click','.btn_modal_form', function(){
			var title = $(this).data('title');
			var ref = $(this).data('ref');
			var jenis = $(this).data('jenis');
			title = (title)?title:'Form';
			$('#modal_form .modal-title').html(title);
			$('#ref').val(ref);
			$('#jenis').val(jenis);
			$('#modal_form').modal('show');
		});
		$('#form_jenis_identitas').on('submit',function(){
			var data = $(this).serializeArray();
			$.ajax({
				url: '<?=site_url('master/jenis-identitas/simpan')?>', data: data, type: 'POST', dataType: 'JSON',
				beforeSend:()=>blockUI('#modal_form .modal-content'),
				error: (e)=>{
					blockUI('#modal_form .modal-content',false);
					toast('error',e.status+': '+e.statusText);
				},success:(r)=>{
					blockUI('#modal_form .modal-content',false);
					if(r.status==200){
						toast('success',r.message);
						$('#modal_form').modal('hide');
						if(tbl){tbl.ajax.reload();}
					}else{
						toast('error',r.message);
					}
				}
			})
		});
	});
</script>