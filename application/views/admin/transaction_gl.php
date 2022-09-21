<?php $this->layout->begin_head(); ?>
<style>
	.card {
		min-height: 115px
	}

	.table td,
	.table th {
		white-space: initial !important;
	}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.css">
<?php $this->layout->end_head(); ?>
<div class="header bg-primary pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<!-- Card stats -->
			<div class="row">
				<div class="col-xl-4 col-lg-4 mt-2">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Total Transaction</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_transaction }}</span>
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
				<div class="col-xl-4 col-lg-4 mt-2">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Paid Transaction</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.paid_transaction }}</span>
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
				<div class="col-xl-4 col-lg-4 mt-2">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Unpaid Transaction</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.unpaid_transaction }}</span>
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
<!-- Page content -->
<div class="container-fluid mt--7">
	<div class="col-xl-12">
		<div class="card shadow">
			<div class="card-header">
				<div class="row">
					<div class="col-6">
						<h3>Transaction Guarantee Letter</h3>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<datagrid @loaded_data="loadedGrid" ref="datagrid" api-url="<?= base_url('admin/transaction/grid_gl'); ?>" :fields="[{name:'invoice',sortField:'invoice','title':'No Invoice'}, {name:'fullname',sortField:'fullname','title':'Member Name'},{name:'status_payment',sortField:'status_payment','title':'Status Payment'},{name:'status_gl',sortField:'status_gl',title:'Status GL'},{name:'pay_plan_date',sortField:'pay_plan_date',title:'Payment Plan Date'},{name:'t_id','title':'Aksi'}]">
					<template slot="fullname" slot-scope="props">
						{{ props.row.fullname }} <br />
						<span class="badge badge-info">
							Sponsor : {{ props.row.sponsor }}
						</span>
						<a v-if="props.row.filename" :href="'<?= base_url('admin/transaction/file_gl'); ?>/'+props.row.filename" target="_blank" class="btn btn-sm btn-info">
							File Guarantee Letter
						</a>
					</template>
					<template slot="status_payment" slot-scope="props">
						{{ props.row.status_payment.toUpperCase() }}
					</template>
					<template slot="status_gl" slot-scope="props">
						<span v-if="props.row.status_gl == 'Paid'" class="badge badge-success">
							{{ props.row.status_gl }}
						</span>
						<span v-if="props.row.status_gl == 'Unpaid'" class="badge badge-danger">
							{{ props.row.status_gl }}
						</span>
						<div v-if="props.row.status_gl == 'Unpaid' && props.row.expiredPayDate && isOverdue(props.row.expiredPayDate)">
							<span class="badge badge-danger">
								Overtime from  Payment Plan Date - Commitee
							</span>
						</div>
						<div v-if="props.row.status_gl == 'Unpaid' && props.row.pay_plan_date && isOverdue(props.row.pay_plan_date)">
							<span  class="badge badge-warning">
								Overtime from Payment Plan Date - Sponsor
							</span>
						</div>

						<br/>
						<a v-if="props.row.receiptPayment" :href="'<?= base_url('admin/transaction/file_gl'); ?>/'+props.row.receiptPayment+'/true'" target="_blank" class="btn btn-sm btn-info mt-2">
							File Receipt Payment
						</a>

					</template>
					<template slot="pay_plan_date" slot-scope="props">
						<span class="badge badge-info mt-2" style="font-size: 100%;">
						 	Sponsor : {{ props.row.pay_plan_date | formatDate }} <br/>
						</span>
						<span class="badge badge-info mt-2" style="font-size: 100%;" v-if="props.row.expiredPayDate">
							Commitee : {{ props.row.expiredPayDate | formatDate }}
						</span>
					</template>
					<template slot="t_id" slot-scope="props">
						<div class="table-button-container">
							<button @click="modify(props)" class="btn btn-info btn-sm">
								<span class="fa fa-edit"></span> Modify
							</button>
						</div>
					</template>
				</datagrid>
			</div>

		</div>
	</div>
