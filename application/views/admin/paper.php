<?php
/**
 * @var $admin_paper
 */
$this->layout->begin_head();
?>
<style>
	.table th, .table td{
		white-space: normal !important;
	}
</style>
<?php
$this->layout->end_head();
?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<!-- Card stats -->
			<div class="row">
				<div class="col-md-3">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Returned to Author</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_stat_0 }}</span>
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
				<div class="col-md-3">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Need Review</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_stat_1 }}</span>
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
				<div class="col-md-3">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Accepted</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_stat_2 }}</span>
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
				<div class="col-md-3">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">No Reviewer</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_no_reviewer }}</span>
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
			</div>
		</div>
	</div>
</div>
<div class="container-fluid mt--7">
	<div class="col-xl-12">
		<div class="card shadow">
			<div class="card-header">
				<div class="row">
					<div class="col-6">
						<h3>Papers</h3>
					</div>
				</div>
			</div>
			<div class="table-responsive">

				<datagrid
					@loaded_data="loadedGrid"
					ref="datagrid"
					api-url="<?= base_url('admin/paper/grid'); ?>"
					:fields="[{name:'fullname',sortField:'fullname','title':'Member Name'},{name:'status','sortField':'status'},{name:'reviewer','sortField':'reviewer'},{name:'t_updated_at',sortField:'t_updated_at',title:'Submit On'},{name:'t_id','title':'Aksi'}]">
					<?php if($this->session->user_session['role'] == User_account_m::ROLE_ADMIN_PAPER):?>
						<template slot="fullname" slot-scope="props">
							Hidden
						</template>
					<?php endif ;?>
					<template slot="status" slot-scope="props">
						{{ status[props.row.status] }}<br/>
						<a class="badge badge-info" :href="'<?=base_url('admin/paper/file');?>/'+props.row.filename+'/'+props.row.m_id+'/Abstract'"  target="_blank" v-if="props.row.filename">Abstract</a>
						<a class="badge badge-info" :href="'<?=base_url('admin/paper/file');?>/'+props.row.fullpaper+'/'+props.row.m_id+'/Fullpaper'"  target="_blank" v-if="props.row.fullpaper">Fullpaper</a>
						<a class="badge badge-info" :href="'<?=base_url('admin/paper/file');?>/'+props.row.poster+'/'+props.row.m_id+'/Presentation'"  target="_blank" v-if="props.row.poster">Presentation/Poster</a>
					</template>
					<template slot="t_updated_at" slot-scope="props">
						{{ formatDate(props.row.t_created_at) }}
					</template>
					<template slot="t_id" slot-scope="props">
						<div class="table-button-container">
							<button @click="detail(props)" class="btn btn-info btn-sm">
								<span class="fa fa-search"></span> Detail
							</button>
							<button @click="review(props)" class="btn btn-warning btn-sm">
								<span class="fa fa-edit"></span> review
							</button>
							<?php if($this->session->user_session['role'] != User_account_m::ROLE_ADMIN_PAPER):?>
							<button v-if="!props.row.reviewer" @click="setReviewer(props)"
									class="btn btn-warning btn-sm">
								<span class="fa fa-user"></span> Set Reviewer
							</button>
							<?php endif;?>
						</div>
					</template>
				</datagrid>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modal-reviewer">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<h4 class="modal-title">Set Reviewer</h4>
			</div>
			<div class="modal-body">
				<table class="table" style="white-space: normal !important;">
					<tr>
						<th style="width: 30%">Author Name</th>
						<td>{{ reviewModel.author }}</td>
					</tr>
					<tr>
						<th>Title</th>
						<td >{{ reviewModel.title }}</td>
					</tr>
					<tr>
						<th>Submitted On</th>
						<td>{{ formatDate(reviewModel.t_updated_at) }}</td>
					</tr>
					<tr>
						<th>Reviewer</th>
						<td>
							<select class="form-control" v-model="reviewModel.reviewer">
								<option disabled hidden value="">Select Reviewer</option>
								<option v-for="a in admin" :value="a.username">{{ a.username }} | {{ a.name }}</option>
							</select>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer text-right">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primar" @click="save">Save</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modal-review">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">{{ detailMode ? "Detail Paper":"Review Paper" }}</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div v-if="validation" class="alert alert-danger">
					<span v-html="validation"></span>
				</div>
				<table class="table" style="white-space: normal !important;">
					<tr v-if="reviewModel.fullpaper">
						<th>Fullpaper Link</th>
						<td><a :href="'<?=base_url('admin/paper/file');?>/'+reviewModel.fullpaper+'/'+reviewModel.m_id+'/Fullpaper'" target="_blank">Click Here !</a></td>
					</tr>
					<tr  v-if="reviewModel.poster">
						<th>Presentation/Poster Link</th>
						<td><a :href="'<?=base_url('admin/paper/file');?>/'+reviewModel.poster+'/'+reviewModel.m_id+'/Presentation'" target="_blank">Click Here !</a></td>
					</tr>
					<tr>
						<th>Submitted On</th>
						<td>{{ formatDate(reviewModel.t_updated_at) }}</td>
					</tr>

					<tr>
						<th>Title</th>
						<td>{{ reviewModel.title }}</td>
					</tr>
					<tr>
						<th>Abstract</th>
						<td style="white-space: pre-wrap !important;">{{ (reviewModel.introduction) }}</td>
					</tr>
