<section class="page-header page-header-modern page-header-sm custom-page-header" style="background-color: #d4af37;">
    <div class="container">
        <div class="row">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1 class="text-color-dark font-weight-bold">Login</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-dark">
                    <li><a href="<?=base_url('site');?>" class="text-color-dark">Beranda</a></li>
                    <li class="active">Login</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="custom-section-padding" style="background-image: url('<?= base_url('themes/porto'); ?>/img/bgjadwal.jpg'); background-repeat: no-repeat; background-size: cover; height: 100%;">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 offset-lg-3">
                <div class="row mb-3">
                    <div class="col-12">
                        <?php if ($error != ''): ?>
                            <div class="alert alert-danger">
                                <?= $error; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <form class="custom-contact-form-style-1" method="POST">
                            <div class="form-row">
                                <div class="form-group col">
                                    <div class="custom-input-box mt-4">
                                        <i class="icon-user icons text-color-dark">  Email</i>
                                        <input type="text" value="" data-msg-required="Please enter your Email."
                                        maxlength="100" class="form-control" name="username" placeholder="Email"
                                        required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <div class="custom-input-box">
                                        <i class="icon-key icons text-color-dark">  Password</i>
                                        <input type="password" value="" data-msg-required="Please enter your password."
                                        data-msg-password="Password can not empty." maxlength="100"
                                        class="form-control" name="password" placeholder="Password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <input type="submit" value="Login Now" name="login"
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
                    </div>
                </div>
                <div class="alert alert-primary mb-5">
                    Lupa Password ? <a href="<?= base_url("site/forget"); ?>" class="text-light"><i>Klik disini</i></a> <br/>
                    Belum punya akun ? <a href="<?= base_url("member/register"); ?>" class="text-light"><i>Klik disini</i></a><br/>
                </div>
            </div>
        </div>
    </div>
</section>