<div class="table-responsive">
	<table class="table table-striped table-hover" style="text-transform:uppercase;">
		<tr>
			<td width="200">Jenis Identitas</td>
			<td width="5">:</td>
			<td><?=$data->jenis_identitas?></td>
		</tr>
		<tr>
			<td>No. Identitas</td>
			<td >:</td>
			<td><?=$data->no_identitas?></td>
		</tr>
		<tr>
			<td>Nama Lengkap</td>
			<td>:</td>
			<td><?=$data->nama?></td>
		</tr>
		<tr>
			<td>Jenis Kelamin</td>
			<td>:</td>
			<td><?=$data->jk?></td>
		</tr>
		<tr>
			<td>Tempat, Tanggal Lahir</td>
			<td>:</td>
			<td><?=$data->tempat_lahir.', '.tanggal_indo($data->tgl_lahir)?></td>
		</tr>
		<tr>
			<td>Agama</td>
			<td>:</td>
			<td><?=$data->agama?></td>
		</tr>
		<tr>
			<td>Pekerjaan</td>
			<td>:</td>
			<td><?=$data->pekerjaan?></td>
		</tr>
		<tr>
			<td>Kewarganegaraan</td>
			<td>:</td>
			<td><?=$data->kewarganegaraan?></td>
		</tr>
		<tr><td colspan="3">Alamat Indonesia</td></tr>
		<tr>
			<td class="pl-5">Provinsi</td>
			<td>:</td>
			<td><?=strtoupper($data->provinsi)?></td>
		</tr>
		<tr>
			<td class="pl-5">Kota / Kabupaten</td>
			<td>:</td>
			<td><?=strtoupper($data->kota)?></td>
		</tr>
		<tr>
			<td class="pl-5">Kecamatan</td>
			<td>:</td>
			<td><?=strtoupper($data->kecamatan)?></td>
		</tr>
		<tr>
			<td class="pl-5">Desa</td>
			<td>:</td>
			<td><?=strtoupper($data->desa)?></td>
		</tr>
		<tr>
			<td class="pl-5">Alamat Lengkap</td>
			<td>:</td>
			<td><?=$data->alamat_idn?></td>
		</tr>
		<tr><td colspan="3">Alamat Malaysia</td></tr>
		<tr>
			<td class="pl-5">Negeri</td>
			<td>:</td>
			<td><?=strtoupper($data->negeri)?></td>
		</tr>
		<tr>
			<td class="pl-5">Daerah</td>
			<td>:</td>
			<td><?=strtoupper($data->daerah)?></td>
		</tr>
		<tr>
			<td class="pl-5">Distrik</td>
			<td>:</td>
			<td><?=strtoupper($data->distrik)?></td>
		</tr>
		<tr>
			<td class="pl-5">Alamat Lengkap</td>
			<td>:</td>
			<td><?=$data->alamat_mys?></td>
		</tr>
	</table>
</div>