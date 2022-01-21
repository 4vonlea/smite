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
$theme_path = base_url("themes/gigaland") . "/";
?>
<link href="<?= base_url(); ?>themes/script/chosen/chosen.css" rel="stylesheet">
<link href="<?= $theme_path; ?>css/custom.css" rel="stylesheet">
<?php $this->layout->end_head(); ?>
<section id="subheader" style="background-size: cover;">
    <div class="center-y relative text-center" style="background-size: cover;">
        <div class="container" style="background-size: cover;">
            <div class="row" style="background-size: cover;">

                <div class="col-md-12 text-center" style="background-size: cover;">
                    <h1 style="color:#F4AD39;">Registrasi Akun Group</h1>
                </div>
                <div class="clearfix" style="background-size: cover;"></div>
            </div>
        </div>
    </div>
</section>

<section id="app" class="custom-section-padding">
    <div class="container">
        <div class="row">
            <!-- NOTE Setelah Submmit -->
            <div v-if="page == 'registered'" class="col-lg-12">
                <div class="alert alert-success" style="background-color: #F5AC39;">
                    <h4 class="text-dark"><i class="fa fa-info"></i> Akunmu berhasil dibuat</h4>
                    <p>Kami telah mengirim informasi akun ke alamat email peserta. Untuk melengkapi proses registrasi mohon diinformasik ke peserta, untuk menekan <i>confirmation link</i> yang kami kirim via email.
                        Jika tidak menerima email konfirmasi, silakan cek folder spam. Jika perlu bantuan, silakan kontak kami.<br>
                    </p>
                    <p><strong>Sebagai informasi tambahan harap untuk mencatat Invoice ID anda untuk melakukan konfirmasi pembayaran, Untuk melakukan konfirmasi pembayaran bisa dilakukan melalui halaman <a href="<?= base_url('member/register/check_invoice') ?>" style="color:#161D30;text-decoration: underline;" target="_BLANK">Check Invoice</a></strong></p>

                </div>

                <div class="card mt-2">
                    <div class="card-header text-center">
                        <h5 class="m-0 p-0" style="color:#F4AD39;">Event</h5>
                    </div>
                    <div class="card-body">
                        <table class="table text-light">
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
                    <div class="card-header text-center">
                        <h5 class="m-0 p-0" style="color:#F4AD39;">Informasi Tagihan</h5>
                    </div>
                    <div class="card-body">
                        <table class="table text-light">
                            <thead>
                                <th>Event Name</th>
                                <th>Pricing</th>
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
                        <div class="card-body">
                            <h3 class="card-title ">{{ account.bank }}</h3>
                            <p class="card-text">
                            <table style="color: #F5AC39;">
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

                <div class="alert alert-success mt-2" style="background-color: #F5AC39;">
                    <h4 class="text-dark"><i class="fa fa-info"></i> Konfirmasi Pembayaran</h4>
                    <p><strong>Untuk melakukan konfirmasi pembayaran bisa dilakukan melalui halaman <a href="<?= base_url('member/register/check_invoice') ?>" style="color:#161D30;text-decoration: underline;" target="_BLANK">Check Invoice</a></strong></p>
                </div>
            </div>
        </div>

        <!-- NOTE Payment -->
        <div v-if="page == 'payment'" class="col-lg-8 offset-lg-2">
            <div class="card mt-2">
                <div class="card-header text-center">
                    <h5 class="m-0 p-0" style="color:#F4AD39;">Data Akun</h5>
                </div>
                <div class="card-body">
                    <table class="table text-light">
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
                <div class="card-header text-center">
                    <h5 class="m-0 p-0" style="color:#F4AD39;">Event</h5>
                </div>
                <div class="card-body">
                    <table class="table text-light">
                        <thead>
                            <th>Event Name</th>
                            <th>Pricing</th>
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
                                <td>{{ formatCurrency(totalPrice()) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="form-group mb-2">
                <select name="selectedPaymentMethod" id="selectedPaymentMethod" :class="{ 'is-invalid':validation_error.selectedPaymentMethod}" class="form-control selectedPaymentMethod mt-2 text-center text-light" style="background-color: #161C31" v-model="selectedPaymentMethod">
                    <option v-for="(method,ind) in paymentMethod" :value="method.key" :selected="method.key == 'manualPayment'">{{method.desc}}</option>
                </select>
                <div v-if="validation_error.selectedPaymentMethod" class="invalid-feedback">
                    {{ validation_error.selectedPaymentMethod }}
                </div>
            </div>
            <hr />
            <div class="col-lg-12 text-center">
                <button :disabled="saving" type="button" @click="checkout" class="btn-main" style="background-color:#F4AD39; color:black;">
                    <i v-if="saving" class="fa fa-spin fa-spinner"></i>
                    Checkout
                </button>
            </div>

        </div>

        <!-- NOTE Sebelum Submit -->
        <div v-if="page == 'register'" class="col-lg-8 offset-lg-2">
            <div class="alert alert-success mt-2" style="background-color: #F5AC39;">
                <h4 class="text-dark"><i class="fa fa-info"></i> <b>Perhatian</b></h4>
                <p>Pastikan alamat email yang dimasukkan valid dan dapat anda akses, karena kami akan mengirimkan kode aktivasi melalui email tersebut. Akun anda tidak dapat digunakan sebelum diaktivasi terlebih dahulu.</p>
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
                                <div class="col-lg-12 mb-sm-20">

                                    <div class="field-set" style="color:#F4AD39;">
                                        <h5 style="color:#F4AD39;">Bill To*</h5>
                                        <input type="text" :class="{'is-invalid': validation_error.bill_to}" class="form-control mb-0" name="bill_to" placeholder="Bill To" />
                                        <div v-if="validation_error.bill_to" class="invalid-feedback" v-html="validation_error.bill_to"></div>
                                    </div>

                                    <div class="spacer-20"></div>

                                    <div class="field-set" style="color:#F4AD39;">
                                        <h5 style="color:#F4AD39;">Status*</h5>
                                        <?= form_dropdown('status', $participantsCategory, '', [':class' => "{'is-invalid':validation_error.status}", 'id' => 'status', 'v-model' => 'status_selected', 'class' => 'form-control mb-0', 'placeholder' => 'Select your status !', 'style' => 'background-color: #161C31']); ?>
                                        <div v-if="validation_error.status" class="invalid-feedback" v-html="validation_error.status"></div>
                                    </div>

                                    <!-- NOTE EVENTS -->
                                    <span v-if="status_selected">
                                        <hr />
                                        <div class="card">
                                            <div class="card-header text-center">
                                                <h2 class="m-0 p-0"><strong class="font-weight-extra-bold ">Acara</strong></h2>
                                            </div>
                                            <div class="card-body text-center" style="color:#F4AD39;">
                                                Silakan pilih acara yang Anda inginkan. *Acara tersedia berdasarkan status dan tanggal Anda
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="accordion accordion-quaternary col-md-12">
                                                <div v-for="(event, index) in filteredEvent" class="card card-default mt-2" v-bind:key="index">
                                                    <div class="card-header">
                                                        <h4 class="card-title m-0" style="color:#F5AC39">
                                                            {{ event.name }}
                                                        </h4>
                                                    </div>
                                                    <div :id="'accordion-'+index" class="collapse show table-responsive">
                                                        <div>
                                                            <div v-if="event.participant >= event.kouta" class="alert alert-warning text-center">
                                                                <h4>Maaf Kouta untuk acara ini penuh</h4>
                                                            </div>
                                                            <table class="table text-light">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="border-end">Kategori</th>
                                                                        <th v-for="pricing in event.pricingName" class="text-center"><span v-html="pricing.title"></span></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="member in event.memberStatus">
                                                                        <td class="border-end">{{ member }}</td>
                                                                        <td v-for="pricing in event.pricingName" class="text-center">
                                                                            <span v-if="pricing.pricing[member]">
                                                                                {{ formatCurrency(pricing.pricing[member].price) }} / {{formatCurrency(pricing.pricing[member].price_in_usd, 'USD')}}
                                                                                <div v-if="member == status_text" class="de-switch mt-2" style="background-size: cover;">
                                                                                    <input type="checkbox" :id="`switch-unlock_${member}_${event.name}`" :value="pricing.pricing[member].added" class="checkbox" v-model="pricing.pricing[member].added" @click="addEvent($event,pricing.pricing[member],member,event.name)">
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
                                                <div v-if="validation_error.eventAdded" style="font-size: .875em;color: #dc3545;">
                                                    {{ validation_error.eventAdded }}
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                    <!-- NOTE GROUP -->
                                    <div class="form-group">
                                        <hr />
                                        <h5 style="color:#F4AD39;">Members
                                            <span v-if="validation_error.members">
                                                (<span style="color: #F4AD39;" v-html="validation_error.members"></span>)
                                            </span>
                                        </h5>
                                        <table class="table text-light border">
                                            <thead class="text-center">
                                                <tr>
                                                    <th class="border-end" width="5%">
                                                        <h5>No</h5>
                                                    </th>
                                                    <th class="border-end" width="50%">
                                                        <h5>Data Member</h5>
                                                    </th>
                                                    <th class="border-end" width="10%">
                                                        <button @click="addMembers" type="button" class="btn btn-primary" style="background-color:#F4AD39; color:black;"><i class="fa fa-plus"></i>
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
                                                                <label class="control-label" style="color:#F4AD39;">Email</label>
                                                                <input type="text" v-model="member.email" placeholder="Email" :class="{'is-invalid': member.validation_error.email}" class="form-control mb-0" name="email" />
                                                                <div v-if="member.validation_error.email" class="invalid-feedback">
                                                                    {{ member.validation_error.email }}
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-6 p-2">
                                                                <label class="control-label" style="color:#F4AD39;">Full Name*</label>
                                                                <input type="text" v-model="member.fullname" placeholder="Full Name" :class="{'is-invalid':member.validation_error.fullname}" class="form-control mb-0" name="fullname" />
                                                                <div v-if="member.validation_error.fullname" class="invalid-feedback">
                                                                    {{ member.validation_error.fullname }}
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-6 p-2 dark-select">
                                                                <label class="control-label" style="color:#F4AD39;">Institution</label>
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

                                                                <div class="mt-2" v-if="member.univ == <?= Univ_m::UNIV_OTHER; ?>">
                                                                    <input style="color:#F4AD39;" type="text" v-model="member.other_institution" :class="{ 'is-invalid':member.validation_error.other_institution} " class="form-control mb-0" name="other_institution" />
                                                                    <div v-if="member.validation_error.other_institution" class="invalid-feedback">
                                                                        {{ member.validation_error.other_institution }}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-6 p-2">
                                                                <label class="control-label" style="color:#F4AD39;">Sponsor</label>
                                                                <input type="text" v-model="member.sponsor" placeholder="Sponsor" :class="{'is-invalid': member.validation_error.sponsor}" class="form-control mb-0" name="sponsor" />
                                                                <div v-if="member.validation_error.sponsor" class="invalid-feedback">
                                                                    {{ member.validation_error.sponsor }}
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
                                        <small class="col-12" for="" style="color:#F4AD39;">*PLEASE FILL YOUR NAME CORRECTLY FOR YOUR CERTIFICATE</small>
                                        <div class="card card-default mt-2">
                                            <div class="card-header text-center" style="color:#F5AC39">
                                                <b>{{ formatCurrency(total()) }}</b>
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
                    <button :disabled="saving" type="button" @click="register" class="btn-main" style="background-color:#F4AD39; color:black;">
                        <i v-if="saving" class="fa fa-spin fa-spinner"></i>
                        Next
                    </button>
                </div>
            </form>

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
    </div>
</section>
<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/chosen/chosen.jquery.min.js"); ?>"></script>

<script>
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
        },
        computed: {
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
                for (var i in this.transactions) {
                    if (idr) {
                        total += parseFloat(this.transactions[i].price);
                    } else {
                        total += parseFloat(this.transactions[i].price_in_usd);
                    }
                }
                return total;
            },
            total(idr = true) {
                var total = 0;
                this.eventAdded.forEach((item, index) => {
                    if (idr) {
                        total += parseFloat(item.price);
                    } else {
                        total += parseFloat(item.price_in_usd);
                    }
                })
                total = total * this.members.length;
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
                    } else if (res.status == false && res.message) {
                        Swal.fire('Fail', res.message, 'error');
                    } else {
                        app.page = 'payment';
                        app.data = res.data;
                        app.transactions = res.transactions ? res.transactions.cart : [];
                    }
                }).fail(function(res) {
                    Swal.fire('Fail', 'Server fail to response !', 'error');
                }).always(function(res) {
                    app.saving = false;
                });
            },
            checkout() {
                let selected = app.paymentMethod.find(data => data.key == app.selectedPaymentMethod);
                if (selected && selected.key == "espay") {
                    $("#modal-select-payment").modal("show");

                    var invoiceID = app.data.id_invoice;
                    var apiKeyEspay = "<?= Settings_m::getEspay()['apiKey']; ?>";
                    var data = {
                        key: apiKeyEspay,
                        paymentId: invoiceID,
                        backUrl: `<?= base_url('member/register/check_invoice'); ?>/${invoiceID}`,
                    };
                    console.log(data);
                    if (typeof SGOSignature !== "undefined") {
                        var sgoPlusIframe = document.getElementById("sgoplus-iframe");
                        if (sgoPlusIframe !== null)
                            sgoPlusIframe.src = SGOSignature.getIframeURL(data);
                        SGOSignature.receiveForm();
                    }
                } else {
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
                }
            },
            formatCurrency(price, currency = 'IDR') {
                return new Intl.NumberFormat("id-ID", {
                    style: 'currency',
                    currency: currency
                }).format(price);
            },
            // NOTE Menambah dan Menghapus Event
            addEvent(e, event, member, event_name) {

                if (e.target.checked) {
                    event.member_status = member;
                    event.event_name = event_name;

                    this.eventAdded.push(event);
                } else {
                    this.eventAdded = app.eventAdded.filter(data => data.id != event.id);
                }

            },
            formatDate(date) {
                return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
            },
            addMembers() {

                this.members.push({
                    email: '',
                    fullname: '',
                    univ: '',
                    other_institution: '',
                    sponsor: '',
                    price: '',
                    message_payment: '',
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

        $(document).on('change', '.selectedPaymentMethod', function(e) {
            e.preventDefault();
            let selected = app.paymentMethod.find(data => data.key == app.selectedPaymentMethod);
            console.log('mantap ', selected, app.selectedPaymentMethod, $(this).val());
            if (selected && selected.key == "espay") {
                $("#modal-select-payment").modal("show");

                var invoiceID = app.data.id_invoice;
                var apiKeyEspay = "<?= Settings_m::getEspay()['apiKey']; ?>";
                var data = {
                    key: apiKeyEspay,
                    paymentId: invoiceID,
                    backUrl: `<?= base_url('member/register/check_invoice'); ?>/${invoiceID}`,
                };
                console.log(data);
                if (typeof SGOSignature !== "undefined") {
                    var sgoPlusIframe = document.getElementById("sgoplus-iframe");
                    if (sgoPlusIframe !== null)
                        sgoPlusIframe.src = SGOSignature.getIframeURL(data);
                    SGOSignature.receiveForm();
                }
            }
        });
    });
</script>
<?php $this->layout->end_script(); ?>