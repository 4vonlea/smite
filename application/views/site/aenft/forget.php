<section id="home" aria-label="section" class="banner-section bg-img">
    <div class="container">
        <div class="section-wrapper">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="de_tab tab_simple">
                        <h4><i class="fa fa-lock-open"></i> Reset Password</h4>
                        <div class="de_tab_content">
                            <div class="tab-1">
                                <div class="row wow fadeIn">
                                    <div class="col-lg-12 mb-sm-20">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <p class="">Masukkan email yang anda gunakan saat pendaftaran</p>
                                                    <?php echo $this->session->flashdata('message'); ?>
                                                </div>
                                            </div>

                                            <form id="defaultForm" class="form-border" method="post" action="<?php echo base_url('site/forget_reset') ?>">
                                                <div class="field-set">
                                                    <input type='text' name='username' id='email' class="form-control" placeholder="Email">
                                                </div>
                                                <div class="row">
                                                    <div class="d-grid mt-2">
                                                        <button type="submit" class="btn btn-edge btn-info">Reset</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>