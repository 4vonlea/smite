<section class="page-header page-header-modern bg-color-quaternary page-header-md custom-page-header">
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1>Forget Password</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                    <li><a href="<?=base_url('site/home');?>">Home</a></li>
                    <li class="active">Forget Password</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="custom-section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-lg-offset-2">
                    
                        <div class="row">
                            <label class="col-lg-2 control-label"></label>
                            <div class="col-lg-5">
                                <h2 class="font-weight-bold">Forget Password Form</h2>
                            </div>
                        </div>
                    <form id="defaultForm" method="post" class="form-horizontal" action="" >
                    
                        
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="email" />
                            </div>
                        </div>

                        <hr />
                        <div class="form-group row">
                        <label class="col-lg-2 control-label"></label>
                            <div class="col-lg-5 col-lg-offset-3">
                                <button type="submit" class="btn btn-outline custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase" >Submit</button>
                                <button type="button" class="btn btn-outline custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase" id="resetBtn" style="border-color:red;color:red">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
</div>
</section>