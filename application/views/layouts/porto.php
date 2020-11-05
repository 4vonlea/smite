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
				top: 80%;
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
				font-size: 18px;
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
				position:fixed;bottom:0;height:50px;width:100%;background-color:#FFFFFFFF;display:flex;flex-direction:column;justify-content:center;
			}
			.running-logo img{
				height: 40px;
			}

			.contai {
				position: relative;
				overflow: hidden;
				width: 100%;
				padding-top: 56.25%; /* 16:9 Aspect Ratio (divide 9 by 16 = 0.5625) */
			}

			/* Then style the iframe to fit in the container div with full height and width */
			.responsive-iframe {
				position: absolute;
				top: 0;
				left: 0;
				bottom: 0;
				right: 0;
				width: 100%;
				/*height: 500px;*/
			}
		</style>

	</head>
	<body>

		<div class="body">
			<header id="header" class="header-effect-shrink"
			data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyChangeLogo': true, 'stickyStartAt': 30, 'stickyHeaderContainerHeight': 70}">
			<div class="header-body border-top-0 bg-dark box-shadow-none">
				<div class="header-container container">
					<div class="header-row">
						<div class="header-column">
							<div class="header-row">
								<div class="header-logo">
									<a href="<?=base_url();?>">
										<img alt="Porto" width="155" height="40"
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
													Beranda
												</a>
											</li>
											<li>
												<a class="nav-link" href="<?= base_url("site/schedules"); ?>">
													Jadwal
												</a>
											</li>
											<li class="dropdown dropdown-primary">
												<a class="dropdown-toggle nav-link"
												href="<?= base_url("site/committee"); ?>">
												Panitia
											</a>
										</li>
										<li>
											<a class="nav-link" href="#footer">
												Hubungi kami
											</a>
										</li>
									<?php endif; ?>
									<?php if ($this->session->has_userdata('user_session')): ?>
									<li>
										<a class="nav-link" href="<?= base_url("site/vid"); ?>">
											Vote foto & video
										</a>
									</li>
									<?php endif;?>

									<li style="border-left: 1px solid #fff" class="dropdown dropdown-primary">
										<?php if ($this->session->has_userdata('user_session')): ?>
											<a class="dropdown-toggle nav-link"
											href="<?= base_url('member/area/#/profile'); ?>/">
											Area Pengguna
										</a>
										<?php else: ?>
											<a class="dropdown-toggle nav-link"
											href="#">
											Area Pengguna &nbsp;<i class="fa fa-chevron-circle-down"></i>
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
											Registrasi
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

<?php
$spplatinum       = $this->Sponsor_link_m->listspplatinum();
$spgold       = $this->Sponsor_link_m->listspgold();
$spsilver       = $this->Sponsor_link_m->listspsilver();
?>

