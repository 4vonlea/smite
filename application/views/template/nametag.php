<?php
/**
 * @var $image
 * @var $property
 */


$qr = "";
if (isset($data['qr'])) {
	ob_start();
	QRCode::png($data['qr'],false,QR_ECLEVEL_L,4,2);
	$qr = base64_encode(ob_get_clean());
}
header('Content-Type: text/html');

?>
<style>
	@page{
		size: 9.5 cm 13.5 cm;
		margin: 0;
	}
</style>
<body style="width: 9.5cm;height: 13.5cm">
<img src="<?= $image; ?>" style="height:100%;width:100%;object-fit:cover;position: absolute;"/>
<?php foreach ($property as $i=>$row): ?>

	<div
		style="text-align:center;font-size:<?= $row['style']['fontSize']; ?>px ;font-weight:<?= $row['style']['fontWeight']; ?>;position: absolute;z-index: <?=$i;?>;width:<?= $row['style']['width']; ?>%;top:<?= $row['style']['top']; ?>%;left: <?= $row['style']['left']; ?>%">
			<?php if($row['name'] == 'qr'):?>
				<img
					style="width:<?= $row['style']['fontSize']; ?>%;position: relative" src="data:image/png;base64,<?= $qr; ?>"/>
			<?php else: ?>
				<?= $data[$row['name']]; ?>
			<?php endif;?>

	</div>
<?php endforeach; ?>
</body>
