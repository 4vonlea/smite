<div class="kingster-breadcrumbs">
    <div class="kingster-breadcrumbs-container kingster-container">
        <div class="kingster-breadcrumbs-item kingster-item-pdlr"> <span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Go to Home." href="<?=base_url();?>" class="home"><span property="name">Home</span></a>
            <meta property="position" content="1">
        </span>&gt;<span property="itemListElement" typeof="ListItem"><span property="name">All News</span>
        <meta property="position" content="2">
    </span>
</div>
</div>
</div>

<div class="gdlr-core-pbf-column gdlr-core-column-60 gdlr-core-column-first">
    <div class="gdlr-core-item-list-wrap gdlr-core-column-60">
        <div class="gdlr-core-block-item-title-wrap  gdlr-core-left-align gdlr-core-item-mglr" id="div_1dd7_46">
            <div class="gdlr-core-block-item-title-inner clearfix">
                <h3 class="gdlr-core-block-item-title" id="h3_1dd7_10">News & Updates</h3>
                <div class="gdlr-core-block-item-title-divider" id="div_1dd7_47"></div>
            </div>
        </div>
        <div class="gdlr-core-item-list-wrap">
            <div class="gdlr-core-item-list gdlr-core-blog-widget gdlr-core-item-mglr clearfix gdlr-core-style-small"> 
                <?php
                foreach ($allnews as $key):
                    ?>  
                    <div class="gdlr-core-blog-widget-content">
                        <!-- <div class="gdlr-core-blog-info-wrapper gdlr-core-skin-divider"> -->
                            <span class="gdlr-core-blog-title gdlr-core-skin-title" id="h3_1dd7_12">
                                <p><?php echo $key->title ?></p>
                            </span>
                            <!-- </div> -->
                            <p style="font-size:11px" >
                                <?php echo character_limiter($key->content, 300) ?>
                            </p>
                            <p><a href="<?php echo base_url('site/readnews/'.$key->id) ?>" style="font-size: 11px" class="btn btn-success">read more</a></p>
                            <hr>
                        </div>
                        <?php
                    endforeach;
                    ?>  
                </div>
            </div>
        </div>
    </div>
