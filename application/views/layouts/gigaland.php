<?php

/**
 * @var $content
 */
$theme_path = base_url("themes/gigaland") . "/";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title><?= Settings_m::getSetting('site_title'); ?></title>
    <link rel="icon" href="<?= base_url('themes/gigaland'); ?>/images/logowebrev.png" type="image/gif" sizes="16x16">
   <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Gigaland - NFT Marketplace Website Template" name="description" />
    <meta content="" name="keywords" />
    <meta content="" name="author" />
    <!-- CSS Files
    ================================================== -->
    <link id="bootstrap" href="<?= $theme_path; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="<?= $theme_path; ?>css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="<?= $theme_path; ?>css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/animate.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/style.css" rel="stylesheet" type="text/css" />
    <!-- color scheme -->
    <link id="colors" href="<?= $theme_path; ?>css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/coloring.css" rel="stylesheet" type="text/css" />

    <?php if (ENVIRONMENT == "production") : ?>
        <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <?php else : ?>
        <script src="<?= base_url('themes/script/vue.js'); ?>"></script>
    <?php endif; ?>
    <?= $additional_head; ?>

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
                                            <img alt="" src="<?= base_url('themes/gigaland'); ?>/images/logowebrev.png" style="width:50px" />
                                            <!-- <span><b>&nbsp; Annual Scientific Meeting</b></span> -->
                                        </a>
                                    </div>
                                    <!-- logo close -->
                                </div>

                            </div>

                            <div class="de-flex-col header-col-mid">
                                <!-- mainmenu begin -->
                                <?php
                                    $isHome = $this->router->class == "Site" && $this->router->method == "home";
                                ?>
                                <ul id="mainmenu">
                                    <li>
                                        <a href="<?=$isHome ? '':base_url('site/home');?>#content" style="color:#F4AD39;">Home<span></span></a>
                                    </li>
                                    <li>
                                        <a href="<?=$isHome ? '':base_url('site/home');?>#sign" style="color:#F4AD39;">Sign In<span></span></a>
                                    </li>
                                    <li>
                                        <a href="<?=$isHome ? '':base_url('site/home');?>#event" style="color:#F4AD39;">Event<span></span></a>
                                    </li>
                                    <li>
                                        <a href="<?=$isHome ? '':base_url('site/home');?>#abstract" style="color:#F4AD39;">Abstract<span></span></a>
                                    </li>

                                </ul>
                                <!-- mainmenu close -->
                                <div class="menu_side_area">
                                    <a href="<?= base_url('member/register'); ?>" class="btn-main btn-tasks" style="background-color:#F4AD39; color:black;"><i class="icon_document"></i><span>Registration</span></a>
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
        <?= $content; ?>
    </div>
    <!-- content close -->
    <a href="#" id="back-to-top"></a>
    <!-- footer begin -->
    <footer style="color:#F4AD39;">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-1">
                    <div class="widget">
                        <h5 style="color:#F4AD39;">AOMC</h5>
                        <ul>
                            <li><u href="#">Contact Person</u></li>
                            <li><a href="#">Name : Is Me (+62############)</a></li>
                            <li><a href="#">Name : Is Me (+62############)</a></li>
                            <li><a href="#">Email : email@email.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-1">
                    <div class="widget">
                        <h5 style="color:#F4AD39;">Registration Fee</h5>
                        <ul>
                            <li><b href="#"><u>Domestic Participant (IDR):</u></b></li>
                            <li><a href="#">- Participant</a></li>
                            <li><a href="#">1. Specialist : 1000000(Early Bird) / 1250000(After 16 May 22)</a></li>
                            <li><a href="#">2. General Practioner : 500000(Early Bird) / 700000(After 16 May 22)</a></li>
                            <li><a href="#">3. Resident : 500000(Early Bird) / 700000(After 16 May 22)</a></li>
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
    <script src="<?= $theme_path; ?>js/jquery.min.js"></script>
    <script src="<?= $theme_path; ?>js/bootstrap.min.js"></script>
    <script src="<?= $theme_path; ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= $theme_path; ?>js/wow.min.js"></script>
    <script src="<?= $theme_path; ?>js/jquery.isotope.min.js"></script>
    <script src="<?= $theme_path; ?>js/easing.js"></script>
    <script src="<?= $theme_path; ?>js/owl.carousel.js"></script>
    <script src="<?= $theme_path; ?>js/validation.js"></script>
    <script src="<?= $theme_path; ?>js/jquery.magnific-popup.min.js"></script>
    <script src="<?= $theme_path; ?>js/enquire.min.js"></script>
    <script src="<?= $theme_path; ?>js/jquery.plugin.js"></script>
    <script src="<?= $theme_path; ?>js/jquery.countTo.js"></script>
    <script src="<?= $theme_path; ?>js/jquery.countdown.js"></script>
    <script src="<?= $theme_path; ?>js/jquery.lazy.min.js"></script>
    <script src="<?= $theme_path; ?>js/jquery.lazy.plugins.min.js"></script>
    <script src="<?= $theme_path; ?>js/designesia.js"></script>

    <!-- COOKIES NOTICE  -->
    <script src="<?= $theme_path; ?>js/cookit.js"></script>
    <script src="<?= base_url("themes/script/moment.min.js"); ?>"></script>
    <script>
        $(document).ready(function() {
            $.cookit({
                backgroundColor: '#101010',
                messageColor: '#fff',
                linkColor: '#FEF006',
                buttonColor: '#FEF006',
                messageText: "This website uses cookies to ensure you get the best experience on our website.",
                linkText: "Learn more",
                linkUrl: "index.html",
                buttonText: "I accept",
            });
        });
    </script>
    <?= $script_js; ?>


</body>

</html>