<?php $this->layout->begin_head(); ?>
<style>
	.card {
		min-height: 115px
	}

	.table td, .table th {
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
									<h5 class="card-title text-uppercase text-muted mb-0">Settlement Transaction</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_finish }}</span>
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
									<h5 class="card-title text-uppercase text-muted mb-0">Pending Transaction</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_pending }}</span>
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
									<h5 class="card-title text-uppercase text-muted mb-0">Need Verification</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_need_verify }}</span>
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
									<h5 class="card-title text-uppercase text-muted mb-0">Waiting Checkout</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_waiting }}</span>
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
									<h5 class="card-title text-uppercase text-muted mb-0">Expired Transaction</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_unfinish }}</span>
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
						<h3>Transaction</h3>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<datagrid @loaded_data="loadedGrid" ref="datagrid" api-url="<?= base_url('admin/transaction/grid'); ?>" :fields="[{name:'invoice',sortField:'invoice','title':'No Invoice'}, {name:'fullname',sortField:'fullname','title':'Member Name'},{name:'status_payment',sortField:'status_payment'},{name:'t_updated_at',sortField:'t_updated_at',title:'Date'},{name:'t_id','title':'Aksi'}]">
					<template slot="status_payment" slot-scope="props">
						{{ props.row.status_payment.toUpperCase() }}
					</template>
					<template slot="t_id" slot-scope="props">
						<div class="table-button-container">
							<button @click="detail(props)" class="btn btn-info btn-sm">
								<span class="fa fa-search"></span> Detail
							</button>
							<?php if ($this->session->user_session['role'] == User_account_m::ROLE_SUPERADMIN) : ?>
								<button @click="modify(props)" class="btn btn-info btn-sm">
									<span class="fa fa-edit"></span> Modify
								</button>
							<?php endif; ?>
							<a class="btn btn-primary btn-sm" :href="'<?= base_url('admin/notification/index'); ?>/'+props.row.m_id" target="_blank">
								<span class="fa fa-envelope"></span> Email
							</a>

							<button v-if="props.row.status_payment != 'waiting' && props.row.channel != '<?=Transaction_m::CHANNEL_GL;?>'" @click="setAsGuaranteeLetter(props)" class="btn btn-info btn-sm">
								<span class="fa fa-edit"></span> Set As GL Transaction
							</button>
						</div>
					</template>
				</datagrid>
			</div>

		</div>
	</div>
