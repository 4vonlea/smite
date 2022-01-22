<?php

/**
 * @var Transaction_m $transaction
 */
$header_image = base_url('themes/uploads/header_kop.jpg');
$member = $transaction->member;

ob_start();
QRCode::png("payment_proof;" . $transaction->id, false, QR_ECLEVEL_L, 4, 2);
$qr = base64_encode(ob_get_clean());
header('Content-Type: text/html');
?>
<style>
	.table {
		border-collapse: collapse;
		width: 90%;
		margin-top: 15px;
		margin-bottom: 15px;
	}

	.table th,
	.table td {
		padding: 5px 10px;
		vertical-align: top;
	}

	.table th {
		text-align: left;
	}

	.watermark {
		position: absolute;
		top: 20%;
		/* or whatever */
		left: 15%;
		/* or whatever, position according to taste */
		opacity: 0.2;
		/* Firefox, Chrome, Safari, Opera, IE >= 9 (preview) */
		filter: alpha(opacity=20);
		/* for <= IE 8 */
		z-index: -1;
	}
</style>
<img class="watermark" src="<?= base_url('themes/uploads/paid.jpg'); ?>" />
<table border="0" cellpadding="0" cellspacing="0" style="width: 700px;margin-right: auto;margin-left: auto">
	<tr>
		<td>
			<img src="<?= $header_image; ?>" style="width:700px" alt="<?= $header_image; ?>" />
		</td>
	</tr>
	<tr style="height:30px">
		<td style="text-align:center;height:30px">
			<p>
				<span style="font-family:times new roman,times,serif;font-size:12pt"><strong>PAYMENT AND REGISTRATION PROOF - RECEIPT</strong></span>
			</p>
			<p style="text-align:right">
				<span style="font-family:times new roman,times,serif;font-size:12pt;text-align:start;background-color:#ffffff"><?= date("d F Y", strtotime($transaction->updated_at)); ?></span>
			</p>
			<p style="text-align:left">
				Dear. <?= $member->sponsor; ?>
			</p>
			<p style="text-align:justify;text-justify:inter-word;">
				Thank you for your registration and participation in the event <?= Settings_m::getSetting("text_payment_proof"); ?>. Here are the registration and payment details:
			</p>
		</td>
	</tr>
	<tr>
		<td align="center">
			<table class="table" style="width: 100%;">
				<tr>
					<th width="170">ID INVOICE</th>
					<td width="10">:</td>
					<td><?= $transaction->id; ?></td>
				</tr>
				<tr>
					<th>Name</th>
					<td>:</td>
					<td><?= $member->fullname; ?></td>
				</tr>
				<tr>
					<th>Status</th>
					<td>:</td>
					<td><?= $member->status_member->kategory; ?></td>
				</tr>
				<tr>
					<th>Username</th>
					<td>:</td>
					<td><?= $member->username_account; ?></td>
				</tr>
				<?php if (isset($member->sponsor) && $member->sponsor != "") : ?>
					<tr>
						<th>Sponsor</th>
						<td>:</td>
						<td><?= $member->sponsor; ?></td>
					</tr>
				<?php endif; ?>
				<tr>
					<th>Followed Event</th>
					<td>:</td>
					<td>
						<ul style="margin: 0px;padding-left:15px">
							<?php
							$total = 0;
							foreach ($transaction->detailsWithEvent() as $d) {
								$total += $d->price;
								echo "<li>$d->product_name :  <br/>Rp " . number_format($d->price, 2, ",", ".") . "</li>";
							};
							?>
						</ul>
					</td>
				</tr>
				<tr>
					<th>Amount Price</th>
					<td>:</td>
					<td>Rp <?= number_format($total, 2, ",", "."); ?>*</td>
				</tr>
				<tr>
					<th>
						Payment Method
					</th>
					<td>:</td>
					<td>
						<?= strtoupper($transaction->channel); ?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<span style="font-size:9pt">*The amount price above does not include online bank payment administration fees</small>
					</td>
				</tr>
			</table>
		</td>
	</tr> <br />
	<tr style="height:20px">
		<td style="height:20px">
			<p style="text-align:justify;text-justify:inter-word;">
			This payment proof (receipt) is a valid proof and is used properly. Participants must show this receipt to the committee at the time of re-registration. Thank you</p> <br /><br />
		</td>
	</tr>
	<tr>
		<td>
			<div style="bottom: 80px;left:0px;position: absolute">
				<img style="margin: auto" src="data:image/png;base64,<?= $qr; ?>" />
			</div>
			<?php
			$this->load->view("template/invoice_payment_signature");
			?>
		</td>
	</tr>
</table>