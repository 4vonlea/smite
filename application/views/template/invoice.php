<?php

/**
 * @var Transaction_m $transaction
 */
$header_image = base_url('themes/uploads/header_kop.jpg');
$member = $transaction->member;
$lang['cal_january']	= "Januari";
$lang['cal_february']	= "Februari";
$lang['cal_march']		= "Maret";
$lang['cal_april']		= "April";
$lang['cal_mayl']		= "Mei";
$lang['cal_june']		= "Juni";
$lang['cal_july']		= "Juli";
$lang['cal_august']		= "Agustus";
$lang['cal_september']	= "September";
$lang['cal_october']	= "Oktober";
$lang['cal_november']	= "November";
$lang['cal_december']	= "Desember";
setlocale(LC_TIME, 'id_ID');
$payment = Settings_m::manualPayment(false);
$isGroup = ($member == null);
?>
<html>

<head>
	<style>
		.table th,
		.table td {
			vertical-align: top;
		}

		.table-event {
			width: 100%;
			border-collapse: collapse;
			margin-top: 10px;
		}

		.table-event thead {
			background-color: green;
			color: white;
		}

		.table-event th {
			text-align: center;
		}

		.table-event th,
		.table-event td {
			border: 1px solid;
			padding: 3px;
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

		.row {
			width: 100%;
			clear: both;
		}

		.col {
			width: 145px;
			display: block;
			float: left;
			font-weight: bold;
		}

		.col2 {
			width: 10px;
			display: block;
			float: left;
		}
	</style>
</head>

<body>
	<header>
		<img src="<?= $header_image; ?>" style="width:100%" alt="<?= $header_image; ?>" />
	</header>
	<footer>
		<img class="watermark" src="<?= base_url('themes/uploads/payment_required.jpg'); ?>" />
	</footer>
	<section>
		<h4 style="text-align: center;">INVOICE</h4>
		<p>Dear Participant <?= $member ?  $member->fullname : $transaction->member_id; ?></p>
		<p style="text-align:justify;text-justify:inter-word;">Here we send the bill of payment as a form of official statement. Please complete this payment immediately
			<?php if (is_array($payment) && count($payment) == 1 && $transaction->channel == "MANUAL TRANSFER") : ?>
				via Bank <?= $payment[0]['bank']; ?> No <?= $payment[0]['no_rekening']; ?> a.n <?= $payment[0]['holder']; ?>
			<?php elseif (is_array($payment) && count($payment) > 1) : ?>
				via :
		<ul>
			<?php foreach ($payment as $list) : ?>
				<li>Bank <?= $list['bank']; ?> No <?= $list['no_rekening']; ?> a.n <?= $list['holder']; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	</p>
	<div class="row">
		<div class="col">Order Date</div>
		<div class="col2">:</div>
		<div class="col3">
			<?= date("d F Y \a\\t H:i:s A", strtotime($transaction->created_at)); ?>
		</div>
	</div>
	<div class="row">
		<div class="col">ID Invoice</div>
		<div class="col2">:</div>
		<div>
			<?= $transaction->id; ?>
		</div>
	</div>
	<div class="row">
		<div class="col"><?= $isGroup ? "Bill To" : "Name"; ?></div>
		<div class="col2">:</div>
		<div>
			<?= $member->fullname ?? $transaction->member_id; ?>
		</div>
	</div>
	<?php if ($member && $member->email) : ?>
		<div class="row">
			<div class="col">
				E-mail
			</div>
			<div class="col2">:</div>
			<div>
				<a href="mailto:<?= $member->email; ?>" rel="noreferrer" target="_blank">
					<?= $member->email; ?>
				</a>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($member && $member->username_account) : ?>
		<div class="row">
			<div class="col">
				Username
			</div>
			<div class="col2">:</div>
			<div>
				<?= $member->username_account; ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($member && $member->status) : ?>
		<div class="row">
			<div class="col">
				Status
			</div>
			<div class="col2">:</div>
			<div>
				<?= $member->status_member->kategory; ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if (isset($member->sponsor) && $member->sponsor != "") : ?>
		<div class="row">
			<div class="col">
				Sponsor
			</div>
			<div class="col2">:</div>
			<div>
				<?= $member->sponsor; ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="row">
		<div class="col">Payment Method</div>
		<div class="col2">:</div>
		<div class="col3">
			<?= strtoupper($transaction->channel); ?> -
			<?php
			$data = $transaction->toArray();
			if ($data['paymentGatewayInfo']['product']) {
				echo $data['paymentGatewayInfo']['product'] . "";
			}
			if ($data['paymentGatewayInfo']['productNumber']) {
				echo " / " . $data['paymentGatewayInfo']['productNumber'] . ",<br/>";
			}

			?>
		</div>
	</div>
	<?php if ($data['paymentGatewayInfo']['expired']) { ?>
		<div class="row">
			<div class="col">Payment Expired On</div>
			<div class="col2">:</div>
			<div>
				<?= date("d F Y \a\\t H:i:s A", strtotime($data['paymentGatewayInfo']['expired'])); ?>
			</div>
		</div>
	<?php } ?>
	<div class="row">
		<div class="">
			<table class="table-event">
				<thead>
					<tr>
						<th>No</th>
						<th>Item</th>
						<th>Price</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$total = 0;
					$no = 1;
					foreach ($transaction->detailsWithEvent() as $d) {
						echo "<tr>";
						echo "<td style='text-align:center'>$no</td>";
						$total += $d->price;
						$name = ($isGroup && $d->member_name ? " / " . $d->member_name : "");
						if ($d->price_usd > 0) {
							echo "<td>$d->product_name <strong>$name</strong></td><td style='text-align:center'>USD " . $d->price_usd . "</td>";
						} else {
							echo "<td>$d->product_name <strong>$name</strong></td><td style='text-align:center'>Rp " . number_format($d->price, 2, ",", ".") . "</td>";
						}
						echo "</tr>";
						$no++;
					};
					?>
				</tbody>
				<tfoot>
					<tr>
						<th style="text-align: left" colspan="2">
							Total
						</th>
						<th>
							Rp <?= number_format($total, 2, ",", "."); ?>*
						</th>
					</tr>
				</tfoot>
			</table>
			<ul>
				<li style="font-size:11pt">The amount price above include online bank payment administration fees</li>
				<?php if (isset($transaction->note) && $transaction->note != "") : ?>
					<li style="font-size:11pt"><?= $transaction->note; ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<p style="text-align:justify;text-justify:inter-word;font-size:18px">
		<strong>No refund may be allowed after transaction</strong>. Any inappropriate payments or fraud transactions will be automatically deleted by the system and participants must re-checkout. Thank you
	</p>
	<?php
	$this->load->view("template/invoice_payment_signature");
	?>
	</section>
</body>

</html>