<!doctype html>
<html class="no-js" lang="en">

<head>
	<meta charset="utf-8">
	<title><?= Settings_m::getSetting('site_title'); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- site favicon -->
	<link rel="icon" type="image/png" href="<?=base_url('themes/bigamer');?>/assets/images/favicon.png">
	<!-- Place favicon.ico in the root directory -->

	<!-- All stylesheet and icons css  -->
	<link rel="stylesheet" href="<?=base_url('themes/bigamer');?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url('themes/bigamer');?>/assets/css/animate.css">
	<link rel="stylesheet" href="<?=base_url('themes/bigamer');?>/assets/css/icofont.min.css">
	<link rel="stylesheet" href="<?=base_url('themes/bigamer');?>/assets/css/swiper.min.css">
	<link rel="stylesheet" href="<?=base_url('themes/bigamer');?>/assets/css/lightcase.css">
	<link rel="stylesheet" href="<?=base_url('themes/bigamer');?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url('themes/bigamer');?>/assets/css/style.min.css">
	<link rel="stylesheet" href="<?=base_url('themes/bigamer');?>/assets/css/custom.css">
    <?php if (ENVIRONMENT == "production") : ?>
        <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <?php else : ?>
        <script src="<?= base_url('themes/script/vue.js'); ?>"></script>
    <?php endif; ?>
    <?= $additional_head; ?>

</head>

