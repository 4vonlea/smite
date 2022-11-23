<?php

/**
 * @var array $events
 */
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8"></div>
<!-- Page content -->
<div class="container-fluid mt--7">
	<!-- Table -->
	<div class="row" v-show="detailMode == 0">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<h3>Broadcast Notification History</h3>
				</div>
				<div class="table-responsive">
					<datagrid ref="grid" :sort-order="[{field:'created_at',direction:'desc'}]" ref="datagrid" api-url="<?= base_url('admin/notification/grid'); ?>" :fields="[{name:'t_id',title:'ID',sortField:'id'},{name:'subject',sortField:'subject','title':'Subject'},{name:'channel',title:'Channel',sortField:'channel'},{name:'status',title:'Status',sortField:'status'},{name:'created_at',sortField:'created_at','title':'Created At'},{name:'id',title:'Action'}]">
						<template slot="created_at" slot-scope="props">
							<span>{{ props.row.created_at | formatDate}}</span>
						</template>
						<template slot="created_at" slot-scope="props">
							<span>{{ props.row.created_at | formatDate}}</span>
						</template>
						<template slot="id" slot-scope="props">
							<button v-if="props.row.status == 'Finish'" class="btn btn-primary btn-sm" @click="retry(props.row.id)">
								<span class="fa fa-edit"></span> Retry
							</button>
							<button class="btn btn-info btn-sm" @click="detail(props.row.id)">
								<span class="fa fa-search"></span> Detail
							</button>
						</template>
					</datagrid>
				</div>
			</div>
		</div>
	</div>
	<div class="row" v-show="detailMode == 1">
		<div class="col-xl-12">
			<div class="card shadow-lg card-profile-bottom mb-2">
				<div class="card-body p-3">
					<div class="row gx-4">
						<div class="col-auto my-auto">
							<div class="avatar avatar-xl position-relative bg-primary"><i class="fa fa-bullhorn"></i></div>
						</div>
						<div class="col-auto my-auto">
							<div class="h-100">
								<h5 class="mb-1">
									Detail Broadcast Notification History
								</h5>
								<p class="mb-0 font-weight-bold text-sm">
									Subject : {{ detailHistory.info.subject }}
								</p>
								<i class="badge badge-info">Status {{ detailHistory.info.status }} </i>
								<i class="badge badge-success">Channel {{ detailHistory.info.channel }} </i>
							</div>
						</div>
						<div class="col-md-3 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
							<div class="nav-wrapper position-relative end-0 text-right">
								<button class="btn btn-info" @click="detailMode=0">Back</button>
								<button class="btn btn-info" @click="detail(detailHistory.id)">Reload</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9">
					<div class="card mb-4">
						<div class="card-header">
							<h4 class="card-title">
								List Receiver
								{{ detailHistory.data.length }} of {{ detailHistory.info.countReceiver }}
							</h4>
						</div>
						<div class="card-body table-responsive">
							<div v-show="loadingDetail" class="text-center">
								<i class="fa fa-spin fa-spinner fa-3x"></i>
								<h3>Loading Data</h3>
							</div>
							<vuetable v-show="!loadingDetail" ref="vuetable" :fields="['fullname', 'email', 'phone', 'feedback']" :api-mode="false" :data="detailHistory" :per-page="10" :data-total="detailHistory.data.length" :data-manager="dataManager" data-path="data" pagination-path="pagination" :css="css.table" @vuetable:pagination-data="onPaginationData"></vuetable>
							<div class="row mt-3 mb-3">
								<vuetable-pagination-info ref="paginationInfo" no-data-template="No Data Available !" :css="css.info"></vuetable-pagination-info>
								<vuetable-pagination ref="pagination" :css="css.pagination" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card mb-4">
						<div class="card-header">
							<h4 class="card-title">Message</h4>
						</div>
						<div class="card-body">
							<div class="col-md-12">
								<span v-html="detailHistory.info.message"></span>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<?php $this->layout->begin_head(); ?>
<style>
	.vuetable-td-feedback {
		white-space: pre-wrap !important;
	}
</style>
<?php $this->layout->end_head();; ?>
<?php $this->layout->begin_script(); ?>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>

