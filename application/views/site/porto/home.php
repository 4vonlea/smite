<main role="main">

   <!-- <div class="slider-container rev_slider_wrapper" style="height: 100%;">
    <div id="revolutionSlider" class="slider rev_slider manual" data-version="5.4.8">
        <ul>
            <li data-transition="fade">
                <img class="img-fluid" src="<?= base_url('themes/porto'); ?>/img/slide1_.png"  
                alt=""
                data-bgposition="center center" 
                data-bgfit="contain" 
                data-bgrepeat="no-repeat" 
                data-bgparallax="1" 
                class="rev-slidebg">
            </li>
            <li data-transition="fade">
                <img class="img-fluid" src="<?= base_url('themes/porto'); ?>/img/slide2.png"  
                alt=""
                data-bgposition="center center" 
                data-bgfit="cover" 
                data-bgrepeat="no-repeat" 
                data-bgparallax="1" 
                class="rev-slidebg">
            </li>
        </ul>
    </div>
</div> -->

<section id="slidee" class="bg-color-grey">
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

<!-- <section id="images" class="bg-color-light pt-3">
    <div class="container pb-4">
        <div class="row pt-5">
            <div class="col-lg-12">
                <img class="img img-fluid" src="<?= base_url('themes/porto'); ?>/img/gambar.png">
            </div>
        </div>
    </div>
</section> -->

<section id="login" class="bg-color-grey">
    <div class="container pb-4">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="text-color-dark font-weight-bold mt-5 mb-3">Login here</h2>
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
                            class="btn custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase"
                            data-loading-text="Loading...">
                        </div>
                        <div class="form-group col">
                            <label class="form-check-label">
                                <input type="checkbox" name="rememberme" class="form-check-input"/>
                                Remember Me
                            </label>
                        </div>
                    </div>
                </form>
                <span style="font-size: 20px;" class="text-color-dark font-weight-bold">Don't have account??</span>
                <a class="btn custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase ml-3" href="<?= base_url("member/register"); ?>">Register here</a>
            </div>
            <div class="col-lg-6">
                <h3 class="text-color-dark font-weight-normal text-6 mt-5 ml-3 mb-3"><strong class="font-weight-extra-bold">How to
                register :</strong></h3>
                <ol class="text-color-dark text-4 line-height-5 mb-0 ml-3">
                    <li>Click <b>REGISTER HERE</b>, and fill your profile</li>
                    <li>Choose your event (and or submit your abstract)</li>
                    <li>Pay via our online payment</li>
                    <li>Click Webinar Link and Enjoy our Symposium</li>
                </ol> 
                <br>
                <ul class="text-color-dark text-4 line-height-5 ml-3 mb-0">
                    <li><i>1 account 1 email per user</i></li>
                    <li><i>E-certificate will be delivered to registered email</i></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
</section>

<section id="event" class="bg-color-light pt-4">
    <div class="container mt-4 pb-4">
        <?php
        $colap = 1;
        foreach ($query as $row):
            ?>
            <h4 class="font-weight-bold text-color-dark pt-4"><?php echo $row->kategori ?></h4>
            <?php
            foreach ($row->kondisi as $row2):
                ?>
                <div class="accordion accordion-primary" id="accordion7">
                    <div class="card card-default">
                        <div class="card-header">
                            <h4 class="card-title m-1">
                                <a class="accordion-toggle collapsed text-1 text-color-light" data-toggle="collapse" data-parent="#accordion7" href="#colap<?php echo $colap; ?>" aria-expanded="false">
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
                                                <td rowspan="2" align="center" class="align-middle"><a href="<?=base_url("site/login");?>" class="btn btn-success">ORDER</a></td>
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
                <h2 class="text-color-dark text-uppercase font-weight-bold text-center mb-1">News & Updates</h2>
                <a class="text-color-primary text-weight-bold" href="<?php echo base_url('site/all_news') ?>" target="_self" id="a_1dd7_5"><center>Read All News</center></a>
            </div>
        </div>
        <hr>
        <div class="row">
            <?php
            foreach ($query2 as $key):
                ?>
                <div class="col-lg-4 text-center text-md-left mb-5 mb-lg-0">
                    <h6 class="text-color-dark font-weight-normal text-6 line-height-2"><strong class="font-weight-extra-bold"><?php echo $key->title ?></strong></h6>
                    <p style="font-size:9px" >
                        <?php echo character_limiter($key->content, 100) ?>
                    </p>
                    <p><a href="<?php echo base_url('site/readnews/'.$key->id) ?>" style="font-size: 11px" class="btn btn-primary">read more</a></p>
                </div>
                <?php 
            endforeach; 
            ?>
        </div>
    </div>
</section>




</main>