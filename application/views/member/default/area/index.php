<?php
/**
 * @var Member_m $user
 */
$config = $this->config->item("midtrans");
$client_key = $config['client_key'];
$userDetail = array_merge($user->toArray(), ['status_member' => $user->status_member->kategory]);
?>
<link href="<?= base_url(); ?>themes/script/chosen/chosen.css" rel="stylesheet">
<link href="<?= base_url(); ?>themes/script/magnific/magnific.css" rel="stylesheet">
<link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" />
<style>
    .btn:disabled{
        cursor: not-allowed;
    }
    .tab-content > table.active {
        display: table;
    }
</style>
<div id="app">
	<section class="page-header page-header-modern bg-color-quaternary page-header-md custom-page-header">
		<div class="container pt-5">
			<div class="row mt-3">
				<div class="col-md-8 order-2 order-md-1 align-self-center p-static">
					<h1>Member Area</h1>
				</div>
				<div class="col-md-4 order-1 order-md-2 align-self-center">
					<ul class="breadcrumb d-block text-md-right breadcrumb-light">
						<li><a href="<?= base_url('site'); ?>">Home</a></li>
						<li>Member Area</li>
						<li class="active">{{ $route.meta.title }}</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<div class="container py-2">
		<div class="row">
			<div class="col-lg-3 mt-4 mt-lg-0">

				<div class="d-flex justify-content-center mb-4">
					<div class="profile-image-outer-container">
						<div class="profile-image-inner-container bg-color-primary"
							 onclick="$('#file-profile').click();">
							<img :src="image_link">
							<span class="profile-image-button bg-color-dark">
                                    <i class="fas fa-camera text-light"></i>
                                </span>
						</div>
						<input id="file-profile" style="width: 0px" accept="image/*" @change="uploadImage" type="file"
							   ref="file" class="profile-image-input">
					</div>
				</div>

				<aside class="sidebar mt-2" id="sidebar">
					<ul class="nav nav-list flex-column mb-5">

						<li class="nav-item">
							<router-link active-class="active" class="nav-link text-dark" to="/profile">Profil</router-link>
						</li>
						<li class="nav-item">
							<router-link active-class="active" class="nav-link text-dark" to="/paper">Kirim Manuskrip
							</router-link>
						</li>
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center mt-2 pl-2 mb-1 text-muted">
                            <span>Pembelian</span>
                        </h6>
						<li class="nav-item">
							<router-link active-class="active" class="nav-link text-dark" to="/events">Pilih Acara
							</router-link>
						</li>
						<li class="nav-item">
							<router-link active-class="active" class="nav-link text-dark" to="/billing">Keranjang & Pembayaran
							</router-link>
						</li>
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center mt-2 pl-2 mb-1 text-muted">
                            <span>On Event</span>
                        </h6>
                        <li class="nav-item">
							<router-link active-class="active" class="nav-link text-dark" to="/webminar">Webinar Link
							</router-link>
                        </li>
                        <?php if(in_array($userDetail['status'],$statusToUpload)):?>
                        <li class="nav-item">
							<router-link active-class="active" class="nav-link text-dark" to="/material">Upload Materi/Bahan
							</router-link>
                        </li>
                        <?php endif;?>
                        <li class="nav-item">
							<router-link active-class="active" class="nav-link text-dark" to="/sertifikat">Download Sertifikat
							</router-link>
                        </li>
                        <li class="nav-item">
							<router-link active-class="active" class="nav-link text-dark" to="/presentation">Daftar Presentasi Ilmiah
							</router-link>
                        </li>
						<li class="nav-item mt-4"><a class="nav-link text-dark"
												href="<?= base_url('member/area/logout'); ?>">Logout</a></li>
					</ul>
				</aside>

			</div>

			<router-view active-class="active"></router-view>
		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<!-- <script type="text/javascript"
		src="https://app.midtrans.com/snap/snap.js"
		data-client-key="<?=$client_key;?>"></script> -->
<?php if(isset(Settings_m::getEspay()['jsKitUrl'])):?>
<script src="<?=Settings_m::getEspay()['jsKitUrl'];?>"></script>
<?php endif;?>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>

<script src="<?= base_url("themes/script/vue-router.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuetable2.js"); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
<script src="<?=base_url("themes/script/chosen/chosen.jquery.min.js");?>"></script>
<script src="<?=base_url("themes/script/chosen/vue-chosen.js");?>"></script>
<script src="<?=base_url("themes/script/magnific/magnific.js");?>"></script>
<script src="<?=base_url("themes/script/moment-tz.js");?>"></script>
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

    var userD = <?=json_encode($userDetail);?>;
    Vue.use(VueRouter);
    const routes = [
        {path: '/', component: PageProfile, meta: {'title': 'My Profile'}},
        {path: '/profile', component: PageProfile, meta: {'title': 'My Profile'}},
        {path: '/events', component: PageEvents, meta: {'title': 'Events'}},
        {path: '/paper', component: PagePaper, meta: {'title': 'Submit Paper'}},
        {path: '/billing', component: PageBilling, meta: {'title': 'Transaction & Cart'}},
        {path: '/webminar', component: PageWebminar, meta: {'title': 'Webminar'}},
        {path: '/material', component: PageMaterial, meta: {'title': 'Material Upload'}},
        {path: '/sertifikat', component: PageSertifikat, meta: {'title': 'Download Sertifikat'}},
        {path: '/presentation', component: PagePresentation, meta: {'title': 'Presentation'}},
    ];
    let router = new VueRouter({
        //mode: 'history',
        routes // short for `routes: routes`
    });

    document.addEventListener('contextmenu', event => {
        if(router.currentRoute.path != "/presentation")
            event.preventDefault()}
    );

    Vue.mixin({
        data: function () {
            return {
                appUrl:"<?=base_url();?>",
                baseUrl: "<?=base_url('member/area');?>/",
                user: userD,
                apiKeyEspay:"<?=Settings_m::getEspay()['apiKey'];?>",
            }
        }
    })

    var app = new Vue({
        router,
        'el': '#app',
        data: {
            image_link: "<?= $user->getImageLink(); ?>",
        },
        methods: {
            uploadImage() {
                var file_data = this.$refs.file.files[0];
                var form_data = new FormData();
                form_data.append('file', file_data);
                $.ajax({
                    url: '<?=base_url('member/area/upload_image');?>', // point to server-side controller method
                    dataType: 'JSON', // what to expect back from the server
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (response) {
                        if (response.status)
                            app.image_link = response.link;
                        else
                            Swal.fire('Failed', response.message, 'warning');
                    },
                    error: function (response) {
                        Swal.fire('Failed', response.message, 'error');
                    }
                });
            }
        }
    }).$mount('#app');

</script>
<?php $this->layout->end_script(); ?>
