<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>TT-<?=$data->id?></title>
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
<body style="font-family: sans-serif;">
	<div class="btn_print"><button type="button" onclick="window.print()">CETAK DOKUMEN</button></div>
	<div style="border: thin solid black; border-style: dashed; padding: 15px;">
		<center>
			<div style="width:fit-content; padding: 0 5px; text-transform: uppercase;">
				<strong style="font-size: large;">KWITANSI</strong>
				<div style="border-bottom: 2px solid black; margin:0 -5px;"></div>
				Tanda Bukti Pembayaran Jasa Kekonsuleran
			</div>
		</center>
		<table width="100%" cellpadding="3" cellspacing="0" border="0" style="margin-top: 15px">
			<tbody>
				<tr>
					<td>Diterima Dari</td>
					<td>:</td>
					<td><?=$pelapor->nama." ( $pelapor->no_identitas )"?></td>
				</tr>
				<tr>
					<td>Pelayanan</td>
					<td>:</td>
					<td><?=$pl->kode_layanan.' - '.$pl->pelayanan?></td>
				</tr>
				<tr>
					<td width="150">No Dokumen</td>
					<td width="15">:</td>
					<td><?=$data->no_dokumen2?></td>
				</tr>
				<tr>
					<td width="150">Jumlah</td>
					<td width="15">:</td>
					<td><?=$data->jml_berkas.' x '.$data->biaya.' = '.numb(intval($data->jml_berkas)*intval($data->biaya))?></td>
				</tr>
				<tr>
					<td width="150">Biaya</td>
					<td width="15">:</td>
					<td><?=(empty($data->biaya)?'TIDAK DIPUNGUT BIAYA':penyebut(intval($data->jml_berkas)*intval($data->biaya)).' RM')?></td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td>:</td>
					<td><?=tanggal_indo($data->created_at,TRUE)?></td>
				</tr>
			</tbody>
		</table>
		<p align="justify">Jumlah biaya yang anda bayar merupakan Penerimaan Negara Bukan Pajak Yang Berlaku Pada Kementerian Luar Negeri No.26 Tahun 2020 dan Keputusan Kepala Perwakilan RI untuk Malaysia di Kuala Lumpur No. 027/WN/III/2021/01 tentang Jenis dan Tarif Penerimaan Bukan Pajak Atas Pelayanan Pada Kedutaan Besar Republik Indonesia di Kuala Lumpur</p>
		<p>* Kwitansi ini adalah cetakan komputer tanda tangan tidak diperlukan.</p>
	</div>
	<script type="text/javascript">
		window.print();
	</script>
</body>
</html>