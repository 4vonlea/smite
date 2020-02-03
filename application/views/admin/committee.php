<?php
/**
 * @var array $events
 */
?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8"></div>
<!-- Page content -->
<div class="container-fluid mt--7">
	<!-- Table -->
	<div v-if="formMode==0" class="row">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h3>Committees</h3>
						</div>
						<div class="col-6 text-right">
							<button type="button" class="btn btn-primary" data-toggle="modal"
									data-target="#modal-committees-status"><i class="fa fa-book"></i> Committees
								Position List
							</button>
							<button class="btn btn-primary" @click="formMode = 1;form = {attributes:[]};"><i
									class="fa fa-plus"></i>
								Add Committee
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<datagrid
						ref="datagrid"
						api-url="<?= base_url('admin/committee/grid'); ?>"
						:fields="[{name:'t_name',title:'Name',sortField:'t_name'},{name:'status',sortField:'status','title':'Event Name and Position'},{name:'t_id',title:'Action'}]">
						<template slot="status" slot-scope="props">
							<span v-html="parseStatus(props.row)"></span>
						</template>
						<template slot="t_id" slot-scope="props">
							<button class="btn btn-primary btn-sm" @click="editCom(props.row)">
								<span class="fa fa-edit"></span> Edit
							</button>
							<button class="btn btn-danger btn-sm" @click="deleteCom(props.row,$event)">
								<span class="fa fa-trash"></span> Delete
							</button>
						</template>
					</datagrid>
				</div>
			</div>
		</div>
	</div>
	<div v-else-if="formMode == 1" class="row">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<h3>New Committee</h3>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label class="form-control-label">Fullname</label>
						<input type="text" class="form-control" v-model="form.name"/>
					</div>
					<div class="form-group">
						<label class="form-control-label">Position in Event</label>
						<table class="table">
							<thead>
							<tr>
								<th>Event</th>
								<th>Position</th>
								<th>
									<button class="btn btn-primary btn-sm"
											@click="addAttributes"><i
											class="fa fa-plus"></i> Add
									</button>
								</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<th colspan="3" v-if="form.attributes.length == 0" class="text-center">No Data</th>
							</tr>
							<tr v-for="(attr,index) in form.attributes">
								<td>
									<select class="form-control" v-model="attr.event">
										<option disabled hidden value="">Select Event</option>
										<option v-for="event in events" :value="event.id">{{ event.name }}</option>
									</select>
								</td>
								<td>
									<select class="form-control" v-model="attr.status">
										<option disabled hidden value="">Select Position</option>
										<option v-for="pos in committeePosition" :value="pos">{{ pos }}</option>
									</select>
								</td>
								<td>
									<button class="btn btn-danger" @click="deleteAttributes(attr,index,$event)"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-footer text-right">
					<button @click="save" class="btn btn-default" :disabled="saving">
						<i v-if="saving" class="fa fa-spin fa-spinner"></i>
						Save
					</button>
					<button @click="formMode=0" class="btn btn-default">Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modal-committees-status">
	<div class="modal-dialog">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Position Committee List</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="form-group">
					<div class="input-group">
						<input v-model="new_status_committee" type="text" class="form-control"
							   @keyup.enter="addPositionCommittee" placeholder="New Event Category"/>
						<div class="input-group-append">
							<button type="button" class="btn btn-primary" @click="addPositionCommittee"><i
									class="fa fa-plus"></i></button>
						</div>
					</div>
				</div>
				<ul class="list-group">
					<li v-for="(cat,index) in committeePosition"
						class="list-group-item d-flex justify-content-between align-items-center">
						{{ cat }}
						<button @click="removePositionCommittee(index)" class="btn badge badge-primary badge-pill"><i
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
    var tempPosition = <?=Settings_m::statusCommitte();?>;

    function postCategory(cat) {
        return $.post('<?=base_url('admin/setting/save/' . Settings_m::STATUS_COMMITTEE);?>', {value: cat});
    }

    var app = new Vue({
        el: '#app',
        data: {
            formMode: 0,
            new_status_committee: "",
            committeePosition:<?=Settings_m::statusCommitte();?>,
            saving: false,
            events:<?=json_encode($events);?>,
            form: {attributes: []},
        },
        methods: {
            parseStatus(data){
                if(data.status) {
                    var group = `<ul class="list-group list-group-flush" >`;
                    $.each(data.status.split(";"), function (i, v) {
                        var t = v.split(",");
                        group += `<li class="list-group-item d-flex justify-content-between align-items-center">
								${t[2]} as ${t[1]}
								<a target="_blank" href="<?=base_url('admin/committee/nametag');?>/${t[0]}" class="badge badge-primary badge-pill">Download Name Tag</a>
							  </li>`;
                    });
                    group += `</ul>`;
                    return group;
                }
                return "-";
			},
            deleteAttributes(attr,index,evt){
                var btn = evt.currentTarget;
                if(!attr.id)
					this.form.attributes.splice(index,1);
				else{
                    btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
                    btn.setAttribute("disabled",true);
                    $.post("<?=base_url("admin/committee/delete_attribute");?>", attr, function (res) {
                        if (res.status) {
                            Swal.fire("Success", "Position deleted successfully", "success");
                            app.form.attributes.splice(index,1);
                        }else
                            Swal.fire("Failed", res.message, "error");
                    }, "JSON").fail(function () {
                        Swal.fire("Failed", "Failed to load data !", "error");
                    }).always(function () {
                        btn.innerHTML = '<i class="fa fa-trash"></i>';
                        btn.removeAttribute("disabled");
                    });
				}

			},
			addAttributes(){
                this.form.attributes.push({event:'',status:''});
			},
			editCom(row){
                this.form = {
                    id:row.t_id,
					name:row.t_name,
					attributes:[]
				};
                if(row.status) {
                    $.each(row.status.split(";"), function (i, v) {
                        var t = v.split(",");
                        app.form.attributes.push({
                            id: t[0],
                            event: t[3],
                            status: t[1]
                        });
                    });
                }
                this.formMode = 1;
			},
			deleteCom(row,evt){
                var btn = evt.currentTarget;
                Swal.fire({
                    title: "Are you sure ?",
                    text: `You will delete "${row.t_name}" From Committee`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
                        btn.setAttribute("disabled",true);
                        $.post("<?=base_url("admin/committee/delete");?>", {id:row.t_id}, function (res) {
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
            save() {
                this.saving = true;
                $.post("<?=base_url('admin/committee/save');?>", this.form, function (res) {
                    if (res.status) {
                        Swal.fire("Success", "Committee saved successfully !", "success");
                        app.formMode = 0;
                    }else{

					}
                }, "JSON").always(function () {
                    app.saving = false;
                }).fail(function () {
                    Swal.fire("Failed", "Failed to save !", "error");
                });
            },
            addPositionCommittee: function () {
                if (this.new_status_committee != "") {
                    tempPosition.push(this.new_status_committee);
                    postCategory(tempPosition).done(function () {
                        app.committeePosition.push(app.new_status_committee);
                        app.new_status_committee = "";
                    }).fail(function () {
                        tempPosition.pop();
                        Swal.fire("Failed", "Failed to save !", "error");
                    });
                }
            },
            removePositionCommittee: function (index) {
                var value = tempPosition[index];
                tempPosition.splice(index, 1);
                postCategory(tempPosition).done(function () {
                    app.committeePosition.splice(index, 1);
                }).fail(function () {
                    tempPosition.push(value);
                    Swal.fire("Failed", "Failed to remove !", "error");
                });
            },
        },

    });
</script>
<?php $this->layout->end_script(); ?>
