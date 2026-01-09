<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.css">
<script src="<?=base_url('style/lte')?>/plugins/moment/moment.min.js"></script>
<script src="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.js"></script>
<style>
    div.dataTables_wrapper div.dataTables_filter input{margin:0 !important;}
    /* .text-left{text-align:} */
</style>
<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">Data Pelayanan</h3>
	</div>
	<div class="card-body">
		<table width="100%" id="datatable" class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th>Kode Layanan</th>
					<th>Layanan</th>
					<th>Biaya</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
    var start_date = moment();
    var end_date = moment();

	function setValDate(){
		if(start_date.format('YYYY-MM-DD')==end_date.format('YYYY-MM-DD')){ 
			$('#cari_date').val(start_date.format('DD MMMM YYYY'));
		}else{
			$('#cari_date').val(start_date.format('DD MMMM YYYY')+' - '+end_date.format('DD MMMM YYYY'));
		}
	}

	$(document).ready(()=>{

		var tbl = $('#datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: {type: 'POST', data: (data)=>{
                data.start_date = start_date.format('YYYY-MM-DD');
                data.end_date = end_date.format('YYYY-MM-DD');
                return data;
            }},
	        columns: [
	        	{data: 'kode_layanan',},
	        	{data: 'pelayanan',},
	        	{data: 'jumlah', className:'text-right'},
	        ]
		});
		$('#datatable_filter').html('<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">Tanggal</span></div><input type="text" class="form-control" id="cari_date" readonly><div class="input-group-append"><button type="button" id="btn_print" class="btn btn-primary"><i class="fa fa-print"></i></button></div></div>');
        $('#cari_date').daterangepicker({
            ranges   : {
                'Hari Ini' : [moment(), moment()],
                '7 Hari Terakhir' : [moment().subtract(6, 'days'), moment()],
                'Bulan ini'  : [moment().startOf('month'), moment().endOf('month')],
                'Bulan sebelumnya'  : [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
            },
			autoUpdateInput: false,
            startDate: start_date,
            endDate  : end_date,
            locale: {format: 'DD MMMM YYYY',}
        },function (start, end) { start_date = start; end_date = end; tbl.ajax.reload(); setValDate();});
		$('#btn_print').on('click',()=>{
			window.open('<?=site_url('laporan/keuangan/cetak')?>?start_date='+start_date.format('YYYY-MM-DD')+'&end_date='+end_date.format('YYYY-MM-DD'));
		});
		setValDate();
	});
</script>