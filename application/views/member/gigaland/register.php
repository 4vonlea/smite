<?php
/**
 * @var array $participantsCategory
 * @var array $statusList;
 * @var array $participantsUniv
 * @var array $univList;
 */
$this->layout->begin_head();
?>
<link href="<?= base_url(); ?>themes/script/chosen/chosen.css" rel="stylesheet">
<?php $this->layout->end_head();?>
<section class="page-header page-header-modern page-header-sm custom-page-header" style="background-color: #d4af37;">
    <div class="container">
        <div class="row">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1 class="text-color-dark font-weight-bold">Registrasi Akun</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-dark">
                    <li><a href="<?= base_url('site/home'); ?>" class="text-color-dark">Beranda</a></li>
                    <li class="active">Registrasi</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="app" class="custom-section-padding" style="background-image: url('<?= base_url('themes/porto'); ?>/img/bgjadwal.jpg'); background-repeat: no-repeat; background-size: cover; height: 100%;">
    <div class="container">
        <div class="row">
            <div v-if="registered" class="col-lg-12 col-lg-offset-2">
                <div class="alert alert-success">
                    <h4><i class="fa fa-info"></i> Akunmu berhasil dibuat</h4>
                    <p>Kami telah mengirim link konfirmasi ke alamat emailmu. Untuk melengkapi proses registrasi, Silahkan klik <i>confirmation link</i>.
                        Jika tidak menerima email konfirmasi, silakan cek folder spam. Kemudian, mohon pastikan ada memasukan alamat email yg valid saat mengisi form pendaftaran. Jika perlu bantuan, silakan kontak kami.</p>
                </div>
            </div>
            <div v-else class="col-lg-12 col-lg-offset-2">

                <div class="alert alert-info alert-dismissable alert-hotel mt-5">
                    <i class="fa fa-info"></i>
                    <b>Perhatian</b>
                    Pastikan alamat email yang dimasukkan valid dan dapat anda akses, karena kami akan mengirimkan kode aktivasi melalui email tersebut. Akun anda tidak dapat digunakan sebelum diaktivasi terlebih dahulu.
                </div>
                <form id="form-register" ref="form">
                    <div class="form-group row">
                        <label class="col-lg-3 control-label control-label-bold">Email*</label>
                        <div class="col-lg-5">
                            <input type="text" :class="{'is-invalid': validation_error.email}" class="form-control" name="email"/>
                            <div v-if="validation_error.email" class="invalid-feedback">
                                {{ validation_error.email }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Password*</label>
                        <div class="col-lg-5">
                            <input type="password" :class="{ 'is-invalid':validation_error.password }" class="form-control" name="password"/>
                            <div v-if="validation_error.password" class="invalid-feedback">
                                {{ validation_error.password }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Confirm Password*</label>
                        <div class="col-lg-5">
                            <input type="password" :class="{ 'is-invalid': validation_error.confirm_password }" class="form-control" name="confirm_password"/>
                            <div v-if="validation_error.confirm_password" class="invalid-feedback">
                                {{ validation_error.confirm_password }}
                            </div>
                        </div>
                    </div>
                    <hr/>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Status*</label>
                        <div class="col-lg-5">
                            <?= form_dropdown('status', $participantsCategory, '', [':class'=>"{'is-invalid':validation_error.status}",'v-model'=>'status_selected', 'class' => 'form-control', 'placeholder' => 'Select your status !']); ?>
                            <div v-if="validation_error.status" class="invalid-feedback" >
                                {{ validation_error.status }}
                            </div>
                        </div>
                    </div>

                    <div v-if="needVerification" class="form-group row">
                        <label class="col-lg-3 control-label">Mohon unggah bukti identitas anda* <small>(jpg,jpeg,png)</small></label>
                        <div class="col-lg-5">
                            <input type="file" name="proof" accept=".jpg,.png,.jpeg" :class="{'is-invalid':validation_error.proof}" class="form-control-file"/>
                            <div v-if="validation_error.proof" class="invalid-feedback d-block">
                                {{ validation_error.proof }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Nama Lengkap*</label>
                        <div class="col-lg-5">
							<small>*Mohon mengisi nama dengan lengkap dan benar (beserta gelar) untuk sertifikat</small>
                            <input type="text" :class="{'is-invalid':validation_error.fullname}" class="form-control" name="fullname"/>
                            <div v-if="validation_error.fullname" class="invalid-feedback">
                                {{ validation_error.fullname }}
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Alamat</label>
                        <div class="col-lg-5">
                            <textarea :class="{ 'is-invalid':validation_error.address }" class="form-control" name="address"></textarea>
                            <div class="invalid-feedback">
                                {{ validation_error.address }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Kota</label>
                        <div class="col-lg-5">
                            <input type="text" :class="{'is-invalid':validation_error.city}" class="form-control" name="city"/>
                            <div v-if="validation_error.city" class="invalid-feedback">
                                {{ validation_error.city }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Institusi*</label>
                        <div class="col-lg-5">
                            <?= form_dropdown('univ', $participantsUniv, '', [':class'=>"{'is-invalid':validation_error.univ}",'v-model'=>'univ_selected', 'class' => 'form-control chosen', 'placeholder' => 'Select your institution !']); ?>
                            <div v-if="validation_error.univ" class="invalid-feedback" >
                                {{ validation_error.univ }}
                            </div>
                        </div>
                    </div>

					<div v-if="univ_selected == <?=Univ_m::UNIV_OTHER;?>" class="form-group row">
						<label class="col-lg-3 control-label">Institusi lain</label>
						<div class="col-lg-5">
							<input type="text" :class="{ 'is-invalid':validation_error.other_institution}"  class="form-control" name="other_institution"/>
							<div v-if="validation_error.phone" class="invalid-feedback">
								{{ validation_error.other_institution }}
							</div>

						</div>
					</div>


                    <div class="form-group row">
                        <label class="col-lg-3 control-label">No HP/WA*</label>
                        <div class="col-lg-5">
                            <input type="text" :class="{ 'is-invalid':validation_error.phone}" @keypress="onlyNumber" class="form-control" name="phone"/>
                            <div v-if="validation_error.phone" class="invalid-feedback">
                                {{ validation_error.phone }}
                            </div>

                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Jenis Kelamin*</label>
                        <div class="col-lg-5">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender" checked value="M"/> Laki-laki
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender" value="F"/> Wanita
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Sponsor</label>
                        <div class="col-lg-5">
                            <input type="text" :class="{'is-invalid':validation_error.sponsor}" class="form-control" name="sponsor"/>
                            <div v-if="validation_error.sponsor" class="invalid-feedback">
                                {{ validation_error.sponsor }}
                            </div>
                        </div>
                    </div>

<!--                    <div class="form-group row">-->
<!--                        <label class="col-lg-3 control-label">Birthday</label>-->
<!--                        <div class="col-lg-5">-->
<!--                            <vuejs-datepicker :input-class="[{'is-invalid':validation_error.birthday},'form-control']"-->
<!--                                              wrapper-class="wrapper-datepicker"-->
<!--                                              name="birthday"></vuejs-datepicker>-->
<!--                            <div v-if="validation_error.birthday" class="invalid-feedback d-block">-->
<!--                                {{ validation_error.birthday }}-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

                </form>
                <hr/>
                <div class="form-group row mb-5">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-5 col-lg-offset-3">
                        <button :disabled="saving" type="button" @click="register"
                                class="btn btn-primary custom-border-radius font-weight-semibold text-uppercase">
                            <i v-if="saving"  class="fa fa-spin fa-spinner"></i>
                            Submit
                        </button>
                        <button type="button"
                                class="btn btn-danger custom-border-radius font-weight-semibold text-uppercase"
                                id="resetBtn">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script src="<?=base_url("themes/script/chosen/chosen.jquery.min.js");?>"></script>

<script>
    var app = new Vue({
        'el': "#app",
        components: {
            vuejsDatepicker
        },
        data: {
            statusList:<?=json_encode($statusList);?>,
            status_selected:"",
            univList:<?=json_encode($statusList);?>,
            univ_selected:"",
            saving:false,
            validation_error:{},
            registered:false,
        },
		computed:{
			needVerification(){
			    var ret = false;
			    var app = this;
			    $.each(this.statusList,function (i,v) {
					if(v.id == app.status_selected){
					    ret = v.need_verify == "1";
					    return false;
					}
                });
				return ret;
			}
		},
        methods: {
            onlyNumber ($event) {
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
                formData.set("birthday",birthday);
                this.saving = true;
                $.ajax({
                    url: '<?=base_url('member/register');?>',
                    type: 'POST',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData
                }).done(function (res) {
                    if(res.status == false && res.validation_error){
                        app.validation_error = res.validation_error
                    }else if(res.status == false && res.message){
                        Swal.fire('Fail',res.message,'error');
                    }else{
                        app.registered = true;
                    }
                }).fail(function (res) {
                    Swal.fire('Fail','Server fail to response !','error');
                }).always(function (res) {
                    app.saving = false;
                });
            }
        }
    });
    $(function(){
		$(".chosen").chosen().change(function(){
        	app.univ_selected = $(this).val();
    	});
	});
</script>
<?php $this->layout->end_script(); ?>

