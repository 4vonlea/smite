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
        <header class="transparent scroll-dark">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="de-flex sm-pt10">
                            <div class="de-flex-col">
                                <div class="de-flex-col">
                                    <!-- logo begin -->
                                    <div id="logo">
                                        <a href="03_grey-index.html">
                                            <img alt="" src="images/logowebrev.png" style="width:50px" />
                                            <!-- <span><b>&nbsp; Annual Scientific Meeting</b></span> -->
                                        </a>
                                    </div>
                                    <!-- logo close -->
                                </div>

                            </div>

                            <div class="de-flex-col header-col-mid">
                                <!-- mainmenu begin -->
                                <ul id="mainmenu">
                                    <li>
                                        <a href="home" style="color:#F4AD39;">Home<span></span></a>
                                    </li>


                                </ul>
                                <!-- mainmenu close -->
                                <div class="menu_side_area">
                                    <a href="registration" style="background-color:#F4AD39; color:black;" class="btn-main btn-tasks"><i class="icon_document"></i><span>Registration</span></a>
                                    <span id="menu-btn"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </header>
    <!-- header close -->
    <!-- content begin -->
    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <!-- section begin -->
        <!-- <section id="subheader">
            <div class="center-y relative text-center">
                <div class="container">
                    <div class="row">

                        <div class="col-md-12 text-center">
                            <h1 style="color:#F4AD39;">Registration</h1>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- section close -->

        <!-- section begin -->
        <section id="section-main" aria-label="section" style="color:#F4AD39;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form id="form-create-item" class="form-border" method="post" action="">
                            <div class="de_tab tab_simple">
                                <h4 style="color:#F4AD39;"><i class="fa fa-home"></i> Login</h4>
                                <div class="de_tab_content">
                                    <div class="tab-1">
                                        <div class="row wow fadeIn">
                                            <div class="col-lg-12 mb-sm-20">
                                                <div class="col-12">
                                                    <?php if ($error != '') : ?>
                                                        <div class="alert alert-danger">
                                                            <?= $error; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="field-set" style="color:#F4AD39;">
                                                    <div class="field-set">
                                                        <input type='text' name='email' id='email' class="form-control" placeholder="Email">
                                                    </div>

                                                    <div class="field-set">
                                                        <input type='password' name='password' id='password' class="form-control" placeholder="Password">
                                                    </div>
                                                    <div class="d-buttons">
                                                        <button href="" class="btn-main btn-fullwidth" name="login" style="background-color:#F4AD39; color:black;">Sign</button><br>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>


                                </div>
                            </div>

                            <!-- <div class="spacer-30"></div>
                            <input type="button" style="background-color:#F4AD39; color:black;" id="submit" class="btn-main" value="Register">
                            </form> -->
                    </div>
                </div>
            </div>
        </section>
    </div>


    <a href="#" id="back-to-top"></a>

    <!-- footer begin -->
    <!-- footer begin -->
    <footer style="color:#F4AD39;">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-1">
                    <div class="widget">
                        <h5 style="color:#F4AD39;">AOMC</h5>
                        <ul>
                            <li><u href="#">Contact Person</u></li>
                            <li><a href="https://wa.me/6285163683209" target="blank">Rida Sieseria, MD : 085163683209 (Registration and Information)</a></li>
                            <li><a href="#" target="blank">Email : admin@aomc-pinbanjarmasin2022.com</a></li>
                            <hr>
                            <li><a href="https://wa.me/628179400579" target="blank">Fachrurrazy, MD : 08179400579 (Scientific Affair)</a></li>
                            <li><a href="#" target="blank">Email : scientific@aomc-pinbanjarmasin2022.com</a></li>
                            <hr>
                            <li><a href="https://wa.me/6285888885010" target="blank">Asnelia Devicaesaria, MD : 085888885010 (Sponsorship Affair)</a></li>
                            <li><a href="#" target="blank">Email : sponsor@aomc-pinbanjarmasin2022.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-1">
                    <div class="widget">
                        <h5 style="color:#F4AD39;">Registration Fee</h5>
                        <ul>
                            <li><b href="#"><u>Domestic Participant (IDR):</u></b></li>
                            <li><a href="#">- Participant</a></li>
                            <li><a href="#">1. Specialist : Rp. 1.000.000(Early Bird) / Rp. 1.250.000(After 16 May 22)</a></li>
                            <li><a href="#">2. General Practioner : Rp. 500.000(Early Bird) / Rp. 700.000(After 16 May 22)</a></li>
                            <li><a href="#">3. Resident : Rp. 500.000(Early Bird) / Rp. 700.000(After 16 May 22)</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-1">
                    <div class="widget">
                        <h5 style="color:#F4AD39;">Registration Fee</h5>
                        <ul>
                            <li><b href="#"><u>International Participant ($US):</u></b></li>
                            <li><a href="#">- Participant</a></li>
                            <li><a href="#">1. Specialist : $50(Early Bird) / $70(After 16 May 22)</a></li>
                            <li><a href="#">2. Trainee : $35(Early Bird) / $50(After 16 May 22)</a></li>

                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-1">
                    <div class="widget">
                        <h5 style="color:#F4AD39;">Note</h5>
                        <p>Note : <br>
                            - Workshop / Teaching Course participant must be registered as symposium participant<br>
                            - All registration fee should be paid by payment gateway in our
                            website: <a href="www.aomc-pinbanjarmasin2022.com">www.aomc-pinbanjarmasin2022.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="subfooter">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="de-flex">
                            <div class="de-flex-col">
                                <a href="03_grey-index.html">
                                    <span class="copy" style="color:#F4AD39;">&copy; Copyright 2022 - Annual Scientific Meeting AOMC</span>
                                </a>
                            </div>
                            <div class="de-flex-col">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer close -->
    </div>



    <!-- Javascript Files
    ================================================== -->
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