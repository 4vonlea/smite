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
                    <h1 style="color:#F4AD39;">Check Invoice</h1>
                </div>
                <div class="clearfix" style="background-size: cover;"></div>
            </div>
        </div>
    </div>
</section>

<section id="app" class="custom-section-padding">
    <div class="container">
        <div class="row">

            <!-- NOTE Sebelum Submit -->
            <div class="col-lg-8 offset-lg-2">
                <form id="form-register" class="form-border" ref="form">
                    <div class="de_tab tab_simple">
                        <div class="de_tab_content">
                            <div class="tab-1">
                                <div class="row wow fadeIn">
                                    <div class="col-lg-12 mb-sm-20">
                                        <div class="field-set" style="color:#F4AD39;">
                                            <h5 style="color:#F4AD39;">ID Invoice*</h5>
                                            <input type="text" :class="{'is-invalid': validation_error.invoice}" class="form-control mb-0" name="invoice" placeholder="ID Invoice" value="<?= $id_invoice ?>" />
                                            <div v-if="validation_error.invoice" class="invalid-feedback" v-html="validation_error.invoice"></div>
                                            <!-- <button type="button" class="btn-main" style="background-color:#F4AD39; color:black;" @click="checkInvoice">Check</button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-4 mt-4">
                    <div class="col-lg-12 text-center">
                        <button type="button" class="btn-main" style="background-color:#F4AD39; color:black;" @click="checkInvoice" :disabled="saving"><i v-if="saving" class="fa fa-spin fa-spinner"></i> Check</button>
                    </div>
                </form>
                <div v-if="model.id">
                    <hr>
                    <div class="alert alert-success" style="background-color: #F5AC39;">
                        <h4 class="text-dark"><i class="fa fa-info"></i> Payment {{model.status_payment}}</h4>
                        <p>{{model.description}}</p>
                    </div>
                    <div v-if="mode == 'upload'">
                        <h5 class="text-light">Untuk menyelesaikan pembayaran silakan upload bukti pembayaran anda pada form berikut:</h5>
                        <form id="form-upload" class="form-border" ref="formUpload">
                            <div class="de_tab tab_simple">
                                <div class="de_tab_content">
                                    <div class="tab-1">
                                        <div class="row wow fadeIn">
                                            <div class="col-lg-12 mb-sm-20">
                                                <div class="field-set" style="color:#F4AD39;">
                                                    <h5 style="color:#F4AD39;">Proof Transfer(png,jpg,jpeg,pdf)</h5>
                                                    <input name="file_proof" type="file" accept=".png,.jpg,.jpeg,.pdf" :class="{'is-invalid':upload_validation.invalid}" class="custom-file-input" />
                                                    <div v-if="upload_validation.invalid" class="invalid-feedback">{{ upload_validation.invalid }}</div>

                                                    <div class="spacer-20"></div>

                                                    <h5 style="color:#F4AD39;">Message</h5>
                                                    <textarea name="message" class="form-control" placeholder="Message"></textarea>

                                                    <button type="button" class="btn-main" style="background-color:#F4AD39; color:black;" @click="uploadProof($event,upload)">Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div v-if="mode == 'upload_success'">
                        <div class="alert alert-success" style="background-color: #F5AC39;">
                            <h4 class="text-dark"><i class="fa fa-info"></i> Upload Bukti Pembayaran berhasil</h4>
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
                // id: '1',
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
        <?php if ($id_invoice != '') { ?>
            app.checkInvoice();
        <?php } ?>
    });
</script>
<?php $this->layout->end_script(); ?>