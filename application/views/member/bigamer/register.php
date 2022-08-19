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
$theme_path = base_url("themes/bigamer") . "/";
?>
<link href="<?= base_url(); ?>themes/script/chosen/chosen.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://unpkg.com/vue2-datepicker@3.11.0/index.css">
<style>
    .achievement-area-copy {
        background-color: #232a5c;
        padding: 30px;
    }

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
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<?php $this->layout->end_head(); ?>
<section class="pageheader-section" style="background-image: url(<?= $theme_path; ?>assets/images/pageheader/bg.jpg);">
    <div class="container">
        <div class="section-wrapper text-center text-uppercase">
            <h2 class="pageheader-title">Halaman Registrasi</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="index.html">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Registrasi</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<section id="app" class="padding-top padding-bottom">
    <div class="container">
        <div class="row g-4">
            <!-- NOTE Setelah Submmit -->
            <div v-if="page == 'registered'" class="col-lg-8 offset-lg-2">
                <div class="alert alert-success">
                    <h4 class="text-dark"><i class="fa fa-info"></i> Akun anda telah dibuat</h4>
                    <p>Kami telah mengirimkan email konfirmasi, mohon cek inbox / spam. Untuk menyelesaikan pendaftaran, klik <i>link konfirmasi</i>yang terdapat didalam email.
                        Silakan hubungi kami bila memerlukan bantuan.</p>
                </div>

                <div class="card card-achievement mt-2">
                    <div class="card-header text-center">
                        <h4 class="m-0 p-0"><strong class="font-weight-extra-bold ">Informasi Tagihan</strong></h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>Nama Kegiatan</th>
                                <th>
                                    <p>Harga
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
                                    <td>{{ formatCurrency(totalPrice()) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-sm-6 mt-2" v-for="account in paymentBank">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title card-title text-dark">{{ account.bank }}</h3>
                            <p class="card-text">
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
                            </p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-success mt-2">
                    <h4 class="text-dark"><i class="fa fa-info"></i> Konfirmasi Pembayaran</h4>
                    <p><strong>Untuk konfirmasi pembayaran, silakan login, kemudian masuk ke menu "keranjang dan pembayaran"</strong></p>
                </div>
            </div>


            <!-- NOTE Payment -->
            <div v-if="page == 'payment'" class="col-lg-8 offset-lg-2">

                <div class="card card-achievement mt-2">
                    <div class="card-header bg-achievement text-center">
                        <h4 class="m-0 p-0"><strong class="font-weight-extra-bold ">Akun</strong></h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Email</td>
                                    <td class="text-center">:</td>
                                    <th>{{data.email}}</th>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td class="text-center">:</td>
                                    <th>{{data.fullname}}</th>
                                </tr>
                                <tr>
                                    <td>Member ID</td>
                                    <td class="text-center">:</td>
                                    <th>{{data.id}}</th>
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

                <div class="card card-achievement mt-2">
                    <div class="card-header bg-achievement text-center">
                        <h4 class="m-0 p-0"><strong class="font-weight-extra-bold ">Kegiatan</strong></h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th></th>
                                <th>Nama Kegiatan</th>
                                <th>
                                    <p>Harga
                                        <span v-show="isUsd">
                                            (<span style="color:#F4AD39">Converted to rupiah</span>)
                                        </span>
                                    </p>
                                </th>
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
                                    <td>{{ formatCurrency((totalPrice())) }}</td>
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
                <div class="col-lg-12 text-center">
                    <button :disabled="saving" type="button" @click="checkout" class="btn btn-edge btn-purple">
                        <i v-if="saving" class="fa fa-spin fa-spinner"></i>
                        Checkout
                    </button>
                    <button type="button" @click="page = 'register'" class="btn btn-edge btn-purple">
                        Back
                    </button>
                </div>
            </div>

            <!-- NOTE Sebelum Submit -->
            <div v-show="page == 'register'">
                <div class="alert btn-purple">
                    <h4 class="text-black"><i class="icofont icofont-info-circle"></i> <b>Perhatian</b></h4>
                    <p><b>Pastikan email yang terdaftar dapat diakses oleh anda. Link konfirmasi akan dikirimkan ke email tersebut. Mohon cek inbox atau spam</b></p>
                </div>
                <div class="achievement-area-copy">
                    <h3 class="title text-center mb-4">Registrasi sekarang</h3>
                    <form id="form-register" style="text-align: left; font-size: 18px; font-weight: 500;" ref="form">
                        <div class="form-group mb-2">
                            <label>NIK (wajib diisi untuk integrasi P2KB)</label>
                            <div class="input-group">
                                <input v-on:keyup.enter="checkMember" type="text" v-model="valueData.nik" :class="{'is-invalid':validation_error.nik}" class="form-control mb-0" name="nik" placeholder="NIK anda" />
                                <button :disabled="checkingMember" @click="checkMember" class="btn btn-primary" type="button">
                                    <i v-if="checkingMember" class="fa fa-spin fa-spinner"></i> Cek KTP di Database P2KB
                                </button>
                            </div>
                            <div v-if="validation_error.nik" class="d-block invalid-feedback">
                                {{ validation_error.nik }}
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label>Email*</label>
                            <input type="text" v-model="valueData.email" :class="{'is-invalid': validation_error.email}" class="form-control mb-0" name="email" placeholder="Email" :disabled="isEmail" />
                            <div v-if="validation_error.email" class="invalid-feedback">
                                {{ validation_error.email }}
                            </div>
                        </div>

                        <div class="form-group mb-2">

                            <label>Password*</label>
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
                            <?= form_dropdown('status', $participantsCategory, '', [':class' => "{'is-invalid':validation_error.status}", 'id' => 'status', 'v-model' => 'status_selected', 'class' => 'form-control mb-0', 'placeholder' => 'Select your status !']); ?>
                            <div v-if="validation_error.status" class="invalid-feedback">
                                {{ validation_error.status }}
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label>KTA Perdossi</label>
                            <input type="text" v-model="valueData.kta" readonly :class="{'is-invalid':validation_error.kta}" class="form-control mb-0" name="kta" placeholder="Full Name" />
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
                            <label> Nama Lengkap*</label>
                            <small>*Mohon isi lengkap dengan gelar untuk sertifikat, perubahan nama setelah registrasi tidak dapat dilakukan</small>
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

                            <label>Nomor Whatsapp (optional)*</label>
                            <input type="text" readonly v-model="valueData.phone" :class="{ 'is-invalid':validation_error.phone}" @keypress="onlyNumber" class="form-control mb-0" name="phone" placeholder="Phone/WA" />
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
                        <div class="col-lg-12" v-if="status_selected">
                            <hr />
                            <div class="alert btn-purple">
                                <h4 class="text-black"><i class="icofont icofont-info-circle"></i> <b>Event</b></h4>
                                <p class="text-center">Pilih kegiatan yang ingin anda ikuti. Untuk kenyamaan anda, Kami harap pembayaran dapat langsung dilakukan tanpa penundaan setelah checkout. *harga yang tertera belum termasuk biaya admin</p>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <ul class="nav nav-pills">
                                        <li v-for="cat in eventCategory" class="nav-item">
                                            <span style="cursor: pointer;" class="nav-link" @click="showCategory = cat" :class="{'active':showCategory == cat}">{{ cat }}</span>
                                        </li>
                                        <li class="nav-item" style="cursor:pointer">
                                            <span class="nav-link" @click="showCategory = 'hotel-booking'" :class="{'active':showCategory == 'hotel-booking'}"> Hotel Booking </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="accordion accordion-quaternary col-md-12">
                                    <div v-for="(event, index) in filteredEvent" v-bind:key="index">
                                        <div class="card card-achievement card-default mt-2" v-show="showCategory == event.category">
                                            <div class="card-header bg-achievement">
                                                <h4 class="card-title m-0">
                                                    {{ event.name }}
                                                    <br /><span style="font-size: 14px;" v-if="event.event_required">(You must follow event "{{ event.event_required }}" to participate this event)</span>
                                                </h4>
                                            </div>
                                            <div :id="'accordion-'+index" class="collapse show table-responsive">
                                                <div>
                                                    <div v-if="event.participant >= event.kouta" class="alert alert-warning text-center">
                                                        <h4>Maaf, kuota telah penuh</h4>
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

                                                                        <span v-show="pricing.pricing[member].price != 0">{{ formatCurrency(pricing.pricing[member].price) }}</span>
                                                                        <span v-show="pricing.pricing[member].price != 0 && pricing.pricing[member].price_in_usd != 0"> / </span>
                                                                        <span v-show="pricing.pricing[member].price_in_usd != 0">{{formatCurrency(pricing.pricing[member].price_in_usd, 'USD')}}</span>

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
                                    </div>
                                    <div class="card card-achievement" v-if="showCategory == 'hotel-booking'">
                                        <div class="card-header">
                                            <h4 class="card-title m-0">
                                                Hotel Booking
                                            </h4>
                                        </div>
                                        <div class="card-body collapse show table-responsive">
                                            <hotel-booking :unique-id="uniqueid" :on-delete="onCancelBooking" :on-book="onBooking" :booking="hotelBooking.booking" book-url="<?= base_url('member/register/add_cart'); ?>" search-url="<?= base_url('api/available_room'); ?>" :min-date="hotelBooking.minBookingDate" :max-date="hotelBooking.maxBookingDate"></hotel-booking>
                                        </div>
                                    </div>
                                    <div class="card card-achievement card-default mt-2">
                                        <div class="card-header bg-achievement text-center" style="color: #fff;">
                                            <b>{{ formatCurrency(total()) }}</b>
                                            <span v-show="isUsd">
                                                <br>
                                                <p style="font-size: 12px;">(Converted to rupiah)</p>
                                            </span>
                                        </div>
                                    </div>
                                    <div v-if="validation_error.eventAdded" style="font-size: 1em;" class="alert alert-danger mt-1 text-center">
                                        {{ validation_error.eventAdded }}
                                    </div>
                                    <div v-if="validation_error.requiredEvent" style="font-size: 1em;" class="alert alert-danger mt-1 text-center">
                                        {{ validation_error.requiredEvent }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- NOTE End Events -->
                </div>

                <hr />
                <div class="col-lg-12 text-center">
                    <button :disabled="saving" type="button" @click="register" style="width:200px;" class="btn btn-edge btn-purple">
                        <i v-if="saving" class="fa fa-spin fa-spinner"></i>
                        Next
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-select-payment">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-purple">
                    <h4 class="modal-title">Pilih metode pembayaran</h4>
                    <button type="button" class="btn btn-default" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <iframe id="sgoplus-iframe" sandbox="allow-same-origin allow-scripts allow-top-navigation allow-forms" style="width:100%"></iframe>
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
<script src="https://unpkg.com/vue2-datepicker@3.11.0" charset="utf-8"></script>
<script src="<?= base_url("themes/script/vue-hotel-booking.js?") ?>"></script>
<script src="<?= base_url("themes/script/vue-espay.js") ?>"></script>
<script src="https://unpkg.com/vue-select@latest"></script>

<?php if (isset(Settings_m::getEspay()['jsKitUrl'])) : ?>
    <script src="<?= Settings_m::getEspay()['jsKitUrl']; ?>"></script>
<?php endif; ?>
<script>
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        'el': "#app",
        components: {
            vuejsDatepicker
        },
        data: {
            valueData: {
                nik: '',
                kta: '',
                fullname: '',
                email: '',
                phone: '',
            },
            uniqueid: "<?= $uniqueId; ?>",
            statusList: <?= json_encode($statusList); ?>,
            status_selected: "",
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
            events: <?= json_encode($events) ?>,
            eventAdded: [],
            adding: false,
            transactions: null,
            paymentBank: null,
            city: "",
            haveSponsor: '0',
            isEmail: false,
            data: {},
            isUsd: false,
            checkingMember: false,
            showCategory: '',
            hotelBooking: {
                booking: [],
                minBookingDate: '<?= $rangeBooking['start']; ?>',
                maxBookingDate: '<?= $rangeBooking['end']; ?>',
            }
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
            if (this.events.length > 0) {
                this.showCategory = this.events[0].category
            }
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
            checkMember() {
                this.checkingMember = true;
                $.get("<?= base_url('member/register/info_member_perdossi'); ?>/" + this.valueData.nik, (res) => {
                    if (res.message == "success") {
                        this.valueData.kta = res.member.perdossi_no;
                        this.valueData.fullname = `${res.member.member_title_front} ${res.member.fullname} ${res.member.member_title_back}`;
                        this.valueData.email = res.member.email;
                        this.valueData.phone = res.member.member_phone;
                    } else {
                        Swal.fire('Info', `NIK.${this.valueData.nik} : ${res.message}`, 'info');
                    }
                }).always(() => {
                    this.checkingMember = false;
                }).fail(() => {
                    Swal.fire('Fail', 'Failed to get member information in perdossi API', 'error')
                })
            },
            totalPrice(idr = true) {
                var total = 0;
                var isUsd = 0;
                for (var i in this.transactions) {
                    if (idr && this.transactions[i].price != 0) {
                        total += parseFloat(this.transactions[i].price);
                    } else {
                        isUsd += 1;
                        kurs_usd = {
                            "using_api": 0,
                            "value": "0"
                        };
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

                        kurs_usd = {
                            "using_api": 0,
                            "value": "0"
                        };
                        total += (parseFloat(item.price_in_usd) * kurs_usd.value);
                    }
                })
                this.hotelBooking.booking.forEach(room => {
                    total += room.price;
                })
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
                if (this.city)
                    formData.append('city', this.city.key);
                formData.append('eventAdded', JSON.stringify(app.eventAdded));
                formData.append('data', JSON.stringify(app.data));
                formData.append('paymentMethod', app.paymentMethod);
                formData.append('uniqueId', this.uniqueid);
                formData.append('booking', JSON.stringify(this.hotelBooking.booking));

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
                        app.data = res.data;
                        app.isEmail = app.data.email != '' ? true : false;
                        app.transactions = res.transactions.cart;
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
                    $("#modal-select-payment").modal("show");
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
                        url: '<?= base_url('member/register/checkout'); ?>',
                        type: 'POST',
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: formData
                    }).done(function(res) {
                        if (res.statusData == false && res.validation_error) {
                            app.validation_error = res.validation_error;
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
                        uniqueId: this.uniqueid
                    });
                    Swal.fire('Berhasil', "Hotel berhasil ditambahkan !", 'success');
                } else {
                    Swal.fire('Fail', "Tanggal Checkout harus lebih dari tanggal checkin", 'warning');
                }
            },
            addEvent(e, event, member, event_name) {
                let isRequired = this.checkRequirement(event.event_required_id);
                if (e.target.checked) {
                    if (isRequired) {
                        // $.post("<?= base_url('member/register/add_cart'); ?>",{
                        //     uniqueId:this.uniqueid,
                        //     id:event.id,
                        //     event_id:event.id_event,
                        //     member_status:member,
                        //     event_name:event_name,
                        // },(res)=>{
                        //     if(res.status){
                        event.member_status = member;
                        event.event_name = event_name;
                        // event.transaction_detail_id = res.transaction_detail_id;
                        // event.transaction_id = res.id;
                        this.eventAdded.push(event);
                        // }
                        // }).always(() => {

                        // }).fail((xhr)=>{
                        //     Swal.fire('Fail', 'Server fail to response !', 'error');
                        // })
                    } else {
                        $(e.target).prop('checked', false);
                        Swal.fire('Info', `You must follow event <b>"${event.event_required}"</b> to participate this event !`, 'info');
                    }
                } else {
                    // $.post("<?= base_url('member/register/delete_item_cart'); ?>",{
                    //     id:event.transaction_detail_id,
                    //     transaction_id:event.transaction_id
                    // },(res)=>{
                    //     if(res.status){
                    let eventId = event.id_event;
                    app.removeEvent(event.id_event);
                    //     }
                    // }).fail((xhr)=>{
                    //     Swal.fire('Fail', 'Server fail to response !', 'error');
                    // }).always((xhr)=>{

                    // })
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

        // $(document).on('change', '.selectedPaymentMethod', function(e) {
        //     e.preventDefault();
        //     let selected = app.paymentMethod.find(data => data.key == app.selectedPaymentMethod);
        //     console.log('mantap ', selected, app.selectedPaymentMethod, $(this).val());
        //     if (selected && selected.key == "espay") {
        //         $("#modal-select-payment").modal("show");

        //         var invoiceID = app.data.id_invoice;
        //         var apiKeyEspay = "?= Settings_m::getEspay()['apiKey']; ?>";
        //         var data = {
        //             key: apiKeyEspay,
        //             paymentId: invoiceID,
        //             backUrl: `?= base_url('member/register/check_invoice'); ?>/${invoiceID}`,
        //         };
        //         if (typeof SGOSignature !== "undefined") {
        //             var sgoPlusIframe = document.getElementById("sgoplus-iframe");
        //             if (sgoPlusIframe !== null)
        //                 sgoPlusIframe.src = SGOSignature.getIframeURL(data);
        //             SGOSignature.receiveForm();
        //         }
        //     }
        // });
    });
</script>
<?php $this->layout->end_script(); ?>