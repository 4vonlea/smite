<?php

/**
 * @var array $participantsCategory
 * @var array $statusList;
 * @var array $participantsUniv
 * @var array $univList;
 */
$this->layout->begin_head();

/**
 * @var $content
 */
$theme_path = base_url("themes/aenft") . "/";
?>
<link href="<?= base_url(); ?>themes/script/chosen/chosen.css" rel="stylesheet">
<style>
    .chosen-container-single .chosen-single {
        height: 38px;
        border-radius: 3px;
        border: 1px solid #CCCCCC;
    }

    .chosen-container-single .chosen-single span {
        padding-top: 4px;
    }

    .chosen-container-single .chosen-single div b {
        margin-top: 4px;
    }
</style>
<?php $this->layout->end_head(); ?>
<!-- Start Hero -->
<div id="home" class="cs-hero cs-style1 cs-type2 cs-bg text-center  cs-ripple_version" data-src="<?= $theme_path; ?>/assets/img/konas/bg-head.jpg" id="home">
    <div class="cs-dark_overlay"></div>
    <div class="container">
        <div class="cs-hero_img wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
            <img src="<?= $theme_path; ?>/assets/img/konas/logo.png" style="width: 100%; max-width: 320px; height: auto;">
        </div>
        <div class="cs-hero_text wow fadeIn" data-wow-duration="1s" data-wow-delay="0.45s" style="margin-top: -50px;">
            <h1 class="cs-hero_title text-uppercase cs-font_60 cs-font_36_sm cs-bold">Registrasi Grup</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.html">Beranda</a></li>
                <li class="breadcrumb-item active text-info" aria-current="page"><i class="fa-solid fa-user-group"></i> Registrasi Grup</li>
            </ol>
        </div>
    </div>
