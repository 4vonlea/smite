<?php

/**
 * @var $pricingDefault
 */
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>

<div class="container-fluid mt--7">
    <transition name="fade" mode="out-in">
        <div v-if="mode == 'table'" key="table" class="row">
            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>Push Data To P2KB</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="row mb-2 mt-2">
                            <div class="col form-inline ml-3">
                                <label>
                                    Show&nbsp;
                                    <select v-model="datagrid.pageSize" class="form-control form-control-sm">
                                        <option v-for="c in datagrid.perPage" :value="c">{{ c }}</option>
                                    </select>&nbsp;Entries
                                </label>
                            </div>
                            <div class="col mr-3">
                                <div class="input-group">
                                    <input type="text" v-model="datagrid.globalFilter" @keyup.enter="doFilter" placeholder="Type to search !" class="form-control" />
                                    <div class="input-group-append">
                                        <button type="button" v-on:click="doFilter" class="btn btn-primary"><i class="fa fa-search"></i> Search
                                        </button>
                                        <button type="button" v-on:click="resetFilter" class="btn btn-primary"><i class="fa fa-times"></i> Reset
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <vuetable ref="vuetable" :api-mode="false" :fields="datagrid.fields" :data="datagrid.localData" :data-total="datagrid.dataCount" :data-manager="dataManager" data-path="data" pagination-path="pagination" :per-page="datagrid.pageSize" :css="datagrid.css.table" @vuetable:pagination-data="onPaginationData">
                            <template slot="id" slot-scope="props">
                                <div class="table-button-container">
                                    <v-button @click="showModalPushP2KB(props.rowData)" class="btn btn-info btn-sm">
                                        <span class="fa fa-upload"></span> Push Certificate To P2KB
                                    </v-button>
                                    <v-button @click="getMap($event,props.rowData)" class="btn btn-info btn-sm">
                                        <span class="fa fa-list-ol"></span> Mapping P2KB
                                    </v-button>
                                </div>
                            </template>
                        </vuetable>
                        <div class="row mt-3 mb-3">
                            <vuetable-pagination-info ref="paginationInfo" no-data-template="No Data Available !" :css="datagrid.css.info"></vuetable-pagination-info>
                            <vuetable-pagination ref="pagination" :css="datagrid.css.pagination" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div v-if="mode == 'mapping'">
            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>Mapping P2KB Configuration</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4>Event Name : {{ mapping.name }}</h4>
                        <hr />
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Category Participant</th>
                                    <th>Mapping</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="status in mapping.statusList">
                                    <td>
                                        <h3>{{status.kategory}}</h3>
                                    </td>
                                    <td>
                                        <v-mapping-p2kb v-model="mapping.map[status.kategory]" :aktivitas-options="listAktivitas" url-jenis-aktivitas="<?= base_url('admin/push_p2kb/jenis_aktivitas'); ?>" url-skp="<?= base_url('admin/push_p2kb/skp'); ?>"></v-mapping-p2kb>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <v-button @click="mode ='table'" class="btn btn-danger" icon="fa fa-back">Batal</v-button>
                        <v-button @click="saveMap($event)" class="btn btn-primary" icon="fa fa-save">Simpan</v-button>
                    </div>
                </div>
            </div>
        </div>
    </transition>


    <div class="modal" id="modal-push-p2kb" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content table-responsive">
                <div class="modal-header">
                    <h4>Push Certificate To P2KB</h4>
                </div>
                <div v-if="pushP2KB.mode == 'preparing'" class="modal-body">
                    <h3>Event Name : {{ pushP2KB.eventName }}</h3>
                    <hr />
                    <div class="form-group">
                        <label class="control-label">Synchronized Participant</label>
                        <div class="form-check">
                            <input class="form-check-input" v-model="pushP2KB.participantType" type="radio" value="all" id="all-participant">
                            <label class="form-check-label" for="all-participant">
                                All Participant
                            </label>
                            <small class="form-text text-muted">All participants and there is a possibility of data being doubled if previously pushed</small>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" v-model="pushP2KB.participantType" type="radio" value="notyet" id="notyet-participant">
                            <label class="form-check-label" for="notyet-participant">
                                Not yet pushed Only
                            </label>
                            <small class="form-text text-muted">Only participants data have not been pushed</small>
                        </div>
                    </div>
                </div>
                <div v-if="pushP2KB.mode == 'cannot_start'" class="modal-body">
                    <div class="col-md-12">
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Failed to Start !</h4>
                            <p>{{ pushP2KB.message }}</p>
                        </div>
                    </div>
                </div>
                <div v-if="pushP2KB.mode == 'sync'" class="modal-body">
                    <div class="progress" style="height: 35px;">
                        <div class="progress-bar progress-bar-striped" role="progressbar" :style="{width: pushP2KB.progress+'%'}" aria-valuemin="0" aria-valuemax="100">
                            {{ pushP2KB.processed }} of {{ pushP2KB.total }}
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Total Data
                            <span class="badge badge-primary badge-pill">{{pushP2KB.total}}</span>
                        </li>
                        <li class="list-group-item">Success
                            <span class="badge badge-primary badge-pill">{{pushP2KB.success}}</span>
                        </li>
                        <li class="list-group-item">Failed
                            <span class="badge badge-primary badge-pill">{{pushP2KB.failed}}</span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button v-if="pushP2KB.status != 'processing' && pushP2KB.processed == pushP2KB.total && pushP2KB.resultList.length > 0" @click="downloadResultPush" class="btn btn-info ml-2">Download Result</button>
                    <v-button v-if="pushP2KB.mode =='preparing'" class="btn btn-primary" @click="startPush($event,'start')" icon="fa fa-start">
                        Start
                    </v-button>
                    <button :disabled="pushP2KB.status == 'processing' && pushP2KB.processed != pushP2KB.total" type="button" :class="{disabled:pushP2KB.status == 'processing'}" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Table -->

