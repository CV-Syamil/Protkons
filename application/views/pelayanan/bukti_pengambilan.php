<?php
	$pagi = mktime(10,0,0);
	$siang = mktime(14,00,0);
	$sore = mktime(16,0,0);
	// $xtime = strtotime($data->created_at);
	$waktu = mktime(date('H'),date('i'),date('s'));
	$tgl = date('Y-m-d');
	if($waktu<=$pagi){
		$tgl.= ' 12:30';
	}elseif($waktu>$pagi&&$waktu<$siang){
		$tgl.= ' 16:00'; 
	}else{
		$tgl = date('Y-m-d',strtotime('+1 days')).' 09:00';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>TTS-<?=$data->id?></title>
	<link rel="icon" href="https://elektrikalpanel.com/images/temp/id.png">
	<style type="text/css">
		td{vertical-align: top; overflow-wrap: anywhere; text-transform: uppercase;}
		.btn_print{
			position: fixed;
			top: 0;
			right:0;
			width: fit-content;
			max-width: 200px;
			background:teal;
			padding: 15px 35px 20px 15px;
			border-bottom-left-radius: 30px;
			box-shadow: -1px 2px 10px 2px rgba(0,0,0,0.3);
			color:white;
			text-align:center;
		}
		#btn_cetak {
			width: 100%;
			margin-left: 15px;
			padding: 10px;
			background: #2196f3;
			border: none;
			border-radius: 50px;
			margin-top: 5px;
			color: white;
			cursor:pointer;
		}
		#tgl{
			cursor:pointer;
			width: 100%;
			padding: 10px 15px;
			border: none;
			border-radius: 50px;
			margin-top: 5px;
			text-align: center;
		}
		/* @page {
			size: landscape A5;
			margin: 0;
		} */
	</style>
	<style type="text/css" media="print">
		.btn_print{
			display: none !important;
		}
	</style>
	<link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.css">
	<script src="<?=base_url('style/lte')?>/plugins/jquery/jquery.min.js"></script>
	<script src="<?=base_url('style/lte')?>/plugins/moment/moment.min.js"></script>
	<script src="<?=base_url('style/lte')?>/plugins/daterangepicker/daterangepicker.js"></script>
</head>
<body style="font-family: sans-serif;">
	<div class="btn_print">
		<b>Tanggal Pengambilan</b>
		<input type="text" id="tgl" readonly>
		<button type="button" id="btn_cetak" onclick="window.print()">CETAK DOKUMEN</button>
	</div>
	<div style="border: thin solid black; border-style: dashed; padding: 15px;">
		<center><strong style="font-size: x-large;">SURAT PENGAMBILAN</strong></center>
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
					<td width="150">Kode Pelayanan</td>
					<td width="15">:</td>
					<td><?=$data->id?></td>
				</tr>
				<tr>
					<td width="150">Jumlah</td>
					<td width="15">:</td>
					<td><?=$data->jml_berkas?></td>
				</tr>
				<tr>
					<td width="150">Biaya</td>
					<td width="15">:</td>
					<td><?=(empty($data->biaya)?'GRATIS':$data->jml_berkas.' x '.$data->biaya.' = '.numb(intval($data->jml_berkas)*intval($data->biaya)).' ( '.penyebut(intval($data->jml_berkas)*intval($data->biaya)).' RM )')?></td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td>:</td>
					<td><?=tanggal_indo($data->created_at,TRUE)?></td>
				</tr>
				<tr>
					<td colspan="3">
						<br>
						<i>DOKUMEN DAPAT DIAMBIL PADA TANGGAL <strong id="tgl_txt"></strong></i>.
					</td>
				</tr>
			</tbody>
		</table>
		<p>* KAMI TIDAK BERTANGGUNG JAWAB PADA DOKUMEN ANDA JIKA TIDAK DIAMBIL LEBIH DARI 3 (TIGA) BULAN SEJAK TANGGAL PENGAMBILAN.</p>
	</div>
	<script type="text/javascript">
		let tgl = moment('<?=$tgl?>');
		$('#tgl').daterangepicker({
			autoUpdateInput: false,
			singleDatePicker: true,
			timePicker:true,
			timePicker24Hour: true,
			timePickerSeconds: false,
			timePickerIncrement: 15,
            startDate: tgl,
            endDate: tgl,
			minDate:moment(), 
            locale: {format: 'DD MMMM YYYY HH:mm',}
        },function (start, end) { setDate(start); });
		function setDate(t){
			let month = <?=json_encode(bulan_id())?>;
			let day = t.date();
			let bl = month[t.month()];
			let th = t.year();
			let jam = t.hour();
			let menit = t.minute();
			let tgl_str = ((day>9)?day:'0'+day)+' '+bl+' '+th+' '+((jam>9)?jam:'0'+jam)+':'+((menit>9)?menit:'0'+menit);
			$('#tgl').val(tgl_str);
			$('#tgl_txt').html(tgl_str);
		}
		setDate(tgl);
	</script>
</body>
</html>