<main role="main">

    <div class="slider-container rev_slider_wrapper" style="height: 100vh;">
        <div id="revolutionSlider" class="slider rev_slider manual" data-version="5.4.8">
            <ul>

                <li data-transition="fade">
                    <img src="<?= base_url('themes/porto'); ?>/img/slides/neuron.webp"  
                    alt=""
                    data-bgposition="center center" 
                    data-bgfit="cover" 
                    data-bgrepeat="no-repeat" 
                    class="rev-slidebg">

                    <div class="tp-caption custom-font-size-1 font-weight-semibold text-uppercase"
                    data-x="['left','left','left','left']"
                    data-hoffset="['80','80','80','80']" 
                    data-y="['center','center','center','center']"
                    data-voffset="['-80','-80','-80','-80']" 
                    data-start="500"
                    data-paddingleft="['0', '0', '0', '0']"
                    style="z-index: 5; font-size: 18px; color:#FFD800FF;"
                    data-transform_in="y:[-300%];opacity:0;s:500;">Virtual Congress 2020</div> 

                    <h1 class="tp-caption text-color-light font-weight-extra-bold text-uppercase"
                    data-x="['left','left','left','left']"
                    data-hoffset="['77','77','77','77']" 
                    data-y="['center','center','center','center']"
                    data-voffset="['-45','-45','-45','-45']" 
                    data-fontsize="['60', '60', '50', '40']"
                    data-start="800"
                    data-paddingleft="['0', '0', '0', '0']"
                    style="z-index: 5; font-size: 60px;"
                    data-transform_in="y:[-300%];opacity:0;s:500;">PIN PERDOSSI</h1>

                    <div class="tp-caption text-color-light"
                    data-x="['left','left','left','left']"
                    data-hoffset="['81','81','81','81']" 
                    data-y="['center','center','center','center']"
                    data-voffset="['0','0','0','0']" 
                    data-start="1500"
                    data-paddingleft="['0', '0', '0', '0']"
                    data-fontsize="20"
                    style="z-index: 5;"
                    data-transform_in="y:[-300%];opacity:0;s:500;">Pertemuan Ilmiah Nasional</div>

                    <div class="tp-caption text-color-light"
                    data-x="['left','left','left','left']"
                    data-hoffset="['81','81','81','81']" 
                    data-y="['center','center','center','center']"
                    data-voffset="['20','20','20','20']" 
                    data-start="1500"
                    data-paddingleft="['0', '0', '0', '0']"
                    data-fontsize="20"
                    style="z-index: 5;"
                    data-transform_in="y:[-300%];opacity:0;s:500;">Perhimpunan Dokter Spesialis Saraf Indonesia</div>

                    <div class="tp-caption text-uppercase font-weight-extra-bold"
                    data-x="['left','left','left','left']"
                    data-hoffset="['80','80','80','80']" 
                    data-y="['center','center','center','center']"
                    data-voffset="['55','55','55','55']" 
                    data-start="1500"
                    data-paddingleft="['0', '0', '0', '0']"
                    data-fontsize="['20', '20', '20', '26']"
                    data-lineheight="['15', '15', '15', '22']"
                    style="z-index: 5; color: #6acdca;"
                    data-transform_in="y:[-300%];opacity:0;s:500;">Menjawab Tantangan Pelayanan Neurologi</div>

                    <div class="tp-caption text-uppercase font-weight-extra-bold"
                    data-x="['left','left','left','left']"
                    data-hoffset="['80','80','80','80']" 
                    data-y="['center','center','center','center']"
                    data-voffset="['75','75','75','75']" 
                    data-start="1500"
                    data-paddingleft="['0', '0', '0', '0']"
                    data-fontsize="['20', '20', '20', '26']"
                    data-lineheight="['15', '15', '15', '22']"
                    style="z-index: 5; color: #6acdca;"
                    data-transform_in="y:[-300%];opacity:0;s:500;">di Era Adaptasi Kebiasaan Baru</div>

                    <!-- <div class="tp-caption text-uppercase"
                    data-x="['right','right','right','right']"
                    data-hoffset="['80','80','80','80']" 
                    data-y="['center','center','center','center']"
                    data-voffset="['-40','-40','-40','-40']" 
                    data-start="1500"
                    style="z-index: 5;"
                    data-transform_in="opacity:0;s:500;">

                    <a href="#" class="play-video-custom custom-rev-next">
                        <img src="<?= base_url('themes/porto'); ?>/img/play-icon.png" class="img-fluid" width="90" height="90" />
                    </a> -->

                </div>

            </li>
            <li data-transition="fade">
                <img src="video/event.jpg"  
                alt=""
                data-bgposition="center center" 
                data-bgfit="cover" 
                data-bgrepeat="no-repeat" 
                class="rev-slidebg">

                <div class="rs-background-video-layer" 
                data-forcerewind="on" 
                data-volume="mute" 
                data-videowidth="100%" 
                data-videoheight="100%" 
                data-videomp4="video/Unilever - Event Opening Video.mp4" 
                data-videopreload="preload" 
                data-videoloop="none" 
                data-forceCover="1" 
                data-aspectratio="16:9" 
                data-autoplay="true" 
                data-autoplayonlyfirsttime="false" 
                data-nextslideatend="true">
            </div>

            <div class="tp-dottedoverlay tp-opacity-overlay"></div>

        </li>
    </ul>
