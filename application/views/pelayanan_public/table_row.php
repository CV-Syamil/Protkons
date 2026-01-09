<?php foreach($data->data as $v){ ?>
    <tr>
        <td><?=$v->pelayanan_nama?></td>
        <td><?=$v->pelapor->nama?></td>
        <td><?=date('d F Y',strtotime($v->created_at))?></td>
        <td><?=date('d F Y',strtotime($v->tgl_ambil))?></td>
        <td>
            <?php
                switch($v->status){
                    case 1: echo '<span class="badge bg-primary">Menunggu Verifikasi</span>'; break;
                    case 2: echo '<span class="badge bg-success">DiTerima</span>'; break;
                    case 9: echo '<span class="badge bg-danger">DiTolak</span>'; break;
                }
            ?>
        </td>
        <td align="center">
            <button type="button" class="btn btn-sm btn-info showOnModal" title="Detail Pelayanan" data-href="<?=site_url('pelayanan-publik/detail/'.$v->id)?>"><i class="fa fa-eye"></i></button>
        </td>
    </tr>
<?php } ?>