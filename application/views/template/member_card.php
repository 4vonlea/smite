<?php
/**
 * @var $event
 * @var $member
 */
ob_start();
QRCode::png($member['id']);
$image = base64_encode(ob_get_clean());
header('Content-Type: text/html');
?>
<style>
	@page{
		size: 14.1 cm 21 cm;
	}
	.wrapper {
		width: 100%;
		height: 18 cm;
		border: 1px solid;
		text-align: center;
	}
</style>
<div class="wrapper">
	<table>
		<tr>
			<td>
				<img src="data:image/png;base64,<?= $image; ?>" height="120px"/>
			</td>
		</tr>
	</table>
	<div>
		<h2>PESERTA</h2>
		<h2><?= $event['name']; ?></h2>
		<h2>East INSDV 2020</h2>
		<br/>
		<h2>Tema:</h2>
		<h2>"<?= $event['theme']; ?>"</h2>
		<h2><?= $event['held_on']; ?></h2>
		<h2><?= $event['held_in']; ?></h2>
		<h1 style="text-transform: uppercase"><?=$member['fullname'];?></h1>
	</div>
</div>

