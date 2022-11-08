<?php

/**
 * @var array $statusList
 * @var array $univDl
 */
$this->layout->begin_head();
?>
<link href="<?= base_url(); ?>themes/script/chosen/chosen.css" rel="stylesheet">

<?php $this->layout->end_head(); ?>
<div class="header bg-primary pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<!-- Card stats -->
			<div class="row">
				<div class="col-xl-6 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Total Members</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_members }}</span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
										<i class="fas fa-chart-bar"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-6 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Unverified Members</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_unverified }}</span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-warning text-white rounded-circle shadow">
										<i class="fas fa-chart-pie"></i>
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
<!-- Page content -->
<div class="container-fluid mt--7">
	<!-- Table -->
	<div v-if="profileMode==0" class="row">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h3>Members</h3>
						</div>
						<div class="col-6 text-right">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-particant-status"><i class="fa fa-book"></i> Member Status
								List
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<datagrid @loaded_data="loadedGrid" ref="datagrid" api-url="<?= base_url('admin/member/grid'); ?>" :fields="[{name:'fullname',sortField:'fullname'}, {name:'email',sortField:'email'}, {name:'kta',sortField:'kta',title:'Status Anggota'},{name:'created_at',title:'Registered At',sortField:'created_at'},{name:'id',title:'Actions',titleClass:'action-th'}]">
						<template slot="email" slot-scope="prop">
							{{ prop.row.email }}
							<span v-if="prop.row.verified_email == 0" class="badge badge-warning">Unverified</span>
							<button @click="resendVerification(prop.row.email)" v-if="prop.row.verified_email == 0" class="badge badge-info">Resend Verification</button>
						</template>
						<template slot="verified_by_admin" slot-scope="prop">
							<span :class="[(prop.row.verified_by_admin == 1 ?'badge-success':'badge-warning')]" class="badge">{{ (prop.row.verified_by_admin == 1 ?'Verified':'Unverified') }}</span>
						</template>
						<template slot="created_at" slot-scope="prop">
							{{ formatDate(prop.row.created_at) }}
						</template>
						<template slot="kta" slot-scope="prop">
							<span v-if="prop.row.kta && prop.row.kta != '-'" class="badge badge-success">Anggota</span>
							<span v-else-if="!prop.row.kta" class="badge badge-info">Belum Sync</span>
							<span v-else class="badge badge-danger">Umum</span>
						</template>
						<template slot="id" slot-scope="props">
							<div class="table-button-container">
								<button v-if="props.row.verified_by_admin == 0" @click="openVerifyModal(props)" class="btn btn-warning btn-sm">
									<span class="fa fa-pen"></span> Verify
								</button>
								<button :disabled="syncId == props.row.id" class="btn btn-primary btn-sm" @click="sync(props.row,$event)">
									<span class="fa fa-sync"></span> Sync
									<i v-if="syncId == props.row.id" class="fa fa-spin fa-spinner"></i>
								</button>
								<v-button class="btn btn-primary btn-sm" icon="fa fa-search" @click="detail(props.row,$event)">
									Detail
								</v-button>
								<button class="btn btn-primary btn-sm" @click="edit(props)">
									<span class="fa fa-edit"></span> Edit
								</button>
								<button class="btn btn-danger btn-sm" @click="deleteMember(props,$event)">
									<span class="fa fa-trash"></span> Delete
								</button>
								<a class="btn btn-primary btn-sm" :href="'<?= base_url('admin/notification/index'); ?>/'+props.row.id" target="_blank">
									<span class="fa fa-envelope"></span> Email
								</a>
							</div>
						</template>
					</datagrid>
				</div>
			</div>
		</div>
	</div>
	<div v-else-if="profileMode == 3" class="row">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<h3>Profile Member</h3>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label class="form-check-label">NIK</label>
						<div class="input-group">
							<input type="text" class="form-control" v-model="profile.nik" />
							<div class="input-group-append">
								<button @click="checkMember" :disabled="checkingMember" class="btn btn-outline-primary" type="button">
									Cek NIK di Database P2KB
									<i v-if="checkingMember" class="fa fa-spin fa-spinner"></i>
								</button>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="form-check-label">Email</label>
						<input type="text" class="form-control" v-model="profile.email" />
					</div>
					<div class="form-group">
						<label class="form-check-label">Status</label>
						<select class="form-control" v-model="profile.status">
							<option v-for="status in statusList" :value="status.id">{{status.kategory}}</option>
						</select>
					</div>
					<div class="form-group">
						<label class="form-check-label">KTA</label>
						<input type="text" class="form-control" v-model="profile.kta" />
					</div>
					<div class="form-group">
						<label class="form-check-label">Alternatif Status (Optional)</label>
						<input type="text" class="form-control" v-model="profile.alternatif_status" />
					</div>
					<div class="form-group">
						<label class="form-check-label">Alternatif Status 2 (Optional)</label>
						<input type="text" class="form-control" v-model="profile.alternatif_status2" />
					</div>
					<div class="form-group">
						<label class="form-check-label">Fullname</label>
						<input type="text" class="form-control" v-model="profile.fullname" />
					</div>
					<div class="form-group">
						<label class="form-check-label">Gender</label>
						<div class="radio">
							<label>
								<input type="radio" name="gender" v-model="profile.gender" value="M" /> Male
							</label>
							<label>
								<input type="radio" name="gender" v-model="profile.gender" value="F" /> Female
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="form-check-label">Phone</label>
						<input type="text" class="form-control" v-model="profile.phone" />
					</div>
					<div class="form-group">
						<label class="form-check-label">City</label>
						<input type="text" class="form-control" readonly v-model="profile.city_name" />
					</div>
					<div class="form-group">
						<label class="form-check-label">Address</label>
						<textarea type="text" class="form-control" v-model="profile.address"></textarea>
					</div>
					<div class="form-group">
						<label class="form-check-label">Institution</label>
						<vue-chosen v-model="profile.univ" :options="institutionList" placeholder="Select Institution"></vue-chosen>
					</div>
					<div class="form-group">
						<label class="form-check-label">Sponsor</label>
						<textarea type="text" class="form-control" v-model="profile.sponsor"></textarea>
					</div>
				</div>
				<div class="card-footer text-right">
					<button @click="saveProfile" class="btn btn-default" :disabled="savingProfile">
						<i v-if="savingProfile" class="fa fa-spin fa-spinner"></i>
						Save
					</button>
					<button @click="profileMode=0" class="btn btn-default">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div v-else class="row">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<h3>Profile Member</h3>
				</div>
				<table class="table table-bordered">
					<tr>
						<th>Status As</th>
						<td colspan="2">{{ profile.statusName }}</td>
						<td rowspan="3">
							<img class="img img-thumbnail" :src="profile.imageLink" style="max-height: 200px" />
						</td>
					</tr>
					<tr>
						<th>KTA</th>
						<td colspan="2">{{ profile.kta }}</td>
					</tr>
					<tr>
						<th>
							Alternatif Status
						</th>
						<td colspan="2">
							<ol>
								<li>{{ profile.alternatif_status }}</li>
								<li>{{ profile.alternatif_status2 }}</li>
							</ol>
						</td>
					</tr>
					<tr>
						<th>NIK</th>
						<td colspan="2">{{ profile.nik }}</td>
					</tr>
					<tr>
						<th>Full Name</th>
						<td colspan="2">{{ profile.fullname }}</td>
					</tr>
					<tr>
						<th>Email</th>
						<td colspan="2">{{ profile.email }}</td>
					</tr>
					<tr>
						<th>Username Account</th>
						<td colspan="2">{{ profile.username_account }}</td>
					</tr>
					<tr>
						<th>Phone/WA</th>
						<td colspan="3">{{ profile.phone }}</td>
					</tr>
					<tr>
						<th>Gender</th>
						<td colspan="3">{{ (profile.gender == 'M' ?'Male':'Female') }}</td>

					</tr>
					<tr>
						<th>City</th>
						<td colspan="3">{{ profile.city_name }}</td>
					</tr>
					<tr>
						<th>Instutution</th>
						<td colspan="3">{{ profile.univ_nama }}</td>
					</tr>
					<tr>
						<th>Address</th>
						<td colspan="3">{{ profile.address }}</td>
					</tr>
					<tr>
						<th>Sponsor</th>
						<td colspan="3">{{ profile.sponsor }}</td>
					</tr>
					<tr v-if="profile.event">
						<th>Followed Event</th>
						<td colspan="3">
							<table class="table">
								<tr>
									<th rowspan="2">Event Name</th>
									<th colspan="3">Administration</th>
								</tr>
								<tr>
									<th>Name Tag</th>
									<th>Seminar Kit</th>
									<th>Certificate</th>
									<th>Taker</th>
								</tr>
								<tr v-for="ev in profile.event">
									<td>
										{{ ev.event_name }} <br />
										<v-button @click="sendNametag(ev,$event)" class="btn btn-primary btn-sm">Send Nametag</v-button>
										<button :disabled="sendingCertificate" v-on:click="sendCertificate(ev)" class="btn btn-primary btn-sm"><i v-if="sendingCertificate" class="fa fa-spin fa-spinner"></i>Send Certificate</button>
										<a :href="'<?= base_url('admin/member/card'); ?>/'+ev.event_id+'/'+profile.id" target="_blank">Preview Name Tag</a> 
										<a :href="'<?= base_url('admin/member/preview_certificate'); ?>/'+ev.event_id+'/'+profile.id" target="_blank">Preview Certificate</a> 
									</td>
									<td>
										<input type="checkbox" v-model="ev.checklist.nametag" true-value="true" false-value="false" />
									</td>
									<td>
										<input type="checkbox" v-model="ev.checklist.seminarkit" true-value="true" false-value="false" />
									</td>
									<td>
										<input type="checkbox" v-model="ev.checklist.certificate" true-value="true" false-value="false" />
									</td>
									<td>
										<input type="text" v-model="ev.checklist.taker" class="form-control" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div class="card-footer text-right">
					<button @click="saveChecklist" class="btn btn-default" :disabled="savingCheck">
						<i v-if="savingCheck" class="fa fa-spin fa-spinner"></i>
						Save Check
					</button>
					<button @click="profileMode=0" class="btn btn-default">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="modal-particant-status">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Member Status List</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="form-group">
					<div class="input-group">
						<input v-model="new_status" type="text" class="form-control" @keyup.enter="addStatus" placeholder="New Member Status" />
						<div class="input-group-append">
							<button type="button" class="btn btn-primary" @click="addStatus"><i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>Need Verification</th>
							<th>Is Hide</th>
							<th>Status Name</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(cat,index) in statusList">
							<td>
								<input type="checkbox" v-model="cat.need_verify" true-value="1" false-value="0" @click="needVerification(index)" />
							</td>
							<td>
								<input type="checkbox" v-model="cat.is_hide" true-value="1" false-value="0" @click="hideStatus(index)" />
							</td>
							<td>
								{{ cat.kategory }}
							</td>
							<td>
								<button @click="removeStatus(index)" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
							</td>
						</tr>
					</tbody>
				</table>

			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>

