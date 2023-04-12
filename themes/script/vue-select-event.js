let templateSelectEvent = `
<section>
    <div class="row mt-2">
        <div class="col-md-12">
            <ul class="nav nav-pills mb-2">
                <li v-for="cat in eventCategory" style="cursor:pointer" class="nav-item">
                    <span class="nav-link text-center" @click="showCategory = cat.name"
                        :class="{'active':showCategory == cat.name}">
                        <span>{{ cat.category }}</span>
                        <span class="d-block">{{ cat.heldOn }}</span>
                    </span>
                </li>
                <li v-if="showHotelBooking" class="nav-item" style="cursor:pointer">
                    <span class="nav-link" @click="showCategory = 'hotel-booking'"  :class="{'active':showCategory == 'hotel-booking'}"> Hotel Booking </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="accordion accordion-quaternary col-md-12">
        <div v-for="(event, index) in events" class="mt-2" v-bind:key="index">
            <div class="card card-achievement" v-if="showCategory == event.categoryGroup">
                <div :id="'accordion-'+index" class="card-body collapse show table-responsive">
                    <div class="alert alert-success text-center" style="margin-bottom:0px" v-if="event.followed">
                        <h5 class="mb-0" style="color: black;">You follow this event</h5>
                        <a class="btn btn-primary" :href="tagNameUrl(event.id,user.id)"
                            target="_blank">Download Name Tag
                        </a>
                    </div>
                    <div v-else>
                        <div v-if="event.participant >= event.kouta" class="alert alert-warning text-center">
                            Sorry qouta for this event is full
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-start">{{ event.name }}
                                    <!--<br /><span style="font-size: 14px;" v-if="event.event_required">(You must follow event
                                        <strong>{{ event.event_required }}</strong> to participate this event)</span>-->
                                    </th>
                                    <th v-for="pricing in event.pricingName" class="text-center"><span
                                            v-html="pricing.title"></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="member in event.memberStatus">
                                    <td>As {{ member }}</td>
                                    <td v-for="pricing in event.pricingName" class="text-center">
                                        <span v-if="pricing.pricing[member]">
                                            <span v-show="pricing.pricing[member].price != 0">{{
                                                formatCurrency(pricing.pricing[member].price) }}</span>
                                            <span
                                                v-show="pricing.pricing[member].price != 0 && pricing.pricing[member].price_in_usd != 0">
                                                / </span>
                                            <span
                                                v-show="pricing.pricing[member].price_in_usd != 0">{{formatCurrency(pricing.pricing[member].price_in_usd,
                                                'USD')}}</span>

                                            <button type="button"
                                                @click="addToCart(pricing.pricing[member],member,event.name,event.id)"
                                                v-if="pricing.pricing[member].available && !pricing.pricing[member].added && !pricing.pricing[member].waiting_payment"
                                                :disabled="adding" class="btn btn-sm btn-primary"><i v-if="adding"
                                                    class="fa fa-spin fa-spinner"></i> Add To Cart</button>
                                            <button type="button" v-if="!pricing.pricing[member].available"
                                                style="cursor:not-allowed;color:#fff;" aria-disabled="true" disabled
                                                class="btn btn-sm btn-danger">Not Available</button>
                                            <button type="button" v-if="pricing.pricing[member].waiting_payment"
                                                style="cursor:not-allowed;color:#fff;" aria-disabled="true" disabled
                                                class="btn btn-sm btn-info">Waiting Payment</button>
                                            <button type="button" v-if="pricing.pricing[member].added"
                                                style="cursor:default;color:#fff;" aria-disabled="true" disabled
                                                class="btn btn-sm btn-success">Added</button>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-achievement" v-if="showCategory == 'hotel-booking' && showHotelBooking">
            <div class="card-header card-bg card__shadow">
                <h4 class="card-title m-0">
                    Hotel Booking
                </h4>
            </div>
            <div class="card-body collapse show table-responsive">
                <slot name="hotel-component"></slot>
            </div>
        </div>
        <slot name="footer" v-bind:count="countAdded"></slot>
    </div>
</section>
`;
Vue.component("select-event", {
	template: templateSelectEvent,
	props: {
		showHotelBooking: {
			type: Boolean,
			default: true,
		},
		tagNamePath: {
			type: String,
		},
		events: {
			type: Array,
			default: () => [],
		},
		addCartUrl: {
			type: String,
		},
	},
	computed: {
		eventCategory() {
			let category = {};
			this.events.forEach(function (val) {
				let heldOn = "";
				let heldOnObject = {};
				try {
					heldOnObject = JSON.parse(val.held_on);
					heldOn =
						heldOnObject.start == heldOnObject.end
							? moment(heldOnObject.start).format("DD MMM YYYY")
							: `${moment(heldOnObject.start).format("DD MMM YYYY")} - ${moment(
									heldOnObject.end
							  ).format("DD MMM YYYY")}`;
				} catch (e) {
					console.log(e);
				}
				let keyObject = `${val.category} ${moment(heldOnObject.start).unix()}`;
				let categoryGroup = `${val.category} ${heldOn}`;
				val.categoryGroup = categoryGroup;
				let objectGroup = {
					name: categoryGroup,
					category: val.category,
					heldOn: heldOn,
				};
				if (typeof category[keyObject] == "undefined") {
					category[keyObject] = objectGroup;
				}
			});

			const orderedCategory = Object.keys(category)
				.sort()
				.reduce((obj, key) => {
					obj[key] = category[key];
					return obj;
				}, {});
			let [firstKey] = Object.keys(orderedCategory);
			if (firstKey) {
				this.showCategory = orderedCategory[firstKey].name;
			}
			return orderedCategory;
		},
		countAdded() {
			var count = 0;
			for (var event in this.events) {
				for (var pricingName in this.events[event].pricingName) {
					for (var pricing in this.events[event].pricingName[pricingName]
						.pricing) {
						if (
							this.events[event].pricingName[pricingName].pricing[pricing]
								.added == 1 &&
							!this.events[event].followed
						) {
							count++;
						}
					}
				}
			}
			return count;
		},
	},
	data() {
		return {
			showCategory: "",
			adding: false,
		};
	},
	mounted() {},
	methods: {
		addToCart(event, statusMember, event_name, event_id) {
			var page = this;
			this.adding = true;
			event.member_status = statusMember;
			event.event_name = event_name;
			event.event_id = event_id;
			$.post(this.addCartUrl, event, function (res) {
				if (res.status) {
					event.added = 1;
				} else {
					Swal.fire("Info", res.message, "warning");
				}
			})
				.fail(function () {
					Swal.fire("Fail", "Failed adding to cart !", "error");
				})
				.always(function () {
					page.adding = false;
				});
		},
		tagNameUrl(eventId, userId) {
			return this.tagNamePath ?? +`${eventId}/${userId}`;
		},
		formatCurrency(price, currency = "IDR") {
			return new Intl.NumberFormat("id-ID", {
				style: "currency",
				currency: currency,
			}).format(price);
		},
	},
});
