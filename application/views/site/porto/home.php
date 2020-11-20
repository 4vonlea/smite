<div role="main" class="main">

    <section id="slidee" class="bg-color-grey appear-animation" data-appear-animation = "fadeIn">
        <div class="owl-carousel owl-theme nav-inside nav-inside-edge nav-squared nav-with-transparency nav-light" data-plugin-options="{'items': 1, 'margin': 10, 'loop': true, 'nav': true, 'dots': false, 'autoplay': true, 'autoplayTimeout': 6000}">
            <div>
                <div class="img-thumbnail border-0 p-0 d-block">
                    <img class="img-fluid border-radius-0" src="<?= base_url('themes/porto'); ?>/img/slide1.png" alt="">
                </div>
            </div>
            <div>
                <div class="img-thumbnail border-0 p-0 d-block">
                    <img class="img-fluid border-radius-0" src="<?= base_url('themes/porto'); ?>/img/slide2.png" alt="">
                </div>
            </div>
        </div>
    </section>

    <section id="counttotal" class="bg-color-light">
        <div class="container">
            <div class="featured-boxes featured-boxes-style-3 featured-boxes-flat">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="featured-box featured-box-primary featured-box-effect-3" style="height: 200px;">
                            <div class="box-content box-content-border-0">
                                <i class="icon-featured far fa-user"></i>
                                <h4 class="font-weight-normal text-8 mt-2"><strong class="font-weight-extra-bold"></strong>93</h4>
                                <p class="mb-0 mt-0 text-3">speaker</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="featured-box featured-box-primary featured-box-effect-3" style="height: 200px;">
                            <div class="box-content box-content-border-0">
                                <i class="icon-featured far fa-user"></i>
                                <h4 class="font-weight-normal text-8 mt-2"><strong class="font-weight-extra-bold"></strong>36</h4>
                                <p class="mb-0 mt-0 text-3">Moderator</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="featured-box featured-box-tertiary featured-box-effect-3" style="height: 200px;">
                            <div class="box-content box-content-border-0">
                                <i class="icon-featured far fa-user"></i>
                                <h4 class="font-weight-normal text-8 mt-2"><strong class="font-weight-extra-bold"></strong><?php echo $participant; ?></h4>
                                <p class="mb-0 mt-0 text-3">Participant</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="featured-box featured-box-quaternary featured-box-effect-3" style="height: 200px;">
                            <div class="box-content box-content-border-0">
                                <i class="icon-featured far fa-file-alt"></i>
                                <h4 class="font-weight-normal text-8 mt-2"><strong class="font-weight-extra-bold">293</strong></h4>
                                <p class="mb-0 mt-0 text-3">Abstrak</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="timer" class="bg-color-grey pt-2 pb-3">
        <?php
        foreach($eventcountdown as $eventcd) 
        {
            $eventtimer = $eventcd->value;
        }

        foreach($papercountdown as $papercd) 
        {
            $papertimer = $papercd->value;
        }
        ?>
        <div class="container">
            <div class="row">
             <div class="col-lg-6 py-2 appear-animation" data-appear-animation="fadeInRightShorter">
                <!-- <h3 class="text-color-dark text-uppercase font-weight-bold text-center mb-3">abstract Countdown</h3> -->
                <!-- <div class="countdown countdown-borders countdown-biru" data-plugin-countdown data-plugin-options="{'date': '<?php echo $papertimer; ?>', 'numberClass': 'font-weight-extra-bold', 'wrapperClass': 'border-color-emas'}"></div> -->
                <!-- <h3 class="text-color-dark text-uppercase font-weight-bold text-center mb-3">Event Countdown</h3> -->
                <!-- <div class="countdown countdown-borders countdown-emas" data-plugin-countdown data-plugin-options="{'date': '<?php echo $eventtimer; ?>', 'numberClass': 'font-weight-extra-bold', 'wrapperClass': 'border-color-biru bg-color-biru'}"></div> -->
                <div class="d-flex justify-content-center pb-1">
                    <iframe width="600" height="270" allowfullscreen="true" src="https://www.youtube.com/embed/awJsqhB9mwE"></iframe>
                </div>
                    <a href="<?= base_url('themes/porto'); ?>/pengumuman/simpo.pdf" target="_blank" class="btn btn-primary font-weight-semibold text-uppercase text-3 mt-2 mb-3 col-lg-12">Jadwal Acara (perubahan)</a>
            </div>
            <div class="col-lg-6 divider-left-border py-2 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="200">
               <!-- <h3 class="text-color-dark text-uppercase font-weight-bold text-center mb-3">Event Countdown</h3>
                 <div class="countdown countdown-borders countdown-emas" data-plugin-countdown data-plugin-options="{'date': '<?php echo $eventtimer; ?>', 'numberClass': 'font-weight-extra-bold', 'wrapperClass': 'border-color-biru bg-color-biru'}"></div> -->
                 <div class="text-color-dark ml-3 pb-3">
                     <span><b>Oral Presentation:</b><br>Dipresentasikan durasi 10 menit (termasuk tanya jawab)</span><br>
                     <span><b>Moderated poster:</b><br>E-Poster dipresentasikan dengan durasi 8 menit (termasuk tanya jawab)</span><br>
                     <span><b>Viewed poster:</b><br>E-Poster tidak dipresentasikan</span>
                </div>
                     <a href="<?= base_url('themes/porto'); ?>/pengumuman/oralposter.pdf" target="_blank" class="btn btn-primary font-weight-semibold text-uppercase text-3 col-lg-12">Jadwal Oral & E-Poster</a>
                <a href="<?= base_url('themes/porto'); ?>/pengumuman/penyajian.pdf" target="_blank" class="btn btn-primary font-weight-semibold text-uppercase text-3 mt-2 mb-3 col-lg-12">Ketentuan Penyajian Presentasi PIN PERDOSSI 2020</a>
                 
             </div>
         </div>
     </div>
 </section>

 <!-- <section class="bg-color-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <a href="<?= base_url('themes/porto'); ?>/pengumuman/final.pdf" target="_blank" class="btn btn-primary font-weight-semibold text-uppercase text-3 mt-2 mb-3 col-lg-12">Final Announcement</a>
            </div>
            <div class="col-lg-6 divider-left-border">
                <a href="<?= base_url('themes/porto'); ?>/pengumuman/oralposter.pdf" target="_blank" class="btn btn-primary font-weight-semibold text-uppercase text-3 col-lg-12">Jadwal Oral & E-Poster</a>
                <a href="<?= base_url('themes/porto'); ?>/pengumuman/penyajian.pdf" target="_blank" class="btn btn-primary font-weight-semibold text-uppercase text-3 mt-2 mb-3 col-lg-12">Ketentuan Penyajian Presentasi PIN PERDOSSI 2020</a>
            </div>
        </div>
        
    </div>
