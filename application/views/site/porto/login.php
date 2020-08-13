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
                <div class="alert alert-success">
                    Forget Password ? <a href="<?= base_url("site/forget"); ?>">Click Here</a> <br/>
                    Don't have an account yet ? <a href="<?= base_url("member/register"); ?>">Click Here</a><br/>
                </div>
            </div>
        </div>
    </div>
</section>