</div>
<!-- End Hero -->
<section id="app" class="padding-top padding-bottom">

    <div class="cs-height_70 cs-height_lg_40"></div>
    <div class="container wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
        <!-- NOTE Setelah Submmit -->
        <div v-if="page == 'registered'" class="cs-iconbox cs-style1 cs-white_bg">
            <div class="alert alert-success">
                <h4 class="text-dark"><i class="fa fa-info"></i> Registration Success</h4>
                <p>We have sent a confirmation link to your email address. To complete the registration process, please click <i>confirmation link</i>.
                    If you don't receive a confirmation email, please check your spam. Then, please make sure you enter a valid email address when filling out the registration form. If you need help, please contact us.</p>
                <p><strong>For additional information, please note your Invoice ID to confirm payment. Payment confirmation can be done through the <a href="<?= base_url('member/register/check_invoice') ?>" style="color:#161D30;text-decoration: underline;" target="_BLANK">Check Invoice</a> page</strong></p>
            </div>

            <div class="card mt-2">
                <div class="card-header card-bg card__shadow  text-center">
                    <h5 class="m-0 p-0">Event</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Bill To</td>
                                <td class="text-center">:</td>
                                <th>{{data.bill_to}}</th>
                            </tr>
                            <tr>
                                <td>Your Email</td>
                                <td class="text-center">:</td>
                                <th>{{data.email}}</th>
                            </tr>
                            <tr>
                                <td>Invoice ID</td>
                                <td class="text-center">:</td>
                                <th>{{data.id_invoice}}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header card-bg card__shadow  text-center">
                    <h5 class="m-0 p-0">Billing Information</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>Event Name</th>
                            <th>
                                <p>Pricing
                                    <span v-show="isUsd">
                                        (<span style="color:#F4AD39">After converting to rupiah</span>)
                                    </span>
                                </p>
                            </th>
                        </thead>
                        <tbody>
                            <tr v-for="item in transactionsSort">
                                <td>{{ item.product_name}}</td>
                                <td>{{ formatCurrency(item.price) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right font-weight-bold border-end">Total :</td>
                                <td>{{ formatCurrency(totalPrice()) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-sm-4 mt-2" v-for="account in paymentBank">
                <div class="card">
                    <div class="card-header card-bg card__shadow ">
                        <h3 class="card-title ">{{ account.bank }}</h3>
                    </div>
                    <div class="card-body">
                        <table class="card-text" style="color: #F5AC39;">
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
                    </div>
                </div>
            </div>

            <div class="alert alert-success mt-2">
                <h4 class="text-dark"><i class="fa fa-info"></i> Payment confirmation</h4>
                <!-- <p><strong>Untuk melakukan konfirmasi pembayaran bisa dilakukan melalui halaman <a href="<?= base_url('member/register/check_invoice') ?>" style="color:#161D30;text-decoration: underline;" target="_BLANK">Check Invoice</a></strong></p> -->
                <p><strong>Payment confirmation can be done through the <a href="<?= base_url('member/register/check_invoice') ?>" style="color:#161D30;text-decoration: underline;" target="_BLANK">Check Invoice</a> page</strong></p>
            </div>
        </div>

        <!-- NOTE Payment -->
        <div v-if="page == 'payment'" class="cs-iconbox cs-style1 cs-white_bg">
            <div class="card mt-2">
                <div class="card-header card-bg card__shadow  text-center">
                    <h5 class="m-0 p-0">Account</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Bill To</td>
                                <td class="text-center">:</td>
                                <th>{{data.bill_to}}</th>
                            </tr>
                            <tr>
                                <td>Invoice ID</td>
                                <td class="text-center">:</td>
                                <th>{{data.id_invoice}}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header card-bg card__shadow  text-center">
                    <h5 class="m-0 p-0">Event</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>Event Name</th>
                            <th>
                                <p>Pricing
                                    <span v-show="isUsd">
                                        (<span style="color:#F4AD39">After converting to rupiah</span>)
                                    </span>
                                </p>
                            </th>
                        </thead>
                        <tbody>
                            <tr v-for="item in transactionsSort">
                                <td>{{ item.product_name}}</td>
                                <td>{{ formatCurrency(item.price) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right font-weight-bold">Total :</td>
                                <td>
                                    {{ formatCurrency(totalPrice()) }}
                                    <span v-show="isUsd">
                                        <br>
                                        <p>After converting to rupiah</p>
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="form-group mb-2">
                <select name="selectedPaymentMethod" id="selectedPaymentMethod" :class="{ 'is-invalid':validation.selectedPaymentMethod}" class="form-control selectedPaymentMethod mt-2 text-center" v-model="selectedPaymentMethod">
                    <option v-for="(method,ind) in paymentMethod" :value="method.key" :selected="method.key == 'manualPayment'">{{method.desc}}</option>
                </select>
                <div v-if="validation.selectedPaymentMethod" class="invalid-feedback">
                    {{ validation.selectedPaymentMethod }}
                </div>
            </div>
            <hr />
            <div class="col-lg-12 text-center">
                <button :disabled="saving" type="button" @click="checkout" class="btn btn-primary">
                    <i v-if="saving" class="fa fa-spin fa-spinner"></i>
                    Checkout
                </button>
                <button v-if="allowBack" type="button" @click="page = 'register'" class="btn btn-primary">
                    Back
                </button>
            </div>
        </div>

        <!-- NOTE Sebelum Submit -->
        <div v-if="page == 'register'" class="cs-iconbox cs-style1 cs-white_bg">
            <div class="alert alert-primary" role="alert">
                <h4 class="text-black"><i class="icofont icofont-info-circle"></i> <b>Perhatian.</h4>
                <p><span style="color: #ff0000;"><strong>1. Pembayaran tidak dapat di <em>refund</em></strong></span></p>
                <p><span style="color: #000000;">2. Pastikan alamat email yang dimasukkan valid dan dapat Anda akses karena kami akan mengirimkan kode aktivasi melalui email tersebut. Akun Anda tidak dapat digunakan kecuali jika sudah&nbsp; diaktifkan terlebih dahulu.</span></p>
            </div>
            <form id="form-register" class="form-border" ref="form">
                <div class="de_tab tab_simple">
                    <div class="de_tab_content">
                        <div class="tab-1">
                            <div class="row wow fadeIn">
                                <div class="col-lg-12">
                                    <div class="form-group mb-2">
                                        <label>Bill To*</label>
                                        <input type="text" :class="{'is-invalid': validation.bill_to}" class="form-control mb-0" name="bill_to" placeholder="Bill To" v-model="model.bill_to" />
                                        <div v-if="validation.bill_to" class="invalid-feedback" v-html="validation.bill_to"></div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label>Your Email* <small>(Invoice will be sent to this email)</small></label>
                                        <input type="text" :class="{'is-invalid': validation.email}" class="form-control mb-0" name="email" placeholder="Email" v-model="model.email" />
                                        <div v-if="validation.email" class="invalid-feedback" v-html="validation.email"></div>
                                    </div>
                                    <!-- NOTE GROUP -->
                                    <div class="form-group mt-3">
                                        <hr />

                                        </h5>
                                        <table class="table border">
                                            <thead class="text-center">
                                                <tr>
                                                    <th class="border-end" width="5%">
                                                        <h5>No</h5>
                                                    </th>
                                                    <th class="border-end" width="50%">
                                                        <h5>Data Members</h5>

                                                    </th>
                                                    <th class="border-end" width="10%">
                                                        <button @click="addMembers" type="button" class="btn btn-primary"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="model.members.length == 0">
                                                    <td class="text-center" colspan="7">
                                                        <h5>No Data</h5>
                                                        <p class="text-danger">
                                                            {{ validation.members }}
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr v-for="(member,index) in model.members" :key="member.id">
                                                    <td class="text-center border-end">
                                                        <h5>{{(index+1)}}</h5>
                                                    </td>
                                                    <td class="border-end">
                                                        <div class="row m-1">
                                                            <div class="form-group col-6 p-2">
                                                                <label class="control-label text-light">NIK KTP (wajib diisi untuk integrasi P2KB)</label>
                                                                <div class="input-group">
                                                                    <input type="text" v-on:keyup.enter="checkMember(member)" v-model="member.nik" placeholder="NIK anda" :class="{'is-invalid':member.validation.nik}" class="form-control mb-0" name="nik" />
                                                                    <button :disabled="member.checking" @click="checkMember(member)" class="btn btn-primary" type="button">
                                                                        <i v-if="member.checking" class="fa fa-spin fa-spinner"></i> Cek NIK di Database P2KB
                                                                    </button>
                                                                </div>
                                                                <div v-if="member.validation.nik" class="d-block invalid-feedback">
                                                                    {{ member.validation.nik }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-6 p-2">
                                                                <label class="control-label text-light">Email (wajib sama dengan email p2kb)</label>
                                                                <input type="text" v-model="member.email" placeholder="Email" :class="{'is-invalid': member.validation.email}" class="form-control mb-0" name="email" />
                                                                <div v-if="member.validation.email" class="invalid-feedback">
                                                                    {{ member.validation.email }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-6 p-2">
                                                                <label class="control-label text-light">Full Name*</label>
                                                                <input type="text" v-model="member.fullname" placeholder="Full Name" :class="{'is-invalid':member.validation.fullname}" class="form-control mb-0" name="fullname" />
                                                                <div v-if="member.validation.fullname" class="invalid-feedback">
                                                                    {{ member.validation.fullname }}
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-6 p-2 dark-select">
                                                                <label class="control-label text-light">Institution</label>
                                                                <v-select placeholder="Select Institution" v-model="member.univ" label="univ_nama" :reduce="univ => univ.univ_id" :options="univList"></v-select>
                                                                <div v-if="member.validation.univ" class="invalid-feedback">
                                                                    {{ member.validation.univ }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-6 p-2">
                                                                <label class="control-label text-light">Status*</label>
                                                                <?= form_dropdown('status', $participantsCategory, '', [':class' => "{'is-invalid':member.validation.status}", 'id' => 'status', 'v-model' => 'member.status', 'class' => 'form-control mb-0', 'placeholder' => 'Select your status !']); ?>
                                                                <div v-if="member.validation.status" class="invalid-feedback" v-html="member.validation.status"></div>
                                                            </div>

                                                            <div class="form-group col-6 p-2" v-if="member.univ == <?= Univ_m::UNIV_OTHER; ?>">
                                                                <label class="control-label text-light">Other Institution*</label>
                                                                <input type="text" v-model="member.other_institution" :class="{ 'is-invalid':member.validation.other_institution} " class="form-control mb-0" name="other_institution" />
                                                                <div v-if="member.validation.other_institution" class="invalid-feedback">
                                                                    {{ member.validation.other_institution }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button @click="model.members.splice(index,1)" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <small class="col-12" for="">*PLEASE FILL YOUR NAME CORRECTLY FOR YOUR CERTIFICATE</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="col-lg-12 text-center mt-2">
                    <v-button type="button" @click="registerMember" style="width: 300px;" class="btn btn-edge btn-primary">
                        Next
                    </v-button>
                </div>
            </form>
        </div>

        <div v-if="page == 'select-event'" class="cs-iconbox cs-style1 cs-white_bg">
            <!-- NOTE EVENTS -->
            <div class="alert alert-info text-center">
                <span class="h6">Events are available based on your status and date</span>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <table class="table">
                        <tbody v-for="(member,index) in model.members" :key="member.id" class="text-light">
                            <tr>
                                <td colspan="2">
                                    {{ member.fullname }} <span class="badge bg-info">{{ findStatus(member.status).kategory }}</span>
                                </td>
                                <td>
                                    <button @click="showModal(member)" class="btn btn-info">
                                        <i class="fa fa-plus"></i> Add Event
                                    </button>
                                </td>
                            </tr>
                            <tr v-for="(followed,index) in model.transactions[member.id]" :key="followed.id+member.id">
                                <td>
                                    {{ Number(index)+1 }}
                                </td>
                                <td>
                                    {{ followed.event_name}}
                                </td>
                                <td>
                                    <v-button @click="(self) => deleteFollowedEvent(self,member,followed)" class="btn btn-danger">
                                        <i class="fa fa-trash"></i> Delete Event
                                    </v-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-default mt-2">
                <div class="card-header card-bg card__shadow  text-center" style="color:#fff">
                    <b>{{ formatCurrency(total()) }}</b>
                    <span v-show="isUsd">
                        <br>
                        <p>After converting to rupiah</p>
                    </span>
                </div>
            </div>
            <div class="col-lg-12 text-center mt-2">
                <v-button type="button" @click="page = 'register'" style="width: 300px;" class="btn btn-edge btn-primary">
                    Prev
                </v-button>
                <v-button type="button" @click="page = 'payment'" style="width: 300px;" class="btn btn-edge btn-primary">
                    Next
                </v-button>
            </div>
        </div>
    </div>

    <div id="modal-event" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Select Event</h5>
                </div>
                <div class="modal-body">
                    <select-event v-if="!loadingEvent" :add-cart-url="'<?= base_url('member/register/group/add_cart'); ?>/'+currentMember.id+'/'+currentMember.status" :on-add="selectedEvent" :events="events" :show-hotel-booking="false">
                        <template v-slot:hotel-component>
                            <hotel-booking label-class="text-dark" :unique-id="tempMemberId" :on-delete="onCancelBooking" :on-book="onBooking" :booking="hotelBooking.booking" book-url="<?= base_url('member/register/add_cart'); ?>" search-url="<?= base_url('api/available_room'); ?>" :min-date="hotelBooking.minBookingDate" :max-date="hotelBooking.maxBookingDate"></hotel-booking>
                        </template>
                        <template v-slot:footer="props">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class=" text-right alert alert-info">
                                        <span>Jumlah event yang diikuti : {{ props.count }}</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </select-event>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal" id="modal-select-payment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">Select Payment Method</h4>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <iframe id="sgoplus-iframe" sandbox="allow-same-origin allow-scripts allow-top-navigation allow-forms" style="width:100%"></iframe>
            </div>
        </div>
    </div>
</div>
<?php $this->layout->begin_head(); ?>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<?php $this->layout->end_head(); ?>
<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/chosen/chosen.jquery.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/v-button.js"); ?>"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/uuid@latest/dist/umd/uuidv4.min.js"></script>
<script src="<?= base_url("themes/script/vue-select-event.js?") ?>"></script>
<script src="<?= base_url("themes/script/vue-hotel-booking.js?") ?>"></script>

<?php if (isset(Settings_m::getEspay()['jsKitUrl'])) : ?>
    <script src="<?= Settings_m::getEspay()['jsKitUrl']; ?>"></script>
<?php endif; ?>
<script>
    var modalPayment = new bootstrap.Modal(document.getElementById('modal-select-payment'));
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        'el': "#app",
        components: {
            vuejsDatepicker
        },
        data: {
            model: <?= $model ? json_encode($model) : " {
                bill_to: '',
                email: '',
                members: [],
                transactions:{},
            }"; ?>,
            currentMember: {},
            statusList: <?= json_encode($statusList); ?>,
            status_selected: "",
            status_text: "",
            univList: <?= json_encode($univlist); ?>,
            saving: false,
            validation: {
                members: ''
            },
            page: 'select-event',
            paymentMethod: [],
            selectedPaymentMethod: '',
            events: [],
            paymentBank: null,
            isUsd: false,
            allowBack: true,
            loadingEvent: false,
        },
        mounted: function() {
            // NOTE Set Payment Method
            let paymentData = <?= json_encode($paymentMethod) ?>;
            let tempPayment = [{
                key: "",
                desc: "Select Payment Method"
            }];
            $.each(paymentData, function(i, v) {
                let sp = v.split(";");
                tempPayment.push({
                    key: sp[0],
                    desc: sp[1]
                });
            })

            this.paymentMethod = tempPayment;
            if (this.continueTransaction) {
                this.page = 'payment';
                this.data = this.continueTransaction.data; //JSON.parse(JSON.stringify(res.data));
                this.model.members = this.continueTransaction.data.members; //JSON.parse(JSON.stringify(res.data.members))
                this.status_selected = this.continueTransaction.status.status_selected;
                this.status_text = this.continueTransaction.status.status_text;
                this.transactions = this.continueTransaction.transactions ? this.continueTransaction.transactions.cart : [];
                this.allowBack = false;
                Vue.nextTick(() => {
                    app.initEspayFrame();
                });
            }
            if (this.events.length > 0) {
                this.showCategory = this.events[0].category
            }
        },
        computed: {
            eventCategory() {
                let category = {};
                this.filteredEvent.forEach(function(val) {
                    let heldOn = "";
                    try {
                        let heldOnObject = JSON.parse(val.held_on);
                        heldOn = heldOnObject.start == heldOnObject.end ?
                            moment(heldOnObject.start).format("DD MMM YYYY") :
                            `${moment(heldOnObject.start).format("DD MMM YYYY")} - ${moment(heldOnObject.end).format("DD MMM YYYY")}`;
                    } catch (e) {

                    }
                    let categoryGroup = `${val.category} ${heldOn}`;
                    val.categoryGroup = categoryGroup;
                    let objectGroup = {
                        name: categoryGroup,
                        category: val.category,
                        heldOn: heldOn
                    }
                    if (typeof category[categoryGroup] == 'undefined') {
                        category[categoryGroup] = objectGroup;
                    }
                });
                return category;
            },
            needVerification() {
                var ret = false;
                var app = this;
                $.each(this.statusList, function(i, v) {
                    if (v.id == app.status_selected) {
                        ret = v.need_verify == "1";
                        return false;
                    }
                });
                return ret;
            },
            transactionsSort() {
                return this.transactions.sort(function(a, b) {
                    return (a.event_pricing_id > b.event_pricing_id) ? -1 : 1;
                })
            },
            filteredEvent() {
                var statusSelected = this.status_selected;
                var status = this.statusList.find(data => data.id == statusSelected);
                status = status ? status.kategory : '';
                var events = [];
                if (this.events) {
                    this.events.forEach(function(item, index) {
                        if ($.inArray(status, item.memberStatus) !== -1) {
                            events.push(item);
                        }
                    });
                }
                return events;
            }
        },
        methods: {
            deleteFollowedEvent(self, member, event) {
                self.toggleLoading();
                $.post("<?= base_url('member/register/group/delete_cart'); ?>", {
                    member_id: member.id,
                    event_pricing_id: event.id,
                }, (res) => {
                    if (res.status) {
                        Vue.set(this.model.transactions, member.id, res.transactions);
                    } else {
                        Swal.fire("Info", res.message, "warning");

                    }
                }).fail((err) => {
                    Swal.fire('Fail', 'Failed to get event data', 'error')
                }).always(() => {
                    self.toggleLoading();
                })
            },
            selectedEvent(event) {
                if (this.model.transactions[this.currentMember.id]) {
                    this.model.transactions[this.currentMember.id].push(event);
                } else {
                    Vue.set(this.model.transactions, this.currentMember.id, [event]);
                }
            },
            findStatus(id) {
                return this.statusList.find(data => data.id == id);
            },
            showModal(member) {
                this.loadingEvent = true;
                this.currentMember = member;
                $("#modal-event").modal("show");
                $.post("<?= base_url('member/register/group/get_events'); ?>", {
                    statusId: member.status,
                    memberId: member.id,
                }, (res) => {
                    this.events = res.events;
                }).fail((err) => {
                    Swal.fire('Fail', 'Failed to get event data', 'error')
                }).always(() => {
                    this.loadingEvent = false;
                })
            },
            totalPrice(idr = true) {
                var total = 0;
                return total;
            },
            total(idr = true) {
                var total = 0;
                return total;
            },
            onlyNumber($event) {
                let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
                    $event.preventDefault();
                }
            },
            registerMember(self) {
                self.toggleLoading();
                $.post("<?= base_url('member/register/group/add_members'); ?>", this.model)
                    .done((res) => {
                        Vue.set(this.model, 'members', res.members);
                        if (res.status) {
                            this.page = "select-event";
                        } else {
                            this.validation = res.message;
                        }
                    })
                    .fail(() => {
                        Swal.fire('Fail', "Failed to save members", 'error');
                    })
                    .always(() => {
                        self.toggleLoading();
                    })
            },
            register() {
                var formData = new FormData(this.$refs.form);
                // var birthday = moment(formData.get('birthday')).format("Y-MM-DD");
                var birthday = moment().format("Y-MM-DD");
                formData.set("birthday", birthday);

                // NOTE Data Event dan Payment
                formData.append('eventAdded', JSON.stringify(app.eventAdded));
                formData.append('paymentMethod', JSON.stringify(app.paymentMethod));
                formData.append('members', JSON.stringify(app.members));
                formData.append('data', JSON.stringify(app.data));
                formData.append('group', true);

                this.saving = true;
                $.ajax({
                    url: '<?= base_url('member/register/group'); ?>',
                    type: 'POST',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData
                }).done(function(res) {
                    if (res.status == false && res.data.validation) {
                        app.validation = res.data.validation;
                        app.members = res.data.members;
                        Swal.fire('Fail', 'Some fields are invalid', 'error').then((result) => {
                            if (result) {
                                $("html, body").animate({
                                    scrollTop: 0
                                }, 1);
                            }
                        });
                    } else if (res.status == false && res.message) {
                        Swal.fire('Fail', res.message, 'error');
                    } else {
                        app.page = 'payment';
                        app.data = JSON.parse(JSON.stringify(res.data));
                        app.members = JSON.parse(JSON.stringify(res.data.members))
                        app.transactions = res.transactions ? res.transactions.cart : [];
                        app.initEspayFrame();
                    }
                }).fail(function(res) {
                    Swal.fire('Fail', 'Server fail to response !', 'error');
                }).always(function(res) {
                    app.saving = false;
                });
            },
            initEspayFrame() {
                var invoiceID = app.data.id_invoice;
                var apiKeyEspay = "<?= Settings_m::getEspay()['apiKey']; ?>";
                var data = {
                    key: apiKeyEspay,
                    paymentId: invoiceID,
                    backUrl: `<?= base_url('member/register/check_invoice'); ?>/${invoiceID}`,
                };
                if (typeof SGOSignature !== "undefined") {
                    var sgoPlusIframe = document.getElementById("sgoplus-iframe");
                    if (sgoPlusIframe !== null)
                        sgoPlusIframe.src = SGOSignature.getIframeURL(data);
                    SGOSignature.receiveForm();
                }
            },
            checkout() {
                let selected = app.paymentMethod.find(data => data.key == app.selectedPaymentMethod);
                if (selected && selected.key == "espay") {
                    modalPayment.show();
                } else if (selected && selected.key == "manualPayment") {
                    var formData = new FormData(this.$refs.form);
                    // var birthday = moment(formData.get('birthday')).format("Y-MM-DD");
                    var birthday = moment().format("Y-MM-DD");
                    formData.set("birthday", birthday);

                    // NOTE Data Event dan Payment
                    formData.append('data', JSON.stringify(app.data));
                    formData.append('selectedPaymentMethod', app.selectedPaymentMethod);

                    this.saving = true;
                    $.ajax({
                        url: '<?= base_url('member/register/checkout/1'); ?>',
                        type: 'POST',
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: formData
                    }).done(function(res) {
                        if (res.statusData == false && res.validation) {
                            app.validation = res.validation
                        } else if (res.statusData == false && res.message) {
                            Swal.fire('Fail', res.message, 'error');
                        } else {
                            app.page = 'registered';
                            app.paymentBank = res.response.manual;
                        }
                    }).fail(function(res) {
                        Swal.fire('Fail', 'Server fail to response !', 'error');
                    }).always(function(res) {
                        app.saving = false;
                    });
                } else {
                    Swal.fire('Info', "Please Select Payment method !", 'warning');
                }
            },
            formatCurrency(price, currency = 'IDR') {
                return new Intl.NumberFormat("id-ID", {
                    style: 'currency',
                    currency: currency
                }).format(price);
            },
            checkOverlap(event) {
                try {
                    let eventHold = JSON.parse(event.held_on);
                    let countOverlap = 0;
                    this.eventAdded.forEach(evendExists => {
                        let eventExistHold = JSON.parse(evendExists.held_on);
                        if (Date.parse(eventExistHold.end) >= Date.parse(eventHold.start) && Date.parse(eventExistHold.start) <= Date.parse(eventHold.end)) {
                            countOverlap++;
                        }
                    })
                    return countOverlap > 0;
                } catch (e) {
                    console.log(e);
                    return true;
                }
            },
            addEvent(e, event, member, eventParent) {
                let isRequired = this.checkRequirement(event.event_required_id);
                let isOverlap = this.checkOverlap(eventParent);

                if (e.target.checked) {
                    if (isOverlap) {
                        $(e.target).prop('checked', false);
                        Swal.fire('Info', `The Selected event overlap with another event!`, 'info');
                    } else if (isRequired) {
                        event.member_status = member;
                        event.event_name = eventParent.name;
                        event.held_on = eventParent.held_on;
                        this.eventAdded.push(event);
                    } else {
                        $(e.target).prop('checked', false);
                        Swal.fire('Info', `You must follow event <b>"${event.event_required}"</b> to participate this event !`, 'info');
                    }
                } else {
                    let eventId = event.id_event;
                    app.removeEvent(event.id_event);
                }
            },
            removeEvent(id) {
                app.eventAdded.forEach((data) => {
                    if (id != 'undefined') {
                        if (data.id_event == id) {
                            app.eventAdded = app.eventAdded.filter(data => data.id_event != id);
                            $(`.${data.id_event}`).prop('checked', false);
                        }
                        if (data.event_required_id == id) {
                            app.removeEvent(data.id_event);
                        }
                    }
                });
            },
            formatDate(date) {
                return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
            },
            checkMember(member) {
                member.checking = true;
                $.get("<?= base_url('member/register/info_member_perdossi'); ?>/" + member.nik, (res) => {
                    if (res.message == "success") {
                        member.kta = res.member.perdossi_no;
                        member.fullname = `${res.member.member_title_front} ${res.member.fullname} ${res.member.member_title_back}`;
                        member.email = res.member.email;
                        member.phone = res.member.member_phone;
                        member.p2kb_member_id = res.member.member_id;
                    }
                }).always(() => {
                    member.checking = false;
                }).fail(() => {
                    Swal.fire('Fail', 'Failed to get member information in perdossi API', 'error')
                })
            },
            addMembers() {
                console.log(uuidv4());
                this.model.members.push({
                    id: uuidv4(),
                    email: '',
                    fullname: '',
                    kta: '',
                    univ: '',
                    other_institution: '',
                    nik: '',
                    status: '',
                    p2kb_member_id: '',
                    validation: {
                        'nik': ''
                    }
                });
            },
        }
    });
    $(function() {
        $(document).on('change', '.chosen', function() {
            // app.univ = $(this).data('index');
            app.members[$(this).data('index')].univ = $(this).val();
        });

        // NOTE Status change event set null
        $('#status').change(function(e) {
            e.preventDefault();
            app.status_text = $("#status option:selected").text();
            app.eventAdded = [];
        });

    });
</script>
<?php $this->layout->end_script(); ?>