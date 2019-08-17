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
								<td>{{ item.status_payment}}</td>
								<td>{{ sumPrice(item.detail)}}</td>
								<td><button class="btn btn-default" @click="detailTransaction(item)">Detail</button></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="row">
					<h4>Cart</h4>
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
								<td>
								<button :disabled="checking_out" @click="checkout" class="btn btn-primary float-right">
									Checkout <i v-if="checking_out" class="fa fa-spin fa-spinner"></i>
								</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
				
			</div>
        </div>
    `,
    data: function () {
        return {
            loading: false,
            fail: false,
			checking_out:false,
			cart:null,
			transaction:null,
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
				total+=this.cart[i].price;
			}
			return total;
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
					if(res.status){
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
    	detailTransaction(item){

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
