<?php
/**
 * @var Member_m $user
 */
?>
    <div id="app">
        <section class="page-header page-header-modern bg-color-quaternary page-header-md custom-page-header">
            <div class="container">
                <div class="row mt-3">
                    <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                        <h1>Member Area</h1>
                    </div>
                    <div class="col-md-4 order-1 order-md-2 align-self-center">
                        <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                            <li><a href="<?= base_url('site/home'); ?>">Home</a></li>
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
                        <div class="profile-image-outer-container" >
                            <div class="profile-image-inner-container bg-color-primary" onclick="$('#file-profile').click();">
                                <img :src="image_link">
                                <span class="profile-image-button bg-color-dark">
                                    <i class="fas fa-camera text-light"></i>
                                </span>
                            </div>
                            <input id="file-profile" style="width: 0px" accept="image/*" @change="uploadImage" type="file" ref="file" class="profile-image-input">
                        </div>
                    </div>

                    <aside class="sidebar mt-2" id="sidebar">
                        <ul class="nav nav-list flex-column mb-5">
                            <li class="nav-item"><router-link active-class="active" class="nav-link text-dark" to="/profile">My Profile</router-link></li>
                            <li class="nav-item"><router-link active-class="active" class="nav-link text-dark" to="/paper">Submit Paper</router-link></li>
                            <li class="nav-item"><router-link active-class="active" class="nav-link text-dark" to="/events">Events</router-link></li>
                            <li class="nav-item"><router-link active-class="active" class="nav-link text-dark" to="/billing">Transaction & Cart</router-link></li>
                            <li class="nav-item"><a class="nav-link text-dark" href="<?= base_url('member/area/logout'); ?>">Logout</a></li>
                        </ul>
                    </aside>

                </div>

                <router-view active-class="active" ></router-view>
            </div>
        </div>
    </div>
<?php $this->layout->begin_script(); ?>
    <script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>

    <script src="<?= base_url("themes/script/vue-router.min.js"); ?>"></script>
    <script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>

    <script type="module">
        import progressPage from "<?= base_url("themes/script/progressPage.js"); ?>";
        import PageProfile from "<?= base_url("member/area/page/profile"); ?>";
        import PageEvents from "<?= base_url("member/area/page/events"); ?>";
        import PagePaper from "<?= base_url("member/area/page/paper"); ?>";
        import PageBilling from "<?= base_url("member/area/page/billing"); ?>";

        var userD = <?=json_encode(array_merge($user->toArray(),['status_member'=>$user->status_member->kategory]));?>;
        Vue.use(VueRouter);
        const routes = [
            {path: '/', component: PageProfile,meta:{'title':'My Profile'}},
            {path: '/profile', component: PageProfile,meta:{'title':'My Profile'}},
            {path: '/events', component: PageEvents,meta:{'title':'Events'}},
            {path: '/paper', component: PagePaper,meta:{'title':'Submit Paper'}},
            {path: '/billing', component: PageBilling,meta:{'title':'Billing & Invoice'}},
        ];
        let router = new VueRouter({
            //mode: 'history',
            routes // short for `routes: routes`
        });

        Vue.mixin({
            data:function () {
                return {
                    baseUrl:"<?=base_url('member/area');?>/",
                    user:userD,
                }
            }
        })

        var app = new Vue({
            router,
            'el': '#app',
            data: {
                image_link:"<?= $user->getImageLink(); ?>",
            },
            methods:{
                uploadImage(){
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
                            if(response.status)
                                app.image_link = response.link;
                            else
                                Swal.fire('Failed',response.message,'warning');
                        },
                        error: function (response) {
                            Swal.fire('Failed',response.message,'error');
                        }
                    });
                }
            }
        }).$mount('#app');

    </script>
<?php $this->layout->end_script(); ?>
