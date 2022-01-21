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
                    <h1 style="color:#F4AD39;">Registration</h1>
                </div>
                <div class="clearfix" style="background-size: cover;"></div>
            </div>
        </div>
    </div>
</section>

<!-- <div class="container">
        <div class="row">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1 class="text-color-dark font-weight-bold">Registrasi Akun</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-dark">
                    <li><a href="?= base_url('site/home'); ?>" class="text-color-dark">Beranda</a></li>
                    <li class="active">Registrasi</li>
                </ul>
            </div>
        </div>
    </div> -->
</section>

<section id="app" class="custom-section-padding">
    <div class="container">
        <div class="row">
            <!-- NOTE Setelah Submmit -->
            <div v-if="page == 'registered'" class="col-lg-8 offset-lg-2">
                <div class="alert alert-success" style="background-color: #F5AC39;">
                    <h4 class="text-dark"><i class="fa fa-info"></i> Akunmu berhasil dibuat</h4>
                    <p>Kami telah mengirim link konfirmasi ke alamat emailmu. Untuk melengkapi proses registrasi, Silahkan klik <i>confirmation link</i>.
                        Jika tidak menerima email konfirmasi, silakan cek folder spam. Kemudian, mohon pastikan anda memasukan alamat email yg valid saat mengisi form pendaftaran. Jika perlu bantuan, silakan kontak kami.</p>
                </div>

                <div class="card mt-2">
                    <div class="card-header text-center">
                        <h4 class="m-0 p-0"><strong class="font-weight-extra-bold ">Halaman untuk mengonfirmasi riwayat penagihan dan invoice display</strong></h4>
                    </div>
                    <div class="card-body">
                        <table class="table text-light">
                            <thead>
                                <th></th>
                                <th>Event Name</th>
                                <th>Pricing</th>
                            </thead>
                            <tbody>
                                <tr v-for="item in transactionsSort">
                                    <td></td>
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
                    <p><strong>Untuk melakukan konfirmasi pembayaran silakan login, kemudian akses menu "Keranjang dan Pembayaran"</strong></p>
                </div>
            </div>


            <!-- NOTE Payment -->
            <div v-if="page == 'payment'" class="col-lg-8 offset-lg-2">

                <div class="card mt-2">
                    <div class="card-header text-center">
                        <h4 class="m-0 p-0"><strong class="font-weight-extra-bold ">Data Akun</strong></h4>
                    </div>
                    <div class="card-body">
                        <table class="table text-light">
                            <tbody>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <th>{{data.email}}</th>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <th>{{data.fullname}}</th>
                                </tr>
                                <tr>
                                    <td>Member ID</td>
                                    <td>:</td>
                                    <th>{{data.id}}</th>
                                </tr>
                                <tr>
                                    <td>Invoice ID</td>
                                    <td>:</td>
                                    <th>{{data.id_invoice}}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-header text-center">
                        <h4 class="m-0 p-0"><strong class="font-weight-extra-bold ">Event</strong></h4>
                    </div>
                    <div class="card-body">
                        <table class="table text-light">
                            <thead>
                                <th></th>
                                <th>Event Name</th>
                                <th>Pricing</th>
                            </thead>
                            <tbody>
                                <tr v-for="item in transactionsSort">
                                    <td></td>
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
                        Submit
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
                                            <h5 style="color:#F4AD39;">Email*</h5>
                                            <input type="text" :class="{'is-invalid': validation_error.email}" class="form-control mb-0" name="email" placeholder="Email" />
                                            <div v-if="validation_error.email" class="invalid-feedback">
                                                {{ validation_error.email }}
                                            </div>

                                            <div class="spacer-20"></div>

                                            <h5 style="color:#F4AD39;">Password*</h5>
                                            <input type="password" :class="{ 'is-invalid':validation_error.password }" class="form-control mb-0" name="password" placeholder="Password" />
                                            <div v-if="validation_error.password" class="invalid-feedback">
                                                {{ validation_error.password }}
                                            </div>

                                            <div class="spacer-20"></div>

                                            <h5 style="color:#F4AD39;">Confirm Password*</h5>
                                            <input type="password" :class="{ 'is-invalid': validation_error.confirm_password }" class="form-control mb-0" name="confirm_password" placeholder="Confirm Password" />
                                            <div v-if="validation_error.confirm_password" class="invalid-feedback">
                                                {{ validation_error.confirm_password }}
                                            </div>

                                            <div class="spacer-20"></div>

                                            <h5 style="color:#F4AD39;">Your Status*</h5>
                                            <?= form_dropdown('status', $participantsCategory, '', [':class' => "{'is-invalid':validation_error.status}", 'id' => 'status', 'v-model' => 'status_selected', 'class' => 'form-control mb-0', 'placeholder' => 'Select your status !', 'style' => 'background-color: #161C31']); ?>
                                            <div v-if="validation_error.status" class="invalid-feedback">
                                                {{ validation_error.status }}
                                            </div>

                                            <div class="spacer-20"></div>

                                            <span v-if="needVerification">
                                                <h5 style="color:#F4AD39;">Mohon unggah bukti identitas anda* <small>(jpg,jpeg,png)</small></h5>
                                                <input type="file" name="proof" accept=".jpg,.png,.jpeg" :class="{'is-invalid':validation_error.proof}" class="form-control-file" />
                                                <div v-if="validation_error.proof" class="invalid-feedback d-block">
                                                    {{ validation_error.proof }}
                                                </div>

                                                <div class="spacer-20"></div>
                                            </span>

                                            <h5 style="color:#F4AD39;"> Full Name*</h5>
                                            <small>*Mohon mengisi nama dengan lengkap dan benar (beserta gelar) untuk sertifikat</small>
                                            <input type="text" :class="{'is-invalid':validation_error.fullname}" class="form-control mb-0" name="fullname" placeholder="Full Name" />
                                            <div v-if="validation_error.fullname" class="invalid-feedback">
                                                {{ validation_error.fullname }}
                                            </div>

                                            <div class="spacer-20"></div>

                                            <h5 style="color:#F4AD39;"> Alamat*</h5>
                                            <textarea :class="{ 'is-invalid':validation_error.address }" class="form-control mb-0" name="address" placeholder="Alamat"></textarea>
                                            <div class="invalid-feedback">
                                                {{ validation_error.address }}
                                            </div>

                                            <div class="spacer-20"></div>

                                            <h5 style="color:#F4AD39;"> City*</h5>
                                            <input type="text" :class="{'is-invalid':validation_error.city}" class="form-control mb-0" name="city" placeholder="City" />
                                            <div v-if="validation_error.city" class="invalid-feedback">
                                                {{ validation_error.city }}
                                            </div>

                                            <div class="spacer-20"></div>
                                            <span class="dark-select">
                                                <h5 style="color:#F4AD39;"> Your Institution*</h5>
                                                <?= form_dropdown('univ', $participantsUniv, '', [':class' => "{'is-invalid':validation_error.univ}", 'v-model' => 'univ_selected', 'class' => 'form-control chosen mb-0', 'placeholder' => 'Select your institution !']); ?>
                                                <div v-if="validation_error.univ" class="invalid-feedback">
                                                    {{ validation_error.univ }}
                                                </div>
                                            </span>

                                            <div class="spacer-20"></div>

                                            <span v-if="univ_selected == <?= Univ_m::UNIV_OTHER; ?>">
                                                <h5 style="color:#F4AD39;"> Other Institution*</h5>
                                                <input type="text" :class="{ 'is-invalid':validation_error.other_institution}" class="form-control mb-0" name="other_institution" placeholder="Other Institution" />
                                                <div v-if="validation_error.other_institution" class="invalid-feedback">
                                                    {{ validation_error.other_institution }}
                                                </div>

                                                <div class="spacer-20"></div>
                                            </span>

                                            <h5 style="color:#F4AD39;">Phone/WA*</h5>
                                            <input type="text" :class="{ 'is-invalid':validation_error.phone}" @keypress="onlyNumber" class="form-control mb-0" name="phone" placeholder="Phone/WA" />
                                            <div v-if="validation_error.phone" class="invalid-feedback">
                                                {{ validation_error.phone }}
                                            </div>

                                            <div class="spacer-20"></div>

                                            <h5 style="color:#F4AD39;">Gender*</h5>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="gender" checked value="M" /> Laki-laki
                                                </label>
                                                <label>
                                                    <input type="radio" name="gender" value="F" /> Wanita
                                                </label>
                                            </div>
                                            <div v-if="validation_error.gender" class="invalid-feedback">
                                                {{ validation_error.gender }}
                                            </div>

                                            <div class="spacer-20"></div>

                                            <h5 style="color:#F4AD39;">Sponsor*</h5>
                                            <input type="text" :class="{'is-invalid':validation_error.sponsor}" class="form-control mb-0" name="sponsor" placeholder="Sponsor" />
                                            <div v-if="validation_error.sponsor" class="invalid-feedback">
                                                {{ validation_error.sponsor }}
                                            </div>

                                            <!-- NOTE Events -->
                                            <div class="col-lg-12" v-if="status_selected">
                                                <hr />
                                                <div class="card">
                                                    <div class="card-header text-center">
                                                        <h2 class="m-0 p-0"><strong class="font-weight-extra-bold ">Acara</strong></h2>
                                                    </div>
                                                    <div class="card-body text-center">
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
                                                                                <th>Kategori</th>
                                                                                <th v-for="pricing in event.pricingName" class="text-center"><span v-html="pricing.title"></span></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr v-for="member in event.memberStatus">
                                                                                <td>{{ member }}</td>
                                                                                <td v-for="pricing in event.pricingName" class="text-center">
                                                                                    <span v-if="pricing.pricing[member]">
                                                                                        {{ formatCurrency(pricing.pricing[member].price) }}
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
                                                        <div class="card card-default mt-2">
                                                            <div class="card-header text-center" style="color:#F5AC39">
                                                                <b>{{ formatCurrency(total) }}</b>
                                                            </div>
                                                        </div>
                                                        <div v-if="validation_error.eventAdded" style="font-size: .875em;color: #dc3545;">
                                                            {{ validation_error.eventAdded }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- NOTE End Events -->
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">&times;</button>
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

<?php if (isset(Settings_m::getEspay()['jsKitUrl'])) : ?>
    <script src="<?= Settings_m::getEspay()['jsKitUrl']; ?>"></script>
<?php endif; ?>
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
            totalPrice() {
                var total = 0;
                for (var i in this.transactions) {
                    total += Number(this.transactions[i].price);
                }
                return total;
            },
            transactionsSort() {
                return this.transactions.sort(function(a, b) {
                    return (a.event_pricing_id > b.event_pricing_id) ? -1 : 1;
                })
            },

            total() {
                var total = 0;
                this.eventAdded.forEach((item, index) => {
                    total += parseFloat(item.price);
                })
                return total;
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
                formData.append('paymentMethod', app.paymentMethod);

                this.saving = true;
                $.ajax({
                    url: '<?= base_url('member/register'); ?>',
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
                        app.page = 'payment';
                        app.data = res.data;
                        app.transactions = res.transactions.cart;
                    }
                }).fail(function(res) {
                    Swal.fire('Fail', 'Server fail to response !', 'error');
                }).always(function(res) {
                    app.saving = false;
                });
            },
            checkout() {
                var formData = new FormData(this.$refs.form);
                // var birthday = moment(formData.get('birthday')).format("Y-MM-DD");
                var birthday = moment().format("Y-MM-DD");
                formData.set("birthday", birthday);

                // NOTE Data Event dan Payment
                formData.append('data', JSON.stringify(app.data));
                formData.append('selectedPaymentMethod', app.selectedPaymentMethod);

                this.saving = true;
                $.ajax({
                    url: '<?= base_url('member/register/checkout'); ?>',
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
            },
            formatCurrency(price) {
                return new Intl.NumberFormat("id-ID", {
                    style: 'currency',
                    currency: "IDR"
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
        }
    });
    $(function() {
        $(".chosen").chosen().change(function() {
            app.univ_selected = $(this).val();
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
                    backUrl: `<?= base_url('member/area'); ?>/redirect_client/billing/${invoiceID}`,
                };
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