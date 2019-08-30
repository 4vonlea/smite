<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<!-- Card stats -->
			<div class="row">
				<div class="col-xl-4 col-lg-4">
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
				<div class="col-xl-4 col-lg-4">
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
				<div class="col-xl-4 col-lg-4">
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
						<h3>Transaction</h3>
					</div>
				</div>
			</div>

			<datagrid
				@loaded_data="loadedGrid"
				ref="datagrid"
				api-url="<?= base_url('admin/paper/grid'); ?>"
				:fields="[{name:'fullname',sortField:'fullname','title':'Member Name'},{name:'status','sortField':'status'},{name:'t_updated_at',sortField:'t_updated_at',title:'Date'},{name:'t_id','title':'Aksi'}]">
				<template slot="status" slot-scope="props">
					{{ status[props.row.status] }}
				</template>
				<template slot="t_updated_at" slot-scope="props">
					{{ formatDate(props.row.t_updated_at) }}
				</template>
				<template  slot="t_id" slot-scope="props">
					<div class="table-button-container">
						<button @click="detail(props)" class="btn btn-info btn-sm">
							<span class="fa fa-search"></span> Detail
						</button>
						<button v-if="props.row.status == 1" @click="review(props)" class="btn btn-warning btn-sm">
							<span class="fa fa-edit"></span> review
						</button>
					</div>
				</template>
			</datagrid>
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
				<div v-if="validation" class="alert alert-danger" >
					<span v-html="validation"></span>
				</div>
				<table class="table">
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
						<td>{{ formatDate(reviewModel.t_updated_at) }}</td>
					</tr>
					<tr v-if="detailMode == 1">
						<th>Status</th>
						<td>{{ status[reviewModel.status] }}</td>
					</tr>
					<tr>
						<th>Link Download</th>
						<td> <a :href="reviewModel.link" target="_blank" >Click Here !</a></td>
					</tr>
					<tr v-if="detailMode == 0">
						<th>Result Of Review</th>
						<td>
							<div class="form-check-inline">
								<label class="form-check-label">
									<input type="radio" class="form-check-input"  v-model="reviewModel.status" value="0">Return to Author
								</label>
							</div>
							<div class="form-check-inline">
								<label class="form-check-label">
									<input type="radio" class="form-check-input" v-model="reviewModel.status" value="2">Accepted
								</label>
							</div>
						</td>
					</tr>
					<tr v-if="detailMode == 0">
						<th>Message <br/><small>*if returned to author</small></th>
						<td>
							<textarea cols="5" rows="5" class="form-control" v-model="reviewModel.message"></textarea>
						</td>
					</tr>
				</table>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<span v-if="detailMode == 0">
					<button :disabled="saving" type="button" class="btn btn-primary" @click="save" >
						<i v-if="saving" class="fa fa-spin fa-spinner"></i> Save
					</button>
				</span>

			</div>

		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<script>
    var app = new Vue({
        el: '#app',
        data: {
			status:<?=json_encode(Papers_m::$status);?>,
            pagination: {},
			reviewModel:{},
			detailMode:0,
			saving:false,
			validation:null,
        },
        methods: {
            loadedGrid: function (data) {
                this.pagination = data;
            },
			detail(row){
                this.validation = null;
                this.detailMode = 1;
                this.reviewModel = row.row;
                this.reviewModel.link = `<?=base_url("admin/paper/file");?>/${row.row.filename}`;
                $("#modal-review").modal('show');
			},
			save(){
                app.saving = true;
                $.post("<?=base_url('admin/paper/save');?>",this.reviewModel,function (res) {
					if(!res.status){
						app.validation = res.message;
					}else{
                        app.$refs.datagrid.refresh();
                        $("#modal-review").modal('hide');
                        Swal.fire('Success',"Review has been saved",'success');
                    }
                },"JSON").fail(function () {
                    Swal.fire('Fail',"Failed to process !",'warning');
                }).always(function () {
					app.saving = false;
                });
			},
			review(row){
                this.validation = null;
                this.detailMode = 0;
				this.reviewModel = row.row;
				this.reviewModel.link = `<?=base_url("admin/paper/file");?>/${row.row.filename}`;
				$("#modal-review").modal('show');
			},
            formatDate(date){
                return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
            },
        }
    });
</script>

<?php $this->layout->end_script(); ?>
