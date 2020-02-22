<?php
/**
 * @var $content
 */
$theme_path = base_url("themes/kingster") . "/";
?>
<!DOCTYPE html>
<html lang="en-US" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?= Settings_m::getSetting('site_title'); ?></title>

	<link rel='stylesheet' href='<?=$theme_path;?>plugins/goodlayers-core/plugins/combine/style.css' type='text/css' media='all' />
	<link rel='stylesheet' href='<?=$theme_path;?>plugins/goodlayers-core/include/css/page-builder.css' type='text/css' media='all' />
	<link rel='stylesheet' href='<?=$theme_path;?>plugins/revslider/public/assets/css/settings.css' type='text/css' media='all' />
	<link rel='stylesheet' href='<?=$theme_path;?>css/style-core.css' type='text/css' media='all' />
	<link rel='stylesheet' href='<?=$theme_path;?>css/kingster-style-custom.css' type='text/css' media='all' />
	<link rel='stylesheet' href='<?=$theme_path;?>css/bootstrap.min.css' type='text/css' media='all' />
	<link rel="stylesheet" href="<?= $theme_path; ?>plugins/fontawesome-free/css/all.min.css">

	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:700%2C400" rel="stylesheet" property="stylesheet" type="text/css" media="all">
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2Cregular%2Citalic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CABeeZee%3Aregular%2Citalic&amp;subset=latin%2Clatin-ext%2Cdevanagari&amp;ver=5.0.3' type='text/css' media='all' />

	<script type='text/javascript' src='<?=base_url('themes/script/jquery/dist/jquery.min.js');?>'></script>
	<script type='text/javascript' src='<?=$theme_path;?>js/jquery/jquery-migrate.min.js'></script>

	<?php if (ENVIRONMENT == "production"): ?>
		<script src="https://cdn.jsdelivr.net/npm/vue"></script>
	<?php else: ?>
		<script src="<?= base_url('themes/script/vue.js'); ?>"></script>
	<?php endif; ?>
	<?= $additional_head;?>
</head>

<body class="home page-template-default page page-id-2039 gdlr-core-body woocommerce-no-js tribe-no-js kingster-body kingster-body-front kingster-full  kingster-with-sticky-navigation  kingster-blockquote-style-1 gdlr-core-link-to-lightbox">
<!--For Mobile Navigation View-->
<div class="kingster-mobile-header-wrap">
	<div class="kingster-mobile-header kingster-header-background kingster-style-slide kingster-sticky-mobile-navigation " id="kingster-mobile-header">
		<div class="kingster-mobile-header-container kingster-container clearfix">
			<div class="kingster-logo  kingster-item-pdlr">
				<div class="kingster-logo-inner">
					<a class="" href="index.html">
						<img alt="Logo" style="max-height: 50px"
							 src="<?= base_url('themes/uploads/logo.png'); ?>">
					</a>
				</div>
			</div>
			<div class="kingster-mobile-menu-right">
				<div class="kingster-main-menu-search" id="kingster-mobile-top-search"><i class="fa fa-search"></i></div>
				<div class="kingster-top-search-wrap">
					<div class="kingster-top-search-close"></div>
					<div class="kingster-top-search-row">
						<div class="kingster-top-search-cell">
							<form role="search" method="get" class="search-form" action="#">
								<input type="text" class="search-field kingster-title-font" placeholder="Search..." value="" name="s">
								<div class="kingster-top-search-submit"><i class="fa fa-search"></i></div>
								<input type="submit" class="search-submit" value="Search">
								<div class="kingster-top-search-close"><i class="icon_close"></i></div>
							</form>
						</div>
					</div>
				</div>
				<div class="kingster-mobile-menu"><a class="kingster-mm-menu-button kingster-mobile-menu-button kingster-mobile-button-hamburger" href="#kingster-mobile-menu"><span></span></a>
					<div class="kingster-mm-menu-wrap kingster-navigation-font" id="kingster-mobile-menu" data-slide="right">
						<ul id="menu-main-navigation" class="m-menu">
							<li class="menu-item menu-item-home current-menu-item menu-item-has-children"><a href="<?= base_url("site"); ?>">Home</a>		
							</li>
							<li class="menu-item menu-item-has-children"><a href="<?= base_url("site/committee"); ?>">Committee</a>
							</li>
							<li class="menu-item menu-item-has-children"><a href="<?= base_url("site/paper"); ?>">Call for Abstract</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--End For Mobile Navigation View-->