</section> -->

<section id="login" class="bg-color-light">
    <div class="container pb-4">
        <div class="row">
            <div class="col-lg-6 appear-animation" data-appear-animation="fadeInRightShorter">
                <h2 class="text-color-dark font-weight-bold mt-5 mb-3">Login disini</h2>
                <form class="custom-contact-form-style-1" action="<?= base_url('site/login'); ?>" method="POST">
                    <div class="form-row">
                        <div class="form-group col">
                            <div class="custom-input-box">
                                <i class="icon-user icons text-color-primary"></i>
                                <input type="text" value="" data-msg-required="Please enter your Email."
                                maxlength="100" class="form-control" name="username" placeholder="Email"
                                required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <div class="custom-input-box">
                                <i class="icon-key icons text-color-primary"></i>
                                <input type="password" value="" data-msg-required="Please enter your password."
                                data-msg-password="Password can not empty." maxlength="100"
                                class="form-control" name="password" placeholder="Password" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <input type="submit" value="Login now" name="login"
                            class="btn btn-primary custom-border-width custom-border-radius font-weight-semibold text-uppercase"
                            data-loading-text="Loading...">
                        </div>
                        <div class="form-group col">
                            <label class="form-check-label">
                                <input type="checkbox" name="rememberme" class="form-check-input"/>
                                Ingat saya
                            </label>
                            <a href="<?= base_url("site/forget"); ?>" class="text-color-primary ml-5">Lupa password?</a>
                        </div>
                    </div>
                </form>
                <span style="font-size: 20px;" class="text-color-dark font-weight-bold">Belum punya akun?</span>
                <a class="btn btn-primary  custom-border-width custom-border-radius font-weight-semibold text-uppercase ml-3" href="<?= base_url("member/register"); ?>">Registrasi disini</a>
            </div>
            <div class="col-lg-6 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="400">
                <h3 class="text-color-dark font-weight-normal text-6 mt-5 ml-3 mb-3"><strong class="font-weight-extra-bold">Cara untuk registrasi :</strong></h3>
                <ol class="text-color-dark text-4 line-height-5 mb-0 ml-3">
                    <li>Klik <b>REGISTRASI DISINI</b>, dan lengkapi biodatamu</li>
                    <li>Pilih event yang ingin diikuti (and or submit abstrakmu)</li>
                    <li>Bayar melalui portal pembayaran online kami</li>
                    <li>Klik <i>Webinar Link</i> dan nikmati webinar kami</li>
                </ol> 
                <br>
                <ul class="text-color-dark text-4 line-height-5 ml-3 mb-0">
                    <li><i>1 akun 1 email per user</i></li>
                    <li><i>E-certificate akan dikirimkan ke email yang terdaftar</i></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