</div>
<div class="modal" id="modal-modify">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Modify Transaction</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body table-responsive">
				<form id="form-gl" ref="formModify">
				<table class="table table-bordered">
					<tr>
						<th>ID Invoice</th>
						<td>
							{{ modifyModel.id }}
							<input type="hidden" name="id" :value="modifyModel.id" />
						</td>
					</tr>
					<tr>
						<th>Total Amount</th>
						<td>{{ amount }}</td>
					</tr>
					<tr>
						<th>Payment Method</th>
						<td>
							<?= form_dropdown('channel', ['CASH' => 'CASH', 'ESPAY' => 'Espay', 'MANUAL TRANSFER' => 'MANUAL TRANSFER', Transaction_m::CHANNEL_GL => Transaction_m::CHANNEL_GL], 'CASH', [':class' => "{'is-invalid':modifyModel.validation_error.channel}", 'class' => 'form-control', 'placeholder' => 'Select your status !', 'v-model' => 'modifyModel.channel']); ?>
						</td>
					</tr>
					<tr>
						<th>Sponsor</th>
						<td>
							<input type="text" class="form-control" name="midtrans_data[sponsorName]" v-model="modifyModel.midtrans_data.sponsorName" />
						</td>
					</tr>
					<tr>
						<th>Payment Plan Date - Sponsor</th>
						<td>
							<vuejs-datepicker :input-class="{'form-control':true,'is-invalid': modifyModel.validation_error.payPlanDate}" wrapper-class="" name="midtrans_data[payPlanDate]" v-model="modifyModel.midtrans_data.payPlanDate"></vuejs-datepicker>
						</td>
					</tr>
					<tr>
						<th>Payment Plan Date - Committee</th>
						<td>
							<vuejs-datepicker :input-class="{'form-control':true,'is-invalid': modifyModel.validation_error.expiredPayDate}" wrapper-class="" name="midtrans_data[expiredPayDate]" v-model="modifyModel.midtrans_data.expiredPayDate"></vuejs-datepicker>
						</td>
					</tr>
					<tr>
						<th>File Guarantee Letter</th>
						<td>

							<a v-if="modifyModel.midtrans_data.fileName" :href="'<?= base_url('admin/transaction/file_gl'); ?>/'+modifyModel.midtrans_data.fileName" target="_blank" class="btn btn-sm btn-info mb-2">
								Download
							</a>
							<input type="file" name="fileName" class="form-control" :class="{'is-invalid': modifyModel.validation_error.fileName}" />
							<div v-if="modifyModel.validation_error.fileName" class="invalid-feedback">
								{{ modifyModel.validation_error.fileName }}
							</div>
						</td>
					</tr>
					<tr>
						<th>File Receipt Payment</th>
						<td>
							<a v-if="modifyModel.midtrans_data.receiptPayment" :href="'<?= base_url('admin/transaction/file_gl'); ?>/'+modifyModel.midtrans_data.receiptPayment+'/true'" target="_blank" class="btn btn-sm btn-info mb-2">
								Download
							</a>
							<input type="file" name="receiptPayment" class="form-control" :class="{'is-invalid': modifyModel.validation_error.receiptPayment}" />
							<div v-if="modifyModel.validation_error.receiptPayment" class="invalid-feedback">
								{{ modifyModel.validation_error.receiptPayment }}
							</div>
						</td>
					</tr>
					<tr>
						<th>Status Payment Guarantee Letter</th>
						<td>
							<button type="button" class="btn" @click="modifyModel.message_payment = '<?=Transaction_m::GL_PAID_MESSAGE;?>'" :class="[modifyModel.message_payment == '<?=Transaction_m::GL_PAID_MESSAGE;?>' ? 'btn-primary':'btn-lighter']">Paid</button>
							<button type="button" class="btn" @click="modifyModel.message_payment = '-'" :class="[modifyModel.message_payment != '<?=Transaction_m::GL_PAID_MESSAGE;?>' ? 'btn-primary':'btn-lighter']">Unpaid</button>
						</td>
					</tr>
				</table>
				</form>
			</div>
			<!-- Modal footer -->
			<div class="modal-footer">
				<button :disabled="savingModify" type="button" class="btn btn-primary" @click="saveModify">
					Save
					<i class="fa fa-spin fa-spinner" v-if="savingModify"></i>
				</button>
				<button :disabled="savingModify" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.umd.js" charset="utf-8"></script>

