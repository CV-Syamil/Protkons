<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?=base_url('style/lte')?>/plugins/select2/js/select2.full.min.js"></script>
<script src="<?=base_url('style/jcombo/jquery.jCombo.min.js')?>"></script>
<script src="<?=base_url('style/lte/plugins/chart.js/Chart.min.js')?>"></script>
<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.css">
<script src="<?=base_url('style/lte')?>/plugins/moment/moment.min.js"></script>
<script src="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.js"></script>
<style>
	.no-padding{padding:0 0 15px 0 !important;}
</style>
<style type="text/css">
	#slc_datatable{width: 150px !important; display: inline-block; margin-left: 5px;}
	#datatable td .btn{ margin:2px; }
</style>
<div class="row">
	<div class="col-md-4 col-xs-12">
		<div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Menu Pelayanan</span>
            <span class="info-box-number"><?=numb($count_pl['pl'])?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
		<!-- /.info-card -->
	</div>
	<!-- / .col -->
	<div class="col-md-4 col-xs-12">
		<div class="info-box">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-database"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Semua Pelayanan</span>
            <span class="info-box-number"><?=numb($count_pl['tr_pl'])?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
		<!-- /.info-card -->
	</div>
	<!-- / .col -->
	<div class="col-md-4 col-xs-12">
		<div class="info-box">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Arsip Pelayanan</span>
            <span class="info-box-number"><?=numb($count_pl['tr_pl2'])?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
		<!-- /.info-card -->
	</div>
	<!-- / .col -->
</div>

