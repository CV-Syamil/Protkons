<table border="1">
    <thead>
        <tr> <th colspan="8">LAPORAN PELAYANAN PELAPOR</th> </tr>
        <tr> <th colspan="8"><?=tanggal_indo($start)?> - <?=tanggal_indo($end)?></th> </tr>
        <tr> <th colspan="8" align="left">Jumlah Pemohon : <?=numb(count($data))?></th> </tr>
        <tr>
            <th>ID PELAPOR</th>
            <th>NAMA PELAPOR</th>
            <th>ID PELAYANAN</th>
            <th>PELAYANAN</th>
            <th>PROVINSI</th>
            <th>KOTA</th>
            <th>KECAMATAN</th>
            <th>ALAMAT</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data as $v){
            echo "<tr>
                <td>$v->no_pelapor</td>
                <td>".strtoupper($v->nm_pelapor)."</td>
                <td>$v->id</td>
                <td>".strtoupper($v->pelayanan)."</td>
                <td>".strtoupper($v->provinsi)."</td>
                <td>".strtoupper($v->kota)."</td>
                <td>".strtoupper($v->kecamatan)."</td>
                <td>".strtoupper($v->alamat_idn)."</td>
            </tr>";
        }?>
    </tbody>
</table>