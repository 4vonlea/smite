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
    <meta content="<?= Settings_m::getSetting('site_title'); ?>" name="description" />
    <meta content="<?= Settings_m::getSetting('site_title'); ?>,Smite" name="keywords" />
    <meta content="smite" name="author" />
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
    <link href="<?= $theme_path; ?>fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/style.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/de-grey.css" rel="stylesheet" type="text/css" />
    <!-- color scheme -->
    <link id="colors" href="<?= $theme_path; ?>css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="<?= $theme_path; ?>css/coloring.css" rel="stylesheet" type="text/css" />

    <?php if (ENVIRONMENT == "production") : ?>
        <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
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

                            <?php if (isset($isLogin)) { ?>
                                <div class="de-flex-col header-col-mid">
                                    <!-- mainmenu begin -->
                                    <?php
                                        $member = $this->router->class == "area";
                                        $userDetail = array_merge($user->toArray(), ['status_member' => $user->status_member->kategory]);
                                    ?>
                                    <ul id="mainmenu">
                                        <li>
                                            <a href="<?= base_url('site/home'); ?>#content" style="color:#F4AD39;">Home<span></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url('member/area'); ?>#/profile" style="color:#F4AD39;">Profile<span></span></a>
                                        </li>
                                        <?php if($hasSettlementTransaction):?>
                                        <li>
                                            <a href="<?= base_url('member/area'); ?>#/paper" style="color:#F4AD39;">Send Manuscript<span></span></a>
                                        </li>
                                        <?php endif;?>
                                        <li class="menu-item-has-children has-child">
                                            <a href="#" style="color:#F4AD39;">Purchase<span></span></a><span></span>
                                            <ul>
                                                <li><a href="<?= base_url('member/area'); ?>#/events">Events</a></li>
                                                <li><a href="<?= base_url('member/area'); ?>#/billing">Cart & Payment</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children has-child">
                                            <a href="#" style="color:#F4AD39;">On Event<span></span></a><span></span>
                                            <ul>
                                                <li><a href="<?= base_url('member/area'); ?>#/webminar">Webinar Link</a></li>
                                                <?php if (in_array($userDetail['status'], $statusToUpload)) : ?>
                                                    <li><a href="<?= base_url('member/area'); ?>#/material">Upload Material</a></li>
                                                <?php endif; ?>
                                                <li><a href="<?= base_url('member/area'); ?>#/sertifikat">Download Certificate</a></li>
                                                <li><a href="<?= base_url('member/area'); ?>#/presentation">List of Scientific Presentations</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="<?= base_url('member/area/logout'); ?>" style="color:#F4AD39;">Logout<span></span></a>
                                        </li>

                                    </ul>
                                    <div class="menu_side_area">
                                        <span id="menu-btn"></span>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="de-flex-col header-col-mid">
                                    <!-- mainmenu begin -->
                                    <?php
                                    $isHome = $this->router->class == "Site" && $this->router->method == "home";
                                    ?>
                                    <ul id="mainmenu">
                                        <li>
                                            <a href="<?= $isHome ? '' : base_url('site/home'); ?>#content" style="color:#F4AD39;">Home<span></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= $isHome ? '' : base_url('site/home'); ?>#sign" style="color:#F4AD39;">Sign In<span></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= $isHome ? '' : base_url('site/home'); ?>#event" style="color:#F4AD39;">Event<span></span></a>
                                        </li>
                                    </ul>
                                    <!-- mainmenu close -->
                                    <div class="menu_side_area">
                                        <a href="<?= base_url('member/register'); ?>" class="btn-main btn-tasks" style="background-color:#F4AD39; color:black;"><i class="icon_document"></i><span>Registration</span></a>
                                        <span id="menu-btn"></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-4 col-8">
                        <a href="https://live.aomc-pinbanjarmasin2022.com/login" class="btn-main btn-lg" style="background-color:#00fdff; color:black;">-------------Click here to Join Event-------------</a><br>
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
        <div class="container" style="background-size: cover;">
            <div class="row" style="background-size: cover;">
                <div class="col-md-3 col-sm-6 col-xs-1" style="background-size: cover;">
                    <div class="widget" style="background-size: cover;">
                        <h5 style="color:#F4AD39;">Important Dates</h5>
                        <ul>
                            <li><u href="#">To be announced</u></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-1" style="background-size: cover;">
                    <div class="widget" style="background-size: cover;">
                        <h5 style="color:#F4AD39;">Registration Fee</h5>
                        <ul>
                            <li><b href="#"><u>Indonesian Participant (IDR):</u></b></li>
                            <li><a href="#">- Participant (both access to AOMC and PIN meeting) </a></li>
                            <li><a href="#">1. Specialist : Rp. 1.000.000 (Early Bird) / Rp. 1.250.000 (After 16 May 22)</a></li>
                            <li><a href="#">2. General Practioner : Rp. 500.000 (Early Bird) / Rp. 700.000 (After 16 May 22)</a></li>
                            <li><a href="#">3. Resident : Rp. 500.000 (Early Bird) / Rp. 700.000 (After 16 May 22)</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-1" style="background-size: cover;">
                    <div class="widget" style="background-size: cover;">
                        <h5 style="color:#F4AD39;">Registration Fee</h5>
                        <ul>
                            <li><b href="#"><u>International Participant (USD):</u></b></li>
                            <li><a href="#">- Participant (access to AOMC meeting only)</a></li>
                            <li><a href="#">1. Specialist : $50 (Early Bird) / $70 (After 16 May 22)</a></li>
                            <li><a href="#">2. Trainee : $35 (Early Bird) / $50 (After 16 May 22)</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-1" style="background-size: cover;">
                    <div class="widget" style="background-size: cover;">
                        <h5 style="color:#F4AD39;">Note</h5>
                        <p>Note : <br>
                            - Workshop / Teaching Course participant must be registered as symposium participant<br>
                            - All registration fee should be paid by payment gateway in our
                            website: <a href="https://aomc-pinbanjarmasin2022.com">www.aomc-pinbanjarmasin2022.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="subfooter" style="background-size: cover;">
            <div class="container" style="background-size: cover;">
                <div class="row" style="background-size: cover;">
                    <div class="col-md-12" style="background-size: cover;">
                        <div class="de-flex" style="background-size: cover;">
                            <div class="de-flex-col" style="background-size: cover;">
                                <a href="03_grey-index.html">
                                    <span class="copy" style="color:#F4AD39;"> Copyright - 20th AOMC - PIN 2022</span>
                                </a>
                            </div>
                            <div class="de-flex-col" style="background-size: cover;">

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