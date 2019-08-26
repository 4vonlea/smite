<?php
/**
 * @var array $statusList
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
                                    <span class="h2 font-weight-bold mb-0">{{ pagination.total }}</span>
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

                <datagrid
						@loaded_data="loadedGrid"
                        ref="datagrid"
                        api-url="<?= base_url('admin/member/grid'); ?>"
                        :fields="[{name:'fullname',sortField:'fullname'}, {name:'email',sortField:'email'},{name:'gender',sortField:'gender'},{name:'verified_by_admin',sortField:'verified_by_admin',title:'Status Verification'},{name:'id',title:'Actions',titleClass:'action-th'}]">
                    <template slot="verified_by_admin" slot-scope="prop">
						<span :class="[(prop.row.verified_by_admin == 1 ?'badge-success':'badge-warning')]" class="badge">{{ (prop.row.verified_by_admin == 1 ?'Verified':'Unverified') }}</span>
					</template>
					<template slot="id" slot-scope="props">
                        <div class="table-button-container">
                            <button v-if="props.row.verified_by_admin == 0" @click="openVerifyModal(props)" class="btn btn-warning btn-sm">
                                <span class="fa fa-pen"></span> Verify
                            </button>
							<button class="btn btn-primary btn-sm" @click="detail(props.row)">
								<span class="fa fa-zoom"></span> Detail
							</button>
                        </div>
                    </template>

                </datagrid>
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
						<td>{{ profile.statusName }}</td>
						<td rowspan="3" colspan="2">
							<img class="img img-thumbnail" :src="profile.imageLink" />
						</td>
					</tr>
					<tr>
						<th>Full Name</th>
						<td>{{ profile.fullname }}</td>
					</tr>
					<tr>
						<th>Email</th>
						<td>{{ profile.email }}</td>
					</tr>
					<tr>
						<th>Phone/WA</th>
						<td colspan="3">{{ profile.phone }}</td>
					</tr>
					<tr>
						<th>Gender</th>
						<td>{{ (profile.gender == 'M' ?'Male':'Female') }}</td>
						<th>Birthday</th>
						<td>{{ profile.birthdayFormatted }}</td>
					</tr>
					<tr>
						<th>City</th>
						<td colspan="3">{{ profile.city }}</td>
					</tr>
					<tr>
						<th>Address</th>
						<td colspan="3">{{ profile.address }}</td>
					</tr>
				</table>
				<div class="card-footer text-right">
					<button @click="profileMode=0" class="btn btn-default">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="modal-particant-status">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Event Categories List</h4>
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
                <ul class="list-group">
                    <li v-for="(cat,index) in statusList"
                        class="list-group-item d-flex justify-content-between align-items-center">
                        {{ cat.kategory }}
                        <button @click="removeStatus(index)" class="btn badge badge-primary badge-pill"><i
                                    class="fa fa-times"></i></button>
                    </li>
                </ul>
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
		<div class="modal-content">
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
					<td><a :href="verifyModel.proofLink" target="_blank">Click Here To View</a> </td>
				</tr>
				<tr>
					<th>Your Response</th>
					<td>
						<div class="form-check">
							<input type="radio" name="response" value="1" class="form-check-input" v-model="verifyModel.response"/>
							<label class="form-check-label">Agree</label>
						</div>
						<div class="form-check">
							<input type="radio" name="response" value="0" class="form-check-input" v-model="verifyModel.response"/>
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
				<button :disabled="verifying" type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
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
			verifyModel:{},
			verifying:false,
			verifyMessage:null,
			countMember:0,
			countVerified:0,
			pagination:{},
			profileMode:0,
			profile:{},
        },
        methods: {
            detail(profile){
                $.each(this.statusList,function (i,v) {
                    if(v.id == profile.status)
                        profile.statusName = v.kategory;
                });
                if(profile.image){
                    profile.imageLink = `<?=base_url('themes/uploads/profile');?>/${profile.image}`;
				}else{
                    profile.imageLink = `<?=base_url('themes/uploads/people.jpg');?>`;
				}
                profile.birthdayFormatted =  moment(profile.birthday).format('DD MMM YYYY');
                this.profileMode = 1;
                this.profile = profile;
			},
            verify(){
                this.verifying = true;
				$.post("<?=base_url('admin/member/verify');?>",this.verifyModel,function (res) {
				    if(res.status) {
                        $("#modal-verification").modal("hide");
                        Swal.fire("Success", "Member has been verified !", "success");
                        app.$refs.datagrid.refresh();
                    }else
				        app.verifyMessage = res.message;
                },'JSON').fail(function () {
                    Swal.fire("Failed", "Failed to verify !", "error");
                    $("#modal-verification").modal("hide");
                }).always(function () {
					app.verifying = false;
                });
			},
            openVerifyModal(prop) {
                this.verifyMessage = null;
                $.each(this.statusList,function (i,v) {
					if(v.id == prop.row.status)
					    prop.row.statusSubmitted = v.kategory;
                });
				prop.row.proofLink = "<?=base_url('admin/member/get_proof');?>/"+prop.row.id;
				this.verifyModel = prop.row;
				$("#modal-verification").modal("show");
            },
            addStatus: function () {
                if (this.new_status != "") {
                    tempStatus.push({"kategory":this.new_status});
                    postStatus(tempStatus).done(function (res) {
                        app.statusList = res;
                        app.new_status = "";
                    }).fail(function () {
                        tempStatus.pop();
                        Swal.fire("Failed", "Failed to save !", "error");
                    });
                }

            },
            removeStatus: function (index) {
                var value = this.statusList[index];
                $.post("<?=base_url('admin/member/remove_status');?>",{id:value.id},function (res) {
                    if(res.status)
                        app.statusList.splice(index, 1);
                },'JSON').fail(function () {
                    Swal.fire("Failed", "Failed to remove !", "error");
                });
            },
			loadedGrid:function (data) {
				this.pagination = data;
            }
        }
    });
</script>
<?php $this->layout->end_script(); ?>
