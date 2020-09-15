<?php
/**
 * @var $fullname
 * @var $email
 * @var $password
 * @var $participant
 * @var $participantsCategory
 * @var $gender
 * @var $status
 * @var $city
 * @var $address
 */
?>
<style>
	table{
		border-collapse: collapse;
		margin: 5px auto;
	}
	table,th,td{
		text-align: left;
		border: 1px solid black;
	},
	tr, td , th {
		padding: 10px 10px;
	}
</style>
<p>Yth, <?=$fullname;?></p>
<p>
	Terlampir Akun Anda serta invoice bukti dan registrasi pada kegiatan <?=Settings_m::getSetting('text_payment_proof');?>.
</p>
	<table>
		<tr>
			<th>Email/Username</th>
			<td><?=$email;?></td>
		</tr>
		<tr>
			<th>Password</th>
			<td><?=$password;?></td>
		</tr>
		<tr>
			<th>Nama</th>
			<td><?=$fullname;?></td>
		</tr>
		<tr>
			<th>Status</th>
			<td><?=$participantsCategory[$status];?></td>
		</tr>
		<tr>
			<th>Jenis Kelamin</th>
			<td><?=$gender == "M" ? "Laki-Laki":"Perempuan";?></td>
		</tr>
		<tr>
			<th>Kota</th>
			<td><?=$city?></td>
		</tr>
		<tr>
			<th>Alamat</th>
			<td><?=$address?></td>
		</tr>
</table>