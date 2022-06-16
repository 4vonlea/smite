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
	table {
		border-collapse: collapse;
		margin: 5px auto;
	}

	table,th,td {
		text-align: left;
		border: 1px solid black;
	}
	tr,	td,	th {
		padding: 10px 10px;
	}
</style>
<p>Dear Participant</p>
<p>
	You've received this email because you have requested to reset your password. Please find your new password below.

</p>
<p>
	Your new password is <b><?php echo $password; ?></b>
</p>
<p>We encourage you to change your password immediately. Please keep your password confidential. Thank you</p>