<body>
	<!-- preloader start here -->
    <div class="preloader">
        <div class="preloader-inner">
			<div class="preloader-icon">
				<span></span>
				<span></span>
            </div>
        </div>
    </div>
	<!-- preloader ending here -->

	<!-- scrollToTop start here -->
    <a href="#" class="scrollToTop"><i class="icofont-rounded-up"></i></a>
    <!-- scrollToTop ending here -->

	<!-- ==========Header Section Starts Here========== -->
	<header class="header-section">
		<div class="container">
			<div class="header-holder d-flex flex-wrap justify-content-between align-items-center">
				<div class="brand-logo d-none d-lg-inline-block">
					<div class="logo">
						<a href="index.html">
							<img src="<?=base_url('themes/bigamer');?>/assets/images/logo/logo.png" alt="logo">
						</a>
					</div>
				</div>
				<div class="header-menu-part">
					<div class="header-top">
						<div class="header-top-area">
							<ul class="left">
								<li>
									<i class="icofont-ui-call"></i> <a href="http://wa.me/6289603215099" target="_BLANK"><span>089603215099</span></a>
								</li>
								<li>
									<i class="icofont-email"></i> <a href="mailto:admin@pinperdossicirebon2022.com" target="_BLANK"> <span>Mail : Admin</span></a>
								</li>
								<li>
									<i class="icofont-location-pin"></i> Cirebon
								</li>
							</ul>
							<ul class="social-icons d-flex align-items-center">
								<li>
									<a href="#" class="youtube"><i class="icofont-youtube-play"></i></a>
								</li>
								<li>
									<a href="#" class="twitter"><i class="icofont-twitter"></i></a>
								</li>
								<li>
									<a href="#" class="instagram"><i class="icofont-instagram"></i></a>
								</li>
								<li>
									<a href="http://wa.me/6289603215099" target="_BLANK" class="whatsapp"><i class="icofont-whatsapp"></i></a>
								</li>
							</ul>
						</div>
					</div>
					<div class="header-bottom">
						<div class="header-wrapper justify-content-lg-end">
							<div class="mobile-logo d-lg-none">
								<a href="index.html"><img src="<?=base_url('themes/bigamer');?>/assets/images/logo/logo.png" alt="logo"></a>
							</div>
							<div class="menu-area">
								<ul class="menu">

									<li><a href="<?=base_url('site/home');?>#home">Home</a></li>
									<li><a href="<?=base_url('site/home');?>#sign-in">Sign In</a></li>
									<li><a href="<?=base_url('site/home');?>#event">Event</a></li>
									
								</ul>
								<!-- <a href="login.html" class="login"><i class="icofont-user"></i> <span>LOG IN</span> </a> -->
								<a href="<?=base_url('member/register');?>" class="signup"><i class="icofont-users"></i> <span>Registration</span></a>

								<!-- toggle icons -->
								<div class="header-bar d-lg-none">
									<span></span>
									<span></span>
									<span></span>
								</div>
								<div class="ellepsis-bar d-lg-none">
									<i class="icofont-info-square"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>



    <?= $content; ?>
    
	<!-- ================ footer Section start Here =============== -->
    <footer class="footer-section">
        <div class="footer-middle padding-bottom" style="background-image: url(<?=base_url('themes/bigamer');?>/assets/images/footer/bg-2.jpg);">
            <div class="container">
                <div class="row padding-lg-top">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-middle-item-wrapper">
                            <div class="footer-middle-item mb-lg-0">
                                <div class="fm-item-title">
                                    <h5>Tanggal Penting</h5>
									<hr>
                                </div>
                                <div class="fm-item-content">
									<ul style="font-size: 15px;">
										<li>Batas Pengumpulan CV dan judul untuk SKP IDI : 10 Agustus 2022</li>
										<li>Batas Pengumpulan Abstrak : 12 Oktober 2022</li>
										<li>Pengumuman Penerimaan Abstrak : 24 Oktober 2022</li>
										<li>Program Ilmiah : 17 – 18 November 2022 WS, dan 19– 20 November 2022 Simposium</li>
										<li>E-Poster & Presentasi Oral : 19 November 2022</li>
									</ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-middle-item-wrapper">
                            <div class="footer-middle-item mb-lg-0">
                                <div class="fm-item-title">
                                    <h5>Simposium</h5>
									<hr>
                                </div>
                                <div class="fm-item-content">
                                    <ul style="font-size: 15px;">
										<li>Stroke dan Pembuluh Darah,</li>
										<li>Neurointervensi</li>
										<li>Neurointensif</li>
										<li>Neurobehaviour</li>
										<li>Neurorestorasi</li>
										<li>Neurogeriatri</li>
										<li>Neuroinfeksi</li>
										<li>Neuroimaging</li>
										<li>Neuroonkologi</li>
									</ul>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-middle-item-wrapper">
                            <div class="footer-middle-item mb-lg-0">
                                <div class="fm-item-title">
                                    <h5>Biaya Registrasi</h5>
									<hr>
                                </div>
                                <div class="fm-item-content">
                                    <ul style="font-size: 15px;">
										<b>Early Bird</b> <br>
										<li>Spesialist : Rp. 3.000.000</li>
										<li>Resident : Rp. 1.500.000</li>
										<li>GP : Rp. 1.500.000</li> <br>
										<b>Early Bird</b> <br>
										<li>Spesialist : Rp. 3.500.000</li>
										<li>Resident : Rp. 2.000.000</li>
										<li>GP : Rp. 2.000.000</li>
									</ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-middle-item-wrapper">
                            <div class="footer-middle-item mb-lg-0">
                                <div class="fm-item-title">
                                    <h5>Sign In / Registration</h5>
									<hr>
                                </div>
                                <div class="fm-item-content">
                                    <div class="d-grid">
										<a href="login.html" class="btn btn-edge btn-purple"> Sign In</a><br>
										<a href="register.html" class="btn btn-edge btn-purple"> Registrasi Individu</a><br>
										<a href="register-group.html" class="btn btn-edge btn-purple"> Registrasi Grup/Kelompok</a><br>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="footer-bottom-content text-center">
                            <p>&copy;2022 <a href="#home">PIN PERDOSSI CIREBON</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- ================ footer Section end Here =============== -->


	<!-- All Needed JS -->
	<script src="<?=base_url('themes/bigamer');?>/assets/js/vendor/jquery-3.6.0.min.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/vendor/modernizr-3.11.2.min.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/circularProgressBar.min.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/isotope.pkgd.min.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/swiper.min.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/lightcase.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/waypoints.min.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/wow.min.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/vendor/bootstrap.bundle.min.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/plugins.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/main.js"></script>
	<script src="<?=base_url('themes/bigamer');?>/assets/js/custom.js"></script>
    <script src="<?= base_url("themes/script/moment.min.js"); ?>"></script>


	<!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
	<script>
		window.ga = function () {
			ga.q.push(arguments)
		};
		ga.q = [];
		ga.l = +new Date;
		ga('create', 'UA-XXXXX-Y', 'auto');
		ga('set', 'anonymizeIp', true);
		ga('set', 'transport', 'beacon');
		ga('send', 'pageview')
	</script>
	<script src="https://www.google-analytics.com/analytics.js" async></script>
    <?= $script_js; ?>

</body>

</html>