<?php
/**
 * @var $image
 * @var $property
 */
?>
<img src="<?= $image; ?>" style="width: 100%;"/>
<?php foreach ($property as $row): ?>
	<div
		style="position: absolute;top:<?= $row['style']['top']; ?>;left: <?= $row['style']['left']; ?>">
		<?= $data[$row['name']]; ?>
	</div>
<?php endforeach; ?>