<script>
	var info = <?= json_encode(Transaction_m::$transaction_status); ?>;
	Vue.component('vue-ctk-date-time-picker', window['vue-ctk-date-time-picker']);
	Vue.filter('formatDate', function(value) {
		if (value) {
			return moment(value).format('DD MMMM YYYY')
		}
	});
	var app = new Vue({
		el: '#app',
		components: {
			vuejsDatepicker,
		},
		data: {
			modifyModel: {
				member: {},
				details: [],
				status_payment: "",
				validation_error: {},
				midtrans_data: {}
			},
			savingModify: false,
			pagination: {},
		},
		computed: {
			amount() {
				var price = 0;
				for (var d in this.modifyModel.details) {
					if (this.modifyModel.details[d])
						price += Number(this.modifyModel.details[d].price);
				}
				return this.formatCurrency(price);
			},
		},
		methods: {
			isOverdue(value){
				return moment(value).isBefore();
			},
			transactionsSort(data) {
				return data.sort(function(a, b) {
					return (a.event_pricing_id > b.event_pricing_id) ? -1 : 1;
				})
			},
			saveModify(event) {
				var formData = new FormData(this.$refs.formModify);
				if(this.modifyModel.midtrans_data.payPlanDate)
					formData.set("midtrans_data[payPlanDate]",moment(this.modifyModel.midtrans_data.payPlanDate).format('YYYY-MM-DD'));
				if(this.modifyModel.midtrans_data.expiredPayDate)
					formData.set("midtrans_data[expiredPayDate]",moment(this.modifyModel.midtrans_data.expiredPayDate).format('YYYY-MM-DD'));
				if(this.modifyModel.midtrans_data.fileName)
					formData.set("midtrans_data[fileName]",this.modifyModel.midtrans_data.fileName);
				if(this.modifyModel.midtrans_data.receiptPayment)
					formData.set("midtrans_data[receiptPayment]",this.modifyModel.midtrans_data.receiptPayment);

				this.savingModify = true;
				formData.set("message_payment",this.modifyModel.message_payment);
				$.ajax({
					url: '<?= base_url('admin/transaction/save_gl'); ?>',
					type: 'POST',
					contentType: false,
					cache: false,
					processData: false,
					data: formData
				}).done(function(res) {
					if (res.status == false && res.validation_error) {
						app.modifyModel.validation_error = res.validation_error
					} else if (res.status == false && res.message) {
						Swal.fire('Fail', res.message, 'error');
					} else {
						app.modifyModel.validation_error = {};
						app.$refs.datagrid.refresh();
						Swal.fire({
							title: 'Successfully',
							type: 'success',
							html: `<p>Guarantee Letter Transaction Saved</p>`,
						});
					}
				}).fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function(res) {
					app.savingModify = false;
				});

			},
			loadedGrid: function(data) {
				this.pagination = data;
			},
			modify(row) {
				app.$refs.datagrid.loading = true;
				var url = "<?= base_url('admin/transaction/detail'); ?>";
				$('#form-gl').trigger('reset');
				$.post(url, {
						id: row.row.invoice
					}, null, 'JSON')
					.done(function(res) {
						if (res.model.midtrans_data) {
							try {
								res.model.midtrans_data = JSON.parse(res.model.midtrans_data);
							} catch {
								res.model.midtrans_data = {
									fileName: '',
									payPlanDate: '',
									sponsorName: '',
									expiredPayDate:'',
								};
							}
						} else {
							res.model.midtrans_data = {
								fileName: '',
								payPlanDate: '',
								sponsorName: '',
								expiredPayDate:'',
							};
						}
						res.model.validation_error = {};
						app.modifyModel = res.model;
						app.isGroup = $.isArray(app.modifyModel.member);
						app.listEvent = res.listEvent;
						$("#modal-modify").modal("show");
					}).fail(function(xhr) {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function() {
						app.$refs.datagrid.loading = false;
					});
			},
			formatCurrency(price) {
				return new Intl.NumberFormat("id-ID", {
					style: 'currency',
					currency: "IDR"
				}).format(price);
			}
		}
	});
</script>
<?php $this->layout->end_script(); ?>