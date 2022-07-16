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
    <link href="<?= base_url('themes/gigaland'); ?>/css/jquery.countdown.css" rel="stylesheet" type="text/css" />

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

									<li><a href="#home">Home</a></li>
									<li><a href="#sign-in">Sign In</a></li>
									<li><a href="#event">Event</a></li>
									
								</ul>
								<!-- <a href="login.html" class="login"><i class="icofont-user"></i> <span>LOG IN</span> </a> -->
								<a href="<?=base_url('site/register');?>" class="signup"><i class="icofont-users"></i> <span>Registration</span></a>

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
	<!-- ==========Header Section Ends Here========== -->

	<!-- ===========Banner Section start Here========== -->
	<section id="home" class="banner-section bg-img" style="background-image: url(<?=base_url('themes/bigamer');?>/assets/images/custom/header.jpg);">
		<div class="container">
			<div class="">
				<div class="banner-content text-center">
					<h1 class="header-title">PIN PERDOSSI CIREBON</h1>
					<p style="text-transform: initial; font-size: 32px;">5.0 Neurotech : A New Innovationto Enchance <br> Neuroscience and Reshape NeuroSociety</p>
					<p style="text-transform: initial;">17 - 22 November 2022</p>
					<a href="#info" class="default-button reverse-effect"><span>Watch Now <i class="icofont-play-alt-1"></i></span> </a>
				</div>
				<div class="banner-thumb d-flex flex-wrap justify-content-center justify-content-between align-items-center align-items-lg-end">
					<div class="banner-thumb-img ml-xl-50-none">
						<a href=""><img src="<?=base_url('themes/bigamer');?>/assets/images/custom/perdossi_1.png" style="width: 300px;" alt="banner-thumb"></a>
					</div>
					<div class="banner-thumb-img mr-xl-50-none">
						<a href=""><img src="<?=base_url('themes/bigamer');?>/assets/images/custom/perdossi_2.png" style="width: 300px;" alt="banner-thumb"></a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- ===========Banner Section Ends Here========== -->

	<!-- ===========Info Section Start Here========== -->
	<section id="info" class="padding-top padding-bottom">
		<div class="container">
			<div class="section-header">
				<!-- <p>our LATEST VIDEOS</p> -->
				<!-- <h2 class="header-title">SPEAKER</h2> -->
			</div>
			<div class="section-wrapper">
				<!-- <div class="row g-4">
					<div class="col-12">
						<div class="video-bottom">
							<div class="testimonial-slider overflow-hidden">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="contact-item text-center">
											<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/cp2.png" class="lazy img-fluid mb-2" alt="img">
											<div class="contact-content">
												<p class="title" style="font-size: 18px;">Name 1</p><br>
												"Spesialis" <br>
											</div>
										</div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="contact-item text-center">
											<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/cp2.png" class="lazy img-fluid mb-2" alt="img">
											<div class="contact-content">
												<p class="title" style="font-size: 18px;">Name 2</p><br>
												"Spesialis" <br>
											</div>
										</div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="contact-item text-center">
											<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/cp2.png" class="lazy img-fluid mb-2" alt="img">
											<div class="contact-content">
												<p class="title" style="font-size: 18px;">Name 3</p><br>
												"Spesialis" <br>
											</div>
										</div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="contact-item text-center">
											<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/cp2.png" class="lazy img-fluid mb-2" alt="img">
											<div class="contact-content">
												<p class="title" style="font-size: 18px;">Name 4</p><br>
												"Spesialis" <br>
											</div>
										</div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="contact-item text-center">
											<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/cp2.png" class="lazy img-fluid mb-2" alt="img">
											<div class="contact-content">
												<p class="title" style="font-size: 18px;">Name 5</p><br>
												"Spesialis" <br>
											</div>
										</div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div> -->
				<div class="row justify-content-center g-4 mt-4">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="contact-item text-center">
                            <div class="contact-thumb mb-4">
								<i class="icofont icofont-user-suited icofont-2x" style="position: relative; z-index: 2;"></i>
                            </div>
                            <div class="contact-content">
                                <h6 class="title">Speaker</h6>
                                <h3>10</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="contact-item text-center">
                            <div class="contact-thumb mb-4">
                                <i class="icofont icofont-users-alt-4 icofont-3x" style="position: relative; z-index: 2;"></i>
                            </div>
                            <div class="contact-content">
                                <h6 class="title">Moderator</h6>
                                <h3>18</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="contact-item text-center">
                            <div class="contact-thumb mb-4">
                                <i class="icofont icofont-users-alt-5 icofont-3x" style="position: relative; z-index: 2;"></i>
                            </div>
                            <div class="contact-content">
                                <h6 class="title">Participant</h6>
                                <h3>100</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="contact-item text-center">
                            <div class="contact-thumb mb-4">
                                <i class="icofont icofont-file-alt icofont-3x" style="position: relative; z-index: 2;"></i>
                            </div>
                            <div class="contact-content">
                                <h6 class="title">Abstract</h6>
                                <h3>110</h3>
                            </div>
                        </div>
                    </div>
                </div>

				<div class="row g-4 mt-4">
					<div class="achievement-area">
						<div class="row">
							<div class="col-lg-6 mb-3" style="text-align: center">
								<h4>Abstract Countdown</h4>
								<p class="text-white">(<?= date_format($papercountdown, "F d, Y"); ?>)</p>

								<div class="de_countdown text-center btn btn-edge-block btn-purple mt-2" data-year="<?= date_format($papercountdown, "Y"); ?>" data-month="<?= date_format($papercountdown, "m"); ?>" data-day="<?= date_format($papercountdown, "d"); ?>" data-hour="<?= date_format($papercountdown, "H"); ?>"></div>
							</div>
							<div class="col-lg-6" style="text-align: center">
								<h4>Event Countdown</h4>
								<p class="text-white">(<?= date_format($eventcountdown, "F d, Y"); ?>)</p>
								<div class="de_countdown text-center btn btn-edge-block btn-purple mt-2" data-year="<?= date_format($eventcountdown, "Y"); ?>" data-month="<?= date_format($eventcountdown, "m"); ?>" data-day="<?= date_format($eventcountdown, "d"); ?>" data-hour="<?= date_format($eventcountdown, "H"); ?>"></div>
							</div>
						</div>
					</div>
				</div>

				<h3 class="mt-5 mb-2">SIMPOSIUM</h3>

				<div class="row g-5">
					<div class="col-lg-6">
						<div class="upcome-matches">
							<div class="row g-3">
								<div class="col-12">
									<div class="match-item-2 item-layer">
										<div class="match-inner">
											<div class="match-header d-flex flex-wrap justify-content-between align-items-center">
												<p class="match-team-info" style="text-transform: initial;">
													Minimal : <span class="fw-bold">30 Orang</span>
												</p>
												<p class="match-prize" style="text-transform: initial;">Rp. <span class="fw-bold">$3200</span></p>
											</div>
											<div class="match-content gradient-bg-blue">
												<div class="row align-items-center justify-content-center">
													<div class="col-md-8 order-md-2 mt-4 mt-md-0">
														<div class="match-game-info text-center">
															<h4><a href="" style="text-transform: initial;">Stroke dan Pembuluh Darah</a>
															</h4>
															<p class="d-flex flex-wrap justify-content-center">
																<span class="match-date">30
																	April 2021 </span><span class="match-time">Time:
																	08:30PM</span>
															</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="match-item-2 item-layer">
										<div class="match-inner">
											<div class="match-header d-flex flex-wrap justify-content-between align-items-center">
												<p class="match-team-info" style="text-transform: initial;">
													Minimal : <span class="fw-bold">30 Orang</span>
												</p>
												<p class="match-prize" style="text-transform: initial;">Rp. <span class="fw-bold">$3200</span></p>
											</div>
											<div class="match-content gradient-bg-blue">
												<div class="row align-items-center justify-content-center">
													<div class="col-md-8 order-md-2 mt-4 mt-md-0">
														<div class="match-game-info text-center">
															<h4><a href="" style="text-transform: initial;">Neurointervensi</a>
															</h4>
															<p class="d-flex flex-wrap justify-content-center">
																<span class="match-date">30
																	April 2021 </span><span class="match-time">Time:
																	08:30PM</span>
															</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="match-item-2 item-layer">
										<div class="match-inner">
											<div class="match-header d-flex flex-wrap justify-content-between align-items-center">
												<p class="match-team-info" style="text-transform: initial;">
													Minimal : <span class="fw-bold">30 Orang</span>
												</p>
												<p class="match-prize" style="text-transform: initial;">Rp. <span class="fw-bold">$3200</span></p>
											</div>
											<div class="match-content gradient-bg-blue">
												<div class="row align-items-center justify-content-center">
													<div class="col-md-8 order-md-2 mt-4 mt-md-0">
														<div class="match-game-info text-center">
															<h4><a href="" style="text-transform: initial;">Neurointensif</a>
															</h4>
															<p class="d-flex flex-wrap justify-content-center">
																<span class="match-date">30
																	April 2021 </span><span class="match-time">Time:
																	08:30PM</span>
															</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="match-item-2 item-layer">
										<div class="match-inner">
											<div class="match-header d-flex flex-wrap justify-content-between align-items-center">
												<p class="match-team-info" style="text-transform: initial;">
													Minimal : <span class="fw-bold">30 Orang</span>
												</p>
												<p class="match-prize" style="text-transform: initial;">Rp. <span class="fw-bold">$3200</span></p>
											</div>
											<div class="match-content gradient-bg-blue">
												<div class="row align-items-center justify-content-center">
													<div class="col-md-8 order-md-2 mt-4 mt-md-0">
														<div class="match-game-info text-center">
															<h4><a href="" style="text-transform: initial;">Neurobehaviour</a>
															</h4>
															<p class="d-flex flex-wrap justify-content-center">
																<span class="match-date">30
																	April 2021 </span><span class="match-time">Time:
																	08:30PM</span>
															</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="match-item-2 item-layer">
										<div class="match-inner">
											<div class="match-header d-flex flex-wrap justify-content-between align-items-center">
												<p class="match-team-info" style="text-transform: initial;">
													Minimal : <span class="fw-bold">30 Orang</span>
												</p>
												<p class="match-prize" style="text-transform: initial;">Rp. <span class="fw-bold">$3200</span></p>
											</div>
											<div class="match-content gradient-bg-blue">
												<div class="row align-items-center justify-content-center">
													<div class="col-md-8 order-md-2 mt-4 mt-md-0">
														<div class="match-game-info text-center">
															<h4><a href="" style="text-transform: initial;">Neurorestorasi</a>
															</h4>
															<p class="d-flex flex-wrap justify-content-center">
																<span class="match-date">30
																	April 2021 </span><span class="match-time">Time:
																	08:30PM</span>
															</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="upcome-matches">
							<div class="row g-3">
								<div class="col-12">
									<div class="match-item-2 item-layer">
										<div class="match-inner">
											<div class="match-header d-flex flex-wrap justify-content-between align-items-center">
												<p class="match-team-info" style="text-transform: initial;">
													Minimal : <span class="fw-bold">30 Orang</span>
												</p>
												<p class="match-prize" style="text-transform: initial;">Rp. <span class="fw-bold">$3200</span></p>
											</div>
											<div class="match-content gradient-bg-blue">
												<div class="row align-items-center justify-content-center">
													<div class="col-md-8 order-md-2 mt-4 mt-md-0">
														<div class="match-game-info text-center">
															<h4><a href="" style="text-transform: initial;">Neurogeriatri</a>
															</h4>
															<p class="d-flex flex-wrap justify-content-center">
																<span class="match-date">30
																	April 2021 </span><span class="match-time">Time:
																	08:30PM</span>
															</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="match-item-2 item-layer">
										<div class="match-inner">
											<div class="match-header d-flex flex-wrap justify-content-between align-items-center">
												<p class="match-team-info" style="text-transform: initial;">
													Minimal : <span class="fw-bold">30 Orang</span>
												</p>
												<p class="match-prize" style="text-transform: initial;">Rp. <span class="fw-bold">$3200</span></p>
											</div>
											<div class="match-content gradient-bg-blue">
												<div class="row align-items-center justify-content-center">
													<div class="col-md-8 order-md-2 mt-4 mt-md-0">
														<div class="match-game-info text-center">
															<h4><a href="" style="text-transform: initial;">Neuroinfeksi</a>
															</h4>
															<p class="d-flex flex-wrap justify-content-center">
																<span class="match-date">30
																	April 2021 </span><span class="match-time">Time:
																	08:30PM</span>
															</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="match-item-2 item-layer">
										<div class="match-inner">
											<div class="match-header d-flex flex-wrap justify-content-between align-items-center">
												<p class="match-team-info" style="text-transform: initial;">
													Minimal : <span class="fw-bold">30 Orang</span>
												</p>
												<p class="match-prize" style="text-transform: initial;">Rp. <span class="fw-bold">$3200</span></p>
											</div>
											<div class="match-content gradient-bg-blue">
												<div class="row align-items-center justify-content-center">
													<div class="col-md-8 order-md-2 mt-4 mt-md-0">
														<div class="match-game-info text-center">
															<h4><a href="" style="text-transform: initial;">Neuroimaging</a>
															</h4>
															<p class="d-flex flex-wrap justify-content-center">
																<span class="match-date">30
																	April 2021 </span><span class="match-time">Time:
																	08:30PM</span>
															</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="match-item-2 item-layer">
										<div class="match-inner">
											<div class="match-header d-flex flex-wrap justify-content-between align-items-center">
												<p class="match-team-info" style="text-transform: initial;">
													Minimal : <span class="fw-bold">30 Orang</span>
												</p>
												<p class="match-prize" style="text-transform: initial;">Rp. <span class="fw-bold">$3200</span></p>
											</div>
											<div class="match-content gradient-bg-blue">
												<div class="row align-items-center justify-content-center">
													<div class="col-md-8 order-md-2 mt-4 mt-md-0">
														<div class="match-game-info text-center">
															<h4><a href="" style="text-transform: initial;">Neuroonkologi</a>
															</h4>
															<p class="d-flex flex-wrap justify-content-center">
																<span class="match-date">30
																	April 2021 </span><span class="match-time">Time:
																	08:30PM</span>
															</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- ===========Info Section Ends Here========== -->

	<!-- ===========Sign in Start Here========== -->
	<section id="sign-in" class="padding-top padding-bottom bg-img" style="background-image: url(<?=base_url('themes/bigamer');?>/assets/images/custom/bg1.jpg);">
		<div class="container">
			<div class="section-header">
				<!-- <p>our LATEST VIDEOS</p> -->
				<h2 class="header-title" class="header-title">Sign In / Register</h2>
			</div>
			<div class="section-wrapper">
				<div class="row g-4">
					<div class="achievement-area">
						<div class="row">
							<div class="col-lg-6" style="text-align: center">
								<h3>Sign In</h3>
								<form name="contactForm" id="contact_form" class="form-border" method="post" action="<?= base_url('site/login'); ?>">
									<div class="field-set mb-2">
										<input type="text" name="username" id="email" class="form-control" placeholder="Email">
									</div>
									<div class="field-set mb-2">
										<input type="password" name="password" id="password" class="form-control" placeholder="Password">
									</div>
									<a href="<?=base_url('site/forget');?>" class="mb-2">Forgot Password ?</a>
									<div class="d-grid">
										<button type="submit" name="login" value="login" class="btn btn-edge btn-purple"><i class="icofont icofont-sign-in"></i> Sign</button><br>
									</div>
								</form>
							</div>
							<div class="col-lg-6">
								<h3 style="text-align: center">How to Register</h3>
								<p>
									1. Click <b>Register</b>, and fill in your profile <br>
									2. Choose your event (and or submit your abstract)<br>
									3. Pay via our online payment gateway<br>
									4. Download your registration receipt, and See you on the date<br>
								</p>

								<li class="about-item2 d-flex flex-wrap mb-2">
									<div class="about-item-thumb2">
										<i class="icofont icofont-email"></i>
									</div>
									<div class="about-item-content">
										<p class="mt-1">1 email per account</p>
									</div>
								</li>
								<li class="about-item2 d-flex flex-wrap">
									<div class="about-item-thumb2 mt-2">
										<i class="icofont icofont-certificate-alt-1"></i>
									</div>
									<div class="about-item-content">
										<p>E-certificate will be sent <br> to the registered email</p>
									</div>
								</li>
							</div>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-lg-6 col-md-6 mb-2">
							<div class="d-grid text-center">
								<a href="<?=base_url('member/register');?>" class="default-button reverse-effect"><span> <i class="icofont-user"></i> Registrasi Individu</span> </a>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="d-grid text-center">
								<a href="<?=base_url('member/register/group');?>" class="default-button reverse-effect"><span> <i class="icofont-users"></i> Registrasi Grup/Kelompok</span> </a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- ===========Sign in Ends Here========== -->

	<!-- ===========Video tutorial Start Here========== -->
	<section id="video-tutorial" class="padding-top padding-bottom">
		<div class="container">
			<div class="section-header">
				<h2 class="header-title">Video Tutorial</h2>
			</div>
			<div class="section-wrapper">
				<div class="row g-4">
					<div class="col-12">
						<div class="partner-list" id="accordionExample">
							<div class="row g-4 justify-content-center">
								<div class="col-12">
									<div class="accordion-item">
										<div class="accordion-header" id="headingOne">
											<button class="accordion-button collapsed" type="button"
												data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
												aria-controls="collapseOne">
												<span class="accor-header-inner d-flex flex-wrap align-items-center">
													<p style="color: white; font-size: 20px; margin-top: 15px">Registration</p>
												</span>
											</button>
										</div>
										<div id="collapseOne" class="accordion-collapse collapse"
											aria-labelledby="headingOne" data-bs-parent="#accordionExample">
											<div class="accordion-body">
												<embed src="" width="100%" height="600px" align="center"></embed>
											</div>
										</div>
									</div>
									<div class="accordion-item mt-1">
										<div class="accordion-header" id="headingTwo">
											<button class="accordion-button collapsed" type="button"
												data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true"
												aria-controls="collapseTwo">
												<span class="accor-header-inner d-flex flex-wrap align-items-center">
													<p style="color: white; font-size: 20px; margin-top: 15px">How to register by using credit card (esp International Participant)</p>
												</span>
											</button>
										</div>
										<div id="collapseTwo" class="accordion-collapse collapse"
											aria-labelledby="headingTwo" data-bs-parent="#accordiTwoxample">
											<div class="accordion-body">
												<embed src="" width="100%" height="600" align="center"></embed>
											</div>
										</div>
									</div>
									<div class="accordion-item mt-1">
										<div class="accordion-header" id="headingThree">
											<button class="accordion-button collapsed" type="button"
												data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true"
												aria-controls="collapseThree">
												<span class="accor-header-inner d-flex flex-wrap align-items-center">
													<p style="color: white; font-size: 20px; margin-top: 15px">How to submit your abstract</p>
												</span>
											</button>
										</div>
										<div id="collapseThree" class="accordion-collapse collapse"
											aria-labelledby="headingThree" data-bs-parent="#accordiThreexample">
											<div class="accordion-body">
												<embed src="" width="100%" height="600px" align="center"></embed>
											</div>
										</div>
									</div>
									<div class="accordion-item mt-1">
										<div class="accordion-header" id="headingFour">
											<button class="accordion-button collapsed" type="button"
												data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true"
												aria-controls="collapseFour">
												<span class="accor-header-inner d-flex flex-wrap align-items-center">
													<p style="color: white; font-size: 20px; margin-top: 15px">How to do a group registration</p>
												</span>
											</button>
										</div>
										<div id="collapseFour" class="accordion-collapse collapse"
											aria-labelledby="headingFour" data-bs-parent="#accordiFourxample">
											<div class="accordion-body">
												<embed src="" width="100%" height="600px" align="center"></embed>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- ===========Video tutorial Ends Here========== -->

	<!-- ===========Event Start Here========== -->
	<div id="event" class="padding-top padding-bottom bg-img" style="background-image: url(<?=base_url('themes/bigamer');?>/assets/images/custom/bg3.jpg);">
        <div class="container">
			<div class="section-header">
				<h2 class="header-title">Event Info</h2>
			</div>
			<div class="section-wrapper">
				<div class="row g-4">
					<ul class="gallery-filter">
						<li data-filter=".one" class="item-one is-checked"><span class="category">Tanggal Penting</span></li>
						<li data-filter=".two" class="item-two"><span class="category">FAQ</span></li>
						<li data-filter=".three" class="item-three"><span class="category">Scientific Programme</span></li>
						<li data-filter=".four" class="item-four"><span class="category">Scientific Guideline</span></li>
						<li data-filter=".five" class="item-five"><span class="category">Sambutan</span></li>
						<li data-filter=".six" class="item-six"><span class="category">Kepanitiaan</span></li>
					</ul>
				</div>
				<div class="masonary-gallery" style="position: relative; height: 1153.99px;">
					<div class="col-12 masonary-item one" style="position: absolute; left: 0px; top: 0px;">
						<div class="row g-4">
							<div class="achievement-area">
								<h4>Tanggal Penting</h4>
								<hr>
								<ul>
									<li>Batas Pengumpulan CV dan judul untuk SKP IDI : 10 Agustus 2022</li>
									<li>Batas Pengumpulan Abstrak : 12 Oktober 2022</li>
									<li>Pengumuman Penerimaan Abstrak : 24 Oktober 2022</li>
									<li>Program Ilmiah : 17 – 18 November 2022 WS, dan 19– 20 November 2022 Simposium</li>
									<li>E-Poster & Presentasi Oral : 19 November 2022</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-12 masonary-item two" style="position: absolute; left: 0px; top: 0px; display: none;">
						<div class="row g-4">
							<div class="achievement-area">
								<h4>FAQ</h4>
								<hr>
								<ul style="text-align: justify;">
									<li>
										<i class="icofont-exclamation-circle"></i> Is this conference held online or offline?<br>
										- This conference will be held online using Zoom Application.
									</li>
									<li>
										<i class="icofont-exclamation-circle"></i> How to login?<br>
										- Login using the user email that has been registered on the web https://aomc-pinbanjarmasin2022.com/ and passwords respectively.
									</li>
									<li>
										<i class="icofont-exclamation-circle"></i> What if I forget my password?<br>
										- If you forget your password, please click the Forgot Password section on the login page, and enter your registered email, then click Reset. The new password will be sent to the email that was entered during the reset process. Or you can also just click:
									</li>
									<li>
										<i class="icofont-exclamation-circle"></i> Where is the zoom link?<br>
										- The event link is available in the Webinar Links” section of the Member Area page, to the left of the page. https://aomc-pinbanjarmasin2022.com/
									</li>
									<li>
										<i class="icofont-exclamation-circle"></i> What if I enter the wrong user's email?<br>
										- Please search in your email inbox, with keywords: to find out your username notification
									</li>
									<li>
										<i class="icofont-exclamation-circle"></i> I was registered by the sponsor, what should I do to login?<br>
										- Please contact your sponsor directly for your account information.
									</li>
									<li>
										<i class="icofont-exclamation-circle"></i> I am not a participant in this event, but I am a speaker/moderator/judge at this event, how do I login?<br>
										- For speakers, moderators and judges, we have sent account access autogenerated to your doctor's email. Please check your inbox/spam and try to access our website with that account.
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-12 masonary-item three" style="position: absolute; left: 0px; top: 0px;  display: none;">
						<div class="row g-4">
							<div class="achievement-area">
								<h4>Scientific Programme for PIN's</h4>
								-
								<hr>
								<h4>Scientific Programme for AOMC</h4>
								<a href="https://aomc-pinbanjarmasin2022.com/themes/gigaland/images/220601 AOMC 2022_Scientific Program Update.pdf" target="blank" class="btn btn-edge btn-primary">AOMC 2022 Scientific Programme (updated on 1 June)</a><br>
							</div>
						</div>
					</div>
					<div class="col-12 masonary-item four" style="position: absolute; left: 0px; top: 0px;  display: none;">
						<div class="row g-4">
							<div class="achievement-area">
								<h4>Scientific Guideline for PIN's</h4>
								<a href="https://aomc-pinbanjarmasin2022.com/themes/gigaland/images/220514 - Update Pedoman Free Paper.pdf" target="blank" class="btn btn-edge btn-primary mt-2">Guideline for PIN's Call for Abstract (New! updated on 15 May)</a><br>
								<a href="https://aomc-pinbanjarmasin2022.com/themes/gigaland/images/TemplateAbstrakPINPerdossi2022.docx" target="blank" class="btn btn-edge btn-warning mt-2">Template for PIN's Abstract</a><br>
								<a href="https://drive.google.com/drive/folders/1MydS41eTpg5uUvnDqV-EcFybPCcLkAsH" target="blank" class="btn btn-edge btn-primary mt-2">Template for Paper Presentation (New! Updated on 15 May) </a><br>
								<a href="https://aomc-pinbanjarmasin2022.com/themes/gigaland/images/FAQ Ilmiah PIN Banjarmasin.pdf" target="blank" class="btn btn-edge btn-primary mt-2">FAQ Paper Competition (New! 15 May)</a>
								<hr>
								<h4>Scientific Guideline for AOMC</h4>
								<a href="https://aomc-pinbanjarmasin2022.com/themes/gigaland/images/Abstract Guidelines Final.pdf" target="blank" class="btn btn-edge btn-warning">Guideline for AOMC abstract (updated on 12th April)</a><br>
								<a href="https://aomc-pinbanjarmasin2022.com/themes/gigaland/images/AOMC 2022 Abstract Submission Form For Original Article.docx" target="blank" class="btn btn-edge btn-warning mt-2">Click here for template for the original article</a><br>
								<a href="https://aomc-pinbanjarmasin2022.com/themes/gigaland/images/20220428 - AOMC Form For Case Report.docx" target="blank" class="btn btn-edge btn-warning mt-2">Click here for template for case report (updated on 28 Apr)</a>
							</div>
						</div>
					</div>
					<div class="col-12 masonary-item five" style="position: absolute; left: 0px; top: 0px;  display: none;">
						<div class="row g-4">
							<div class="achievement-area">
								<h4>Sambutan</h4>
								<hr>
								<div class="accordion" id="accordionExample">
									<div class="accordion-item">
										<h2 class="accordion-header" id="headingSambutan">
											<button class="accordion-button collapsed text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSambutan" aria-expanded="false" aria-controls="collapseSambutan">
												Sambutan PIN PERDOSSI CIREBON 2022
											</button>
										</h2>
										<div id="collapseSambutan" class="accordion-collapse collapse show" aria-labelledby="headingSambutan" data-bs-parent="#accordionExample">
											<div class="accordion-body">
												<embed src="<?=base_url('themes/bigamer');?>/assets/pdf/sambutan.pdf" width="100%" height="500px" align="center"></embed>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 masonary-item six" style="position: absolute; left: 0px; top: 0px;  display: none;">
						<div class="row g-4">
							<div class="contact-item">
								<h4>Kepanitiaan</h4>
								<hr>
								<div class="accordion" id="accordionExample">
									<div class="accordion-item">
										<h2 class="accordion-header" id="headingPanitia">
											<button class="accordion-button collapsed text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePanitia" aria-expanded="false" aria-controls="collapsePanitia">
												Kepanitiaan PIN PERDOSSI CIREBON 2022
											</button>
										</h2>
										<div id="collapsePanitia" class="accordion-collapse collapse show" aria-labelledby="headingPanitia" data-bs-parent="#accordionExample">
											<div class="accordion-body">
												<embed src="<?=base_url('themes/bigamer');?>/assets/pdf/kepanitiaan.pdf" width="100%" height="500px" align="center"></embed>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
	<!-- ===========Event Ends Here========== -->

	<!-- ===========CP Section Start Here========== -->
	<section id="cp" class="padding-top padding-bottom">
		<div class="container">
			<div class="section-header">
				<h2 class="header-title">Contact Person</h2>
			</div>
			<div class="section-wrapper">
				<div class="row g-5 justify-content-center row-cols-md-3 row-cols-sm-3 row-cols-1 mt-1">
					
						<div class="game-item item-layer">
							<div class="game-item-inner bg-1">
								<div class="game-thumb">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/cp1.png" alt="game-img">
								</div>
								<div class="game-overlay text-center">
									<h4><a href="#" style="text-transform: initial;">Sekretariat</a> </h4><br>
									<div class="continer">
										<a href="http://wa.me/6287733667120" target="_BLANK" class="btn btn-xs btn-purple mt-1"><i class="icofont-whatsapp"></i> Mae</a><br>
										<a href="http://wa.me/6281322361407" target="_BLANK" class="btn btn-xs btn-purple mt-1"><i class="icofont-whatsapp"></i> dr. Wahyu Kartikasari, Sp. S</a><br>
									</div>
								</div>
							</div>
						</div>
						<div class="game-item item-layer">
							<div class="game-item-inner bg-2">
								<div class="game-thumb">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/cp2.png" alt="game-img">
								</div>
								<div class="game-overlay text-center">
									<h4><a href="#" style="text-transform: initial;">Registrasi</a> </h4><br>
									<div class="container">
										<a href="http://wa.me/6289603215099" target="_BLANK" class="btn btn-xs btn-purple mt-1"><i class="icofont-whatsapp"></i> dr. Azka</a><br>
									</div>
								</div>
							</div>
						</div>
						<div class="game-item item-layer">
							<div class="game-item-inner bg-3">
								<div class="game-thumb">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/cp3.png" alt="game-img">
								</div>
								<div class="game-overlay text-center">
									<h4><a href="#" style="text-transform: initial;">Ilmiah</a> </h4><br>
									<div class="container">
										<a href="http://wa.me/6281220336222" target="_BLANK" class="btn btn-xs btn-purple mt-1"><i class="icofont-whatsapp"></i> dr. Andi Suharso, Sp.N</a><br>
									</div>
								</div>
							</div>
						</div>
						<div class="game-item item-layer">
							<div class="game-item-inner bg-4">
								<div class="game-thumb">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/cp4.png" alt="game-img">
								</div>
								<div class="game-overlay text-center">
									<h4><a href="#" style="text-transform: initial;">Akomodasi</a> </h4><br>
									<div class="container">
										<a href="http://wa.me/6285222625454" target="_BLANK" class="btn btn-xs btn-purple mt-1"><i class="icofont-whatsapp"></i> dr. Rinrin Maharani, Sp.S</a><br>
									</div>
								</div>
							</div>
						</div>
						<div class="game-item item-layer">
							<div class="game-item-inner bg-5">
								<div class="game-thumb">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/cp5.png" alt="game-img">
								</div>
								<div class="game-overlay text-center">
									<h4><a href="#" style="text-transform: initial;">Dana dan Sponsorship</a> </h4><br>
									<div class="container">
										<a href="http://wa.me/6285888885010" target="_BLANK" class="btn btn-xs btn-purple mt-1"><i class="icofont-whatsapp"></i> dr. Asnelia Devicaesaria, Sp.S</a><br>
										<a href="http://wa.me/628122012437" target="_BLANK" class="btn btn-xs btn-purple mt-1"><i class="icofont-whatsapp"></i> dr. Agus Kusnandang, Sp.S</a><br>
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
	</section>
	<!-- ===========CP Section Ends Here========== -->

	<!-- ===========Sponsor Section Start Here========== -->
	<div class="sponsor-section padding-bottom">
		<div class="container">
			<div class="section-header">
				<h2 class="header-title">Sponsor</h2>
			</div>
			<div class="section-wrapper">
				<h4>Platinum</h4>
				<hr>
				<div class="row g-5 justify-content-center row-cols-md-3 row-cols-sm-3 row-cols-1 mt-1">
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
				</div>
				<br><br>
				<h4>Gold</h4>
				<hr>
				<div class="row g-5 justify-content-center row-cols-md-4 row-cols-sm-4 row-cols-1 mt-1">
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
				</div>
				<br><br>
				<h4>Gold</h4>
				<hr>
				<div class="row g-5 justify-content-center row-cols-md-5 row-cols-sm-5 row-cols-2 mt-1">
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
				</div>
				<br><br>
				<h4>Gold</h4>
				<hr>
				<div class="row g-5 justify-content-center row-cols-md-6 row-cols-sm-6 row-cols-2 mt-1">
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="sponsor-item">
							<div class="sponsor-inner">
								<div class="sponsor-thumb text-center">
									<img src="<?=base_url('themes/bigamer');?>/assets/images/custom/sponsor.png" alt="sponsor-thumb">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- ===========Sponsor Section Ends Here========== -->

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
										<a href="<?=base_url('member/register');?>" class="btn btn-edge btn-purple"> Registrasi Individu</a><br>
										<a href="<?=base_url('member/register/group');?>" class="btn btn-edge btn-purple"> Registrasi Grup/Kelompok</a><br>
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
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.countTo.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.countdown.js"></script>


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