</section>

<!-- <section id="event" class="bg-color-light pt-1">
    <div class="container mt-0 pb-4">
        <?php
        $colap = 1;
        foreach ($query as $row):
            ?>
            <h4 class="font-weight-bold text-color-dark pt-4 appear-animation" data-appear-animation="fadeInRightShorter"><?php echo $row->kategori ?></h4>
            <?php
            foreach ($row->kondisi as $row2):
                ?>
                <div class="accordion appear-animation" id="accordion7" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="600">
                    <div class="card card-default">
                        <div class="card-header" style="background-color: #080531;">
                            <h4 class="card-title m-1">
                                <a class="accordion-toggle collapsed text-1"  style="color: #dfad26;" data-toggle="collapse" data-parent="#accordion7" href="#colap<?php echo $colap; ?>" aria-expanded="false">
                                    <?php echo $row2->kondisi ?> <span class="custom-accordion-plus"></span>
                                </a>
                            </h4>
                        </div>
                        <div id="colap<?php echo $colap; ?>" class="collapse" aria-expanded="false" style="height: 0px;">
                            <div class="card-body">
                                <p>
                                    <?php
                                    foreach ($row2->acara as $row3):
                                        ?>
                                        <table  class="table table-bordered">
                                            <tr>
                                                <td rowspan="2">
                                                    <?php echo $row3->nama_acara ?>
                                                </td>

                                                <?php
                                                foreach ($row3->pricing as $row4):
                                                    ?>
                                                    <td><?php echo $row4['jenis_harga'] ?> <br> (<?php echo Site::formatdate($row4['waktu_mulai']) ?> s.d. <?php echo Site::formatdate($row4['waktu_akhir']) ?> ) </td>
                                                    <?php
                                                endforeach;
                                                ?>
                                                <td rowspan="2" align="center" class="align-middle"><a href="<?=base_url("site/login");?>" class="btn btn-primary">PESAN</a></td>
                                            </tr>
                                            <tr>
                                                <?php
                                                foreach ($row3->pricing as $row4):
                                                    ?>
                                                    <td><?php echo "Rp. " . number_format($row4['harga'], 2, ',', '.') ?></td>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </tr>
                                        </table>

                                        <?php
                                    endforeach;
                                    ?>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                $colap++;
            endforeach;
            ?>
            <?php
        endforeach;
        ?>
    </div>
</section> -->

<section id="news" class="bg-color-grey pt-4">
    <div class="container pb-4">
        <div class="row pt-2">
            <div class="col">
                <h2 class="text-color-dark text-uppercase font-weight-bold text-center mb-1 appear-animation" data-appear-animation="fadeInUp" data-appear-animation-delay = "200">Berita dan Info Terkini</h2>
                <a class="text-weight-bold appear-animation" style="background-color: #080531; color: #dfad26;" href="<?php echo base_url('site/all_news') ?>" target="_self" id="a_1dd7_5" data-appear-animation = "fadeInUp" data-appear-animation-delay = "600"><center>Baca semua berita</center></a>
            </div>
        </div>
        <hr>
        <div class="row">
            <?php
            foreach ($query2 as $key):
                ?>
                <div class="col-lg-4 text-center text-md-left mb-5 mb-lg-0">
                    <h6 class="text-color-dark font-weight-normal text-6 line-height-2 appear-animation" data-appear-animation = "fadeInUp" data-appear-animation-delay = "1200"><strong class="font-weight-extra-bold"><?php echo $key->title ?></strong></h6>
                    <div class="appear-animation" data-appear-animation = "fadeInUp" data-appear-animation-delay = "1200">
                        <p style="font-size:9px">
                            <?php echo character_limiter($key->content, 500) ?>
                        </p>
                    </div>
                    <p>
                        <a href="<?php echo base_url('site/readnews/'.$key->id) ?>" style="font-size: 11px" class="btn btn-primary appear-animation" data-appear-animation ="fadeInRightShorter" data-appear-animation-delay = "1800">baca selengkapnya</a>
                    </p>
                </div>
                <?php 
            endforeach; 
            ?>
        </div>
    </div>
</section>

