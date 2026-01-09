<div class="card card-solid">
    <div class="card-header">
        <h3 class="card-title">Data Identitas (MAIN SERVER)</h3>
    </div>
    <div class="card-body">
        <form action="" id="form-cari">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" name="cari" placeholder="Cari berdasarkan NO IDENTITAS atau NAMA" class="form-control" minlength="3" required>
                    <div class="input-group-append">
                        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </form>
        <table width="100%" class="table table-striped table-hover table-bordered" id="table-x">
			<thead>
                <tr>
                    <th>No Identitas</th>
                    <th>Nama</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th width="100">Actions</th>
                </tr>
			</thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-center font-italic">-- no data --</td></tr></tbody>
		</table>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
<script>
    var dataId;
    $('#form-cari').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            url: '<?=site_url("server_master/identitas/data")?>', type: 'POST', data: $(this).serializeArray(), dataType: 'JSON',
            beforeSend:()=>blockUI('.card'), complete:()=>blockUI('.card',false),
            error:(e)=>toast('error',r.statusText,'Error '+e.status),
            success:(r)=>{
                var html=`<tr><td colspan="6" class="text-center font-italic">-- no data --</td></tr>`;
                if(r.length>0){
                    html="";
                    dataId = r;
                    r.forEach((d,i)=>{
                        var tgl  = moment(d.tgl_lahir).lang('id').format('DD MMMM YYYY');
                        html+= `<tr>
                                <td>${d.no_identitas}</td>
                                <td>${d.nama}</td>
                                <td>${d.tempat_lahir}</td>
                                <td>${tgl}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-success btn-add" data-index="${i}"><i class="fa fa-plus"></i> Tambah/Update</button>
                                </td>
                            </tr>`;
                    });
                }
                $('#tbody').html(html);
            }
        });
    });
    $('#table-x').on('click','.btn-add', function(e){
        e.preventDefault();
        var data = dataId[parseInt($(this).data('index'))];
        $.ajax({
            url: '<?=site_url("server_master/identitas/tambah")?>', type: 'POST', data: data, dataType: 'JSON',
            beforeSend:()=>blockUI('.card'), complete:()=>blockUI('.card',false),
            error:(e)=>toast('error',r.statusText,'Error '+e.status),
            success:(r)=>{
                if(r.status==200){
                    toast('success',r.message,'BERHASIL');
                }else{
                    toast('error',r.message,'GAGAL');
                }
            }
        });
    });
</script>