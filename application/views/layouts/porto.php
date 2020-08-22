<?php
/**
 * @var $content
 */
$theme_path = base_url("themes/porto") . "/";
?>
<!DOCTYPE html>
<html>
<head>
	<!-- Basic -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title><?= Settings_m::getSetting('site_title'); ?></title>

	<meta name="keywords" content="PIN PERDOSSI Virtual Congress"/>
	<meta name="description" content="PIN PERDOSSI Virtual Congress">
	<meta name="anonym" content="PIN PERDOSSI Virtual Congress">

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?= $theme_path; ?>img/favicon.ico" type="image/x-icon"/>
	<link rel="apple-touch-icon" href="<?= $theme_path; ?>img/apple-touch-icon.png">

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

	<!-- Web Fonts  -->
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700%7CSintony:400,700" rel="stylesheet"
	type="text/css">

	<!-- Vendor CSS -->
	<link rel="stylesheet" href="<?= $theme_path; ?>vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>vendor/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>vendor/animate/animate.min.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>vendor/simple-line-icons/css/simple-line-icons.min.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>vendor/owl.carousel/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>vendor/owl.carousel/assets/owl.theme.default.min.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>vendor/magnific-popup/magnific-popup.min.css">

	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?= $theme_path; ?>css/theme.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>css/theme-elements.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>css/theme-blog.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>css/theme-shop.css">

	<!-- Current Page CSS -->
	<link rel="stylesheet" href="<?= $theme_path; ?>vendor/rs-plugin/css/settings.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>vendor/rs-plugin/css/layers.css">
	<link rel="stylesheet" href="<?= $theme_path; ?>vendor/rs-plugin/css/navigation.css">

	<!-- Demo CSS -->
	<link rel="stylesheet" href="<?= $theme_path; ?>css/demos/demo-event.css">

	<!-- Skin CSS -->
	<link rel="stylesheet" href="<?= $theme_path; ?>css/skins/skin-event.css">

	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="<?= $theme_path; ?>css/custom.css">

	<!-- Head Libs -->
	<script src="<?= $theme_path; ?>vendor/modernizr/modernizr.min.js"></script>
	<?php if (ENVIRONMENT == "production"): ?>
		<script src="https://cdn.jsdelivr.net/npm/vue"></script>
		<?php else: ?>
			<script src="<?= base_url('themes/script/vue.js'); ?>"></script>
		<?php endif; ?>

		<style type="text/css">
			.icon-bar {
				position: fixed;
				top: 90%;
				right: 0%;
				-webkit-transform: translateY(-10%);
				-ms-transform: translateY(-10%);
				transform: translateY(-10%);
			}

			.icon-bar a {
				display: block;
				text-align: center;
				padding: 10px;
				transition: all 0.3s ease;
				color: white;
				font-size: 14px;
			}

			.icon-bar a:hover {
				background-color: #000;
			}

			.whatsapp {
				background: #6cd115;
				color: white;
			}

			.vertical-center {
				margin: 0;
				position: absolute;
				top: 50%;
				-ms-transform: translateY(-50%);
				transform: translateY(-50%);
			}
			.running-logo{
				position:fixed;bottom:0;height:50px;width:100%;background-color:#343a40;display:flex;flex-direction:column;justify-content:center;
			}
			.running-logo img{
				height: 40px;
			}
		</style>

	</head>
	<body>

		<div class="body">
			<header id="header" class="header-transparent header-transparent-dark-bottom-border header-effect-shrink"
			data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyChangeLogo': true, 'stickyStartAt': 30, 'stickyHeaderContainerHeight': 70}">
			<div class="header-body border-top-0 bg-dark box-shadow-none">
				<div class="header-container container">
					<div class="header-row">
						<div class="header-column">
							<div class="header-row">
								<div class="header-logo">
									<a href="<?=base_url();?>">
										<img alt="Porto" width="82" height="40"
										src="<?= base_url('themes/uploads/logo.png'); ?>">
									</a>
								</div>
							</div>
						</div>
						<div class="header-column justify-content-end">
							<div class="header-row">
								<div
								class="header-nav header-nav-links header-nav-dropdowns-dark header-nav-light-text order-2 order-lg-1">
								<div
								class="header-nav-main header-nav-main-mobile-dark header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-effect-2 header-nav-main-sub-effect-1">
								<nav class="collapse">
									<ul class="nav nav-pills" id="mainNav">
										<?php if (!$this->session->has_userdata('user_session')): ?>

											<li>
												<a class="nav-link" href="<?= base_url("site"); ?>">
													Home
												</a>
											</li>
											<li>
												<a class="nav-link" href="<?= base_url("site/schedules"); ?>">
													Schedule
												</a>
											</li>
											<li class="dropdown dropdown-primary">
												<a class="dropdown-toggle nav-link"
												href="<?= base_url("site/committee"); ?>">
												committee
											</a>
										</li>
										<li>
											<a class="nav-link" href="#footer">
												contact us
											</a>
										</li>
									<?php endif; ?>

									<li style="border-left: 1px solid #fff" class="dropdown dropdown-primary">
										<?php if ($this->session->has_userdata('user_session')): ?>
											<a class="dropdown-toggle nav-link"
											href="<?= base_url('member/area/#/profile'); ?>/">
											Member Area
										</a>
										<?php else: ?>
											<a class="dropdown-toggle nav-link"
											href="#">
											Member Area &nbsp;<i class="fa fa-chevron-circle-down"></i>
										</a>
										<ul class="dropdown-menu">
											<li>
												<a class="dropdown-item"
												href="<?= base_url("site/login"); ?>">
												Login
											</a>
										</li>
										<li>
											<a class="dropdown-item"
											href="<?= base_url("member/register"); ?>">
											Register
										</a>
									</li>
								</ul>
							<?php endif; ?>

						</li>
					</ul>
				</nav>
			</div>
			<button class="btn header-btn-collapse-nav" data-toggle="collapse"
			data-target=".header-nav-main nav">
			<i class="fas fa-bars"></i>
		</button>
	</div>