<section id="vid_promo" class="bg-color-light pt-3 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 appear-animation" data-appear-animation="fadeIn">
                <!-- <div class="d-flex justify-content-center pb-4 pt-5">
                    <iframe width="600" height="300" allowfullscreen="true" src="https://www.youtube.com/embed/awJsqhB9mwE"></iframe>
                </div> -->
                <img src="<?= base_url('themes/porto'); ?>/img/fotolomba.png" class="img-fluid mt-5" alt="">
            </div>
            <div class="col-md-6">
                <hr>
                <div class="text-center text-uppercase mt-2">
                    <h4>Juri Lomba Foto & Video</h4>
                </div>
                <hr>
                
                <div class="row">

                    <div class="col-md-6">
                        <div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="600">
                            <span class="thumb-info thumb-info-no-borders thumb-info-no-borders-rounded thumb-info-lighten thumb-info-bottom-info thumb-info-centered-icons">
                                <span class="thumb-info-wrapper">
                                    <img src="<?= base_url('themes/porto'); ?>/img/yuliardy.jpg" class="img-fluid" alt="">
                                    <span class="thumb-info-title">
                                        <span class="thumb-info-inner line-height-1 font-weight-bold text-dark position-relative top-3">dr. Yuliardy Limengka, BMedSc, SpB</span>
                                    </span>                                                 
                                    
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="600">
                            <span class="thumb-info thumb-info-no-borders thumb-info-no-borders-rounded thumb-info-lighten thumb-info-bottom-info thumb-info-centered-icons">
                                <span class="thumb-info-wrapper">
                                    <img src="<?= base_url('themes/porto'); ?>/img/rara.jpg" class="img-fluid" alt="">
                                    <span class="thumb-info-title">
                                        <span class="thumb-info-inner line-height-1 font-weight-bold text-dark position-relative top-3">dr. Twindy Rarasati</span>
                                    </span>  
                                </span>
                            </span>
                        </div>
                    </div>

                </div>

                <div>
                    <!-- <a href="<?= base_url('themes/porto'); ?>/img/syarat.pdf" target="_blank" class="btn btn-info font-weight-semibold text-4 mt-2 col-lg-12">Syarat & Ketentuan</a> -->
                    <a href="<?php echo base_url('site/vid') ?>" class="btn btn-primary font-weight-semibold  text-uppercase text-4 mt-2 col-lg-12"><i class="fas fa-vote-yea">  Vote Foto & Video</i></a>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<section id="sponsor" class="bg-color-light">
    <div class="container mt-4 pt-4 pb-4">
        <div class="row">
            <div class="col-lg-12">
                <hr class="d-lg-none tall">
                <h4>Frequently Asked Question</h4>

                <div class="toggle toggle-primary" data-plugin-toggle="" data-plugin-options="{ 'isAccordion': true }">
                    <section class="toggle active">
                        <label>Bagaimana cara login?</label>
                        <div class="toggle-content" style="display: block;">
                            <p>Login menggunakan user email yang telah didaftarkan pada web <a href="https://pinperdossi2020.com/">https://pinperdossi2020.com/</a> dan password masing-masing</p>
                        </div>
                    </section>
                    <section class="toggle">
                        <label>Bagaimana kalau saya lupa password saya?</label>
                        <div class="toggle-content">
                            <p>Apabila lupa password, silakan mengklik bagian “Lupa Password” pada laman login, dan masukkan email yang terdaftar, kemudian klik Reset. Password baru akan dikirim ke email yang telah diinput ketika proses reset. Atau bisa juga langsung menklik: <a href="https://pinperdossi2020.com/site/forget">https://pinperdossi2020.com/site/forget</a></p>
                        </div>
                    </section>
                    <section class="toggle">
                        <label>Di mana link acara tersedia?</label>
                        <div class="toggle-content">
                            <p>Link acara tersedia di bagian “Webinar Link” di laman Member Area, sebelah kiri laman tsb. Saksikan video Tutorial di <a href="https://pinperdossi2020.com/site/readnews/5">https://pinperdossi2020.com/site/readnews/5</a></p>
                        </div>
                    </section>
                    <section class="toggle">
                        <label>Bagaimana apabila saya salah input email user saya?</label>
                        <div class="toggle-content">
                            <p>Silakan mencari di kotak masuk email anda, dengan kata kunci: admin@pinperdossi2020.com untuk mengetahui notifikasi username anda</p>
                        </div>
                    </section>
                    <section class="toggle">
                        <label>Saya didaftarkan oleh sponsor, apa yang harus saya lakukan untuk login?</label>
                        <div class="toggle-content">
                            <p>Silakan menghubungi sponsor anda langsung untuk informasi akun anda</p>
                        </div>
                    </section>
                    <section class="toggle">
                        <label>Saya bukan peserta di acara ini, tapi saya pembicara/moderator/juri pada acara ini, bagaimana saya login?</label>
                        <div class="toggle-content">
                            <p>Bagi para pembicara, moderator dan juri, akses akun telah kami kirimkan secara autogenerated ke email dokter sekalian. Mohon mengecek inbox/ spam dan mencoba akses di website kami dengan akun tersebut.</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>

</div>

<script type="text/javascript">
    function savelike(video_id)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('site/savelikes');?>",
            data: "Video_id="+video_id,
            success: function (response) {
             $("#like_"+video_id).html(response+" Likes");

         }
     });
    }
</script>