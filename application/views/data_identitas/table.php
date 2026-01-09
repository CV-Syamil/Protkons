<div class="card card-solid">
    <div class="card-header">
        <h3 class="card-title">Data Identitas</h3>
    </div>
    <div class="card-body">
        <table width="100%" id="datatable" class="table table-striped table-hover table-bordered">
			<thead>
                <tr>
                    <td>No Identitas</td>
                    <td>Nama</td>
                    <td>Tempat Lahir</td>
                    <td>Tanggal Lahir</td>
                    <td>Riwayat Pelayanan</td>
                    <td width="150">Actions</td>
                </tr>
			</thead>
		</table>
    </div>
</div>
<script>
    var tbl;
    $(document).ready(()=>{
        tbl = $('#datatable').DataTable({
			processing: true,
	        serverSide: true,
	        ajax: { type: 'POST' },
	        columns: [
	        	{data: 'no_identitas',},
	        	{data: 'nama',},
	        	{data: 'tempat_lahir',},
	        	{data: 'tgl_lahir',},
	        	{data: 'history_pl', searchable: false, orderable: false},
	        	{data: 'act', searchable: false, orderable: false, className: 'text-center'},
	        ]
		});
    });
</script>