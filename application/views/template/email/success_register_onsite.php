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
<p>Dear <?=$fullname;?></p>
<p>
	Thank you for completing your registration. Please note your username and password below to join in our website <?=base_url();?> . For participant who has'nt complete the payment. We have attached your invoice for your convinience. <?=Settings_m::getSetting('text_payment_proof');?>.
</p>
	<table style="border-collapse: collapse;margin: 5px auto;text-align: left;border: 1px solid black;">
		<tr style="border: 1px solid black;padding: 10px 10px;">
			<th style="border: 1px solid black;padding: 10px 10px;">Email/ Username</th>
			<td style="border: 1px solid black;padding: 10px 10px;"><?=$email;?></td>
		</tr>
		<tr style="border: 1px solid black;padding: 10px 10px;">
			<th style="border: 1px solid black;padding: 10px 10px;">Password</th>
			<td style="border: 1px solid black;padding: 10px 10px;"><?=$password;?></td>
		</tr>
		<tr style="border: 1px solid black;padding: 10px 10px;">
			<th style="border: 1px solid black;padding: 10px 10px;">Name</th>
			<td style="border: 1px solid black;padding: 10px 10px;"><?=$fullname;?></td>
		</tr>
		<tr style="border: 1px solid black;padding: 10px 10px;">
			<th style="border: 1px solid black;padding: 10px 10px;">Status</th>
			<td style="border: 1px solid black;padding: 10px 10px;"><?=$participantsCategory[$status];?></td>
		</tr>
</table>