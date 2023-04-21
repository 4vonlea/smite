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
    <title>National Congress of Indonesian Neurology Association 2023 SEMARANG</title>
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/css/plugins/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/css/plugins/slick.css">
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/css/plugins/animate.css">
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/css/custom.css">
    <link rel="stylesheet" href="<?= base_url('themes/aenft'); ?>/assets/fontawesome/css/all.min.css">
</head>

<body class="cs-dark">
    <!-- <div class="cs-preloader cs-white_bg cs-center">
        <div class="cs-preloader_in">
            <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/logo.png" alt="Logo">
        </div>
    </div> -->

    <button class="btn btn-sm btn-primary position-fixed bottom-0 end-0 translate-middle d-none" onclick="scrollToTop()" id="back-to-up">
        <i class="fa fa-arrow-up" aria-hidden="true"></i>
    </button>

    <!-- Start Hero -->
    <div id="home" class="cs-hero cs-style1 cs-type2 cs-bg text-center  cs-ripple_version" data-src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg-head.png">
        <div class="cs-dark_overlay">

        </div>
        <!-- <div class="dropdown mt-3 ms-3" style="position: absolute; right: 5px; top:5px;">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-language"></i> <?= ucfirst($this->config->item("language")); ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="<?= current_url() . "?language=english"; ?>">English</a></li>
                <li><a class="dropdown-item" href="<?= current_url() . "?language=indonesia"; ?>">Indonesia</a></li>
            </ul>
        </div> -->
        <div class="container scaled">
            <div class="cs-hero_img wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/logo.png" style="width: 100%; max-width: 320px; height: auto;">
            </div>
            <div class="cs-hero_text wow fadeIn" data-wow-duration="1s" data-wow-delay="0.45s" style="margin-top: -50px;">
                <h3 class="cs-hero_title text-uppercase">National Congress of Indonesian Neurology Association 2023 SEMARANG</h3>
                <h3 class="cs-hero_subtitle text-uppercase">2 - 6 August 2023</h3>
                <div class="row mt-5 align-middle">
                    <div class="col-lg-3 col-md-3 col-12 d-grid gap-2 mb-1">
                        <a href="<?= base_url('site/home'); ?>" class="btn btn-success"><i class="fa-solid fa-earth-asia fa-2x mt-1"></i><br>Visit Full Website here</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-12 d-grid gap-2 mb-1">
                        <a href="<?= base_url('member/register'); ?>" class="btn btn-primary"><i class="fa-solid fa-clipboard-user fa-2x mt-1"></i> <br>Individual Registration</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-12 d-grid gap-2 mb-1">
                        <a href="<?= base_url('member/register/group'); ?>" class="btn btn-success"><i class="fa-solid fa-user-group fa-2x mt-1"></i><br>Group Registration</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-12 d-grid gap-2 mb-1">
                        <a href="https://drive.google.com/file/d/1S7dkr-fvrvmWk9AFmbAkKYqG0-PYaKJd/view?usp=share_link" target="_blank" class="btn btn-primary"><i class="fa-solid fa-cloud-arrow-down fa-2x mt-1"></i><br>Download Second Announcement</a>
                    </div>
                </div>
                <hr class="mt-3 mb-3">
                <div class="text-center">
                    <div class="col-12">
                        <h6>Symposium, Workshop and Hotel Registration can only be done through the website. The committee does not accept registration through other mechanisms. </h6>
                        <h6>E-Certificate integration into P2KB Online can only be given to participants who register via the website</h6>
                    </div>
                </div>
                <div class="d-grid gap-2 col-md-6 offset-md-3 mt-3 mb-3">
                    <div class="box">
                        <h6>For those who have registered, please login here.</h6>
                        <br>

                        <form action="<?= base_url('site/login'); ?>" method="post">
                            <?php if (!$hasSession) : ?>
                                <div class="inputBox">
                                    <input type="text" name="username" required="">
                                    <label for="">Username</label>
                                </div>
                                <div class="inputBox">
                                    <input type="password" name="password" required="">
                                    <label for="">Password</label>
                                </div>
                                <div class="text-end text-white mb-3" style="margin-top: 20px; font-size: 14px; text-decoration: underline;">
                                    <a href="<?= base_url("site/forget"); ?>">Forgot password?</a>
                                </div>
                            <?php endif; ?>

                            <button type="submit" value="login" name="login" class="btn btn-primary w-100" style="margin-top: -3px;">
                                <span> <?= $hasSession ? lang("back_to_member_area") : lang("login"); ?></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- End Hero -->
  <br><br>      
  <div class="container">
            <div class="cs-seciton_heading cs-style1 text-uppercase wow fadeInUp text-center" data-wow-duration="1s" data-wow-delay="0.2s">
                <h3 class="cs-section_title cs-font_16 cs-font_14_sm cs-gradient_color">News</h3>
                <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm">Explore Semarang and Join our event</h2>
            </div>
  <div class="row">
    	<div class="col-md-6 col-sm-12 p-4">
          <div id="demo" class="carousel slide" data-bs-ride="carousel">
        <!-- The slideshow/carousel -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/1.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/2.jpeg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/3.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/4.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/5.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/6.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/7.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/8.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/9.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/10.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/11.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/bg/12.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: cover;">
            </div>
        </div>

        <!-- Left and right controls/icons -->
        <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
        </div>
        <div class="col-md-6 col-sm-12 p-4">
           <div class="row align-items-center wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
            <div class="col-lg-12 text-center">
                <img src="<?= base_url('themes/aenft'); ?>/assets/img/konas/konas.jpg" alt="" class="d-block" style="width:100%; height: 700px; object-fit: contain;">
            </div>
        </div>
        </div>
    </div>
    

    <div class="cs-footer_wrap">
       
        <footer class="cs-footer text-center">
            <div class="container mt-4">
                <div class="cs-copyright text-center wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
                    <p>&copy; 2023 License to #National Congress of Indonesian Neurology Association 2023 Semarang#</p>
                    <p>Developed by #CV. Meta Medika#</p><span class="cs-primary_font cs-primary_color"></span>
                </div>
            </div>
            <div class="cs-height_25 cs-height_lg_25"></div>
        </footer>
        <!-- End Footer -->
    </div>

    <!-- Script -->
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/jquery.slick.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/jquery.counter.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/wow.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/ripples.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/main.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/custom.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/fontawesome/js/all.min.js"></script>
    <script src="<?= base_url('themes/aenft'); ?>/assets/js/plugins/bootstrap.bundle.min.js"></script>
</body>

</html>