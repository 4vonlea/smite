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
								<td><button class="btn btn-default" @click="detailTransaction(item,$event)">Detail</button></td>
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
							<tr v-for="item in cart">
								<td>
									<a @click="unfollow(item)" href="#billing" title="Remove item" class="fa fa-trash text-danger"></a>
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
								<td></td>
								<td></td>
								<td class="text-right">
								<a :href="appUrl+'member/area/download/invoice/'+current_invoice" target="_blank" class="btn btn-primary" >Download Invoice</a>
								<button :disabled="checking_out" @click="checkout" class="btn btn-primary">
									Checkout <i v-if="checking_out" class="fa fa-spin fa-spinner"></i>
								</button>
								</td>
							</tr>
						</tfoot>
					</table>
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
						<div class="modal-footer">
							<a :href="appUrl+'member/area/download/invoice/'+detailModel.id" target="_blank" v-if="detailModel.finish" class="btn btn-primary" >Download Invoice</a>
							<a :href="appUrl+'member/area/download/proof/'+detailModel.id" target="_blank" v-if="detailModel.finish" class="btn btn-primary" >Download Payment Proof</a>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>					
						</div>
					</div>
				</div>
			</div>
        </div>
    `,
    data: function () {
        return {
        	current_invoice:"",
            loading: false,
            fail: false,
			checking_out:false,
			cart:null,
			transaction:null,
			detailModel:{status_payment:""},
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
    	checkout(){
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
								$.post(page.appUrl+"member/payment/after_checkout",{id:res.invoice,message_payment:result.status_message},function () {
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
			$.post(this.baseUrl + "get_transaction", null, function (res) {
				if (res.status) {
					page.current_invoice = res.current_invoice;
					page.cart = res.cart;
					page.transaction = res.transaction;
				} else {
					page.fail = true;
				}
			}).fail(function () {
				page.fail = true;
			}).always(function () {
				page.loading = false;
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
    			total+= detail[dt].price;
			}
    		return this.formatCurrency(total);
		}
	}
});
