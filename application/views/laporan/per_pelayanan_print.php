<h4 style="">Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?=$tanggal?></h4>
<h4 style="margin-top:-15px">Pelayanan&nbsp;&nbsp; : <?='('.$ms_pl->kode_layanan.') '.$ms_pl->pelayanan;?></h4>
<table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>TANGGAL</th>
            <th>NO DOKUMEN</th>
            <th>NAMA PELAPOR</th>
            <th>PEJABAT PENANDATANGAN</th>
            <?php $str_col=""; foreach ($kolom2 as $col) { $str_col.="<th>".strtoupper(str_replace('_',' ',$col))."</th>"; } echo $str_col;?>
            <th>JUMLAH BERKAS</th>
            <th>BIAYA</th>
            <th>TOTAL BIAYA</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $tbiaya = 0;
            $jml_berkas = 0;
            foreach ($data_pl as $dt) {
                $sbiaya = intval($dt->jml_berkas)*intval($dt->biaya);
                $jml_berkas+=intval($dt->jml_berkas);
                $tbiaya+= $sbiaya;
                $str = "<tr>";
                $str.= "<td>'".strtoupper(date('d F Y',strtotime($dt->created_at)))."</td>";
                $str.= "<td align=\"center\">".$dt->no_dokumen."</td>";
                $str.= "<td >".strtoupper($dt->nm_pelapor)."</td>";
                $str.= "<td >".strtoupper($dt->nm_hs)."</td>";
                $dt2 = @$data_item[$dt->id];
                foreach ($kolom2 as $kol) {
                    $sval = @$dt2[$kol];
                    $sval = empty($sval)?'':((is_numeric($sval))?"=\"$sval\"":$sval);
                    $str.= "<td>".strtoupper($sval)."</td>";
                }
                $str.= "<td align=\"center\">".numb($dt->jml_berkas)."</td>";
                $str.= "<td align=\"right\">".numb($dt->biaya)."</td>";
                $str.= "<td align=\"right\">".numb($sbiaya)."</td>";
                $str.= "</tr>";
                echo $str;
            }
        ?>
        <tr>
            <td align="right" colspan="2">JUMLAH LAYANAN</td>
            <td align="center"><?=numb(count($data_pl))?></td>
            <td align="right" colspan="<?=count($kolom2)+1?>">JUMLAH BERKAS</td>
            <td align="center"><?=numb($jml_berkas)?></td>
            <!-- <?=(empty($kolom2)?'':'<td colspan="'.count($kolom2).'"></td>')?> -->
            <td align="right">TOTAL BIAYA</td>
            <td align="right"><?=numb($tbiaya)?></td>
        </tr>
    </tbody>
</table>