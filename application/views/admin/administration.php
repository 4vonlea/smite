<?php
/**
 * @var $event
 */
?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml"
	 xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>

<div class="container-fluid mt--7">
	<div key="table" class="row">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h3>Administration</h3>
						</div>
						<div class="col-6 text-right">
							<select class="form-control" v-model="selectedEvent" @change="fetchData">
								<option disabled hidden value="">Select Event First</option>
								<option v-for="(event,key) in eventList" :value="key"> {{ event }}</option>
							</select>
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
					<div style="position: relative">
						<div v-if="loading" class="bg-color-dark"
							 style="width: 100%;height: 100%;position: absolute; z-index: 1000;background-color: darkgrey;opacity: .5">
							<div class="fa fa-spin fa-cog text-primary fa-4x" role="status"
								 style="position:absolute;opacity:1;top: 45%;left: 45%;">
								<span class="sr-only">Loading...</span>
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
								<template slot="name_tag" slot-scope="props">
									<input type="checkbox" v-model="props.rowData.checklist.nametag" @change="changeChecklist(props.rowData)"/>
								</template>
								<template slot="seminar_kit" slot-scope="props">
									<input type="checkbox" v-model="props.rowData.checklist.seminarkit" @change="changeChecklist(props.rowData)"/>
								</template>
								<template slot="taker" slot-scope="props">
									<input type="text" class="form-control" v-model="props.rowData.checklist.taker" @change="changeChecklist(props.rowData)">
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
	</div>
</div>
<!-- Table -->

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
            fields: [{name:'fullname',sortField:'fullname'},{name:'name_tag',title:"Name Tag"},{name:'seminar_kit',title:"Seminar Kit"},{name:'taker',title:"Taker"},],
            eventList:<?=json_encode($event);?>,
            selectedEvent: "",
            localData: {data:[],pagination:{}},
            pageSize: 10,
            globalFilter: '',
            perPage: [10, 20, 50, 100],
            loading: false,
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
        created(){
            this.fetchData();
		},
        methods: {
            dataManager(sortOrder, pagination) {
                let data = this.localData.data
                // account for search filter
                if (this.globalFilter) {
                    // the text should be case insensitive
                    let txt = new RegExp(this.globalFilter, 'i')

                    // search on name, email, and nickname
                    data = _.filter(data, function (item) {
                        return item.fullname.search(txt) >= 0
                    })
                }

                // sortOrder can be empty, so we have to check for that as well
                if (sortOrder.length > 0) {
                    data = _.orderBy(data, sortOrder[0].sortField, sortOrder[0].direction)
                }

                // since the filter might affect the total number of records
                // we can ask Vuetable to recalculate the pagination for us
                // by calling makePagination(). this will make VuetablePagination
                // work just like in API mode
                pagination = this.$refs.vuetable.makePagination(data.length)

                // if you don't want to use pagination component, you can just
                // return the data array
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
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            },
            resetFilter() {
                this.globalFilter = "";
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            },
			changeChecklist(row){
                $.post("<?=base_url("admin/member/save_check");?>", {transaction: [{checklist:row.checklist,id:row.td_id}]});
			},
			fetchData(){
                if(this.selectedEvent != "") {
                    var app = this;
                    this.loading = true;
                    $.post("<?=base_url('admin/administration/get_participant');?>", {id: this.selectedEvent}, function (res) {
                        if (res.status) {
                            $.each(res.data,function (i,v) {
								if(!v.checklist)
								    v.checklist = {nametag:false,seminarkit:false,taker:""};
								else{
								    try{
								        v.checklist = JSON.parse(v.checklist);
								        v.checklist.nametag = (v.checklist.nametag == "true");
								        v.checklist.seminarkit = (v.checklist.seminarkit == "true");
									}catch (e) {
                                        v.checklist = {nametag:false,seminarkit:false,taker:""};
                                    }
								}
                            });
                            app.localData = {
                                data: res.data,
                                "pagination": {
                                    "total": res.data.length,
                                    "per_page": 10,
                                    "current_page": 1,
                                    "last_page": Math.floor(res.data.length / 10),
                                    "next_page_url": null,
                                    "prev_page_url": null,
                                    "from": 1,
                                    "to": 10,
                                }
                            };
                        }
                    }).fail(function () {

                    }).always(function () {
                        app.loading = false;
                    });
                }
			}
        }
    });
</script>
<?php $this->layout->end_script(); ?>