</div>
</div>

<section id="images" class="bg-color-grey pt-4">
    <div class="container pb-4">
        <div class="row pt-1">
            <div class="col-lg-12">
                <img class="img img-fluid" src="<?= base_url('themes/porto'); ?>/img/gambar.png">
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


<section id="sponsor" class="bg-color-light pt-4">
    <div class="container mt-4 pt-4 pb-4">
        <div class="row pt-2">
            <div class="col">
                <h2 class="text-color-dark text-uppercase font-weight-bold text-center mb-1">Sponsors</h2>
                <p class="custom-font-size-1 text-center mb-5">Thanks to our sponsors</p>
            </div>
        </div>
        <span class="alternative-font" style="font-size: 30px;"><b>Platinum Sponsor</b></span>
        <hr>
        <div class="row">
            <?php
            foreach ($spplatinum as $platinum):
                ?>
                <div class="col-lg-3 col-xs-6">
                    <span class="thumb-info thumb-info-no-borders thumb-info-no-borders-rounded thumb-info-lighten thumb-info-centered-info thumb-info-block thumb-info-block-dark">
                        <span class="thumb-info-wrapper">
                            <img src="<?= base_url('themes/uploads/sponsor') ?>/<?= $platinum->logo ?>" class="img-responsive">
                            <span class="thumb-info-title">
                                <span class="thumb-info-inner"><?= $platinum->name ?></span>
                                <a href="<?= base_url('site/sponsor') ?>/<?= $platinum->name ?>" target="_blank" class="btn btn-info btn-xs text-weight-bold text-color-light"><i class="fas fa-search"></i></a>
                            </span>
                        </span>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <hr>
        <span class="alternative-font" style="font-size: 30px;"><b>Gold Sponsor</b></span>
        <hr>

        <div class="row">
            <?php
            foreach ($spgold as $gold):
                ?>
                <div class="col-lg-2">
                    <span class="align-middle thumb-info thumb-info-no-borders thumb-info-no-borders-rounded thumb-info-lighten thumb-info-centered-info thumb-info-block thumb-info-block-dark mx-3 my-3">
                        <span class="thumb-info-wrapper">
                            <img src="<?= base_url('themes/uploads/sponsor') ?>/<?= $gold->logo ?>" class="img-fluid">
                            <span class="thumb-info-title">
                                <span class="thumb-info-inner" style="font-size: 12px;"><?= $gold->name ?></span>
                                <a href="<?= base_url('site/sponsor') ?>/<?= $gold->name ?>" target="_blank" class="btn btn-info btn-xs text-weight-bold text-color-light"><i class="fas fa-search"></i></a>
                            </span>
                        </span>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <br>
        <hr>
        <span class="alternative-font" style="font-size: 30px;"><b>Silver Sponsor</b></span>
        <hr>
        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel owl-theme" data-plugin-options="{'items': 6, 'autoplay': true, 'autoplayTimeout': 3000}">
                    <?php 
                    foreach ($spsilver as $silver):
                    ?>
                    <div>
                        <center>
                            <a href="<?= base_url('site/sponsor') ?>/<?= $silver->name ?>" target="_blank">
                                <img class="img-fluid px-2" src="<?= base_url('themes/uploads/sponsor'); ?>/<?= $silver->logo?>" style="" alt="">
                            </a>
                        </center>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</section>

<div class="icon-bar">
    <a href="https://wa.me/6281575099960" target="_blank" class="whatsapp"><i class="fab fa-whatsapp"> How can I help you ?</i></a> 
</div>

</main>