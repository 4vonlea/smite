<?php
/**
 * @var array $events
 */
$this->layout->begin_head()
?>
<link href="<?=base_url('themes/script/easyautocomplete/easy-autocomplete.min.css');?>" rel="stylesheet">
	
<?php
	$this->layout->end_head();
?>
<div class="header bg-info pb-8 pt-5 pt-md-8"></div>
<!-- Page content -->
<div class="container-fluid mt--7">
	<?php if($this->session->has_userdata("message")):?>
		<div class="alert alert-success">
			<h4 class="text-center"><?=$this->session->message;?></h4>
		</div>	
	<?php endif;?>
	<!-- Table -->
	<div v-if="formMode==0" class="row">
		<div class="col-xl-12">
			<?php if($this->session->has_userdata('import')):?>
				<?php
				$import = $this->session->userdata('import');
				$import = explode(";",$import);

				?>
			<div class="alert <?=$import[0] == '0' ? 'alert-danger':'alert-success';?>">
				<?=$import[1];?>
			</div>
			<?php endif;?>
			<div class="card shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-3">
							<h3>Committees</h3>
						</div>
						<div class="col-9 text-right">
							<div class="dropdown">
								<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Import Data
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="<?=base_url('admin/committee/download_template');?>">Download Template</a>
									<button onclick="$('#file').click()" class="dropdown-item">Upload Template</button>
									<form id="form-import" method="POST" enctype="multipart/form-data" action="<?=base_url('admin/committee/import');?>">
										<input onchange="$('#form-import').submit()" id="file" accept=".xls,.xlsx" type="file" name="import" class="hidden" />
									</form>
								</div>
							</div>
							<div class="dropdown">
								<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Download Committtee
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="<?=base_url("admin/committee/download_committee/excel");?>" target="_blank">As Excel</a>
									<a class="dropdown-item" href="<?=base_url("admin/committee/download_committee/csv");?>"  target="_blank">As CSV</a>
									<a class="dropdown-item" href="<?=base_url("admin/committee/download_committee/pdf");?>"  target="_blank">As PDF</a>
								</div>
							</div>
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
						<input type="text" class="form-control autocomplete" v-model="form.name"/>
					</div>
					<div class="form-group">
						<label class="form-control-label">Email</label>
						<input type="text" class="form-control" v-model="form.email"/>
					</div>
					<div class="form-group">
						<label class="form-control-label">No Contact</label>
						<input type="text" class="form-control" v-model="form.no_contact"/>
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
<script src="<?=base_url('themes/script/easyautocomplete/jquery.easy-autocomplete.min.js');?>"></script>
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
            form: {attributes: [],no_contact:'',email:'',name:''},
		},
		watch:{
			formMode(newVal,oldVal){
				if(newVal == 1){
					Vue.nextTick(function(){
						$('.autocomplete').easyAutocomplete({
							url: '<?=base_url('admin/committee/search_member');?>',
							listLocation: "items",
							getValue: 'value',
							template: {
								type: "custom",
								method: function (value, item) {
									return `${value} (${item.email})`;
								}
							},
							ajaxSettings: {
								dataType: "json",
								method: "POST",
								data: {
									dataType: "json"
								}
							},
							list:{
								onClickEvent:function(){
									var element = $('.autocomplete').getSelectedItemData();
									app.form.no_contact = element.phone;
									app.form.email = element.email;
									app.form.name = element.value;
									console.log(app.form);
								}
							},
							preparePostData: function (data) {
								data.cari = app.form.name;
								return data;
							},
						});
					});
				}
			}
		},
        methods: {
            parseStatus(data){
                if(data.status) {
                    var group = `<ul class="list-group list-group-flush" >`;
                    $.each(data.status.split(";"), function (i, v) {
                        var t = v.split(",");
                        group += `<li class="list-group-item d-flex justify-content-between align-items-center">
								${t[2]} as ${t[1]}
								<div>
									<a target="_blank" href="<?=base_url('admin/committee/nametag');?>/${t[0]}" class="badge badge-primary badge-pill pull-right">Download Name Tag</a>
									<a target="_blank" href="<?=base_url('admin/committee/certificate');?>/${t[0]}" class="badge badge-primary badge-pill pull-right">Download Certificate</a>
									<a target="_blank" href="<?=base_url('admin/committee/send_certificate');?>/${t[0]}" class="badge badge-primary badge-pill pull-right">Send Certificate</a>
								</div>
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
                    }, "JSON").fail(function (xhr) {
						var message =  xhr.getResponseHeader("Message");
						if(!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
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
					email:row.email,
					no_contact:row.no_contact,
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
                        }, "JSON").fail(function (xhr) {
							var message =  xhr.getResponseHeader("Message");
							if(!message)
								message = 'Server fail to response !';
							Swal.fire('Fail', message, 'error');
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
                }).fail(function (xhr) {
					var message =  xhr.getResponseHeader("Message");
					if(!message)
						message = 'Pleases fullfill fullname, email, no contact, position in event !';
					Swal.fire('Fail', message, 'error');
                });
            },
            addPositionCommittee: function () {
                if (this.new_status_committee != "") {
                    tempPosition.push(this.new_status_committee);
                    postCategory(tempPosition).done(function () {
                        app.committeePosition.push(app.new_status_committee);
                        app.new_status_committee = "";
                    }).fail(function (xhr) {
                        tempPosition.pop();
						var message =  xhr.getResponseHeader("Message");
						if(!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
                    });
                }
            },
            removePositionCommittee: function (index) {
                var value = tempPosition[index];
                tempPosition.splice(index, 1);
                postCategory(tempPosition).done(function () {
                    app.committeePosition.splice(index, 1);
                }).fail(function (xhr) {
                    tempPosition.push(value);
					var message =  xhr.getResponseHeader("Message");
					if(!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
                });
            },
        },

    });
</script>
<?php $this->layout->end_script(); ?>
