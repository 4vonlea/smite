<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Laralink">
    <!-- Favicon Icon -->
    <link rel="icon" href="<?= base_url('themes/aenft'); ?>/assets/img/konas/logo.png" />
    <!-- Site Title -->
    <title>NATIONAL CONGRESS OF INDONESIAN NEUROLOGY ASSOCIATION 2023 SEMARANG</title>
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/css/plugins/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/css/plugins/slick.css">
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/css/plugins/animate.css">
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/css/custom.css">
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/fontawesome/css/all.min.css">
    <?php if (ENVIRONMENT == "production") : ?>
        <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <?php else : ?>
        <script src="<?= base_url('themes/script/vue.js'); ?>"></script>
    <?php endif; ?>
    <?= $additional_head; ?>

</head>

<body class="cs-dark">
    <div class="cs-preloader cs-white_bg cs-center">
        <div class="cs-preloader_in">
            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/logo.png" alt="Logo">
        </div>
    </div>

    <button class="btn btn-sm btn-primary position-fixed bottom-0 end-0 translate-middle d-none" onclick="scrollToTop()" id="back-to-up">
        <i class="fa fa-arrow-up" aria-hidden="true"></i>
    </button>
    <!-- Start Header Section -->
    <header class="cs-site_header cs-style1 cs-sticky-header cs-primary_color text-uppercase cs-white_bg">
        <div class="cs-main_header">
            <div class="container">
                <div class="cs-main_header_in">
                    <div class="cs-main_header_left">
                        <a class="cs-site_branding cs-accent_color" href="<?= base_url('site/home'); ?>">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/logo.png" alt="Logo" class="cs-hide_dark">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/logo.png" alt="Logo" class="cs-hide_white">
                        </a>
                    </div>
                    <div class="cs-main_header_center">
                        <div class="cs-nav">
                            <ul class="cs-nav_list">
                                <?php if (isset($isLogin)) { ?>
                                    <!-- mainmenu begin -->
                                    <?php
                                    $member = $this->router->class == "area";
                                    $userDetail = array_merge($user->toArray(), ['status_member' => $user->status_member->kategory]);
                                    ?>
                                    <li>
                                        <a class="cs-smoth_scroll" href="<?= base_url('site/home'); ?>#content">
                                            <i class="fa fa-home me-1"></i>
                                            <span><?= lang("home"); ?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="cs-smoth_scroll" href="<?= base_url('member/area'); ?>#/profile">
                                            <i class="fa fa-user me-1"></i>
                                            <span><?= lang("profile"); ?></span>
                                        </a>
                                    </li>

                                    <li class="menu-item-has-children">
                                        <a href="#">
                                            <i class="fa fa-calendar me-1"></i>Event & Program
                                        </a>
                                        <span class="fa fa-chevron-down"></span>
                                        <ul>
                                            <li>
                                                <a class="cs-smoth_scroll" href="<?= base_url('member/area'); ?>#/events">

                                                    <span><?= lang("select_event"); ?></span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="cs-smoth_scroll" href="<?= base_url('member/area'); ?>#/billing">

                                                    <span><?= lang("payment"); ?></span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="cs-smoth_scroll" href="<?= base_url('member/area'); ?>#/com_program">

                                                    <span><?= lang("com_program"); ?></span>
                                                </a>
                                            </li>
                                            <?php if ($hasSettlementTransaction) : ?>
                                                <li>
                                                    <a class="cs-smoth_scroll" href="<?= base_url('member/area'); ?>#/paper">

                                                        <span><?= lang("send_abstrack"); ?></span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>



                                    <!-- <li>
                                      <a href="<?= base_url('member/area'); ?>#/webminar">
                                          <i class="fa fa-cart-shopping fa-lg"></i>
                                          <span>Webinar Link</span>
                                      </a>
                                  </li> -->
                                    <?php if (in_array($userDetail['status'], $statusToUpload)) : ?>
                                        <li>
                                            <a class="cs-smoth_scroll" href="<?= base_url('member/area'); ?>#/material">
                                                <i class="fa fa-upload me-1"></i>
                                                <span><?= lang("upload_material"); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <!-- <li><a href="<?= base_url('member/area'); ?>#/sertifikat">Unduh Certificate</a></li> -->
                                    <li>
                                        <a class="cs-smoth_scroll" href="<?= base_url('member/area'); ?>#/presentation">
                                            <i class="fa fa-photo-video me-1"></i>
                                            <span><?= lang("scientific_presentation"); ?></span>
                                        </a>
                                    </li>

                                <?php } else { ?>
                                    <li><a href="<?= base_url('site/home'); ?>#home" class="cs-smoth_scroll">
                                            <i class="fa fa-home me-1"></i>
                                            Home</a>
                                    </li>
                                    <li><a href="<?= base_url('site/home'); ?>#event" class="cs-smoth_scroll">
                                            <i class="fa fa-calendar me-1"></i>
                                            Event</a></li>
                                    <li><a href="<?= base_url('site/home'); ?>#news" class="cs-smoth_scroll">
                                            <i class="fa fa-newspaper me-1"></i>
                                            News</a></li>
                                    <li><a href="<?= base_url('site/home'); ?>#login" class="cs-smoth_scroll">
                                            <i class="fa fa-sign-in me-1"></i>
                                            Login</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="cs-main_header_right">

                        <div class="cs-toolbox">
                            <?php if (isset($isLogin)) : ?>
                                <a href="<?= base_url('member/area/logout'); ?>" class="cs-btn cs-btn_filed cs-btn_danger">
                                    <i class="fa fa-arrow-right-from-bracket"></i>
                                    <span>Logout</span>
                                </a>
                            <?php else : ?>
                                <a href="<?= base_url('member/register'); ?>" class="cs-btn cs-btn_filed cs-accent_btn">
                                    <i class="fa-solid fa-clipboard-user"></i>
                                    &nbsp;<span><?= lang("registration"); ?></span>
                                </a>
                            <?php endif; ?>

                            <!-- <div class="dropdown ms-2">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-language"></i> <?= ucfirst($this->config->item("language")); ?>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="<?= current_url() . "?language=english"; ?>">English</a></li>
                                    <li><a class="dropdown-item" href="<?= current_url() . "?language=indonesia"; ?>">Indonesia</a></li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header Section -->
    <div class="cs-height_95 cs-height_lg_95"></div>
    <!--
    <nav class="floating-menu">
        <ul class="main-menu">
            <?php if (isset($isLogin)) { ?>
                <?php
                $member = $this->router->class == "area";
                $userDetail = array_merge($user->toArray(), ['status_member' => $user->status_member->kategory]);
                ?>
                <li>
                    <a class="ripple cs-smoth_scroll" href="<?= base_url('site/home'); ?>#content">
                        <i class="fa fa-home fa-lg"></i>
                        <span><?= lang("home"); ?></span>
                    </a>
                </li>
                <li>
                    <a class="ripple" href="<?= base_url('member/area'); ?>#/profile">
                        <i class="fa fa-user fa-lg"></i>
                        <span><?= lang("profile"); ?></span>
                    </a>
                </li>
                <?php if ($hasSettlementTransaction) : ?>
                    <li>
                        <a class="ripple" href="<?= base_url('member/area'); ?>#/paper">
                            <i class="fa fa-paper-plane fa-lg"></i>
                            <span><?= lang("send_abstrack"); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a class="ripple" href="<?= base_url('member/area'); ?>#/events">
                        <i class="fa fa-calendar fa-lg"></i>
                        <span><?= lang("select_event"); ?></span>
                    </a>
                </li>
                <li>
                    <a class="ripple" href="<?= base_url('member/area'); ?>#/billing">
                        <i class="fa fa-cart-shopping fa-lg"></i>
                        <span><?= lang("payment"); ?></span>
                    </a>
                </li>
                <li>
                    <a class="ripple" href="<?= base_url('member/area'); ?>#/com_program">
                        <i class="far fa-calendar-check fa-lg"></i>
                        <span><?= lang("com_program"); ?></span>
                    </a>
                </li>
                 <li>
                    <a href="<?= base_url('member/area'); ?>#/webminar">
                        <i class="fa fa-cart-shopping fa-lg"></i>
                        <span>Webinar Link</span>
                    </a>
                </li>
                <?php if (in_array($userDetail['status'], $statusToUpload)) : ?>
                    <li>
                        <a class="ripple" href="<?= base_url('member/area'); ?>#/material">
                            <i class="fa fa-upload fa-lg"></i>
                            <span><?= lang("upload_material"); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                 <li><a href="<?= base_url('member/area'); ?>#/sertifikat">Unduh Certificate</a></li>
                <li>
                    <a class="ripple" href="<?= base_url('member/area'); ?>#/presentation">
                        <i class="fas fa-file-powerpoint"></i>
                        <span><?= lang("scientific_presentation"); ?></span>
                    </a>
                </li>

            <?php } else { ?>
                <li><a href="<?= base_url('site/home'); ?>#home" class="ripple cs-smoth_scroll"><i class="fa fa-home fa-lg"></i><span><?= lang("home"); ?></span></a></li>
                <li><a href="<?= base_url('site/home'); ?>#login" class="ripple cs-smoth_scroll"><i class="fa fa-sign-in fa-lg"></i><span><?= lang("login"); ?></span></a></li>
                <li><a href="<?= base_url('site/home'); ?>#event" class="ripple cs-smoth_scroll"><i class="fa fa-calendar fa-lg"></i><span><?= lang("event"); ?></span></a></li>
                <li><a href="<?= base_url('site/home'); ?>#news" class="ripple cs-smoth_scroll"><i class="fa fa-newspaper fa-lg"></i><span><?= lang("news"); ?></span></a></li>
            <?php } ?>
        </ul>
        <div class="menu-bg"></div>
    </nav>
	-->
    <?= $content; ?>
    <div class="cs-height_75 cs-height_lg_45"></div>
    <div class="cs-footer_wrap">
        <div class="wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
            <div class="cs-cta cs-style2 cs-accent_bg">
                <div class="row padding-lg-top">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-middle-item-wrapper">
                            <div class="footer-middle-item mb-lg-0">
                                <div class="fm-item-title">
                                    <h5>Important Dates</h5>
                                    <hr>
                                </div>
                                <div class="fm-item-content">
                                    <ul class="mt-3">
                                        <li>Early Registration : Januari - 31 May 2023</li>
                                        <li>Abstract Submission: April - May 2023</li>
                                        <li>Abstract Announcement: 20 July 2023</li>
                                        <li>Regular Registration : 1 June - 1 August 2023</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-middle-item-wrapper">
                            <div class="footer-middle-item mb-lg-0">
                                <div class="fm-item-title">
                                    <h5>POKDI</h5>
                                    <hr>
                                </div>
                                <div class="fm-item-content">
                                    <ul class="mt-3">
                                         <li>Neurovascular; Neurorestoration; Neurotrauma; Neuropediatri; Neurooncology; Sleep disorder; Neuroinfection; Headache; Neurobehavior; Neurootology-Neuroophtamology Neurointervention; Pain; Neurointensive; Movement Disorder; Neurogeriatri; Epilepsy; Neuroimaging; Neuroepidemiology; Neurophysiology</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-middle-item-wrapper">
                            <div class="footer-middle-item mb-lg-0">
                                <div class="fm-item-title">
                                    <h5>Symposium Fee</h5>
                                    <hr>
                                </div>
                                <div class="fm-item-content">
                                    <ul class="mt-3">
                                        <b>Early Bird</b> <br>
                                        <li>Spesialist : Rp. 3.500.000</li>
                                        <li>GP/ Resident : Rp. 2.000.000</li>
                                        <b>Regular</b> <br>
                                        <li>Spesialist : Rp. 4.000.000</li>
                                        <li>GP/ Resident : Rp. 2.250.000</li>
                                        <b>Onsite</b> <br>
                                        <li>Spesialist : Rp. 4.500.000</li>
                                        <li>GP/ Resident : Rp. 2.500.000</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-middle-item-wrapper">
                            <div class="footer-middle-item mb-lg-0">
                                <div class="fm-item-title">
                                    <h5>Login / Registration</h5>
                                    <hr>
                                </div>
                                <div class="fm-item-content">
                                    <div class="d-grid">
                                        <a href="<?= base_url('site/login'); ?>" class="btn btn-primary mt-3"><i class="fa-solid fa-sign-in"></i> Enter to Login</a>
                                        <a href="<?= base_url('member/register'); ?>" class="btn btn-primary mt-2"><i class="fa-solid fa-clipboard-user"></i> Individual Registration</a>
                                        <a href="<?= base_url('member/register/group'); ?>" class="btn btn-primary mt-2"><i class="fa-solid fa-user-group"></i> Group Registration</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End CTA -->
        <!-- Start Footer -->
        <footer class="cs-footer text-center">
            <div class="container mt-4">
                <div class="cs-copyright text-center wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
                    <p>&copy; 2023 License to #NATIONAL CONGRESS OF THE INDONESIAN NEUROLOGICAL ASSOCIATION 2023#</p>
                    <p>Developed by #CV. Meta Medika#</p><span class="cs-primary_font cs-primary_color"></span>
                </div>
            </div>
            <div class="cs-height_25 cs-height_lg_25"></div>
        </footer>
        <!-- End Footer -->
    </div>

    <!-- inline svg hidden -->
    <svg style="display: none" class="d-none">
        <defs>
            <symbol id="logosvg" viewBox="0 0 400 400">
                <g id="circles" fill="none" stroke-width="16" stroke-linecap="round" stroke-miterlimit="10">
                    <circle cx="200" cy="200" r="180" stroke-dasharray="50 30 30 30 10 30" />
                    <circle cx="200" cy="200" r="155" stroke-dasharray="30 30 80 30" />
                </g>
                <g id="notes" stroke="none">
                    <path d="M232 120C232 106.7 242.7 96 256 96C269.3 96 280 106.7 280 120V243.2L365.3 300C376.3 307.4 379.3 322.3 371.1 333.3C364.6 344.3 349.7 347.3 338.7 339.1L242.7 275.1C236 271.5 232 264 232 255.1L232 120zM256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0zM48 256C48 370.9 141.1 464 256 464C370.9 464 464 370.9 464 256C464 141.1 370.9 48 256 48C141.1 48 48 141.1 48 256z" />
                    <path d="M232 120C232 106.7 242.7 96 256 96C269.3 96 280 106.7 280 120V243.2L365.3 300C376.3 307.4 379.3 322.3 371.1 333.3C364.6 344.3 349.7 347.3 338.7 339.1L242.7 275.1C236 271.5 232 264 232 255.1L232 120zM256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0zM48 256C48 370.9 141.1 464 256 464C370.9 464 464 370.9 464 256C464 141.1 370.9 48 256 48C141.1 48 48 141.1 48 256z" />
                </g>
            </symbol>
        </defs>
    </svg>

    <!-- Script -->
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/jquery.slick.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/jquery.counter.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/wow.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/ripples.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/main.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/custom.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url("themes/script/moment.min.js"); ?>"></script>
    <script type="text/javascript">
        // Notice how this gets configured before we load Font Awesome
        window.FontAwesomeConfig = {
            autoReplaceSvg: false
        }
    </script>
    <?= $script_js; ?>
</body>

</html>