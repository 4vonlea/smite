<?php

/**
 * @var Member_m $user
 */
$theme_path = base_url("themes/gigaland") . "/";
$config = $this->config->item("midtrans");
$client_key = $config['client_key'];
$userDetail = array_merge($user->toArray(), ['status_member' => $user->status_member->kategory]);
$this->layout->begin_head();
?>
<link href="<?= base_url(); ?>themes/script/chosen/chosen.css" rel="stylesheet">
<link href="<?= base_url(); ?>themes/script/magnific/magnific.css" rel="stylesheet">
<link href="<?= $theme_path; ?>css/custom.css" rel="stylesheet">
<link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" />
<style>
    .btn:disabled {
        cursor: not-allowed;
    }

    .tab-content>table.active {
        display: table;
    }

    .color-heading {
        color: #F4AD39 !important;
    }
    .cover {
        position:absolute;
        padding:20px;
        margin:0;
        top:0;
        left:0;
        width: 100%;
        height: 100%;
        background:rgba(0,0,0,0.75);
        z-index: 2000;
        overflow: hidden;
    }
</style>
<?php $this->layout->end_head(); ?>

<div id="app">
    <div class="cover container-fluid" v-if="presentationCover.isShow">
        <div class="row">
            <div class="col-md-11 col-sm-9">
               
            </div>
            <div class="col-md-1 col-sm-3 mb-1">
                <button @click="togglePresentation('#','#')" class="btn btn-danger">Close</button>
            </div>
            <div class="col-12">
                <iframe :src='presentationCover.link' width='100%' height='600px' frameborder='0'></iframe>  
                <audio controls autoplay muted>
                    <source :src="presentationCover.voiceLink" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>              
            </div>
        </div>
    </div>
    <section id="subheader" style="background-size: cover;" class="pb-5">
    </section>
    <div class="container py-2 mb-5" id="content">
        <div class="row">
            <div class="col-lg-12 mt-4 mt-lg-0">
                <div class="de_tab tab_simple">
                    <div class="de_tab_content">
                        <div class="tab-1">
                            <div class="row wow fadeIn">
                                <!-- <div class="col-lg-2">
                                    <div class="field-set" style="color:#F4AD39;">
                                        <img :src="image_link" id="click_profile_img" class="d-banner-img-edit img-fluid" alt="" onclick="$('#file-profile').click();">
                                        <input id="file-profile" accept="image/*" @change="uploadImage" type="file" ref="file" style="display: none">
                                    </div>
                                </div> -->
                                <div class="col-lg-12 mb-sm-20" style="background-size: cover;">
                                    <div class="field-set" style="background-size: cover;">
                                        <router-view active-class="active"></router-view>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->layout->begin_script(); ?>
<!-- <script type="text/javascript"
		src="https://app.midtrans.com/snap/snap.js"
		data-client-key="<?= $client_key; ?>"></script> -->
<?php if (isset(Settings_m::getEspay()['jsKitUrl'])) : ?>
    <script src="<?= Settings_m::getEspay()['jsKitUrl']; ?>"></script>
<?php endif; ?>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>

<script src="<?= base_url("themes/script/vue-router.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuetable2.js"); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
<script src="<?= base_url("themes/script/chosen/chosen.jquery.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/chosen/vue-chosen.js"); ?>"></script>
<script src="<?= base_url("themes/script/magnific/magnific.js"); ?>"></script>
<script src="<?= base_url("themes/script/moment-tz.js"); ?>"></script>
<!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
<script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>

