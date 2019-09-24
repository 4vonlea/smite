<div class="slider-container rev_slider_wrapper" style="height: 100%;">
    <div id="revolutionSlider" class="slider rev_slider manual" data-version="5.4.8">
        <ul>
            <li data-transition="fade">
                <img src="<?= base_url('themes/porto'); ?>/img/demos/business-consulting/slides/black.jpg"
                     alt=""
                     data-bgposition="center center"
                     data-bgfit="cover"
                     data-bgrepeat="no-repeat"
                     data-bgparallax="1"
                     class="rev-slidebg">

                <h1 class="tp-caption custom-secondary-font font-weight-bold text-color-light"
                    data-x="['left','left','left','left']" data-hoffset="['30','30','30','30']"
                    data-y="center" data-voffset="['-80','-80','-80','-40']"
                    data-start="800"
                    data-transform_in="y:[-300%];opacity:0;s:500;" style="font-size: 20px;">Welcome to EASTDV</h1>

                <div class="tp-caption custom-secondary-font font-weight-bold text-color-light"
                     data-x="['left','left','left','left']" data-hoffset="['30','30','30','30']"
                     data-y="center" data-voffset="['-42','-42','-42','2']"
                     data-start="800"
                     data-transform_in="y:[-300%];opacity:0;s:500;" style="font-size: 30px;">East Indonesian Society of Dermatology and Venerology
                </div>

                <a href="<?=base_url("site/login");?>"
                   class="btn btn-primary tp-caption text-uppercase text-color-light custom-border-radius"
                   data-hash
                   data-hash-offset="85"
                   data-x="['left','left','left','left']" data-hoffset="['30','30','30','30']"
                   data-y="center" data-voffset="['60','60','60','100']"
                   data-start="1500"
                   style="font-size: 12px; padding: 15px 6px;"
                   data-transform_in="y:[-300%];opacity:0;s:500;"><b><strong>Login Now</strong></b></a>
            </li>
            <li data-transition="fade">
                <img src="<?= base_url('themes/porto'); ?>/img/demos/business-consulting/slides/blackv.jpg"
                     alt=""
                     data-bgposition="center center"
                     data-bgfit="cover"
                     data-bgrepeat="no-repeat"
                     data-bgparallax="1"
                     class="rev-slidebg">

                <h1 class="tp-caption custom-secondary-font font-weight-bold text-color-light"
                    data-x="['left','left','left','left']" data-hoffset="['30','30','30','30']"
                    data-y="center" data-voffset="['-80','-80','-80','-40']"
                    data-start="800"
                    data-transform_in="y:[-300%];opacity:0;s:500;" style="font-size: 20px;">Welcome to EASTDV</h1>

                <div class="tp-caption custom-secondary-font font-weight-bold text-color-light"
                     data-x="['left','left','left','left']" data-hoffset="['30','30','30','30']"
                     data-y="center" data-voffset="['-42','-42','-42','2']"
                     data-start="800"
                     data-transform_in="y:[-300%];opacity:0;s:500;" style="font-size: 30px;">East Indonesian Society of Dermatology and Venerology
                </div>

                <a href="<?=base_url("site/login");?>"
                   class="btn btn-primary tp-caption text-uppercase text-color-light custom-border-radius"
                   data-hash
                   data-hash-offset="85"
                   data-x="['left','left','left','left']" data-hoffset="['30','30','30','30']"
                   data-y="center" data-voffset="['60','60','60','100']"
                   data-start="1500"
                   style="font-size: 12px; padding: 15px 6px;"
                   data-transform_in="y:[-300%];opacity:0;s:500;"><b><strong>Login Now</strong></b></a>
            </li>
        </ul>
    </div>
</div>

<section class="looking-for custom-position-1 custom-md-border-top">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-8">
                <div class="looking-for-box">
                    <h2><span class="text-1 custom-secondary-font">New Paradigm of Management in Pediatric to Geriatic Dermatology & Vereology</span><br>
                       Effective Strategies Related to Diagnosis Prevention and Management</h2>
                       <p>March 13-15 2020, Golden Tulip Banjarmasin</p>
                </div>
            </div>
            <div class="col-md-3 d-flex justify-content-md-end mb-4 mb-md-0">
                <a class="text-decoration-none" href="tel:+00112304567" target="_blank" title="Call Us Now">
									<span class="custom-call-to-action">
										<span class="action-title text-color-primary">Call Us Now</span>
										<span class="action-info text-color-light">+6281254064731</span>
                                        <span class="action-info text-color-light">+6282358599991 (call only)</span>
                                        <span class="action-info text-color-light">+6282159043555 (WA only)</span>
									</span>
                                    <span class="custom-call-to-action">
                                        <span class="action-title text-color-primary">Email Us Now</span>
                                        <span class="action-info text-color-light">East.insdv2020@gmail.com</span>
                                    </span>
                </a>
            </div>
            <!-- <div class="col-md-3 col-lg-2">
                <a class="text-decoration-none" href="mail:mail@example.com" target="_blank" title="Email Us Now">
									<span class="custom-call-to-action">
										<span class="action-title text-color-primary">Email Us Now</span>
										<span class="action-info text-color-light">mail@example.com</span>
									</span>
                </a>
            </div>
        </div> -->
    </div>
</section>

