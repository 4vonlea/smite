export default Vue.component("PageEvents", {
    template: `
        <div class="achievement-area-copy">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div v-if="!fail">            
				<div v-if="user.verified_by_admin == 0" class="alert alert-info">
					<h4>Your status is being reviewed</h4>
					<p>The administrator needs to review and approve your status. Please come back later to check your status.
					You will receive an email when a decision has been made, and <strong> you cannot participate in the event until your status is accepted</strong></p>
				</div>
				<div v-else >
					<div class="row">
						<div class="col-md-9">
						<div class="overflow-hidden mb-1">
							<h2 class="font-weight-normal color-heading text-7 mb-0"><strong class="font-weight-extra-bold">Events</strong></h2>
						</div>
						<div class="overflow-hidden mb-4 pb-3">
							<p class="mb-0">Please select the event you want. We suggest you to make a payment immediately (credit card or virtual account) after checkout. *the price exclude administration fee</p>
						</div>
						</div>
						<div class="col-md-3"></div>
					</div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <ul class="nav nav-pills mb-2">
                                <li v-for="cat in eventCategory" style="cursor:pointer" class="nav-item">
                                    <span class="nav-link" @click="showCategory = cat" :class="{'active':showCategory == cat}">{{ cat }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
					<div class="row">
						<div class="accordion accordion-quaternary col-md-12">
							<div v-for="(event, index) in events"  v-bind:key="index">
                                <div class="card card-achievement" v-if="showCategory == event.category">
                                    <div class="card-header">
                                        <h4 class="card-title m-0">
                                            {{ event.name }} 
                                            <br/><span style="font-size: 14px;" v-if="event.event_required">(You must follow event <strong>{{ event.event_required }}</strong> to participate this event)</span>
                                        </h4>
                                    </div>
                                    <div :id="'accordion-'+index" class="collapse show table-responsive">
                                            <div class="alert alert-success text-center" style="margin-bottom:0px" v-if="event.followed">
                                                <h5 class="mb-0" style="color: black;">You follow this event</h5>
                                                <!--<a class="btn btn-default" :href="'<?=base_url('member/area/card');?>/'+event.id+'/'+user.id" target="_blank">Download Name Tag</a>-->
                                                <!--<a class="btn btn-default" :href="'<?=base_url('member/area/certificate');?>/'+event.id+'/'+user.id" target="_blank">Download Certificate</a>-->
                                            </div>
                                            <div v-else >
                                                <div v-if="event.participant >= event.kouta" class="alert alert-warning text-center">
                                                    <h4>Sorry qouta for this full event</h4>
                                                </div>
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Status As</th>
                                                            <th v-for="pricing in event.pricingName" class="text-center"><span v-html="pricing.title"></span></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="member in event.memberStatus">
                                                            <td>{{ member }}</td>
                                                            <td v-for="pricing in event.pricingName" class="text-center">
                                                                <span v-if="pricing.pricing[member]">

                                                                    <span v-show="pricing.pricing[member].price != 0">{{ formatCurrency(pricing.pricing[member].price) }}</span>
                                                                    <span v-show="pricing.pricing[member].price != 0 && pricing.pricing[member].price_in_usd != 0"> / </span>
                                                                    <span v-show="pricing.pricing[member].price_in_usd != 0">{{formatCurrency(pricing.pricing[member].price_in_usd, 'USD')}}</span>

                                                                    <button @click="addToCart(pricing.pricing[member],member,event.name,event.id)" v-if="pricing.pricing[member].available && !pricing.pricing[member].added && !pricing.pricing[member].waiting_payment" :disabled="adding"  class="btn btn-sm btn-purple"><i v-if="adding" class="fa fa-spin fa-spinner"></i> Add To Cart</button>
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
					<div class="row">
						<div class="col-md-12  text-right">
							<router-link class="btn btn-primary mt-4" to="/billing"><span style="font-size: 12px;border-right: 1px solid" class="badge badge-warning">{{ countAdded }}</span> <i class="fa fa-shopping-cart fa-1x"></i> Make Payment </router-link>
						</div>
					</div>
				</div>
            </div>
        </div>
    `,
    data: function() {
        return {
            loading: false,
            fail: false,
            user: {},
            adding: false,
            events: null,
            showCategory:"",
        }
    },
    created() {
        this.fetchEvents()
    },
    watch: {
        '$route': 'fetchEvents'
    },
    computed: {
        eventCategory(){
            let category = [];
            this.events.forEach(function(val){
                if(category.includes(val.category) == false){
                    category.push(val.category);
                }
            });
            return category;
        },
        countAdded() {
            var count = 0;
            for (var event in this.events) {
                for (var pricingName in this.events[event].pricingName) {
                    for (var pricing in this.events[event].pricingName[pricingName].pricing) {
                        if (this.events[event].pricingName[pricingName].pricing[pricing].added == 1 && !this.events[event].followed) {
                            count++;
                        }
                    }
                }
            }
            return count;
        }
    },
    methods: {
        addToCart(event, member, event_name, event_id) {
            var page = this;
            this.adding = true;
            event.member_status = member;
            event.event_name = event_name;
            event.event_id = event_id;
            $.post(this.baseUrl + "add_cart", event, function(res) {
                if (res.status) {
                    event.added = 1;
                } else {
                    Swal.fire('Fail', res.message, 'warning');
                }
            }).fail(function() {
                Swal.fire('Fail', "Failed adding to cart !", 'error');
            }).always(function() {
                page.adding = false;
            });
        },
        fetchEvents() {
            var page = this;
            page.loading = true;
            page.fail = false;
            $.post(this.baseUrl + "get_events", null, function(res) {
                if (res.status) {
                    page.events = res.events;
                    if(res.events.length > 0){
                        page.showCategory = res.events[0].category
                    }
                } else {
                    page.fail = true;
                }
            }).fail(function() {
                page.fail = true;
            }).always(function() {
                page.loading = false;
            });
        },
        formatCurrency(price, currency = 'IDR') {
            return new Intl.NumberFormat("id-ID", {
                style: 'currency',
                currency: currency
            }).format(price);
        }
    }
});