<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laporan Keuangan Pelayanan Konsuler</title>
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
	</style>
	<style type="text/css" media="print">
		.btn_print{
			display: none !important;
		}
	</style>
</head>
<body>
    <div class="btn_print"><button type="button" onclick="window.print()">CETAK</button></div>
    <?php $el_body="";$total_biaya = 0; foreach($data as $v){
        $total_biaya+=intval($v->jumlah);
        $biaya = numb($v->jumlah);
        $el_body.="<tr>
            <td>$v->kode_layanan</td>
            <td>$v->pelayanan</td>
            <td align=\"right\">$biaya</td>
        </tr>";
    }?>
    <center><h1>Laporan Keuangan Pelayanan Konsuler</h1></center>
    <h4 style="">Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?=tanggal_indo($start).(($start==$end)?'':' - '.tanggal_indo($end))?></h4>
    <table width="100%" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background:#eee;">
                <th width="140">Kode Pelayanan</th>
                <th>Pelayanan</th>
                <th>Biaya</th>
            </tr>
        </thead>
        <tbody>
            <?=$el_body?>
            <tr style="background:#eee;">
                <th colspan="2" align="right">Total</th>
                <th align="right"><?=numb($total_biaya)?></th>
            </tr>
        </tbody>
    </table>
</body>
</html>