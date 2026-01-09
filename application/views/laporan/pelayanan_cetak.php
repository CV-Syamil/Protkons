<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laporan Pelayanan Konsuler</title>
    <link rel="icon" href="https://elektrikalpanel.com/images/temp/id.png">
	<style type="text/css">
		td{vertical-align: top; overflow-wrap: anywhere; text-transform: uppercase;}
		button{
			padding: 20px 30px 15px 30px;
			font-size: x-large;
			background: teal;
			border: none;
			color: white;
			top: 10px;
			margin-left: auto;
			margin-right: auto;
			border-bottom-left-radius: 30px;
			cursor: pointer;
			box-shadow: -1px 2px 10px 2px rgba(0,0,0,0.3);
			transition: ease 0.3s;
		}
		button:hover{
			background: cornflowerblue;
			transition: ease 0.3s;
		}
		.btn_print{
			position: fixed;
			top: 0;
			right:0;
			width: 100%;
			text-align: right;
		}
		/* @page {
			size: A5;
			margin: 0;
		} */
	</style>
	<style type="text/css" media="print">
		.btn_print{
			display: none !important;
		}
	</style>
</head>
<body>
    <div class="btn_print"><button type="button" onclick="window.print()">CETAK</button></div>
    <center><h1>Laporan Pelayanan Konsuler</h1></center>
    <h4 style="">Tanggal&nbsp;&nbsp;: <?=tanggal_indo($start).(($start==$end)?'':' - '.tanggal_indo($end))?></h4>
    <table width="100%" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background:#eee;">
                <th width="140">Kode Layanan</th>
                <th>Pelayanan</th>
                <th>Jumlah Layanan</th>
                <th>Jumlah Berkas</th>
                <th>Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            <?php $total=0;$total2=0; $total3=0; foreach($data as $v){
				$total+=intval($v->jumlah);
				$total2+=intval($v->jml_berkas);
				$total3+=intval($v->total);
				$jml = numb($v->jumlah);
				$berkas = numb($v->jml_berkas);
				$total_biaya = numb($v->total);
                echo "<tr>
                    <td>$v->kode_layanan</td>
                    <td>$v->pelayanan</td>
                    <td align=\"right\">$jml</td>
                    <td align=\"right\">$berkas</td>
                    <td align=\"right\">$total_biaya</td>
                </tr>";
            }?>
			<tr style="background:#eee;">
				<td colspan="2" align="right">Total</td>
				<td align="right"><?=numb($total)?></td>
				<td align="right"><?=numb($total2)?></td>
				<td align="right"><?=numb($total3)?></td>
			</tr>
        </tbody>
    </table>
</body>
</html>