</div>
<div class="modal" id="modal-detail">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Detail Transaction</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>Invoice Number</th>
						<td>{{ detailModel.id }}</td>
						<th>Invoice Date</th>
						<td :colspan="[isGroup? '2' : '1']">{{ detailModel.updated_at }}</td>
					</tr>
					<tr>
						<th class="text-center" :colspan="[isGroup? '5' : '4']">Billing Information</th>
					</tr>
					<tr>
						<th>Bill To</th>
						<td :colspan="isGroup ? '4' : '3'">{{ isGroup ? detailModel.member_id : detailModel.member.fullname }}</td>
					</tr>
					<tr v-if="!isGroup">
						<th>Email</th>
						<td :colspan="isGroup ? '4' : '3'">{{ detailModel.member.email }}</td>
					</tr>
					<tr v-if="!isGroup">
						<th>Address</th>
						<td :colspan="isGroup ? '4' : '3'">{{ detailModel.member.address+", "+detailModel.member.city }}</td>
					</tr>
					<tr>
						<th>Amount</th>
						<td :colspan="isGroup ? '4' : '3'">{{ amount }}</td>
					</tr>
					<tr>
						<th>Channel Payment</th>
						<td :colspan="isGroup ? '4' : '3'">
							{{ detailModel.channel }}
							<div v-if="detailModel.paymentGatewayInfo.product" class="card mt-3">
								<div class="card-body">
									<h5 class="card-title">
										Bank Info : {{ detailModel.paymentGatewayInfo.product }}
									</h5>
									<h5 class="card-text">
										Account Number : {{ detailModel.paymentGatewayInfo.productNumber}}
									</h5>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Status</th>
						<td :colspan="isGroup ? '4' : '3'">{{ detailModel.status_payment.toUpperCase() }}</td>
					</tr>
					<tr v-if="detailModel.channel != 'ESPAY'">
						<th>{{ detailModel.channel == 'EDC' || detailModel.channel == 'MANUAL TRANSFER' ? 'Code Reference' : 'Additional Info' }}</th>
						<td :colspan="isGroup ? '4' : '3'">{{ detailModel.message_payment }}</td>
					</tr>


					<tr v-if="detailModel.payment_proof">
						<th>Transfer Proof</th>
						<td :colspan="isGroup ? '4' : '3'">
							<a target="_blank" :href="'<?= base_url('admin/transaction/file'); ?>/'+detailModel.payment_proof">Click Here To View</a>
						</td>
					</tr>
					<tr v-if="detailModel.client_message">
						<th>Participant Message <br />(Upload Transfer Proof)</th>
						<td :colspan="isGroup ? '4' : '3'">
							{{ detailModel.client_message }}
						</td>
					</tr>
					<tr>
						<th class="text-center" :colspan="[isGroup ? '5' : '4']">Details</th>
					</tr>
					<tr>
						<th colspan="2" v-if="isGroup">Member Name</th>
						<th colspan="2">Event Name</th>
						<th colspan="2">Price</th>
					</tr>
					<tr v-for="(dt,ind) in detailSort">
						<td colspan="2" v-if="isGroup">{{ dt.member.fullname }}</td>
						<td colspan="2">{{ dt.product_name }}</td>
						<td colspan="2">
							{{ editUniquePrice == false || dt.event_pricing_id != 0 ?  formatCurrency(dt.price) : "" }}
							<a v-if="dt.event_pricing_id == 0 && editUniquePrice == false" @click="editUniquePrice = true;inputUniquePrice=dt.price;" href="#"><i class="fa fa-edit"></i></a>
							<div v-if="dt.event_pricing_id == 0 && editUniquePrice == true" class="input-group mb-3">
								<input type="text" v-model="inputUniquePrice" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
								<div class="input-group-append">
									<button :disabled="sendingUniquePrice" @click="saveEditDetail(dt.id,ind)" class="btn btn-outlined-default" type="button">
										<i v-show="sendingUniquePrice == false" class="fa fa-save"></i>
										<i v-show="sendingUniquePrice == true" class="fa fa-spin fa-spinner"></i>
									</button>
									<button :disabled="sendingUniquePrice" @click="editUniquePrice = false;" class="btn btn-outlined-default" type="button">
										<i class="fa fa-times"></i>
									</button>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<div class="btn-toolbar">
					<button v-if="detailModel.status_payment == '<?= Transaction_m::STATUS_PENDING; ?>'" @click="expirePayment" type="button" class="btn btn-primary" :disabled="expiring">
						<i v-if="verifying" class="fa fa-spin fa-spinner"></i>
						Expire Payment
					</button>
					<button v-if="detailModel.status_payment != '<?= Transaction_m::STATUS_EXPIRE; ?>' && detailModel.status_payment != '<?= Transaction_m::STATUS_FINISH; ?>'" @click="verifyPayment" type="button" class="btn btn-primary" :disabled="verifying">
						<i v-if="verifying" class="fa fa-spin fa-spinner"></i>
						Verify Payment
					</button>
					<a :href="'<?= base_url('admin/transaction/download/invoice'); ?>/'+detailModel.id" target="_blank" class="btn btn-primary">Download Invoice</a>
					<a :href="'<?= base_url('admin/transaction/download/proof'); ?>/'+detailModel.id" target="_blank" v-if="detailModel.status_payment == '<?= Transaction_m::STATUS_FINISH; ?>'" class="btn btn-primary">Download Bukti Registrasi</a>
					<button :disabled="sendingProof" v-on:click="resendPaymentProof(detailModel)" v-if="detailModel.status_payment == '<?= Transaction_m::STATUS_FINISH; ?>'" class="btn btn-primary"><i v-if="sendingProof" class="fa fa-spin fa-spinner"></i> Resend Bukti Registrasi</button>
					<button :disabled="sendingProof" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>

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
				<table class="table table-bordered">
					<tr>
						<th>Invoice Number</th>
						<td>{{ modifyModel.id }}</td>
						<th>Invoice Date</th>
						<td :colspan="[isGroup? '2' : '1']">{{ modifyModel.updated_at }}</td>
					</tr>
					<tr>
						<th class="text-center" :colspan="[isGroup? '5' : '4']">Billing Information</th>
					</tr>
					<tr>
						<th>Bill To</th>
						<td :colspan="isGroup ? '4' : '3'">{{ modifyModel.member.fullname }}</td>
					</tr>
					<tr v-if="modifyModel.member.email">
						<th>Email</th>
						<td :colspan="isGroup ? '4' : '3'">{{ modifyModel.member.email }}</td>
					</tr>
					<tr>
						<th>Address</th>
						<td :colspan="isGroup ? '4' : '3'">{{ modifyModel.member.address+", "+modifyModel.member.city }}</td>
					</tr>
					<tr>
						<th>Amount</th>
						<td :colspan="isGroup ? '4' : '3'">{{ amount }}</td>
					</tr>
					<tr>
						<th>Channel Payment</th>
						<td :colspan="isGroup ? '4' : '3'">{{ modifyModel.channel }}</td>
					</tr>
					<tr>
						<th>Status</th>
						<td :colspan="isGroup ? '4' : '3'">
							<select class="form-control" v-model="modifyModel.status_payment">
								<option value="<?= Transaction_m::STATUS_WAITING; ?>">Waiting Checkout</option>
								<option value="<?= Transaction_m::STATUS_PENDING; ?>">Pending</option>
								<option value="<?= Transaction_m::STATUS_FINISH; ?>">Settlement</option>
								<option value="<?= Transaction_m::STATUS_EXPIRE; ?>">Expired</option>
							</select>
						</td>
					</tr>
					<tr v-if="detailModel.channel != 'ESPAY'">
						<th>{{ modifyModel.channel == 'EDC' || modifyModel.channel == 'MANUAL TRANSFER' ? 'Code Reference' : 'Additional Info' }}</th>
						<td :colspan="isGroup ? '4' : '3'">{{ modifyModel.message_payment }}</td>
					</tr>

					<tr v-if="modifyModel.payment_proof">
						<th>Transfer Proof</th>
						<td :colspan="isGroup ? '4' : '3'">
							<a target="_blank" :href="'<?= base_url('admin/transaction/file'); ?>/'+modifyModel.payment_proof">Click Here To View</a>
						</td>
					</tr>
					<tr v-if="modifyModel.client_message">
						<th>Participant Message <br />(Upload Transfer Proof)</th>
						<td :colspan="isGroup ? '4' : '3'">
							{{ modifyModel.client_message }}
						</td>
					</tr>
					<tr>
						<th class="text-center" :colspan="[isGroup ? '5' : '4']">Details</th>
					</tr>
					<tr>
						<th colspan="2" v-if="isGroup">Member Name</th>
						<th colspan="2">Event Name</th>
						<th :colspan="[isGroup ? '2' : '1']">Price</th>
						<th v-if="!isGroup"><button @click="modifyModel.details.push({member_id:modifyModel.member.id,transaction_id:modifyModel.id,event_pricing_id:0,product_name:'',price:0,isDeleted:0})" class="btn btn-primary btn-sm">Add Item</button></th>
					</tr>
					<tr v-for="(dt,ind) in modifySort" :class="{'bg-red':dt.isDeleted}">
						<td colspan="2" v-if="isGroup">{{ dt.member.fullname }}</td>
						<td v-if="!dt.id" colspan="2">
							<select class="form-control" @change="changeEvent($event,dt)" v-model="dt.event_pricing_id">
								<option value="0">Select Event (Event filtered by status)</option>
								<option v-for="item in listEvent" :data-product_name="`${item.event_name} (${item.condition})`" :data-price="item.price" :value="item.id"> {{ `${item.event_name} (${item.name})` }}</option>
							</select>
						</td>
						<td v-else colspan="2">{{ dt.product_name }}</td>
						<td colspan="2">
							<div class="input-group input-group-sm">
								<input type="text" v-model="dt.price" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
								<div class="input-group-append">
									<button v-if="dt.isDeleted == 0" @click="deleteItem(dt,ind)" class="btn btn-outlined-default" type="button">
										<i class="fa fa-times"></i>
									</button>
									<button v-if="dt.isDeleted == 1" @click="dt.isDeleted = 0" class="btn btn-outlined-default" type="button">
										<i class="fa fa-redo"></i>
									</button>

								</div>
							</div>
						</td>
					</tr>
				</table>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" @click="saveModify">Save</button>
				<button :disabled="sendingProof" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modal-gl">
