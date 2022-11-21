<div class="header bg-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>

<div class="container-fluid mt--7">
	<div key="table" class="row">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h3>Sponsor Stand</h3>
						</div>
						<div class="col-6 text-right">
							<a href="<?=base_url('admin/sponsor/report');?>" class="btn btn-primary">
								Download Report
							</a>
							<button type="button" class="btn btn-primary" @click="onAdd">
								Add Sponsor
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<datagrid ref="datagrid" api-url="<?= base_url('admin/sponsor/grid_stand'); ?>" :fields="[{name:'sponsor',sortField:'sponsor','title':'Sponsor Name'},{name:'id',sortField:'id','title':'Actions'}]">
						<template slot="id" slot-scope="props">
							<div class="table-button-container">
								<v-button @click="edit(props)" icon="fa fa-edit" class="btn btn-info btn-sm">
									Edit
								</v-button>
								<v-button @click="deleteRow(props,$event)" icon="fa fa-trash" class="btn btn-warning btn-sm">
									Delete
								</v-button>
								<a :href="'<?=base_url('admin/sponsor/qr_stand');?>/'+props.row.id" class="btn btn-primary btn-sm" target="_blank">
									<i class="fa fa-qrcode"></i> Download QR Code
								</a>
								<a :href="'<?=base_url('admin/sponsor/report');?>/'+props.row.id" target="_blank" class="btn btn-primary btn-sm">
									<i class="fa fa-file"></i>
									Download Report
								</a>
							</div>
						</template>
					</datagrid>
				</div>
			</div>
		</div>
	</div>
	<div id="modal-form" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ formTitle }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<input type="hidden" v-model="model.id" />
						<label class="control-label">Sponsor Name</label>
						<input type="text" v-model="model.sponsor" class="form-control" />
					</div>
				</div>
				<div class="modal-footer">
					<v-button type="button" @click="save($event)" class="btn btn-primary">Save</v-button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Table -->

<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/v-button.js"); ?>"></script>
<script>
	function model() {
		return {
			id: null,
			sponsor: null,
		}
	}
	var app = new Vue({
		el: '#app',
		data: {
			message: '',
			error: null,
			model: model(),
			formTitle: "Add Sponsor",
		},
		methods: {
			deleteRow(prop, self) {
				Swal.fire({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						self.toggleLoading();
						var url = "<?= base_url('admin/sponsor/delete_stand'); ?>";
						$.post(url, {
								id: prop.row.id
							}, null, "JSON")
							.done(function(res) {
								if (res.status)
									app.$refs.datagrid.refresh();
								else
									Swal.fire("Failed", "Failed to delete !", "error");
							}).fail(function(xhr) {
								var message = xhr.getResponseHeader("Message");
								if (!message)
									message = 'Server fail to response !';
								Swal.fire('Fail', message, 'error');
							}).always(() => {
								self.toggleLoading();

							});
					}
				});
			},
			edit: function(props) {
				this.formTitle = "Edit Sponsor";
				this.model = props.row;
				$("#modal-form").modal("show");
			},
			onAdd: function() {
				this.formTitle = "Add Sponsor";
				this.model = model();
				$("#modal-form").modal("show");
			},
			save(self) {
				self.toggleLoading();
				$.post("<?= base_url('admin/sponsor/save_stand'); ?>", this.model).done((res, text, xhr) => {
					if (res.status) {
						this.$refs.datagrid.refresh();
						$("#modal-form").modal("hide");
					} else {
						Swal.fire("Failed", "Server failed to response !", "error");
					}
				}).fail(function(xhr) {
					if (xhr.responseJSON && xhr.responseJSON.validation)
						app.form.validation = xhr.responseJSON.validation;
					else {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}
				}).always(function() {
					self.toggleLoading();
				});
			}
		}
	});
</script>
<?php $this->layout->end_script(); ?>