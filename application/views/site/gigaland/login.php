<section id="subheader">
    <div class="center-y relative text-center">
        <div class="container">
            <div class="row">

                <div class="col-md-12 text-center">
                    <h1 style="color:#F4AD39;">Login</h1>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</section>

<section class="no-bottom" data-bgimage="url(<?= base_url('themes/gigaland'); ?>/images/background/13.jpg) bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 offset-lg-3">
                <div class="row mb-3">
                    <div class="col-12">
                        <?php if ($error != '') : ?>
                            <div class="alert alert-danger">
                                <?= $error; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <form class="custom-contact-form-style-1" method="POST">
                            <div class="row mb-3">
                                <div class="form-group col">
                                    <div class="custom-input-box mt-4">
                                        <i class="icon-user icons text-color-dark"> Email</i>
                                        <input type="text" value="" data-msg-required="Please enter your Email." maxlength="100" class="form-control" name="username" placeholder="Email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col">
                                    <div class="custom-input-box">
                                        <i class="icon-key icons text-color-dark"> Password</i>
                                        <input type="password" value="" data-msg-required="Please enter your password." data-msg-password="Password can not empty." maxlength="100" class="form-control" name="password" placeholder="Password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col">
                                    <input type="submit" value="Login Now" name="login" class="btn btn-main" style="background-color: rgb(244, 173, 57); color: black;" data-loading-text="Loading...">
                                </div>
                                <div class="form-group col">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="rememberme" class="form-check-input" />
                                        Ingat saya
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="alert alert-primary mb-5">
                    Lupa Password ? <a href="<?= base_url("site/forget"); ?>" class="text-light"><i>Klik disini</i></a> <br />
                    Belum punya akun ? <a href="<?= base_url("member/register"); ?>" class="text-light"><i>Klik disini</i></a><br />
                </div>
            </div>
        </div>
    </div>
</section>