<section class="page-header page-header-modern bg-color-quaternary page-header-sm custom-page-header">
    <div class="container container mt-5 pt-5">
        <div class="row mt-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1>Read News</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                    <li><a href="<?=base_url('site');?>">Home</a></li>
                    <li class="active">Read News</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="custom-section-padding">
	<div class="container">
		<div class="row">
			<div class="">
				<div> 
					<?php
					foreach ($news as $key):
						?>  
						<div class="gdlr-core-blog-widget-content">
							<span>
								<h4><?php echo $key->title ?></h4>
							</span>
							<p style="font-size:11px" >
								<?php echo $key->content ?>
							</p>
							<hr>
						</div>
						<?php
					endforeach;
					?>  
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