</div>
</div>
</div>
</div>
</div>
</header>
<div role="main" class="main">
	<?= $content; ?>
</div>

<footer id="footer" class="bg-color-quaternary">
		<img alt="" class="img-fluid pb-5 mb-5" src="<?= base_url('themes/porto'); ?>/img/4.png">
</footer>

<div class="running-logo">
	<marquee behavior="scroll" direction="left">
	<?php
	        $spplatinum       = $this->Sponsor_link_m->listspplatinum();
			$spgold       = $this->Sponsor_link_m->listspgold();
			$spsilver       = $this->Sponsor_link_m->listspsilver();
	?>
	<?php foreach($spplatinum as $sp) :?>
		<a href=<?=base_url("site/sponsor/$sp->name");?>>
			<img src="<?= base_url("themes/uploads/sponsor/$sp->logo") ?>" class="img" />
		</a>
	<?php endforeach;?>
	<?php foreach($spgold as $sp) :?>
		<a href=<?=base_url("site/sponsor/$sp->name");?>>
			<img src="<?= base_url("themes/uploads/sponsor/$sp->logo") ?>" class="img" />
		</a>
	<?php endforeach;?>
	<?php foreach($spsilver as $sp) :?>
		<a href=<?=base_url("site/sponsor/$sp->name");?>>
			<img src="<?= base_url("themes/uploads/sponsor/$sp->logo") ?>" class="img" />
		</a>
	<?php endforeach;?>
	
  </marquee>	
</div>

</div>

</div>

<script src="<?= $theme_path; ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= $theme_path; ?>vendor/jquery.appear/jquery.appear.min.js"></script>
<script src="<?= $theme_path; ?>vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="<?= $theme_path; ?>vendor/jquery.cookie/jquery.cookie.min.js"></script>
<script src="<?= $theme_path; ?>vendor/popper/umd/popper.min.js"></script>
<script src="<?= $theme_path; ?>vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= $theme_path; ?>vendor/common/common.min.js"></script>
<script src="<?= $theme_path; ?>vendor/jquery.validation/jquery.validate.min.js"></script>
<script src="<?= $theme_path; ?>vendor/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
<script src="<?= $theme_path; ?>vendor/jquery.gmap/jquery.gmap.min.js"></script>
<script src="<?= $theme_path; ?>vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
<script src="<?= $theme_path; ?>vendor/isotope/jquery.isotope.min.js"></script>
<script src="<?= $theme_path; ?>vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="<?= $theme_path; ?>vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="<?= $theme_path; ?>vendor/vide/jquery.vide.min.js"></script>
<script src="<?= $theme_path; ?>vendor/vivus/vivus.min.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="<?= $theme_path; ?>js/theme.js"></script>

<!-- Current Page Vendor and Views -->
<script src="<?= $theme_path; ?>vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="<?= $theme_path; ?>vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>

<!-- Current Page Vendor and Views -->
<script src="<?= $theme_path; ?>js/views/view.contact.js"></script>

<!-- Demo -->
<script src="<?= $theme_path; ?>js/demos/demo-event.js"></script>

<!-- Theme Custom -->
<script src="<?= $theme_path; ?>js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="<?= $theme_path; ?>js/theme.init.js"></script>
<script src="<?= base_url("themes/script/moment.min.js"); ?>"></script>

<?= $script_js; ?>

</body>
</html>
