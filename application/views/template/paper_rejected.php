<?php
/**
 * @var Transaction_m $transaction
 */
$header_image = base_url('themes/uploads/header_kop.jpg');
?>
<style>
	.table {
		border-collapse: collapse;
		width: 90%;
		margin-top: 15px;
		margin-bottom: 15px;
	}

	.table th, .table td {
		padding: 5px 10px;
		vertical-align: top;
	}

	.table th {
		text-align: left;
	}

	.text-center {
		text-align: center;
	}
</style>
<table border="0" cellpadding="0" cellspacing="0" style="width:628px">
	<tr style="height:22px">
		<td>
			<img src="<?= $header_image; ?>" width="720"/>
		</td>
	</tr>
	<tr>
		<td class="text-center">
			<h4 style="font-size: 14pt">Abstract Submission Announcement – <?=Settings_m::getSetting('site_title');?></h4>
		</td>
	</tr>
	<tr>
		<td>
			<p>Dear <?= $member->fullname; ?></p>
          	<p>Institution : <?=$member->institution->univ_nama;?></p>
			<p>Thank you for submitting your abstract to <?=Settings_m::getSetting('site_title');?>. Hereby we informed you that
				your abstract:</p>
			<table>
				<tr>
					<td style="width: 100px;">Title</td>
					<td style="width: 5px">:</td>
					<td><?= $paper->title; ?></td>
				</tr>
				<tr>
					<td>ID</td>
					<td>:</td>
					<td><?= $paper->getIdPaper(); ?></td>
				</tr>
				<tr>
					<td>Result</td>
					<td>:</td>
					<td>Rejected</td>
				</tr>
			</table>
			<p>We appreciate you submitting your abstract and thank you for giving us the opportunity to consider your work. </p> 

		</td>
	</tr>
	<tr style="height:20px">
		<td>
			<div style="width:100%;float: right">
				<p style="text-align: center">Kind Regards,</p>
				<div style="text-align: center">
					<p>Chairman of <?= Settings_m::getSetting('site_title'); ?></p>
					<img style="z-index: -10;margin-bottom:40px;height:200px"
						 src="<?= base_url('themes/uploads/ttd_cap.jpg'); ?>">
				</div>
			</div>
		</td>
	</tr>

</table>
