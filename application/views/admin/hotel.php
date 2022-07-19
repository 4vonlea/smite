<?php

/**
 * @var array $events
 */
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8"></div>
<!-- Page content -->
<div class="container-fluid mt--7">
	<!-- Table -->
	<div class="row" v-show="formMode == 0">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-3">
							<h3>Hotel</h3>
						</div>
						<div class="col-9 text-right">
							<button class="btn btn-primary" @click="addHotel"><i class="fa fa-plus"></i>
								Add Hotel
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<datagrid ref="datagrid" api-url="<?= base_url('admin/hotel/grid'); ?>" :fields="[{name:'name',title:'Hotel Name',sortField:'name'},{name:'address',title:'Address',sortField:'address'},{name:'roomsCount',title:'Number of rooms',sortField:'roomsCount'},{name:'t_id',title:'Action'}]">
						<template slot="t_id" slot-scope="props">
							<button class="btn btn-primary btn-sm" @click="editHotel(props.row,$event)">
								<span class="fa fa-edit"></span> Edit
							</button>
							<button class="btn btn-danger btn-sm" @click="deleteHotel(props.row,$event)">
								<span class="fa fa-trash"></span> Delete
							</button>
						</template>
					</datagrid>
				</div>
			</div>
		</div>
	</div>
	<div class="row" v-show="formMode == 1">
		<div class="col-xl-12">
			<div class="card mb-4">
				<!-- Card header -->
				<div class="card-header">
					<div class="row">
						<div class="col-12">
							<h4 class="card-title">Add Hotel or Room</h4>
						</div>
					</div>
				</div>
				<!-- Card body -->
				<div class="card-body">
					<!-- Form groups used in grid -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Hotel Name</label>
								<input type="text" class="form-control" :class="{'is-invalid':validation.name}" v-model="form.name" />
								<div v-if="validation.name" class="invalid-feedback">
									{{validation.name}}
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Address</label>
								<textarea class="form-control" :class="{'is-invalid':validation.address}" v-model="form.address"></textarea>
								<div v-if="validation.address" class="invalid-feedback">
									{{validation.address}}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Room Name</th>
										<th>Qouta</th>
										<th>Description</th>
										<th>Available To Book</th>
										<th>
											<button @click="addRoom" class="btn btn-sm btn-primary">Add Room</button>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(room,ind) in form.rooms">
										<td>
											<input type="text" class="form-control" :class="{'is-invalid':validation['rooms['+ind+'][name]']}" v-model="room.name" />
											<div v-if="validation['rooms['+ind+'][name]']" class="invalid-feedback">
												{{validation['rooms['+ind+'][name]']}}
											</div>
										</td>
										<td>
											<input type="text" class="form-control" :class="{'is-invalid':validation['rooms['+ind+'][quota]']}" v-model="room.quota" />
											<div v-if="validation['rooms['+ind+'][quota]']" class="invalid-feedback">
												{{validation['rooms['+ind+'][quota]']}}
											</div>
										</td>
										<td>
											<input type="text" class="form-control" :class="{'is-invalid':validation['rooms['+ind+'][description]']}" v-model="room.description" />
											<div v-if="validation['rooms['+ind+'][description]']" class="invalid-feedback">
												{{validation['rooms['+ind+'][description]']}}
											</div>
										</td>
										<td>
											<date-picker v-model="room.range_date" :formatter="momentFormat" value-type="format" :input-class="{'form-control':true,'is-invalid':validation['rooms['+ind+'][range_date]']}" range></date-picker>
											<div v-if="validation['rooms['+ind+'][range_date]']" class="invalid-feedback d-block">
												{{validation['rooms['+ind+'][range_date]']}}
											</div>
										</td>
										<td>
											<button @click="deleteRoom(ind,room,$event)" class="btn btn-sm btn-danger">
												<i class="fa fa-trash"></i>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					<button @click="save($event)" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
					<button @click="formMode=0" class="btn btn-default"><i class="fa fa-reply"></i> Back</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->layout->begin_head(); ?>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/vue2-datepicker@3.11.0/index.css">

