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
		.row{
			width: 100%;
			clear: both;
		}
		.col {
			width: 145px;
			display: block;
			float: left;
			font-weight: bold;
		}
		.col2{
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
		<img class="watermark" src="<?=  base_url('themes/uploads/payment_required.jpg');?>" />
	</footer>
	<section>
		<h4 style="text-align: center;">INVOICE</h4>
		<p>Dear <?= $member->sponsor ?? $transaction->member_id; ?></p>
		<p style="text-align:justify;text-justify:inter-word;">Here we send the bill of payment as a form of official bill. Please complete this payment immediately
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
				<?php if ($transaction->checkout == 0) : ?>
					<?= date("d F Y \a\\t H:i:s A"); ?>
				<?php else : ?>
					<?= date("d F Y \a\\t H:i:s A", strtotime($transaction->created_at)); ?>
				<?php endif; ?>
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
			<div class="col"><?=$isGroup ? "Bill To":"Name";?></div>
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
			<div class="col">Followed event</div>
			<div class="col2">:</div>
			<div style="padding-left: 155px">
				<?php
				$total = 0;
				foreach ($transaction->detailsWithEvent() as $d) {
					$total += $d->price;
					$name = ($isGroup ? $d->member_name : "");
					if ($d->price_usd > 0) {
						echo "<span>$d->product_name  / <strong>$name</strong> :  <br/>USD " . $d->price_usd . "</span><br/>";
					} else {
						echo "<span>$d->product_name / <strong>$name</strong> :  <br/>Rp " . number_format($d->price, 2, ",", ".") . "</span><br/>";
					}
				};
				?>
			</div>
		</div>
		<div class="row">
			<div class="col">Amount Price</div>
			<div class="col2">:</div>
			<div>
				Rp <?= number_format($total, 2, ",", "."); ?>*

			</div>
		</div>
		<div class="row">
			<div class="col">Payment Method</div>
			<div class="col2">:</div>
			<div>
				<?= strtoupper($transaction->channel); ?> -
				<?php
				$data = $transaction->toArray();
				if ($data['paymentGatewayInfo']['product']) {
					echo $data['paymentGatewayInfo']['product'] . "<br/>";
				}
				if ($data['paymentGatewayInfo']['productNumber']) {
					echo "Account Number : " . $data['paymentGatewayInfo']['productNumber'] . "<br/>";
				}
				?>
			</div>
		</div>
		<p style="text-align:justify;text-justify:inter-word;font-size:20px">
			Any inappropriate payments or fraud transactions will be automatically deleted by the system and participants must re-checkout. Thank you
		</p>
		<?php
		$this->load->view("template/invoice_payment_signature");
		?>
	</section>
</body>

</html>