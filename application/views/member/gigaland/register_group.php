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
<?php $this->layout->end_head(); ?>
<section id="subheader" class="text-light" data-bgimage="url(<?= $theme_path ?>/images/background/subheader.jpg) top">
    <div class="center-y relative text-center" style="background-size: cover;">
        <div class="container" style="background-size: cover;">
            <div class="row" style="background-size: cover;">

                <div class="col-md-12 text-center" style="background-size: cover;">
                    <h1>Registrasi Akun Group</h1>
                </div>
                <div class="clearfix" style="background-size: cover;"></div>
            </div>
        </div>
    </div>

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
            <div v-if="page == 'registered'" class="col-lg-12 col-lg-offset-2">
                <div class="alert alert-success">
                    <h4><i class="fa fa-info"></i> Akunmu berhasil dibuat</h4>
                    <p>Kami telah mengirim link konfirmasi ke alamat emailmu. Untuk melengkapi proses registrasi, Silahkan klik <i>confirmation link</i>.
                        Jika tidak menerima email konfirmasi, silakan cek folder spam. Kemudian, mohon pastikan anda memasukan alamat email yg valid saat mengisi form pendaftaran. Jika perlu bantuan, silakan kontak kami.</p>
                </div>

                <div class="card mt-2">
                    <div class="card-header text-center">
                        <h4 class="m-0 p-0"><strong class="font-weight-extra-bold">Halaman untuk mengonfirmasi riwayat penagihan dan invoice display</strong></h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
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

                    <div class="col-sm-4 mt-2" v-for="account in paymentBank">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">{{ account.bank }}</h3>
                                <p class="card-text">
                                <table>
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

                    <div class="alert alert-success mt-2">
                        <h4><i class="fa fa-info"></i> Konfirmasi Pembayaran</h4>
                        <p><strong>Untuk melakukan konfirmasi pembayaran silakan login, kemudian akses menu "Keranjang dan Pembayaran"</strong></p>
                    </div>
                </div>
            </div>

            <!-- NOTE Payment -->
            <div v-if="page == 'payment'" class="col-lg-12 col-lg-offset-2">

                <div class="card mt-2">
                    <div class="card-header text-center">
                        <h4 class="m-0 p-0"><strong class="font-weight-extra-bold">Data Akun</strong></h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Bill To</td>
                                    <td>:</td>
                                    <th>{{data.bill_to_input}}</th>
                                </tr>
                                <tr>
                                    <td>Invoince ID</td>
                                    <td>:</td>
                                    <th>{{data.id_invoice}}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-header text-center">
                        <h4 class="m-0 p-0"><strong class="font-weight-extra-bold">Event</strong></h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
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
                    <select name="selectedPaymentMethod" id="selectedPaymentMethod" :class="{ 'is-invalid':validation_error.selectedPaymentMethod}" class="form-control selectedPaymentMethod mt-2 text-center" v-model="selectedPaymentMethod">
                        <option v-for="(method,ind) in paymentMethod" :value="method.key">{{method.desc}}</option>
                    </select>
                    <div v-if="validation_error.selectedPaymentMethod" class="invalid-feedback">
                        {{ validation_error.selectedPaymentMethod }}
                    </div>
                </div>
                <hr />
                <div class="form-group row mb-2 mb-5">
                    <div class="col-lg-12 text-center">
                        <button :disabled="saving" type="button" @click="checkout" class="btn btn-primary custom-border-radius font-weight-semibold text-uppercase">
                            <i v-if="saving" class="fa fa-spin fa-spinner"></i>
                            Submit
                        </button>
                    </div>
                </div>

            </div>

            <!-- NOTE Sebelum Submit -->
            <div v-if="page == 'register'" class="col-lg-12 col-lg-offset-2">

                <div class="alert alert-info alert-dismissable alert-hotel mt-5">
                    <i class="fa fa-info"></i>
                    <b>Perhatian</b>
                    Pastikan alamat email yang dimasukkan valid dan dapat anda akses, karena kami akan mengirimkan kode aktivasi melalui email tersebut. Akun anda tidak dapat digunakan sebelum diaktivasi terlebih dahulu.
                </div>
                <form id="form-register" ref="form">
                    <div class="form-group row mb-2">
                        <label class="col-lg-3 control-label control-label-bold">Bill To*</label>
                        <div class="col-lg-9">
                            <input type="text" :class="{'is-invalid': validation_error.bill_to}" class="form-control" name="bill_to" value="bawaihi@ulm.ac.id" />
                            <div v-if="validation_error.bill_to" class="invalid-feedback" v-html="validation_error.bill_to"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-lg-3 control-label">Status*</label>
                        <div class="col-lg-9">
                            <?= form_dropdown('status', $participantsCategory, '', [':class' => "{'is-invalid':validation_error.status}", 'id' => 'status', 'v-model' => 'status_selected', 'class' => 'form-control', 'placeholder' => 'Select your status !']); ?>
                            <div v-if="validation_error.status" class="invalid-feedback" v-html="validation_error.status"></div>
                        </div>
                    </div>

                    <!-- NOTE Events -->
                    <div class="col-lg-12" v-if="status_selected">
                        <hr />
                        <div class="card">
                            <div class="card-header text-center">
                                <h2 class="m-0 p-0"><strong class="font-weight-extra-bold">Acara</strong></h2>
                            </div>
                            <div class="card-body text-center">
                                Silakan pilih acara yang Anda inginkan. *Acara tersedia berdasarkan status dan tanggal Anda
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-9">
                                <div class="overflow-hidden mb-1">
                                    <h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Acara</strong></h2>
                                </div>
                                <div class="overflow-hidden mb-4 pb-3">
                                    <p class="mb-0"></p>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div> -->

                        <div class="row">
                            <div class="accordion accordion-quaternary col-md-12">
                                <div v-for="(event, index) in filteredEvent" class="card card-default mt-2" v-bind:key="index">
                                    <div class="card-header">
                                        <h4 class="card-title m-0">
                                            <a class="accordion-toggle" data-toggle="collapse" :href="'#accordion-'+index" aria-expanded="true">
                                                {{ event.name }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div :id="'accordion-'+index" class="collapse show table-responsive">
                                        <div>
                                            <div v-if="event.participant >= event.kouta" class="alert alert-warning text-center">
                                                <h4>Maaf Kouta untuk acara ini penuh</h4>
                                            </div>
                                            <table class="table">
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
                                <!-- <div class="card card-default mt-2">
                                    <div class="card-header text-center">
                                        <b>{{ formatCurrency(total) }}</b>
                                    </div>
                                </div> -->
                                <div v-if="validation_error.eventAdded" style="font-size: .875em;color: #dc3545;">
                                    {{ validation_error.eventAdded }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- NOTE End Events -->

                    <!-- NOTE Members -->
                    <div class="form-group">
                        <hr />
                        <label>Members
                            <span v-if="validation_error.members">
                                (<span style="color: red;" v-html="validation_error.members"></span>)
                            </span>
                        </label>
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="50%">Data Member</th>
                                    <th width="10%">
                                        <button @click="addMembers" type="button" class="btn btn-primary"><i class="fa fa-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="members.length == 0">
                                    <td class="text-center" colspan="7">No Data</td>
                                </tr>
                                <tr v-for="(member,index) in members">
                                    <td class="text-center">{{(index+1)}}</td>
                                    <td>
                                        <div class="row">
                                            <div class="form-group col-6 p-2">
                                                <label class="control-label">Email</label>
                                                <input type="text" v-model="member.email" placeholder="Email" :class="{'is-invalid': member.validation_error.email}" class="form-control" name="email" />
                                                <div v-if="member.validation_error.email" class="invalid-feedback">
                                                    {{ member.validation_error.email }}
                                                </div>
                                            </div>
                                            <div class="form-group col-6 p-2">
                                                <label class="control-label">Full Name*</small>
                                                </label>
                                                <input type="text" v-model="member.fullname" placeholder="Full Name" :class="{'is-invalid':member.validation_error.fullname}" class="form-control" name="fullname" />
                                                <div v-if="member.validation_error.fullname" class="invalid-feedback">
                                                    {{ member.validation_error.fullname }}
                                                </div>
                                            </div>

                                            <div class="form-group col-6 p-2">
                                                <label class="control-label">Institution</label>
                                                <br>
                                                <?= form_dropdown("univ", $participantsUniv, "", [
                                                    ':name' => '`univ_${index}`',
                                                    ':class' => "{ 'is-invalid':member.validation_error.univ}",
                                                    "class" => 'form-control chosen',
                                                    'placeholder' => 'Select your Institution !',
                                                    ':data-index' => 'index',
                                                    'v-model' => 'member.univ'
                                                ]); ?>
                                                <div v-if="member.validation_error.univ" class="invalid-feedback">
                                                    {{ member.validation_error.univ }}
                                                </div>

                                                <div class="mt-2" v-if="member.univ == <?= Univ_m::UNIV_OTHER; ?>">
                                                    <input type="text" v-model="member.other_institution" :class="{ 'is-invalid':member.validation_error.other_institution} " class="form-control" name="other_institution" />
                                                    <div v-if="member.validation_error.other_institution" class="invalid-feedback">
                                                        {{ member.validation_error.other_institution }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-6 p-2">
                                                <label class="control-label">Sponsor</label>
                                                <input type="text" v-model="member.sponsor" placeholder="Sponsor" :class="{'is-invalid': member.validation_error.sponsor}" class="form-control" name="sponsor" />
                                                <div v-if="member.validation_error.sponsor" class="invalid-feedback">
                                                    {{ member.validation_error.sponsor }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- <td>
											<div class="row" v-for="(event, indexEvent) in model.selected">
												<div class="form-group col-6">
													<label class="control-label">Price</label>
													<input type="text" class="form-control" :value="formatCurrency(event[listStatus[model.status]] ? event[listStatus[model.status]].price : '0')" disabled />
												</div>
											</div>
										</td> -->
                                    <td class="text-center">
                                        <button @click="members.splice(index,1)" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <small class="col-12" for="">*PLEASE FILL YOUR NAME CORRECTLY FOR YOUR CERTIFICATE</small>
                        <div class="card card-default mt-2">
                            <div class="card-header text-center">
                                <b>{{ formatCurrency(total) }}</b>
                            </div>
                        </div>
                    </div>

                </form>
                <hr />
                <div class="form-group row mb-2 mb-5">
                    <div class="col-lg-12 text-center">
                        <button :disabled="saving" type="button" @click="register" class="btn btn-primary custom-border-radius font-weight-semibold text-uppercase">
                            <i v-if="saving" class="fa fa-spin fa-spinner"></i>
                            Submit
                        </button>
                        <button type="button" class="btn btn-danger custom-border-radius font-weight-semibold text-uppercase" id="resetBtn">Cancel
                        </button>
                    </div>
                </div>
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
                total = total * this.members.length;
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
                    if (res.statusData == false && res.validation_error) {
                        app.validation_error = res.validation_error
                    } else if (res.statusData == false && res.message) {
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
                    backUrl: `<?= base_url('member/area'); ?>/redirect_client/billing/${invoiceID}`,
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