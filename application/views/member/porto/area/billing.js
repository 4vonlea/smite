export default Vue.component("PageBilling", {
    template: `
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div v-if="!loading">
            	<div class="overflow-hidden mb-1">
                	<h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Transaction & Cart</strong></h2>
				</div>
				<div class="overflow-hidden mb-4 pb-3">
					<p class="mb-0">A page to confirm your billing and display invoice history </p>
				</div>
				<div class="row">
					<h4>Transaction History</h4>
					<table class="table table-bordered">
						<thead>
							<th>Date</th>
							<th>No Invoice</th>
							<th>Status</th>
							<th>Total Price</th>
							<th></th>
						</thead>
						<tbody v-if="!transaction">
							<tr>
								<td colspan="5" class="text-center">No Transaction</td>
							</tr>
						</tbody>
						<tbody v-else>
							<tr v-for="item in transaction">
								<td>{{ formatDate(item.updated_at)}}</td>
								<td>{{ item.id}}</td>
								<td>{{ item.status_payment.toUpperCase()}}</td>
								<td>{{ sumPrice(item.detail)}}</td>
								<td>
									<button class="btn btn-default" @click="detailTransaction(item,$event)">Detail</button>
									<button @click="modalProof(item)" v-if="item.status_payment == 'pending'" class="btn btn-default" >Upload Transfer Proof</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="row">
					<h4>Current Cart</h4>
					<div v-if="!cart" class="col-md-12 alert alert-warning">
						<p>You have not selected the events to added</p>
					</div>
					<table v-else class="table">
						<thead>
							<th></th>
							<th>Event Name</th>
							<th>Pricing</th>
						</thead>
						<tbody>
							<tr v-for="item in cartSort">
								<td>
									<a v-if="item.event_pricing_id != '0'" @click="unfollow(item)" href="#billing" title="Remove item" class="fa fa-trash text-danger"></a>
								</td>
								<td>{{ item.product_name}}</td>
								<td>{{ formatCurrency(item.price) }}</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td class="text-right font-weight-bold">Total :</td>    
								<td>{{ formatCurrency(totalPrice) }}</td>
							</tr>
							<tr>
								<td class="text-right" colspan="3">
									<a :href="appUrl+'member/area/download/invoice/'+current_invoice" target="_blank" class="btn btn-primary" >Download Invoice</a>
									<div class="btn-group">
										<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											{{ paymentMethod[selectedPaymentMethod].desc }} <span class="caret"></span>
										</button>
										<div class="dropdown-menu">
											<span v-for="(method,ind) in paymentMethod">
											<button v-if="ind > 0" class="dropdown-item" href="#" @click="selectedPaymentMethod=ind;return false;"> {{ method.desc }}</button>
											</span>
										</div>
									</div>
									<button :disabled="checking_out" @click="checkout" type="button" class="btn btn-primary">
										Checkout
										<i v-if="checking_out" class="fa fa-spin fa-spinner"></i>
									</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div class="modal" id="modal-upload-proof">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Upload Transfer Proof</h4>
						</div>
						<div class="modal-body">
							<form ref="formUpload">
								<div class="form-group">
									<label class="form-control-label">Invoice ID</label>
									<input name="invoice_id" type="text" :value="upload.id" readonly class="form-control" />
								</div>
								<div class="form-group">
									<label class="form-control-label">Amount (Rp)</label>
									<input type="text" :value="sumPrice(upload.detail)" readonly class="form-control" />
								</div>
								<div class="form-group">
									<label class="form-control-label">Proof Transfer(png,jpg,jpeg,pdf)</label>
									<div class="custom-file">
										<input @change="fileChange" name="file_proof" type="file" accept=".png,.jpg,.jpeg,.pdf" :class="{'is-invalid':upload_validation.invalid}" class="custom-file-input" />									
										<label ref="labelFile" class="custom-file-label" for="validatedCustomFile">Choose file...</label>
										<div v-if="upload_validation.invalid" class="invalid-feedback">{{ upload_validation.message_invalid }}</div>
									</div>
								</div>
								<div class="form-group">
									<label class="form-control-label">Message</label>
									<textarea name="message" class="form-control">
									</textarea>
								</div>
							</form>
						</div>
						<div class="modal-footer text-right">
							<button @click="uploadProof($event,upload)" type="button" class="btn btn-primary">Upload</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal" id="modal-detail">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Detail Transaction</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
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
									<td colspan="3">{{ user.fullname }}</td>
								</tr>
								<tr>
									<th>Address</th>
									<td colspan="3">{{ user.address+", "+user.city }}</td>
								</tr>
								<tr>
									<th>Amount</th>
									<td colspan="3">{{ amount }}</td>
								</tr>
								<tr>
									<th>Payment Method</th>
									<td colspan="3">{{ detailModel.channel }}</td>
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
								<tr v-if="detailModel.status_payment == 'pending'">
									<th class="text-center" colspan="4">Info Transfer</th>
								</tr>
								<tr v-if="detailModel.status_payment == 'pending' && detailModel.channel == 'ESPAY'">
									<td colspan="4">
									<table>
										<tr>
											<th>Bank/Vendor Name</th>
											<td>{{ detailEspay.bank_name }}</td>
										</tr>
										<tr>
											<th>Product Name</th>
											<td>{{ detailEspay.product_name }}</td>
										</tr>
										<tr>
											<th>Account Number</th>
											<td>{{ detailEspay.product_value }}</td>
										</tr>
										<tr>
											<th>Amount</th>
											<td> 
												{{ formatCurrency(detailEspay.amount) }} 
												<br/>
												<small>*Amount may differ due to additional charge from Espay </small>
											</td>
										</tr>
									</table>
									</td>
								<tr>
								<tr v-if="detailModel.status_payment == 'pending' && detailModel.channel == 'MANUAL TRANSFER'">
									<td colspan="4">
										<p>Please transfer <b>{{ amount }}</b> to one of the following bank accounts
										<br/>Then upload proof of payment (receipts, SMS banking screenshoot, etc) on Transaction History </p>
										<div class="row">
											<div class="col-sm-6" v-for="account in detailModel.banks">
												<div class="card">
														<h3 class="card-title">{{ account.bank }}</h3>
														<p class="card-text">
															<table>
																<tr>
																	<th>Account Number</th>
																	<td>:</td>
																	<td>{{ account.no_rekening }}</td>
																</tr>												
																<tr>
																	<th>Account Holder</th>
																	<td>:</td>
																	<td>{{ account.holder }}</td>
																</tr>												
															</table>
														</p>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div class="modal-footer">
							<a :href="appUrl+'member/area/download/invoice/'+detailModel.id" target="_blank" class="btn btn-primary" >Download Invoice</a>
							<a :href="appUrl+'member/area/download/proof/'+detailModel.id" target="_blank" v-if="detailModel.finish" class="btn btn-primary" >Download Bukti Registrasi</a>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>					
						</div>
					</div>
				</div>
			</div>
			<div class="modal" id="modal-select-payment">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Select Payment Method</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
						<iframe id="sgoplus-iframe" style="width:100%"></iframe>
						</div>
					</div>
				</div>
			</div>
			
			<div class="modal" id="modal-manual_payment">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Info Payment</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body table-responsive">
							<p>Please transfer <b>{{ formatCurrency(manual_payment.ammount) }}</b> to one of the following bank accounts
							<br/>Then upload proof of payment (receipts, SMS banking screenshoot, etc) on Transaction History </p>
							<div class="row">
								<div class="col-sm-6" v-for="account in manual_payment.banks">
									<div class="card">
										<div class="card-body">
											<h3 class="card-title">{{ account.bank }}</h3>
											<p class="card-text">
												<table>
													<tr><th>Account Number</th><td>:</td><td>{{ account.no_rekening }}</td></tr>												
													<tr><th>Account Holder</th><td>:</td><td>{{ account.holder }}</td></tr>												
												</table>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>					
						</div>
					</div>
				</div>
			</div>
        </div>
    `,
    data: function () {
        return {
			current_invoice: "",
			loading: false,
			fail: false,
			checking_out: false,
			cart: null,
			transaction: null,
			detailModel: {status_payment: ""},
			manual_payment: {"banks":[{'bank': 'BNI', 'no_rekening': "0212", "holder": "Muhammad Zaien"}],"ammount":0},
			upload:{},
			paymentMethod:[{key:"0",desc:"Select Payment Method"}],
			selectedPaymentMethod:0,
			upload_validation:{invalid:false,message_invalid:""},
		}
    },
	created() {
		this.fetchTransaction()
	},
	watch: {
		'$route': 'fetchTransaction'
	},
	computed:{
    	totalPrice(){
    		var total = 0;
			for(var i in this.cart){
				total+=Number(this.cart[i].price);
			}
			return total;
		},
		detailEspay(){
			if(this.detailModel.midtrans_data)
				return JSON.parse(this.detailModel.midtrans_data);
			return {};
		},
		amount(){
			var price = 0;
			for(var d in this.detailModel.details){
				if(this.detailModel.details[d])
					price += Number(this.detailModel.details[d].price);
			}
			return this.formatCurrency(price);
		},
		cartSort(){
    		return this.cart.sort(function (a,b) {
				return (a.event_pricing_id > b.event_pricing_id) ? -1:1;
			})
		}
	},
	methods: {
    	fileChange(event){
			this.$refs.labelFile.innerHTML = event.currentTarget.files[0].name;
		},
    	uploadProof(evt,upload){
    		var page = this;
			var btn = evt.currentTarget;
			btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
			$.ajax({
				url:page.appUrl+"member/area/upload_proof",
				cache: false,
				type:'POST',
				contentType:false,
				processData:false,
				dataType:"JSON",
				data:new FormData(page.$refs.formUpload),
				success:function (res) {
					if(res.status == false && res.message){
						page.upload_validation.invalid = true;
						page.upload_validation.message_invalid = res.message;
					}else if(res.status){
						upload.status_payment = res.data.status_payment;
						$("#modal-upload-proof").modal("hide");

					}
				}
			}).fail(function () {

			}).always(function () {
				btn.innerHTML = "Upload";
			});
		},
		checkout(){
			if(this.selectedPaymentMethod > 0){
				let selected = this.paymentMethod[this.selectedPaymentMethod];
				if(selected && selected.key == "manualPayment")
					this.checkoutManual();
				if(selected && selected.key == "espay")
					$("#modal-select-payment").modal("show");
			}else{
				Swal.fire('Info',"Please Select Payment method !",'warning');
			}
				
		},
    	checkoutManual(){
    		var page =this;
    		page.checking_out = true;
    		$.ajax({
				url:page.appUrl+"member/payment/checkout",
				cache: false,
				type:'POST',
				dataType:"JSON",
				success:function (res) {
					if(res.status && res.info) {
						Swal.fire('Success',res.message,'info');
						page.fetchTransaction();
					}else if(res.status && res.manual){
						page.manual_payment.ammount = page.totalPrice;
						page.manual_payment.banks = res.manual;
						page.fetchTransaction();
						$("#modal-manual_payment").modal("show");
					}else if(res.status){
						snap.pay(res.token,{
							onSuccess: function(result){
								$.post(page.appUrl+"member/payment/after_checkout",{id:res.invoice,message_payment:result.status_message},function () {
									page.fetchTransaction();
								});
							},
							onPending: function(result){
								$.post(page.appUrl+"member/payment/after_checkout",{id:res.invoice,message_payment:result.status_message},function () {
									page.fetchTransaction();
								});
							},
							onError: function(result){
								$.post(page.appUrl+"member/payment/after_checkout",{id:res.invoice,message_payment:result.status_message,error:'error'},function () {
									page.fetchTransaction();
								});
							}
						});
					}else{
						Swal.fire('Fail',res.message,'error');
					}
				}
			}).fail(function () {
				Swal.fire('Fail',"Failed getting token !",'error');
			}).always(function () {
				page.checking_out = false;
			})
		},
		modalProof(item){
    		this.upload = {};
    		this.upload_validation = {invalid:false,message_invalid:""};
			this.$refs.formUpload.reset();
			this.$refs.labelFile.innerHTML = "Choose File ...";
			this.upload = item;
			$("#modal-upload-proof").modal("show");
		},
    	detailTransaction(item,event){
    		var page = this;
    		event.target.html ="<i class='fa fa-spin fa-spinner'></i>";
			var url = page.appUrl+"member/area/detail_transaction";
			$.post(url,{id:item.id},null,'JSON')
				.done(function (res) {
					page.detailModel = res;
					$("#modal-detail").modal("show");
				}).fail(function (xhr) {
				Swal.fire("Failed","Failed to load data !","error");
			}).always(function () {
				event.target.html ="Detail";
			});
		},
    	unfollow(item){
    		var page = this;
			Swal.fire({
				title: "Are you sure ?",
				text: `You will delete "${item.product_name}" From cart`,
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if(result.value){
					page.loading = true;
					$.post(page.baseUrl+"delete_item_cart",item,function () {
						page.fetchTransaction();
					}).always(function () {
						page.loading = false;
					});
				}
			});
		},
		fetchTransaction() {
			var page = this;
			page.loading = true;
			page.fail = false;
			$.get(this.appUrl+"/member/payment/check_payment")
			.always(function(){
				$.post(page.baseUrl + "get_transaction", null, function (res) {
					if (res.status) {
						page.current_invoice = res.current_invoice;
						page.cart = res.cart;
						page.transaction = res.transaction;
						$.each(res.paymentMethod,function(i,v){
							let sp = v.split(";");
							page.paymentMethod.push({key:sp[0],desc:sp[1]});
						})
						var invoiceID = this.current_invoice;
						var data = {
							key: page.apiKeyEspay,
							paymentId: res.current_invoice,
							backUrl: page.appUrl+`member/area/redirect_client/billing/${invoiceID}`,
						},
						if(SGOSignature){
							sgoPlusIframe = document.getElementById("sgoplus-iframe");
							if (sgoPlusIframe !== null) 
								sgoPlusIframe.src = SGOSignature.getIframeURL(data);
							SGOSignature.receiveForm();
						}
					} else {
						page.fail = true;
					}
				}).fail(function () {
					page.fail = true;
				}).always(function () {
					page.loading = false;
				});
			});
		},
		formatCurrency(price){
			return new Intl.NumberFormat("id-ID",{ style: 'currency',currency:"IDR"} ).format(price);
		},
		formatDate(date){
			return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
		},
		sumPrice(detail){
    		var total = 0;
    		for(var dt in detail){
    			total+= Number(detail[dt].price);
			}
    		return this.formatCurrency(total);
		}
	}
});