<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Set As Guarantee Letter</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<table class="table table-bordered">
					<tr>
						<th>ID Invoice</th>
						<td>{{ glModel.id }}
					</tr>
					<tr>
						<th>Bill To</th>
						<td :colspan="isGroup ? '4' : '3'">{{ isGroup ? glModel.member_id : glModel.member.fullname }}</td>
					</tr>
				</table>
				<hr/>
				<form id="form-gl" ref="formGl">
					
					<input type="hidden" name="id" :value="glModel.id"/>
					<div class="form-group">
						<label>Sponsor Name</label>
						<input type="text" name="midtrans_data[sponsorName]" :class="{'is-invalid': glModel.validation_error['midtrans_data[sponsorName]']}" v-model="glModel.midtrans_data.sponsorName" class="form-control" />
						<div v-if="glModel.validation_error['midtrans_data[sponsorName]']" class="invalid-feedback">
							{{ glModel.validation_error['midtrans_data[sponsorName]'] }}
						</div>
					</div>
					<div class="form-group">
						<label>Pay Plan Date</label>
						<vuejs-datepicker :input-class="{'form-control':true,'is-invalid': glModel.validation_error['midtrans_data[payPlanDate]']}" wrapper-class="" name="midtrans_data[payPlanDate]" v-model="glModel.midtrans_data.payPlanDate" ></vuejs-datepicker>
						<div v-if="glModel.validation_error['midtrans_data[payPlanDate]']" class="invalid-feedback d-block">
							{{ glModel.validation_error['midtrans_data[payPlanDate]'] }}
						</div>
					</div>
					<div class="form-gorup">
						<label>Guarantee Letter File <small>(pdf,jpg,jpeg Max 2 MB)</small></label>
						<input type="file" name="fileName" :class="{'is-invalid': glModel.validation_error.fileName}" class="form-control" />
						<div v-if="glModel.validation_error.fileName" class="invalid-feedback">
							{{ glModel.validation_error.fileName }}
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" :disabled="savingGl" class="btn btn-primary" @click="saveGuaranteeLetter">
					Save
					<i class="fa fa-spin fa-spinner" v-if="savingGl"></i>
				</button>
				<button :disabled="savingGl" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