<div class="modal" id="modal-verification">
	<div class="modal-dialog">
		<div class="modal-content table-responsive">
			<div class="modal-header">
				<h4>Verify Status</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div v-if="verifyMessage" class="alert alert-danger">
				{{ verifyMessage }}
			</div>
			<table class="table">
				<tr>
					<th>Full Name</th>
					<td>{{ verifyModel.fullname }}</td>
				</tr>
				<tr>
					<th>Status Submitted As</th>
					<td>{{ verifyModel.statusSubmitted }}</td>
				</tr>
				<tr>
					<th>Proof</th>
					<td>
						<img :src="verifyModel.proofLink" class="img" />
					</td>
				</tr>
				<tr>
					<th>Your Response</th>
					<td>
						<div class="form-check">
							<input type="radio" name="response" value="1" class="form-check-input" v-model="verifyModel.response" />
							<label class="form-check-label">Agree</label>
						</div>
						<div class="form-check">
							<input type="radio" name="response" value="0" class="form-check-input" v-model="verifyModel.response" />
							<label class="form-check-label">Disagree</label>
						</div>
					</td>
				</tr>
				<tr v-if="verifyModel.response == 0">
					<th>Set Status As</th>
					<td>
						<select name="set_status" class="custom-select custom-select-sm" v-model="verifyModel.status">
							<option v-for="st in statusList" :value="st.id">{{ st.kategory }}</option>
						</select>
					</td>
				</tr>
			</table>
			<div class="modal-footer">
				<button :disabled="verifying" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button :disabled="verifying" type="button" class="btn btn-primary" @click="verify">
					<i v-if="verifying" class="fa fa-spin fa-spinner"></i>
					Confirm
				</button>
			</div>
		</div>
	</div>
