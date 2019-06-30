<section class="page-header page-header-modern bg-color-quaternary page-header-md custom-page-header">
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1>Downloads</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                    <li><a href="<?=base_url('site/home');?>">Home</a></li>
                    <li class="active">Register</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="custom-section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-lg-offset-2">
                
                    <div class="alert alert-info alert-dismissable alert-hotel">
                        <i class="fa fa-info"></i>
                        <b>Attention</b>
                        Make sure the e-mail address you entered is exists and that you can open it, because we will send the activation code to activate your account to your e-mail. Before being activated, your account cannot be used yet.          
                    </div>
                    <form id="defaultForm" method="post" class="form-horizontal" action="" >    
                        <div class="form-group row">
                            <label class="col-lg-3 control-label">Email</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="email" />
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-3 control-label">Password</label>
                            <div class="col-lg-5">
                                <input type="password" class="form-control" name="password" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 control-label">Confirm Password</label>
                            <div class="col-lg-5">
                                <input type="password" class="form-control" name="confirmPassword" />
                            </div>
                        </div>
                        <hr />
                        
                        <div class="form-group row">
                            <label class="col-lg-3 control-label">Full Name</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="namaanggota" />
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <label class="col-lg-3 control-label">Address</label>
                            <div class="col-lg-5">
                                <textarea  class="form-control" name="alamat"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-3 control-label">City</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="kota" />
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-3 control-label">Telpon</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="telp" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 control-label">Sex</label>
                            <div class="col-lg-5">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="jk" value="L" /> Male
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="jk" value="P" /> Female
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-3 control-label">Birthday</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="tgllahir" /> (YYYY/MM/DD)
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 control-label" id="captchaOperation"></label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" name="captcha" />
                                <input type="hidden" class="form-control" name="jenisanggota" value="P" />
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row">
                        <label class="col-lg-3 control-label"></label>
                            <div class="col-lg-5 col-lg-offset-3">
                                <button type="submit" class="btn btn-outline custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase" >Register</button>
                                <button type="button" class="btn btn-outline custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase" id="resetBtn" style="border-color:red;color:red">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
</div>
</div>
</section>
