<?php
/**
 * @var Member_m $user
 */
$config = $this->config->item("midtrans");
$client_key = $config['client_key'];
?>
<?php $this->layout->begin_head();?>
<style>
	.nav-list > li > a.active {
		background-color: white;
		color: black !important;
	}
	.kingster-fixed-navigation.kingster-style-fixed{
		z-index: 1 !important;
	}
	.modal-backdrop{
		z-index: 1 !important;
	}
	.btn-primary{
		color: white !important;
	}
</style>
<?php $this->layout->end_head();?>
<div class="kingster-page-title-wrap  kingster-style-custom kingster-left-align">
	<div class="kingster-header-transparent-substitute"></div>
	<div class="kingster-page-title-overlay"></div>
	<div class="kingster-page-title-bottom-gradient"></div>
	<div class="kingster-page-title-container kingster-container">
		<div class="kingster-page-title-content kingster-item-pdlr" style="padding-bottom: 60px ;">
			<div class="kingster-page-caption" style="font-size: 21px ;font-weight: 400 ;letter-spacing: 0px ;">
				Participate, Manage profile, and Submit Paper
			</div>
			<h1 class="kingster-page-title"
				style="font-size: 48px ;font-weight: 700 ;text-transform: none ;letter-spacing: 0px ;color: #ffffff ;">
				Member Area</h1></div>
	</div>
</div>
<div id="app">

	<div class="kingster-page-wrapper" id="kingster-page-wrapper">
		<div class="kingster-content-container kingster-container">
			<div class=" kingster-sidebar-wrap clearfix kingster-line-height-0 kingster-sidebar-style-both">
				<div class=" kingster-sidebar-center kingster-column-45 kingster-line-height">
					<div class="gdlr-core-page-builder-body">
						<div class="gdlr-core-pbf-section">
							<div class="gdlr-core-pbf-section-container gdlr-core-container clearfix">
								<div class="gdlr-core-pbf-element">
									<div class="gdlr-core-blog-item gdlr-core-item-pdb clearfix  gdlr-core-style-blog-full"
										style="padding-bottom: 40px ;">
										<router-view active-class="active"></router-view>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="kingster-sidebar-left kingster-column-15 kingster-line-height kingster-line-height">

					<div class="kingster-sidebar-area kingster-item-pdlr">
						<div id="text-6" class="widget widget_text">
							<div class="d-flex justify-content-center mb-4">
								<div class="profile-image-outer-container text-center">
									<img :src="image_link" class="img img-thumbnail">
									<button class="gdlr-core-excerpt-read-more gdlr-core-button gdlr-core-rectangle" onclick="$('#file-profile').click();">
										Change Profile Photo
									</button>
									<input id="file-profile" accept="image/*" @change="uploadImage" type="file" ref="file" style="display: none">
								</div>
							</div>
						</div>
						<div id="recent-posts-3" class="widget widget_recent_entries kingster-widget">
							<h3 class="kingster-widget-title" style="margin-bottom: 0px">Navigation</h3><span class="clear"></span>
							<ul class="nav nav-list flex-column mb-5" style="background-color: #3db166">
								<li class="nav-item">
									<router-link active-class="active" class="nav-link text-light" to="/profile">My
										Profile
									</router-link>
								</li>
								<li class="nav-item">
									<router-link active-class="active" class="nav-link text-light" to="/paper">Submit
										Paper
									</router-link>
								</li>
								<li class="nav-item">
									<router-link active-class="active" class="nav-link text-light" to="/events">Events
									</router-link>
								</li>
								<li class="nav-item">
									<router-link active-class="active" class="nav-link text-light" to="/billing">
										Transaction &
										Cart
									</router-link>
								</li>
								<li class="nav-item"><a class="nav-link text-light"
														href="<?= base_url('member/area/logout'); ?>">Logout</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->layout->begin_script(); ?>
<script type="text/javascript"
		src="https://app.midtrans.com/snap/snap.js"
		data-client-key="<?= $client_key; ?>"></script>
		https://sandbox-kit.espay.id/public/signature/js 
<script src="https://sandbox-kit.espay.id/public/signature/js"></script>

<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>

<script src="<?= base_url("themes/script/vue-router.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>

<script type="module">
	import progressPage from "<?= base_url("themes/script/progressPage.js"); ?>";
	import PageProfile from "<?= base_url("member/area/page/profile"); ?>";
	import PageEvents from "<?= base_url("member/area/page/events"); ?>";
	import PagePaper from "<?= base_url("member/area/page/paper"); ?>";
	import PageBilling from "<?= base_url("member/area/page/billing"); ?>";

	var userD = <?=json_encode(array_merge($user->toArray(), ['status_member' => $user->status_member->kategory]));?>;
	Vue.use(VueRouter);
	const routes = [
		{path: '/', component: PageProfile, meta: {'title': 'My Profile'}},
		{path: '/profile', component: PageProfile, meta: {'title': 'My Profile'}},
		{path: '/events', component: PageEvents, meta: {'title': 'Events'}},
		{path: '/paper', component: PagePaper, meta: {'title': 'Submit Paper'}},
		{path: '/billing', component: PageBilling, meta: {'title': 'Transaction & Cart'}},
	];
	let router = new VueRouter({
		//mode: 'history',
		routes // short for `routes: routes`
	});

	Vue.mixin({
		data: function () {
			return {
				appUrl: "<?=base_url();?>",
				baseUrl: "<?=base_url('member/area');?>/",
				user: userD,
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