</div>

<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/chosen/chosen.jquery.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/chosen/vue-chosen.js"); ?>"></script>
<script src="<?= base_url("themes/script/v-button.js"); ?>"></script>
<script>
	var tempStatus = <?= json_encode($statusList); ?>;

	function postStatus(cat) {
		return $.post('<?= base_url('admin/member/add_status'); ?>', {
			value: cat
		});
	}

	var app = new Vue({
		el: '#app',
		data: {
			new_status: '',
			statusList: <?= json_encode($statusList); ?>,
			institutionList: <?= json_encode($univDl); ?>,
			verifyModel: {},
			verifying: false,
			verifyMessage: null,
			pagination: {},
			profileMode: 0,
			profile: {},
			savingCheck: false,
			savingProfile: false,
			sendingCertificate: false,
			checkingMember:false,
			syncId:null,
		},
		methods: {
			sync(member){
				this.syncId = member.id;
				$.post("<?=base_url("admin/member/cek_member_perdossi");?>",{
					nik:member.nik,
					id:member.id
				}, (res) => {
                    if (res.message == "success") {
                        Swal.fire('Info', `Keanggotaan berhasil dicek. <br/> Data KTA, Nama dan No Telpon telah disinkronkan`, 'info');
                        member.kta = res.member.perdossi_no;
                        member.fullname = `${res.member.member_title_front} ${res.member.fullname} ${res.member.member_title_back}`;
                        member.phone = res.member.member_phone;
                    } else {
                        member.kta = "-";
                        Swal.fire('Info', `${member.fullname} dengan NIK.${member.nik} : ${res.message}`, 'info');
                    }
                }).always(() => {
                    this.syncId = null;
                }).fail(() => {
                    Swal.fire('Fail', 'Failed to get member information in perdossi API', 'error')
                })
			},
			checkMember() {
                this.checkingMember = true;
                $.get("<?=base_url("member/register/info_member_perdossi/");?>" + this.profile.nik, (res) => {
                    if (res.message == "success") {
                        Swal.fire('Info', `Keanggotaan berhasil dicek. <br/> Data KTA, Nama, Email dan No Telpon telah diubah<br/> Silakah disimpan`, 'info');
                        this.profile.kta = res.member.perdossi_no;
                        this.profile.fullname = `${res.member.member_title_front} ${res.member.fullname} ${res.member.member_title_back}`;
                        this.profile.email = res.member.email;
                        this.profile.phone = res.member.member_phone;
                    } else {
                        Swal.fire('Info', `NIK.${this.profile.nik} : ${res.message}`, 'info');
                    }
                }).always(() => {
                    this.checkingMember = false;
                }).fail(() => {
                    Swal.fire('Fail', 'Failed to get member information in perdossi API', 'error')
                })
            },
			formatDate(date) {
				return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
			},
			resendVerification(email) {
				$.post("<?= base_url("admin/member/resend_verification"); ?>", {
					email: email
				}, function(res) {
					if (res.status)
						Swal.fire("Success", "Verification email sent !", "success");
					else
						Swal.fire('Fail', res.message, 'error');
				}, "JSON").fail(function(xhr) {
					Swal.fire('Fail', "Server gagal memproses !", 'error');
				}).always(function() {
					app.sendingCertificate = false;
				});
			},
			sendNametag(event,self) {
				var data = {
					id: event.event_id,
					event_name: event.event_name,
					m_id:this.profile.id,
				}
				self.toggleLoading();
				$.post("<?= base_url("admin/member/send_nametag"); ?>", data, function(res) {
					if (res.status)
						Swal.fire("Success", "Nametag sended !", "success");
					else
						Swal.fire("Failed", res.message, "error");
				}, "JSON").fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					self.toggleLoading();
				});
			},
			sendCertificate(event) {
				app.sendingCertificate = true;
				var data = {
					fullname: event.fullname,
					email: event.email,
					gender: event.gender,
					status_member: event.member_status,
					id: event.event_id,
					event_name: event.event_name,
					alternatif_status: event.alternatif_status,
					alternatif_status2: event.alternatif_status2,
					m_id:this.profile.id,
				}
				$.post("<?= base_url("admin/member/send_certificate"); ?>", data, function(res) {
					if (res.status)
						Swal.fire("Success", "Certificate sended !", "success");
					else
						Swal.fire("Failed", res.message, "error");
				}, "JSON").fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					app.sendingCertificate = false;
				});
			},
			saveProfile() {
				app.savingProfile = true;
				$.post("<?= base_url("admin/member/save_profile"); ?>", app.profile, function(res) {
					if (res.status){
						app.profile.username_account = app.profile.email;
						Swal.fire("Success", "Profile Saved !", "success");
					}else
						Swal.fire("Failed", (res.message ? res.message : "Failed to save data !"), "error");
				}, "JSON").fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					app.savingProfile = false;
				});
			},
			deleteMember(prop, event) {
				var btn = event.currentTarget;
				Swal.fire({
					title: "Are you sure ?",
					text: `You will delete "${prop.row.fullname}" From member`,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
						btn.setAttribute("disabled", true);
						$.post("<?= base_url("admin/member/delete"); ?>", prop.row, function(res) {
							if (res.status) {
								Swal.fire("Success", "Member deleted successfully", "success");
								app.$refs.datagrid.refresh();
							} else
								Swal.fire("Failed", res.message, "error");
						}, "JSON").fail(function(xhr) {
							var message = xhr.getResponseHeader("Message");
							if (!message)
								message = 'Server fail to response !';
							Swal.fire('Fail', message, 'error');
						}).always(function() {
							btn.innerHTML = '<i class="fa fa-trash"></i> Delete';
							btn.removeAttribute("disabled");
						});
					}
				});
			},
			edit(prop) {
				this.profile = prop.row;
				this.profileMode = 3;
			},
			saveChecklist() {
				app.savingCheck = true;
				var data = [];
				$.each(app.profile.event, function(i, r) {
					var t = r.checklist;
					data.push({
						id: r.td_id,
						checklist: t
					});
				});

				$.post("<?= base_url("admin/member/save_check"); ?>", {
					transaction: data
				}, function(res) {
					Swal.fire("Success", "Checklist saved !", "success");
				}, "JSON").fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					app.savingCheck = false;
				});
			},
			detail(profile, self) {
				$.each(this.statusList, function(i, v) {
					if (v.id == profile.status)
						profile.statusName = v.kategory;
				});
				if (profile.image) {
					profile.imageLink = `<?= base_url('themes/uploads/profile'); ?>/${profile.image}`;
				} else {
					profile.imageLink = `<?= base_url('themes/uploads/people.jpg'); ?>`;
				}
				self.toggleLoading();

				$.post("<?= base_url("admin/member/get_event"); ?>", {
					id: profile.id
				}, function(res) {
					profile.event = res;
					profile.birthdayFormatted = moment(profile.birthday).format('DD MMM YYYY');
					app.profileMode = 1;
					app.profile = profile;
				}, "JSON").fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					self.toggleLoading();

				});
			},
			verify() {
				this.verifying = true;
				$.post("<?= base_url('admin/member/verify'); ?>", this.verifyModel, function(res) {
					if (res.status) {
						$("#modal-verification").modal("hide");
						Swal.fire("Success", "Member has been verified !", "success");
						app.$refs.datagrid.reload();
					} else
						app.verifyMessage = res.message;
				}, 'JSON').fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
					$("#modal-verification").modal("hide");
				}).always(function() {
					app.verifying = false;
				});
			},
			openVerifyModal(prop) {
				this.verifyMessage = null;
				$.each(this.statusList, function(i, v) {
					if (v.id == prop.row.status)
						prop.row.statusSubmitted = v.kategory;
				});
				prop.row.proofLink = "<?= base_url('admin/member/get_proof'); ?>/" + prop.row.id;
				this.verifyModel = prop.row;
				$("#modal-verification").modal("show");
			},
			addStatus: function() {
				if (this.new_status != "") {
					tempStatus.push({
						"kategory": this.new_status
					});
					postStatus(tempStatus).done(function(res) {
						app.statusList = res;
						tempStatus = JSON.parse(JSON.stringify(res));
						app.new_status = "";
					}).fail(function(xhr) {
						tempStatus.pop();
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					});
				}

			},
			removeStatus: function(index) {
				var value = this.statusList[index];
				$.post("<?= base_url('admin/member/remove_status'); ?>", {
					id: value.id
				}, function(res) {
					if (res.status)
						app.statusList.splice(index, 1);
				}, 'JSON').fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				});
			},
			hideStatus: function(index) {
				var value = this.statusList[index];
				value.is_hide = (value.is_hide == 1 ? 0 : 1);
				$.post("<?= base_url('admin/member/verification_status'); ?>", value, function(res) {

				}, 'JSON').fail(function(xhr) {
					value.is_hide = (value.is_hide == 1 ? 0 : 1);
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				});
			},
			needVerification: function(index) {
				var value = this.statusList[index];
				value.need_verify = (value.need_verify == 1 ? 0 : 1);
				$.post("<?= base_url('admin/member/verification_status'); ?>", value, function(res) {

				}, 'JSON').fail(function(xhr) {
					value.need_verify = (value.need_verify == 1 ? 0 : 1);
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				});
			},
			loadedGrid: function(data) {
				this.pagination = data;
			}
		},
		mounted() {
			<?php if (isset($_GET['q'])) : ?>
				this.$refs.datagrid.globalFilter = "<?= $_GET['q']; ?>";
				this.$refs.datagrid.doFilter();
			<?php endif; ?>
		}
	});
</script>
<?php $this->layout->end_script(); ?>