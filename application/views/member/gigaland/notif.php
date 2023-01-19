<?php
/**
 * @var array $participantsCategory
 */
?>
<section class="page-header page-header-modern bg-color-quaternary page-header-md custom-page-header">
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1>Email Confirmation</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                    <li><a href="<?= base_url('site/home'); ?>">Home</a></li>
                    <li class="active">Registration</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="app" class="custom-section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-lg-offset-2">
                <div class="alert alert-success">
                    <h4><i class="fa fa-info"></i><?=$title;?></h4>
                    <p><?=$message;?></p>
                </div>
            </div>
        </div>
    </div>
</section>
