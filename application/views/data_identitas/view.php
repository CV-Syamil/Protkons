<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?=base_url('style/lte')?>/plugins/select2/js/select2.full.min.js"></script>
<style type="text/css">
	.slc_datatable{width: 150px !important; display: inline-block; margin-left: 5px;}
	.table tbody td{text-transform:uppercase;}
</style>
<div class="card">
	<div class="card-header">
		<h3 class="card-title">
			<a href="<?=back_link(site_url('pelayanan'))?>" title="kembali"><i class="fa fa-arrow-left"></i></a>&nbsp;
			Detail Identitas
		</h3>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover" style="text-transform:uppercase;">
				<tr>
					<td width="200">Jenis Identitas</td>
					<td width="2">:</td>
					<td><?=$data->jenis_identitas?></td>
				</tr>
				<tr>
					<td>No. Identitas</td>
					<td>:</td>
					<td><?=$data->no_identitas.((empty($history)?'':'&nbsp;&nbsp;&nbsp;<u><a href="#" data-toggle="modal" data-target="#modal_history" class="small"><i>history</i></a></u>'))?></td>
				</tr>
				<tr>
					<td>Nama Lengkap</td>
					<td>:</td>
					<td><?=$data->nama?></td>
				</tr>
				<tr>
					<td>Jenis Kelamin</td>
					<td>:</td>
					<td><?=$data->jk?></td>
				</tr>
				<tr>
					<td>Tempat, Tanggal Lahir</td>
					<td>:</td>
					<td><?=$data->tempat_lahir.', '.tanggal_indo($data->tgl_lahir)?></td>
				</tr>
				<tr>
					<td>No.Telepon</td>
					<td>:</td>
					<td><?=$data->no_telp?></td>
				</tr>
				<tr>
					<td>Agama</td>
					<td>:</td>
					<td><?=$data->agama?></td>
				</tr>
				<tr>
					<td>Pekerjaan</td>
					<td>:</td>
					<td><?=$data->pekerjaan?></td>
				</tr>
				<tr>
					<td>Kewarganegaraan</td>
					<td>:</td>
					<td><?=$data->kewarganegaraan?></td>
				</tr>
				<tr>
					<td>Provinsi</td>
					<td>:</td>
					<td><?=strtoupper($data->provinsi)?></td>
				</tr>
				<tr>
					<td>Kota / Kabupaten</td>
					<td>:</td>
					<td><?=strtoupper($data->kota)?></td>
				</tr>
				<tr>
					<td>Kecamatan</td>
					<td>:</td>
					<td><?=strtoupper($data->kecamatan)?></td>
				</tr>
				<tr>
					<td>Desa</td>
					<td>:</td>
					<td><?=strtoupper($data->desa)?></td>
				</tr>
				<tr>
					<td>Alamat Lengkap</td>
					<td>:</td>
					<td><?=$data->alamat_idn?></td>
				</tr>
				<tr><td colspan="3">&nbsp;</td></tr>
				<tr>
					<td>Negeri</td>
					<td>:</td>
					<td><?=strtoupper($data->negeri)?></td>
				</tr>
				<tr>
					<td>Daerah</td>
					<td>:</td>
					<td><?=strtoupper($data->daerah)?></td>
				</tr>
				<tr>
					<td>Distrik</td>
					<td>:</td>
					<td><?=strtoupper($data->distrik)?></td>
				</tr>
				<tr>
					<td>Alamat Lengkap</td>
					<td>:</td>
					<td><?=$data->alamat_mys?></td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h3 class="card-title">Data Pelayanan</h3>
		<div class="card-tools">
			<?php if(can_access(['loket','verifikasi','kasir'])){ ?>
				<button type="button" data-toggle="modal" data-target="#modal_layanan" class="btn bg-success btn-tool">Buat Pelayanan</button>
			<?php } ?>
		</div>
	</div>
	<div class="card-body">
		<table width="100%" id="datatable" class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th>ID Pelayanan</th>
					<th>No Dokumen</th>
					<th>Layanan</th>
					<th>Nama Pelapor</th>
					<th>Tanggal</th>
					<th>Status</th>
					<th width="150">Actions</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="modal fade" id="modal_layanan">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pilih Layanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="modal_form_body">
      	<div class="form-group">
      		<select class="form-control select2" style="width: 100%;" id="slc_layanan">
      			<option value="#">-- Pilih Layanan --</option>
      			<?php foreach ($pl as $v) {
      				$params = implode('/', [$data->id,$v->pelayanan_id,url_title($v->pelayanan)]);
      				echo '<option value="'.site_url('pelayanan/buat-layanan/'.$params).'">'.$v->pelayanan.'</option>';
      			} ?>
      		</select>
      	</div>
      	<div class="form-group">
      		<a href="#" id="btn_buat_layanan" class="btn btn-block btn-success"><i class="fa fa-edit"></i> Buat Layanan</a>
      	</div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_history">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Riwayat Perubahan No Identitas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      	<table class="table table-bordered table-striped" id="datatable_history">
			  <thead>
				  <tr>
					  <th>No Identitas</th>
					  <th>Tanggal</th>
				  </tr>
			  </thead>
			  <tbody>
				  <?php foreach ($history as $v) {
					$ec='';
					$ec.="<td>$v->no_identitas</td>";
					$ec.="<td>".tanggal_indo($v->created_at)."</td>";
					echo "<tr>$ec</tr>";
				  } ?>
			  </tbody>
		  </table>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
	$(document).ready(()=>{
		$('#datatable_history').DataTable();
		var tbl = $('#datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: {type: 'POST', url: '<?=site_url('pelayanan')?>',
	        data: (data)=>{data.sts=$('#slc_datatable').val(); data.pelapor='<?=$data->id?>'; return data;}},
	        columns: [
	        	{data: 'kode',},
	        	{data: 'no_dokumen',},
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
		$('.select2').select2({
	      theme: 'bootstrap4'
	    });
	});
</script>