<div class="row">
	<?php if(can_access(['admin','verifikasi','loket','hs'])){ ?>
		<div class="col-md">
			<div class="card card-solid">
				<div class="card-header with-border">
					<div class="input-group">
						<input type="text" name="tgl" class="form-control date_range_filter" id="inp_date_ch_pl">
						<select style="max-width:100px" name="layanan" id="slc_ch_pl" class="form-control select2 slc_pl"></select>
						<div class="input-group-append">
							<button type="button" id="btn_filter_ch_pl" class="btn bg-purple" title="filter grafik"><i class="fa fa-filter"></i></button>
							<button type="button" id="btn_export_ch_pl" class="btn btn-primary" title="export grafik"><i class="fa fa-download"></i></button>
						</div>
					</div>
				</div>
				<div class="card-body no-padding" id="body_ch_pl"><canvas id="chart_pelayanan" style="width:100%; max-width:100%; min-height:250px;"></canvas></div>
			</div>
			<script>
				$(document).ready(()=>{
					var chart_pl = new Chart($('#chart_pelayanan').get(0).getContext('2d'),{
						type: 'bar',
						data: {
							labels: [],
							datasets:[{
								label:'Jumlah',
								data: [],
								backgroundColor: ch_colors[3]
							}]
						},options:{
							maintainAspectRatio : false,
							responsive : true,
							title: {
								display: true,
								text: 'Grafik Arsip Layanan'
							},legend:{
								display:false,
							},scales: {
								xAxes: [{
									display: true,
									scaleLabel: {
										display: true,
										labelString: 'Tanggal'
									}
								}],
								yAxes: [{
									display: true,
									ticks: {
										min: 0,
										stepSize: 1
									}
								}]
							},
						},
					});
					var ajax_ch_pl;
					$('#btn_filter_ch_pl').on('click',()=>get_data_ch_pl());
					function get_data_ch_pl(){
						var tgl = $('#inp_date_ch_pl').val();
						var pl = $('#slc_ch_pl').val();
						if(ajax_ch_pl){ajax_ch_pl.abort();}
						ajax_ch_pl = $.ajax({
							url: '<?=site_url('grafik/ch-pl')?>', type: 'POST', data:{pl:pl,tgl:tgl}, dataType: 'JSON',
							beforeSend: ()=>{ blockUI('#body_ch_pl');},
							error: (e)=>{ blockUI('#body_ch_pl',false); toast('error','Chart Arsip Pelayanan',e.status+': '+e.statusText);},
							success: (r)=>{ 
								blockUI('#body_ch_pl',false);
								if(r.data){
									chart_pl.data.labels = r.data.labels;
									chart_pl.data.datasets[0].data = r.data.datas;
									chart_pl.update();
								}
							},
						});
					}
					$('#btn_export_ch_pl').on('click',()=>{
						var a = document.createElement('a');
						a.href = chart_pl.toBase64Image('image/jpeg',1);
						a.download = 'Chart Arsip Pelayanan';
						a.click();
					});
					setTimeout(function() {
						get_data_ch_pl();
					},500);
				});
			</script>
		</div>
	<?php } if(can_access(['admin','hs','kasir'])){ ?>
		<div class="col-md">
			<div class="card card-solid">
				<div class="card-header with-border">
					<div class="input-group">
						<input type="text" name="tgl" class="form-control date_range_filter" id="inp_date_ch_keu">
						<select style="max-width:100px" name="layanan" id="slc_ch_keu" class="select2 form-control slc_pl"></select>
						<div class="input-group-append">
							<button type="button" id="btn_filter_ch_keu" class="btn bg-purple" title="filter grafik"><i class="fa fa-filter"></i></button>
							<button type="button" id="btn_export_ch_keu" class="btn btn-primary" title="export grafik"><i class="fa fa-download"></i></button>
						</div>
					</div>
				</div>
				<div class="card-body no-padding" id="body_ch_keu"><canvas id="chart_keu" style="width:100%; max-width:100%; min-height:250px;"></canvas></div>
			</div>
			<script>
				$(document).ready(()=>{
					var chart_keu = new Chart($('#chart_keu').get(0).getContext('2d'),{
						type: 'bar',
						data: {
							labels: [],
							datasets:[{
								label:'Jumlah',
								data: [],
								backgroundColor: ch_colors[0]
							}]
						},options:{
							maintainAspectRatio : false,
							responsive : true,
							title: {
								display: true,
								text: 'Grafik Keuangan'
							},legend:{
								display:false,
							},scales: {
								xAxes: [{
									display: true,
									scaleLabel: {
										display: true,
										labelString: 'Tanggal'
									}
								}],
								yAxes: [{
									display: true,
									ticks: {
										min: 0,
										stepSize: 1
									}
								}]
							}
						}
					});
					var ajax_ch_keu;
					$('#btn_filter_ch_keu').on('click',()=>get_data_ch_keu());
					function get_data_ch_keu(){
						var tgl = $('#inp_date_ch_keu').val();
						var pl = $('#slc_ch_keu').val();
						if(ajax_ch_keu){ajax_ch_keu.abort();}
						ajax_ch_pl = $.ajax({
							url: '<?=site_url('grafik/ch-keu')?>', type: 'POST', data:{pl:pl,tgl:tgl}, dataType: 'JSON',
							beforeSend: ()=>{ blockUI('#body_ch_keu');},
							error: (e)=>{ blockUI('#body_ch_keu',false); toast('error','Chart Keuangan',e.status+': '+e.statusText);},
							success: (r)=>{ 
								blockUI('#body_ch_keu',false);
								if(r.data){
									chart_keu.data.labels = r.data.labels;
									chart_keu.data.datasets[0].data = r.data.datas;
									chart_keu.update();
								}
							},
						});
					}
					$('#btn_export_ch_keu').on('click',()=>{
						var a = document.createElement('a');
						a.href = chart_keu.toBase64Image('image/jpeg',1);
						a.download = 'Chart Keuangan Pelayanan';
						a.click();
					});
					setTimeout(function() {
						get_data_ch_keu();
					},500);
				});
			</script>
		</div>
	<?php } ?>
