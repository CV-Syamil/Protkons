<label>Kolom Isian</label>
<div id="accordion-x">
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title w-100">
                <a href="#wil_id" class="d-block w-100" data-toggle="collapse">Wilayah Indonesia</a>
            </h3>
        </div>
        <div class="collapse" id="wil_id" data-parent="#accordion-x">
            <div class="card-body">
                <form class="form_wil">
                    <input type="hidden" name="t" value="wil_id">
                    <div class="input-group mb-3">
                        <input type="search" placeholder="Pencarian Nama Provinsi/Kota/Kabupaten/Kecamatan" name="s" class="form-control" required>
                        <div class="input-group-append"><button class="btn btn-default"><i class="fa fa-search"></i></button></div>
                    </div>
                </form>
                <div id="res_wil_id"></div>
            </div>
        </div>
    </div>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title w-100">
                <a href="#wil_my" class="d-block w-100" data-toggle="collapse">Wilayah Malaysia</a>
            </h3>
        </div>
        <div class="collapse" id="wil_my" data-parent="#accordion-x">
            <div class="card-body">
                <form class="form_wil">
                    <input type="hidden" name="t" value="wil_my">
                    <div class="input-group mb-3">
                        <input type="search" placeholder="Pencarian Nama Negeri/Daerah/Distrik" name="s" class="form-control" required>
                        <div class="input-group-append"><button class="btn btn-default"><i class="fa fa-search"></i></button></div>
                    </div>
                </form>
                <div id="res_wil_my"></div>
            </div>
        </div>
    </div>
    <?php foreach($field as $key => $v){ $idname = $v->field_name.$key; ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title w-100">
                    <a href="#<?=$idname?>" class="d-block w-100" data-toggle="collapse"><?="$v->label ($v->field_name)"?></a>
                </h3>
            </div>
            <div class="collapse" id="<?=$idname?>" data-parent="#accordion-x">
                <div class="card-body">
                    <?php if(!empty($v->notes)){ echo "<p>$v->notes</p>"; } ?>
                    <label>Pilihan Isian</label>
                    <ul>
                        <?php if(!empty($v->data)){ foreach (explode('||',$v->data) as $key => $value) { echo "<li>$value</li>"; } } ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    $('.form_wil').on('submit', function(e){
        e.preventDefault();
        var f = this;
        $.ajax({
            url: '<?=site_url('import_pelayanan/getwilayah')?>', type: 'POST', data:$(f).serializeArray(), dataType:'JSON',
            beforeSend:()=>blockUI(f),
            complete:()=>blockUI(f,false),
            success:(r)=>{
                var str = "";
                var l1="",l2="",l3="";
                if(r.t=='wil_id'){ l1 = "Provinsi"; l2 = "Kota/Kabupaten"; l3 = "Kecamatan"; }
                if(r.t=='wil_my'){ l1 = "Negeri"; l2 = "Daerah"; l3 = "Distrik"; }
                r.data.forEach(d=>{
                    str+=`<div class="card my-2">
                        <div class="card-body px-3 py-2">
                            <div>${l1} : <b>${d.v1}</b></div>
                            <div>${l2} : <b>${d.v2}</b></div>
                            <div>${l3} : <b>${d.v3}</b></div>
                        </div>
                    </div>`;
                });
                $(`#res_${r.t}`).html(str);
                console.log(r);
            }
        });
    });
</script>