<section class="custom-section-padding" id="login">
    <div class="container">
        <div class="row mb-3">
            <div class="col-lg-12">
            <?php 
                $colap = 1;
                foreach ($query as $row):
            ?>    
                <h2 class="font-weight-bold text-color-dark"><?php echo $row->kategori ?></h2>
                <?php
                    foreach ($row->kondisi as $row2):
                ?>
                <div class="accordion without-bg custom-accordion-style-1" id="accordion7">
                    <div class="card card-default">
                        <div class="card-header">
                            <h4 class="card-title m-0">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion7" href="#colap<?php echo $colap;?>" aria-expanded="false">
                                    <?php echo $row2->kondisi ?> <span class="custom-accordion-plus"></span>
                                </a>
                            </h4>
                        </div>
                        <div id="colap<?php echo $colap;?>" class="collapse" aria-expanded="false" style="height: 0px;">
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
                            <td rowspan="2" align="center" class="align-middle"><a href="<?= base_url("site/login"); ?>" class="btn btn-success">ORDER</a></td>
                            </tr>
                            <tr>
                            <?php
                                foreach ($row3->pricing as $row4):
                            ?>
                                <td><?php echo "Rp. ".number_format($row4['harga'], 2, ',', '.') ?></td>
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
                <br><br>

            </div>
        </div>
    </div>
</section>

<section class="looking-for section-primary">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2 col-lg-3">
                <div class="looking-for-box">
                    <h2> Don't have account?</h2>
                    <p class="mb-4 mb-md-0">Please register with easy step</p>
                </div>
            </div>
            <div class="col-md-3 d-flex left-content-md-end mb-4 mb-md-0">
                <a class="text-decoration-none" href="<?=base_url("site/register");?>">
					<span class="custom-call-to-action white-border text-color-light">
                    <span class="action-info"><input type="submit" value="Register Now" class="btn  custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase" style="border:solid #fff 2px"></span>
					</span>
                </a>
            </div>
            <div class="col-md-3 col-lg-4">
                <div class="looking-for-box">
                    <h2>First Announcement</h2>
                    <p class="mb-4 mb-md-0">More info about nasional symposium & workshop</p>
                </div>
            </div>
            <div class="col-md-2 d-flex justify-content-md-end mb-4 mb-md-0">
                <a class="text-decoration-none" href="<?= base_url('themes/porto'); ?>/pengumuman/Announcement.pdf" target="_blank">
                    <span class="custom-call-to-action white-border text-color-light">
                    <span class="action-info"><input type="submit" value=Download class="btn  custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase" style="border:solid #fff 2px"></span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="custom-section-padding" id="simpo">
    <div class="container">
    
        <div class="row">
            <article class="blog-post col">
                <div class="row">
                    <div class="col-sm-8 col-lg-4">
                        <div class="owl-carousel owl-theme nav-inside float-left mr-4 mb-2" data-plugin-options="{'items': 1, 'margin': 10, 'animateOut': 'fadeOut','autoplay': true}">
                            <div>
                                <img alt="" class="img-thumbnail rounded" src="themes/porto/img/huany.png">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-8">
                        <h2> PRAKATA</h2>    
                        <hr class="solid">
                        <div class="post-infos d-flex">
                            <span class="info posted-by">
                                <span class="post-author font-weight-semibold text-color-dark">
                                   <p>Lajunya sumber informasi yang tidak terbendung di era digital dan peningkatan status sosial ekonomi serta pendidikan, membuat masyarakat semakin sadar dan peduli terhadap status kesehatan mereka. Salah satunya adalah kesehatan kulit selaku organ paling luar dan kelamin sebagai organ dalam yang penting. Paradigma ini mendorong masyarakat untuk lebih proaktif memeriksakan diri apabila mengalami masalah pada kesehatan kulit dan kelaminnya.</p> <br>
                                   <p>Penyakit yang dikonsultasikan pasien pada dokter Spesialis Kulit & Kelamin maupun dokter umum selaku garda terdepan fasilitas kesehatan, bergeser tidak sebatas kosmetik, bahkan hingga penyakit kongenital/herediter pada bayi, dewasa muda, hingga keluhan kulit dan kelamin pada geriatri. Di satu sisi, penanganan dan kewaspadaaan terhadap kelainan sejak dini akan sangat berguna untuk menghindari perburukan penyakit.</p><br>
                                   <p> Berdasarkan uraian tersebut jelas merupakan tantangan tersendiri bagi dokter layanan primer & dokter spesialis kulit kelamin yang menjadi fasilitator dalam memberikan pelayanan setiap harinya berdasarkan Evidence Based Medicine. PERDOSKI memahami hal tersebut, maka dalam rangka P2KB, diadakan simposium dan workshop dengan tema New Paradigm of Management in Pediatric to Geriatric Dermatology and Venereology: Effective Strategies Related to Diagnosis Prevention and Management .
                                   </p><br>
                                   <p> Besar harapan kami agar 2nd East INSDV 2020 yang diselenggarakan di Banjarmasin kali ini  dapat menambah wawasan dan menyegarkan pengetahuan para sejawat dokter spesialis kulit dan kelamin, dokter spesialis lain, dokter umum, serta mahasiswa kedokteran yang berminat guna meningkatkan kualitas pelayanan kesehatan pada masyarakat. Terlebih jika mengingat Kalimantan-Selatan sebagai provinsi dengan jalur hijau yang luas dan sedang dalam perkembangan yang sangat pesat. Dilengkapi dengan destinasi wisata yang khas dan berkembang, tentu menjadi daya Tarik tersendiri. Pun kearifan lokal yang sangat bervariasi baik prosesi adat, budaya, kuliner, & berbagai kerajinan buah tangan tradisional, sehingga Kalimantan-Selatan dapat menjadi salah satu destinasi yang wajib untuk disambangi.
                                   </p><br>
                                   <p>Ketua Panitia</p><br>
                                   <p><b>dr. Huany Wongdjaja, Sp.KK , M.Kes </b></p>

                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>

                        

                </div>
            </div>
        </div>
    </div>
