<!DOCTYPE html>
<html lang="zxx">


<head>
    <title><?= Settings_m::getSetting('site_title'); ?></title>
    <link rel="icon" href="<?= base_url('themes/gigaland'); ?>/images/logowebrev.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="<?= Settings_m::getSetting('site_title'); ?>" name="description" />
    <meta content="<?= Settings_m::getSetting('site_title'); ?>,Smite" name="keywords" />
    <meta content="smite" name="author" />
    <!-- CSS Files
    ================================================== -->
    <link id="bootstrap" href="<?= base_url('themes/gigaland'); ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="<?= base_url('themes/gigaland'); ?>/css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="<?= base_url('themes/gigaland'); ?>/css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('themes/gigaland'); ?>/css/animate.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('themes/gigaland'); ?>/css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('themes/gigaland'); ?>/css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('themes/gigaland'); ?>/css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('themes/gigaland'); ?>/css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('themes/gigaland'); ?>/css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('themes/gigaland'); ?>/css/style.css" rel="stylesheet" type="text/css" />
    <!-- <link href="<?= base_url('themes/gigaland'); ?>/css/style-nav.css" rel="stylesheet" type="text/css" /> -->
    <link href="<?= base_url('themes/gigaland'); ?>/css/de-grey.css" rel="stylesheet" type="text/css" />
    <!-- color scheme -->
    <link id="colors" href="<?= base_url('themes/gigaland'); ?>/css/colors/scheme-04.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('themes/gigaland'); ?>/css/coloring.css" rel="stylesheet" type="text/css" />
</head>

<body class="dark-scheme de-grey">
    <div id="wrapper">
        <!-- header begin -->
        <!-- header close -->
        <!-- content begin -->
        <div class="no-bottom no-top" id="content">
            <div id="top"></div>
            <section id="section-hero" class="no-bottom" data-bgimage="url(<?= base_url('themes/gigaland'); ?>/images/background/13.jpg) bottom">
                <div class="container">
                    <div id="items-carousel-big" class="owl-carousel">
                        <!-- carousel item -->
                        <div class="nft__item_lg">
                            <div class="row align-items-center">
                                <div class="col-lg-3 wow fadeInRight" data-wow-delay=".5s">
                                    <!-- <img src="<?= base_url('themes/gigaland'); ?>/images/webposterrev.jpg" style="width:500px; height:620px; text-align:right; margin-left:auto;" class="img-fluid" alt=""> -->
                                </div>
                                <div class="col-lg-9 wow fadeInRight" data-wow-delay=".5s">
                                    <div class="d-desc">
                                        <h3 style="color:#F4AD39; text-shadow: 3px 2px 1px black; ">20<sup>th</sup> Asian Oceanian Myology Center Meeting in Conjunction with National Scientific Meeting PERDOSSI, Banjarmasin, June 9th -12th, 2022</h3><br>
                                        <div class="row">
                                            <img src="<?= base_url('themes/gigaland'); ?>/images/logowebrev.png" style="width:100px;" alt="">
                                            <img src="<?= base_url('themes/gigaland'); ?>/images/aomccm.png" style="width:170px;" alt="">
                                            <img src="<?= base_url('themes/gigaland'); ?>/images/AOMC.png" style="width:100px;" alt="">
                                            <img src="<?= base_url('themes/gigaland'); ?>/images/perdossi.png" style="width:100px;" alt="">
                                            <img src="<?= base_url('themes/gigaland'); ?>/images/idi.png" style="width:100px;" alt="">
                                        </div>
                                        <div class="spacer-10"></div>
                                        <div class="d-buttons">
                                            <!--<a href="#" class="btn-main btn-lg" style="background-color:#F4AD39; color:black;">This Site Under Maintenance</a>&nbsp;-->

                                            <a href="<?= base_url('site/home'); ?>" class="btn-main btn-sm" style="background-color:#F4AD39; color:black;">Visit our Website here</a>&nbsp;
                                            <a href="<?= base_url('member/register'); ?>" class="btn-main btn-sm" style="background-color:#F4AD39; color:black;">Click here For Individual Registration</a>&nbsp;
                                            <a href="<?= base_url('member/register/group'); ?>" class="btn-main btn-sm" style="background-color:#F4AD39; color:black;">Click here for Group Registration</a>&nbsp;

										<div class="spacer-10"></div>
										<div class="spacer-10"></div>

<<<<<<< HEAD
                                        	<a href="<?= base_url('themes/gigaland'); ?>/images/3rdAnnouncement20220416.pdf" target="blank" class="btn-main btn-lg" style="background-color:#F4AD39; color:black;">Download 3 <sup>rd</sup> Announcement here (updated on April 16th)</a>&nbsp;
=======
                                        	<a href="<?= base_url('themes/gigaland'); ?>/images/3rdAnnouncement20220415.pdf" target="blank" class="btn-main btn-lg" style="background-color:#F4AD39; color:black;">Download 3 <sup>rd</sup> Announcement here (updated on April 15th)</a>&nbsp;
>>>>>>> 843562138b2b3710c786a2d3b5e66a96ff987c81

                                            <!--<a href="" class="btn-main btn-lg" style="background-color:#F4AD39; color:black;">Enter</a>&nbsp;-->											
                                        </div>
                                        <hr>
                                        <div class="container">
											<h3 style="color:#f44939;">NEWS! Abstract Submission is extended to April 30th, 2022</h3><br>
                                            <div class="row align-items-center wow fadeInRight" data-wow-delay=".5s">
                                                <div class="col-lg-6">
                                                    <?php if (!$hasSession) : ?>
                                                        <h3 style="color:#F4AD39;">Sign In</h3><br>
                                                    <?php endif;?>
                                                    <form name="contactForm" id='contact_form' class="form-border" method="post" action="<?= base_url('site/login'); ?>">
                                                        <?php if (!$hasSession) : ?>
                                                            <div class="field-set">
                                                                <input type='text' name='username' id='email' class="form-control" placeholder="Email">
                                                            </div>

                                                            <div class="field-set">
                                                                <input type='password' name='password' id='password' class="form-control" placeholder="Password">
                                                            </div>
                                                        <?php endif; ?>
                                                        <a href="<?=base_url('site/forget');?>">Forgot Password ?</a>
                                                        <div class="d-buttons">
                                                            <input type="submit" name="login" class="btn-main btn-fullwidth" value="<?= $hasSession ? "Back To Member Area" : "Sign"; ?>" style="background-color:#F4AD39; color:black;"><br>
                                                        </div>
                                                    </form>
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
        </div>
        <!-- content close -->


        <!-- footer begin -->

        <div class="subfooter">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="de-flex">
                            <div class="de-flex-col">
                                <a href="index">
                                    <span class="copy" style="color:#F4AD39;">&copy; Copyright - 20th AOMC and PIN 2022</span>
                                </a>
                            </div>
                            <div class="de-flex-col">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer close -->

    </div>



    <!-- Javascript Files
    ================================================== -->
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.min.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/bootstrap.min.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/wow.min.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.isotope.min.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/easing.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/owl.carousel.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/validation.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.magnific-popup.min.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/enquire.min.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.plugin.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.countTo.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.countdown.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.lazy.min.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.lazy.plugins.min.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/designesia.js"></script>


</body>

</html>