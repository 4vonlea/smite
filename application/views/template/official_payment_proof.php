<?php
/**
 * @var Transaction_m $transaction
 */
$header_image = base_url('themes/uploads/header_kop.jpg');
$member = $transaction->member;

ob_start();
QRCode::png("payment_proof;".$transaction->id,false,QR_ECLEVEL_L,4,2);
$qr = base64_encode(ob_get_clean());
header('Content-Type: text/html');
?>
<style>
	.table{
		border-collapse: collapse;width: 90%;
		margin-top: 15px;
		margin-bottom: 15px;
	}
	.table th,.table td{
		padding: 5px 10px;
		vertical-align: top;
	}
	.table th{
		text-align: left;
	}
</style>
<table border="0" cellpadding="0" cellspacing="0" style="width:628px">
	<tr style="height:22px">
		<td>
			<img src="<?= $header_image; ?>" width="720"/>
		</td>
	</tr>
	<tr style="height:30px">
		<td style="text-align:center;width:720px;height:30px">
			<p>
				<span
					style="font-family:times new roman,times,serif;font-size:12pt"><strong>BUKTI REGISTRASI - KUITANSI</strong></span>
			</p>
			<p style="text-align:right">
				<span
					style="font-family:times new roman,times,serif;font-size:12pt;text-align:start;background-color:#ffffff"><?=  date("d F Y", strtotime($transaction->updated_at)); ?></span>
			</p>
			<p style="text-align:left">
				<span
					style="font-family:times new roman,times,serif;font-size:12pt;text-align:start;background-color:#ffffff">Yth. <?= $member->fullname; ?></span>
			</p>
			<p style="text-align:left">
				<span
					style="font-family:times new roman,times,serif;font-size:12pt;text-align:start;background-color:#ffffff">
					Terimakasih atas registrasi dan pembayaran yang dilakukan untuk berpartisipasi dalam acara <?=Settings_m::getSetting("text_payment_proof");?>. Berikut adalah rincian registrasi dan pembayaran:
					</span>
				<!-- <span
					style="font-family:times new roman,times,serif;font-size:12pt;text-align:start;background-color:#ffffff">
					The details of
					your registration is shown below:
				</span> -->
			</p>
		</td>
	</tr>
	<tr>
		<td align="center">
			<table class="table">
				<tr>
					<th width="170">ID INVOICE</th>
					<td width="10">:</td>
					<td><?=$transaction->id;?></td>
				</tr>
				<tr>
					<th>Nama Peserta</th>
					<td>:</td>
					<td><?=$member->fullname;?></td>
				</tr>
				<tr>
					<th>Status</th>
					<td>:</td>
					<td><?=$member->status_member->kategory;?></td>
				</tr>
				<?php if($member->sponsor):?>
				<tr>
					<th>Sponsor</th>
					<td>:</td>
					<td><?=$member->sponsor;?></td>
				</tr>
				<?php endif;?>
				<tr>
					<th>Acara yang diikuti</th>
					<td>:</td>
					<td>
						<ul style="margin: 0px;padding-left:15px">
							<?php
							$total = 0;
							foreach($transaction->detailsWithEvent() as $d){
								$total += $d->price;
								echo "<li>$d->product_name :  <br/>Rp ".number_format($d->price, 2, ",", ".")."</li>";
							};
							?>
						</ul>
					</td>
				</tr>
				<tr>
					<th>Total Harga</th>
					<td>:</td>
					<td>Rp <?= number_format($total,2,",",".");?></td>
				</tr>
				<tr>
					<th>
						Metode Pembayaran
					</th>
					<td>:</td>
					<td>
						<?= strtoupper($transaction->channel); ?>
					</td>
				</tr>
			</table>
		</td>
	</tr> <br/>
	<tr style="height:20px">
		<td style="text-align:left;width:720px;height:20px">
			<p>
				Bukti pembayaran (kuitansi) ini merupakan tanda bukti yang sah dan dipergunakan sebagaimana mestinya. Peserta wajib menunjukkan kuitansi ini kepada panitia pada saat registrasi ulang. Terimakasih
			</p> <br/><br/>
		</td>
	</tr>
	<tr >
		<td >
			<div style="bottom: 80px;left:0px;position: absolute">
				<img style="margin: auto" src="data:image/png;base64,<?= $qr; ?>"/>
			</div>
			<div style="float: right;width: 70%;height: 100%;">
				<p style="text-align:right;font-size: 18px;font-weight: bold">Salam hormat,<br/>
					Ketua Panitia <br/><br/>
					<img width="200px" height="100px" class="" src="<?=base_url("themes/uploads/ttd_cap.jpg");?>"> <br/>
					<?=Settings_m::getSetting('ketua_panitia');?>
				</p>
			</div>
		</td>
	</tr>
</table>
