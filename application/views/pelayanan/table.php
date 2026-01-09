<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?=base_url('style/lte')?>/plugins/select2/js/select2.full.min.js"></script>
<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.css">
<script src="<?=base_url('style/lte')?>/plugins/moment/moment.min.js"></script>
<script src="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?=base_url('style/jcombo/jquery.jCombo.min.js')?>"></script>

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
		<h3 class="card-title">Data Pelayanan</h3>
		<div class="card-tools">
			<?php if(can_access(['loket','verifikasi','kasir'])){ ?>
				<button type="button" data-toggle="modal" data-target="#modal_identitas" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Buat Pelayanan</button>
			<?php } ?>
		</div>
	</div>
	<div class="card-body">
		<div class="row mb-3" id="filter_xxxx">
			<div class="col-md-6">
				<div class="form-group">
					<div class="dropdown">
						<label class="text-success" style="cursor:pointer" title="click untuk mengubah tipe" id="lbl_tgl_x" data-toggle="dropdown">Tanggal diBuat <i class="fa fa-caret-down"></i></label>
						<ul class="dropdown-menu ddfx" style="padding: 5px 10px;">
							<li><a href="#" onclick="setTypeTgl('created_at');return false;" class="text-black">Tanggal diBuat</a></li>
							<li><a href="#" onclick="setTypeTgl('updated_at');return false;" class="text-black">Tanggal Update</a></li>
						</ul>
					</div>
					<input type="hidden" name="tgl_type" id="tgl_type" value="created_at">
					<input type="text" class="form-control" id="tgl_range" readonly>

					<input type="hidden" class="form-control" id="tgl" value="">
					<input type="hidden" class="form-control" id="tgl2" value="">
				</div>
			</div>
			<?php if(can_access('su')){ ?>
				<div class="col-md-6">
					<div class="form-group">
						<label>Fungsi</label>
						<select id="slc_fungsi" class="form-control">
							<?php foreach($fungsi as $fs){ echo "<option value=\"$fs->id\">$fs->nama</option>"; } ?>
						</select>
					</div>
				</div>
			<?php } ?>
			<div class="col-md-6">
				<div class="form-group">
					<label>ID Pelayanan</label>
					<input type="search" id="kode_layanan" class="form-control">
				</div>
			</div>
			<div class="<?=can_access('su')?'col-md-6':'col-md-12'?>">
				<div class="form-group">
					<label>Pelayanan</label>
					<select id="slc_pl" class="form-control"></select>
				</div>
			</div>
			<div class="col-md-12" id="field_slc_pl">
				<label>Item Pelayanan</label>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Pelayanan</label>
							<select name="field_pl" class="form-control" id="slc_field_pl"></select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Value</label>
							<input type="search" name="value_pl" class="form-control" id="value_field_pl" placeholder="Pencarian">
						</div>
					</div>
				</div>
				<hr>
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
					<select id="slc_sts" class="form-control"><option value="">Semua Status</option><?=$options_sts?></select>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="input-group mb-2">
					<div class="input-group-prepend"><span class="input-group-text">Show</span></div>
					<select id="slc_length_dt" class="form-control"><?php foreach([10,20,50,100] as $v){ echo "<option value=\"$v\">$v</option>"; } ?></select>
					<div class="input-group-append"><span class="input-group-text">Entries</span></div>
				</div>
			</div>
			<div class="col-sm-6 col-md-9">
				<button id="filter_btn" class="btn btn-block btn-primary mb-2">Filter Data</button>
			</div>
		</div>
		
		<table width="100%" id="datatable" class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th>ID Pelayanan</th>
					<th>No. Dokumen</th>
					<th>Layanan</th>
					<th>Nama Pelapor</th>
					<th>Home Staff</th>
					<th>Tgl. dibuat</th>
					<th>Tgl. Update</th>
					<th>Status</th>
					<th width="150">Actions</th>
				</tr>
			</thead>
		</table>
	</div>
	<div class="card-footer">
		<label>Keterangan</label>
		<div class="row">
			<?php foreach (btn_status_layanan() as $v) { if(can_access($v[3])){?>
				<div class="col-md-3 col-sm-4 col-xs-6 pb-3"><button type="button" class="btn btn-sm <?=$v[0];?>"><i class="<?=$v[1];?>"></i></button>&nbsp;:&nbsp;<?=$v[2];?></div>
			<?php } } ?>
		</div>
	</div>
</div>

