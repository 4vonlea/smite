<?php

/**
 * @var $image
 * @var $property
 */
$qr = "";
if (isset($data['qr'])) {
	ob_start();
	QRCode::png($data['qr'], false, QR_ECLEVEL_L, 4, 2);
	$qr = base64_encode(ob_get_clean());
}
header('Content-Type: text/html');
?>
<html>

<head>
	<style>
		@page {
			size: a4 landscape;
			margin: 23px 23px 0px 23px;
		}

		.page-break {
			<?= isset($anotherPage) && is_array($anotherPage)  && count($anotherPage) > 0 ?
				"page-break-after: always;" : ""
			?>
			height: 97%;
			width: 100%;
		}

		.img-fit {
			max-height: 100%;
			width: 100%;
		}
	</style>
</head>

<body>
	<div class="page-break" style="background:url(<?= $image; ?>);background-size:cover;">
		<?php if (isset($property)) foreach ($property as $i => $row) : ?>
			<div style="color:#fff;text-align:center;font-size:<?= $row['style']['fontSize']; ?>px ;font-weight:<?= $row['style']['fontWeight']; ?>;position: absolute;z-index: <?= $i; ?>;width:<?= $row['style']['width']; ?>%;top:<?= $row['style']['top']; ?>%;left: <?= $row['style']['left']; ?>%">
				<?php if ($row['name'] == 'qr_code') : ?>
					<img style="width:<?= $row['style']['width']; ?>%;position: relative" src="data:image/png;base64,<?= $qr; ?>" />
				<?php else : ?>
					<?= $data[$row['name']]; ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php if (isset($anotherPage) && is_array($anotherPage)) : ?>
	<?php foreach ($anotherPage as $row) {
			$image = file_get_contents($row['image']);
			echo '<div style="height:97%;background:url(' . $image . ');background-size:cover;"></div>';
		}
	endif; ?>
</body>

</html>