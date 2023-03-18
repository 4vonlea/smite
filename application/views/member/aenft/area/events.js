export default Vue.component("PageEvents", {
    template: `
        <div class="cs-iconbox cs-style1 cs-white_bg">
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
                    <select-event :add-cart-url="baseUrl+'add_cart'" :events="events" :show-hotel-booking="false">
                        <template v-slot:hotel-component>
                            <hotel-booking v-if="false" label-class="text-dark" :booking="booking" :book-url="baseUrl+'add_cart'" :search-url="appUrl+'/api/available_room'" :min-date="minBookingDate" :max-date="maxBookingDate"></hotel-booking>
                        </template>
                        <template v-slot:footer="props" >
                            <div class="row">
                                <div class="col-md-12  text-right">
                                    <router-link class="btn btn-primary mt-4" to="/billing"><span style="font-size: 12px;border-right: 1px solid" class="badge badge-warning">{{ props.count }}</span> <i class="fa fa-shopping-cart fa-1x"></i> Make Payment </router-link>
                                </div>
                            </div>
                        </template>
                    </select-event>
				</div>
            </div>
        </div>
    `,
    data: function () {
        return {
            loading: false,
            fail: false,
            user: {},
            adding: false,
            events: [],
            booking:[],
            showCategory: "",
            minBookingDate:"2022-07-10",
            maxBookingDate:"2022-07-20",
        }
    },
    created() {
        this.fetchEvents();
    },
    watch: {
        '$route': 'fetchEvents'
    },
    computed: {
        eventCategory() {
            let category = {};
            this.events.forEach(function (val) {
                let heldOn = "";
                try{
                    let heldOnObject = JSON.parse(val.held_on);
                    heldOn = heldOnObject.start == heldOnObject.end ? 
                                                        moment(heldOnObject.start).format("DD MMM YYYY") :
                                                        `${moment(heldOnObject.start).format("DD MMM YYYY")} - ${moment(heldOnObject.end).format("DD MMM YYYY")}` ;
                }catch (e){

                }
                let categoryGroup = `${val.category} ${heldOn}`;
                val.categoryGroup = categoryGroup;
                let objectGroup = {
                    name : categoryGroup,
                    category : val.category,
                    heldOn : heldOn
                }
                if (typeof category[categoryGroup] == 'undefined') {
                    category[categoryGroup] = objectGroup;
                }
            });
            return category;
        },
    },
    methods: {
        addToCart(event, member, event_name, event_id) {
            var page = this;
            this.adding = true;
            event.member_status = member;
            event.event_name = event_name;
            event.event_id = event_id;
            $.post(this.baseUrl + "add_cart", event, function (res) {
                if (res.status) {
                    event.added = 1;
                } else {
                    Swal.fire('Fail', res.message, 'warning');
                }
            }).fail(function () {
                Swal.fire('Fail', "Failed adding to cart !", 'error');
            }).always(function () {
                page.adding = false;
            });
        },
        fetchEvents() {
            var page = this;
            page.loading = true;
            page.fail = false;
            $.post(this.baseUrl + "get_events", null, function (res) {
                if (res.status) {
                    page.events = res.events;
                    page.booking = res.booking;
                    page.minBookingDate = res.rangeBooking.start;
                    page.maxBookingDate = res.rangeBooking.end;
                    if (res.events.length > 0) {
                        page.showCategory = res.events[0].category
                    }
                } else {
                    page.fail = true;
                }
            }).fail(function () {
                page.fail = true;
            }).always(function () {
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