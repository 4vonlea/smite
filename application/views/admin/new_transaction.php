<div class="header bg-primary pb-8 pt-5 pt-md-8"></div>
<!-- Page content -->
<div id="app" class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header">
                    <h4 class="card-title">New Transaction</h4>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <!-- Form groups used in grid -->
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" name="id" />
                            <div class="form-group">
                                <label class="control-label">Member Name</label>
                                <v-select v-model="memberModel" placeholder="Please type a member name" @search="fetchMember" @input="memberChange" :options="listMembers" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Status Payment</label>
                                <?= form_dropdown('status_payment', ['waiting'=>'Waiting','pending' => 'Pending', 'settlement' => 'settlement'], 'pending', [':class' => "{'is-invalid':validationError.status}", 'class' => 'form-control', 'placeholder' => 'Select your status !', 'v-model' => 'transaction.status_payment']); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Method Payment</label>
                                <?= form_dropdown('channel', ['CASH' => 'CASH', 'MANUAL TRANSFER' => 'MANUAL TRANSFER', Transaction_m::CHANNEL_GL => Transaction_m::CHANNEL_GL], 'CASH', [':class' => "{'is-invalid':validationError.status}", ':class'=>"{'is-invalid': validation_error.channel}", 'class' => 'form-control', 'placeholder' => 'Select your status !', 'v-model' => 'transaction.channel']); ?>
                                <div v-if="validation_error.channel" class="invalid-feedback d-block">
                                    {{ validation_error.channel }}
                                </div>
                            </div>
                            <div v-if="transaction.channel == '<?=Transaction_m::CHANNEL_GL;?>'" class="form-group">
                                <label>Sponsor Name</label>
                                <input type="text" name="midtrans_data[sponsorName]" :class="{'is-invalid': validation_error['midtrans_data[sponsorName]']}" v-model="transaction.midtrans_data.sponsorName" class="form-control" />
                                <div v-if="validation_error['midtrans_data[sponsorName]']" class="invalid-feedback">
                                    {{ validation_error['midtrans_data[sponsorName]'] }}
                                </div>
                            </div>
                            <div v-if="transaction.channel == '<?=Transaction_m::CHANNEL_GL;?>'" class="form-group">
                                <label>Payment Plan Date - Sponsor</label>
                                <vuejs-datepicker :input-class="{'form-control':true,'is-invalid': validation_error['midtrans_data[payPlanDate]']}" wrapper-class="" name="midtrans_data[payPlanDate]" v-model="transaction.midtrans_data.payPlanDate"></vuejs-datepicker>
                                <div v-if="validation_error['midtrans_data[payPlanDate]']" class="invalid-feedback d-block">
                                    {{ validation_error['midtrans_data[payPlanDate]'] }}
                                </div>
                            </div>
                            <div v-if="transaction.channel == '<?=Transaction_m::CHANNEL_GL;?>'" class="form-group">
                                <label>Payment Plan Date - Committee</label>
                                <vuejs-datepicker :input-class="{'form-control':true,'is-invalid': validation_error['midtrans_data[expiredPayDate]']}" wrapper-class="" name="midtrans_data[expiredPayDate]" v-model="transaction.midtrans_data.expiredPayDate"></vuejs-datepicker>
                                <div v-if="validation_error['midtrans_data[expiredPayDate]']" class="invalid-feedback d-block">
                                    {{ validation_error['midtrans_data[expiredPayDate]'] }}
                                </div>
                            </div>
                            <div v-if="transaction.channel == '<?=Transaction_m::CHANNEL_GL;?>'" class="form-gorup">
                                <label>Guarantee Letter File <small>(pdf,jpg,jpeg Max 2 MB)</small></label>
                                <input ref="fileGuarantee" type="file" name="fileName" :class="{'is-invalid': validation_error.fileName}" class="form-control" />
                                <div v-if="validation_error.fileName" class="invalid-feedback">
                                    {{ validation_error.fileName }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <ul class="nav nav-pills mb-2">
                                <li v-for="cat in eventCategory" style="cursor:pointer" class="nav-item">
                                    <span class="nav-link" @click="showCategory = cat" :class="{'active':showCategory == cat}">{{ cat }}</span>
                                </li>
                                <li class="nav-item" style="cursor:pointer">
                                    <span class="nav-link" @click="showCategory = 'hotel-booking'" :class="{'active':showCategory == 'hotel-booking'}"> Hotel Booking </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="accordion accordion-quaternary col-md-12">
                            <div v-for="(event, index) in events" v-bind:key="index">
                                <div class="card card-achievement" v-if="showCategory == event.category">
                                    <div class="card-header bg-default">
                                        <a class="card-title m-0 text-light" data-toggle="collapse" :href="'#accordion-'+index">
                                            {{ event.name }}
                                            <br /><span style="font-size: 14px;" v-if="event.event_required">(You must follow event <strong>{{ event.event_required }}</strong> to participate this event)</span>
                                        </a>
                                    </div>
                                    <div :id="'accordion-'+index" class="card-body collapse table-responsive">
                                        <div class="alert alert-success text-center" style="margin-bottom:0px" v-if="event.followed">
                                            <h5 class="mb-0" style="color: black;">Member follow this event</h5>
                                        </div>
                                        <div v-else>
                                            <div v-if="event.participant >= event.kouta" class="alert alert-warning text-center">
                                                <h4>Sorry qouta for this event is full</h4>
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
                                                                <br />
                                                                <v-button @click="addToCart(pricing.pricing[member],member,event.name,event.id,$event)" v-if="pricing.pricing[member].available && !pricing.pricing[member].added && !pricing.pricing[member].waiting_payment" class="btn btn-sm btn-primary">Add To Cart</v-button>
                                                                <button v-if="!pricing.pricing[member].available" style="cursor:not-allowed;color:#fff;" aria-disabled="true" disabled class="btn btn-sm btn-danger">Not Available</button>
                                                                <button v-if="pricing.pricing[member].waiting_payment" style="cursor:not-allowed;color:#fff;" aria-disabled="true" disabled class="btn btn-sm btn-info">Waiting Payment</button>
                                                                <button v-if="pricing.pricing[member].added" style="cursor:default;color:#fff;" aria-disabled="true" disabled class="btn btn-sm btn-success">Added</button>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-achievement" v-if="showCategory == 'hotel-booking'">
                                <div class="card-header">
                                    <h4 class="card-title m-0">
                                        Hotel Booking
                                    </h4>
                                </div>
                                <div class="card-body collapse show table-responsive">
                                    <hotel-booking :show-list="false" :callback="afterBook" :post-data="{memberId:memberModel.code,transaction:transaction}" :booking="booking" book-url="<?= base_url('admin/transaction/add_cart'); ?>" search-url="<?= base_url('api/available_room'); ?>" :min-date="minBookingDate" :max-date="maxBookingDate"></hotel-booking>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Transaction Cart</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="transactionDetails.length == 0">
                            <tr>
                                <td colspan="4" class="text-center">
                                    Data Not Available
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-if="transactionDetails.length > 0">
                            <tr v-for="(row,ind) in transactionDetails" :key="row.id">
                                <td>{{ ind+1 }}</td>
                                <td style="white-space: pre !important;">{{ row.product_name }}</td>
                                <td>{{ formatCurrency(row.price) }}</td>
                                <td>
                                    <v-button icon="fa fa-trash" class="btn btn-danger" @click="deleteItem(row,ind,$event)"></v-button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot v-if="transactionDetails.length > 0">
                            <tr>
                                <td colspan="2">Total</td>
                                <td>{{ formatCurrency(totalPrice) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                <div class="row">
                        <div class="col-6">
                            <h4>ID Invoice : {{ transaction.id }}</h4>
                        </div>
                        <div class="col-6 text-right">
                            <v-button @click="saveTransaction($event)" class="btn btn-primary" icon="fa fa-save">Save</v-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->layout->begin_head(); ?>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<link rel="stylesheet" type="text/css" href="https://unpkg.com/vue2-datepicker@3.11.0/index.css">
<?php $this->layout->end_head(); ?>

<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/vue-hotel-booking.js?") ?>"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<script src="https://unpkg.com/vue2-datepicker@3.11.0" charset="utf-8"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/v-button.js"); ?>"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        'el': "#app",
        components: {
            vuejsDatepicker
        },
        data: {
            memberModel: null,
            showCategory: "",
            events: [],
            listMembers: [],
            validationError: {},
            transaction: {
                status_payment:'waiting',
                channel:'CASH',
                midtrans_data:{}
            },
            transactionDetails: [],
            booking: [],
            minBookingDate: "2022-07-10",
            maxBookingDate: "2022-07-20",
            validation_error:{}
        },
        computed: {
            totalPrice() {
                let price = 0;
                this.transactionDetails.forEach((val) => {
                    price += Number(val.price);
                })
                return price;
            },
            eventCategory() {
                let category = [];
                this.events.forEach(function(val) {
                    if (category.includes(val.category) == false) {
                        category.push(val.category);
                    }
                });
                return category;
            },
        },
        methods: {
            saveTransaction(self){
                var formData = new FormData();
				if(this.transaction.midtrans_data && this.transaction.midtrans_data.sponsorName)
                    formData.set("midtrans_data[sponsorName]",this.transaction.sponsorName);
				if(this.transaction.midtrans_data && this.transaction.midtrans_data.payPlanDate)
					formData.set("midtrans_data[payPlanDate]",moment(this.transaction.midtrans_data.payPlanDate).format('YYYY-MM-DD'));
				if(this.transaction.midtrans_data && this.transaction.midtrans_data.expiredPayDate)
					formData.set("midtrans_data[expiredPayDate]",moment(this.transaction.midtrans_data.expiredPayDate).format('YYYY-MM-DD'));
                if(this.$refs.fileGuarantee && this.$refs.fileGuarantee.files.length > 0){
                    var file = this.$refs.fileGuarantee.files[0];
                    formData.set("fileName",file);
                }
                if(!this.transaction.channel){
                    this.validation_error = {'channel':'Payment method required'};
                    Swal.fire('Warning',"Please set payment method !", 'warning');
                    return true;
                }
                formData.set("id",this.transaction.id);
                formData.set("channel",this.transaction.channel);
                formData.set("status_payment",this.transaction.status_payment);
                self.toggleLoading();
				$.ajax({
					url: '<?= base_url('admin/transaction/save_gl'); ?>',
					type: 'POST',
					contentType: false,
					cache: false,
					processData: false,
					data: formData
				}).done((res) => {
					if (res.status == false && res.validation_error) {
						this.validation_error = res.validation_error
						Swal.fire('Fail',"Please check your form !", 'error');
					} else if (res.status == false && res.message) {
						Swal.fire('Fail', res.message, 'error');
					} else {
						this.validation_error = {};
						Swal.fire({
							title: 'Successfully',
							type: 'success',
							html: `<p>Transaction Saved</p>`,
						});
					}
				}).fail((xhr) => {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always((res) => {
                    self.toggleLoading();
				});
            },
            deleteItem(detail, ind, self) {
                Swal.fire({
                    title: 'Do you want to delete the item?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`,
                    type:'warning'
                }).then((result) => {
                    console.log(result);
                    if (result.value) {
                        self.toggleLoading();
                        $.post("<?= base_url("admin/transaction/delete_item"); ?>", {
                            id: detail.id,
                            transaction_id:detail.transaction_id
                        }, (res) => {
                            if (res.status) {
                                Swal.fire('Success', "Item Deleted !", 'success');
                            }
                            this.transactionDetails.splice(ind, 1);
                        }).fail(function() {
                            Swal.fire('Fail', "Failed to delete item !", 'error');
                        }).always(() => {
                            self.toggleLoading();
                        });
                    }
                });
            },
            afterBook(status, res) {
                if (status) {
                    if(!this.transaction.id){
                        this.transaction = res.transaction;
                    }
                    this.transactionDetails = res.transaction_details;
                }
            },
            addToCart(event, member, event_name, event_id, self) {
                event.member_status = member;
                event.event_name = event_name;
                event.event_id = event_id;
                event.memberId = this.memberModel.code;
                event.transaction = this.transaction;
                self.toggleLoading();
                $.post("<?= base_url('admin/transaction/add_cart'); ?>", event, (res) => {
                    if (res.status) {
                        event.added = 1;
                        if(!this.transaction.id){
                            this.transaction = res.transaction;
                        }
                        this.transactionDetails = res.transaction_details;
                    } else {
                        Swal.fire('Fail', res.message, 'warning');
                    }
                }).fail(function() {
                    Swal.fire('Fail', "Failed adding to cart !", 'error');
                }).always(() => {
                    self.toggleLoading();
                });
            },
            memberChange(value) {
                $.post("<?= base_url('admin/transaction/get_events'); ?>", {
                    member_id: value.code,
                    status: value.status
                }, (res) => {
                    if (res.status) {
                        this.events = res.events;
                        this.booking = res.booking;
                        this.minBookingDate = res.rangeBooking.start;
                        this.maxBookingDate = res.rangeBooking.end;
                        if (res.events.length > 0) {
                            this.showCategory = res.events[0].category
                        }
                    } else {}
                }).fail(function() {}).always(function() {});
            },
            fetchMember(search, loading) {
                loading(true);
                $.post("<?= base_url('admin/member/search'); ?>", {
                    search: search
                }, (res) => {
                    this.listMembers = res.data;
                }).fail(() => {
                    Swal.fire('Warning', ` Failed to fetch members !`, 'warning');
                }).always(() => {
                    loading(false);
                })
            },
            formatCurrency(price, currency = 'IDR') {
                return new Intl.NumberFormat("id-ID", {
                    style: 'currency',
                    currency: currency
                }).format(price);
            }
        }
    });
</script>
<?php $this->layout->end_script(); ?>