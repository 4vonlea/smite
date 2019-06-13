<section class="page-header page-header-modern bg-color-quaternary page-header-md custom-page-header">
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1>Certificate</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                    <li><a href="<?=base_url('site/home');?>">Home</a></li>
                    <li class="active">Certificate</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <div class="alert alert-info alert-dismissable step1">
        <i class="fa fa-info"></i>
        <b>Attention</b>
        To get form questionnaire , please login with 6 digits code on the ID card.<br>
        <b>Certificate must be downoaded before July 15, 2019</b>
    </div>
    <div class="input-group mb-3 ">
        <input type="text" class="form-control" id="code" placeholder="entry Code" aria-label="entry Code" aria-describedby="button-addon2">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="getPeserta()">Submit</button>
        </div>
    </div>

</div>