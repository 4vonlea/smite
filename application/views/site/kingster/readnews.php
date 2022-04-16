<div class="kingster-breadcrumbs">
    <div class="kingster-breadcrumbs-container kingster-container">
        <div class="kingster-breadcrumbs-item kingster-item-pdlr"> <span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Go to Home." href="<?=base_url();?>" class="home"><span property="name">Home</span></a>
            <meta property="position" content="1">
        </span>&gt;<span property="itemListElement" typeof="ListItem"><span property="name">News</span>
        <meta property="position" content="2">
    </span>
</div>
</div>
</div>

<div class="gdlr-core-pbf-column gdlr-core-column-60 gdlr-core-column-first">
    <div class="gdlr-core-item-list-wrap gdlr-core-column-60">
        <div class="gdlr-core-item-list-wrap">
            <div class="gdlr-core-item-list gdlr-core-blog-widget gdlr-core-item-mglr clearfix gdlr-core-style-small"> 
                <?php
                foreach ($news as $key):
                    ?>  
                    <div class="gdlr-core-blog-widget-content">
                        <!-- <div class="gdlr-core-blog-info-wrapper gdlr-core-skin-divider"> -->
                            <span class="gdlr-core-blog-title gdlr-core-skin-title" id="h3_1dd7_12">
                                <p><?php echo $key->title ?></p>
                            </span>
                            <!-- </div> -->
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