<script>
	Vue.filter('formatDate', function(value) {
		if (value) {
			return moment(value).format('DD MMMM YYYY HH:mm')
		}
	});
	var app = new Vue({
		el: '#app',
		data: {
			detailMode: 0,
			loadingDetail: false,
			detailHistory: {
				id:null,
				info: {},
				data: [],
				pagination: {}
			},
			css: {
				table: {
					tableWrapper: '',
					tableHeaderClass: 'mb-0',
					tableBodyClass: 'mb-0',
					tableClass: 'table table-bordered table-stripped table-hover table-grid',
					loadingClass: 'loading',
					ascendingIcon: 'fa fa-chevron-up',
					descendingIcon: 'fa fa-chevron-down',
					ascendingClass: 'sorted-asc',
					descendingClass: 'sorted-desc',
					sortableIcon: 'fa fa-sort',
					detailRowClass: 'vuetable-detail-row',
					handleIcon: 'fa fa-bars text-secondary',
					renderIcon(classes, options) {
						return `<i class="${classes.join(' ')}"></span>`
					}
				},
				info: {
					infoClass: 'pl-4 col'
				},
				pagination: {
					wrapperClass: 'col pagination',
					activeClass: 'bg-primary active',
					disabledClass: 'disabled',
					pageClass: 'btn btn-border',
					linkClass: 'btn btn-border',
					icons: {
						first: '',
						prev: '',
						next: '',
						last: '',
					},
				}
			},
		},
		mounted() {
			let defaultId = <?= $id ? "'$id'" : "null"; ?>;
			if (defaultId) {
				this.detail(defaultId);
			}
		},
		methods: {
			onPaginationData(paginationData) {
				this.$refs.pagination.setPaginationData(paginationData);
				this.$refs.paginationInfo.setPaginationData(paginationData);
			},
			onChangePage(page) {
				this.$refs.vuetable.changePage(page);
			},
			dataManager(sortOrder, pagination) {
				let data = this.detailHistory.data;
				if (this.globalFilter) {
					let txt = new RegExp(this.globalFilter, 'i')
					data = _.filter(data, function(item) {
						return item.module.search(txt) >= 0;
					})
				}
				if (sortOrder.length > 0) {
					data = _.orderBy(data, sortOrder[0].sortField, sortOrder[0].direction)
				}
				pagination = this.$refs.vuetable.makePagination(data.length)
				return {
					pagination: pagination,
					data: _.slice(data, pagination.from - 1, pagination.to)
				}
			},
			retry(id) {
				Swal.fire({
					title: 'You will repeat this process !',
					text: "Please choose list receiver to resend ?",
					type: 'warning',
					showDenyButton: true,
					showCancelButton: true,
					confirmButtonText: 'All Receiver',
					cancelButtonText: `Only Failed`,
					cancelButtonColor: '#d33',
				}).then((result) => {
					let type = null;
					console.log(result);
					if (result.value) {
						type = "all";
					}else if(result.dismiss == "cancel"){
						type ="onlyFailed";
					}
					if(type){
						$.post(`<?= base_url('admin/notification/retry'); ?>/${id}`,{type:type},(res) => {
							if(res.status){
								app.$refs.grid.reload();
							}
						})
					}
				})
			},
			detail(id) {
				this.detailMode = 1;
				this.loadingDetail = true;
				$.get(`<?= base_url('admin/notification/detail_history'); ?>/${id}`, (res) => {
					let data = res.attribute;// JSON.parse(res.attribute);
					this.detailHistory = {
						id:id,
						info: {
							subject: res.subject,
							message: res.message,
							type: res.type,
							channel:res.channel,
							status: res.status,
							countReceiver:res.count_receiver,
							created_at: res.created_at
						},
						"pagination": {
							"total": data.length,
							"per_page": 10,
							"current_page": 1,
							"last_page": Math.ceil(data.length / this.per_page),
							"next_page_url": null,
							"prev_page_url": null,
							"from": 1,
							"to": 10,
						},
						data: data,
					}
					Vue.nextTick(() => {
						this.$refs.vuetable.refresh();
					})
				}).always(() => {
					this.loadingDetail = false;
				});
			}
		},
	});
</script>
<?php $this->layout->end_script(); ?>