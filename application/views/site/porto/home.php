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

<section id="timer" class="bg-color-light pt-2 pb-3">
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
            <h3 class="text-color-dark text-uppercase font-weight-bold text-center mb-3">abstract Countdown</h3>
               <div class="countdown countdown-borders countdown-biru" data-plugin-countdown data-plugin-options="{'date': '<?php echo $papertimer; ?>', 'numberClass': 'font-weight-extra-bold', 'wrapperClass': 'border-color-emas'}"></div>
           </div>
           <div class="col-lg-6 divider-left-border py-2 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="200">
               <h3 class="text-color-dark text-uppercase font-weight-bold text-center mb-3">Event Countdown</h3>
               <div class="countdown countdown-borders countdown-emas" data-plugin-countdown data-plugin-options="{'date': '<?php echo $eventtimer; ?>', 'numberClass': 'font-weight-extra-bold', 'wrapperClass': 'border-color-biru bg-color-biru'}"></div>
           </div>
       </div>
   </div>
</section>

<section class="bg-color-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                    <a href="<?= base_url('themes/porto'); ?>/pengumuman/2nd.pdf" target="_blank" class="btn btn-primary font-weight-semibold text-uppercase text-5 mt-2 mb-3 col-lg-12">Second Announcement</a>
            </div>
            <div class="col-lg-6 divider-left-border">
                    <a href="<?= base_url('themes/porto'); ?>/pengumuman/ketentuan.pdf" target="_blank" class="btn btn-primary font-weight-semibold text-uppercase text-5 mt-2 mb-3 col-lg-12">KETENTUAN E-POSTER & PRESENTASI ORAL</a>
            </div>
        </div>
        
    </div>
</section>

<section id="login" class="bg-color-grey">
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

<section id="event" class="bg-color-light pt-1">
    <div class="container mt-0 pb-4">
        <?php
        $colap = 1;
        foreach ($query as $row):
            ?>
            <h4 class="font-weight-bold text-color-dark pt-4 appear-animation" data-appear-animation="fadeInRightShorter"><?php echo $row->kategori ?></h4>
            <?php
            foreach ($row->kondisi as $row2):
                ?>
                <div class="accordion" id="accordion7 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="600">
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
</section>

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

</div>