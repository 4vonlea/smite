export default Vue.component("PageEvents", {
	template: `
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div v-if="!fail">            
				<div v-if="user.verified_by_admin == 0" class="alert alert-info">
					<h4>Your status is under review</h4>
					<p>The current administrators need to review and approve your status. Please return to check your status later.
					You will be sent an email when a decision has been made, and <strong>you cannot follow an event before your status accepted</strong></p>
				</div>
				<div v-else >
					<div class="row">
						<div class="col-md-9">
						<div class="overflow-hidden mb-1">
							<h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Events</strong></h2>
						</div>
						<div class="overflow-hidden mb-4 pb-3">
							<p class="mb-0">Please select the event you want. *Event available based on your status and date</p>
						</div>
						</div>
						<div class="col-md-3">
							<router-link class="btn btn-primary mt-4" to="/billing"><span style="font-size: 12px;border-right: 1px solid" class="badge badge-warning">{{ countAdded }}</span> <i class="fa fa-shopping-cart fa-1x"></i> To Cart </router-link>
						</div>
					</div>
					
					<div class="row">
						<div class="accordion accordion-quaternary col-md-12">
							<div  v-for="(event, index) in events" class="card card-default" v-bind:key="index">
								<div class="card-header">
									<h4 class="card-title m-0">
										<a class="accordion-toggle" data-toggle="collapse" :href="'#accordion-'+index" aria-expanded="true">
											{{ event.name }}
										</a>
									</h4>
								</div>
								<div :id="'accordion-'+index" class="collapse show table-responsive">
										<div class="alert alert-success text-center" v-if="event.followed">
											<h5>You are following this event</h5>
											<a class="btn btn-default" :href="'<?=base_url('member/area/card');?>/'+event.id+'/'+user.id" target="_blank">Download Name Tag</a>
											<a class="btn btn-default" :href="'<?=base_url('member/area/certificate');?>/'+event.id+'/'+user.id" target="_blank">Download Certificate</a>
										</div>
										<div v-else >
											<div v-if="event.participant >= event.kouta" class="alert alert-warning text-center">
												<h4>Sorry Kouta for this event is full</h4>
											</div>
											<table class="table">
												<thead>
													<tr>
														<th>Category</th>
														<th v-for="pricing in event.pricingName" class="text-center"><span v-html="pricing.title"></span></th>
													</tr>
												</thead>
												<tbody>
													<tr v-for="member in event.memberStatus">
														<td>{{ member }}</td>
														<td v-for="pricing in event.pricingName" class="text-center">
															<span v-if="pricing.pricing[member]">
															{{ formatCurrency(pricing.pricing[member].price) }}<br/>
															<button @click="addToCart(pricing.pricing[member],member,event.name)" v-if="pricing.pricing[member].available && !pricing.pricing[member].added && !pricing.pricing[member].waiting_payment" :disabled="adding"  class="btn btn-sm btn-warning"><i v-if="adding" class="fa fa-spin fa-spinner"></i> Add To Cart</button>
															<button v-if="!pricing.pricing[member].available" style="cursor:not-allowed;color:#fff;" aria-disabled="true"  disabled class="btn btn-sm btn-danger">Not Available</button>
															<button v-if="pricing.pricing[member].waiting_payment" style="cursor:not-allowed;color:#fff;" aria-disabled="true"  disabled class="btn btn-sm btn-info">Waiting Payment</button>
															<button v-if="pricing.pricing[member].added" style="cursor:default;color:#fff;" aria-disabled="true"  disabled class="btn btn-sm btn-success">Added</button>
															</span>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    `,
	data: function () {
		return {
			loading: false,
			fail: false,
			user: {},
			adding:false,
			events: null,
		}
	},
	created() {
		this.fetchEvents()
	},
	watch: {
		'$route': 'fetchEvents'
	},
	computed:{
		countAdded(){
			var count = 0;
			for(var event in this.events){
				for(var pricingName in this.events[event].pricingName){
					for(var pricing in this.events[event].pricingName[pricingName].pricing ){
						if(this.events[event].pricingName[pricingName].pricing[pricing].added == 1 && !this.events[event].followed) {
							count++;
						}
					}
				}
			}
			return count;
		}
	},
	methods: {
		addToCart(event,member,event_name){
			var page = this;
			this.adding  = true;
			event.member_status = member;
			event.event_name = event_name;
			$.post(this.baseUrl+"add_cart",event,function (res) {
				if(res.status) {
					event.added = 1;
				}else{
					Swal.fire('Fail',res.message,'warning');
				}
			}).fail(function () {
				Swal.fire('Fail',"Failed adding to cart !",'error');
			}).always(function () {
				page.adding  = false;
			});
		},
		fetchEvents() {
			var page = this;
			page.loading = true;
			page.fail = false;
			$.post(this.baseUrl + "get_events", null, function (res) {
				if (res.status) {
					page.events = res.events;
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
		}
	}
});
