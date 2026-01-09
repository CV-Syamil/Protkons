<div class="card card-solid">
	<div class="card-header with-border">
		<h3 class="card-title mt-1">Notifikasi</h3>
        <div class="card-tools">
            <button type="button" id="btnread" data-href="<?=site_url('notifikasi/read_all')?>" class="btn btn-tools btn-sm btn-info"><i class="fa fa-eye"></i> Read All</button>
        </div>
	</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <?php foreach($data as $v){ ?>
                        <tr>
                            <td>
                                <div class="font-weight-bold">
                                    <a class="<?=($v->has_read==0)?'':'text-muted'?>" href="<?=(empty($v->href)&&$v->has_read==1)?'#read_notif':site_url('notifikasi/baca/'.$v->id.'/notifikasi')?>"> <?=$v->title?> </a>
                                </div>
                                <div class="text-muted"><?=$v->message?></div>
                            </td>
                            <td class="text-right text-muted"><?=date('d F Y | H:i',strtotime($v->waktu))?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $('#btnread').on('click', function(){
        var href = $(this).data('href');
        Swal.fire({
            title: 'Baca Semua Notifikasi',
            text: "Memperbarui status semua notifikasi telah terbaca.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'OK',
            showLoaderOnConfirm: true,
            preConfirm: ()=>{
                return new Promise(function(resolve, reject) {
                    window.location = href;
                });
            },allowOutsideClick:false
        }).then((result)=>{ });
    });
</script>