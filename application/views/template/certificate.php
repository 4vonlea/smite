<?php
/**
 * @var $image
 * @var $property
 */
?>
<img src="<?= $image; ?>" style="width: 100%;position: absolute;top:-1.3%"/>
<?php foreach ($property as $i=>$row): ?>
	<div
		style="text-align:center;font-size:<?= $row['style']['fontSize']; ?>px ;font-weight:<?= $row['style']['fontWeight']; ?>;position: absolute;z-index: <?=$i;?>;width:<?= $row['style']['width']; ?>%;top:<?= $row['style']['top']; ?>%;left: <?= $row['style']['left']; ?>%">
		<?= $data[$row['name']]; ?>
	</div>
<?php endforeach; ?>