<script type="module">
    Vue.use(Vuetable);

    import progressPage from "<?= base_url("themes/script/progressPage.js"); ?>";
    import PageProfile from "<?= base_url("member/area/page/profile"); ?>";
    import PageEvents from "<?= base_url("member/area/page/events"); ?>";
    import PagePaper from "<?= base_url("member/area/page/paper"); ?>";
    import PageBilling from "<?= base_url("member/area/page/billing"); ?>";
    import PageWebminar from "<?= base_url("member/area/page/webminar"); ?>";
    import PageMaterial from "<?= base_url("member/area/page/material"); ?>";
    import PagePresentation from "<?= base_url("member/area/page/presentation"); ?>";
    import PageSertifikat from "<?= base_url("member/area/page/sertifikat"); ?>";

    var userD = <?= json_encode($userDetail); ?>;
    Vue.use(VueRouter);
    const routes = [{
            path: '/',
            component: PageProfile,
            meta: {
                'title': 'My Profile'
            }
        },
        {
            path: '/profile',
            component: PageProfile,
            meta: {
                'title': 'My Profile'
            }
        },
        {
            path: '/events',
            component: PageEvents,
            meta: {
                'title': 'Events'
            }
        },
        {
            path: '/paper',
            component: PagePaper,
            meta: {
                'title': 'Submit Paper'
            }
        },
        {
            path: '/billing',
            component: PageBilling,
            meta: {
                'title': 'Transaction & Cart'
            }
        },
        {
            path: '/webminar',
            component: PageWebminar,
            meta: {
                'title': 'Webminar'
            }
        },
        {
            path: '/material',
            component: PageMaterial,
            meta: {
                'title': 'Material Upload'
            }
        },
        {
            path: '/sertifikat',
            component: PageSertifikat,
            meta: {
                'title': 'Download Sertifikat'
            }
        },
        {
            path: '/presentation',
            component: PagePresentation,
            meta: {
                'title': 'Presentation'
            }
        },
    ];
    let router = new VueRouter({
        //mode: 'history',
        routes // short for `routes: routes`
    });

    document.addEventListener('contextmenu', event => {
        // if (router.currentRoute.path != "/presentation")
        //     event.preventDefault()
    });

    Vue.mixin({
        data: function() {
            return {
                appUrl: "<?= base_url(); ?>",
                baseUrl: "<?= base_url('member/area'); ?>/",
                user: userD,
                apiKeyEspay: "<?= Settings_m::getEspay()['apiKey']; ?>",
                image_link: "<?= $user->getImageLink(); ?>",

            }
        }
    })

    var app = new Vue({
        router,
        'el': '#app',
        data:{
            presentationCover:{
                isShow:false,
                link:"#",
                voiceLink:"#",
            }
        },
        methods: {
            uploadImage() {
                var file_data = this.$refs.file.files[0];
                var form_data = new FormData();
                form_data.append('file', file_data);
                $.ajax({
                    url: '<?= base_url('member/area/upload_image'); ?>', // point to server-side controller method
                    dataType: 'JSON', // what to expect back from the server
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function(response) {
                        if (response.status)
                            app.image_link = response.link;
                        else
                            Swal.fire('Failed', response.message, 'warning');
                    },
                    error: function(response) {
                        Swal.fire('Failed', response.message, 'error');
                    }
                });
            },
            togglePresentation(filename,voice,id){
                this.presentationCover.isShow = !this.presentationCover.isShow;
                if(this.presentationCover.isShow){
                    this.presentationCover.voiceLink = this.baseUrl+"file_presentation/"+voice+'/'+id;
                    this.presentationCover.link = "https://view.officeapps.live.com/op/view.aspx?src=<?=base_url('application/uploads/papers');?>/"+filename;
                    document.documentElement.style.overflow = "hidden";
                }else{
                    document.documentElement.style.overflow = "scroll";
                    this.presentationCover.voiceLink = "#";
                    this.presentationCover.link = "#";
                }
            }
        }
    }).$mount('#app');
    $(document).ready(function() {
        $(document).on('show.bs.modal', '.modal', function(event) {
            setTimeout(function() {
                $('.modal-backdrop').not('.modal-stack').css('z-index', '-1').addClass('modal-stack');
            }, 0);
        });
    });
</script>
<?php $this->layout->end_script(); ?>