<!--For Desktop View-->
<div class="kingster-body-outer-wrapper">
	<div class="kingster-body-wrapper clearfix  kingster-with-frame">
		<!--Desktop Navigation-->
		<div class="kingster-top-bar">
			<div class="kingster-top-bar-background"></div>
			<div class="kingster-top-bar-container kingster-container ">
				<div class="kingster-top-bar-container-inner clearfix">
					<div class="kingster-top-bar-left kingster-item-pdlr"><i class="fa fa-envelope-open-o" id="i_983a_0"></i>pinperdossi2020banjarmasin@gmail.com<i class="fa fa-phone" id="i_983a_1"></i>0821 5490 0203</div>
					<div class="kingster-top-bar-right kingster-item-pdlr">
						<ul id="kingster-top-bar-menu" class="sf-menu kingster-top-bar-menu kingster-top-bar-right-menu">
							
						</ul>
						<div class="kingster-top-bar-right-social"></div><a class="kingster-top-bar-right-button" href="#" target="_blank">PIN PERDOSSI2020</a></div>
				</div>
			</div>
		</div>
		<header class="kingster-header-wrap kingster-header-style-plain  kingster-style-menu-right kingster-sticky-navigation kingster-style-fixed" data-navigation-offset="75px">
			<div class="kingster-header-background"></div>
			<div class="kingster-header-container  kingster-container">
				<div class="kingster-header-container-inner clearfix">
					<div class="kingster-logo  kingster-item-pdlr">
						<div class="kingster-logo-inner">
							<a class="" href="index.html">
								<img alt="Logo" style="max-height: 50px"
									 src="<?= base_url('themes/uploads/logo.png'); ?>">
							</a>
						</div>
					</div>
					<div class="kingster-navigation kingster-item-pdlr clearfix ">
						<div class="kingster-main-menu" id="kingster-main-menu">
							<ul id="menu-main-navigation-1" class="sf-menu">
								<li class="menu-item menu-item-home kingster-normal-menu"><a href="<?= base_url("site"); ?>" class="sf-with-ul-pre">Home</a></li>
								<li class="menu-item menu-item-has-children kingster-normal-menu"><a href="<?= base_url("site/committee"); ?>" class="sf-with-ul-pre">Committee</a></li>
								<li class="menu-item menu-item-has-children kingster-mega-menu"><a href="<?= base_url("site/paper"); ?>" class="sf-with-ul-pre">Call for Abstract</a></li>
							</ul>
							<div class="kingster-navigation-slide-bar" id="kingster-navigation-slide-bar"></div>
						</div>
						
					</div>
				</div>
			</div>
		</header>
		<!--End Desktop Navigation-->

<!--		<div class="kingster-page-wrapper" id="kingster-page-wrapper">-->
<!--			<div class="gdlr-core-page-builder-body">-->
				<?= $content; ?>
<!--			</div>-->
<!--		</div>-->


		<footer>
			<div class="kingster-footer-wrapper ">
				<div class="kingster-footer-container kingster-container clearfix">
					<div class="kingster-footer-column kingster-item-pdlr kingster-column-60">
						<div id="text-2" class="widget widget_text kingster-widget">
							<div class="textwidget">
								<p><img src="<?=base_url('themes/kingster');?>/upload/footer-logo.png" alt="" />
									<br /> <span class="gdlr-core-space-shortcode" id="span_983a_1"></span>
									<br /> Departement of Neurology
									<br />  Medical Faculty of Lambung Mangkurat University
									<br /> Ulin General Hospital
									<br /> Jl. A. Yani km. 2,5, Sungai Baru Banjarmasin Tengah District Banjarmasin, South Kalimantan 70122</p>
								<p><span id="span_983a_2">0821 5490 0203</span>
									<br /> <span class="gdlr-core-space-shortcode" id="span_983a_3"></span>
									<br /> <a id="a_983a_0" href="">pinperdossi2020banjarmasin@gmail.com</a></p>
								<div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-left-align">
									<div class="gdlr-core-divider-line gdlr-core-skin-divider" id="div_983a_49"></div>
								</div>
							</div>
						</div>
					</div>
					
					
				</div>
			</div>

			<div class="kingster-copyright-wrapper">
				<div class="kingster-copyright-container kingster-container clearfix">
					<div class="kingster-copyright-left kingster-item-pdlr"><b>Copyright All Right Reserved 2019, Max Themes</b></div>
					<div class="kingster-copyright-right kingster-item-pdlr">
						<div class="gdlr-core-social-network-item gdlr-core-item-pdb  gdlr-core-none-align" id="div_983a_50">
							<a href="#" target="_blank" class="gdlr-core-social-network-icon" title="facebook">
								<i class="fa fa-facebook" ></i>
							</a>
							<a href="#" target="_blank" class="gdlr-core-social-network-icon" title="google-plus">
								<i class="fa fa-google-plus" ></i>
							</a>
							<a href="#" target="_blank" class="gdlr-core-social-network-icon" title="linkedin">
								<i class="fa fa-linkedin" ></i>
							</a>
							<a href="#" target="_blank" class="gdlr-core-social-network-icon" title="skype">
								<i class="fa fa-skype" ></i>
							</a>
							<a href="#" target="_blank" class="gdlr-core-social-network-icon" title="twitter">
								<i class="fa fa-twitter" ></i>
							</a>
							<a href="#" target="_blank" class="gdlr-core-social-network-icon" title="instagram">
								<i class="fa fa-instagram" ></i>
							</a>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
</div>

<script type='text/javascript' src='<?=$theme_path;?>plugins/goodlayers-core/plugins/combine/script.js'></script>
<script type='text/javascript'>
	var gdlr_core_pbf = {
		"admin": "",
		"video": {
			"width": "640",
			"height": "360"
		},
		"ajax_url": "#"
	};
</script>
<script type='text/javascript' src='<?=$theme_path;?>plugins/goodlayers-core/include/js/page-builder.js'></script>
<script type='text/javascript' src='<?=$theme_path;?>js/jquery/ui/effect.min.js'></script>
<script type='text/javascript'>
	var kingster_script_core = {
		"home_url": "index.html"
	};
</script>
<script type='text/javascript' src='<?=$theme_path;?>js/plugins.min.js'></script>
<script type='text/javascript' src='<?=$theme_path;?>js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?=base_url();?>/themes/script/moment.min.js'></script>
<?= $script_js; ?>

</body>
</html>
