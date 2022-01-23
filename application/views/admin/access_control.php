<div class="header bg-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml"
	 xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>
<div class="container-fluid mt--7">
	<div class="col-xl-12">
		<div class="card shadow">
			<div class="card-header">
				<div class="row">
					<div class="col-6">
						<h3>User Access Control</h3>
					</div>
					<div class="col-6 text-right">
						<?php $role = User_account_m::$listRole;
						unset($role[User_account_m::ROLE_MEMBER]); ?>
						<?= form_dropdown('role', $role, '', ['class' => 'form-control', 'v-model' => 'selectedRole', '@change' => 'fetchAccess']); ?>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<div class="row mb-2 mt-2">
					<div class="col form-inline ml-3">
						<label>
							Show&nbsp;
							<select v-model="pageSize" class="form-control form-control-sm">
								<option v-for="c in perPage" :value="c">{{ c }}</option>
							</select>&nbsp;Entries
						</label>
					</div>
					<div class="col mr-3">
						<div class="input-group">
							<input type="text" v-model="globalFilter" @keyup.enter="doFilter"
								   placeholder="Type to search !" class="form-control"/>
							<div class="input-group-append">
								<button type="button" v-on:click="doFilter" class="btn btn-primary"><i
										class="fa fa-search"></i> Search
								</button>
								<button type="button" v-on:click="resetFilter" class="btn btn-primary"><i
										class="fa fa-times"></i> Reset
								</button>
							</div>
						</div>

					</div>
				</div>
				<vuetable ref="vuetable"
						  :api-mode="false"
						  :fields="fields"
						  :data="localData"
						  :data-total="dataCount"
						  :data-manager="dataManager"
						  data-path="data"
						  pagination-path="pagination"
						  :per-page="pageSize"
						  :css="css.table"
						  @vuetable:pagination-data="onPaginationData">
					<template slot="access" slot-scope="props">
						<label class="form-check form-check-inline"><input :disabled="selectedRole == 1 && props.rowData.module == 'account'" type="checkbox" value="view"
																		   v-model="props.rowData.access"
																		   @change="save(props.rowData,'view')"/>View</label>
						<label class="form-check form-check-inline"><input :disabled="selectedRole == 1 && props.rowData.module == 'account'" type="checkbox" value="insert"
																		   v-model="props.rowData.access"
																		   @change="save(props.rowData,'insert')"/>Insert</label>
						<label class="form-check form-check-inline"><input :disabled="selectedRole == 1 && props.rowData.module == 'account'" type="checkbox" value="update"
																		   v-model="props.rowData.access"
																		   @change="save(props.rowData,'update')"/>Update</label>
						<label class="form-check form-check-inline"><input :disabled="selectedRole == 1 && props.rowData.module == 'account'" type="checkbox" value="delete"
																		   v-model="props.rowData.access"
																		   @change="save(props.rowData,'delete')"/>Delete</label>
					</template>
				</vuetable>
				<div class="row mt-3 mb-3">
					<vuetable-pagination-info ref="paginationInfo"
											  no-data-template="No Data Available !"
											  :css="css.info"></vuetable-pagination-info>
					<vuetable-pagination ref="pagination"
										 :css="css.pagination"
										 @vuetable-pagination:change-page="onChangePage"
					></vuetable-pagination>
				</div>

			</div>
		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
<script>
	var app = new Vue({
		el: '#app',
		components: {
			'vuetable-pagination': Vuetable.VuetablePagination,
			'vuetable-pagination-info': Vuetable.VuetablePaginationInfo
		},
		data: {
			selectedRole: '1',
			loading: false,
			//Grid Data
			fields: [{name: 'role', sortField: 'role', 'title': 'Role Name'}, {
				name: 'module',
				title: "Module Name/Page",
				sortField: 'module'
			}, {name: 'access'}],
			localData: {data: [], pagination: {}},
			pageSize: 10,
			globalFilter: '',
			perPage: [10, 20, 50, 100],
			dataCount: 0,
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
					activeClass: 'active',
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
			this.fetchAccess();
		},
		methods: {
			save(row, access) {
				var type = 'insert';
				if (row.access.indexOf(access) == -1)
					type = 'delete';

				$.post("<?=base_url('admin/account/save_access');?>", {
					role: row.role,
					module: row.module,
					access: access,
					type: type
				})
					.fail(function (xhr, message) {
						var message =  xhr.getResponseHeader("Message");
						if(!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function () {
				});
			},
			fetchAccess() {
				this.loading = true;
				$.post("<?=base_url('admin/account/access_data');?>", {role: this.selectedRole}, function (res) {
					if (res.status) {
						app.localData = {
							data: res.data,
							"pagination": {
								"total": res.data.length,
								"per_page": 10,
								"current_page": 1,
								"last_page": Math.ceil(res.data.length / 10),
								"next_page_url": null,
								"prev_page_url": null,
								"from": 1,
								"to": 10,
							}
						};
						Vue.nextTick(function () {
							app.$refs.vuetable.refresh();
						})
					} else {
						Swal.fire("Failed", res.message, "error");
					}
				}).fail(function (xhr, message) {
					var message =  xhr.getResponseHeader("Message");
					if(!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function () {
					app.loading = false;
				});
			},

			//Grid Method
			dataManager(sortOrder, pagination) {
				let data = this.localData.data;
				console.log(data);
				if (this.globalFilter) {
					console.log("MASUK");
					let txt = new RegExp(this.globalFilter, 'i')
					data = _.filter(data, function (item) {
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
			onPaginationData(paginationData) {
				this.$refs.pagination.setPaginationData(paginationData);
				this.$refs.paginationInfo.setPaginationData(paginationData);
			},
			onChangePage(page) {
				this.$refs.vuetable.changePage(page);
			},
			doFilter() {
				Vue.nextTick(() => this.$refs.vuetable.refresh())
			},
			resetFilter() {
				this.globalFilter = "";
				Vue.nextTick(() => this.$refs.vuetable.refresh())
			},
		}
	});
</script>
<?php $this->layout->end_script(); ?>