<?php $this->layout->end_head(); ?>

<?php $this->layout->begin_script(); ?>
<script src="https://unpkg.com/vue2-datepicker@3.11.0" charset="utf-8"></script>
<script>
	Vue.component('date-picker', DatePicker);
	var app = new Vue({
		el: '#app',
		data: {
			formMode: 0,
			form: {
				rooms: [],
			},
			validation: {
				rooms:[],
			},
			momentFormat: {
				stringify: (date) => {
					return date ? moment(date).format('DD MMM YYYY') : ''
				},
				parse: (value) => {
					return value ? moment(value, 'DD MMM YYYY').toDate() : null
				},
			}
		},
		methods: {
			addHotel() {
				this.formMode = 1;
				this.form = {
					rooms: [],
				};
			},
			addRoom() {
				this.form.rooms.push({name:null,quota:null,desciption:null});
			},
			editHotel(row, evt) {
				this.form = row;
				var btn = evt.currentTarget;
				btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
				btn.setAttribute("disabled", true);
				$.post("<?= base_url('admin/hotel/detail'); ?>", {
					id: row.t_id
				}, (res) => {
					if (res) {
						this.formMode = 1;
						this.form = res;
					}
				}).fail(() => {
					Swal.fire('Fail', "Server failed to response", 'error');
				}).always(() => {
					btn.innerHTML = '<i class="fa fa-edit"></i> Edit';
					btn.removeAttribute("disabled");
				})
			},
			deleteRoom(ind, room, evt) {
				var btn = evt.currentTarget;
				Swal.fire({
					title: "Are you sure ?",
					text: `You will delete Room with name "${room.name}"`,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
						btn.setAttribute("disabled", true);
						if (room.id) {
							$.post("<?= base_url("admin/hotel/delete_room"); ?>", {
								id: room.id
							}, (res) => {
								if (res.status) {
									this.form.rooms.splice(ind, 1);
								} else
									Swal.fire("Failed", res.message, "error");
							}, "JSON").fail(function(xhr) {
								var message = xhr.getResponseHeader("Message");
								if (!message)
									message = 'Server fail to response !';
								Swal.fire('Fail', message, 'error');
							}).always(function() {
								btn.innerHTML = '<i class="fa fa-trash"></i>';
								btn.removeAttribute("disabled");
							});
						} else {
							this.form.rooms.splice(ind, 1);
							btn.innerHTML = '<i class="fa fa-trash"></i>';
							btn.removeAttribute("disabled");
						}
					}
				});
			},
			save(evt) {
				var btn = evt.currentTarget;
				btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
				btn.setAttribute("disabled", true);
				this.validation = {};
				$.post("<?= base_url("admin/hotel/save"); ?>", this.form, (res) => {
					if (res.status) {
						this.form = res.data;
						Swal.fire("Success", "Hotel saved successfully", "success");
						this.$refs.datagrid.refresh();
					} else if (res.validation) {
						this.validation = res.validation;
					} else {
						Swal.fire("Failed", res.message, "error");
					}
				}, "JSON").fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					btn.innerHTML = '<i class="fa fa-save"></i> Save';
					btn.removeAttribute("disabled");
				});
			},
			deleteHotel(row, evt) {
				var btn = evt.currentTarget;
				Swal.fire({
					title: "Are you sure ?",
					text: `You will delete a Hotel with name"${row.name}"`,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
						btn.setAttribute("disabled", true);
						$.post("<?= base_url("admin/hotel/delete"); ?>", {
							id: row.t_id
						}, function(res) {
							if (res.status) {
								Swal.fire("Success", "Hotel deleted successfully", "success");
								app.$refs.datagrid.refresh();
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
		},
	});
</script>
<?php $this->layout->end_script(); ?>