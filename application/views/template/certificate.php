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
<img src="<?= $image; ?>" style="width: 100%;position: absolute;top:-1.3%"/>
<?php if(isset($property)) foreach ($property as $i=>$row): ?>
	<div
		style="text-align:center;font-size:<?= $row['style']['fontSize']; ?>px ;font-weight:<?= $row['style']['fontWeight']; ?>;position: absolute;z-index: <?=$i;?>;width:<?= $row['style']['width']; ?>%;top:<?= $row['style']['top']; ?>%;left: <?= $row['style']['left']; ?>%">
		<?php if($row['name'] == 'qr_code'):?>
			<img
				style="width:<?= $row['style']['width']; ?>%;position: relative" src="data:image/png;base64,<?= $qr; ?>"/>
		<?php else: ?>
			<?= $data[$row['name']]; ?>
		<?php endif;?>
	</div>
<?php endforeach; ?>