<!--					<tr>-->
<!--						<th>Aims</th>-->
<!--						<td>{{ (reviewModel.aims) }}</td>-->
<!--					</tr>-->
<!--					<tr>-->
<!--						<th>Methods</th>-->
<!--						<td>{{ (reviewModel.methods) }}</td>-->
<!--					</tr>-->
<!--					<tr>-->
<!--						<th>Result</th>-->
<!--						<td>{{ (reviewModel.result) }}</td>-->
<!--					</tr>-->
<!--					<tr>-->
<!--						<th>Conclusion</th>-->
<!--						<td>{{ (reviewModel.conclusion) }}</td>-->
<!--					</tr>-->

					<tr v-if="detailMode == 1">
						<th>Status</th>
						<td>{{ status[reviewModel.status] }}</td>
					</tr>
					<tr>
						<th>Abstract Link</th>
						<td><a :href="reviewModel.link" target="_blank">Click Here !</a></td>
					</tr>
					<tr v-if="detailMode == 1">
						<th>Mode Of Presentation</th>
						<td>{{ reviewModel.type_presence }}</td>
					</tr>
					<tr v-if="reviewModel.co_author">
						<th>Co-Author</th>
						<td>
							<table class="table">
								<tr>
									<th>Fullname</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Affiliation</th>
								</tr>
								<tr v-for="c in reviewModel.co_author">
									<td>{{ (c.fullname ?c.fullname:"") }}</td>
									<td>{{ (c.email ?c.email:"") }}</td>
									<td>{{ (c.phone ?c.phone:"") }}</td>
									<td>{{ (c.affiliation ?c.affiliation:"") }}</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr v-if="reviewModel.status == 0">
						<th>Feedback Message</th>
						<td>{{ reviewModel.message }}</td>
					</tr>
					<tr  v-if="reviewModel.status == 0">
						<th>Link Download Feedback</th>
						<td><a :href="reviewModel.link_feedback" target="_blank">Click Here !</a></td>
					</tr>
					<tr v-if="detailMode == 0">
						<th>Result Of Review</th>
						<td>
							<?php foreach(Papers_m::$status as $k=>$v):?>
								<div class="form-check-inline">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" v-model="reviewModel.status" value="<?=$k;?>">
										<?=$v;?>
									</label>
								</div>
							<?php endforeach; ?>
						</td>
					</tr>
					<tr v-if="detailMode == 0">
						<th>Message <br/><small>*if returned to author</small></th>
						<td>
							<textarea cols="5" rows="5" class="form-control" v-model="reviewModel.message"></textarea>
						</td>
					</tr>
					<tr v-if="detailMode == 0">
						<th>Feedback File</th>
						<td>
							<input type="file" name="feedback" ref="feedbackFile" class="form-control" accept=".doc,.docx,.ods" />
						</td>
					</tr>
					<tr v-if="detailMode == 0">
						<th>Type Presentation</th>
						<td>
							<div class="form-check-inline">
								<label class="form-check-label">
									<input  v-model="reviewModel.type_presence"  type="radio" name="type_presence" value="Oral">
									Oral
								</label>
							</div>
							<div class="form-check-inline">
								<label class="form-check-label">
									<input  v-model="reviewModel.type_presence" type="radio" name="type_presence" value="Poster">
									Poster
								</label>
							</div>
						</td>
					</tr>
				</table>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<span v-if="detailMode == 0">
					<button :disabled="saving" type="button" class="btn btn-primary" @click="save">
						<i v-if="saving" class="fa fa-spin fa-spinner"></i> Save
					</button>
				</span>

			</div>

		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<script>
    const toBase64 = file => new Promise((resolve, reject) => {
        const reader = new FileReader();
        if (file) {
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
        } else {
            resolve(null);
        }
        reader.onerror = error => reject(error);
    });

    var app = new Vue({
        el: '#app',
        data: {
            status:<?=json_encode(Papers_m::$status);?>,
            pagination: {},
            reviewModel: {},
            detailMode: 0,
            saving: false,
            admin:<?=json_encode($admin_paper);?>,
            validation: null,
        },
        methods: {
            loadedGrid: function (data) {
                this.pagination = data;
            },
            detail(row) {
                this.validation = null;
                this.detailMode = 1;
                this.reviewModel = row.row;
                try{
                    var temp = JSON.parse(row.row.co_author);
                    this.reviewModel.co_author = temp;
                }catch (e) {
                    console.log(e);
                }
                this.reviewModel.link = `<?=base_url("admin/paper/file");?>/${row.row.filename}/${row.row.m_id}`;
                this.reviewModel.link_feedback = `<?=base_url("admin/paper/file");?>/${row.row.feedback}`;
                $("#modal-review").modal('show');
            },
            save() {
                app.saving = true;
                toBase64(app.$refs.feedbackFile.files[0]).then(function (result) {
                    if(result) {
                        app.reviewModel.feedback_file = result;
                        app.reviewModel.filename_feedback = app.$refs.feedbackFile.files[0].name;
                    }
                    $.post("<?=base_url('admin/paper/save');?>", app.reviewModel, function (res) {
                        if (!res.status) {
                            app.validation = res.message;
                        } else {
                            app.$refs.datagrid.refresh();
                            $("#modal-review").modal('hide');
                            $("#modal-reviewer").modal('hide');
                            Swal.fire('Success', "Review has been saved", 'success');
                        }
                    }, "JSON").fail(function () {
                        Swal.fire('Fail', "Failed to process !", 'warning');
                    }).always(function () {
                        app.saving = false;
                    });
                });

            },
            setReviewer(row) {
                this.validation = null;
                this.detailMode = 0;
                this.reviewModel = row.row;
                this.reviewModel.link = `<?=base_url("admin/paper/file");?>/${row.row.filename}/${row.row.m_id}`;
                $("#modal-reviewer").modal('show');
            },
            review(row) {
                this.validation = null;
                this.detailMode = 0;
                this.reviewModel = row.row;
                try{
                    var temp = JSON.parse(row.row.co_author);
                    this.reviewModel.co_author = temp;
                }catch (e) {
					console.log(e);
                }
                this.reviewModel.link = `<?=base_url("admin/paper/file");?>/${row.row.filename}/${row.row.m_id}`;
                $("#modal-review").modal('show');
            },
            formatDate(date) {
                return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
            },
        }
    });
</script>

<?php $this->layout->end_script(); ?>