</div>
<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.umd.js" charset="utf-8"></script>

<script>
	var info = <?= json_encode(Transaction_m::$transaction_status); ?>;
    Vue.component('vue-ctk-date-time-picker', window['vue-ctk-date-time-picker']);
	var app = new Vue({
		el: '#app',
		components: {
            vuejsDatepicker,
        },
		data: {
			info: info,
			listEvent: [],
			detailModel: {
				member: {},
				details: [{
					member: {}
				}],
				paymentGatewayInfo:{},
				status_payment: ""
			},
			modifyModel: {
				member: {},
				details: [],
				status_payment: ""
			},
			glModel:{
				member: {},
				midtrans_data:{},
				validation_error:{}
			},
			pagination: {},
			verifying: false,
			expiring: false,
			sendingProof: false,
			editUniquePrice: false,
			inputUniquePrice: '',
			sendingUniquePrice: false,
			isGroup: false,
			savingGl:false,
		},
		computed: {
			amount() {
				var price = 0;
				for (var d in this.detailModel.details) {
					if (this.detailModel.details[d])
						price += Number(this.detailModel.details[d].price);
				}
				return this.formatCurrency(price);
			},
			detailSort() {
				return this.transactionsSort(this.detailModel.details);
			},
			modifySort() {
				return this.transactionsSort(this.modifyModel.details);
			}
		},
		methods: {

			transactionsSort(data) {
				return data.sort(function(a, b) {
					return (a.event_pricing_id > b.event_pricing_id) ? -1 : 1;
				})
			},
			saveModify(event) {
				event.target.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
				event.target.setAttribute("disabled", "disabled");
				$.post("<?= base_url('admin/transaction/save_modify'); ?>", this.modifyModel, null, 'JSON')
					.done(function(res) {
						if (res.status && res.status == false) {
							Swal.fire("Failed", res.message, "error");
						} else {
							app.modifyModel = res.model;
							app.listEvent = res.listEvent;
							app.modifyModel = res.model;
							Swal.fire("Success", "Transaction Saved Successfully", "success");
						}
					}).always(function() {
						event.target.innerHTML = "Save";
						event.target.removeAttribute("disabled");
					}).fail(function() {
						Swal.fire("Failed", "Failed to save", "error");
					});

			},
			deleteItem(dt, ind) {
				if (dt.id) {
					dt.isDeleted = 1;
				} else {
					this.modifyModel.details.splice(ind, 1);
				}

			},
			changeEvent(event, dt) {
				let options = event.target.options;
				let selectedIndex = event.target.selectedIndex;
				dt.price = options[selectedIndex].dataset.price;
				dt.product_name = options[selectedIndex].dataset.product_name;
			},
			saveEditDetail(id, ind) {
				app.sendingUniquePrice = true;
				$.post("<?= base_url('admin/transaction/update_detail'); ?>", {
						id: id,
						price: app.inputUniquePrice,
					}, null, 'JSON')
					.done(function(res) {
						if (res.status) {
							app.editUniquePrice = false;
							app.detailModel.details[ind].price = app.inputUniquePrice;
						} else {
							Swal.fire("Failed", "Gagal mengirim ulang bukti registrasi", "error");
						}
					}).fail(function(xhr) {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function() {
						app.sendingUniquePrice = false;
					});
			},
			resendPaymentProof(data) {
				this.sendingProof = true;
				$.post("<?= base_url('admin/transaction/resend/proof'); ?>/" + data.id, null, 'JSON')
					.done(function(res) {
						if (res.status) {
							Swal.fire("Success", "Bukti Registrasi berhasil dikirim ulang !", "success");
						} else {
							Swal.fire("Failed", "Gagal mengirim ulang bukti registrasi", "error");
						}
					}).fail(function(xhr) {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function() {
						app.sendingProof = false;
					});
			},
			loadedGrid: function(data) {
				this.pagination = data;
			},
			expirePayment() {
				var url = "<?= base_url('admin/transaction/expire'); ?>";
				var app = this;
				app.expiring = true;
				$.post(url, this.detailModel, null, 'JSON')
					.done(function(res) {
						if (res.status) {
							app.$refs.datagrid.refresh();
							app.detailModel.status_payment = "<?= Transaction_m::STATUS_EXPIRE; ?>";
							Swal.fire("Success", "The payment has been declared expired !", "success");
						} else {
							Swal.fire("Failed", "Failed to expire transaction", "error");
						}
					}).fail(function(xhr) {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function() {
						app.expiring = false;
					});
			},
			verifyPayment() {
				var url = "<?= base_url('admin/transaction/verify'); ?>";
				var app = this;
				app.verifying = true;
				$.post(url, this.detailModel, null, 'JSON')
					.done(function(res) {
						if (res.status) {
							app.detailModel.status_payment = "<?= Transaction_m::STATUS_FINISH; ?>";
							Swal.fire("Success", "Transaction success verified !", "success");
						} else {
							Swal.fire("Failed", "Failed to verify transaction", "error");
						}
					}).fail(function(xhr) {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function() {
						app.verifying = false;
					});
			},
			detail(row) {
				app.$refs.datagrid.loading = true;
				var url = "<?= base_url('admin/transaction/detail'); ?>";
				$.post(url, {
						id: row.row.invoice
					}, null, 'JSON')
					.done(function(res) {
						app.detailModel = res.model;
						app.isGroup = $.isArray(app.detailModel.member);
						$("#modal-detail").modal("show");
					}).fail(function(xhr) {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function() {
						app.$refs.datagrid.loading = false;
					});
			},
			modify(row) {
				app.$refs.datagrid.loading = true;
				var url = "<?= base_url('admin/transaction/detail'); ?>";
				$.post(url, {
						id: row.row.invoice
					}, null, 'JSON')
					.done(function(res) {
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
			setAsGuaranteeLetter(row){
				app.$refs.datagrid.loading = true;
				var url = "<?= base_url('admin/transaction/detail'); ?>";
				$.post(url, {
						id: row.row.invoice
					}, null, 'JSON')
					.done(function(res) {
						if(res.model.midtrans_data){
							try{
								res.model.midtrans_data = JSON.parse(res.model.midtrans_data);
							}catch{
								res.model.midtrans_data = {
									filename:'',
									payPlanDate:'',
									sponsorName:'',
								};
							}
						}else{
							res.model.midtrans_data = {
								filename:'',
								payPlanDate:'',
								sponsorName:'',
							};
						}
						res.model.validation_error = {};
						app.isGroup = $.isArray(res.model.member);
						app.glModel = res.model;
						$('#form-gl').trigger('reset');
						$("#modal-gl").modal("show");
					}).fail(function(xhr) {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function() {
						app.$refs.datagrid.loading = false;
					});
			},
			saveGuaranteeLetter(){
				var formData = new FormData(this.$refs.formGl);
				if(app.glModel.midtrans_data.payPlanDate)
					formData.set("payPlanDate",moment(app.glModel.midtrans_data.payPlanDate).format('YYYY-MM-DD'));
				this.savingGl = true;
				$.ajax({
					url: '<?= base_url('admin/transaction/save_gl'); ?>',
					type: 'POST',
					contentType: false,
					cache: false,
					processData: false,
					data: formData
				}).done(function(res) {
					if (res.status == false && res.validation_error) {
						app.glModel.validation_error = res.validation_error
					} else if (res.status == false && res.message) {
						Swal.fire('Fail', res.message, 'error');
					} else {
						app.glModel.validation_error = {};
						app.$refs.datagrid.refresh();
						Swal.fire({
							title: 'Successfully',
							type: 'success',
							html: `<p>Set As Guarantee Letter</p>`,
						});
					}
				}).fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function(res) {
					app.savingGl = false;
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