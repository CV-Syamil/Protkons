<table class="table table-striped table-borderless">
    <tr>
        <td colspan="3" align="center">
            <img style="width:200px; height:200px" src="<?=$data->foto?>" class="img-responsive img-bordered img-circle">
        </td>
    </tr>
    <tr>
        <td width="150">Username</td>
        <td width="5">:</td>
        <td><?=$data->username?></td>
    </tr>
    <tr>
        <td>NIP</td>
        <td>:</td>
        <td><?=$data->nip?></td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>:</td>
        <td><?=$data->nama?></td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>:</td>
        <td><?=$data->jabatan.(empty($data->jabatan_en)?'':' / '.$data->jabatan_en.' (en)')?></td>
    </tr>
    <tr>
        <td>Fungsi</td>
        <td>:</td>
        <td><?=$data->nm_fungsi?></td>
    </tr>
    <tr>
        <td>Akses</td>
        <td>:</td>
        <td><?=user_akses($data->akses)?></td>
    </tr>
    <tr>
        <td>Akses Pelayanan</td>
        <td>:</td>
        <td>
            <?php
                if(in_array('all',$data->akses_pelayanan)){ echo "Semua Pelayanan"; }
                else{
                    $li="";
                    foreach ($layanan as $v) { $li.="<li>$v->pelayanan ($v->kode_layanan)</li>";}
                    echo "<ol style=\"padding-left:15px !important;\">$li</ol>";
                }
            ?>
        </td>
    </tr>
</table>