<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?=base_url('style/lte')?>/plugins/select2/js/select2.full.min.js"></script>
<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.css">
<script src="<?=base_url('style/lte')?>/plugins/moment/moment.min.js"></script>
<script src="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.js"></script>

<style type="text/css">
	.slc_datatable{width: 150px !important; display: inline-block; margin-left: 5px;}
	#datatable td .btn{ margin:2px; }
	.table tbody td{text-transform:uppercase;}
	.ddfx a{ color: black;}
	.ddfx a:hover{ font-weight:bold; }
	.dark-mode .select2-selection{ background-color: transparent !important; }
	.dark-mode .select2-selection__rendered{ color: white !important; }
	.dark-mode .select2-selection__placeholder{ color: white !important; }
</style>
<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">Data Pelayanan (Online)</h3>
	</div>
	<div class="card-body">
		<div class="row mb-3" id="filter_xxxx">
			<div class="col-md-6">
				<div class="form-group">
					<label>Tanggal</label>
					<input type="text" class="form-control bg-transparent" id="tgl_range" readonly>

					<input type="hidden" class="form-control" id="tgl" value="">
					<input type="hidden" class="form-control" id="tgl2" value="">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Pelayanan</label>
					<select id="slc_pl" class="form-control"></select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Pelapor</label>
					<select id="slc_identitas" class="form-control"></select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Status</label>
					<select id="slc_sts" class="form-control">
						<option value="">Semua Status</option>
						<option value="1">Verifikasi</option>
						<option value="2">diTerima</option>
						<option value="9">diTolak</option>
					</select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<button type="button" id="btn_show" class="btn btn-primary btn-block">Show Data</button>
				</div>
			</div>
		</div>
		<div style="display:none" id="div_tbl">
			<table width="100%" id="datatable" class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th>Layanan</th>
						<th>Nama Pelapor</th>
						<th>Tgl. diBuat</th>
						<th>Tgl. Pengambilan</th>
						<th>Status</th>
						<th width="150">Actions</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>


<div class="modal fade" id="modal-form-x">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#modal_identitas" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body"></div>
		</div>
		<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>

<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.css">
<script src="<?=base_url('style/lte')?>/plugins/moment/moment.min.js"></script>
<script src="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript">
	var tgl1 = moment(), tgl2 = moment();
	
	$('#tgl_range').daterangepicker({
		ranges   : {
			'Hari Ini' : [tgl1,tgl2],
			'7 Hari Terakhir' : [moment().subtract(6, 'days'), moment()],
			'Bulan ini'  : [moment().startOf('month'), moment().endOf('month')],
			'Bulan sebelumnya'  : [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
			'Tahun ini'  : [moment().startOf('year'), moment().endOf('year')],
		},
		startDate: tgl1,
		endDate  : tgl2,
		showDropdowns: true,
		linkedCalendars: false,
		autoUpdateInput: false,
	},setTglFx);
	setTglFx(tgl1,tgl2);

	var tbl;
	$(document).ready(()=>{
		tbl = $('#datatable').DataTable();
		
		$('.select2').select2({
			theme: 'bootstrap4'
		});
		
		$('#slc_pl').select2({
			theme: 'bootstrap4',
			placeholder: "Semua Pelayanan",
			allowClear: true,
			width: '100%',
			ajax: { url: '<?=site_url('api/get-layanan')?>', type:'POST',dataType: 'json', data: function(p){return {s:p.term,i:1};}, }
		});

		$('#slc_identitas').select2({
			theme: 'bootstrap4',
			placeholder: "Semua Pelapor",
			allowClear: true,
			width: '100%',
			ajax: { url: '<?=site_url('api/get-person')?>', type:'POST',dataType: 'json', data: function(p){return {s:p.term,ref:'noid'};}, }
		});

		$('#btn_show').on('click',function(){
			$.ajax({
				url:'<?=site_url("pelayanan_publik/data")?>',type:'POST', 
				data:{
					tgl : $('#tgl').val(),
					tgl2 : $('#tgl2').val(),
					pl : $('#slc_pl').val(),
					pelapor : $('#slc_identitas').val(),
					sts : $('#slc_sts').val(),
				},
				beforeSend:()=>blockUI('.card'),
				complete:()=>blockUI('.card',false),
				success:(r)=>{
					$('#div_tbl').show('fade');
					tbl.destroy();
					$('#datatable tbody').html(r);
					tbl = $('#datatable').DataTable();
				}
			});
		});
		$(document).on('click','.showOnModal',function(e){
			e.preventDefault();
			let el = $(this);
			let href = el.data('href');
			let title = el.prop('title')??'Form';
			$('#modal-form-x .modal-title').html(title);
			$('#modal-form-x').modal('show');
			$('#modal-form-x .modal-body').html('<center><h4><i class="fa fa-spinner fa-spin"></i> <i>loading...</i></h4></center>');
			$.ajax({
				url: href, type:'GET',
				error:()=>$('#modal-form-x').modal('hide'),
				success:(r)=>$('#modal-form-x .modal-body').html(r)
			});
		});
	});
	function setTglFx(s,e){
		tgl1 = s; tgl2 = e;
		$('#tgl_range').val(s.format('DD MMMM YYYY')+' - '+e.format('DD MMMM YYYY'));
		$('#tgl').val(s.format('YYYY-MM-DD'));
		$('#tgl2').val(e.format('YYYY-MM-DD'));
	}
</script>