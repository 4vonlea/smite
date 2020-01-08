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

	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:700%2C400" rel="stylesheet" property="stylesheet" type="text/css" media="all">
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2Cregular%2Citalic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CABeeZee%3Aregular%2Citalic&amp;subset=latin%2Clatin-ext%2Cdevanagari&amp;ver=5.0.3' type='text/css' media='all' />
	<?php if (ENVIRONMENT == "production"): ?>
		<script src="https://cdn.jsdelivr.net/npm/vue"></script>
	<?php else: ?>
		<script src="<?= base_url('themes/script/vue.js'); ?>"></script>
	<?php endif; ?>
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
							<li class="menu-item menu-item-home current-menu-item menu-item-has-children"><a href="index.html">Home</a>
								<ul class="sub-menu">
									<li class="menu-item menu-item-home"><a href="index.html">Homepage 1</a></li>
									<li class="menu-item"><a href="homepage-2.html">Homepage 2</a></li>
								</ul>
							</li>
							<li class="menu-item menu-item-has-children"><a href="#">Pages</a>
								<ul class="sub-menu">
									<li class="menu-item"><a href="about-us.html">About KU</a></li>
									<li class="menu-item menu-item-has-children"><a href="blog-full-right-sidebar-with-frame.html">Blog</a>
										<ul class="sub-menu">
											<li class="menu-item menu-item-has-children"><a href="blog-full-right-sidebar-with-frame.html">Blog Full</a>
												<ul class="sub-menu">
													<li class="menu-item"><a href="blog-full-right-sidebar-with-frame.html">Blog Full Right Sidebar With Frame</a></li>
													<li class="menu-item"><a href="blog-full-left-sidebar-with-frame.html">Blog Full Left Sidebar With Frame</a></li>
													<li class="menu-item"><a href="blog-full-both-sidebar-with-frame.html">Blog Full Both Sidebar With Frame</a></li>
													<li class="menu-item"><a href="blog-full-right-sidebar.html">Blog Full Right Sidebar</a></li>
													<li class="menu-item"><a href="blog-full-left-sidebar.html">Blog Full Left Sidebar</a></li>
													<li class="menu-item"><a href="blog-full-both-sidebar.html">Blog Full Both Sidebar</a></li>
												</ul>
											</li>
											<li class="menu-item menu-item-has-children"><a href="blog-grid-3-columns-no-space.html">Blog Grid</a>
												<ul class="sub-menu">
													<li class="menu-item"><a href="blog-grid-2-columns.html">Blog Grid 2 Columns</a></li>
													<li class="menu-item"><a href="blog-grid-3-columns.html">Blog Grid 3 Columns</a></li>
													<li class="menu-item"><a href="blog-grid-4-columns.html">Blog Grid 4 Columns</a></li>
													<li class="menu-item"><a href="blog-grid-2-columns-no-space.html">Blog Grid 2 Columns No Space</a></li>
													<li class="menu-item"><a href="blog-grid-3-columns-no-space.html">Blog Grid 3 Columns No Space</a></li>
													<li class="menu-item"><a href="blog-grid-4-columns-no-space.html">Blog Grid 4 Columns No Space</a></li>
												</ul>
											</li>

											<li class="menu-item"><a href="standard-post-type.html">Single Post</a></li>
										</ul>
									</li>
									<li class="menu-item menu-item-has-children"><a href="#">Contact</a>
										<ul class="sub-menu">
											<li class="menu-item"><a href="contact.html">Contact</a></li>
											<li class="menu-item"><a href="contact-2.html">Contact 2</a></li>
											<li class="menu-item"><a href="contact-3.html">Contact 3</a></li>
										</ul>
									</li>
									<li class="menu-item menu-item-has-children"><a href="portfolio-3-columns.html">Portfolio</a>
										<ul class="sub-menu">
											<li class="menu-item menu-item-has-children"><a>Portfolio Grid</a>
												<ul class="sub-menu">
													<li class="menu-item"><a href="portfolio-2-columns.html">Portfolio 2 Columns</a></li>
													<li class="menu-item"><a href="portfolio-3-columns.html">Portfolio 3 Columns</a></li>
													<li class="menu-item"><a href="portfolio-4-columns.html">Portfolio 4 Columns</a></li>
													<li class="menu-item"><a href="portfolio-5-columns.html">Portfolio 5 Columns</a></li>
													<li class="menu-item"><a href="portfolio-2-columns-with-frame.html">Portfolio 2 Columns With Frame</a></li>
													<li class="menu-item"><a href="portfolio-3-columns-with-frame.html">Portfolio 3 Columns With Frame</a></li>
													<li class="menu-item"><a href="portfolio-4-columns-with-frame.html">Portfolio 4 Columns With Frame</a></li>
													<li class="menu-item"><a href="portfolio-2-columns-no-space.html">Portfolio 2 Columns No Space</a></li>
													<li class="menu-item"><a href="portfolio-3-columns-no-space.html">Portfolio 3 Columns No Space</a></li>
													<li class="menu-item"><a href="portfolio-4-columns-no-space.html">Portfolio 4 Columns No Space</a></li>
													<li class="menu-item"><a href="portfolio-5-columns-no-space.html">Portfolio 5 Columns No Space</a></li>
												</ul>
											</li>
											<li class="menu-item menu-item-has-children"><a>Portfolio Masonry</a>
												<ul class="sub-menu">
													<li class="menu-item"><a href="portfolio-masonry-4-columns.html">Masonry 4 Columns</a></li>
													<li class="menu-item"><a href="portfolio-masonry-3-columns.html">Masonry 3 Columns</a></li>
													<li class="menu-item"><a href="portfolio-masonry-2-columns.html">Masonry 2 Columns</a></li>
													<li class="menu-item"><a href="portfolio-masonry-4-columns-no-space.html">Masonry 4 Columns No Space</a></li>
													<li class="menu-item"><a href="portfolio-masonry-3-columns-no-space.html">Masonry 3 Columns No Space</a></li>
													<li class="menu-item"><a href="portfolio-masonry-2-columns-no-space.html">Masonry 2 Columns No Space</a></li>
												</ul>
											</li>

											<li class="menu-item menu-item-has-children"><a class="sf-with-ul-pre" href="singleportfolio.html">Single Portfolio</a></li>
										</ul>
									</li>
									<li class="menu-item"><a href="gallery.html">Gallery</a></li>
									<li class="menu-item"><a href="price-table.html">Price Table</a></li>
									<li class="menu-item"><a href="maintenance.html">Maintenance</a></li>
									<li class="menu-item"><a href="coming-soon.html">Coming Soon</a></li>
									<li class="menu-item"><a href="404.html">404 Page</a></li>
								</ul>
							</li>
							<li class="menu-item menu-item-has-children"><a href="bachelor-of-science-in-business-administration.html">Academics</a>
								<ul class="sub-menu">
									<li class="menu-item menu-item-has-children"><a>Undergraduate</a>
										<ul class="sub-menu">
											<li class="menu-item"><a href="bachelor-of-science-in-business-administration.html">Business Administration</a></li>
											<li class="menu-item"><a href="school-of-law.html">School Of Law</a></li>
											<li class="menu-item"><a href="engineering.html">Engineering</a></li>
											<li class="menu-item"><a href="medicine.html">Medicine</a></li>
											<li class="menu-item"><a href="art-science.html">Art &#038; Science</a></li>
										</ul>
									</li>
									<li class="menu-item menu-item-has-children"><a href="#">Graduate Program</a>
										<ul class="sub-menu">
											<li class="menu-item"><a href="hospitality-management.html">Hospitality Management</a></li>
											<li class="menu-item"><a href="physics.html">Physics</a></li>
											<li class="menu-item"><a href="#">Chemistry</a></li>
											<li class="menu-item"><a href="#">Music</a></li>
											<li class="menu-item"><a href="#">Computer Science</a></li>
										</ul>
									</li>
									<li class="menu-item menu-item-has-children"><a href="#">Resources</a>
										<ul class="sub-menu">
											<li class="menu-item"><a href="bachelor-of-science-in-business-administration.html">Department Page</a></li>
											<li class="menu-item"><a href="finance.html">Major Page</a></li>
											<li class="menu-item"><a href="finance-faculty.html">Faculty Page</a></li>
											<li class="menu-item"><a href="john-hagensy-phd.html">Single Instructor</a></li>
											<li class="menu-item"><a href="introduction-to-financial-accounting.html">Single Course</a></li>
										</ul>
									</li>
									<li class="menu-item"><a href="#">Logo</a></li>
								</ul>
							</li>
							<li class="menu-item menu-item-has-children"><a href="apply-to-kingster.html">Admissions</a>
								<ul class="sub-menu">
									<li class="menu-item"><a href="apply-to-kingster.html">Apply To Kingster</a></li>
									<li class="menu-item"><a href="campus-tour.html">Campus Tour</a></li>
									<li class="menu-item"><a href="scholarships.html">Scholarships</a></li>
									<li class="menu-item"><a href="athletics.html">Athletics</a></li>
									<li class="menu-item"><a href="give-to-kingster.html">Give To Kingster</a></li>
									<li class="menu-item"><a href="alumni.html">Alumni</a></li>
									<li class="menu-item"><a href="event-calendar.html">Event Calendar</a></li>
								</ul>
							</li>
							<li class="menu-item menu-item-has-children"><a href="#">Courses</a>
								<ul class="sub-menu">
									<li class="menu-item"><a href="course-list-1.html">Course List 1</a></li>
									<li class="menu-item"><a href="course-list-2.html">Course List 2</a></li>
								</ul>
							</li>
							<li class="menu-item"><a href="athletics.html">Athletics</a></li>
							<li class="menu-item"><a href="university-life.html">University Life</a></li>
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
					<div class="kingster-top-bar-left kingster-item-pdlr"><i class="fa fa-envelope-open-o" id="i_983a_0"></i> contact@KUTheme.edu <i class="fa fa-phone" id="i_983a_1"></i> +1-3435-2356-222</div>
					<div class="kingster-top-bar-right kingster-item-pdlr">
						<ul id="kingster-top-bar-menu" class="sf-menu kingster-top-bar-menu kingster-top-bar-right-menu">
							<li class="menu-item kingster-normal-menu"><a href="#">Alumni</a></li>
							<li class="menu-item kingster-normal-menu"><a href="#">Calendar</a></li>
							<li class="menu-item kingster-normal-menu"><a href="#">Portal</a></li>
						</ul>
						<div class="kingster-top-bar-right-social"></div><a class="kingster-top-bar-right-button" href="#" target="_blank">Support KU</a></div>
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
								<li class="menu-item menu-item-home kingster-normal-menu"><a href="index.html" class="sf-with-ul-pre">Home</a></li>
								<li class="menu-item menu-item-has-children kingster-normal-menu"><a href="#" class="sf-with-ul-pre">Pages</a>
									<ul class="sub-menu">
										<li class="menu-item" data-size="60"><a href="about-us.html">About KU</a></li>
										<li class="menu-item menu-item-has-children" data-size="60"><a href="blog-full-right-sidebar-with-frame.html" class="sf-with-ul-pre">Blog</a>
											<ul class="sub-menu">
												<li class="menu-item menu-item-has-children"><a href="blog-full-right-sidebar-with-frame.html" class="sf-with-ul-pre">Blog Full</a>
													<ul class="sub-menu">
														<li class="menu-item"><a href="blog-full-right-sidebar-with-frame.html">Blog Full Right Sidebar With Frame</a></li>
														<li class="menu-item"><a href="blog-full-left-sidebar-with-frame.html">Blog Full Left Sidebar With Frame</a></li>
														<li class="menu-item"><a href="blog-full-both-sidebar-with-frame.html">Blog Full Both Sidebar With Frame</a></li>
														<li class="menu-item"><a href="blog-full-right-sidebar.html">Blog Full Right Sidebar</a></li>
														<li class="menu-item"><a href="blog-full-left-sidebar.html">Blog Full Left Sidebar</a></li>
														<li class="menu-item"><a href="blog-full-both-sidebar.html">Blog Full Both Sidebar</a></li>
													</ul>
												</li>
												<li class="menu-item menu-item-has-children"><a href="blog-grid-3-columns-no-space.html" class="sf-with-ul-pre">Blog Grid</a>
													<ul class="sub-menu">
														<li class="menu-item"><a href="blog-grid-2-columns.html">Blog Grid 2 Columns</a></li>
														<li class="menu-item"><a href="blog-grid-3-columns.html">Blog Grid 3 Columns</a></li>
														<li class="menu-item"><a href="blog-grid-4-columns.html">Blog Grid 4 Columns</a></li>
														<li class="menu-item"><a href="blog-grid-2-columns-no-space.html">Blog Grid 2 Columns No Space</a></li>
														<li class="menu-item"><a href="blog-grid-3-columns-no-space.html">Blog Grid 3 Columns No Space</a></li>
														<li class="menu-item"><a href="blog-grid-4-columns-no-space.html">Blog Grid 4 Columns No Space</a></li>
													</ul>
												</li>

												<li class="menu-item"><a href="standard-post-type.html">Single Post</a></li>
											</ul>
										</li>
										<li class="menu-item menu-item-has-children" data-size="60"><a href="#" class="sf-with-ul-pre">Contact</a>
											<ul class="sub-menu">
												<li class="menu-item"><a href="contact.html">Contact</a></li>
												<li class="menu-item"><a href="contact-2.html">Contact 2</a></li>
												<li class="menu-item"><a href="contact-3.html">Contact 3</a></li>
											</ul>
										</li>
										<li class="menu-item menu-item-has-children" data-size="60"><a href="portfolio-3-columns.html" class="sf-with-ul-pre">Portfolio</a>
											<ul class="sub-menu">
												<li class="menu-item menu-item-has-children"><a class="sf-with-ul-pre">Portfolio Grid</a>
													<ul class="sub-menu">
														<li class="menu-item"><a href="portfolio-2-columns.html">Portfolio 2 Columns</a></li>
														<li class="menu-item"><a href="portfolio-3-columns.html">Portfolio 3 Columns</a></li>
														<li class="menu-item"><a href="portfolio-4-columns.html">Portfolio 4 Columns</a></li>
														<li class="menu-item"><a href="portfolio-5-columns.html">Portfolio 5 Columns</a></li>
														<li class="menu-item"><a href="portfolio-2-columns-with-frame.html">Portfolio 2 Columns With Frame</a></li>
														<li class="menu-item"><a href="portfolio-3-columns-with-frame.html">Portfolio 3 Columns With Frame</a></li>
														<li class="menu-item"><a href="portfolio-4-columns-with-frame.html">Portfolio 4 Columns With Frame</a></li>
														<li class="menu-item"><a href="portfolio-2-columns-no-space.html">Portfolio 2 Columns No Space</a></li>
														<li class="menu-item"><a href="portfolio-3-columns-no-space.html">Portfolio 3 Columns No Space</a></li>
														<li class="menu-item"><a href="portfolio-4-columns-no-space.html">Portfolio 4 Columns No Space</a></li>
														<li class="menu-item"><a href="portfolio-5-columns-no-space.html">Portfolio 5 Columns No Space</a></li>
													</ul>
												</li>
												<li class="menu-item menu-item-has-children"><a class="sf-with-ul-pre">Portfolio Masonry</a>
													<ul class="sub-menu">
														<li class="menu-item"><a href="portfolio-masonry-4-columns.html">Masonry 4 Columns</a></li>
														<li class="menu-item"><a href="portfolio-masonry-3-columns.html">Masonry 3 Columns</a></li>
														<li class="menu-item"><a href="portfolio-masonry-2-columns.html">Masonry 2 Columns</a></li>
														<li class="menu-item"><a href="portfolio-masonry-4-columns-no-space.html">Masonry 4 Columns No Space</a></li>
														<li class="menu-item"><a href="portfolio-masonry-3-columns-no-space.html">Masonry 3 Columns No Space</a></li>
														<li class="menu-item"><a href="portfolio-masonry-2-columns-no-space.html">Masonry 2 Columns No Space</a></li>
													</ul>
												</li>

												<li class="menu-item menu-item-has-children"><a class="sf-with-ul-pre" href="singleportfolio.html">Single Portfolio</a></li>
											</ul>
										</li>
										<li class="menu-item" data-size="60"><a href="gallery.html">Gallery</a></li>
										<li class="menu-item" data-size="60"><a href="price-table.html">Price Table</a></li>
										<li class="menu-item" data-size="60"><a href="maintenance.html">Maintenance</a></li>
										<li class="menu-item" data-size="60"><a href="coming-soon.html">Coming Soon</a></li>
										<li class="menu-item" data-size="60"><a href="404.html">404 Page</a></li>
									</ul>
								</li>
								<li class="menu-item menu-item-has-children kingster-mega-menu"><a href="bachelor-of-science-in-business-administration.html" class="sf-with-ul-pre">Academics</a>
									<div class="sf-mega sf-mega-full megaimg">
										<ul class="sub-menu">
											<li class="menu-item menu-item-has-children" data-size="15"><a class="sf-with-ul-pre">Undergraduate</a>
												<ul class="sub-menu">
													<li class="menu-item"><a href="bachelor-of-science-in-business-administration.html">Business Administration</a></li>
													<li class="menu-item"><a href="school-of-law.html">School Of Law</a></li>
													<li class="menu-item"><a href="engineering.html">Engineering</a></li>
													<li class="menu-item"><a href="medicine.html">Medicine</a></li>
													<li class="menu-item"><a href="art-science.html">Art &#038; Science</a></li>
												</ul>
											</li>
											<li class="menu-item menu-item-has-children" data-size="15"><a href="#" class="sf-with-ul-pre">Graduate Program</a>
												<ul class="sub-menu">
													<li class="menu-item"><a href="hospitality-management.html">Hospitality Management</a></li>
													<li class="menu-item"><a href="physics.html">Physics</a></li>
													<li class="menu-item"><a href="#">Chemistry</a></li>
													<li class="menu-item"><a href="#">Music</a></li>
													<li class="menu-item"><a href="#">Computer Science</a></li>
												</ul>
											</li>
											<li class="menu-item menu-item-has-children" data-size="15"><a href="#" class="sf-with-ul-pre">Resources</a>
												<ul class="sub-menu">
													<li class="menu-item"><a href="bachelor-of-science-in-business-administration.html">Department Page</a></li>
													<li class="menu-item"><a href="finance.html">Major Page</a></li>
													<li class="menu-item"><a href="finance-faculty.html">Faculty Page</a></li>
													<li class="menu-item"><a href="john-hagensy-phd.html">Single Instructor</a></li>
													<li class="menu-item"><a href="introduction-to-financial-accounting.html">Single Course</a></li>
												</ul>
											</li>
										</ul>
									</div>
								</li>
								<li class="menu-item menu-item-has-children kingster-normal-menu"><a href="apply-to-kingster.html" class="sf-with-ul-pre">Admissions</a>
									<ul class="sub-menu">
										<li class="menu-item" data-size="60"><a href="apply-to-kingster.html">Apply To Kingster</a></li>
										<li class="menu-item" data-size="60"><a href="campus-tour.html">Campus Tour</a></li>
										<li class="menu-item" data-size="60"><a href="scholarships.html">Scholarships</a></li>
										<li class="menu-item" data-size="60"><a href="athletics.html">Athletics</a></li>
										<li class="menu-item" data-size="60"><a href="give-to-kingster.html">Give To Kingster</a></li>
										<li class="menu-item" data-size="60"><a href="alumni.html">Alumni</a></li>
										<li class="menu-item" data-size="60"><a href="event-calendar.html">Event Calendar</a></li>
									</ul>
								</li>
							</ul>
							<div class="kingster-navigation-slide-bar" id="kingster-navigation-slide-bar"></div>
						</div>
						<div class="kingster-main-menu-right-wrap clearfix ">
							<div class="kingster-main-menu-search" id="kingster-top-search"><i class="icon_search"></i></div>
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
						</div>
					</div>
				</div>
			</div>
		</header>
		<!--End Desktop Navigation-->

		<div class="kingster-page-wrapper" id="kingster-page-wrapper">
			<div class="gdlr-core-page-builder-body">
				<?= $content; ?>
			</div>
		</div>


		<footer>
			<div class="kingster-footer-wrapper ">
				<div class="kingster-footer-container kingster-container clearfix">
					<div class="kingster-footer-column kingster-item-pdlr kingster-column-15">
						<div id="text-2" class="widget widget_text kingster-widget">
							<div class="textwidget">
								<p><img src="upload/footer-logo.png" alt="" />
									<br /> <span class="gdlr-core-space-shortcode" id="span_983a_1"></span>
									<br /> Box 35300
									<br /> 1810 Campus Way NE
									<br /> Bothell, WA 98011-8246</p>
								<p><span id="span_983a_2">+1-2534-4456-345</span>
									<br /> <span class="gdlr-core-space-shortcode" id="span_983a_3"></span>
									<br /> <a id="a_983a_0" href="mailto:admin@kingsteruni.edu">admin@kingsteruni.edu</a></p>
								<div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-left-align">
									<div class="gdlr-core-divider-line gdlr-core-skin-divider" id="div_983a_49"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="kingster-footer-column kingster-item-pdlr kingster-column-15">
						<div id="gdlr-core-custom-menu-widget-2" class="widget widget_gdlr-core-custom-menu-widget kingster-widget">
							<h3 class="kingster-widget-title">Our Campus</h3><span class="clear"></span>
							<div class="menu-our-campus-container">
								<ul id="menu-our-campus" class="gdlr-core-custom-menu-widget gdlr-core-menu-style-plain">
									<li class="menu-item"><a href="#">Acedemic</a></li>
									<li class="menu-item"><a href="#">Planning &#038; Administration</a></li>
									<li class="menu-item"><a href="#">Campus Safety</a></li>
									<li class="menu-item"><a href="#">Office of the Chancellor</a></li>
									<li class="menu-item"><a href="#">Facility Services</a></li>
									<li class="menu-item"><a href="#">Human Resources</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="kingster-footer-column kingster-item-pdlr kingster-column-15">
						<div id="gdlr-core-custom-menu-widget-3" class="widget widget_gdlr-core-custom-menu-widget kingster-widget">
							<h3 class="kingster-widget-title">Campus Life</h3><span class="clear"></span>
							<div class="menu-campus-life-container">
								<ul id="menu-campus-life" class="gdlr-core-custom-menu-widget gdlr-core-menu-style-plain">
									<li class="menu-item"><a href="#">Accessibility</a></li>
									<li class="menu-item"><a href="#">Financial Aid</a></li>
									<li class="menu-item"><a href="#">Food Services</a></li>
									<li class="menu-item"><a href="#">Housing</a></li>
									<li class="menu-item"><a href="#">Information Technologies</a></li>
									<li class="menu-item"><a href="#">Student Life</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="kingster-footer-column kingster-item-pdlr kingster-column-15">
						<div id="gdlr-core-custom-menu-widget-4" class="widget widget_gdlr-core-custom-menu-widget kingster-widget">
							<h3 class="kingster-widget-title">Academics</h3><span class="clear"></span>
							<div class="menu-academics-container">
								<ul id="menu-academics" class="gdlr-core-custom-menu-widget gdlr-core-menu-style-plain">
									<li class="menu-item"><a href="#">Canvas</a></li>
									<li class="menu-item"><a href="#">Catalyst</a></li>
									<li class="menu-item"><a href="#">Library</a></li>
									<li class="menu-item"><a href="#">Time Schedule</a></li>
									<li class="menu-item"><a href="#">Apply For Admissions</a></li>
									<li class="menu-item"><a href="#">Pay My Tuition</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="kingster-copyright-wrapper">
				<div class="kingster-copyright-container kingster-container clearfix">
					<div class="kingster-copyright-left kingster-item-pdlr">Copyright All Right Reserved 2019, Max Themes</div>
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


<script type='text/javascript' src='<?=$theme_path;?>js/jquery/jquery.js'></script>
<script type='text/javascript' src='<?=$theme_path;?>js/jquery/jquery-migrate.min.js'></script>
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
<?= $script_js; ?>

</body>
</html>
