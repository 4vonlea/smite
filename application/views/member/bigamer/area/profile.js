var PageProfile = Vue.component("PageProfile", {
    template: `
<div class="achievement-area-copy">
    <page-loader :loading="loading" :fail="fail"></page-loader>
    <div v-if="!loading && !fail">
        <div class="row">
            <div class="col-md-1">
                <div class="field-set" style="color:#F4AD39;">
                    <img :src="image_link" id="click_profile_img" style="height:80px" class="d-banner-img-edit img-fluid" alt="" onclick="$('#file-profile').click();">
                    <input id="file-profile" accept="image/*" @change="uploadImage" type="file" ref="file" style="display: none">
                </div>
            </div>
            <div class="col-md-7">
                <div class="overflow-hidden mb-1">
                    <h2 class="font-weight-normal color-heading text-7 mb-0"><strong class="font-weight-extra-bold">My Profile</strong></h2>
                </div>
                <div class="overflow-hidden mb-4 pb-3">
                    <p class="mb-0">Your current profile. You can edit your profile by clicking the "edit profile" button.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="float-end">
                    <button @click="[editing = !editing]" class="btn btn-success">
                        <i class="fa fa-edit"></i> {{ editing ? 'Undo Changes':'Edit Profile' }}
                    </button>
                    <button data-bs-toggle="modal" data-bs-target="#reset-password" class="btn btn-success"><i class="fa fa-key"></i> Change Password</button>
                </div>
            </div>
        </div>
        <div v-if="countFollowed == 0" class="alert alert-info text-center">
            <h4 class="mb-0 text-dark">There are no events that you follow</h4>
        </div>
        
         <div v-if="user.verified_by_admin == 0" class="alert alert-info">
            <h4>Your status is being reviewed</h4>
            <p>The current administrator needs to review and approve your status. Please come back to check your status later.
            You will be sent an email when a decision has been made, and you <strong> you cannot participate in the event until your status is accepted</strong></p>
        </div>
        <form role="form" class="needs-validation form-border" method="post">
            <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading" >Status as (read only)</label>
                <div class="col-lg-9">
                    <input disabled="true" class="form-control"  type="text" :value="user.status_member">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 required color-heading">Full Name with title (read only)</label>
                <div class="col-lg-9">
                    <input :disabled="!editing" class="form-control" readonly required="" type="text" v-model="user.fullname">
                </div>
            </div>
            <!-- <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 required color-heading">Gender</label>
                <div class="col-lg-5">
                    <div class="radio">
                        <label>
                            <input :disabled="!editing" type="radio" name="gender" v-model="user.gender" value="M"/> Male
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input :disabled="!editing" type="radio" name="gender" v-model="user.gender" value="F"/> Female
                        </label>
                    </div>
                </div>
            </div> -->
            <!-- <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 required color-heading">Date of birth</label>
                <div class="col-lg-9">
                    <vuejs-datepicker :disabled="!editing" input-class="form-control"
                                    v-model="user.birthday"
                                      wrapper-class="wrapper-datepicker"
                                      name="birthday"></vuejs-datepicker>
                </div>
            </div> -->
            <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 required color-heading">Phone/ Whats App Number</label>
                <div class="col-lg-9">
                    <input :disabled="!editing" type="text" v-model="user.phone" class="form-control" name="phone"/>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 required color-heading">Email</label>
                <div class="col-lg-9">
                    <input :disabled="!editing" class="form-control" required="" type="email" v-model="user.email">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading" >Country</label>
                <div class="col-lg-9 light-select">
                    <vue-chosen :disabled="!editing"  v-model="user.country" :options="country_list" placeholder="Choose Country"></vue-chosen>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading" >City</label>
                <div class="col-lg-9">
                    <input :disabled="!editing" class="form-control" type="text" v-model="user.city" placeholder="City">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading" >Institution/ Affiliation</label>
                <div class="col-lg-9 light-select">
                    <vue-chosen :disabled="!editing"  v-model="user.univ" :options="univ_list" placeholder="Choose one"></vue-chosen>
                </div>
            </div>

            <!-- <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading" >Address</label>
                <div class="col-lg-9">
                    <textarea :disabled="!editing" class="form-control" v-model="user.address" rows="4" style="background-color: transparent;"></textarea>
                </div>
            </div> -->  

            <div class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 required color-heading">Sponsor (if any)</label>
                <div class="col-lg-9">
                    <input :disabled="!editing" class="form-control" required="" type="text" v-model="user.sponsor">
                </div>
            </div>
            
            <div v-if="editing" class="form-group row mb-3">
                <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2" ></label>
                <div class="form-group col-lg-9">
                    <button @click="[editing = false]"  type="button" class="btn btn-secondary"> Cancel</button>
                    <button v-if="editing" @click="saveProfile" type="button" class="btn btn-primary" style="margin-right: 5px;"> Save</button>
                </div>
            </div>
            
        </form>
        <div v-if="countFollowed == 0" class="row">
        	<div class="col-md-12 text-right">    	
				<router-link active-class="active" class="btn btn-primary" to="/events">Click here to choose your event participation</router-link>            
			</div>
		</div>
        <div id="reset-password" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <!-- Modal content-->
                <div class="modal-content" style="background-color: #212428;">
                    <div class="modal-header">
                        <h4 class="modal-title">Reset Password</h4>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="form-reset">
                            <div class="form-group mb-2">
                                <label>New Password</label>
                                <input :class="{'is-invalid':reset.new_password}" type="password" class="form-control" name="new_password" placeholder="New Password"/>
                                <div v-if="reset.new_password" class="invalid-feedback">{{ reset.new_password }}</div>
                            </div>
                            <div class="form-group mb-2">
                                <label>Confirm Password</label>
                                <input :class="{'is-invalid':reset.confirm_password}" type="password" class="form-control" name="confirm_password" placeholder="Confirm Password"/>
                                <div v-if="reset.confirm_password" class="invalid-feedback">{{ reset.confirm_password }}</div>
                            </div>
                            <div class="form-group mb-2">
                                <label>Old Password</label>
                                <input :class="{'is-invalid':reset.old_password}" type="password" class="form-control" name="old_password" placeholder="Old Password"/>
                                <div v-if="reset.old_password" class="invalid-feedback">{{ reset.old_password }}</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" :disabled="processReset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" @click="resetPassword" :disabled="processReset" class="btn btn-primary" ><i v-if="processReset" class="fa fa-spin fa-spinner"></i> Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
`,
    components: {
        vuejsDatepicker
    },
    data: function () {
        return {
            loading: false,
            fail: false,
            user: {},
            editing: false,
            processReset: false,
            reset: {},
            countFollowed: 0,
            univ_list: [],
            country_list: [],
        }
    },
    created() {
        this.fetchCountFollowed()
    },
    watch: {
        '$route': 'fetchCountFollowed'
    },
    methods: {
        fetchCountFollowed() {
            var page = this;
            page.loading = true;
            page.fail = false;
            $.post(this.baseUrl + "count_followed_events", null, function (res) {
                if (res.status) {
                    page.countFollowed = res.count;
                    page.univ_list = res.univ;
                    page.country_list = res.country;
                } else {
                    page.fail = true;
                }
            }).fail(function () {
                page.fail = true;
            }).always(function () {
                page.loading = false;
            });
        },
        saveProfile() {
            var page = this;
            this.loading = true;
            this.user.birthday = moment(this.user.birthday).format("YYYY-MM-DD");
            $.post(this.baseUrl + "save_profile", this.user, function (res) {
                if(res.status){
                    Swal.fire('Success', "Your profile saved successfully", 'success');
                    page.editing = false;
                }else{
                    Swal.fire('Failed',res.message, 'warning');
                }
            }, 'JSON').fail(function (xhr) {
                Swal.fire('Fail', "Failed to save your profile", 'error');
            }).always(function () {
                page.loading = false;
            });
        },
        uploadImage() {
            var file_data = this.$refs.file.files[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                url: this.baseUrl+'upload_image', // point to server-side controller method
                dataType: 'JSON', // what to expect back from the server
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status)
                        app.image_link = response.link+`?t=${new Date().getTime()}`;
                    else
                        Swal.fire('Failed', response.message, 'warning');
                },
                error: function(response) {
                    Swal.fire('Failed', response.message, 'error');
                }
            });
        },
        resetPassword() {
            var page = this;
            var data = $("#form-reset").serializeArray();
            page.processReset = true;
            $.post(this.baseUrl + "reset_password", data, function (res) {
                if (res.status) {
                    $("#reset-password").modal("hide");
                    Swal.fire('Success', "Your password reset successfully", 'success');
                } else {
                    page.reset = res;
                }
            }, 'JSON').fail(function () {
                Swal.fire('Fail', "Failed to process !", 'error');
            }).always(function () {
                page.processReset = false;
            });
        }
    },
});
export default PageProfile;
