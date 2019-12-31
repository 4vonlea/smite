<?php
/**
 * @var array $statusList
 * @var array $univDl
 */
?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
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
							<button type="button" class="btn btn-primary" data-toggle="modal"
									data-target="#modal-particant-status"><i class="fa fa-book"></i> Member Status
								List
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<datagrid
						@loaded_data="loadedGrid"
						ref="datagrid"
						api-url="<?= base_url('admin/member/grid'); ?>"
						:fields="[{name:'fullname',sortField:'fullname'}, {name:'email',sortField:'email'},{name:'verified_by_admin',sortField:'verified_by_admin',title:'Verification'},{name:'created_at',title:'Registered At',sortField:'created_at'},{name:'id',title:'Actions',titleClass:'action-th'}]">
						<template slot="verified_by_admin" slot-scope="prop">
						<span :class="[(prop.row.verified_by_admin == 1 ?'badge-success':'badge-warning')]"
							  class="badge">{{ (prop.row.verified_by_admin == 1 ?'Verified':'Unverified') }}</span>
						</template>
						<template slot="created_at" slot-scope="prop">
							{{ formatDate(prop.row.created_at) }}
						</template>
						<template slot="id" slot-scope="props">
							<div class="table-button-container">
								<button v-if="props.row.verified_by_admin == 0" @click="openVerifyModal(props)"
										class="btn btn-warning btn-sm">
									<span class="fa fa-pen"></span> Verify
								</button>
								<button class="btn btn-primary btn-sm" @click="detail(props.row,$event)">
									<span class="fa fa-search"></span> Detail
								</button>
								<button class="btn btn-primary btn-sm" @click="edit(props)">
									<span class="fa fa-edit"></span> Edit
								</button>
								<button class="btn btn-danger btn-sm" @click="deleteMember(props,$event)">
									<span class="fa fa-trash"></span> Delete
								</button>
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
						<label class="form-check-label">Email</label>
						<input type="text" class="form-control" readonly v-model="profile.email"/>
					</div>
					<div class="form-group">
						<label class="form-check-label">Fullname</label>
						<input type="text" class="form-control" v-model="profile.fullname"/>
					</div>
					<div class="form-group">
						<label class="form-check-label">Gender</label>
						<div class="radio">
							<label>
								<input type="radio" name="gender" v-model="profile.gender" value="M"/> Male
							</label>
							<label>
								<input type="radio" name="gender" v-model="profile.gender" value="F"/> Female
							</label>

						</div>
					</div>
					<div class="form-group">
						<label class="form-check-label">Phone</label>
						<input type="text" class="form-control" v-model="profile.phone"/>
					</div>
					<div class="form-group">
						<label class="form-check-label">City</label>
						<input type="text" class="form-control" v-model="profile.city"/>
					</div>
					<div class="form-group">
						<label class="form-check-label">Address</label>
						<textarea type="text" class="form-control" v-model="profile.address"></textarea>
					</div>
					<div class="form-group">
						<label class="form-check-label">Institution</label>
						<?= form_dropdown("univ",$univDl,"",['v-model'=>'profile.univ','class'=>'form-control']);?>
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
							<img class="img img-thumbnail" :src="profile.imageLink" style="max-height: 200px"/>
						</td>
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
						<th>Phone/WA</th>
						<td colspan="3">{{ profile.phone }}</td>
					</tr>
					<tr>
						<th>Gender</th>
						<td colspan="3">{{ (profile.gender == 'M' ?'Male':'Female') }}</td>

					</tr>
					<tr>
						<th>City</th>
						<td colspan="3">{{ profile.city }}</td>
					</tr>
					<tr>
						<th>Instutution</th>
						<td colspan="3">{{ profile.univ_nama }}</td>
					</tr>
					<tr>
						<th>Address</th>
						<td colspan="3">{{ profile.address }}</td>
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
									<th>Taker</th>
								</tr>
								<tr v-for="ev in profile.event">
									<td>
										{{ ev.event_name }} |
										<a :href="'<?= base_url('admin/member/card'); ?>/'+ev.event_id+'/'+profile.id"
										   target="_blank">Download Name Tag</a>
									</td>
									<td>
										<input type="checkbox" v-model="ev.checklist.nametag" true-value="true"
											   false-value="false"/>
									</td>
									<td>
										<input type="checkbox" v-model="ev.checklist.seminarkit" true-value="true"
											   false-value="false"/>
									</td>
									<td>
										<input type="text" v-model="ev.checklist.taker" class="form-control"/>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div class="card-footer text-right">
					<button @click="saveChecklist" class="btn btn-default" :disabled="savingCheck">
						<i v-if="savingCheck" class="fa fa-spin fa-spinner"></i>
						Save
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
						<input v-model="new_status" type="text" class="form-control" @keyup.enter="addStatus"
							   placeholder="New Member Status"/>
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
						<th>Status Name</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="(cat,index) in statusList">
						<td>
							<input type="checkbox" v-model="cat.need_verify" @click="needVerification(index)"/>
						</td>
						<td>
							{{ cat.kategory }}
						</td>
						<td>
							<button @click="removeStatus(index)" class="btn btn-danger btn-sm"><i
									class="fa fa-times"></i></button>
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
							<input type="radio" name="response" value="1" class="form-check-input"
								   v-model="verifyModel.response"/>
							<label class="form-check-label">Agree</label>
						</div>
						<div class="form-check">
							<input type="radio" name="response" value="0" class="form-check-input"
								   v-model="verifyModel.response"/>
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

