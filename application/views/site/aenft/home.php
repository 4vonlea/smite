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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <style>
        .scrollbar {
            scrollbar-width: thin;
        }

        .scrollbar::-webkit-scrollbar {
            width: 5px;
            background-color: #F5F5F5;
        }

        .scrollbar::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
        }


        .scrollbar::-webkit-scrollbar-thumb {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #555;
        }

        .swiper {
            width: 100%;
            height: 400px;
        }

        .swiper-slide .title {
            position: absolute;
            font-weight: bold;
            top: 10px;
            left: 10px;
            font-size: 20px;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 3px;
            border-radius: 3px;
        }
    </style>
</head>

<body class="cs-dark">
    <!-- <div class="cs-preloader cs-white_bg cs-center">
        <div class="cs-preloader_in">
            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/logo.png" alt="Logo">
        </div>
    </div> -->

    <button class="btn btn-sm btn-primary position-fixed bottom-0 end-0 translate-middle me-1 d-none" onclick="scrollToTop()" id="back-to-up">
        <i class="fa fa-arrow-up fa-2x" aria-hidden="true"></i>
    </button>
    <button style="z-index:1" onclick="document.getElementById('section-contact').scrollIntoView({behavior: 'smooth'});" class="btn btn-sm btn-primary position-fixed bottom-0 end-0 mb-5 translate-middle">
        <i class="fa-2x fa-brands fa-whatsapp"></i>
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
                                <li><a href="#home" class="cs-smoth_scroll">
                                        <i class="fa fa-home me-1"></i>
                                        Home</a>
                                </li>
                                <li><a href="#news" class="cs-smoth_scroll">
                                        <i class="fa fa-newspaper me-1"></i>
                                        News</a></li>
                                <li><a href="#event" class="cs-smoth_scroll">
                                        <i class="fa fa-calendar me-1"></i>
                                        Event</a></li>
                                <li><a href="#registration" class="cs-smoth_scroll">
                                        <i class="fa fa-edit me-1"></i>
                                        Registration</a></li>
                                <li><a href="#information" class="cs-smoth_scroll">
                                        <i class="fa fa-info-circle me-1"></i>
                                        Important Info</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="cs-main_header_right">
                        <div class="cs-toolbox">
                            <a href="<?= base_url('member/register'); ?>" class="cs-btn cs-btn_filed cs-accent_btn">
                                <i class="fa-solid fa-clipboard-user"></i>&nbsp;<span><?= lang("registration"); ?></span>
                            </a>
                        </div>
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
    </header>
    <!-- End Header Section -->
    <div class="cs-height_80 cs-height_lg_80"></div>

    <!-- Start Hero -->
    <div id="home" class="cs-hero cs-style1 cs-type2 cs-bg text-center cs-ripple_version" data-src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg-head.jpg" id="home">
        <!--
        <nav class="floating-menu">
            <ul class="main-menu">
                <li>
                    <a href="#home" class="ripple cs-smoth_scroll">
                        <i class="fa fa-home fa-lg"></i>
                        <span><?= lang("home"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="#login" class="ripple cs-smoth_scroll">
                        <i class="fa fa-sign-in fa-lg"></i>
                        <span><?= lang("login"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="#event" class="ripple cs-smoth_scroll">
                        <i class="fa fa-calendar-alt fa-lg"></i>
                        <span><?= lang("event"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="#news" class="ripple cs-smoth_scroll">
                        <i class="fa fa-newspaper fa-lg"></i>
                        <span><?= lang("news"); ?></span>
                    </a>
                </li>
            </ul>
            <div class="menu-bg"></div>
        </nav>
		-->
        <div class="cs-dark_overlay"></div>
        <div class="container">
            <div class="cs-hero_img wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/logo.png" style="width: 100%; max-width: 320px; height: auto;">
            </div>
            <div class="cs-hero_text wow fadeIn" data-wow-duration="1s" data-wow-delay="0.45s" style="margin-top: -50px;">
                <h1 class="cs-hero_title text-uppercase cs-font_60 cs-font_36_sm cs-bold">KONAS XI PERDOSSI 2023 SEMARANG</h1>
                <!--<h3 class="cs-hero_subtitle cs-font_20 cs-font_16_sm cs-body_line_height">In Conjunction with</h3><br>
                <blockquote>
                    <h2 class="cs-hero_secondary_title cs-font_40 cs-font_24_sm">The 17th International Congress of <br> Asian Society Againts Dementia (ASAD)</h2>
                </blockquote>-->
                <h3 class="cs-hero_subtitle cs-font_22 cs-font_18_sm cs-body_line_height">2 - 6 Agustus 2023</h3>
                <div class="cs-btn_group">
                    <a href="https://drive.google.com/file/d/1-W3ZyKGKAtLteoR7JQrkgQam-RzYvgfJ/view?usp=share_link" class="btn btn-primary btn-round">
                        <i class="fa fa-info-circle"></i>
                        <span>Open Registration in January 2023, Download First Announcement (Update 19 January 2023)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero -->

    <section id="sambutan">
        <div class="cs-height_70 cs-height_lg_40"></div>
        <div class="container">
            <div class="cs-seciton_heading cs-style1 text-uppercase wow fadeInUp text-center" data-wow-duration="1s" data-wow-delay="0.2s">
                <h3 class="cs-section_title cs-font_16 cs-font_14_sm cs-gradient_color">Welcome Messages</h3>
                <h2 class="cs-section_subtitle cs-m0 cs-font_30 cs-font_20_sm">National Congress of the Indonesian Neurological Association 2023 SEMARANG</h2>
            </div>
            <div class="cs-height_50 cs-height_lg_30"></div>
            <div class="row wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s">
                <div class="col-lg-6 col-sm-6">
                    <table border="0" cellpadding="4" cellspacing="4" width="100%">
                        <tr>
                            <td width="30%" valign="top" style="border-top: 0px;"><img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sambutan1.png"></td>
                            <td style="border-top: 0px;">
                                <h4>Welcome Messages</h4>
                                <b>Chairman of Organizing Committee</b> <br>
                                Assalamualaikum wr.wb. <br> Dear Colleagues,
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-top: 0px;">
                                <p align="justify">
                                    It is a great honor for us, Perdossi Semarang, to hold the 11th National Congress of the Indonesian Neurological Association in our beloved city. This National Congress is not only scientific event but also congress of Neurologists throughout Indonesia which is planned to be held on 2 - 6 August 2023

                                    <br><br>

                                    The 11th National Congress of the Indonesian Neurological Association with the theme "NeuroEngineering Update to Reach Outstanding Neurological Service (NEURON)", will involve a number of experts from both Indonesia and International who are competent in their fields to convey the latest developments and discoveries in neurology. We believe this event will provide a lot of additional knowledge and improve the skills that are useful for all of us. Apart from scientific meetings, this event will also hold organizational meetings and nonscientific activities. We hope that all colleagues can participate in Neurobic exercise with the general public as our community service activity and we have prepared Ladies Program for colleagues and families who are interested. All committees invite colleagues and sponsors to participate in The 11th National Congress of the Indonesian Neurological Association in Semarang.
                                    <br><br>

                                    We look forward to your presence and participation in Semarang, The venetie van Java!
                                    <br><br>

                                    Wassalamu’alaikum wr.wb.

                                    <br><br>

                                    Dr. dr. Retnaningsih, Sp. N, Subsp. NIITCC (K), KIC, M.KM <br>
                                    Chairman of Organizing Committee
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <table border="0" cellpadding="4" cellspacing="4" width="100%">
                        <tr>
                            <td style="border-top: 0px;">
                                <h4>Welcome Messages</h4>
                                <b>President of Indonesian Neurological Association</b> <br>
                                Assalamualaikum wr.wb. <br> Dear Professors, Doctors, Seniors and colleagues.
                            </td>
                            <td width="30%" valign="top" style="border-top: 0px;"><img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sambutan2.png"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-top: 0px;">
                                <p align="justify">
                                    Praise and gratitude we pray for the presence of Allah SWT and for the abundance of His grace and gifts to all of us in carrying out our professional duties and working well.
                                    <br><br>

                                    As representatives of the President of the Indonesian Neurological Association (PERDOSSI), we welcome you in The XI National Congress of the Indonesian Neurological Association which will be held on 2 - 6 August 2023, in Semarang.
                                    <br><br>

                                    The theme "NeuroEngineering Update to Reach Outstanding Neurological service (NEURON)" is expected to accommodate neurologists to continue to update their knowledge and skills based on the latest research and guidelines through symposium and workshops. It will certainly be very beneficial for daily practice and improve the quality of service and competitiveness of Indonesian neurologists.
                                    <br><br>

                                    Through the forum of organizational meetings and sessions, it will further strengthen the bond and organization for the development of Indonesian Neurology in the future. This KONAS XI PERDOSSI 2023 activity will also be an important milestone in changing the name of the new association.
                                    <br><br>

                                    To all organizing committees and colleagues who support KONAS XI PERDOSSI 2023, we express our deepest gratitude.
                                    <br><br>

                                    Let's make KONAS XI PERDOSSI 2023 a success in Semarang!. Wassalamu’alaikum wr.wb.

                                    <br><br>

                                    Dr. dr. Dodik Tugasworo, Sp. N, Subsp. NIIOO (K), M.H <br>
                                    President of Indonesian Neurological Association

                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="cs-height_50 cs-height_lg_20"></div>

    </section>

    <section id="gallery">
        <div class="cs-height_70 cs-height_lg_40"></div>
        <div class="container">
            <div class="cs-seciton_heading cs-style1 text-uppercase wow fadeInUp text-center" data-wow-duration="1s" data-wow-delay="0.2s">
                <h3 class="cs-section_title cs-font_16 cs-font_14_sm cs-gradient_color">News</h3>
                <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm">Recent Update</h2>
            </div>
            <div class="cs-height_50 cs-height_lg_30"></div>
            <div class="row wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s">
                <div class="col-lg-6 col-sm-6">
                    <div id="swiper-photo" class="swiper">
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">
                            <!-- Slides -->
                            <?php if (count($videoAndPhoto['photo']) == 0) : ?>
                                <div class="swiper-slide">
                                    <div class="title" data-swiper-parallax="-300">Photo Update</div>
                                    <img src="<?= base_url('themes/img/coming-soon.jpg'); ?>" />
                                </div>
                                <?php else : foreach ($videoAndPhoto['photo'] as $photo) : ?>
                                    <div class="swiper-slide">
                                        <div class="title" data-swiper-parallax="-300"><?= $photo['title']; ?></div>
                                        <img src="<?= base_url('themes/uploads/video') . "/" . $photo['filename']; ?>" />
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>

                        <!-- If we need navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>

                        <!-- If we need scrollbar -->
                        <div class="swiper-scrollbar"></div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div id="swiper-video" class="swiper">
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">
                            <!-- Slides -->
                            <?php if (count($videoAndPhoto['video']) == 0) : ?>
                                <div class="swiper-slide">
                                    <div class="title" data-swiper-parallax="-300">Video Update</div>
                                    <img src="<?= base_url('themes/img/coming-soon.jpg'); ?>" />
                                </div>
                                <?php else : foreach ($videoAndPhoto['video'] as $video) : ?>
                                    <div class="swiper-slide">
                                        <div class="title" data-swiper-parallax="-300"><?= $video['title']; ?></div>
                                        <video src="<?= base_url('themes/uploads/video') . "/" . $video['filename']; ?>" preload="none"></video>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>

                        <!-- If we need navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>

                        <!-- If we need scrollbar -->
                        <div class="swiper-scrollbar"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cs-height_50 cs-height_lg_20"></div>

    </section>

    <div class="cs-height_50 cs-height_lg_20"></div>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                    <div class="cs-iconbox cs-style1 cs-white_bg text-center">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 mb-4">
                                <h3 class="cs-iconbox_title cs-font_28 cs-font_22_sm cs-m0">Abstract Countdown</h3>
                                <div class="cs-height_10 cs-height_lg_10"></div>
                                <div class="cs-iconbox_subtitle">(<?= date_format($papercountdown, "F d, Y"); ?>)</div>
                                <div class="cs-height_10 cs-height_lg_10"></div>
                                <div class="de_countdown h4 cs-iconbox_icon cs-font_26 cs-font_20_sm cs-m0" data-year="<?= date_format($papercountdown, "Y"); ?>" data-month="<?= date_format($papercountdown, "m"); ?>" data-day="<?= date_format($papercountdown, "d"); ?>" data-hour="<?= date_format($papercountdown, "H"); ?>"></div>
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-4">
                                <h3 class="cs-iconbox_title cs-font_28 cs-font_22_sm cs-m0">Event Countdown</h3>
                                <div class="cs-height_10 cs-height_lg_10"></div>
                                <div class="cs-iconbox_subtitle">(<?= date_format($eventcountdown, "F d, Y"); ?>)</div>
                                <div class="cs-height_10 cs-height_lg_10"></div>
                                <div class="de_countdown h4 cs-iconbox_icon cs-font_26 cs-font_20_sm cs-m0" data-year="<?= date_format($eventcountdown, "Y"); ?>" data-month="<?= date_format($eventcountdown, "m"); ?>" data-day="<?= date_format($eventcountdown, "d"); ?>" data-hour="<?= date_format($eventcountdown, "H"); ?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="cs-height_25 cs-height_lg_25"></div>
                </div>
            </div>
        </div>
    </section>

    <div class="cs-height_50 cs-height_lg_20"></div>

    <section id="event">
        <div class="container">
            <div class="cs-seciton_heading cs-style1 text-uppercase text-center wow fadeInUp mb-2" data-wow-duration="1s" data-wow-delay="0.2s">
                <!-- <h3 class="cs-section_title cs-font_16 cs-font_14_sm cs-gradient_color">Video</h3> -->
                <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm">Event</h2>
            </div>
            <div class="wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                <?php foreach ($eventsList as $groupEvent) : ?>
                    <button class="accordion mt-2"><?= $groupEvent['heldOn']; ?> (<?= $groupEvent['kategory']; ?>)</button>
                    <div class="accordion-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered my-3" style="color: white; width: 100%;">
                                <thead bgcolor="#0052FF">
                                    <tr>
                                        <th>Event</th>
                                        <th>Tempat</th>
                                        <th>Kuota</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($groupEvent['list'] as $event) : ?>
                                        <tr>
                                            <td>
                                                <?= $event['name']; ?>
                                                <br>
                                                <small class="fw-bold"><?= $event['description']; ?></small>
                                            </td>
                                            <td><?= $event['held_in']; ?></td>
                                            <td align="center"><span class="badge card-header-bg2 fw-bold"><?= $event['kouta']; ?> Orang</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cs-height_50 cs-height_lg_25"></div>
        </div>
    </section>

    <div class="cs-height_75 cs-height_lg_45"></div>

    <section id="registration" class="cs-bg p-5" data-src="<?= base_url('themes/aenft'); ?>/assets/img/konas/img4.jpg">
        <div class="container wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
            <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm text-uppercase text-center mb-5">Masuk / Registrasi</h2>
            <div class="row">
                <div class="col-lg-6 text-center mb-4">
                    <h4>Masuk Login</h4>
                    <form name="contactForm" id="contact_form" class="form-border" method="post" action="<?= base_url('site/login'); ?>">
                        <?php if (!$hasSession) : ?>
                            <div class="field-set mb-2">
                                <input type="text" name="username" id="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="field-set mb-2">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            </div>
                            <a href="<?= base_url('site/forget'); ?>" class="mb-2">Lupa Password ?</a>
                        <?php endif; ?>

                        <div class="d-grid">
                            <button type="submit" name="login" value="login" class="btn btn-round btn-primary mt-2">
                                <span> <?= $hasSession ? "Back To Member Area" : "<i class='fa-solid fa-sign-in'></i> Sign in / Masuk"; ?></span>
                            </button>
                            <br>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <h4 class="text-center">Cara Pendaftaran</h4>
                    <p>
                    <ol>
                        <li>Klik <b>Registrasi dibawah</b>, dan isi profil anda</li>
                        <li>Pilih kegiatan yang ini diikuti</li>
                        <li>Lakukan pembayaran secara online</li>
                        <li>Bukti Pembayaran dan Registrasi akan dikirimkan via email. Mohon dibawa saat registrasi ulang</li>
                    </ol>
                    </p>
                    <p class="mt-minus2"><i class="fa-solid fa-envelope circle-icon"></i>1 Email per Account</p>
                    <p class="mt-minus2"><i class="fa-solid fa-id-card circle-icon"></i>Sertifikat akan dikirimkan melalui Email</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-6 col-md-6 mb-2">
                    <div class="d-grid text-center">
                        <a href="<?= base_url('member/register'); ?>" class="cs-btn cs-btn_filed btn-col"><span> <i class="fa-solid fa-clipboard-user"></i> Registrasi Individu</span> </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="d-grid text-center">
                        <a href="<?= base_url('member/register/group'); ?>" class="cs-btn cs-btn_filed btn-col"><span> <i class="fa-solid fa-user-group"></i> Registrasi Grup / Kelompok</span> </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="cs-height_70 cs-height_lg_40"></div>
        <div class="container">
            <div class="cs-seciton_heading cs-style1 text-uppercase text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
                <h3 class="cs-section_title cs-font_16 cs-font_14_sm cs-gradient_color">Video</h3>
                <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm">Tutorial</h2>
            </div>
            <div class="wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                <button class="accordion mt-4">Video Registrasi menggunakan pembayaran online</button>
                <div class="accordion-content">
                    <embed src="https://drive.google.com/file/d/1AAbZZ9lENoCZlWRzrPogUTe4Tb_9VyKg/preview" width="100%" height="600px" align="center" allow="autoplay" class="mt-3 mb-3"></embed>
                </div>
                <button class="accordion mt-2">Video Registrasi secara berkelompok</button>
                <div class="accordion-content">
                    <embed src="https://drive.google.com/file/d/19zy8NB9nZo5Q7ctjQ_bQb0WwZkVxYqeq/preview" width="100%" height="600px" align="center" allow="autoplay" class="mt-3 mb-3"></embed>
                </div>
                <!--<button class="accordion mt-2">Video Tutorial melakukan booking hotel</button>
                <div class="accordion-content">
                    <embed src="#" width="100%" height="600px" align="center" allow="autoplay" class="mt-3 mb-3"></embed>
                </div>-->
                <button class="accordion mt-2">Video Tutorial Submisi Abstrak</button>
                <div class="accordion-content">
                    <embed src="https://drive.google.com/file/d/1A1ptqak2pL9YLsK9GdHsQ5Kp5YUWlyQN/preview" width="100%" height="600px" align="center" allow="autoplay" class="mt-3 mb-3"></embed>
                </div>
            </div>
            <div class="cs-height_50 cs-height_lg_30"></div>
        </div>
    </section>

    <div class="cs-height_70 cs-height_lg_40"></div>
    <section id="information" class="event cs-bg" data-src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg-head1.jpg">
        <div class="cs-height_70 cs-height_lg_40"></div>
        <div class="container">
            <div class="cs-seciton_heading cs-style1 text-uppercase text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
                <h3 class="cs-section_title cs-font_16 cs-font_14_sm cs-gradient_color">Informasi</h3>
                <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm">Kegiatan</h2>
            </div>

            <div class="row mt-4 mb-2">
                <div class="event-filter wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                    <span class="filter-item active" data-filter="tanggal">Tanggal Penting</span>
                    <span class="filter-item" data-filter="faq">faq</span>
                    <span class="filter-item" data-filter="jadwal">Jadwal Kegiatan</span>
                    <span class="filter-item" data-filter="pedoman">Pedoman Ilmiah</span>
                    <span class="filter-item" data-filter="panitia">Kepanitiaan</span>
                    <span class="filter-item" data-filter="explore">Explore Semarang</span>
                    <span class="filter-item" data-filter="ladpro">Ladies Program</span>
                    <span class="filter-item" data-filter="neuro">Neurotech Competition</span>
                    <span class="filter-item" data-filter="sertifikat">Sertifikat</span>
                </div>
            </div>
            <div class="event-item tanggal">
                <div class="wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                    <div class="cs-iconbox cs-style1 cs-white_bg">
                        <h4>Tanggal Penting</h4>
                        <hr class="mb-4">
                        <ul>
                            <li>Early Registration : Januari - 30 April 2023</li>
                            <li>Pengumpulan Abstrak : April - Mei 2023</li>
                            <li>Pengumuman Penerimaan Abstrak : 20 Juli 2023</li>
                            <li>Late Registration : 1 Mei - 1 Agustus 2023</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="event-item faq hide">
                <div class="cs-iconbox cs-style1 cs-white_bg">
                    <h4>FAQ</h4>
                    <hr class="mb-4">
                    <ul>
                        <li>
                            <i class="fa-solid fa-info-circle"></i> Apakah kegiatan ini dilakukan secara daring atau luring?<br>
                            - Kegiatan ini dilakukan secara luring di Padma Hotel, Semarang
                        </li>
                        <li>
                            <i class="fa-solid fa-info-circle"></i> Bagaimana caranya login?<br>
                            - Login menggunakan email user yang telah terdaftar di web https://kongresnasionalperdosni2023.com dengan password masing-masing.
                        </li>
                        <li>
                            <i class="fa-solid fa-info-circle"></i> Bagaimana jika saya lupa password?<br>
                            - Jika Anda lupa kata sandi Anda, silakan klik bagian Lupa Kata Sandi pada halaman login, dan masukkan email Anda yang terdaftar, lalu klik Atur Ulang. Password baru akan dikirimkan ke email yang dimasukkan saat proses reset.
                        </li>
                        <li>
                            <i class="fa-solid fa-info-circle"></i> Saya tidak mengetahui username saya?<br>
                            - Silakan lakukan pencarian di inbox email dengan kata kunci: KONGRES PERDOSNI 2023 untuk mengetahui notifikasi username anda saat selesai mendaftar
                        </li>
                        <li>
                            <i class="fa-solid fa-info-circle"></i> Saya didaftarkan oleh pihak sponsor, bagaimana caranya login?<br>
                            - Silakan hubungi sponsor anda langsung untuk mendapatkan informasi akun.
                        </li>
                        <li>
                            <i class="fa-solid fa-info-circle"></i> Saya bukan peserta acara ini, tapi saya pembicara/moderator/juri di acara ini, bagaimana cara login?<br>
                            - Untuk pembicara, moderator, dan juri, kami telah mengirimkan akses akun yang dibuat secara otomatis ke email dokter Anda. Silakan periksa kotak masuk/spam Anda dan coba akses situs web kami dengan akun tersebut.
                        </li>
                    </ul>
                </div>
            </div>
            <div class="event-item jadwal hide">
                <div class="cs-iconbox cs-style1 cs-white_bg">
                    <h4>Jadwal Acara</h4>
                    <hr class="mb-4">
                    <a href="<?= base_url('themes/aenft'); ?>/assets/pdf/jadwal.pdf" target="_blank" class="btn btn-info btn-round"><i class="fa-solid fa-calendar-check"></i> Jadwal Acara</a>
                </div>
            </div>
            <div class="event-item pedoman hide">
                <div class="cs-iconbox cs-style1 cs-white_bg">
                    <h4>Pedoman Ilmiah</h4>
                    <hr class="mb-4">
                    <a href="https://drive.google.com/file/d/1uszw547D0P1CRhoHMOFEFvk4-hzQEyc8/view?usp=share_link" target="_blank" class="btn btn-success btn-round"><i class="fa-solid fa-chart-gantt"></i> Ketentuan Oral dan E-poster (23 Feb 2023)</a>
                </div>
            </div>
            <div class="event-item panitia hide">
                <div class="cs-iconbox cs-style1 cs-white_bg">
                    <h4>Kepanitiaan KONSI XI PERDOSSI 2023 SEMARANG</h4>
                    <hr class="mb-4">
                    <embed src="<?= base_url('themes/aenft'); ?>/assets/pdf/panitia.pdf" width="100%" height="600px" align="center"></embed>
                </div>
            </div>
            <div class="event-item explore hide">
                <div class="cs-iconbox cs-style1 cs-white_bg">
                    <h4>EXPLORING SEMARANG</h4>
                    <hr class="mb-4">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary" target="_blank"><i class="fa-solid fa-download"></i> Download File</a>
                    </div>
                    <button class="accordion mt-4">Wisata Kuliner</button>
                    <div class="accordion-content">
                        <embed src="<?= base_url('themes/aenft'); ?>/assets/images/custom/s&k.jpeg" width="100%" align="center" allow="autoplay" class="mt-3 mb-3"></embed>
                    </div>
                    <button class="accordion mt-4">Wisata Tempat</button>
                    <div class="accordion-content">
                        <embed src="<?= base_url('themes/aenft'); ?>/assets/images/custom/s&k.jpeg" width="100%" align="center" allow="autoplay" class="mt-3 mb-3"></embed>
                    </div>
                    <button class="accordion mt-4">Wisata Darat dan Travel</button>
                    <div class="accordion-content">
                        <embed src="<?= base_url('themes/aenft'); ?>/assets/images/custom/s&k.jpeg" width="100%" align="center" allow="autoplay" class="mt-3 mb-3"></embed>
                    </div>
                </div>
            </div>

            <div class="event-item ladpro hide">
                <div class="cs-iconbox cs-style1 cs-white_bg">
                    <h4>Ladies Program KONAS PERDOSSI</h4>
                    <hr class="mb-4">
                    <i class="fa-solid fa-info-circle"></i> Waktu Kegiatan : <b>Sabtu, 5 Agustus 2023</b><br>
                    <div class="my-2"></div>

                    <i class="fa-solid fa-info-circle"></i> <b>Term and Condition :</b>
                    <ol class="mb-2 mt-1" style="padding-left: 40px;">
                        <li>Peserta Ladies Program <b>mendaftar</b> melalui akun peserta KONAS di website <b>konasperdossi2023.com</b> (mencantumkan nama peserta dan kontak yang dapat dihubungi)</li>
                        <li>Peserta Ladies Program <b>maksimal 2 orang</b> terdaftar dari setiap akun peserta KONAS</li>
                        <li>Ladies Program akan diselenggarakan jika memenuhi <b>kuota minimal 50 orang</b></li>
                    </ol>
                    <i class="fa-solid fa-info-circle"></i> <b>Lokasi Wisata :</b><br>
                    <div id="ladproSlide" class="carousel slide" data-bs-ride="carousel">

                        <!-- The slideshow/carousel -->
                        <div class="carousel-inner text-center">
                            <div class="carousel-item active">
                                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/ladpro.png" class="mt-3" width="50%"></img>
                            </div>
                        </div>

                        <!-- Left and right controls/icons -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#ladproSlide" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#ladproSlide" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="event-item neuro hide">
                <div class="cs-iconbox cs-style1 cs-white_bg">
                    <h4>Neurotech Competition</h4>
                    <hr class="mb-4">
                    <div id="neuroSlide" class="carousel slide" data-bs-ride="carousel">

                        <!-- The slideshow/carousel -->
                        <div class="carousel-inner text-center">
                            <div class="carousel-item active">
                                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/neuro_competition.png" class="mt-3" width="50%"></img>
                            </div>
                        </div>
                        <!-- Left and right controls/icons -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#neuroSlide" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#neuroSlide" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="event-item sertifikat hide">
                <div class="cs-iconbox cs-style1 cs-white_bg">
                    <h4>Sertifikat</h4>
                    <hr class="mb-4">
                    <a href="https://konasperdossi2023.com/certificate/claim" target="_blank" class="btn btn-success btn-round"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-certificate" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5" />
                            <circle cx="6" cy="14" r="3" />
                            <path d="M4.5 17l-1.5 5l3 -1.5l3 1.5l-1.5 -5" />
                        </svg> Klaim Sertifikat</a>
                </div>
            </div>

            <div class="cs-height_50 cs-height_lg_30"></div>
        </div>
    </section>

    <section id="section-contact">
        <div class="cs-height_70 cs-height_lg_40"></div>
        <div class="container">
            <div class="cs-seciton_heading cs-style1 text-uppercase text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
                <h3 class="cs-section_title cs-font_16 cs-font_14_sm cs-gradient_color">Info</h3>
                <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm">Contact Person</h2>
            </div>
            <div class="row g-5 justify-content-center row-cols-md-4 row-cols-sm-4 row-cols-1 mt-2 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                <div class="card-cp">
                    <div class="name-tag text-center">
                        <img src="<?= base_url('themes/aenft'); ?>/assets/img/cs/cs1.png" alt="">
                        <h5>Registrasi</h5>
                    </div>
                    <div class="overlay text-center">
                        <h5 class="mb-2">Registrasi</h5><br>
                        <a href="http://wa.me/6282140805759" target="_BLANK" class="btn btn-sm btn-primary mt-1"><i class="fa-brands fa-whatsapp"></i> dr. Yuna</a><br>
                    </div>
                </div>
                <div class="card-cp">
                    <div class="name-tag text-center">
                        <img src="<?= base_url('themes/aenft'); ?>/assets/img/cs/cs2.png" alt="">
                        <h5>Neurotech</h5>
                    </div>
                    <div class="overlay text-center">
                        <h5 class="mb-2">Neurotech</h5><br>
                        <a href="http://wa.me/6281216551865" target="_BLANK" class="btn btn-sm btn-primary mt-1"><i class="fa-brands fa-whatsapp"></i> dr. Ageng</a><br>
                    </div>
                </div>
                <div class="card-cp">
                    <div class="name-tag text-center">
                        <img src="<?= base_url('themes/aenft'); ?>/assets/img/cs/cs3.png" alt="">
                        <h5>Poster</h5>
                    </div>
                    <div class="overlay text-center">
                        <h5 class="mb-2">Poster</h5><br>
                        <a href="http://wa.me/6281321532753" target="_BLANK" class="btn btn-sm btn-primary mt-1"><i class="fa-brands fa-whatsapp"></i> dr. Nabil (Poster)</a><br>
                    </div>
                </div>
                <div class="card-cp">
                    <div class="name-tag text-center">
                        <img src="<?= base_url('themes/aenft'); ?>/assets/img/cs/cs4.png" alt="">
                        <h5>Sekretariat</h5>
                    </div>
                    <div class="overlay text-center">
                        <h5 class="mb-2">Sekretariat</h5><br>
                        <a href="#" target="_BLANK" class="btn btn-sm btn-primary mt-1"><i class="fa-brands fa-whatsapp"></i> dr. Rahmi</a><br>
                    </div>
                </div>
            </div>
            <div class="cs-height_50 cs-height_lg_30"></div>
        </div>
    </section>

    <!--<section id="news" class="cs-bg" data-src="<?= base_url('themes/aenft'); ?>/assets/img/konas/img6.jpg">
        <div class="cs-height_70 cs-height_lg_40"></div>
        <div class="container">
            <div class="cs-seciton_heading cs-style1 text-uppercase text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
                <h3 class="cs-section_title cs-font_16 cs-font_14_sm cs-gradient_color">INFO</h3>
                <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm">Berita Terbaru </h2>
            </div>
            <?php if (count($allNews) > 0) : ?>
                <div class="row col-12">
                    <a href="<?= base_url('site/all_news'); ?>" class="text-end h5">Lihat Semua</a>
                    <div style="overflow-x: auto;" class="row scrollbar flex-row flex-nowrap row-cols-md-3 row-cols-sm-3 row-cols-1 mt-2 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                        <?php foreach ($allNews as $news) : ?>
                            <a href="<?= base_url('site/readnews/' . $news->id); ?>" class="card card-bg card__shadow mb-2 me-2" style="width: 18rem;">
                                <img class="card-img-top" src="<?= $news->imageCover(); ?>" width="250px" height="250px" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $news->title; ?></h5>
                                    <p class="card-text"><?= news_date($news->created_at); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="row col-12">
                    <div class="alert alert-info text-center mt-2">Mohon Maaf, Belum Ada Berita Yang Tersedia</div>
                </div>
            <?php endif; ?>
            <div class="cs-height_50 cs-height_lg_30"></div>
        </div>
    </section>-->

    <section>
        <div class="cs-height_70 cs-height_lg_45"></div>
        <div class="container">
            <div class="cs-seciton_heading cs-style1 text-uppercase wow fadeInUp text-center" data-wow-duration="1s" data-wow-delay="0.2s">
                <!-- <h3 class="cs-section_title cs-font_16 cs-font_14_sm cs-gradient_color">Daftar Hotel</h3> -->
                <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm">Daftar Hotel </h2>
            </div>
            <div class="cs-height_25 cs-height_lg_25"></div>
            <div class="row wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s">
                <table class="table table-striped align-middle text-white">
                    <tr>
                        <td>
                            <span class="text-white fw-bold">
                                Padma Hotel Semarang <br>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                            </span>
                        </td>
                        <td>
                            <span class="text-white">
                                Jl. Sultan Agung No. 86, Kota Semarang, Jawa Tengah
                            </span>
                        </td>
                        <td align="center">
                            <div class="cs-member_thumb">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#venue">
                                    <i class="i-col fa-solid fa-building-circle-check wow pulse" data-wow-iteration="infinite" data-wow-duration="1500ms"></i> Lokasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-white fw-bold">
                                Grand Candi <br>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                            </span>
                        </td>
                        <td>
                            <span class="text-white">
                                Jarak ke Venue : <span class="fw-bold">1,4 km</span><br>
                                <span class="fw-bold">± 4 menit</span> berkendara
                            </span>
                        </td>
                        <td align="center">
                            <div class="cs-member_thumb">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hotel1">
                                    <i class="i-col fa-solid fa-building-circle-check wow pulse" data-wow-iteration="infinite" data-wow-duration="1500ms"></i> Lokasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-white fw-bold">
                                Patra <br>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                            </span>
                        </td>
                        <td>
                            <span class="text-white">
                                Jarak ke Venue : <span class="fw-bold">1,5 km</span><br>
                                <span class="fw-bold">± 5 menit</span> berkendara
                            </span>
                        </td>
                        <td align="center">
                            <div class="cs-member_thumb">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hotel2">
                                    <i class="i-col fa-solid fa-building-circle-check wow pulse" data-wow-iteration="infinite" data-wow-duration="1500ms"></i> Lokasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-white fw-bold">
                                Arrus <br>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                            </span>
                        </td>
                        <td>
                            <span class="text-white">
                                Jarak ke Venue : <span class="fw-bold">1,9 km</span><br>
                                <span class="fw-bold">± 4 menit</span> berkendara
                            </span>
                        </td>
                        <td align="center">
                            <div class="cs-member_thumb">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hotel3">
                                    <i class="i-col fa-solid fa-building-circle-check wow pulse" data-wow-iteration="infinite" data-wow-duration="1500ms"></i> Lokasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-white fw-bold">
                                Louis Kienne <br>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                            </span>
                        </td>
                        <td>
                            <span class="text-white">
                                Jarak ke Venue : <span class="fw-bold">3,4 km</span><br>
                                <span class="fw-bold">± 9 menit</span> berkendara
                            </span>
                        </td>
                        <td align="center">
                            <div class="cs-member_thumb">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hotel4">
                                    <i class="i-col fa-solid fa-building-circle-check wow pulse" data-wow-iteration="infinite" data-wow-duration="1500ms"></i> Lokasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-white fw-bold">
                                Novotel <br>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                            </span>
                        </td>
                        <td>
                            <span class="text-white">
                                Jarak ke Venue : <span class="fw-bold">4,9 km</span><br>
                                <span class="fw-bold"> 9 menit</span> berkendara
                            </span>
                        </td>
                        <td align="center">
                            <div class="cs-member_thumb">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hotel5">
                                    <i class="i-col fa-solid fa-building-circle-check wow pulse" data-wow-iteration="infinite" data-wow-duration="1500ms"></i> Lokasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-white fw-bold">
                                Gumaya <br>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                                <i class="fa-solid fa-star i-col"></i>
                            </span>
                        </td>
                        <td>
                            <span class="text-white">
                                Jarak ke Venue : <span class="fw-bold">5,6 km</span><br>
                                <span class="fw-bold">± 10 menit</span> berkendara
                            </span>
                        </td>
                        <td align="center">
                            <div class="cs-member_thumb">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hotel6">
                                    <i class="i-col fa-solid fa-building-circle-check wow pulse" data-wow-iteration="infinite" data-wow-duration="1500ms"></i> Lokasi
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </section>

    <!--<section>
        <div class="cs-height_70 cs-height_lg_40"></div>
        <div class="container">
            <div class="cs-seciton_heading cs-style1 text-uppercase wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
                <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm text-center">Sponsor</h2>
            </div>
            <div class="wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                <div class="cs-seciton_heading cs-style1 text-uppercase mt-3">
                    <h3 class="cs-section_title cs-font_18 cs-font_16_sm cs-gradient_color">Sponsor</h3>
                </div>
                <div class="row g-5 justify-content-center row-cols-md-3 row-cols-sm-3 row-cols-1">
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                </div>
                <div class="cs-seciton_heading cs-style1 text-uppercase mt-5">
                    <h3 class="cs-section_title cs-font_18 cs-font_16_sm cs-gradient_color">Gold</h3>
                </div>
                <div class="row g-5 justify-content-center row-cols-md-4 row-cols-sm-4 row-cols-1">
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                </div>
                <div class="cs-seciton_heading cs-style1 text-uppercase mt-5">
                    <h3 class="cs-section_title cs-font_18 cs-font_16_sm cs-gradient_color">Silver</h3>
                </div>
                <div class="row g-5 justify-content-center row-cols-md-5 row-cols-sm-5 row-cols-2">
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                </div>
                <div class="cs-seciton_heading cs-style1 text-uppercase mt-5">
                    <h3 class="cs-section_title cs-font_18 cs-font_16_sm cs-gradient_color">Bronze</h3>
                </div>
                <div class="row g-5 justify-content-center row-cols-md-6 row-cols-sm-6 row-cols-2">
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center sponsor sponsor_shadow">
                            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/sponsor.png" alt="sponsor-thumb">
                        </div>
                    </div>
                </div>
            </div>
            <div class="cs-height_50 cs-height_lg_30"></div>
        </div>
    </section>-->

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
                                        <li>Early Registration : Januari - 30 April 2023</li>
                                        <li>Pengumpulan Abstrak : April - Mei 2023</li>
                                        <li>Pengumuman Penerimaan Abstrak : 20 Juli 2023</li>
                                        <li>Late Registration : 1 Mei - 1 Agustus 2023</li>
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
                                        <li>Neurovascular</li>
                                        <li>Neurorestorasi</li>
                                        <li>Neurotrauma</li>
                                        <li>Neuropediatri</li>
                                        <li>Neuroonkologi</li>
                                        <li>Sleep disorder</li>
                                        <li>Neuroinfeksi</li>
                                        <li>Nyeri Kepala</li>
                                        <li>Neurobehavior</li>
                                        <li>Neurootologi-Neurooftamologi</li>
                                        <li>Neurointervensi</li>
                                        <li>Nyeri</li>
                                        <li>Neurointensive</li>
                                        <li>Gangguan Gerak</li>
                                        <li>Neurogeriatri</li>
                                        <li>Epilepsi</li>
                                        <li>Neuroimaging</li>
                                        <li>Neuroepidemiologi</li>
                                        <li>Neurofisiologi</li>
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
                                        <li>Spesialist : Rp. 3.500.000</li>
                                        <li>Dokter Umum/PPDS : Rp. 2.000.000</li>
                                        <b>Late</b> <br>
                                        <li>Spesialist : Rp. 4.000.000</li>
                                        <li>Dokter Umum/PPDS : Rp. 2.250.000</li>
                                        <b>Onsite</b> <br>
                                        <li>Spesialist : Rp. 4.500.000</li>
                                        <li>Dokter Umum/PPDS : Rp. 2.500.000</li>
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
                <div class="cs-copyright text-center wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
                    <p>&copy; 2023 License to #Panitia KONAS PERDOSNI Semarang#</p>
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

    <div class="modal fade text-black" id="venue" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="hotel1Label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="hotel1Label">
                        Padma Hotel Semarang |
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15840.005173334745!2d110.4165291!3d-7.0091294!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x695373881ac0dbf0!2sPadma%20Hotel%20Semarang!5e0!3m2!1sen!2sid!4v1674867433995!5m2!1sen!2sid" width="100%" height="450px" style="border:0;" allowfullscreen="yes" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-black" id="hotel1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="hotel1Label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="hotel1Label">
                        Grand Candi |
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.9399793095554!2d110.42050421455953!3d-7.0163412949318245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b5844fd9841%3A0x9af9a992c52c211a!2sGrand%20Candi%20Hotel!5e0!3m2!1sen!2sid!4v1673595569981!5m2!1sen!2sid" width="100%" height="450px" style="border:0;" allowfullscreen="yes" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-black" id="hotel2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="hotel1Label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="hotel1Label">
                        Patra |
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.9657193207986!2d110.41827861455951!3d-7.0133145949339575!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b7eb54f1bff%3A0xcfe42ae1e8fde612!2sPatra%20Semarang%20Hotel%20%26%20Convention!5e0!3m2!1sen!2sid!4v1673596710568!5m2!1sen!2sid" width="100%" height="450px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-black" id="hotel3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="hotel1Label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="hotel1Label">
                        Arrus |
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.8867429900956!2d110.41986021455946!3d-7.022597094927406!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708ba80e8ead0d%3A0xcd2d78df546af4a8!2sHotel%20Aruss%20Semarang!5e0!3m2!1sen!2sid!4v1673597084013!5m2!1sen!2sid" width="100%" height="450px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-black" id="hotel4" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="hotel1Label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="hotel1Label">
                        Louis Kienne |
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.204505299054!2d110.4077006217121!3d-6.985174178813799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b4f8e9149b7%3A0xfbac2ca871304049!2sLK%20Hotel%20Pandanaran%20Semarang!5e0!3m2!1sen!2sid!4v1673597158604!5m2!1sen!2sid" width="100%" height="450px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-black" id="hotel5" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="hotel1Label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="hotel1Label">
                        Novotel |
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.259346691499!2d110.41228481455923!3d-6.978695294958423!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b52741bef77%3A0xcb5219107dbe142a!2sNovotel%20Semarang!5e0!3m2!1sen!2sid!4v1673597193143!5m2!1sen!2sid" width="100%" height="450px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-black" id="hotel6" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="hotel1Label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="hotel1Label">
                        Gumaya |
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                        <i class="fa-solid fa-star fa-1x"></i>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.2676274740884!2d110.41796081455932!3d-6.977716494959166!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b54831ad7bf%3A0x1e7ba1dedde1dd1c!2sGumaya%20Tower%20Hotel!5e0!3m2!1sen!2sid!4v1673597258763!5m2!1sen!2sid" width="100%" height="450px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/jquery.slick.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/jquery.counter.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/wow.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/ripples.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/main.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/custom.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/bootstrap.bundle.min.js"></script>

    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.plugin.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.countTo.js"></script>
    <script src="<?= base_url('themes/gigaland'); ?>/js/jquery.countdown.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        $(function() {
            $('.de_countdown').each(function() {
                var y = $(this).data('year');
                var m = $(this).data('month');
                var d = $(this).data('day');
                var h = $(this).data('hour');
                $(this).countdown({
                    until: new Date(y, m - 1, d, h)
                });
            });
            const swiperPhoto = new Swiper('#swiper-photo', {
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                scrollbar: {
                    el: '.swiper-scrollbar',
                },
            });
            var swiperVideo = new Swiper('#swiper-video', {
                direction: 'horizontal',
                loop: false,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                on: {
                    transitionStart: function() {
                        var videos = document.querySelectorAll('video');
                        Array.prototype.forEach.call(videos, function(video) {
                            video.pause();
                        });
                    },

                    transitionEnd: function() {
                        var activeIndex = swiperVideo.activeIndex;
                        // var activeSlide = document.getElementsByClassName('swiper-slide')[activeIndex];
                        // var activeSlideVideo = activeSlide.getElementsByTagName('video')[0];
                        var videos = document.querySelectorAll('video');
                        videos[activeIndex].play();
                        // console.log(activeIndex, activeSlide, activeSlideVideo);
                        // activeSlideVideo.play();
                    },
                }
            })
        })
    </script>
</body>

</html>