<?php $this->layout->begin_head(); ?>
<style>
    .pre-line {
        white-space: inherit !important;
    }

    .vuetable-td-name {
        white-space: pre-line !important;
        width: 400px;
    }

    .disabled {
        cursor: not-allowed;
        opacity: 0.4;
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<?php $this->layout->end_head(); ?>

<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/v-button.js"); ?>"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<script src="<?= base_url("themes/script/v-mapping-p2kb.js"); ?>"></script>
<script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.19.1/package/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        el: '#app',
        data: {
            mode: "table",
            eventList: [],
            mapping: {},
            listAktivitas: [],
            savingMap: false,
            pushP2KB: {
                eventId: 0,
                mode: 'preparing',
                eventName: '',
                participantType: 'all',
                status: '',
                success: 0,
                failed: 0,
                resultList: [],
                data: [],
                processed: 0,
                message: '',
            },
            datagrid: {
                fields: [{
                    name: 'name',
                    sortField: 'name',
                    'title': 'Name'
                }, {
                    name: 'category',
                    title: "Category",
                    sortField: 'category'
                }, {
                    name: 'participant',
                    title: 'Participant',
                    sortField: 'participant',
                }, {
                    name: 'id',
                    title: 'Action',
                }],
                localData: {
                    data: [],
                    pagination: {}
                },
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
                        activeClass: 'active bg-primary',
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
            }
        },
        mounted() {
            this.fetchAccess();
        },
        methods: {
            downloadResultPush() {
                const worksheet = XLSX.utils.json_to_sheet(this.pushP2KB.resultList);
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, "List");
                XLSX.writeFile(workbook, "Pushing Result List .xlsx", {
                    compression: true
                });
            },
            startPush(selfButton, type) {
                if (type == 'start') {
                    selfButton.toggleLoading();
                    $.post("<?= base_url('admin/push_p2kb/push_participant'); ?>", {
                        type: this.pushP2KB.participantType,
                        event_id: this.pushP2KB.eventId
                    }, (res) => {
                        if (res.status) {
                            this.pushP2KB.failed = 0;
                            this.pushP2KB.success = 0;
                            this.pushP2KB.total = res.data.length;
                            this.pushP2KB.data = res.data;
                            this.pushP2KB.status = 'processing';
                            this.pushP2KB.processed = 0;
                            this.pushP2KB.resultList = [];
                            this.pushP2KB.mode = 'sync';
                            this.startPush(selfButton, 'process');
                            if (this.pushP2KB.data.length > 0) 
                                this.startPush(selfButton, 'process');
                            if (this.pushP2KB.data.length > 0) 
                                this.startPush(selfButton, 'process');
                        } else {
                            this.pushP2KB.mode = 'cannot_start';
                            this.pushP2KB.message = res.message;
                        }
                    }).always(() => {
                        selfButton.toggleLoading();
                    });
                } else if (type == 'process') {
                    let participant = this.pushP2KB.data.pop();
                    $.post("<?= base_url("admin/push_p2kb/push_data"); ?>", {
                        event_id:this.pushP2KB.eventId,
                        participant:participant
                    }, (res) => {
                        participant.feedback = JSON.stringify(res);
                        if (res.status) {
                            this.pushP2KB.success++;
                        } else {
                            this.pushP2KB.failed++;
                        }
                        this.pushP2KB.resultList.push(participant);
                    }).always(() => {
                        this.pushP2KB.processed++;
                        this.pushP2KB.progress = Math.round(this.pushP2KB.processed / this.pushP2KB.total * 100)
                        if (this.pushP2KB.data.length > 0) {
                            this.startPush(selfButton, 'process');
                        } else {
                            this.pushP2KB.status = "finish";
                        }
                    }).fail((xhr) => {
                        this.pushP2KB.failed++;
                        participant.feedback = xhr.responseText;
                        this.pushP2KB.resultList.push(participant);
                    });
                }
            },
            showModalPushP2KB(row) {
                this.pushP2KB.eventId = row.id;
                this.pushP2KB.eventName = row.name;
                this.pushP2KB.mode = "preparing";
                this.pushP2KB.processed = 0;
                this.pushP2KB.resultList = [];
                $("#modal-push-p2kb").modal("show");
            },
            saveMap(self) {
                self.toggleLoading();
                $.post("<?= base_url('admin/push_p2kb/map'); ?>/" + this.mapping.id, {
                        map: JSON.stringify(this.mapping.map)
                    }, null, 'JSON')
                    .done(function(res, text, xhr) {
                        if (res.status)
                            app.mode = "table";
                        else
                            Swal.fire('Fail', res.message, 'warning');
                    }).fail(function(xhr) {
                        app.error = xhr.responseJSON;
                        var message = xhr.getResponseHeader("Message");
                        if (message) {
                            Swal.fire('Fail', message, 'error');
                        }
                    }).always(function() {
                        self.toggleLoading();
                    });
            },
            fetchAccess() {
                $.post("<?= base_url('admin/push_p2kb/grid'); ?>", {}, (res) => {
                    if (res.status) {
                        this.datagrid.localData = {
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
                        Vue.nextTick(() => {
                            this.$refs.vuetable.refresh();
                        })
                    } else {
                        Swal.fire("Failed", res.message, "error");
                    }
                }).fail(function(xhr, message) {
                    var message = xhr.getResponseHeader("Message");
                    if (!message)
                        message = 'Server fail to response !';
                    Swal.fire('Fail', message, 'error');
                }).always(function() {});
            },
            getMap(self, row) {
                self.toggleLoading();
                var url = "<?= base_url('admin/push_p2kb/map'); ?>/" + row.id;
                $.get(url)
                    .done((res) => {
                        this.mode = "mapping";
                        this.mapping = res.data;
                        this.listAktivitas = res.aktivitas;
                    }).fail((xhr) => {
                        var message = xhr.getResponseHeader("Message");
                        if (!message)
                            message = 'Server fail to response !';
                        Swal.fire('Fail', message, 'error');
                    }).always(() => {
                        self.toggleLoading();
                    });
            },

            dataManager(sortOrder, pagination) {
                let data = this.datagrid.localData.data;
                if (this.datagrid.globalFilter) {
                    let txt = new RegExp(this.datagrid.globalFilter, 'i')
                    data = _.filter(data, function(item) {
                        return item.name.search(txt) >= 0 || item.category.search(txt) >= 0 
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
                this.datagrid.globalFilter = "";
                Vue.nextTick(() => this.$refs.vuetable.refresh())
            },
        }
    });
</script>
<?php $this->layout->end_script(); ?>