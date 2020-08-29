<section class="page-header page-header-modern bg-color-dark page-header-sm custom-page-header">
    <div class="container container">
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