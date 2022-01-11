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
                    <h1>Check Invoice</h1>
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

            <!-- NOTE Sebelum Submit -->
            <div class="col-lg-12 col-lg-offset-2">
                <form id="form-register" ref="form">
                    <div class="form-group row mb-2">
                        <label class="col-lg-3 control-label control-label-bold">Masukkan ID Invoice</label>
                        <div class="col-lg-6">
                            <input type="text" :class="{'is-invalid': validation_error.invoice}" class="form-control" name="invoice" value="INV-20211229-00016" />
                            <div v-if="validation_error.invoice" class="invalid-feedback" v-html="validation_error.invoice"></div>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-primary" @click="checkInvoice">Check</button>
                        </div>
                    </div>
                </form>
                <div v-if="model.id">
                    <hr>
                    <div class="alert" :class="[model.status_payment == 'Pending' ? 'alert-danger' : 'alert-success']">
                        <h4><i class="fa fa-info"></i> Payment {{model.status_payment}}</h4>
                        <p>{{model.description}}</p>
                    </div>
                    <div v-if="mode == 'upload'">
                        <p>Untuk menyelesaikan pembayaran silakan upload bukti pembayaran anda pada form berikut:</p>
                        <form ref="formUpload">
                            <div class="form-group row mb-2">
                                <label class="col-lg-3 control-label control-label-bold">Proof Transfer(png,jpg,jpeg,pdf)</label>
                                <div class="col-lg-6">
                                    <div class="custom-file">
                                        <input name="file_proof" type="file" accept=".png,.jpg,.jpeg,.pdf" :class="{'is-invalid':upload_validation.invalid}" class="custom-file-input" />
                                        <!-- <label ref="labelFile" class="custom-file-label" for="validatedCustomFile">Choose file...</label> -->
                                        <div v-if="upload_validation.invalid" class="invalid-feedback">{{ upload_validation.invalid }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-lg-3 control-label control-label-bold">Message</label>
                                <div class="col-lg-6">
                                    <textarea name="message" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-lg-3 control-label control-label-bold"></label>
                                <div class="col-lg-6">
                                    <button @click="uploadProof($event,upload)" type="button" class="btn btn-primary">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div v-if="mode == 'upload_success'">
                        <div class="alert alert-success">
                            <h4><i class="fa fa-info"></i> Upload Bukti Pembayaran berhasil</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<!-- <script src="?= base_url("themes/script/chosen/chosen.jquery.min.js"); ?>"></script> -->

<script>
    var app = new Vue({
        'el': "#app",
        components: {
            vuejsDatepicker
        },
        data: {
            invoice: "",
            validation_error: {},
            saving: false,
            model: {
                status_payment: 'Pending',
                description: 'Payment Transaction Has Not Been Processed And Is Waiting To Be Completed.',
            },
            upload_validation: {
                invalid: ""
            },
            mode: '',
        },
        mounted: function() {},
        computed: {},
        methods: {

            // fileChange(event) {
            //     this.$refs.labelFile.innerHTML = event.currentTarget.files[0].name;
            // },
            checkInvoice() {
                var formData = new FormData(this.$refs.form);
                app.validation_error = {};
                app.saving = true;
                $.ajax({
                    url: '<?= current_url(); ?>',
                    type: 'POST',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData
                }).done(function(res) {
                    if (res.status) {
                        app.model = res.transaction;
                        if (app.model.status_payment == 'Pending') {
                            app.mode = 'upload';
                        }
                    } else if (res.status == false && res.message) {
                        Swal.fire('Fail', res.message, 'error');
                    } else {
                        app.validation_error = res.validation_error;
                    }
                }).fail(function(res) {
                    Swal.fire('Fail', 'Server fail to response !', 'error');
                }).always(function(res) {
                    app.saving = false;
                });
            },
            uploadProof(evt, upload) {
                var page = this;
                var btn = evt.currentTarget;
                btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";

                var formData = new FormData(page.$refs.formUpload);
                formData.append('transaction', JSON.stringify(app.model));

                $.ajax({
                    url: '<?= base_url('member/register/upload_proof') ?>',
                    cache: false,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    data: formData,
                    success: function(res) {
                        if (res.status) {
                            app.mode = 'upload_success';
                        } else {
                            app.upload_validation.invalid = res.message;

                        }
                    }
                }).fail(function() {

                }).always(function() {
                    btn.innerHTML = "Upload";
                });
            },
        }
    });
    $(function() {

    });
</script>
<?php $this->layout->end_script(); ?>