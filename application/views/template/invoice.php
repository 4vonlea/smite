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
setlocale (LC_TIME, 'id_ID');
$payment = Settings_m::manualPayment(false);
?>
<style>
	.table th,.table td{
		vertical-align: top;
	}
</style>
<table border="0" cellpadding="0" cellspacing="0" style="width: 700px;margin-right: auto;margin-left: auto">
	<tbody>
	<tr>
		<td height="30" style="text-align:center">
			<img style="width: 660px" src="<?= $header_image; ?>" tabindex="0" width="700"/>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" style="width: 500px;margin-right: auto;margin-left: auto;padding-bottom: 15px">
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
			<table border="0" cellpadding="0" class="table" cellspacing="0" style="width: 660px;vertical-align: top">
				<tbody>
				<tr>
					<td>
						<p>Berikut kami kirimkan tagihan pembayaran sebagai bentuk keterangan resmi. Mohon segera melunasi pembayaran ini melalui <br/>
							<?php if(count($payment) == 1) :?>
								Bank <?=$payment[0]['bank'];?> No <?=$payment[0]['no_rekening'];?> a.n <?=$payment[0]['holder'];?>
							<?php elseif(count($payment) > 1):?>
						<ul>
							<?php foreach ($payment as $list):?>
								<li>Bank <?=$list['bank'];?> No <?=$list['no_rekening'];?> a.n <?=$list['holder'];?></li>
							<?php endforeach;?>
						</ul>
						<?php endif;?>
						</p>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
							<tbody>
							<tr>
								<td style="padding:5px!important" valign="top" width="170">
									Tanggal
								</td>
								<td width="10">:</td>
								<td style="padding:5px!important">
									<?php if($transaction->checkout == 0):?>
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
									Nama Lengkap
								</td>
								<td>:</td>
								<td style="padding:5px!important">
									<?= $member->fullname; ?>
								</td>
							</tr>
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
							<tr>
								<td style="padding:5px!important" valign="top" width="170">
									Status
								</td>
								<td>:</td>
								<td style="padding:5px!important">
									<?= $member->status_member->kategory; ?>
								</td>
							</tr>
							<tr>
								<td style="padding:5px!important" valign="top" width="170">
									Acara yang diikuti
								</td>
								<td>:</td>
								<td style="padding:5px!important;vertical-align: top">

									<ul style="margin:0px;padding-left: 15px">
									<?php
									$total = 0;
									foreach ($transaction->detailsWithEvent() as $d) {
										$total += $d->price;
										echo "<li>$d->product_name : <br/>Rp ".number_format($d->price, 2, ",", ".")."</li>";
									};
									?>
									</ul>
								</td>
							</tr>
							<tr>
								<td style="padding:5px!important" valign="top" width="170">
									Total Harga
								</td>
								<td>:</td>
								<td style="padding:5px!important">
									Rp <?= number_format($total, 2, ",", "."); ?>
								</td>
							</tr>
							<tr>
								<td style="padding:5px!important" valign="top" width="170">
									Metode Pembayaran
								</td>
								<td>:</td>
								<td style="padding:5px!important">
									<?= strtoupper($transaction->channel); ?>
								</td>
							</tr>
							</tbody>
						</table>
					</td>
					<td width="4"></td>
				</tr>
				</tbody>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="500">
				<tbody>
				<tr style="font-size:14px">
					<td>

						<p style="font-size: 20px">
							Pembayaran yang tidak sesuai atau transaksi palsu akan secara otomatis terhapus oleh sistem,
							dan peserta harus melakukan registrasi ulang. Terimakasih
						</p>
						<p></p>
						<p style="text-align:right;font-size: 20px;font-weight: bold">Salam hormat,<br/> 
						Ketua Panitia <br/><br/>
							 <img width="200px" height="100px" class="" src="themes/porto/img/ttd.png"> <br/>
							<?=Settings_m::getSetting('ketua_panitia');?>
						</p>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
