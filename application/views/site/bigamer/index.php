<!doctype html>
<html class="no-js" lang="en">

<head>
	<meta charset="utf-8">
	<title><?= Settings_m::getSetting('site_title'); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- site favicon -->
	<link rel="icon" type="image/png" href="<?= base_url('themes/bigamer'); ?>/assets/images/favicon.png">
	<!-- Place favicon.ico in the root directory -->

	<!-- All stylesheet and icons css  -->
	<link rel="stylesheet" href="<?= base_url('themes/bigamer'); ?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url('themes/bigamer'); ?>/assets/css/animate.css">
	<link rel="stylesheet" href="<?= base_url('themes/bigamer'); ?>/assets/css/icofont.min.css">
	<link rel="stylesheet" href="<?= base_url('themes/bigamer'); ?>/assets/css/swiper.min.css">
	<link rel="stylesheet" href="<?= base_url('themes/bigamer'); ?>/assets/css/lightcase.css">
	<link rel="stylesheet" href="<?= base_url('themes/bigamer'); ?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url('themes/bigamer'); ?>/assets/css/style.min.css">
	<link rel="stylesheet" href="<?= base_url('themes/bigamer'); ?>/assets/css/custom.css">
    <link href="<?= base_url('themes/bigamer'); ?>/assets/dist/css/select2.min.css" rel="stylesheet" />


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

	<section class="pageheader-section bg-img" style="background-image: url(<?= base_url('themes/bigamer'); ?>/assets/images/custom/index.jpg);">
		<div class="container" style="margin-top: -100px;">
            <div class="section-wrapper text-center text-uppercase">
                <h1 class="header-title" style="font-size: 45px;">PIN PERDOSSI CIREBON</h1>
              	<h1 class="header-title" style="font-size: 35px;">17 - 20 November 2022</h1>
				<img src="<?= base_url('themes/bigamer'); ?>/assets/images/custom/perdossi_1.png" class="img-logo-head" alt="">
				<img src="<?= base_url('themes/bigamer'); ?>/assets/images/custom/perdossi_2.png" class="img-logo-head" alt="">
            </div>
			<div class="row text-center mt-2">
				<div class="col-md-6 offset-md-3">
					<!--<a href="<?=base_url('site/home');?>" class="btn-main btn btn-edge-block btn-purple">Visit our Website here</a><br>-->
					<a href="<?=base_url("site/home");?>" class="btn-main btn btn-edge-block btn-primary" style="margin-top: -10px;">Visit our Website</a><br>
					<a href="#" class="btn-main btn btn-edge-block btn-primary" style="margin-top: -10px;">Download First Announcement here (Not yet available)</a><br>
					<!--<a href="https://aomc-pinbanjarmasin2022.com/themes/gigaland/images/20220528FA.pdf" target="blank" class="btn-mai btn btn-edge-block btn-purple" style="margin-top: -10px;">Download First Announcement here (Released on July 18th)</a><br>-->
					<hr>
				</div>
			</div>
			<div class="row mt-3 text-center">
				<div class="col-12">
					<p style="color: white; text-shadow: 3px 2px 1px black; font-size: 25px;">We have opened the registration</p>
				</div>
			</div>

			<div class="row mt-4 mb-5">
				<div class="col-md-6 offset-md-3">
					<form class="account-form" action="<?= base_url('site/login'); ?>" method="POST">
						<?php if(!$hasSession):?>
						<div class="form-group">
							<input type="text" placeholder="User Name" name="username">
						</div>
						<div class="form-group">
							<input type="password" placeholder="Password" name="password">
						</div>
						<div class="form-group">
							<div class="d-flex justify-content-end flex-wrap pt-sm-2">
								<!-- <div class="checkgroup">
									<input type="checkbox" name="remember" id="remember">
									<label for="remember">Remember Me</label>
								</div> -->
								<a href="<?=base_url('site/forget');?>">Forget Password?</a>
							</div>
						</div>
						<?php endif;?>
						<div class="form-group">
							<button type="submit" value="login" name="login" class="btn btn-edge btn-purple" style="margin-top: -3px;">
							<span> <?=$hasSession ? "Back To Member Area":"Sign";?></span>
						</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<!-- ================ footer Section start Here =============== -->
    <footer class="footer-section">
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
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/vendor/jquery-3.6.0.min.js"></script>
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/vendor/modernizr-3.11.2.min.js"></script>
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/circularProgressBar.min.js"></script>
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/isotope.pkgd.min.js"></script>
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/swiper.min.js"></script>
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/lightcase.js"></script>
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/waypoints.min.js"></script>
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/wow.min.js"></script>
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/vendor/bootstrap.bundle.min.js"></script>
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/plugins.js"></script>
	<script src="<?= base_url('themes/bigamer'); ?>/assets/js/main.js"></script>
    <script src="<?= base_url('themes/bigamer'); ?>/assets/dist/js/select2.min.js"></script>


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
</body>
</html>