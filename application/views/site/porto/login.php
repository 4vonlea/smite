<section class="page-header page-header-modern bg-color-quaternary page-header-sm custom-page-header">
    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1>Login</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                    <li><a href="<?=base_url('site');?>">Home</a></li>
                    <li class="active">Login</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="custom-section-padding">
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
                                    <input type="submit" value="Login Now" name="login"
                                    class="btn btn-outline custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase"
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
                    </div>
                </div>
                <div class="alert alert-primary">
                    Forget Password ? <a href="<?= base_url("site/forget"); ?>" class="text-light">Click Here</a> <br/>
                    Don't have an account yet ? <a href="<?= base_url("member/register"); ?>" class="text-light">Click Here</a><br/>
                </div>
            </div>
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
                    <span class="align-middle thumb-info thumb-info-no-borders thumb-info-no-borders-rounded thumb-info-lighten thumb-info-centered-info thumb-info-block thumb-info-block-dark mx-1 my-1">
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
                                <img class="img-fluid px-4" src="<?= base_url('themes/uploads/sponsor'); ?>/<?= $silver->logo?>" style="" alt="">
                            </a>
                        </center>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
