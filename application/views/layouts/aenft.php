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
    <title>KONAS XI PERDOSSI 2023 SEMARANG</title>
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

                    </div>
                    <div class="cs-main_header_right">
                        <?php if (isset($isLogin)) : ?>
                            <div class="cs-toolbox">
                                <a href="<?= base_url('member/area/logout'); ?>" class="cs-btn cs-btn_filed cs-btn_danger">
                                    <i class="fa fa-arrow-right-from-bracket"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="cs-toolbox">
                                <a href="<?= base_url('member/register'); ?>" class="cs-btn cs-btn_filed cs-accent_btn">
                                    <i class="fa-solid fa-clipboard-user"></i>
                                    &nbsp;<span>Registrasi</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header Section -->
    <div class="cs-height_95 cs-height_lg_95"></div>
    <nav class="floating-menu">
        <ul class="main-menu">
            <?php if (isset($isLogin)) { ?>
                <!-- mainmenu begin -->
                <?php
                $member = $this->router->class == "area";
                $userDetail = array_merge($user->toArray(), ['status_member' => $user->status_member->kategory]);
                ?>
                <li>
                    <a class="ripple cs-smoth_scroll" href="<?= base_url('site/home'); ?>#content">
                        <i class="fa fa-home fa-lg"></i>
                        <span>Beranda</span>
                    </a>
                </li>
                <li>
                    <a class="ripple" href="<?= base_url('member/area'); ?>#/profile">
                        <i class="fa fa-user fa-lg"></i>
                        <span>Profil</span>
                    </a>
                </li>
                <?php if ($hasSettlementTransaction) : ?>
                    <li>
                        <a class="ripple" href="<?= base_url('member/area'); ?>#/paper">
                            <i class="fa fa-paper-plane fa-lg"></i>
                            <span>Kirim Abstrak</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a class="ripple" href="<?= base_url('member/area'); ?>#/events">
                        <i class="fa fa-calendar fa-lg"></i>
                        <span>Pilih Kegiatan</span>
                    </a>
                </li>
                <li>
                    <a class="ripple" href="<?= base_url('member/area'); ?>#/billing">
                        <i class="fa fa-cart-shopping fa-lg"></i>
                        <span>Pembayaran</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="<?= base_url('member/area'); ?>#/webminar">
                        <i class="fa fa-cart-shopping fa-lg"></i>
                        <span>Webinar Link</span>
                    </a>
                </li> -->
                <?php if (in_array($userDetail['status'], $statusToUpload)) : ?>
                    <li>
                        <a class="ripple" href="<?= base_url('member/area'); ?>#/material">
                            <i class="fa fa-upload fa-lg"></i>
                            <span>Unggah Materi</span>
                        </a>
                    </li>
                <?php endif; ?>
                <!-- <li><a href="<?= base_url('member/area'); ?>#/sertifikat">Unduh Certificate</a></li> -->
                <li>
                    <a class="ripple" href="<?= base_url('member/area'); ?>#/presentation">
                    <i class="fas fa-file-powerpoint"></i>
                        <span>Presentasi
                            Ilmiah</span>
                    </a>            
                </li>

            <?php } else { ?>
                <li><a href="<?= base_url('site/home'); ?>#home" class="ripple cs-smoth_scroll"><i class="fa fa-home fa-lg"></i></a></li>
                <li><a href="<?= base_url('site/home'); ?>#login" class="ripple cs-smoth_scroll"><i class="fa fa-sign-in fa-lg"></i></a></li>
                <li><a href="<?= base_url('site/home'); ?>#event" class="ripple cs-smoth_scroll"><i class="fa fa-calendar fa-lg"></i></a></li>
            <?php } ?>
        </ul>
        <div class="menu-bg"></div>

    </nav>
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
                                    <h5>Tanggal Penting</h5>
                                    <hr>
                                </div>
                                <div class="fm-item-content">
                                    <ul class="mt-3">
                                        <li>Batas Pengumpulan Abstrak : 21 Oktober 2022</li>
                                        <li>Pengumuman Penerimaan Abstrak : 24 Oktober 2022</li>
                                        <li>Batas Pengumpulan Naskah Lengkap : 30 Oktober 2022</li>
                                        <li>Program Ilmiah : 17 18 November 2022 untuk Workshop, dan 19 20 November 2022 Simposium</li>
                                        <li>E-Poster / Presentasi Oral : 19 November 2022</li>
                                        <li>Pengumuman Pemenang : 20 November 2022</li>
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
                                    <h5>Biaya Registrasi Simposium</h5>
                                    <hr>
                                </div>
                                <div class="fm-item-content">
                                    <ul class="mt-3">
                                        <b>Early Bird</b> <br>
                                        <li>Spesialist : Rp. 3.000.000</li>
                                        <li>Resident : Rp. 1.500.000</li>
                                        <li>GP : Rp. 1.500.000</li> <br>
                                        <b>Regular and Onsite</b> <br>
                                        <li>Spesialist : Rp. 3.500.000</li>
                                        <li>Resident : Rp. 1.750.000</li>
                                        <li>GP : Rp. 1.750.000</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-middle-item-wrapper">
                            <div class="footer-middle-item mb-lg-0">
                                <div class="fm-item-title">
                                    <h5>Masuk / Registrasi</h5>
                                    <hr>
                                </div>
                                <div class="fm-item-content">
                                    <div class="d-grid">
                                        <a href="<?= base_url('site/login'); ?>" class="btn btn-primary mt-3"><i class="fa-solid fa-sign-in"></i> Masuk Login</a>
                                        <a href="<?= base_url('member/register'); ?>" class="btn btn-primary mt-2"><i class="fa-solid fa-clipboard-user"></i> Registrasi Individu</a>
                                        <a href="<?= base_url('member/register/group'); ?>" class="btn btn-primary mt-2"><i class="fa-solid fa-user-group"></i> Registrasi Grup / Kelompok</a>
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
                <div class="cs-copyright text-center wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">Copyright © 2022. All Rights Reserved by <span class="cs-primary_font cs-primary_color">AENFT</span></div>
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