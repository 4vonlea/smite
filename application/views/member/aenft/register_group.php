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
                <!-- <p><strong>Sebagai informasi tambahan harap untuk mencatat Invoice ID anda untuk melakukan konfirmasi pembayaran, Untuk melakukan konfirmasi pembayaran bisa dilakukan melalui halaman <a href="<?= base_url('member/register/check_invoice') ?>" style="color:#161D30;text-decoration: underline;" target="_BLANK">Check Invoice</a></strong></p> -->
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
                                <th>{{data.email_group}}</th>
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
                <select name="selectedPaymentMethod" id="selectedPaymentMethod" :class="{ 'is-invalid':validation_error.selectedPaymentMethod}" class="form-control selectedPaymentMethod mt-2 text-center" v-model="selectedPaymentMethod">
                    <option v-for="(method,ind) in paymentMethod" :value="method.key" :selected="method.key == 'manualPayment'">{{method.desc}}</option>
                </select>
                <div v-if="validation_error.selectedPaymentMethod" class="invalid-feedback">
                    {{ validation_error.selectedPaymentMethod }}
                </div>
            </div>
            <hr />
            <div class="col-lg-12 text-center">
                <button :disabled="saving" type="button" @click="checkout" class="btn btn-primary">
                    <i v-if="saving" class="fa fa-spin fa-spinner"></i>
                    Checkout
                </button>
                <button type="button" @click="page = 'register'" class="btn btn-primary">
                    Back
                </button>
            </div>
        </div>

        <!-- NOTE Sebelum Submit -->
        <div v-if="page == 'register'" class="cs-iconbox cs-style1 cs-white_bg">
            <div class="alert alert-primary" role="alert">
                <h4 class="text-black"><i class="icofont icofont-info-circle"></i> <b>Attention</h4>
                <p>Make sure the email address entered is valid and you can access it because we will send an activation code via that email. Your account cannot be used until it is activated first.</p></b>
            </div>
            <form id="form-register" class="form-border" ref="form">
                <div class="de_tab tab_simple">
                    <!-- <p>
                        <i class="fa fa-info"></i> <b>Perhatian</b>
                        Pastikan alamat email yang dimasukkan valid dan dapat anda akses, karena kami akan mengirimkan kode aktivasi melalui email tersebut. Akun anda tidak dapat digunakan sebelum diaktivasi terlebih dahulu.
                    </p> -->
                    <div class="de_tab_content">
                        <div class="tab-1">
                            <div class="row wow fadeIn">
                                <div class="col-lg-12">

                                    <div class="form-group mb-2">
                                        <label>Bill To*</label>
                                        <input type="text" :class="{'is-invalid': validation_error.bill_to}" class="form-control mb-0" name="bill_to" placeholder="Bill To" v-model="data.bill_to" />
                                        <div v-if="validation_error.bill_to" class="invalid-feedback" v-html="validation_error.bill_to"></div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label>Your Email* <small>(Invoice will be sent to this email)</small></label>
                                        <input type="text" :class="{'is-invalid': validation_error.email_group}" class="form-control mb-0" name="email_group" placeholder="Email" v-model="data.email_group" />
                                        <div v-if="validation_error.email_group" class="invalid-feedback" v-html="validation_error.email_group"></div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label>Status*</label>
                                        <?= form_dropdown('status', $participantsCategory, '', [':class' => "{'is-invalid':validation_error.status}", 'id' => 'status', 'v-model' => 'status_selected', 'class' => 'form-control mb-0', 'placeholder' => 'Select your status !']); ?>
                                        <div v-if="validation_error.status" class="invalid-feedback" v-html="validation_error.status"></div>
                                    </div>

                                    <!-- NOTE EVENTS -->
                                    <span v-if="status_selected">
                                        <div class="alert alert-info">
                                            <h4 class="text-black"><i class="icofont icofont-info-circle"></i> <b>Event</b></h4>
                                            <p class="text-center">Please select the event you want. *Events are available based on your status and date</p>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <ul class="nav nav-pills">
                                                    <li v-for="cat in filteredEvent" class="nav-item">
                                                        <span class="nav-link" @click="showCategory = cat.category" :class="{'active':showCategory == cat.category}">{{ cat.category }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="accordion accordion-quaternary col-md-12">
                                                <div v-for="(event, index) in filteredEvent" v-bind:key="index">
                                                    <div class="card card-default mt-2" v-show="showCategory == event.category">
                                                        <div class="card-header card-bg card__shadow ">
                                                            <h4 class="card-title m-0">
                                                                {{ event.name }} <br />
                                                                <span style="font-size: 14px;" v-if="event.event_required">(You must follow event <strong>{{ event.event_required }}</strong> to participate this event)</span>
                                                            </h4>
                                                        </div>
                                                        <div :id="'accordion-'+index" class="collapse show table-responsive">
                                                            <div v-if="event.participant >= event.kouta" class="alert alert-warning text-center">
                                                                Sorry, quota for this event is full
                                                            </div>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="border-end">Category</th>
                                                                        <th v-for="pricing in event.pricingName" class="text-center"><span v-html="pricing.title"></span></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="member in event.memberStatus">
                                                                        <td class="border-end">{{ member }}</td>
                                                                        <td v-for="pricing in event.pricingName" class="text-center">
                                                                            <span v-if="pricing.pricing[member]">
                                                                                <span v-if="pricing.pricing[member].price != 0">{{ formatCurrency(pricing.pricing[member].price) }}</span>
                                                                                <span v-if="pricing.pricing[member].price != 0 && pricing.pricing[member].price_in_usd != 0"> / </span>
                                                                                <span v-if="pricing.pricing[member].price_in_usd != 0">{{formatCurrency(pricing.pricing[member].price_in_usd, 'USD')}}</span>
                                                                                <div v-if="member == status_text" class="form-check form-switch d-flex justify-content-center">
                                                                                    <input type="checkbox" :id="`switch-unlock_${member}_${event.name}`" :value="pricing.pricing[member].added" class="form-check-input" :class="pricing.pricing[member].event_required_id" v-model="pricing.pricing[member].added" @click="addEvent($event,pricing.pricing[member],member,event.name)">
                                                                                    <label :for="`switch-unlock_${member}_${event.name}`"></label>
                                                                                </div>
                                                                                <div v-else>
                                                                                    <button type="button" v-if="member != status_text" style="cursor:not-allowed;color:#fff;" aria-disabled="true" disabled class="btn btn-sm btn-danger">Not Available</button>
                                                                                </div>
                                                                                <!-- <button type="button" @click="addEvent(pricing.pricing[member],member,event.name)" v-if="member == status_text" :disabled="pricing.pricing[member].added" class="btn btn-sm btn-warning">Add Event</button> -->
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if="validation_error.eventAdded" style="font-size: .875em;color: #F2AC38;">
                                                    {{ validation_error.eventAdded }}
                                                </div>
                                                <div v-if="validation_error.requiredEvent" style="font-size: 1em;color: #F2AC38;">
                                                    {{ validation_error.requiredEvent }}
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                    <!-- NOTE GROUP -->
                                    <div class="form-group mt-3">
                                        <hr />
                                        <h5>Members
                                            <span v-if="validation_error.members">
                                                (<span style="color: #F4AD39;" v-html="validation_error.members"></span>)
                                            </span>
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
                                                <tr v-if="members.length == 0">
                                                    <td class="text-center" colspan="7">
                                                        <h5>No Data</h5>
                                                    </td>
                                                </tr>
                                                <tr v-for="(member,index) in members">
                                                    <td class="text-center border-end">
                                                        <h5>{{(index+1)}}</h5>
                                                    </td>
                                                    <td class="border-end">
                                                        <div class="row m-1">
                                                            <div class="form-group col-6 p-2">
                                                                <label class="control-label text-light">NIK KTP (wajib diisi untuk integrasi P2KB)</label>
                                                                <div class="input-group">
                                                                    <input type="text" v-on:keyup.enter="checkMember(member)" v-model="member.nik" placeholder="NIK anda" :class="{'is-invalid':member.validation_error.nik}" class="form-control mb-0" name="nik" />
                                                                    <button :disabled="member.checking" @click="checkMember(member)" class="btn btn-primary" type="button">
                                                                        <i v-if="member.checking" class="fa fa-spin fa-spinner"></i> Cek NIK di Database P2KB
                                                                    </button>
                                                                </div>
                                                                <div v-if="member.validation_error.nik" class="d-block invalid-feedback">
                                                                    {{ member.validation_error.nik }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-6 p-2">
                                                                <label class="control-label text-light">Email</label>
                                                                <input type="text" v-model="member.email" placeholder="Email" :class="{'is-invalid': member.validation_error.email}" class="form-control mb-0" name="email" />
                                                                <div v-if="member.validation_error.email" class="invalid-feedback">
                                                                    {{ member.validation_error.email }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-6 p-2">
                                                                <label class="control-label text-light">Full Name*</label>
                                                                <input type="text" v-model="member.fullname" placeholder="Full Name" :class="{'is-invalid':member.validation_error.fullname}" class="form-control mb-0" name="fullname" />
                                                                <div v-if="member.validation_error.fullname" class="invalid-feedback">
                                                                    {{ member.validation_error.fullname }}
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-6 p-2 dark-select">
                                                                <label class="control-label text-light">Institution</label>
                                                                <br>
                                                                <?= form_dropdown("univ", $participantsUniv, "", [
                                                                    ':name' => '`univ_${index}`',
                                                                    ':class' => "{ 'is-invalid':member.validation_error.univ}",
                                                                    "class" => 'form-control chosen mb-0',
                                                                    'placeholder' => 'Select your Institution !',
                                                                    ':data-index' => 'index',
                                                                    'v-model' => 'member.univ'
                                                                ]); ?>
                                                                <div v-if="member.validation_error.univ" class="invalid-feedback">
                                                                    {{ member.validation_error.univ }}
                                                                </div>

                                                                <!-- <div class="mt-2" v-if="member.univ == <?= Univ_m::UNIV_OTHER; ?>">
                                                                    <input type="text" v-model="member.other_institution" :class="{ 'is-invalid':member.validation_error.other_institution} " class="form-control mb-0" name="other_institution" />
                                                                    <div v-if="member.validation_error.other_institution" class="invalid-feedback">
                                                                        {{ member.validation_error.other_institution }}
                                                                    </div>
                                                                </div> -->
                                                            </div>

                                                            <!-- <div class="form-group col-6 p-2">
                                                                <label class="control-label">Sponsor</label>
                                                                <input type="text" v-model="member.sponsor" placeholder="Sponsor" :class="{'is-invalid': member.validation_error.sponsor}" class="form-control mb-0" name="sponsor" />
                                                                <div v-if="member.validation_error.sponsor" class="invalid-feedback">
                                                                    {{ member.validation_error.sponsor }}
                                                                </div>
                                                            </div> -->

                                                            <div class="form-group col-6 p-2" v-if="member.univ == <?= Univ_m::UNIV_OTHER; ?>">
                                                                <label class="control-label">Other Institution*</label>
                                                                <input type="text" v-model="member.other_institution" :class="{ 'is-invalid':member.validation_error.other_institution} " class="form-control mb-0" name="other_institution" />
                                                                <div v-if="member.validation_error.other_institution" class="invalid-feedback">
                                                                    {{ member.validation_error.other_institution }}
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button @click="members.splice(index,1)" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <small class="col-12" for="">*PLEASE FILL YOUR NAME CORRECTLY FOR YOUR CERTIFICATE</small>
                                        <div class="card card-default mt-2">
                                            <div class="card-header card-bg card__shadow  text-center" style="color:#fff">
                                                <b>{{ formatCurrency(total()) }}</b>
                                                <span v-show="isUsd">
                                                    <br>
                                                    <p>After converting to rupiah</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="col-lg-12 text-center">
                    <button :disabled="saving" type="button" @click="register" style="width: 300px;" class="btn btn-edge btn-primary">
                        <i v-if="saving" class="fa fa-spin fa-spinner"></i>
                        Next
                    </button>
                </div>
            </form>

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
<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/chosen/chosen.jquery.min.js"); ?>"></script>

<?php if (isset(Settings_m::getEspay()['jsKitUrl'])) : ?>
    <script src="<?= Settings_m::getEspay()['jsKitUrl']; ?>"></script>
<?php endif; ?>
<script>
    var modalPayment = new bootstrap.Modal(document.getElementById('modal-select-payment'));
    var app = new Vue({
        'el': "#app",
        components: {
            vuejsDatepicker
        },
        data: {
            statusList: <?= json_encode($statusList); ?>,
            status_selected: "",
            status_text: "",
            univList: <?= json_encode($statusList); ?>,
            univ_selected: "",
            saving: false,
            validation_error: {},
            page: 'register',

            paymentMethod: [],
            selectedPaymentMethod: '',
            events: <?= json_encode($events) ?>,
            eventAdded: [],
            adding: false,
            transactions: null,
            paymentBank: null,
            members: [],
            data: {},
            isUsd: false,
            continueTransaction: <?= isset($continueTransaction) ? json_encode($continueTransaction) : "{}"; ?>,
            showCategory: "",
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
                console.log(sp)
                tempPayment.push({
                    key: sp[0],
                    desc: sp[1]
                });
            })

            this.paymentMethod = tempPayment;
            if (this.continueTransaction.transactions) {
                this.page = 'payment';
                this.data = this.continueTransaction.data; //JSON.parse(JSON.stringify(res.data));
                this.members = this.continueTransaction.members; //JSON.parse(JSON.stringify(res.data.members))
                this.transactions = this.continueTransaction.transactions ? this.continueTransaction.transactions.cart : [];
                Vue.nextTick(() => {
                    app.initEspayFrame();
                });
                console.log(this.data);
            }
            if (this.events.length > 0) {
                this.showCategory = this.events[0].category
            }
        },
        computed: {
            eventCategory() {
                let category = [];
                this.events.forEach(function(val) {
                    if (category.includes(val.category) == false) {
                        category.push(val.category);
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
            totalPrice(idr = true) {
                var total = 0;
                var isUsd = 0;
                for (var i in this.transactions) {
                    if (idr && this.transactions[i].price != 0) {
                        total += parseFloat(this.transactions[i].price);
                    } else {
                        isUsd += 1;
                        kurs_usd = <?= json_encode(json_decode(Settings_m::getSetting('kurs_usd'), true)); ?>;
                        total += (parseFloat(item.price_in_usd) * kurs_usd.value);
                    }
                }
                this.isUsd = isUsd > 0 ? true : false;
                return total;
            },
            total(idr = true) {
                var total = 0;
                var isUsd = 0;
                this.eventAdded.forEach((item, index) => {
                    if (idr && item.price != 0) {
                        total += parseFloat(item.price);
                    } else {
                        isUsd += 1;

                        kurs_usd = <?= json_encode(json_decode(Settings_m::getSetting('kurs_usd'), true)); ?>;
                        total += (parseFloat(item.price_in_usd) * kurs_usd.value);
                    }
                })
                total = total * this.members.length;

                this.isUsd = isUsd > 0 ? true : false;
                return total;
            },
            onlyNumber($event) {
                //console.log($event.keyCode); //keyCodes value
                let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
                    $event.preventDefault();
                }
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
                    if (res.status == false && res.data.validation_error) {
                        app.validation_error = res.data.validation_error;
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
                console.log("Init Espay");
                var invoiceID = app.data.id_invoice;
                var apiKeyEspay = "<?= Settings_m::getEspay()['apiKey']; ?>";
                var data = {
                    key: apiKeyEspay,
                    paymentId: invoiceID,
                    backUrl: `<?= base_url('member/register/check_invoice'); ?>/${invoiceID}`,
                };
                if (typeof SGOSignature !== "undefined") {
                    console.log(SGOSignature);
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
                        if (res.statusData == false && res.validation_error) {
                            app.validation_error = res.validation_error
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

            // NOTE Menambah dan Menghapus Event
            checkRequirement(event_required_id) {
                let isRequired = true;
                if (event_required_id != null && event_required_id != 0) {
                    find = this.eventAdded.find(data => data.id_event == event_required_id);
                    isRequired = find ? true : false;
                }
                return isRequired;
            },
            addEvent(e, event, member, event_name) {
                let isRequired = this.checkRequirement(event.event_required_id);
                if (e.target.checked) {
                    if (isRequired) {
                        event.member_status = member;
                        event.event_name = event_name;
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

                this.members.push({
                    email: '',
                    fullname: '',
                    kta: '',
                    phone: '',
                    univ: '',
                    other_institution: '',
                    sponsor: '',
                    price: '',
                    message_payment: '',
                    nik: '',
                    checking: false,
                    p2kb_member_id: '',
                    validation_error: {}
                });

                Vue.nextTick(() => {
                    $(".chosen").chosen({
                        width: '100%'
                    });
                });
            },
        }
    });
    $(function() {
        $(document).on('change', '.chosen', function() {
            // app.univ = $(this).data('index');
            app.members[$(this).data('index')].univ = $(this).val();
            console.log(app.members[$(this).data('index')].univ)
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