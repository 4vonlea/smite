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
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Paricipants</h5>
                                    <span class="h2 font-weight-bold mb-0">350,897</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Unverified Participants</h5>
                                    <span class="h2 font-weight-bold mb-0">2,356</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Participants Paper</h5>
                                    <span class="h2 font-weight-bold mb-0">924</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                                <span class="text-nowrap">Since yesterday</span>
                            </p>
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
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h3>Participants</h3>
                        </div>
                        <div class="col-6 text-right">
                            <button @click="onAdd" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Add
                                Event
                            </button>
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modal-particant-status"><i class="fa fa-book"></i> Participant Status
                                List
                            </button>
                        </div>
                    </div>
                </div>

                <datagrid
                        ref="datagrid"
                        api-url="<?= base_url('admin/participant/grid'); ?>"
                        :fields="[{name:'fullname',sortField:'fullname'}, {name:'email',sortField:'email'},{name:'gender',sortField:'gender'},{name:'id',title:'Actions',titleClass:'action-th'}]">
                    <template slot="id" slot-scope="props">
                        <div class="table-button-container">
                            <button @click="editRow(props)" class="btn btn-warning btn-sm">
                                <span class="fa fa-pen"></span> Edit
                            </button>
                            <button @click="detailRow(props)" class="btn btn-info btn-sm">
                                <span class="fa fa-search"></span> Detail
                            </button>
                            <button @click="deleteRow(props)" class="btn btn-danger btn-sm">
                                <span class="fa fa-trash"></span> Delete
                            </button>
                        </div>
                    </template>

                </datagrid>
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
                               placeholder="New Participant Status"/>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" @click="addStatus"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <ul class="list-group">
                    <li v-for="(cat,index) in statusList"
                        class="list-group-item d-flex justify-content-between align-items-center">
                        {{ cat }}
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

<?php $this->layout->begin_script(); ?>

<script>
    var tempStatus = <?=json_encode($statusList);?>;

    function postStatus(cat) {
        return $.post('<?=base_url('admin/participant/add_status');?>', {value: cat});
    }

    var app = new Vue({
        el: '#app',
        data: {
            new_status: '',
            statusList:<?=json_encode($statusList);?>
        },
        methods: {
            onAdd() {

            },
            addStatus: function () {
                if (this.new_event_category != "") {
                    tempStatus.push(this.new_event_category);
                    postStatus(tempStatus).done(function () {
                        app.statusList.push(app.new_event_category);
                        app.new_event_category = "";
                    }).fail(function () {
                        tempStatus.pop();
                        Swal.fire("Failed", "Failed to save !", "error");
                    });
                }

            },
            removeStatus: function (index) {
                var value = tempStatus[index];
                tempStatus.splice(index, 1);
                postStatus(tempStatus).done(function () {
                    app.statusList.splice(index, 1);
                }).fail(function () {
                    tempStatus.push(value);
                    Swal.fire("Failed", "Failed to remove !", "error");
                });
            },
        }
    });
</script>
<?php $this->layout->end_script(); ?>
