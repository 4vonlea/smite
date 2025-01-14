<?php

/**
 * @var Transaction_m $transaction
 */
$header_image = "http://localhost/seminar_dokter/themes/uploads/header_kop.jpg";//base_url('themes/uploads/header_kop.jpg');

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
	</style>
</head>

<body>
	<header>
		<img src="<?= $header_image; ?>" style="width:100%" alt="<?= $header_image; ?>" />
	</header>
	<footer>
		<img class="watermark" src="<?= 'http://localhost/seminar_dokter/themes/uploads/payment_required.jpg	';// base_url('themes/uploads/payment_required.jpg'); ?>" />
	</footer>
	<table border="0" cellpadding="0" cellspacing="0" style="width: 700px;margin-right: auto;margin-left: auto">
		<tbody>

			<tr>
				<td>
					<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;margin-right: auto;margin-left: auto;padding-bottom: 15px">
						<tbody>
							<tr>
								<td align="center" style="font-size:16px;vertical-align:middle;padding-top:2px;line-height:20px">
									<strong>
										INVOICE
									</strong>
								</td>
							</tr>
						</tbody>
					</table>
					<table border="0" cellpadding="0" class="table" cellspacing="0" style="width: 100%;vertical-align: top">
						<tbody>
							<tr>
								<td>
									<p>Dear <?= $member->sponsor ?? $transaction->member_id; ?></p>
									<p style="text-align:justify;text-justify:inter-word;">Here we send the bill of payment as a form of official statement. Please pay off this payment immediately

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
								</td>
							</tr>
							<tr>
								<td align="center">
									<table border="0" cellpadding="5" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<td style="padding:5px!important" valign="top" width="170">
													Order Date
												</td>
												<td width="10">:</td>
												<td style="padding:5px!important">
													<?php if ($transaction->checkout == 0) : ?>
														<?= date("d F Y \a\\t H:i:s A"); ?>
													<?php else : ?>
														<?= date("d F Y \a\\t H:i:s A", strtotime($transaction->created_at)); ?>
													<?php endif; ?>
												</td>
											</tr>
											<tr>
												<td style="padding:5px!important" valign="top" width="170">
													ID Invoice
												</td>
												<td>:</td>
												<td style="padding:5px!important">
													<?= $transaction->id; ?>
												</td>
											</tr>
											<tr>
												<td style="padding:5px!important" valign="top" width="170">
													Name
												</td>
												<td>:</td>
												<td style="padding:5px!important">
													<?= $member->fullname ?? $transaction->member_id; ?>
												</td>
											</tr>
											<?php if ($member && $member->email) : ?>
												<tr>
													<td style="padding:5px!important" valign="top" width="170">
														E-mail
													</td>
													<td>:</td>
													<td style="padding:5px!important">
														<a href="mailto:<?= $member->email; ?>" rel="noreferrer" target="_blank">
															<?= $member->email; ?>
														</a>
													</td>
												</tr>
											<?php endif; ?>
											<?php if ($member && $member->username_account) : ?>
												<tr>
													<td style="padding:5px!important" valign="top" width="170">
														Username
													</td>
													<td>:</td>
													<td style="padding:5px!important">
														<?= $member->username_account; ?>
													</td>
												</tr>
											<?php endif; ?>
											<?php if ($member && $member->status) : ?>

												<tr>
													<td style="padding:5px!important" valign="top" width="170">
														Status
													</td>
													<td>:</td>
													<td style="padding:5px!important">
														<?= $member->status_member->kategory; ?>
													</td>
												</tr>
											<?php endif; ?>
											<?php if (isset($member->sponsor) && $member->sponsor != "") : ?>
												<tr>
													<td style="padding:5px!important" valign="top" width="170">
														Sponsor
													</td>
													<td>:</td>
													<td style="padding:5px!important">
														<?= $member->sponsor; ?>
													</td>
												</tr>
											<?php endif; ?>
											<tr>
												<td style="padding:5px!important" valign="top" width="170">
													Followed event
												</td>
												<td>:</td>
												<td style="padding:5px!important;vertical-align: top">

													<div style="margin:0px;padding-left: 15px">
														<?php
														$total = 0;
														foreach ($transaction->detailsWithEvent() as $d) {
															$total += $d->price;
															$name = ($isGroup ? $d->member_name : "");
															if ($d->price_usd > 0) {
																echo "<span>$d->product_name  / $name :  <br/>USD " . $d->price_usd . "</span><br/>";
															} else {
																echo "<span>$d->product_name / $name :  <br/>Rp " . number_format($d->price, 2, ",", ".") . "</span><br/>";
															}
														};
														?>
													</div>
												</td>
											</tr>
											<tr>
												<td style="padding:5px!important" valign="top" width="170">
													Amount Price
												</td>
												<td>:</td>
												<td style="padding:5px!important">
													Rp <?= number_format($total, 2, ",", "."); ?>*
												</td>
											</tr>
											<tr>
												<td style="padding:5px!important" valign="top" width="170">
													Payment Method
												</td>
												<td>:</td>
												<td style="padding:5px!important">
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
												</td>
											</tr>
											<tr>
												<td colspan="3">
													<span style="font-size:9pt">*The amount price above does not include online bank payment administration fees</small>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td width="4"></td>
							</tr>
						</tbody>
					</table>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>
							<tr style="font-size:14px">
								<td>
									<p style="text-align:justify;text-justify:inter-word;font-size:20px">
										Inappropriate payments or fake transactions will be automatically deleted by the system,and participants must re-register. Thank you
									</p>
									<p></p>
									<?php
									$this->load->view("template/invoice_payment_signature");
									?>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>