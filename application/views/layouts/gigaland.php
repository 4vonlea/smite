<?php

/**
 * @var $content
 */
$theme_path = base_url("themes/gigaland") . "/";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Gigaland - NFT Marketplace Website Template</title>
    <link rel="icon" href="<?= $theme_path; ?>images/icon.png" type="image/gif" sizes="16x16">
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

<body>
    <div id="wrapper">
        <!-- header begin -->
        <header class="transparent scroll-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-12" style="background-size: cover;">
                        <div class="de-flex sm-pt10" style="background-size: cover;">
                            <div class="de-flex-col" style="background-size: cover;">
                                <div class="de-flex-col" style="background-size: cover;">
                                    <!-- logo begin -->
                                    <div id="logo" style="background-size: cover;">
                                        <a href="<?= base_url('site'); ?>">
                                            <img alt="" class="logo" height="30px" src="<?= base_url('themes/uploads/logo.png'); ?>" />
                                            <img alt="" class="logo-2" height="30px" src="<?= base_url('themes/uploads/logo.png'); ?>" />
                                        </a>
                                    </div>
                                    <!-- logo close -->
                                </div>
                            </div>
                            <div class="de-flex-col header-col-mid" style="background-size: cover;">
                                <!-- mainmenu begin -->
                                <ul id="mainmenu">
                                    <li class="menu-item-has-children">
                                        <a href="<?= base_url('site'); ?>">Beranda<span></span></a><span></span>
                                    </li>
                                    <li class="menu-item-has-children has-child">
                                        <a href="#">Jadwal<span></span></a><span></span>
                                        <ul>
                                            <li><a href="<?= base_url('site/schedules'); ?>">Simposium</a></li>
                                            <li><a href="<?= base_url('site/oralposter'); ?>">Presentasi Oral & E-Poster</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="<?= base_url('site/committee'); ?>">Panitia<span></span></a><span></span>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="#footer">Hubungi Kami<span></span></a><span></span>
                                    </li>
                                    <li class="menu-item-has-children has-child">
                                        <a href="#">Area Pengguna<span></span></a><span></span>
                                        <ul>
                                            <li><a href="<?= base_url('site/login'); ?>">Login</a></li>
                                            <li><a href="<?= base_url('member/register'); ?>">Registrasi</a></li>
                                        </ul>
                                    </li>
                                </ul>
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
        <footer class="footer-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-1">
                        <div class="widget">
                            <h5>Marketplace</h5>
                            <ul>
                                <li><a href="#">All NFTs</a></li>
                                <li><a href="#">Art</a></li>
                                <li><a href="#">Music</a></li>
                                <li><a href="#">Domain Names</a></li>
                                <li><a href="#">Virtual World</a></li>
                                <li><a href="#">Collectibles</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-1">
                        <div class="widget">
                            <h5>Resources</h5>
                            <ul>
                                <li><a href="#">Help Center</a></li>
                                <li><a href="#">Partners</a></li>
                                <li><a href="#">Suggestions</a></li>
                                <li><a href="#">Discord</a></li>
                                <li><a href="#">Docs</a></li>
                                <li><a href="#">Newsletter</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-1">
                        <div class="widget">
                            <h5>Community</h5>
                            <ul>
                                <li><a href="#">Community</a></li>
                                <li><a href="#">Documentation</a></li>
                                <li><a href="#">Brand Assets</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">Forum</a></li>
                                <li><a href="#">Mailing List</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-1">
                        <div class="widget">
                            <h5>Newsletter</h5>
                            <p>Signup for our newsletter to get the latest news in your inbox.</p>
                            <form action="blank.php" class="row form-dark" id="form_subscribe" method="post" name="form_subscribe">
                                <div class="col text-center">
                                    <input class="form-control" id="txt_subscribe" name="txt_subscribe" placeholder="enter your email" type="text" /> <a href="#" id="btn-subscribe"><i class="arrow_right bg-color-secondary"></i></a>
                                    <div class="clearfix"></div>
                                </div>
                            </form>
                            <div class="spacer-10"></div>
                            <small>Your email is safe with us. We don't spam.</small>
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
                                    <a href="index.html">
                                        <img alt="" class="f-logo" src="<?= $theme_path; ?>images/logo.png" /><span class="copy">&copy; Copyright 2021 - Gigaland by Designesia</span>
                                    </a>
                                </div>
                                <div class="de-flex-col">
                                    <div class="social-icons">
                                        <a href="#"><i class="fa fa-facebook fa-lg"></i></a>
                                        <a href="#"><i class="fa fa-twitter fa-lg"></i></a>
                                        <a href="#"><i class="fa fa-linkedin fa-lg"></i></a>
                                        <a href="#"><i class="fa fa-pinterest fa-lg"></i></a>
                                        <a href="#"><i class="fa fa-rss fa-lg"></i></a>
                                    </div>
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