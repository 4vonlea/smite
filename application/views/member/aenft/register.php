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
<link rel="stylesheet" type="text/css" href="https://unpkg.com/vue2-datepicker@3.11.0/index.css">
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
       .card .table td, .card .table th{
        color: #000 !important;
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<?php $this->layout->end_head(); ?>
<!-- Start Hero -->
<div id="home" class="cs-hero cs-style1 cs-type2 cs-bg text-center  cs-ripple_version" data-src="<?= $theme_path; ?>assets/img/konas/bg-head.jpg" id="home">
    <div class="cs-dark_overlay"></div>
    <div class="container">
        <div class="cs-hero_img wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
            <img src="<?= $theme_path; ?>assets/img/konas/logo.png" style="width: 100%; max-width: 320px; height: auto;">
        </div>
        <div class="cs-hero_text wow fadeIn" data-wow-duration="1s" data-wow-delay="0.45s" style="margin-top: -50px;">
            <h1 class="cs-hero_title text-uppercase cs-font_60 cs-font_36_sm cs-bold"><?= lang("individual_registration"); ?></h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.html"><?= lang("home"); ?></a></li>
                <li class="breadcrumb-item active text-info" aria-current="page"><i class="fa-solid fa-clipboard-user"></i> <?= lang("registration"); ?></li>
            </ol>
        </div>
    </div>
</div>
<!-- End Hero -->

<section id="app" class="padding-top padding-bottom">
    <div class="cs-height_70 cs-height_lg_40"></div>
    <div class="container wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
        <!-- NOTE Setelah Submmit -->
        <div v-if="page == 'registered'" class="col-lg-12">
            <div class="alert alert-success">
                <h4 class="text-dark"><i class="fa fa-info"></i> <?= lang("account_created"); ?></h4>
                <p><?= lang("account_created_notification"); ?></p>
            </div>

            <div class="card">
                <div class="card-header card-bg card__shadow text-center">
                    <h4 class="m-0 p-0"><strong class="font-weight-extra-bold "><?= lang("billing_information"); ?></strong></h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th><?= lang("event_name"); ?></th>
                            <th>
                                <p><?= lang("price"); ?>
                                    <span v-show="isUsd">
                                        (<span style="color:#F4AD39;font-size:12px">Converted to rupiah</span>)
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
                                <td>{{ formatCurrency(totalPrice) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="col-sm-6 mt-2" v-for="account in paymentBank">
                <div class="card">
                    <div class="card-header card-bg card__shadow ">
                        <h3 class="card-title card-title text-dark">{{ account.bank }}</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
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
                <h4 class="text-dark"><i class="fa fa-info"></i> <?= lang("payment_confirmation"); ?></h4>
                <p><strong><?= lang("payment_confirmation_info"); ?></strong></p>
            </div>
        </div>


        <!-- NOTE Payment -->
        <div v-if="page == 'payment'" class="col-lg-12">

            <div class="card mt-2">
                <div class="card-header card-bg card__shadow text-center">
                    <h4 class="m-0 p-0"><strong class="font-weight-extra-bold ">Akun</strong></h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Email</td>
                                <td class="text-center">:</td>
                                <th>{{valueData.email}}</th>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td class="text-center">:</td>
                                <th>{{valueData.fullname}}</th>
                            </tr>
                            <tr>
                                <td>Member ID</td>
                                <td class="text-center">:</td>
                                <th>{{valueData.id}}</th>
                            </tr>
                            <tr>
                                <td>Invoice ID</td>
                                <td class="text-center">:</td>
                                <th>{{transaction.id}}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card  mt-2">
                <div class="card-header card-bg card__shadow text-center">
                    <h4 class="m-0 p-0"><strong class="font-weight-extra-bold "><?= lang("event"); ?></strong></h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th></th>
                            <th><?= lang("event_name"); ?></th>
                            <th>
                                <p><?= lang("price"); ?>
                                    <span v-show="isUsd">
                                        (<span style="color:#F4AD39">Converted to rupiah</span>)
                                    </span>
                                </p>
                            </th>
                        </thead>
                        <tbody>
                            <tr v-for="item in transaction.details">
                                <td></td>
                                <td>{{ item.product_name}}</td>
                                <td>{{ formatCurrency(item.price) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td class="text-right font-weight-bold">Total :</td>
                                <td>{{ formatCurrency((totalPrice)) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="form-group mb-2 mb-2">
                <select name="selectedPaymentMethod" id="selectedPaymentMethod" :class="{ 'is-invalid':validation_error.selectedPaymentMethod}" class="form-control selectedPaymentMethod mt-2 text-center" v-model="selectedPaymentMethod">
                    <option v-for="(method,ind) in paymentMethod" :value="method.key" :selected="method.key == 'manualPayment'">{{method.desc}}</option>
                </select>
                <div v-if="validation_error.selectedPaymentMethod" class="invalid-feedback">
                    {{ validation_error.selectedPaymentMethod }}
                </div>
            </div>
            <hr />
            <div class="col-lg-12 text-center pt-4">
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
        <div v-show="page == 'register'">
            <div class="alert alert-primary" role="alert">
                <h4 class="text-primary text-uppercase"><i class="fa-solid fa-info-circle"></i> <?= lang('attention'); ?></h4>
                <p class="mt-minus2"><?= lang('email_attention'); ?></p>
            </div>
            <div class="cs-iconbox cs-style1 cs-white_bg">
                <h4 class="text-center text-uppercase">Registrasi Sekarang. <p><span style="color: #ffff00;"><strong>Pembayaran tidak dapat di <em>refund</em></strong></span></p>
                </h4>
                <form id="form-register" style="text-align: left; font-size: 18px; font-weight: 500;" ref="form">
                    <div class="form-group mb-2">
                        <label>NIK KTP*</label>
                        <small>(wajib diisi untuk integrasi P2KB)</small>
                        <div class="input-group">
                            <input v-on:keyup.enter="checkMember" type="text" v-model="valueData.nik" :class="{'is-invalid':validation_error.nik}" class="form-control mb-0" name="nik" placeholder="NIK anda" />
                            <input type="hidden" :value="valueData.p2kb_member_id" name="p2kb_member_id" />
                            <button :disabled="checkingMember" @click="checkMember" class="btn btn-primary" type="button">
                                <i v-if="checkingMember" class="fa fa-spin fa-spinner"></i> Cek NIK di Database P2KB
                            </button>
                        </div>
                        <div v-if="validation_error.nik" class="d-block invalid-feedback">
                            {{ validation_error.nik }}
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label>Email*</label>
                        <small>(wajib sama dengan email p2kb, isian ini akan menjadi username anda)</small>
                        <input type="text" v-model="valueData.email" :class="{'is-invalid': validation_error.email}" class="form-control mb-0" name="email" placeholder="Email" :disabled="isEmail" />
                        <div v-if="validation_error.email" class="invalid-feedback">
                            {{ validation_error.email }}
                        </div>
                    </div>

                    <div class="form-group mb-2">

                        <label>Password*</label>
                        <small>(password untuk akun website ini, BUKAN password email anda)</small>
                        <input type="password" :class="{ 'is-invalid':validation_error.password }" class="form-control mb-0" name="password" placeholder="Password" />
                        <div v-if="validation_error.password" class="invalid-feedback">
                            {{ validation_error.password }}
                        </div>
                    </div>
                    <div class="form-group mb-2">

                        <label>Konfirmasi Password*</label>
                        <input type="password" :class="{ 'is-invalid': validation_error.confirm_password }" class="form-control mb-0" name="confirm_password" placeholder="Confirm Password" />
                        <div v-if="validation_error.confirm_password" class="invalid-feedback">
                            {{ validation_error.confirm_password }}
                        </div>
                    </div>
                    <div class="form-group mb-2">

                        <label>Status*</label>
                        <?= form_dropdown('status', $participantsCategory, '', [':class' => "{'is-invalid':validation_error.status}", '@change' => 'statusChange', 'id' => 'status', 'v-model' => 'status_selected', 'class' => 'form-control mb-0', 'placeholder' => 'Select your status !']); ?>
                        <div v-if="validation_error.status" class="invalid-feedback">
                            {{ validation_error.status }}
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label>KTA Perdossi (read-only)</label>
                        <input type="text" v-model="valueData.kta" readonly :class="{'is-invalid':validation_error.kta}" class="form-control mb-0" name="kta" placeholder="otomatis terisi ketika meng-klik tombol 'Cek NIK di Database P2KB'" />
                        <div v-if="validation_error.kta" class="invalid-feedback">
                            {{ validation_error.kta }}
                        </div>
                    </div>

                    <span v-if="needVerification">
                        <div class="form-group mb-2">

                            <label>Bukti Identitas* <small>(jpg,jpeg,png)</small></label>
                            <input type="file" name="proof" accept=".jpg,.png,.jpeg" :class="{'is-invalid':validation_error.proof}" class="form-control-file" />
                            <div v-if="validation_error.proof" class="invalid-feedback d-block">
                                {{ validation_error.proof }}
                            </div>

                        </div>
                    </span>
                    <div class="form-group mb-2">
                        <label><?= lang("fullname"); ?>*</label>
                        <small><?= lang("fullname_form_information"); ?></small>
                        <input type="text" v-model="valueData.fullname" :class="{'is-invalid':validation_error.fullname}" class="form-control mb-0" name="fullname" placeholder="Full Name" />
                        <div v-if="validation_error.fullname" class="invalid-feedback">
                            {{ validation_error.fullname }}
                        </div>
                    </div>

                    <!-- <label> Alamat*</label>
                                            <textarea :class="{ 'is-invalid':validation_error.address }" class="form-control mb-0" name="address" placeholder="Alamat"></textarea>
                                            <div class="invalid-feedback">
                                                {{ validation_error.address }}
                                            </div>

                                            <div class="spacer-20"></div> -->
                    <div class="form-group mb-2">
                        <input type="hidden" v-model="country_selected" name="country" value="104" />
                        <!-- <label> Negara*</label>
                            <?= form_dropdown('country', $participantsCountry, '', [':class' => "{'is-invalid':validation_error.country}", 'v-model' => 'country_selected', 'class' => 'form-control country_selected chosen mb-0', 'placeholder' => 'Select your institution !']); ?>
                            <div v-if="validation_error.country" class="invalid-feedback">
                                {{ validation_error.country }}
                            </div> -->
                    </div>

                    <span v-if="country_selected == <?= Country_m::COUNTRY_OTHER; ?>">
                        <div class="form-group mb-2">

                            <label> Other Country*</label>
                            <input type="text" :class="{ 'is-invalid':validation_error.other_country}" class="form-control mb-0" name="other_country" placeholder="Other Country" />
                            <div v-if="validation_error.other_country" class="invalid-feedback">
                                {{ validation_error.other_country }}
                            </div>
                        </div>

                    </span>
                    <div class="form-group mb-2">

                        <label> Kota</label>
                        <div class="text-dark" :class="{'is-invalid':validation_error.city}">
                            <v-select placeholder="Pilih Kota" v-model="city" :options="kabupatenList" name="city"></v-select>
                        </div>
                        <div v-if="validation_error.city" class="invalid-feedback d-block">
                            {{ validation_error.city }}
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label> Institusi / Affiliasi*</label>
                        <?= form_dropdown('univ', $participantsUniv, '', [':class' => "{'is-invalid':validation_error.univ}", 'v-model' => 'univ_selected', 'class' => 'form-control univ_selected chosen mb-0', 'placeholder' => 'Select your institution !']); ?>
                        <div v-if="validation_error.univ" class="invalid-feedback">
                            {{ validation_error.univ }}
                        </div>
                    </div>

                    <span v-if="univ_selected == <?= Univ_m::UNIV_OTHER; ?>">
                        <div class="form-group mb-2">

                            <label> Other Institution*</label>
                            <input type="text" :class="{ 'is-invalid':validation_error.other_institution}" class="form-control mb-0" name="other_institution" placeholder="Other Institution" />
                            <div v-if="validation_error.other_institution" class="invalid-feedback">
                                {{ validation_error.other_institution }}
                            </div>
                        </div>
                    </span>
                    <div class="form-group mb-2">

                        <label>Nomor Whatsapp (format: 62xxxxxxxxxx)*</label>
                        <input type="text" v-model="valueData.phone" :class="{ 'is-invalid':validation_error.phone}" @keypress="onlyNumber" class="form-control mb-0" name="phone" placeholder="62xxxxxxxxxx" />
                        <div v-if="validation_error.phone" class="invalid-feedback">
                            {{ validation_error.phone }}
                        </div>
                    </div>
                    <!-- <label>Gender*</label>
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

                                            <div class="spacer-20"></div> -->
                    <div class="form-group mb-2">

                        <label>Apakah anda memiliki sponsor?*</label><br />
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="radioSponsorYes" type="radio" name="haveSponsor" value="1" v-model="haveSponsor" />
                            <label class="form-check-label" for="radioSponsorYes">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="radioSponsorNo" type="radio" name="haveSponsor" value="0" v-model="haveSponsor" />
                            <label class="form-check-label" for="radioSponsorNo">Tidak</label>
                        </div>
                    </div>
                    <!-- <div v-if="validation_error.gender" class="invalid-feedback">
                                                {{ validation_error.gender }}
                                            </div> -->

                    <span v-if="haveSponsor == '1'">
                        <div class="form-group mb-2">
                            <label>Nama Sponsor*</label>
                            <input type="text" :class="{'is-invalid':validation_error.sponsor}" class="form-control mb-0" name="sponsor" placeholder="Sponsor" />
                            <div v-if="validation_error.sponsor" class="invalid-feedback">
                                {{ validation_error.sponsor }}
                            </div>
                        </div>
                    </span>

                    <!-- NOTE Events -->
                    <div class="col-lg-12">
                        <hr />
                        <div class="alert alert-primary">
                            <h4 class="text-black"><i class="icofont icofont-info-circle"></i> <b>Event</b></h4>
                            <p class="text-center">Pilih kegiatan yang ingin anda ikuti. Untuk kenyamaan anda, Kami harap pembayaran dapat langsung dilakukan tanpa penundaan setelah checkout.</p>
                            <p class="text-center">Untuk Hotel, Tidak dapat dipesan menggunakan Guarantee Letter</p>
                        </div>
                        <div v-if="loadingEvent" class="alert alert-info text-center">
                            <i class="fa fa-spin fa-spinner fa-4x"></i>
                            <p>Loading Events Data</p>
                        </div>
                        <select-event  v-if="!loadingEvent" add-cart-url="<?= base_url('member/register/add_cart'); ?>" :events="events" :show-hotel-booking="false">
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
                </form>
                <hr />
                <div class="col-lg-12 text-center pt-4">
                    <button :disabled="saving" type="button" @click="register" style="width:300px;" class="btn btn-primary">
                        <i v-if="saving" class="fa fa-spin fa-spinner"></i>
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal" id="modal-select-payment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">Pilih metode pembayaran. <p><span style="color: #ffff00;"><strong>Perhatian! Pembayaran tidak dapat di <em>refund</em></strong></span></p>
                </h4>
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
<script src="https://unpkg.com/vue2-datepicker@3.11.0" charset="utf-8"></script>
<script src="<?= base_url("themes/script/vue-select-event.js?") ?>"></script>
<script src="<?= base_url("themes/script/vue-hotel-booking.js?") ?>"></script>
<script src="<?= base_url("themes/script/vue-espay.js") ?>"></script>
<script src="https://unpkg.com/vue-select@latest"></script>

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
            valueData: <?= json_encode($defaultMember ? $defaultMember->toArray() : [], JSON_FORCE_OBJECT); ?>,
            loadingEvent: false,
            tempMemberId: "<?= $tempMemberId; ?>",
            statusList: <?= json_encode($statusList); ?>,
            status_selected: "<?= $this->session->userdata('tempStatusId'); ?>",
            status_text: "",
            univList: <?= json_encode($participantsUniv); ?>,
            univ_selected: "",
            kabupatenList: <?= json_encode($kabupatenList); ?>,
            countryList: <?= json_encode($participantsCountry); ?>,
            country_selected: "104",
            saving: false,
            validation_error: {},
            page: 'register',
            paymentMethod: [],
            selectedPaymentMethod: '',
            events: [],
            paymentBank: null,
            city: "",
            haveSponsor: '0',
            isEmail: false,
            isUsd: false,
            checkingMember: false,
            transaction: {},
            hotelBooking: {
                booking: [],
                minBookingDate: '<?= $rangeBooking['start']; ?>',
                maxBookingDate: '<?= $rangeBooking['end']; ?>',
            }
        },
        mounted: function() {
            let paymentData = <?= json_encode($paymentMethod) ?>;
            let tempPayment = [{
                key: "",
                desc: "Select Payment Method"
            }];
            if (this.status_selected != "") {
                this.statusChange();
            }
            $.each(paymentData, function(i, v) {
                let sp = v.split(";");
                tempPayment.push({
                    key: sp[0],
                    desc: sp[1]
                });
            })
            this.paymentMethod = tempPayment;
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
            totalPrice() {
                var total = 0;
                if (this.transaction.details) {
                    this.transaction.details.forEach((item) => {
                        total += Number(item.price);
                        console.log(total,item  );

                    })
                }
                return total;
            },
        },
        methods: {
            statusChange() {
                var status = this.statusList.find(data => data.id == this.status_selected);
                this.loadingEvent = true;
                $.post("<?= base_url('member/register/get_events'); ?>", {
                    status: status.kategory,
                    statusId: this.status_selected
                }, (res) => {
                    this.events = res.events;
                }).fail((err) => {
                    Swal.fire('Fail', 'Failed to get event data', 'error')
                }).always(() => {
                    this.loadingEvent = false;

                })
            },
            checkMember() {
                this.checkingMember = true;
                $.get("<?= base_url('member/register/info_member_perdossi'); ?>/" + this.valueData.nik, (res) => {
                    if (res.message == "success") {
                        this.valueData.kta = res.member.perdossi_no;
                        this.valueData.fullname = `${res.member.member_title_front} ${res.member.fullname} ${res.member.member_title_back}`;
                        this.valueData.email = res.member.email;
                        this.valueData.phone = res.member.member_phone;
                        this.valueData.p2kb_member_id = res.member.member_id;
                    } else {
                        Swal.fire('Info', `NIK.${this.valueData.nik} : Data ${res.message} di Website P2KB PERDOSNI. Jangan khawatir, Anda masih dapat melanjutkan pendaftaran KONAS PERDOSSI Semarang 2023 dengan melanjutkan mengisi kolom yang kosong pada formulir ini.`, 'info');
                    }
                }).always(() => {
                    this.checkingMember = false;
                }).fail(() => {
                    Swal.fire('Fail', 'Failed to get member information in perdossi API', 'error')
                })
            },
            onlyNumber($event) {
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
                if (this.city)
                    formData.append('city', this.city.key);
                formData.append('tempMemberId', this.tempMemberId);
                formData.append('booking', JSON.stringify(this.hotelBooking.booking));

                this.saving = true;
                this.validation_error = {};
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
                        Swal.fire('Fail', 'Some fields are invalid', 'error').then((result) => {
                            if (result) {
                                $("html, body").animate({
                                    scrollTop: 0
                                }, 1);
                            }
                        });
                    } else if (res.statusData == false && res.message) {
                        Swal.fire('Fail', res.message, 'error');
                    } else {
                        app.page = 'payment';
                        app.valueData = res.data;
                        app.transaction = res.transaction
                        app.initEspayFrame(res.transaction.id);
                    }
                }).fail(function(res) {
                    Swal.fire('Fail', 'Server fail to response !', 'error');
                }).always(function(res) {
                    app.saving = false;
                });
            },
            initEspayFrame(invoiceID) {
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
                if (selected && selected.key) {
                    var formData = new FormData(this.$refs.form);
                    var birthday = moment().format("Y-MM-DD");
                    formData.set("birthday", birthday);
                    formData.append('paymentMethod', selected.key);
                    this.saving = true;
                    $.ajax({
                        url: '<?= base_url('member/register/checkout'); ?>',
                        type: 'POST',
                        data: {
                            paymentMethod:selected.key,
                            id_invoice:this.transaction.id, 
                        }
                    }).done(function(res) {
                        if (res.statusData == false && res.validation_error) {
                            app.validation_error = res.validation_error;
                        } else if (res.statusData == false && res.message) {
                            Swal.fire('Fail', res.message, 'error');
                        } else if (selected.key == "espay") {
                            modalPayment.show();
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
            onCancelBooking(ind, room) {
                this.hotelBooking.booking.splice(ind, 1);
            },
            onBooking(room, dateCheck) {
                let night = moment(dateCheck.checkout).diff(dateCheck.checkin, 'days');
                let momentCheckin = moment(dateCheck.checkin);
                let momentCheckout = moment(dateCheck.checkout);
                if (momentCheckin.format("YYYY-MM-DD") == "2022-11-19") {
                    Swal.fire('Fail', "Tidak bisa melakukan booking ditanggal 19 November, Mohon melakukan check-in sejak tanggal 18 November dengan durasi menginap minimal 2 malam.", 'warning');
                    return false;
                }
                if (momentCheckout.format("YYYY-MM-DD") == "2022-11-19") {
                    Swal.fire('Fail', "Tidak diperkenankan checkout ditanggal 19 November", 'warning');
                    return false;
                }
                if (momentCheckin.format("YYYY-MM-DD") == "<?= Transaction_detail_m::DATE_KHUSUS; ?>" && night < 2) {
                    Swal.fire('Fail', "Untuk Tanggal 18 November pemesanan minimal 2 malam", 'warning');
                    return false;
                }
                if (momentCheckout.isAfter(momentCheckin)) {
                    this.hotelBooking.booking.push({
                        id: room.id,
                        name: room.name,
                        hotel_name: room.hotel_name,
                        checkin: momentCheckin.format("YYYY-MM-DD"),
                        checkout: momentCheckout.format("YYYY-MM-DD"),
                        price: night * parseFloat(room.price),
                        tempMemberId: this.tempMemberId
                    });
                    Swal.fire('Berhasil', "Hotel berhasil ditambahkan !", 'success');
                } else {
                    Swal.fire('Fail', "Tanggal Checkout harus lebih dari tanggal checkin", 'warning');
                }
            },
            formatDate(date) {
                return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
            },
        }
    });
    $(function() {

        $(".univ_selected").chosen().change(function() {
            app.univ_selected = $(this).val();
        });

        $(".country_selected").chosen().change(function() {
            app.country_selected = $(this).val();
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