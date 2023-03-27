<section>
    <div class="cs-height_70 cs-height_lg_40"></div>
    <div class="container">
        <div class="cs-seciton_heading cs-style1 text-uppercase wow fadeInUp text-center" data-wow-duration="1s" data-wow-delay="0.2s" style="visibility: visible; animation-duration: 1s; animation-delay: 0.2s; animation-name: fadeInUp;">
            <h3 class="cs-section_title cs-font_16 cs-font_14_sm cs-gradient_color">Halaman Login</h3>
            <h2 class="cs-section_subtitle cs-m0 cs-font_36 cs-font_24_sm">Selamat Datang Kembali</h2>
        </div>
        <div class="cs-height_50 cs-height_lg_30"></div>
        <div class="row">
            <div class="col-6 offset-3">
                <form id="form-create-item" class="account-form" method="post" action="<?= base_url("site/login"); ?>">
                    <div class="col-12">
                        <?php if ($error != '') : ?>
                            <div class="alert alert-danger">
                                <?= $error; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="field-set mb-2">
                        <input type="text" name="username" id="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="field-set mb-2">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    </div>
                    <a href="<?= base_url('site/forget'); ?>" class="mb-2">Lupa Password ?</a>
                    <div class="d-grid">
                        <button type="submit" name="login" value="login" class="btn btn-round btn-primary mt-2">
                            <i class="fa-solid fa-sign-in"></i> Sign </button><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>