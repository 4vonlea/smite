<section class="page-header page-header-modern page-header-sm custom-page-header" style="background-color: #d4af37;">
    <div class="container">
        <div class="row">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1 class="text-color-dark font-weight-bold">Reset Password</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-dark">
                    <li><a href="<?=base_url('site');?>" class="text-color-dark">Beranda</a></li>
                    <li class="active">Reset Password</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="custom-section-padding mt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-lg-offset-2">
                <div class="row">
                    <label class="col-lg-2 control-label"></label>
                    <div class="col-lg-6">
                        <p class="">SPlease enter the email address you registered to reset your password</p>
                        <?php echo $this->session->flashdata('message');?>
                    </div>
                </div>

                <form id="defaultForm" method="post" class="form-horizontal" action="<?php echo base_url('site/forget_reset') ?>" >
                    <div class="form-group row">

                        <label class="col-lg-2 control-label"><b>Email</b></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="username" required="" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 control-label"></label>
                        <div class="d-button">
                            <button type="submit" class="btn btn-primary custom-border-width custom-border-radius font-weight-semibold text-uppercase" >Reset</button>
                        </div>
                    </div>
                </form>
            </div>
            </hr>
        </div>
    </div>
</section>