<script>
    var tempStatus = <?=json_encode($statusList);?>;

    function postStatus(cat) {
        return $.post('<?=base_url('admin/member/add_status');?>', {value: cat});
    }

    var app = new Vue({
        el: '#app',
        data: {
            new_status: '',
            statusList:<?=json_encode($statusList);?>,
            verifyModel: {},
            verifying: false,
            verifyMessage: null,
            pagination: {},
            profileMode: 0,
            profile: {},
            savingCheck: false,
            savingProfile: false,
        },
        methods: {
			formatDate(date) {
				return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
			},
            saveProfile() {
                app.savingProfile = true;
                $.post("<?=base_url("admin/member/save_profile");?>", app.profile, function (res) {
                    if (res.status)
                        Swal.fire("Success", "Profile Saved !", "success");
                    else
                        Swal.fire("Failed", "Failed to save data !", "error");
                }, "JSON").fail(function () {
                    Swal.fire("Failed", "Failed to load data !", "error");
                }).always(function () {
                    app.savingProfile = false;
                });
            },
            deleteMember(prop,event) {
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
                        btn.setAttribute("disabled",true);
                        $.post("<?=base_url("admin/member/delete");?>", prop.row, function (res) {
                            if (res.status) {
                                Swal.fire("Success", "Member deleted successfully", "success");
                                app.$refs.datagrid.refresh();
                            }else
                                Swal.fire("Failed", res.message, "error");
                        }, "JSON").fail(function () {
                            Swal.fire("Failed", "Failed to load data !", "error");
                        }).always(function () {
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
                $.each(app.profile.event, function (i, r) {
                    var t = r.checklist;
                    data.push({
                        id: r.td_id,
                        checklist: t
                    });
                });

                $.post("<?=base_url("admin/member/save_check");?>", {transaction: data}, function (res) {
                    Swal.fire("Success", "Checklist saved !", "success");
                }, "JSON").fail(function () {
                    Swal.fire("Failed", "Failed to load data !", "error");
                }).always(function () {
                    app.savingCheck = false;
                });
            },
            detail(profile, event, institution) {
                $.each(this.statusList, function (i, v) {
                    if (v.id == profile.status)
                        profile.statusName = v.kategory;
                });
                if (profile.image) {
                    profile.imageLink = `<?=base_url('themes/uploads/profile');?>/${profile.image}`;
                } else {
                    profile.imageLink = `<?=base_url('themes/uploads/people.jpg');?>`;
                }
                event.target.innerHtml = "<i class='fa fa-spin fa-spinner'></i>Loading...";

                $.post("<?=base_url("admin/member/get_event");?>", {id: profile.id}, function (res) {
                    profile.event = res;
                    profile.birthdayFormatted = moment(profile.birthday).format('DD MMM YYYY');
                    app.profileMode = 1;
                    app.profile = profile;
                }, "JSON").fail(function () {
                    Swal.fire("Failed", "Failed to load data !", "error");
                }).always(function () {
                    event.target.innerHtml = "Detail";
                });
            },
            verify() {
                this.verifying = true;
                $.post("<?=base_url('admin/member/verify');?>", this.verifyModel, function (res) {
                    if (res.status) {
                        $("#modal-verification").modal("hide");
                        Swal.fire("Success", "Member has been verified !", "success");
                        app.$refs.datagrid.refresh();
                    } else
                        app.verifyMessage = res.message;
                }, 'JSON').fail(function () {
                    Swal.fire("Failed", "Failed to verify !", "error");
                    $("#modal-verification").modal("hide");
                }).always(function () {
                    app.verifying = false;
                });
            },
            openVerifyModal(prop) {
                this.verifyMessage = null;
                $.each(this.statusList, function (i, v) {
                    if (v.id == prop.row.status)
                        prop.row.statusSubmitted = v.kategory;
                });
                prop.row.proofLink = "<?=base_url('admin/member/get_proof');?>/" + prop.row.id;
                this.verifyModel = prop.row;
                $("#modal-verification").modal("show");
            },
            addStatus: function () {
                if (this.new_status != "") {
                    tempStatus.push({"kategory": this.new_status});
                    postStatus(tempStatus).done(function (res) {
                        app.statusList = res;
                        tempStatus = JSON.parse(JSON.stringify(res));
                        app.new_status = "";
                    }).fail(function () {
                        tempStatus.pop();
                        Swal.fire("Failed", "Failed to save !", "error");
                    });
                }

            },
            removeStatus: function (index) {
                var value = this.statusList[index];
                $.post("<?=base_url('admin/member/remove_status');?>", {id: value.id}, function (res) {
                    if (res.status)
                        app.statusList.splice(index, 1);
                }, 'JSON').fail(function () {
                    Swal.fire("Failed", "Failed to remove !", "error");
                });
            },
            needVerification: function (index) {
                var value = this.statusList[index];
                value.need_verify = !value.need_verify;
                $.post("<?=base_url('admin/member/verification_status');?>", value, function (res) {

                }, 'JSON').fail(function () {
                    value.need_verify = 0;
                    Swal.fire("Failed", "Failed to remove !", "error");
                });
            },
            loadedGrid: function (data) {
                this.pagination = data;
            }
        },
        mounted() {
			<?php if(isset($_GET['q'])):?>
            this.$refs.datagrid.globalFilter = "<?=$_GET['q'];?>";
            this.$refs.datagrid.doFilter();
			<?php endif;?>
        }
    });
</script>
<?php $this->layout->end_script(); ?>
