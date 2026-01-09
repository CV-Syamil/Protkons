<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.css">
<script src="<?=base_url('style/lte')?>/plugins/moment/moment.min.js"></script>
<script src="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.js"></script>
<style>
    .datatable th, .datatable td{text-transform:uppercase;}
</style>
<div class="card">
    <div class="card-header"><h4 class="card-title">Laporan Pelapor</h4></div>
    <div class="card-body">
        <form action="<?=site_url('laporan/pelapor/cetak')?>" id="form_laporan" target="_blank" method="POST">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="text" class="form-control date_range_filter">
                        <input type="hidden" name="tgl_s" id="tgl_s">
                        <input type="hidden" name="tgl_e" id="tgl_e">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pelayanan</label>
                        <select name="pl" id="slc_pl" class="form-control">
                            <option value="">Semua Pelayanan</option>
                            <?php foreach($pl as $v){ echo "<option value=\"$v->pelayanan_id\">$v->kode_layanan - $v->pelayanan</option>"; } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select class="form-control" name="jk" id="slc_jk">
                            <option value="">Semua Jenis Kelamin</option>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="form-group">
                        <label>Kewarganegaraan</label>
                        <select class="form-control" name="wn" id="slc_wn">
                            <option value="">Semua Kewarganegaraan</option>
                            <option value="wni">WNI</option>
                            <option value="wna">WNA</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Wilayah</label>
                        <input type="hidden" name="kota" id="tkota">
                        <div class="input-group">
                            <div class="form-control" id="txt-w">Semua Wilayah</div>
                            <div class="input-group-append">
                                <div class="input-group-btn">
                                    <button type="button" data-toggle="modal" data-target="#modal_wilyah" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6"> <button class="btn btn-primary btn-block" type="button" id="btn_filter"><i class="fas fa-filter"></i>&nbsp;&nbsp;&nbsp;Filter</button> </div>
                <div class="col-6"> <button class="btn btn-success btn-block" type="submit" id="btn_export"><i class="fas fa-file-excel"></i>&nbsp;&nbsp;&nbsp;Export</button> </div>
            </div>
        </form>
        <div class="mt-3">
            <table width="100%" id="datatable" class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>ID PELAPOR</th>
                        <th>NAMA PELAPOR</th>
                        <th>ID PELAYANAN</th>
                        <th>PELAYANAN</th>
                        <th>PROVINSI</th>
                        <th>KOTA</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_wilyah">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Data Wialayah</h5>
				<button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#modal_identitas" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
                <div class="form-group">
                    <input type="search" placeholder="Nama Kota / Kabupaten" class="form-control" id="inp_cari_w">
                    <span class="small text-warning">Tekan enter untuk mencari</span>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <label>Semua Wilayah</label>
                            </div>
                            <div class="col-4">
                                <div class="text-right"><button type="button" data-kota="all" data-prov="all" class="btn btn-sm btn-success btn-pilih-w">Pilih</button></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="res_w"></div>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>

<script>
    var tbl;
    var tgl_s = moment().startOf('month');
    var tgl_e = moment().endOf('month');
    var tw1 = [moment('<?=date('Y')?>-01-01').startOf('month'),moment('<?=date('Y')?>-03-01').endOf('month')];
    var tw2 = [moment('<?=date('Y')?>-04-01').startOf('month'),moment('<?=date('Y')?>-06-01').endOf('month')];
    var tw3 = [moment('<?=date('Y')?>-07-01').startOf('month'),moment('<?=date('Y')?>-09-01').endOf('month')];
    var tw4 = [moment('<?=date('Y')?>-10-01').startOf('month'),moment('<?=date('Y')?>-12-01').endOf('month')];
    var kota = "";

	$(document).ready(()=>{
        setTglx(tgl_s,tgl_e);
		tbl = $('#datatable').DataTable({
			processing: false,
	        serverSide: true,
			searching: false,
			lengthChange: false,
	        ajax: {
                type: 'POST', beforeSend:()=>blockUI('#datatable_wrapper'), 
				complete:()=>blockUI('#datatable_wrapper', false), 
                data: (data)=>{
                    data.tgl_s=$('#tgl_s').val();
                    data.tgl_e=$('#tgl_e').val();
                    data.pl=$('#slc_pl').val();
                    data.jk=$('#slc_jk').val();
                    data.kota=$('#tkota').val();
                    data.wn=$('#slc_wn').val();
                    return data;
                }
            },
            columns: [
                {data: 'no_pelapor',searchable: false},
                {data: 'nm_pelapor',searchable: false},
                {data: 'kode_pl',searchable: false,orderable: false,},
                {data: 'pelayanan',searchable: false},
                {data: 'provinsi',searchable: false},
                {data: 'kota',searchable: false},
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
            startDate: tgl_s,
            endDate  : tgl_e,
            locale: {format: 'DD MMMM YYYY',}
        },setTglx);
        $('#btn_filter').on('click',()=>{
            tbl.ajax.reload();
        });
        $('#inp_cari_w').on('change',function(e){
            var v = this.value;
            $.ajax({
                url: '<?=site_url("api/get_wilayah")?>', type: 'POST', data:{s:v}, dataType:'JSON',
                beforeSend:()=>blockUI('#modal_wilyah .modal-body'),
                complete:()=>blockUI('#modal_wilyah .modal-body',false),
                success:(r)=>{
                    if(r&&r.length>0){
                        let s = "";
                        r.forEach(d => {
                            s+=`<div class="card">
                                <div class="card-body">
                                    <div class="text-bold">${d.kota}</div>
                                    <div><i>PROVINSI ${d.prov}</i></div>
                                    <div class="text-right"><button type="button" data-kota="${d.kota}" data-prov="${d.prov}" class="btn btn-sm btn-success btn-pilih-w">Pilih</button></div>
                                </div>
                            </div>`;
                        });
                        $('#res_w').html(s);
                    }else{
                        $('#res_w').html('<center><label>-- Data Tidak ditemukan --</label></center>')
                    }
                }
            });
        });
        $('#modal_wilyah').on('click','.btn-pilih-w',function(e){
            e.preventDefault();
            var p = $(this).data('prov');
            var k = $(this).data('kota');
            if(k=='all'){
                $('#tkota').val('');
                $('#txt-w').html('Semua Wilayah');
            }else{
                $('#tkota').val(k);
                $('#txt-w').html(`PROVINSI ${p} - ${k}`);
            }
            $('.modal').modal('hide');
        });
    });
    function setTglx(s,e){
        $('#tgl_s').val(s.format('YYYY-MM-DD'));
        $('#tgl_e').val(e.format('YYYY-MM-DD'));
    }
</script>