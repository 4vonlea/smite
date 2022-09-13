<?php

/**
 * @var $admin_paper
 */
$this->layout->begin_head();
?>
<style>
	.modal .table th,
	.modal .table td {
		white-space: normal !important;
	}
	.vuetable-td-title{
		word-wrap: break-word;
		white-space: inherit !important;
		font-size: 12px !important;
	}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.css">
<?php
$this->layout->end_head();
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<!-- Card stats -->
			<div class="row">
				<div class="col-md-8 row">
					<div class="col-md-6">
						<div class="card card-stats mb-4 mb-xl-1">
							<div class="card-body">
								<div class="row mb-2">
									<div class="col">
										<h5 class="card-title text-uppercase text-muted mb-0">Abstract</h5>
									</div>
									<div class="col text-right">
										<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
											<i class="fa fa-archive"></i>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-7">
										<span class="h5 font-weight-bold mb-0">Waiting For Review : {{ pagination.total_stat_1 }}</span>
									</div>
									<div class="col-5">
										<span class="h5 font-weight-bold mb-0">Reject : {{ pagination.total_stat_3 }}</span>
									</div>
									<div class="col-7">
										<span class="h5 font-weight-bold mb-0">Return To Author : {{ pagination.total_stat_0 }}</span>
									</div>
									<div class="col-5">
										<span class="h5 font-weight-bold mb-0">Accept : {{ pagination.total_stat_2 }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card card-stats mb-4 mb-xl-1">
							<div class="card-body">
								<div class="row mb-2">
									<div class="col">
										<h5 class="card-title text-uppercase text-muted mb-0">Fullpaper</h5>
									</div>
									<div class="col text-right">
										<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
											<i class="fa fa-copy"></i>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-7">
										<span class="h5 font-weight-bold mb-0">Waiting For Review : {{ pagination.stat_fullpaper_1 }}</span>
									</div>
									<div class="col-5">
										<span class="h5 font-weight-bold mb-0">Reject : {{ pagination.stat_fullpaper_3 }}</span>
									</div>
									<div class="col-7">
										<span class="h5 font-weight-bold mb-0">Return To Author : {{ pagination.stat_fullpaper_0 }}</span>
									</div>
									<div class="col-5">
										<span class="h5 font-weight-bold mb-0">Accept : {{ pagination.stat_fullpaper_2 }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card card-stats mb-4 mb-xl-1">
							<div class="card-body">
								<div class="row mb-2">
									<div class="col">
										<h5 class="card-title text-uppercase text-muted mb-0">Presentation</h5>
									</div>
									<div class="col text-right">
										<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
											<i class="fa fa-file-powerpoint"></i>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-7">
										<span class="h5 font-weight-bold mb-0">Waiting For Review : {{ pagination.stat_presentasi_1 }}</span>
									</div>
									<div class="col-5">
										<span class="h5 font-weight-bold mb-0">Reject : {{ pagination.stat_presentasi_3 }}</span>
									</div>
									<div class="col-7">
										<span class="h5 font-weight-bold mb-0">Return To Author : {{ pagination.stat_presentasi_0 }}</span>
									</div>
									<div class="col-5">
										<span class="h5 font-weight-bold mb-0">Accept : {{ pagination.stat_presentasi_2 }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 mt-1">
						<div class="card card-stats mb-4 mb-xl-0">
							<div class="card-body">
								<div class="row">
									<div class="col">
										<h5 class="card-title text-uppercase text-muted mb-0">No Reviewer</h5>
										<span class="h2 font-weight-bold mb-0">{{ pagination.total_no_reviewer }}</span>
									</div>
									<div class="col-auto">
										<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
											<i class="fa fa-user-times"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row mb-2">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Mode Of Presentation</h5>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-warning text-white rounded-circle shadow">
										<i class="fa fa-check"></i>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6" v-for="(total,type) in pagination.presentation_accepted">
									<small class="font-weight-bold mr-2">{{ type }}: {{ total }}</small>
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
					<div class="col-md-4 col-sm-12">
						<h3>Papers <span v-if="filteredPaper != ''">( filtered by <span class="badge badge-info" style="text-transform:none">{{ filteredPaper }}</span> )</span></h3>
					</div>
					<div class="col-md-8 col-sm-12 text-right">
						<div class="dropdown">
							<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Download All File
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<a class="dropdown-item" href="<?= base_url('admin/paper/download_all_files/abstract'); ?>">Abstract</a>
								<a class="dropdown-item" href="<?= base_url('admin/paper/download_all_files/fullpaper'); ?>">Fullpaper</a>
								<a class="dropdown-item" href="<?= base_url('admin/paper/download_all_files/presentation'); ?>">Presentation</a>
								<a class="dropdown-item" href="<?= base_url('admin/paper/download_all_files/voice'); ?>">Voice Recording</a>
							</div>
						</div>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-setting"><i class="fa fa-book"></i> Setting Due Date & Cut Off
						</button>
						<button v-if="isAdmin" type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#modal-category-paper"><i class="fa fa-book"></i> Category Paper
						</button>
						<a href="<?=base_url('admin/paper/form_paper');?>" class="btn btn-primary">Add Paper</a>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<div class="row">
					<div class="col-md-12">
						<div class="dropdown">
							<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Filter by Category Paper
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<a class="dropdown-item" href="#" @click="filterGrid('all')">All Category</a>
								<a v-for="(cat,index) in categoryPaper" class="dropdown-item" href="#" @click="filterGrid(cat.id)">{{ cat.name }}</a>
							</div>
						</div>
						<div class="dropdown">
							<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Filter by {{ filterStatusLabel }}
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<span class="dropdown-item" href="#" @click="filterStatus('all','Status Member')">All Status</span>
								<span v-for="(cat,index) in categoryMember" class="dropdown-item" href="#" @click="filterStatus(cat.id,cat.kategory)">{{ cat.kategory }}</span>
							</div>
						</div>
					</div>
				</div>
				<datagrid @loaded_data="loadedGrid" ref="datagrid" :api-url="apiUrl" 
				:sort-list="[
					{name:'id_paper',sortField:'id_paper','title':'ID Paper'},
					{name:'category_name',sortField:'category_name','title':'Category Paper'},
					{name:'title',sortField:'title','title':'Abstract Title'},
					{name:'fullname',sortField:'fullname','title':'Member Name'},
					{name:'score','sortField':'score','title':'Score'},
					{name:'status','sortField':'status','title':'Status Abstract'},
					{name:'status_fullpaper','sortField':'status_fullpaper','title':'status_fullpaper'},
					{name:'status_presentasi','sortField':'status_presentasi','title':'Status Presentation'},
					{name:'type_presence','sortField':'type_presence','title':'Presentation'},
					{name:'t_created_at',sortField:'t_created_at',title:'Submit On'},
				]"
				
				:fields="[
						{name:'id_paper',sortField:'id_paper','title':'ID Paper'},
						{name:'title',sortField:'title','title':'Abstract Title'},
						{name:'fullname',sortField:'fullname','title':'Member Name'},
						{name:'score','sortField':'score'},
						{name:'status','sortField':'status','title':'Status Abstract'},
						{name:'type_presence','sortField':'type_presence','title':'Presentation'},
						{name:'t_created_at',sortField:'t_created_at',title:'Submit On'},
						{name:'t_id','title':'Aksi'}
					]">
					<template slot="title" slot-scope="props">
						<span class="badge badge-info">Category : {{ props.row.category_name ?? "Not Set"  }}</span>
						<p style="font-size: 14px;white-space:normal">{{ props.row.title }}</p>
					</template>
					<?php if ($this->session->user_session['role'] == User_account_m::ROLE_ADMIN_PAPER) : ?>
						<template slot="fullname" slot-scope="props">
							Hidden
						</template>
					<?php endif; ?>
					<template slot="fullname" slot-scope="props">
						{{ props.row.fullname }} 
						<hr style="margin-top: 10px;margin-bottom:10px;" />
						<a v-if="props.row.status_presentasi == '<?=Papers_m::ACCEPTED;?>'" class="btn btn-sm btn-primary" target="_blank" :href="'<?=base_url('admin/paper/preview_cert');?>/'+props.row.t_id">Preview Certificate</a>
						<button v-if="props.row.status_presentasi == '<?=Papers_m::ACCEPTED;?>'" @click="sendCertificate(props.row,$event)" class="btn btn-sm btn-primary">Send Certificate</button>
						<hr style="margin-top: 10px;margin-bottom:10px;" />
						<span style="font-size: 12px;" class="badge badge-info mb-1">{{ props.row.status_member }}</span><br/>
						<span style="font-size: 12px;" class="badge badge-info mb-1">{{ props.row.phone }}</span><br/>
						<span style="font-size: 12px;" class="badge badge-info mb-1">{{ props.row.institution }}</span> <br/>
						<span style="font-size: 12px;" class="badge mb-1" :class='[props.row.transaction_status == "Transaction Paid" ? "badge-success":"badge-danger"]'>
							{{ props.row.transaction_status }}
						</span>
					</template>
					<template slot="status" slot-scope="props">
						<ul class="list-group list-group-flush">
							<li class="list-group-item">
								Status Abstract : {{ status[props.row.status] }}<br/>
								<a class="badge badge-info" :href="'<?= base_url('admin/paper/file'); ?>/'+props.row.filename+'/'+props.row.t_id+'/Abstract'" target="_blank" v-if="props.row.filename">File Abstract</a>
							</li>
							<li class="list-group-item">
								Status Fullpaper : {{ (props.row.status == 2 ? status[props.row.status_fullpaper]:'') }}<br />
								<a class="badge badge-info" :href="'<?= base_url('admin/paper/file'); ?>/'+props.row.fullpaper+'/'+props.row.t_id+'/Fullpaper'" target="_blank" v-if="props.row.fullpaper">File Fullpaper</a>
							</li>
							<li class="list-group-item">
								Status Presentation : {{ (props.row.status_fullpaper == 2 ? status[props.row.status_presentasi]:'') }}<br />
								<a class="badge badge-info" :href="'<?= base_url('admin/paper/file'); ?>/'+props.row.poster+'/'+props.row.t_id+'/Presentation'" target="_blank" v-if="props.row.poster">File Presentation/Poster</a>
								<a class="badge badge-info" :href="'<?= base_url('admin/paper/file'); ?>/'+props.row.voice+'/'+props.row.t_id+'/Voice'" target="_blank" v-if="props.row.voice">Voice Recording</a>
							
							</li>
						</ul>
					</template>
					<template slot="t_updated_at" slot-scope="props">
						{{ formatDate(props.row.t_created_at) }}
					</template>
					<template slot="t_id" slot-scope="props">
						<div class="table-button-container">
							<button @click="detail(props,$event)" class="btn btn-info btn-sm">
								<span class="fa fa-search"></span> Detail
							</button>
							<button @click="review(props,$event)" class="btn btn-warning btn-sm">
								<span class="fa fa-edit"></span> review
							</button>
							<?php if ($this->session->user_session['role'] != User_account_m::ROLE_ADMIN_PAPER) : ?>
								<button v-if="!props.row.reviewer" @click="setReviewer(props)" class="btn btn-warning btn-sm">
									<span class="fa fa-user"></span> Set Reviewer
								</button>
							<?php endif; ?>
							<button class="btn btn-danger btn-sm" @click="deletePaper(props,$event)">
								<span class="fa fa-trash"></span> Delete
							</button>
						</div>
					</template>
				</datagrid>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modal-setting" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Setting Due Date & Cut Off</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Abstract Due Date</label>
					<vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm:ss" formatted="DD MMMM YYYY HH:mm" v-model="setting_date.paper_deadline"></vue-ctk-date-time-picker>
				</div>
				<div class="form-group">
					<label>Abstract Cut Off Date</label>
					<vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm:ss" formatted="DD MMMM YYYY HH:mm" v-model="setting_date.paper_cutoff"></vue-ctk-date-time-picker>
				</div>

				<div class="form-group">
					<label>Fullpaper Due Date</label>
					<vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm:ss" formatted="DD MMMM YYYY HH:mm" v-model="setting_date.fullpaper_deadline"></vue-ctk-date-time-picker>
				</div>
				<div class="form-group">
					<label>Fullpaper Cut Off Date</label>
					<vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm:ss" formatted="DD MMMM YYYY HH:mm" v-model="setting_date.fullpaper_cutoff"></vue-ctk-date-time-picker>
				</div>

				<div class="form-group">
					<label>Presentation Due Date</label>
					<vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm:ss" formatted="DD MMMM YYYY HH:mm" v-model="setting_date.presentation_deadline"></vue-ctk-date-time-picker>
				</div>
				<div class="form-group">
					<label>Presentation Cut Off Date</label>
					<vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm:ss" formatted="DD MMMM YYYY HH:mm" v-model="setting_date.presentation_cutoff"></vue-ctk-date-time-picker>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" @click="saveSetting" class="btn btn-primary">Save Date</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
			<div class="modal-body table-responsive">
				<table class="table" style="white-space: normal !important;">
					<tr>
						<th>ID Paper</th>
						<td>{{ reviewModel.id_paper }}</td>
					</tr>
					<tr>
						<th>Category Paper</th>
						<td>{{ reviewModel.category_name }}</td>
					</tr>
					<tr>
						<th style="width: 30%">Author Name</th>
						<td>{{ reviewModel.author }}</td>
					</tr>
					<tr>
						<th>Title</th>
						<td>{{ reviewModel.title }}</td>
					</tr>
					<tr>
						<th>Submitted On</th>
						<td>{{ formatDate(reviewModel.t_created_at) }}</td>
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
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="home-tab" data-toggle="tab" href="#info_paper_tab" role="tab" aria-controls="home" aria-selected="true">Info Paper</a>
					</li>
					<li v-show="!isReviewer" class="nav-item">
						<a class="nav-link" id="profile-tab" data-toggle="tab" href="#abstract_tab" role="tab" aria-controls="profile" aria-selected="false">Review Abstract</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="contact-tab" data-toggle="tab" href="#fullpaper_tab" role="tab" aria-controls="contact" aria-selected="false">Review Fullpaper</a>
					</li>
					<li v-show="!isReviewer" class="nav-item">
						<a class="nav-link" id="contact-tab" data-toggle="tab" href="#presentation_tab" role="tab" aria-controls="contact" aria-selected="false">Review Presentation</a>
					</li>
				</ul>
				<div class="tab-content table-responsive" id="myTabContent">
					<div class="tab-pane fade show active" id="info_paper_tab" role="tabpanel" aria-labelledby="home-tab">
						<table class="table" style="white-space: normal !important;">
							<tr>
								<th>ID Paper</th>
								<td>{{ reviewModel.id_paper }}</td>
							</tr>
							<tr>
								<th>Manuscript Section</th>
								<td>{{ reviewModel.category_name }}</td>
							</tr>
							<tr>
								<th>Manuscript Category</th>
								<td>{{ reviewModel.methods }}</td>
							</tr>
							<tr>
								<th>Manuscript Type </th>
								<td>{{ reviewModel.type }}</td>
							</tr>
							<tr>
								<th>Submitted On</th>
								<td>{{ formatDate(reviewModel.t_created_at) }}</td>
							</tr>

							<tr>
								<th>Title</th>
								<td>{{ reviewModel.title }}</td>
							</tr>
							<tr>
								<th>Abstract</th>
								<td style="white-space: pre-wrap !important;">{{ (reviewModel.introduction) }}</td>
							</tr>
							<!-- <tr v-if="detailMode == 1">
								<th>Status</th>
								<td>{{ status[reviewModel.status] }}</td>
							</tr> -->

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
							<tr>
								<td colspan="2">
									<h5>Feedback from Reviewer</h5>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Time</th>
												<th>Reviewer Name</th>
												<th>Feedback</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="feedback in reviewModel.feedbackList">
												<td>{{ feedback.created_at }}</td>
												<td>{{ feedback.name }}</td>
												<td>{{ feedback.result }}</td>
												<td>{{ status[feedback.status] }}</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</table>
					</div>
					<div v-show="!isReviewer" class="tab-pane fade" id="abstract_tab" role="tabpanel" aria-labelledby="profile-tab">
						<table class="table table-border">
							<tr>
								<th>Upload Date</th>
								<td>{{ reviewModel.t_created_at | formatDate }}</td>
							</tr>
							<tr>
								<th>Abstract Link</th>
								<td><a :href="reviewModel.link" target="_blank">Click Here !</a></td>
							</tr>
							<tr v-if="reviewModel.status == 0">
								<th>Feedback Message</th>
								<td>{{ reviewModel.message }}</td>
							</tr>
							<tr v-if="reviewModel.status == 0 && reviewModel.feedback">
								<th>Link Download Feedback</th>
								<td><a :href="reviewModel.link_feedback" target="_blank">Click Here !</a></td>
							</tr>
							<!-- Review Abstract -->
							<tr v-if="detailMode == 0">
								<th>Result Of Abstract Review</th>
								<td>
									<?php foreach (Papers_m::$status as $k => $v) : ?>
										<div class="form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" v-model="reviewModel.status" value="<?= $k; ?>">
												<?= $v; ?>
											</label>
										</div>
									<?php endforeach; ?>
								</td>
							</tr>
							<tr v-if="detailMode == 0">
								<th>Message <br /><small>*if returned to author</small></th>
								<td>
									<textarea cols="5" rows="5" class="form-control" v-model="reviewModel.message"></textarea>
								</td>
							</tr>
							<tr v-show="!isReviewer" v-if="detailMode == 0">
								<th>Feedback File</th>
								<td>
									<input type="file" name="feedback" ref="feedbackFile" class="form-control" accept=".doc,.docx,.ods" />
								</td>
							</tr>
							<!-- End Review Abstract -->
						</table>
					</div>
					<div class="tab-pane fade" id="fullpaper_tab" role="tabpanel" aria-labelledby="contact-tab">
						<table class="table table-border">
							<!-- Review Full Paper -->
							<tr v-if="reviewModel.fullpaper">
								<th>Upload Date</th>
								<td>{{ reviewModel.time_upload_fullpaper | formatDate }}</td>
							</tr>
							<tr v-if="reviewModel.fullpaper">
								<th>Fullpaper Link</th>
								<td><a :href="'<?= base_url('admin/paper/file'); ?>/'+reviewModel.fullpaper+'/'+reviewModel.t_id+'/Fullpaper'" target="_blank">Click Here !</a></td>
							</tr>
							<tr v-if="reviewModel.status_fullpaper == 0">
								<th>Feedback Message</th>
								<td>{{ reviewModel.feedback_fullpaper }}</td>
							</tr>
							<tr v-if="reviewModel.status_fullpaper == 0 && reviewModel.feedback_file_fullpaper">
								<th>Link Download Feedback</th>
								<td><a :href="`<?= base_url("admin/paper/file"); ?>/${reviewModel.feedback_file_fullpaper}/${reviewModel.t_id}/feedback_fullpaper`" target="_blank">Click Here !</a></td>
							</tr>
							<tr v-if="detailMode == 0">
								<th>Result Of Review</th>
								<td>
									<?php foreach (Papers_m::$status as $k => $v) : ?>
										<div class="form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" v-model="reviewModel.status_fullpaper" value="<?= $k; ?>">
												<?= $v; ?>
											</label>
										</div>
									<?php endforeach; ?>
								</td>
							</tr>
							<tr v-if="detailMode == 0">
								<th>Message <br /><small>*if returned to author</small></th>
								<td>
									<textarea cols="5" rows="5" class="form-control" v-model="reviewModel.feedback_fullpaper"></textarea>
								</td>
							</tr>
							<tr v-if="detailMode == 0">
								<th>Feedback File Fullpaper</th>
								<td>
									<input type="file" name="feedback" ref="feedbackFileFullpaper" class="form-control" accept=".doc,.docx,.ods" />
								</td>
							</tr>
							<tr v-if="detailMode == 0">
								<th>Score</th>
								<td>
									<input type="text" name="score" class="form-control" v-model="reviewModel.score" />
								</td>
							</tr>
							<tr v-if="detailMode == 0 && !isReviewer">
								<th>Type Presentation</th>
								<td>
									<?php foreach (Papers_m::$typePresentation as $i => $val) : ?>
										<div class="form-check-inline">
											<label class="form-check-label">
												<input v-model="reviewModel.type_presence" type="radio" name="type_presence" value="<?= $i; ?>">
												<?= $val; ?>
											</label>
										</div>
									<?php endforeach; ?>
								</td>
							</tr>
							<!-- End Review Fullpaper -->
						</table>
					</div>
					<div v-show="!isReviewer" class="tab-pane fade" id="presentation_tab" role="tabpanel" aria-labelledby="contact-tab">
						<table class="table table-border">
							<!-- Review Presentation -->
							<tr v-if="reviewModel.poster">
								<th>Upload Date</th>
								<td>{{ reviewModel.time_upload_presentasi | formatDate }}</td>
							</tr>
							<tr v-if="reviewModel.poster">
								<th>Presentation/Poster Link</th>
								<td><a :href="'<?= base_url('admin/paper/file'); ?>/'+reviewModel.poster+'/'+reviewModel.t_id+'/'+reviewModel.type_presence+''" target="_blank">Click Here !</a></td>
							</tr>
							<tr v-if="reviewModel.status_presentasi == 0">
								<th>Feedback Message</th>
								<td>{{ reviewModel.feedback_presentasi }}</td>
							</tr>
							<tr v-if="reviewModel.status_presentasi == 0 && reviewModel.feedback_file_presentasi">
								<th>Link Download Feedback</th>
								<td><a :href="`<?= base_url("admin/paper/file"); ?>/${reviewModel.feedback_file_presentasi}/${reviewModel.t_id}/feedback_presenatasi`" target="_blank">Click Here !</a></td>
							</tr>
							<tr v-if="detailMode == 0">
								<th>Result Of Review</th>
								<td>
									<?php foreach (Papers_m::$status as $k => $v) : ?>
										<div class="form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" v-model="reviewModel.status_presentasi" value="<?= $k; ?>">
												<?= $v; ?>
											</label>
										</div>
									<?php endforeach; ?>
								</td>
							</tr>
							<tr v-if="detailMode == 0">
								<th>Message <br /><small>*if returned to author</small></th>
								<td>
									<textarea cols="5" rows="5" class="form-control" v-model="reviewModel.feedback_presentasi"></textarea>
								</td>
							</tr>
							<tr v-if="detailMode == 0">
								<th>Feedback File Presenatation</th>
								<td>
									<input type="file" name="feedback" ref="feedbackFilePresentasi" class="form-control" accept=".doc,.docx,.ods,.jpg,.jpeg,.ppt,.pptx" />
								</td>
							</tr>

							<!-- End Review Presentation -->
						</table>
					</div>
				</div>

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

<!-- NOTE Category Paper -->
<div class="modal" id="modal-category-paper">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Category Paper</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="form-group">
					<div class="input-group">
						<input v-model="new_category_paper" type="text" class="form-control" @keyup.enter="addCategoryPaper" placeholder="New Category Paper" />
						<div class="input-group-append">
							<button type="button" class="btn btn-primary" @click="addCategoryPaper"><i class="fa fa-plus"></i> </button>
						</div>
					</div>
				</div>
				<ul class="list-group">
					<li v-for="(cat,index) in categoryPaper" class="list-group-item d-flex justify-content-between align-items-center">
						{{ cat.name }}
						<button @click="removeCategoryPaper(index)" class="btn badge badge-primary badge-pill"><i class="fa fa-times"></i></button>
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
<?php $this->layout->begin_script(); ?>
<script src="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.umd.js" charset="utf-8"></script>

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

	// NOTE Category Paper
	const apiUrlDefault = "<?= base_url('admin/paper/grid'); ?>";
	var tempCategoryPaper = <?= json_encode($categoryPaper); ?>;

	function postCategoryPaper(cat) {
		return $.post('<?= base_url('admin/paper/addCategoryPaper'); ?>', {
			value: cat
		});
	}

	Vue.component('vue-ctk-date-time-picker', window['vue-ctk-date-time-picker']);
	var app = new Vue({
		el: '#app',
		data: {
			apiUrl: apiUrlDefault,
			status: <?= json_encode(Papers_m::$status); ?>,
			pagination: {},
			reviewModel: {},
			detailMode: 0,
			saving: false,
			isReviewer: <?= $this->session->user_session['role'] == User_account_m::ROLE_ADMIN_PAPER ? "true" : "false"; ?>,
			isAdmin: <?= in_array($this->session->user_session['role'],[User_account_m::ROLE_ADMIN,User_account_m::ROLE_SUPERADMIN]) ? "true" : "false"; ?>,
			admin: <?= json_encode($admin_paper); ?>,
			validation: null,
			setting_date: {
				paper_deadline: "<?= Settings_m::getSetting('paper_deadline'); ?>",
				paper_cutoff: "<?= Settings_m::getSetting('paper_cutoff'); ?>",
				fullpaper_deadline: "<?= Settings_m::getSetting('fullpaper_deadline'); ?>",
				fullpaper_cutoff: "<?= Settings_m::getSetting('fullpaper_cutoff'); ?>",
				presentation_deadline: "<?= Settings_m::getSetting('presentation_deadline'); ?>",
				presentation_cutoff: "<?= Settings_m::getSetting('presentation_cutoff'); ?>",
			},
			new_category_paper: '',
			categoryPaper: <?= json_encode($categoryPaper); ?>,
			categoryMember: <?= json_encode($categoryMember); ?>,
			filteredPaper: "",
			filterStatusLabel:'Status Member',
		},
		filters: {
			formatDate: function(val) {
				return moment(val).format("DD MMMM YYYY [At] HH:mm");
			}
		},
		methods: {

			filterGrid: function(index) {
				if (index != 'all') {
					var find = app.categoryPaper.find(data => data.id == index);
					this.filteredPaper = find.name;
					this.apiUrl = `${apiUrlDefault}/${index}`;
				} else {
					this.filteredPaper = '';
					this.apiUrl = apiUrlDefault;
				}
				app.$refs.datagrid.refresh();
			},
			filterStatus(id,label){
				if(id != "all"){
					app.$refs.datagrid.additionalQuery = {'filterStatus':id};
				}else{
					app.$refs.datagrid.additionalQuery = {};
				}
				this.filterStatusLabel = label;
				app.$refs.datagrid.doFilter();

			},
			// NOTE Category Paper
			addCategoryPaper: function() {
				if (this.new_status != "") {
					tempCategoryPaper.push({
						"name": this.new_category_paper
					});
					postCategoryPaper(tempCategoryPaper).done(function(res) {
						app.statusList = res;
						app.categoryPaper = tempCategoryPaper = JSON.parse(JSON.stringify(res));
						app.new_category_paper = "";
					}).fail(function(xhr) {
						tempCategoryPaper.pop();
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					});
				}
			},
			removeCategoryPaper: function(index) {
				var value = this.categoryPaper[index];
				$.post("<?= base_url('admin/paper/removeCategoryPaper'); ?>", {
					id: value.id
				}, function(res) {
					if (res.status)
						app.categoryPaper.splice(index, 1);
				}, 'JSON').fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				});
			},
			// NOTE END Category Paper

			loadedGrid: function(data) {
				this.pagination = data;
			},
			detail(row, event) {
				this.validation = null;
				this.detailMode = 1;
				this.reviewModel = row.row;
				var inH = event.target.innerHTML;
				event.target.innerHTML = "<span class='fa fa-spin fa-spinner'></span>";
				try {
					$.get(`<?= base_url('admin/paper/get_feedback'); ?>/${row.row.t_id}`)
						.done(function(res) {
							if (typeof row.row.co_author == "string")
								var temp = JSON.parse(row.row.co_author);
							else
								var temp = row.row.co_author;
							app.reviewModel = row.row;
							app.reviewModel.feedbackList = res;
							app.reviewModel.co_author = temp;
							app.reviewModel.link = `<?= base_url("admin/paper/file"); ?>/${row.row.filename}/${row.row.t_id}`;
							app.reviewModel.link_feedback = `<?= base_url("admin/paper/file"); ?>/${row.row.feedback}/${row.row.t_id}/feedback`;
							$("#modal-review").modal('show');
						}).fail(function() {
							Swal.fire('Fail', "Failed to load data", 'error');
						}).always(function() {
							event.target.innerHTML = inH;
						})
				} catch (e) {
					console.log(e);
				}
			},
			saveSetting(evt) {
				evt.target.innerHTML = "<span class='fa fa-spin fa-spinner'></span>";
				$.post("<?= base_url('admin/paper/save_setting_date'); ?>", this.setting_date, null, 'JSON')
					.done(function(res) {
						if (res.status) {
							Swal.fire('Success', "Date saved successfully", 'success');
						}
					}).fail(function(xhr) {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function() {
						evt.target.innerHTML = "Save Date";

					});
			},
			save() {
				app.saving = true;
				toBase64(app.$refs.feedbackFile.files[0])
					.then(function(result) {
						if (result) {
							app.reviewModel.feedback_file = result;
							app.reviewModel.filename_feedback = app.$refs.feedbackFile.files[0].name;
						}
						return toBase64(app.$refs.feedbackFileFullpaper.files[0])
					}).then(function(result) {
						if (result) {
							app.reviewModel.feedback_file_fullpaper = result;
							app.reviewModel.filename_feedback_fullpaper = app.$refs.feedbackFileFullpaper.files[0].name;
						}
						return toBase64(app.$refs.feedbackFilePresentasi.files[0])
					}).then(function(result) {
						if (result) {
							app.reviewModel.feedback_file_presentasi = result;
							app.reviewModel.filename_feedback_presentasi = app.$refs.feedbackFilePresentasi.files[0].name;
						}
						$.post("<?= base_url('admin/paper/save'); ?>", app.reviewModel, function(res) {
							if (!res.status) {
								app.validation = res.message;
							} else {
								app.$refs.datagrid.reload();
								$("#modal-review").modal('hide');
								$("#modal-reviewer").modal('hide');
								Swal.fire('Success', "Review has been saved", 'success');
							}
						}, "JSON").fail(function(xhr) {
							var message = xhr.getResponseHeader("Message");
							if (!message)
								message = 'Server fail to response !';
							Swal.fire('Fail', message, 'error');
						}).always(function() {
							app.saving = false;
						});
					});

			},
			deletePaper(prop, event) {
				var btn = event.currentTarget;
				Swal.fire({
					title: "Are you sure ?",
					html: `You will delete paper with title <b>"${prop.row.title}"<b/>`,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
						btn.setAttribute("disabled", true);
						$.post("<?= base_url("admin/paper/delete"); ?>", {'id':prop.row.t_id}, function(res) {
							if (res.status) {
								Swal.fire("Success", "Paper deleted successfully", "success");
								app.$refs.datagrid.reload();
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
			setReviewer(row) {
				this.validation = null;
				this.detailMode = 0;
				this.reviewModel = row.row;
				this.reviewModel.link = `<?= base_url("admin/paper/file"); ?>/${row.row.filename}/${row.row.t_id}`;
				$("#modal-reviewer").modal('show');
			},
			review(row, event) {
				this.validation = null;
				this.detailMode = 0;
				var inH = event.target.innerHTML;
				this.$refs.feedbackFile.value = ""; //files[0].name;
				this.$refs.feedbackFileFullpaper.value = ""; //files[0].name;
				this.$refs.feedbackFilePresentasi.value = ""; //files[0].name;

				event.target.innerHTML = "<span class='fa fa-spin fa-spinner'></span>";
				try {
					$.get(`<?= base_url('admin/paper/get_feedback'); ?>/${row.row.t_id}`)
						.done(function(res) {
							if (typeof row.row.co_author == "string")
								var temp = JSON.parse(row.row.co_author);
							else
								var temp = row.row.co_author;
							app.reviewModel = row.row;
							app.reviewModel.feedbackList = res;
							app.reviewModel.co_author = temp;
							app.reviewModel.link = `<?= base_url("admin/paper/file"); ?>/${row.row.filename}/${row.row.t_id}`;
							app.reviewModel.link_feedback = `<?= base_url("admin/paper/file"); ?>/${row.row.feedback}/${row.row.t_id}/feedback`;
							$("#modal-review").modal('show');

						}).fail(function() {
							Swal.fire('Fail', "Failed to load data", 'error');
						}).always(function() {
							event.target.innerHTML = inH;
						})
				} catch (e) {
					console.log(e);
				}
			},
			formatDate(date) {
				return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
			},
			sendCertificate(row,evt){
				let dom = evt.target;
				var inH = dom.innerHTML;
				dom.innerHTML = "<span class='fa fa-spin fa-spinner'></span>";

				$.post("<?=base_url();?>/admin/paper/send_certificate",{id:row.t_id},(res)=>{
					if(res.status){
						Swal.fire("Success", "Paper certificate sent successfully", "success");
					}else{
						if(res.message)
							Swal.fire("Failed", res.message, "error");
						else
							Swal.fire("Failed","Failed to sent certificate", "error");
					}
				}).always(() => {
					dom.innerHTML = inH;
				}).fail(()=>{
					Swal.fire('Fail', "Failed to load data", 'error');
				})
			}
		}
	});
</script>

<?php $this->layout->end_script(); ?>