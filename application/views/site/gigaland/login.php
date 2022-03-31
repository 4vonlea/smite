        <!-- section begin -->
        <!-- <section id="subheader">
            <div class="center-y relative text-center">
                <div class="container">
                    <div class="row">

                        <div class="col-md-12 text-center">
                            <h1 style="color:#F4AD39;">Registration</h1>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- section close -->

        <!-- section begin -->
        <section id="section-main" aria-label="section" style="color:#F4AD39;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form id="form-create-item" class="form-border" method="post" action="<?=base_url("site/login");?>">
                            <div class="de_tab tab_simple">
                                <h4 style="color:#F4AD39;"><i class="fa fa-home"></i> Login</h4>
                                <div class="de_tab_content">
                                    <div class="tab-1">
                                        <div class="row wow fadeIn">
                                            <div class="col-lg-12 mb-sm-20">
                                                <div class="col-12">
                                                    <?php if ($error != '') : ?>
                                                        <div class="alert alert-danger">
                                                            <?= $error; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="field-set" style="color:#F4AD39;">
                                                    <div class="field-set">
                                                        <input type='text' name='username' id='email' class="form-control" placeholder="Email">
                                                    </div>

                                                    <div class="field-set">
                                                        <input type='password' name='password' id='password' class="form-control" placeholder="Password">
                                                    </div>
                                                    <a href="<?=base_url('site/forget');?>" class="mb-2">Forgot Password ?</a>
                                                    <div class="d-buttons">
                                                        <input type="submit" name="login" value="Sign" class="btn-main btn-fullwidth" name="login" style="background-color:#F4AD39; color:black;"/><br>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>


                                </div>
                            </div>

                            <!-- <div class="spacer-30"></div>
                            <input type="button" style="background-color:#F4AD39; color:black;" id="submit" class="btn-main" value="Register">
                            </form> -->
                    </div>
                </div>
            </div>
        </section>
    </div>


    <a href="#" id="back-to-top"></a>
