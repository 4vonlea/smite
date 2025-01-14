var PageProfile = Vue.component("PageProfile", {
	template: `
<div class="col-lg-9">
    <page-loader :loading="loading" :fail="fail"></page-loader>
    <div v-if="!loading && !fail">
        <div class="row">
            <div class="col-md-7">
                <div class="overflow-hidden mb-1">
                    <h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Profil Ku</strong></h2>
                </div>
            </div>
            <div class="col-md-5 text-right">
                <button @click="[editing = !editing]" class="btn btn-default">
                      <i class="fa fa-edit"></i> {{ editing ? 'Batal Edit':'Edit Profil' }}
                </button>
                <button data-toggle="modal" data-target="#reset-password" class="btn btn-default"><i class="fa fa-key"></i> Ganti Password</button>
            </div>
            <div class="col-md-12">
                <div class="overflow-hidden mb-4 pb-3">
                    <p class="mb-0">Profil Anda saat ini, Anda dapat mengedit profil Anda dengan mengklik tombol edit.</p>
                </div>
            </div>
        </div>
        <div v-if="countFollowed == 0" class="alert alert-info text-center">
            <h4>Anda harus memilih sebuah acara</h4>
        </div>
         <div v-if="user.verified_by_admin == 0" class="alert alert-info">
            <h4>Status Anda sedang ditinjau</h4>
            <p>Administrator saat ini perlu meninjau dan menyetujui status Anda. Silakan kembali untuk memeriksa status Anda nanti.
            Anda akan dikirimi email ketika keputusan telah dibuat, dan anda <strong> anda tidak dapat mengikuti acara sebelum status anda diterima</strong></p>
        </div>
        <form role="form" class="needs-validation" method="post">
            <div class="form-group row">
                <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Status Anda Sebagi</label>
                <div class="col-lg-9">
                    <input disabled="true" class="form-control"  type="text" :value="user.status_member">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2 required">Nama</label>
                <div class="col-lg-9">
                    <input :disabled="!editing" class="form-control" readonly required="" type="text" v-model="user.fullname">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2 required">Jenis Kelamin</label>
                <div class="col-lg-5">
                    <div class="radio">
                        <label>
                            <input :disabled="!editing" type="radio" name="gender" v-model="user.gender" value="M"/> Laki-Laki
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input :disabled="!editing" type="radio" name="gender" v-model="user.gender" value="F"/> Perempuan
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2 required">Tangga Lahir</label>
                <div class="col-lg-9">
                    <vuejs-datepicker :disabled="!editing" input-class="form-control"
                                    v-model="user.birthday"
                                      wrapper-class="wrapper-datepicker"
                                      name="birthday"></vuejs-datepicker>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2 required">No Handphone/WA</label>
                <div class="col-lg-9">
                    <input :disabled="!editing" type="text" v-model="user.phone" class="form-control" name="phone"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2 required">Email</label>
                <div class="col-lg-9">
                    <input :disabled="!editing" class="form-control" required="" type="email" v-model="user.email">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Kota Tempat Tinggal</label>
                <div class="col-lg-9">
                    <input :disabled="!editing" class="form-control" type="text" v-model="user.city" placeholder="City">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Institusi</label>
                <div class="col-lg-9">
                    <vue-chosen :disabled="!editing"  v-model="user.univ" :options="univ_list" placeholder="Pilih Institusi"></vue-chosen>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Alamat</label>
                <div class="col-lg-9">
                    <textarea :disabled="!editing" class="form-control" v-model="user.address" rows="4"></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2 required">Sponsor</label>
                <div class="col-lg-9">
                    <input :disabled="!editing" class="form-control" required="" type="text" v-model="user.sponsor">
                </div>
            </div>

            <div v-if="editing" class="form-group row">
                <div class="form-group col-lg-12 text-right">
                    <button @click="[editing = false]"  type="button" class="btn btn-default "> Batal</button>
                    <button v-if="editing" @click="saveProfile" type="button" class="btn btn-primary"> Simpan</button>
                </div>
            </div>
            
        </form>
        <div v-if="countFollowed == 0" class="row">
        	<div class="col-md-12 text-right">    	
				<router-link active-class="active" class="btn btn-primary" to="/events">Choose your event participation</router-link>            
			</div>
		</div>
        <div id="reset-password" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Reset Password</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="form-reset">
                            <div class="form-group">
                                <label>New Password</label>
                                <input :class="{'is-invalid':reset.new_password}" type="password" class="form-control" name="new_password" placeholder="New Password"/>
                                <div v-if="reset.new_password" class="invalid-feedback">{{ reset.new_password }}</div>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input :class="{'is-invalid':reset.confirm_password}" type="password" class="form-control" name="confirm_password" placeholder="Confirm Password"/>
                                <div v-if="reset.confirm_password" class="invalid-feedback">{{ reset.confirm_password }}</div>
                            </div>
                            <div class="form-group">
                                <label>Old Password</label>
                                <input :class="{'is-invalid':reset.old_password}" type="password" class="form-control" name="old_password" placeholder="Old Password"/>
                                <div v-if="reset.old_password" class="invalid-feedback">{{ reset.old_password }}</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" :disabled="processReset" class="btn btn-default" data-dismiss="modal">Close</button>
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
			univ_list:[],
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
			$.post(this.baseUrl + "save_profile", this.user, function () {
				Swal.fire('Success', "Your profile saved successfully", 'success');
				page.editing = false;
			}, 'JSON').fail(function (xhr) {
				Swal.fire('Fail', "Failed to save your profile", 'error');
			}).always(function () {
				page.loading = false;
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
