<section class="page-header page-header-modern bg-color-quaternary page-header-md custom-page-header">
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1>Downloads</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                    <li><a href="<?=base_url('site/home');?>">Home</a></li>
                    <li class="active">Login</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="custom-section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                                        <div class="row mb-3">
                    <div class="col-lg-6">
                 
                    
                    <form class="custom-contact-form-style-1" action="" method="POST">
                        <div class="form-row">
                            <div class="form-group col">
                                <div class="custom-input-box">
                                    <i class="icon-user icons text-color-primary"></i>
                                    <input type="text" value="" data-msg-required="Please enter your Email." maxlength="100" class="form-control" name="email"   placeholder="Email" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <div class="custom-input-box">
                                    <i class="icon-key icons text-color-primary"></i>
                                    <input type="password" value="" data-msg-required="Please enter your password." data-msg-password="Password can not empty." maxlength="100" class="form-control" name="password"  placeholder="Password" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <input type="submit" value="Login Now" class="btn btn-outline custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase" data-loading-text="Loading...">
                            </div>
                        </div>
                    </form>
                    </div>
                    </div>
                    <div class="alert alert-success">
                        Forget Password ? <a href="<?=base_url("site/forget");?>">Click Here</a> <br />
                        Don't have an account yet ?  <a href="<?=base_url("site/forget");?>">Click Here</a><br />
                    </div>
                </div>
        </div>
</div>
</div>
</section>