<section id="sponsor" class="bg-color-light">
	<div class="container mt-4 pt-4 pb-4">
		<div class="row pt-2">
			<div class="col">
				<h2 class="text-color-dark text-uppercase font-weight-bold text-center mb-1">Sponsor</h2>
				<p class="custom-font-size-1 text-center mb-5">Terima kasih untuk sponsor kami</p>
			</div>
		</div>
		<span class="text-color-biru appear-animation" data-appear-animation = "fadeInUpBig" data-appear-animation-delay ="0" style="font-size: 30px;"><b>Platinum Sponsor</b></span>
		<hr>
		<div class="row">
			<?php
			foreach ($spplatinum as $platinum):
				?>
				<div class="col-lg-3 col-xs-6 appear-animation" data-appear-animation = "fadeInUpBig" data-appear-animation-delay = "600">
					<span class="thumb-info thumb-info-no-borders thumb-info-no-borders-rounded thumb-info-lighten thumb-info-centered-info thumb-info-block thumb-info-block-dark">
						<span class="thumb-info-wrapper">
							<img src="<?= base_url('themes/uploads/sponsor') ?>/<?= $platinum->logo ?>" class="img-responsive">
							<span class="thumb-info-title">
								<span class="thumb-info-inner"><?= $platinum->name ?></span>
								<a class="btn btn-info btn-lg" data-spsr="<?= $platinum->name ?>" data-toggle="modal" data-target="#sponsormodal" onclick="return setPopUp($(this));"><i class="fas fa-search"></i></a>
							</span>
						</span>
					</span>
				</div>
			<?php endforeach; ?>
		</div>

		<hr>
		<span class="text-color-biru appear-animation" data-appear-animation = "fadeInUpBig" data-appear-animation-delay = "900" style="font-size: 30px;"><b>Gold Sponsor</b></span>
		<hr>

		<div class="row">
			<?php
			foreach ($spgold as $gold):
				?>
				<div class="col-lg-2 appear-animation" data-appear-animation = "fadeInUpBig" data-appear-animation-delay = "1200">
					<span class="align-middle thumb-info thumb-info-no-borders thumb-info-no-borders-rounded thumb-info-lighten thumb-info-centered-info thumb-info-block thumb-info-block-dark mx-1 my-1">
						<span class="thumb-info-wrapper">
							<img src="<?= base_url('themes/uploads/sponsor') ?>/<?= $gold->logo ?>" class="img-fluid">
							<span class="thumb-info-title">
								<span class="thumb-info-inner" style="font-size: 12px;"><?= $gold->name ?></span>
								<a class="btn btn-info btn-lg" data-spsr="<?= $gold->name ?>" data-toggle="modal" data-target="#sponsormodal" onclick="return setPopUp($(this));"><i class="fas fa-search"></i></a>
							</span>
						</span>
					</span>
				</div>
			<?php endforeach; ?>
		</div>
		<br>
		<hr>
		<span class="text-color-biru appear-animation" data-appear-animation = "fadeInUpBig" data-appear-animation-delay = "1500" style="font-size: 30px;"><b>Silver Sponsor</b></span>
		<hr>
		<div class="row">
			<div class="col-lg-12 appear-animation" data-appear-animation = "fadeInUpBig" data-appear-animation-delay = "1500">
				<div class="owl-carousel owl-theme" data-plugin-options="{'items': 6, 'autoplay': true, 'autoplayTimeout': 3000}">
					<?php 
					foreach ($spsilver as $silver):
						?>
						<div>
							<center>
								<a href="#" class="" data-spsr="<?= $silver->name ?>" data-toggle="modal" data-target="#sponsormodal" onclick="return setPopUp($(this));">
									<img class="img-fluid px-5" src="<?= base_url('themes/uploads/sponsor'); ?>/<?= $silver->logo?>" style="" alt="">
								</a>
							</center>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="icon-bar">
	<a href="https://wa.me/6281575099960" target="_blank" class="whatsapp img-fluid"><i class="fab fa-whatsapp"> Perlu bantuan ?</i></a> 
</div>


<!-- Modal -->
<div id="sponsormodal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header"></div>
			<div class="modal-body">
				<div class='contai'>
				</div>
			</div>
			<div class="modal-footer">

			</div>
		</div>
	</div>
</div>

<footer id="footer" class="bg-color-quaternary">
	<img alt="" class="img-fluid pb-5" src="<?= base_url('themes/porto'); ?>/img/footerbg.png">
</footer>

<div class="running-logo">
	<marquee behavior="scroll" direction="left">
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
<script src="<?= $theme_path; ?>vendor/jquery.countdown/jquery.countdown.min.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="<?= $theme_path; ?>js/theme.js"></script>

<!-- Current Page Vendor and Views -->
<script src="<?= $theme_path; ?>vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="<?= $theme_path; ?>vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>

<!-- Current Page Vendor and Views -->
<script src="<?= $theme_path; ?>js/views/view.contact.js"></script>

<!-- Demo -->
<script src="<?= $theme_path; ?>js/demos/demo-business-consulting.js"></script>

<!-- Theme Custom -->
<script src="<?= $theme_path; ?>js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="<?= $theme_path; ?>js/theme.init.js"></script>
<script src="<?= base_url("themes/script/moment.min.js"); ?>"></script>

<script type="text/javascript">
	function setPopUp(dom) {
		var spsr = dom.data('spsr');
		$("#sponsormodal .modal-body").html("<iframe class='responsive-iframe' src='<?= base_url('site/sponsor') ?>/"+spsr+"' frameborder = '1' allowfullscreen></iframe>");
		$("#sponsormodal .modal-header").html("<span id='item-popup'></span><button type='button' class='close' data-dismiss='modal'>&times;</button>");
		$("#item-popup").html(spsr);
	}
</script>


<?= $script_js; ?>

</body>
</html>
