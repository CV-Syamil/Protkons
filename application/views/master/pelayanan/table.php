<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title">Data Master Pelayanan</h3>
		<div class="card-tools">
			<button type="button" onclick="$('#form_pelayanan')[0].reset();" data-toggle="modal" data-target="#modal_form_pelayanan" class="btn btn-tool bg-success"><i class="fa fa-plus"></i> Tambah Data</button>
			<button type="button" data-toggle="modal" data-target="#modal_kode" class="btn btn-tool bg-purple"><i class="fa fa-file-code"></i> Kode Report</button>
		</div>
	</div>
	<div class="card-body">
		<?php if(can_access('su')){ ?>
			<div class="text-right mb-3">
				<div class="input-group">
					<div class="input-group-prepend"><span class="input-group-text">Fungsi</span></div>
					<select id="slc_fungsi" class="form-control">
						<?php foreach($fungsi as $fs){ echo "<option value=\"$fs->id\">$fs->nama</option>"; } ?>
					</select>
				</div>
			</div>
		<?php } ?>
		<table width="100%" class="table table-bordered table-striped table-hover" id="datatable">
			<thead>
				<tr>
					<th width="125">Kode Pelayanan</th>
					<th>Nama Pelayanan</th>
					<th>Biaya</th>
					<th width="100">Template</th>
					<th width="100">Actions</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<div class="modal fade" id="modal_form_pelayanan">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Master Pelayanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<?=form_open('master/pelayanan/simpan-data',['id'=>'form_pelayanan']);?>
			<input type="hidden" name="ref" id="form_ref" value="">
			<?php if(can_access('su')){ ?>
				<div class="form-group">
					<label>Fungsi</label>
					<select name="slc_fungsi" class="form-control" required>
						<?php foreach($fungsi as $fs){ echo "<option value=\"$fs->id\">$fs->nama</option>"; } ?>
					</select>
				</div>
			<?php } ?>
			<div class="form-group">
				<label>Kode Pelayanan <span class="text-red">*</span></label>
				<input type="text" minlength="1" name="kode_layanan" id="form_kode" class="form-control" placeholder="Kode Pelayanan" required>
			</div>
			<div class="form-group">
				<label>Nama Pelayanan <span class="text-red">*</span></label>
				<input type="text" minlength="3" name="nama" id="form_nama" class="form-control" placeholder="Nama Pelayanan" required>
			</div>
			<div class="form-group">
				<label>Biaya Pelayanan <span class="text-red">*</span></label>
				<input type="number" min="0" name="biaya" id="form_biaya" class="form-control" placeholder="Biaya Pelayanan" required>
			</div>
			<div class="form-group">
				<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-primary">
					<input style="cursor: pointer;" type="checkbox" name="show_jml" class="custom-control-input" id="sw_show_jml">
					<label style="cursor: pointer;" class="custom-control-label" for="sw_show_jml"> Show Kolom Jumlah</label>
				</div>
			</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
        <button type="submit" form="form_pelayanan" class="btn btn-primary">Simpan Data</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="modal fade" id="modal_kode" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Daftar Report Code</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="fa fa-search"></i></span>
				</div>
				<input type="search" id="cari_kode_x" placeholder="Cari Kode" class="form-control">
			</div>
		</div>
      	<div class="table-responsive">
      		<table class="table table-bordered table-striped table-hover">
      			<thead>
      				<tr>
      					<th>Kode</th>
      					<th>Keterangan</th>
      				</tr>
      			</thead>
      			<tbody id="kode_x">
      				<?php foreach(phpword_auto_items() as $key => $item){ 
						switch ($key) {
							case 'kode_tr':echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><label>Kode Transaksi Pelayanan</label></td></tr>';break;
							case 'nip_hs':echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><label>Kode Pejabat Penandatangan / HS</label></td></tr>';break;
							case 'no_identitas_pelapor':echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><label>Kode Data Pelapor</label></td></tr>';break;
							case 'th_num_long':echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><label>Auto Kode</label></td></tr>';break;
							case 'biaya_doc':echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><label>Hanya Template Kwitansi</label></td></tr>';break;
							default:break;
						}   ?>
      					<tr>
      						<td>
								<button type="button" class="btn btn-default btn-copy" data-clipboard-text="${<?=$key?>}">${<?=$key?>}</button>
      						</td>
      						<td><?=$item['label']?></td>
      					</tr>
      				<?php } ?>
      			</tbody>
      		</table>
      	</div>
      </div>
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
	        ajax: { type: 'POST', data:(d)=>{ d.slcfungsi = $('#slc_fungsi').val()??''; return d; }},
	        columns: [
	        	{data: 'kode_layanan', className: 'text-center'},
	        	{data: 'pelayanan'},
	        	{data: 'biaya', className: 'text-right'},
	        	{data: 'template_file', searchable: false, orderable: false, className: 'text-center'},
	        	{data: 'act', searchable: false, orderable: false, className: 'text-center'},
	        ]
		});
		$('#slc_fungsi').on('change',()=>tbl.ajax.reload());

		var clipboard = new ClipboardJS('.btn-copy');
		clipboard.on('success', function(e) {
			toast('success',e.text,'Copied!!',1000);
		  e.clearSelection();
		});

		clipboard.on('error', function(e) {
				toast('error',e.text,'Copy Error!!',1000);
		});
		$("#cari_kode_x").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#kode_x tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	});
</script>