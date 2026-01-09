<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=APP_NAME?></title>
  <link rel="icon" href="https://elektrikalpanel.com/images/temp/id.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="<?=base_url('style/lte')?>/dist/css/adminlte.min.css">

  <style type="text/css">
  	html,body{background: #4caf50;}
  </style>
</head>
<body>
	<section class="container" style="margin-top: 15px;">
		<div class="card card-solid">
			<div class="card-header with-border">
				<h3 class="card-title"><?=$pl->pelayanan?></h3>
			</div>
			<div class="card-body" style="padding:10px;">
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<tr>
							<td width="150">Kode Pelayanan</td>
							<td width="5">:</td>
							<td><?=$data->id?></td>
						</tr>
						<tr>
							<td>Tanggal dibuat</td>
							<td>:</td>
							<td><?=tanggal_indo($data->created_at)?></td>
						</tr>
						<tr>
							<td>Nama Pelapor</td>
							<td>:</td>
							<td><?=$pelapor->nama?> (<?=$pelapor->no_identitas?>)</td>
						</tr>
						<tr>
							<td>Penandatangan</td>
							<td>:</td>
							<td><?=$hs?></td>
						</tr>
						<tr>
							<td>Status</td>
							<td>:</td>
							<td><?=status_layanan($data->status,TRUE)?></td>
						</tr>
						<?php if(!empty($tte->notes)){?>
							<tr class="<?=(strtolower($tte->summary)=='valid')?'bg-success':'bg-warning'?>"><td colspan="3"><?=$tte->notes?></td></tr>
							<?php if(!empty($tte->details)){ ?>
								<tr>
									<td>Info TSA</td>
									<td>:</td>
									<td><?=$tte->details[0]->info_tsa->name?></td>
								</tr>
								<tr>
									<td>Info Signer</td>
									<td>:</td>
									<td><?=$tte->details[0]->info_signer->signer_name?></td>
								</tr>
								<tr>
									<td>Tanggal</td>
									<td>:</td>
									<td><?=date('d F Y H:i',strtotime($tte->details[0]->signature_document->signed_in))?></td>
								</tr>
							<?php }
						} ?>
					</table>
				</div>
				<?php if(!empty($data->file_esign)){?>
					<iframe src="<?=base_url('assets/'.$data->file_esign)?>" width="100%" height="600" frameborder="0"></iframe>
				<?php } ?>
			</div>
		</div>
	</section>

  <script src="<?=base_url('style/lte')?>/plugins/jquery/jquery.min.js"></script>
  <script src="<?=base_url('style/lte')?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?=base_url('style/lte')?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <script src="<?=base_url('style/lte')?>/dist/js/adminlte.min.js"></script>
</body>
</html>