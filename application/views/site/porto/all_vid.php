<section class="page-header page-header-modern bg-color-dark page-header-sm custom-page-header">
    <div class="container container">
        <div class="row mt-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1>Galeri Foto & Video</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                    <li><a href="<?=base_url('site');?>">Beranda</a></li>
                    <li class="active">Galeri Foto & Video</li>
                </ul>
            </div>
        </div>
    </div>
</section>



<div class="container py-2">

    <div class="row">
        <div class="col">
            <?php 
            $sess = $this->session->userdata('user_session');
            if (empty($sess)){
                echo 
                "<div class='alert alert-warning text-center'>
                Anda harus login untuk vote foto & video
                </div>";
            }
            ?>

            <ul class="nav nav-pills sort-source sort-source-style-3 justify-content-center" data-sort-id="portfolio" data-option-key="filter" data-plugin-options="{'layoutMode': 'fitRows', 'filter': '*'}">
                <li class="nav-item active" data-option-value="*"><a class="nav-link text-1 text-uppercase active" href="#">Show All</a></li>
                <li class="nav-item" data-option-value=".1"><a class="nav-link text-1 text-uppercase" href="#">Video</a></li>
                <li class="nav-item" data-option-value=".2"><a class="nav-link text-1 text-uppercase" href="#">Foto</a></li>
            </ul>

            <div class="sort-destination-loader sort-destination-loader-showing mt-4 pt-2">
                <div class="row portfolio-list sort-destination" data-sort-id="portfolio">

                    <?php foreach ($query as $key): ?>

                        <div class="col-12 col-sm-6 col-lg-3 isotope-item <?php echo $key->type; ?>">
                            <div class="portfolio-item">
                                <span class="text-color-dark font-weight-normal text-4 mb-2 line-height-2 appear-animation" data-appear-animation = "fadeInUp" data-appear-animation-delay = "600"><strong class="font-weight-extra-bold"><?php echo $key->title ?></strong></span>
                                <?php if ($key->type == '2') { ?>
                                    <span class="thumb-info thumb-info-lighten border-radius-0">
                                        <span class="thumb-info thumb-info-no-borders thumb-info-no-borders-rounded thumb-info-centered-icons">
                                            <img src="<?= base_url(); ?>themes/uploads/video/<?php echo $key->filename; ?>" class="img-fluid border-radius-0">
                                            <span class="thumb-info-action">
                                                <a href="<?php echo base_url('site/seevideo/'.$key->id) ?>">
                                                    <span class="thumb-info-action-icon thumb-info-action-icon-primary" title=""><i class="fas fa-search"></i></span>
                                                </a>
                                            </span>
                                        </span>
                                        
                                    </span>
                                <?php } else { ?>
                                    <div class="contai">
                                        <iframe class="responsive-iframe" allowfullscreen="true" src="<?= base_url(); ?>themes/uploads/video/<?php echo $key->filename; ?>"></iframe>
                                    </div>
                                <?php } ?>
                                <p>
                                    <a onclick="javascript:savelike(<?php echo $key->id;?>);">
                                        <?php
                                        if (!empty($sess)) {
                                            echo "<i class='far fa-thumbs-up' style='color: #00B297FF'></i>";
                                        }
                                        ?>
                                    
                                        <span id="like_<?php echo $key->id;?>">
                                            <!-- <?php if($key->likesbantu > 0){echo $key->likesbantu.' Likes';}else{echo '0 Like';} ?> -->
                                            <?php
                                            if (!empty($sess)) {
                                                if ($key->ini > 0) {
                                                    echo "<i>disukai</i>";
                                                }
                                            }
                                            ?>
                                        </span>
                                        </a>
                                        <span class="float-right">
                                            <a href="<?php echo base_url('site/seevideo/'.$key->id) ?>">
                                                <?php if($key->komen > 0){echo $key->komen.' Komentar';}else{echo 'Komentar';} ?>
                                            </a>
                                        </span>
                                    </p>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function savelike(video_id)
        {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('site/savelikes');?>",
                data: "Video_id="+video_id,
                success: function (response) {
                 $("#like_"+video_id).html(response);
             }
         });
        }
    </script>

