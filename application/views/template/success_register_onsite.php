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
<p>Hi, <?=$fullname;?></p>
<p>
	You've received this email because you have successfully registered by admin to participate on events. Below is your account description
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
			<th>Fullname</th>
			<td><?=$fullname;?></td>
		</tr>
		<tr>
			<th>Status As</th>
			<td><?=$participantsCategory[$status];?></td>
		</tr>
		<tr>
			<th>Gender</th>
			<td><?=$gender == "M" ? "Male":"Female";?></td>
		</tr>
		<tr>
			<th>City</th>
			<td><?=$city?></td>
		</tr>
		<tr>
			<th>Address</th>
			<td><?=$address?></td>
		</tr>
</table>
<p>And below is your invoice and payment proof</p>
