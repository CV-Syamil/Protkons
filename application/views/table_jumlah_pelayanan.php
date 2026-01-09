<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?=base_url('style/lte')?>/plugins/select2/js/select2.full.min.js"></script>

<style type="text/css">
	.slc_datatable{width: 150px !important; display: inline-block; margin-left: 5px;}
	#datatable td .btn{ margin:2px; }
	.table tbody td{text-transform:uppercase;}
	.select2-container .select2-selection--single{height: calc(2.25rem + 3px);}
</style>
<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">Data Input Jumlah Pelayanan</h3>
		<div class="card-tools">
			<button type="button" title="Input Jumlah Pelayanan" class="btn btn-success btn-sm" id="btn-add"><i class="fa fa-plus"></i> Input Jumlah</button>
		</div>
	</div>
	<div class="card-body">
		<table width="100%" id="datatable" class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th>Tanggal</th>
					<th>Layanan</th>
					<th>Jumlah</th>
					<th width="150">Actions</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="modal fade" id="modal_form" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Form</h5>
				<button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#modal_form" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
			</div>
			<div class="modal-body">
				<form action="<?=site_url('jumlah_pelayanan/simpan')?>" id="from-xx" method="post">
					<input type="hidden" name="ref" id="refx">
					<div class="form-group">
						<label for="pelayanan">Pelayanan</label>
						<select name="pelayanan" id="pl" class="form-control slcx" required>
							<?php foreach($pl as $v){ echo "<option value=\"$v->pelayanan_id\">($v->kode_layanan) $v->pelayanan</option>"; } ?>
						</select>
					</div>
					<div class="form-group">
						<label for="tgl">Tanggal</label>
						<input type="date" name="tgl" id="tgl" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="tgl">Jumlah Pelayanan</label>
						<input type="number" min="1" name="jml" id="jml" class="form-control" required>
					</div>
					<div class="text-right">
						<button type="reset" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal">Batal</button> 
						<button type="submit" class="btn btn-primary">Simpan</button> 
					</div>
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
	$(document).ready(()=>{
		$('.slcx').select2({width:'100%',dropdownParent:$('#modal_form'),placeholder:'-- Pilih Pelayanan --'});
		$('#btn-add').on('click',()=>formx($('#btn-add')));
		$('#datatable').on('click','.btn-edit',function() { formx($(this)); });
		function formx(el){
			$('#refx').val(el.data('ref')??'');
			$('#pl').val(el.data('pl')??'').change();
			$('#tgl').val(el.data('tgl')??'<?=date('Y-m-d')?>');
			$('#jml').val(el.data('jml')??1);
			$('#modal_form .modal-title').html(el.prop('title')??'Form');
			$('#modal_form').modal('show');
		}
		$('#from-xx').on('submit', function(e){
			e.preventDefault();
			$.ajax({
				url: this.action, type: this.method, data:$(this).serializeArray(), dataType:'JSON',
				beforeSend:()=>blockUI('.modal'), complete:()=>blockUI('.modal',false),
				success:(r)=>{
					if(r.status==200){
						if(tbl){$('.modal').modal('hide');toast('success',r.message,'SUCCESS');tbl.ajax.reload();}
						else{ blockUI('.card'); window.location.reload(); }
					}else{
						toast('error',r.message,'RESPONSE ERROR '+r.status);
					}
				}
			})
		});
		var tbl = $('#datatable').DataTable({
			processing: false,
	        serverSide: true,
			order : [[0,'DESC']],
            // deferLoading: 0,
	        ajax: {type: 'POST', beforeSend:()=>blockUI('#datatable_wrapper'), 
				complete:()=>blockUI('#datatable_wrapper', false),},
			columns: [
	        	{data: 'tanggal',searchable: false,},
	        	{data: 'pelayanan',searchable: false,orderable: false,},
	        	{data: 'jumlah',searchable: false, className:'text-right'},
	        	{data: 'act', searchable: false, orderable: false, className: 'text-center'},
	        ]
		});
	});
</script>