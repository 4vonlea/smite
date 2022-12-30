<div class="cs-height_95 cs-height_lg_65"></div>
<div class="container">
    <div class="cs-page_heading wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 1s; animation-delay: 0.3s; animation-name: fadeInUp;">
        <ol class="breadcrumb cs-m0">
            <li class="breadcrumb-item"><a href="<?=base_url();?>">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Semua Berita</li>
        </ol>
        <div class="cs-height_10 cs-height_lg_10"></div>
        <h1 class="cs-font_30 cs-m0 text-uppercase">Semua Berita</h1>
    </div>
    <div class="cs-height_50 cs-height_lg_40"></div>
    <div class="row wow fadeIn" data-wow-duration="1s" data-wow-delay="0.45s" style="visibility: visible; animation-duration: 1s; animation-delay: 0.45s; animation-name: fadeIn;">
    <?php foreach($allnews as $news):?>
        <div class="col-lg-3 col-sm-6">
            <div class="cs-blog cs-style1">
                <a href="<?=base_url('site/readnews/'.$news->id);?>" class="cs-blog_thumb cs-zoom_effect">
                    <img src="<?=$news->imageCover();?>" alt="Thumb" class="w-100">
                </a>
                <div class="cs-height_15 cs-height_lg_15"></div>
                <h2 class="cs-blog_title cs-font_22 cs-font_18_sm cs-m0"><a href="<?=base_url('site/readnews/'.$news->id);?>"><?=$news->title;?></a></h2>
                <div class="cs-height_5 cs-height_lg_5"></div>
                <!-- <div class="cs-blgo_subtitle">There are many variations of pass ges of Lorem Ipsum available, but the majority have suffered alterat.</div> -->
                <div class="cs-height_10 cs-height_lg_10"></div>
                <a href="<?=base_url('site/readnews/'.$news->id);?>" class="cs-blog_btn cs-primary_font cs-primary_color cs-accent_color_hover">
                    Selengkapnya
                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.5303 6.53033C15.8232 6.23744 15.8232 5.76256 15.5303 5.46967L10.7574 0.696699C10.4645 0.403806 9.98959 0.403806 9.6967 0.696699C9.40381 0.989593 9.40381 1.46447 9.6967 1.75736L13.9393 6L9.6967 10.2426C9.40381 10.5355 9.40381 11.0104 9.6967 11.3033C9.98959 11.5962 10.4645 11.5962 10.7574 11.3033L15.5303 6.53033ZM0 6.75H15V5.25H0V6.75Z" fill="currentColor"></path>
                    </svg>
                </a>
            </div>
            <div class="cs-height_50 cs-height_lg_50"></div>
        </div>
        <?php endforeach;?>
    </div>
    <div class="cs-height_20 cs-height_lg_20"></div>
</div>