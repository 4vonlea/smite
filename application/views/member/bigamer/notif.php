<?php
/**
 * @var array $participantsCategory
 */
$theme_path = base_url("themes/bigamer") . "/";

?>
<section class="pageheader-section" style="background-image: url(<?= $theme_path; ?>assets/images/pageheader/bg.jpg);">
    <div class="container">
        <div class="section-wrapper text-center text-uppercase">
            <h2 class="pageheader-title">Email Confirmation</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Registration</li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<section id="app" class="padding-top padding-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-lg-offset-2">
                <div class="alert btn-purple">
                    <h4 class="text-dark"><i class="icofont icofont-info-circle"></i><?=$title;?></h4>
                    <p><?=$message;?></p>
                </div>
            </div>
        </div>
    </div>
</section>
