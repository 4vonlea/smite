<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<!-- Card stats -->
			<div class="row">
				<div class="col-xl-4 col-lg-4">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Finish Transaction</h5>
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
				<div class="col-xl-4 col-lg-4">
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
				<div class="col-xl-4 col-lg-4">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Unfinish Transaction</h5>
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
			<datagrid
				@loaded_data="loadedGrid"
				ref="datagrid"
				api-url="<?= base_url('admin/transaction/grid'); ?>"
				:fields="[{name:'invoice',sortField:'invoice','title':'No Invoice'}, {name:'fullname',sortField:'fullname','title':'Member Name'},{name:'status_payment',sortField:'status_payment'},{name:'t_updated_at',sortField:'t_updated_at',title:'Date'},{name:'t_id','title':'Aksi'}]">
				<template slot="status_payment" slot-scope="props">
					{{ props.row.status_payment.toUpperCase() }}
				</template>
				<template  slot="t_id" slot-scope="props">
					<div class="table-button-container">
						<button @click="detail(props)" class="btn btn-info btn-sm">
							<span class="fa fa-search"></span> Detail
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
						<td>{{ detailModel.updated_at }}</td>
					</tr>
					<tr>
						<th class="text-center" colspan="4">Billing Information</th>
					</tr>
					<tr>
						<th>Bill To</th>
						<td colspan="3">{{ detailModel.member.fullname }}</td>
					</tr>
					<tr>
						<th>Address</th>
						<td colspan="3">{{ detailModel.member.address+", "+detailModel.member.city }}</td>
					</tr>
					<tr>
						<th>Amount</th>
						<td colspan="3">{{ amount }}</td>
					</tr>
					<tr>
						<th>Status</th>
						<td colspan="3">{{ detailModel.status_payment.toUpperCase() }}</td>
					</tr>
					<tr>
						<th class="text-center" colspan="4">Details</th>
					</tr>
					<tr>
						<th colspan="2">Event Name</th>
						<th colspan="2">Price</th>
					</tr>
					<tr v-for="dt in detailModel.details">
						<td colspan="2">{{ dt.product_name }}</td>
						<td colspan="2">{{ formatCurrency(dt.price) }}</td>
					</tr>
				</table>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button v-if="detailModel.status_payment != '<?=Transaction_m::STATUS_FINISH;?>'" @click="verifyPayment" type="button" class="btn btn-primary" :disabled="verifying">
					<i v-if="verifying" class="fa fa-spin fa-spinner"></i>
					Verified Payment
				</button>
				<a :href="'<?=base_url('admin/transaction/download/invoice');?>/'+detailModel.id" target="_blank" v-if="detailModel.status_payment == '<?=Transaction_m::STATUS_FINISH;?>'" class="btn btn-primary" >Download Invoice</a>
				<a :href="'<?=base_url('admin/transaction/download/proof');?>/'+detailModel.id" target="_blank" v-if="detailModel.status_payment == '<?=Transaction_m::STATUS_FINISH;?>'" class="btn btn-primary" >Download Payment Proof</a>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<script>
	var info = <?=json_encode(Transaction_m::$transaction_status);?>;
    var app = new Vue({
        el: '#app',
        data: {
			info:info,
            detailModel: {member:{},details:[],status_payment:""},
            pagination: {},
            verifying:false,
        },
		computed:{
            amount(){
                var price = 0;
                for(var d in this.detailModel.details){
                    if(this.detailModel.details[d])
                        price += this.detailModel.details[d].price;
				}
                return this.formatCurrency(price);
			}
		},
        methods: {
            loadedGrid: function (data) {
                this.pagination = data;
            },
			verifyPayment(){
                var url = "<?=base_url('admin/transaction/verify');?>";
                var app = this;
                app.verifying = true;
                $.post(url,this.detailModel,null,'JSON')
                    .done(function (res) {
                        if(res.status){
                            app.detailModel.status_payment = "<?=Transaction_m::STATUS_FINISH;?>";
                            Swal.fire("Success","Transaction success verified !","success");
						}else{
                            Swal.fire("Failed","Failed to verify transaction","error");
                        }
                    }).fail(function (xhr) {
                    Swal.fire("Failed","Failed to load data !","error");
                }).always(function () {
                    app.verifying = false;
                });
			},
            detail(row){
                app.$refs.datagrid.loading = true;
                var url = "<?=base_url('admin/transaction/detail');?>";
                $.post(url,{id:row.row.invoice},null,'JSON')
                    .done(function (res) {
                        app.detailModel = res;
                        $("#modal-detail").modal("show");
                    }).fail(function (xhr) {
                    Swal.fire("Failed","Failed to load data !","error");
                }).always(function () {
                    app.$refs.datagrid.loading = false;
                });
			},
			formatCurrency(price){
                return new Intl.NumberFormat("id-ID",{ style: 'currency',currency:"IDR"} ).format(price);
			}
        }
    });
</script>
<?php $this->layout->end_script(); ?>