</div>
<script type="text/javascript">
	var tw1 = [moment('<?=date('Y')?>-01-01').startOf('month'),moment('<?=date('Y')?>-03-01').endOf('month')];
	var tw2 = [moment('<?=date('Y')?>-04-01').startOf('month'),moment('<?=date('Y')?>-06-01').endOf('month')];
	var tw3 = [moment('<?=date('Y')?>-07-01').startOf('month'),moment('<?=date('Y')?>-09-01').endOf('month')];
	var tw4 = [moment('<?=date('Y')?>-10-01').startOf('month'),moment('<?=date('Y')?>-12-01').endOf('month')];
	var ajax_form_identitas;
	$(document).on('click','#modal_add_identitas, .modal_edit_identitas',function(){
		var href = $(this).data('href');
		if(ajax_form_identitas){ajax_form_identitas.abort();}
		ajax_form_identitas = $.ajax({
			url: href, type: 'GET', beforeSend: ()=>{
				$('#modal_form_identitas .modal-body').html('');
				blockUI('#modal_form_identitas .modal-body');
				$('#modal_identitas').modal('hide');
				$('#modal_form_identitas').modal('show');
			},error: (e)=>{
				setTimeout(function() {
					blockUI('#modal_form_identitas .modal-body',false);
					$('#modal_form_identitas').modal('hide');
					$('#modal_identitas').modal('hide');
					toast('error',e.status+ ': '+e.statusText);
				}, 500);
			}, success: (r)=>{
				$('#modal_form_identitas .modal-body').html(r);
				blockUI('#modal_form_identitas .modal-body',false);
			}
		});
	});
	var tbl_identitas;
	$(document).ready(()=>{
		var tbl = $('#datatable').DataTable({
			processing: true,
	        serverSide: true,
			stateSave: true,
			order : [[3,'DESC']],
	        ajax: { url: '<?=site_url('pelayanan')?>', type: 'POST', data: (data)=>{data.sts=$('#slc_datatable').val();return data;}},
	        columns: [
	        	{data: 'kode',},
	        	{data: 'layanan',},
	        	{data: 'pelapor'},
	        	{data: 'created_at'},
	        	{data: 'status', searchable: false, className: 'text-center'},
	        	{data: 'act', searchable: false, orderable: false, className: 'text-center'},
	        ]
		});
		$('#datatable_filter').append('<select type="search" id="slc_datatable" class="form-control form-control-sm input-sm slc_datatable" aria-controls="datatable"><option value="">Semua Status</option><?=$options_sts?></select>');
		$('#slc_layanan').on('change',function(){
			$('#btn_buat_layanan').prop('href',this.value);
		});
		$('#slc_datatable').on('change',function(){
			tbl.ajax.reload();
		});
		tbl_identitas = $('#datatable_identitas').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: { url: '<?=site_url('data-identitas/table')?>', type: 'POST' },
	        columns: [
	        	{data: 'no_identitas',},
	        	{data: 'nama',},
				{data: 'tempat_lahir',},
				{data: 'tgl_lahir',},
	        	{data: 'act', searchable: false, orderable: false, className: 'text-center'},
	        ]
		});
		$('.date_range_filter').daterangepicker({
            ranges   : {
                '7 Hari Terakhir' : [moment().subtract(6, 'days'), moment()],
                'Triwulan I (<?=date('Y')?>)' : tw1,
                'Triwulan II (<?=date('Y')?>)' : tw2,
                'Triwulan III (<?=date('Y')?>)' : tw3,
                'Triwulan IV (<?=date('Y')?>)' : tw4,
                'Bulan ini'  : [moment().startOf('month'), moment().endOf('month')],
                'Bulan sebelumnya'  : [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
            },
            startDate: moment().startOf('month'),
            endDate  : moment().endOf('month'),
            locale: {format: 'DD MMMM YYYY',}
        },function (start, end) {});
		$('.slc_pl').select2({
			theme: 'bootstrap4',
			placeholder: "Semua Pelayanan",
			allowClear: true,
			ajax: { url: '<?=site_url('api/get-layanan')?>', type:'POST',dataType: 'json', data: function(p){return {s:p.term};}, }
		});
	});
</script>
