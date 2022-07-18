<section id="home" aria-label="section" class="banner-section bg-img">
    <div class="container">
        <div class="section-wrapper text-center text-uppercase">
            <h2 class="pageheader-title mb-3">Halaman Login</h2>
        </div>
        <div class="row">
            <div class="col-lg-8 offset-lg-2 achievement-area">
                <form id="form-create-item" class="account-form" method="post" action="<?= base_url("site/login"); ?>">
                    <div class="col-12">
                        <?php if ($error != '') : ?>
                            <div class="alert alert-danger">
                                <?= $error; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <input type='text' name='username' id='email' class="form-control" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <input type='password' name='password' id='password' class="form-control" placeholder="Password">
                        </div>
                        <a href="<?= base_url('site/forget'); ?>" class="mb-2">Lupa Password ?</a>
                        <div class="form-group">
                            <button type="submit" name="login" value="Sign" class="btn-edge default-button" name="login">
                                <span>
                                <i class="icofont icofont-sign-in"></i> Masuk Login
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>