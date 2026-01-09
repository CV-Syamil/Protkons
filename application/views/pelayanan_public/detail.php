<div class="table-responsive">
    <table class="table table-sm table-borderless">
        <tbody>
            <?php if(!empty($pl->ref_protkons)){ ?>
                <tr>
                    <td width="150">ID Pelayanan</td>
                    <td width="20">:</td>
                    <td>
                        <a href="<?=site_url('pelayanan/detail/'.$pl->ref_protkons.'/'.$pl->pelapor->nama)?>" class="text-bold" ><?=$pl->ref_protkons?></a>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td width="150">Pelayanan</td>
                <td width="20">:</td>
                <td><?=$pl->pelayanan_nama?></td>
            </tr>
            <tr>
                <td>Created At</td>
                <td>:</td>
                <td><?=date('d F Y H:i',strtotime($pl->created_at))?></td>
            </tr>
            <tr>
                <td>Tgl. Ambil</td>
                <td>:</td>
                <td><?=date('d F Y',strtotime($pl->tgl_ambil))?></td>
            </tr>
            <tr>
                <td>Berkas Upload</td>
                <td>:</td>
                <td><a href="<?=$pl->file_upload?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">File Upload</a></td>
            </tr>
            <tr><td colspan="3"></td></tr>
            <tr><td colspan="3"><b>Pelapor</b></td></tr>
            <tr>
                <td>E-Mail</td>
                <td>:</td>
                <td><?=$pl->email?></td>
            </tr>
            <tr>
                <td>No. Identitas</td>
                <td>:</td>
                <td><?=$pl->pelapor->no_identitas?></td>
            </tr>
            <tr>
                <td>Jenis Identitas</td>
                <td>:</td>
                <td><?=$pl->pelapor->jenis_identitas?></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?=$pl->pelapor->nama?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><?=$pl->pelapor->jk?></td>
            </tr>
            <tr>
                <td>Tempat Lahir</td>
                <td>:</td>
                <td><?=$pl->pelapor->tempat_lahir?></td>
            </tr>
            <tr>
                <td>Tgl. Lahir</td>
                <td>:</td>
                <td><?=date('d F Y',strtotime($pl->pelapor->tgl_lahir))?></td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td><?=$pl->pelapor->agama?></td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td><?=$pl->pelapor->pekerjaan?></td>
            </tr>
            <tr>
                <td>No. Telp.</td>
                <td>:</td>
                <td><?=$pl->pelapor->no_telp?></td>
            </tr>
            <tr>
                <td>Nama Ayah</td>
                <td>:</td>
                <td><?=$pl->pelapor->nama_ayah?></td>
            </tr>
            <tr>
                <td>Nama Ibu</td>
                <td>:</td>
                <td><?=$pl->pelapor->nama_ibu?></td>
            </tr>
            <tr>
                <td>Kewarganegaraan</td>
                <td>:</td>
                <td><?=$pl->pelapor->kewarganegaraan?></td>
            </tr>
            <tr><td colspan="3"></td></tr>
            <tr><td colspan="3" class="text-bold">Alamat Indonesia</td></tr>
            <tr>
                <td>Provinsi</td>
                <td>:</td>
                <td><?=$pl->pelapor->provinsi?></td>
            </tr>
            <tr>
                <td>Kota/Kabupaten</td>
                <td>:</td>
                <td><?=$pl->pelapor->kota?></td>
            </tr>
            <tr>
                <td>Kecamatan</td>
                <td>:</td>
                <td><?=$pl->pelapor->kecamatan?></td>
            </tr>
            <tr><td colspan="3"></td></tr>
            <tr><td colspan="3" class="text-bold">Alamat Malaysia</td></tr>
            <tr>
                <td>Negeri</td>
                <td>:</td>
                <td><?=$pl->pelapor->negeri?></td>
            </tr>
            <tr>
                <td>Daerah</td>
                <td>:</td>
                <td><?=$pl->pelapor->daerah?></td>
            </tr>
            <tr>
                <td>Distrik</td>
                <td>:</td>
                <td><?=$pl->pelapor->distrik?></td>
            </tr> 
            <tr><td colspan="3">&nbsp;</td></tr>
            <?php
                foreach ($items as $v) {
                    echo "<tr>
                        <td>$v->field_label</td>
                        <td>:</td>
                        <td>$v->field_value</td>
                    </tr>";
                }
            ?>
        </tbody>
    </table>
</div>
<?php if($pl->status==1){ ?>
<div class="text-right">
    <button class="btn btn-danger" type="button" id="btn_tolak"><i class="fa fa-ban"></i> Tolak</button>
    <button class="btn btn-success" type="button" id="btn_terima"><i class="fa fa-check"></i> Terima</button>
</div>
<?php } ?>
<script>
    $('#btn_tolak').on('click',()=>{
        Swal.fire({
            title: "Alasan penolakan",
            input: "text",
            showCancelButton: true,
            confirmButtonText: "Tolak",
            showLoaderOnConfirm: true,
            preConfirm: (t) => {
                t = t.trim().trim();
                if(t){
                    return new Promise((r,re)=>{
                        window.location = '<?=site_url("pelayanan_publik/tolak/".$pl->id)?>?alasan='+t;
                    });
                }else{
                    Swal.showValidationMessage(`Alasan penolakan tidak boleh kosong.`);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    });
    $('#btn_terima').on('click',()=>{
        Swal.fire({
            title: "Terima Data Pelayanan ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: "Terima",
            showLoaderOnConfirm: true,
            preConfirm: (t) => {
                return new Promise((r,re)=>{
                    window.location = '<?=site_url("pelayanan_publik/terima/".$pl->id)?>';
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    });
</script>