<?php if(can_access(['loket','verifikasi','kasir'])){ ?>
	<div class="modal fade" id="modal_identitas" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Pilih Data Pelapor</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body" id="modal_form_body">
			<div class="mb-3 text-right">
				<button class="btn btn-primary btn-sm" data-href="<?=site_url('data-identitas/tambah-data')?>" id="modal_add_identitas" type="button">Tambah Data</button>
			</div>
			<table class="table table-bordered table-striped table-hover" width="100%" id="datatable_identitas">
				<thead>
					<tr>
						<td>No Identitas</td>
						<td>Nama</td>
						<td>Tempat Lahir</td>
						<td>Tanggal Lahir</td>
						<td>Riwayat Pelayanan</td>
						<td width="50">Actions</td>
					</tr>
				</thead>
			</table>
		</div>
		<div class="modal-footer" align="right">
			<button class="btn btn-default" data-dismiss="modal" type="button">Batal</button>
		</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<div class="modal fade" id="modal_form_identitas" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Form Identitas</h5>
			<button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#modal_identitas" aria-label="Close">
			<span aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body" style="min-height: 200px;"></div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
<?php } ?>

<div class="modal fade" id="modal_kembalikan">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Kembalikan Data ke Verifikasi ?</h5>
				<button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#modal_identitas" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body p-0">
				<form action="" id="form_kembalikan" method="POST">
					<textarea name="alasan" class="form-control" placeholder="Alasan Pengembalian" rows="5" min="5" required></textarea>
				</form>
			</div>
			<div class="modal-footer" align="right">
				<button class="btn btn-default" data-dismiss="modal" type="button">Batal</button>
				<button class="btn btn-primary" form="form_kembalikan" type="submit">Kembalikan</button>
			</div>
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
	var ajax_form_identitas;

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
	// setTglFx(tgl1,tgl2);
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
	$('#datatable').on('click', '.btn-export-file', function(e){
		e.preventDefault();
		var href = $(this).prop('href');
		Swal.fire({
			title: "Tanggal Bukti Pengambilan",
			// input: 'text',
			html: '<input type="text" id="swalDate" class="form-control form-control-lg" readonly/>',
			showCancelButton: true,
			confirmButtonText: 'Export',
			showLoaderOnConfirm: true,
			backdrop: true,
			allowOutsideClick: () => !Swal.isLoading(),
			preConfirm: ()=>{
				var val = $('#swalDate').val();
				if(val){
					window.location = href+'?tgl='+val;
					return '';
				}else{
					Swal.showValidationMessage('Tanggal tidak valid.');
				}
			},
			didOpen: function() {
				setDate(moment());
				$('#swalDate').daterangepicker({
					autoUpdateInput: false,
					singleDatePicker: true,
					timePicker:true,
					timePicker24Hour: true,
					timePickerSeconds: false,
					timePickerIncrement: 15,
					startDate: moment(),
					endDate: moment(),
					minDate:moment(), 
					locale: {format: 'DD MMMM YYYY HH:mm',}
				},function (start, end) { setDate(start); });
			}
		});
	});
	function setDate(t){
		let month = <?=json_encode(bulan_id())?>;
		let day = t.date();
		let bl = month[t.month()];
		let th = t.year();
		let jam = t.hour();
		let menit = t.minute();
		jam = (jam<9)?('0'+jam):jam;
		menit = (menit<9)?('0'+menit):menit;
		// let tgl_str = ((day>9)?day:'0'+day)+' '+bl+' '+th+' '+((jam>9)?jam:'0'+jam)+':'+((menit>9)?menit:'0'+menit);
		let tgl_str = ((day>9)?day:'0'+day)+' '+bl+' '+th+' '+jam+':'+menit;
		$('#swalDate').val(tgl_str);
	}
	var tbl, tbl_identitas;
	var fsearch = {
		tgl : '#tgl',
		tgl2 : '#tgl2',
		tgl_type : '#tgl_type',
		kodepl : '#kode_layanan',
		pl : '#slc_pl',
		pelapor : '#slc_identitas',
		sts : '#slc_sts',
		item  :  '#field_slc_pl',
		plitem  :  '#slc_field_pl',
		plval  :  '#value_field_pl',
		fungsi  :  '#slc_fungsi',
	}
	var lcs={};
	function setfsearch(){
		var dt = {ds:'<?=date('Ymd')?>'};
		for(let k in fsearch){
			if(['pl','pelapor'].includes(k)){
				let dtslc = $(fsearch[k]).select2('data');
				if(dtslc&&dtslc.length>0){
					dtslc = dtslc[0];
					if(dtslc.id==(lcs.pl.id??'')){
						dtslc = lcs.pl;
					}
				}else{
					dtslc={};
				}
				dt[k] = dtslc;
			}else{
				dt[k] = $(fsearch[k]).val();
			}
		}
		localStorage.setItem('fsearch',JSON.stringify(dt));
	}
	function initfsearch(){
		var fsch = localStorage.getItem('fsearch');
		if(fsch){
			var dt = JSON.parse(fsch);
			if(dt instanceof Object){
				lcs = dt;
				for(let k in fsearch){
					let t = fsearch[k];
					let v = dt[k]??'';
					if(['pl','pelapor'].includes(k)&&v.id){
						$(t).html(`<option value="${v.id}">${v.text}</option>`);
						if(k=='pl'){
							setDataItemsPl(v.data_items);
							if(v.data_items){ $('#field_slc_pl').show('fade'); }
						}
					}else if(k=='tgl'||k=='tgl2'){
						let fnow = '<?=date('Ymd')?>';
						let val = '';
						if(dt.ds!=fnow||v==''){
							val = (k=='tgl2')?tgl2.format('YYYY-MM-DD'):tgl1.format('YYYY-MM-DD');
						}else{
							let t = moment(v);
							if(t.isValid()){
								if(k=='tgl2'){ tgl2=t; }
								else{ tgl1=t; }
								val = t.format('YYYY-MM-DD');
							}else{
								val = (k=='tgl2')?tgl2.format('YYYY-MM-DD'):tgl1.format('YYYY-MM-DD');
							}
						}
						$(t).val(val);
					} if(k=='tgl_type'){
						$(t).val(v);
						setTypeTgl(v);
					}else{
						$(t).val(v);
					}
				}
			}
		}
		$('#slc_length_dt').val(tbl.page.len());
		setTglFx(tgl1,tgl2);
		tbl.ajax.reload();
	}
	function setDataItemsPl(dt){
		var opt='<option value="">No. Filter</option>';
		$('#value_field_pl').val('');
		if(dt){
			dt.forEach(d => {
				opt+=`<option value="${d.field_name}">${d.label}</option>`;				
			});
			$('#slc_field_pl').html(opt);
			$('#field_slc_pl').show('fade');
		}else{
			$('#field_slc_pl').hide('fade');
		}
	}
	$(document).ready(()=>{
		tbl = $('#datatable').DataTable({
			processing: false,
	        serverSide: true,
			searching: false,
			lengthChange: false,
			stateSave:true,
			order : [[6,'DESC']],
            deferLoading: 0,
	        ajax: {type: 'POST', beforeSend:()=>blockUI('#datatable_wrapper'), 
			complete:()=>blockUI('#datatable_wrapper', false), data: (data)=>{
				for(var k in fsearch){ data[k] = $(fsearch[k]).val(); }
				return data;
			}},columns: [
	        	{data: 'kode',searchable: false,orderable: false,},
	        	{data: 'no_dokumen',searchable: false,orderable: false,},
	        	{data: 'layanan',searchable: false,orderable: false,},
	        	{data: 'nm_pelapor'},
	        	{data: 'nm_hs'},
	        	{data: 'created_at', searchable: false,orderable: true,},
	        	{data: 'updated_at', searchable: false,orderable: true,},
	        	{data: 'status', searchable: false, className: 'text-center'},
	        	{data: 'act', searchable: false, orderable: false, className: 'text-center'},
	        ]
		});
		$('#slc_layanan').on('change',function(){
			$('#btn_buat_layanan').prop('href',this.value);
		});
		$('.select2').select2({
			theme: 'bootstrap4'
		});
		$(document).on('click','.btn_kembalikan',function(){
			var href = $(this).data('href');
			$('#form_kembalikan').prop('action',href);
			
			$('#modal_kembalikan').modal('show');
		});
		tbl_identitas = $('#datatable_identitas').DataTable({
			processing: true,
	        serverSide: true,
			// deferLoading: 0,
	        ajax: { url: '<?=site_url('data-identitas/table')?>', type: 'POST' },
	        columns: [
	        	{data: 'no_identitas',},
	        	{data: 'nama',},
	        	{data: 'tempat_lahir',},
	        	{data: 'tgl_lahir',},
	        	{data: 'history_pl', searchable: false, orderable: false},
	        	{data: 'act', searchable: false, orderable: false, className: 'text-center'},
	        ]
		});
		$('#datatable_identitas_filter').html('<form id="form_filter" onsubmit="return false"><div class="input-group"> <div class="input-group-prepend"><span class="input-group-text">Search</span></div> <input type="search" class="form-control ml-0" required> <div class="input-group-append"><button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button></div> </div></form>');
		$('#form_filter').on('submit',function(){
			var inp = $(this).find('input');
			var s = inp.val().trim().trim();
			if(s){ tbl_identitas.search(s); tbl_identitas.ajax.reload(); }
			else{ inp.val(s); this.reportValidity(); }
		});
		$(document).on('click','.btn_file',function(){
			var cek = $(this).data('nodoc');
			if(cek<=0){
				setTimeout(() => {
					tbl.ajax.reload();
				}, 1500);
			}
		});
		$('#slc_pl').select2({
			theme: 'bootstrap4',
			placeholder: "Semua Pelayanan",
			allowClear: true,
			width: '100%',
			ajax: { url: '<?=site_url('api/get-layanan')?>', type:'POST',dataType: 'json', data: function(p){return {s:p.term,i:1};}, }
		}).on('change', function(){
			let dt = $(this).select2('data');
			if(dt&&dt.length>0){
				dt = dt[0].data_items;
				if(dt){ setDataItemsPl(dt); }
			}else{
				$('#field_slc_pl').hide('fade');
			}
		}).trigger('change');
		$('#slc_identitas').select2({
			theme: 'bootstrap4',
			placeholder: "Semua Pelapor",
			allowClear: true,
			width: '100%',
			ajax: { url: '<?=site_url('api/get-person')?>', type:'POST',dataType: 'json', data: function(p){return {s:p.term};}, }
		});
		$('#filter_btn').on('click', ()=>{
			var l = $('#slc_length_dt').val();
			setfsearch();
			tbl.page.len(l);
			tbl.ajax.reload();
		});
		$('#datatable').on('click','.btn-restore', function(e){
			e.preventDefault();
			var href = this.href;
			Swal.fire({
				title: 'Kembalikan data ?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Kembalikan Data',
				showLoaderOnConfirm: true,
				preConfirm: ()=>{
				return new Promise(function(resolve, reject) {
					$.ajax({
						url: href, type:'GET', dataType: 'JSON', error:(e)=>{resolve({status:e.status,message:e.statusText});},
						success:(r)=>{resolve(r);}
					});
				});
				},allowOutsideClick:false
			}).then((r)=>{
				if(r.isConfirmed&&r.value){
					if(r.value.status==200){
						Swal.fire('Success',r.value.message,'success');
						tbl.ajax.reload();
					}else{
						Swal.fire('Error',r.value.message,'error');
					}
				}
			});
		});
		$('#datatable').on('click','.del-permanen', function(e){
			e.preventDefault();
			var href = this.href;
			Swal.fire({
				title: 'Hapus Data ?',
				text: 'Data akan dihapus secara permanen dan tidak dapat dikembalikan',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Hapus Data',
				showLoaderOnConfirm: true,
				preConfirm: ()=>{
				return new Promise(function(resolve, reject) {
					$.ajax({
						url: href, type:'GET', dataType: 'JSON', error:(e)=>{resolve({status:e.status,message:e.statusText});},
						success:(r)=>{resolve(r);}
					});
				});
				},allowOutsideClick:false
			}).then((r)=>{
				if(r.isConfirmed&&r.value){
					if(r.value.status==200){
						Swal.fire('Success',r.value.message,'success');
						tbl.ajax.reload();
					}else{
						Swal.fire('Error',r.value.message,'error');
					}
				}
			});
		});

		initfsearch();
	});
	function setTglFx(s,e){
		tgl1 = s; tgl2 = e;
		$('#tgl_range').val(s.format('DD MMMM YYYY')+' - '+e.format('DD MMMM YYYY'));
		$('#tgl').val(s.format('YYYY-MM-DD'));
		$('#tgl2').val(e.format('YYYY-MM-DD'));
	}
	function setTypeTgl(t){
		t = (t!='updated_at')?'created_at':'updated_at';
		$('#tgl_type').val(t); 
		$('#lbl_tgl_x').html(((t=='updated_at')?'Tanggal Update':'Tanggal diBuat')+" <i class=\"fa fa-caret-down text-secondary\"></i>");
	}
</script>