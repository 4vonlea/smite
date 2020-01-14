<?php
/**
 * @var array $participantsCategory
 * @var array $statusList;
 * @var array $participantsUniv
 * @var array $univList;
 */
?>
<div class="kingster-page-title-wrap  kingster-style-custom kingster-left-align">
	<div class="kingster-header-transparent-substitute"></div>
	<div class="kingster-page-title-overlay"></div>
	<div class="kingster-page-title-bottom-gradient"></div>
	<div class="kingster-page-title-container kingster-container">
		<div class="kingster-page-title-content kingster-item-pdlr" style="padding-bottom: 60px ;">
			<div class="kingster-page-caption" style="font-size: 21px ;font-weight: 400 ;letter-spacing: 0px ;">Join Us as A Member</div>
			<h1 class="kingster-page-title" style="font-size: 48px ;font-weight: 700 ;text-transform: none ;letter-spacing: 0px ;color: #ffffff ;">Registration</h1></div>
	</div>
</div>
<div class="kingster-breadcrumbs">
	<div class="kingster-breadcrumbs-container kingster-container">
		<div class="kingster-breadcrumbs-item kingster-item-pdlr"> <span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Go to Home." href="<?=base_url();?>" class="home"><span property="name">Home</span></a>
                        <meta property="position" content="1">
                        </span>&gt;<span property="itemListElement" typeof="ListItem"><span property="name">Registration</span>
                        <meta property="position" content="2">
                        </span>
		</div>
	</div>
</div>
<div class="kingster-page-wrapper" id="kingster-page-wrapper">
	<div class="gdlr-core-page-builder-body">
		<div class="gdlr-core-pbf-sidebar-wrapper " style="margin: 0px 0px 30px 0px;">
			<div class="gdlr-core-pbf-sidebar-container gdlr-core-line-height-0 clearfix gdlr-core-js gdlr-core-container">
				<div class="gdlr-core-pbf-sidebar-content  gdlr-core-column-60 gdlr-core-pbf-sidebar-padding gdlr-core-line-height gdlr-core-column-extend-left" style="padding: 35px 0px 20px 0px;">
					<div class="gdlr-core-pbf-sidebar-content-inner">
						<div class="gdlr-core-pbf-element">
							<div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-top gdlr-core-item-pdlr">
								<div class="gdlr-core-title-item-title-wrap clearfix">
									<h3 class="gdlr-core-title-item-title gdlr-core-skin-title " style="font-size: 27px ;font-weight: 600 ;letter-spacing: 0px ;text-transform: none ;">Please fill all below form</h3></div>
							</div>
						</div>
						<div class="gdlr-core-pbf-element">
							<div class="gdlr-core-text-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align">
								<section id="app" class="custom-section-padding">
									<div class="container">
										<div class="row">
											<div v-if="registered" class="col-lg-12 col-lg-offset-2">
												<div class="alert alert-success">
													<h4><i class="fa fa-info"></i> Your account has been successfully created</h4>
													<p>We have sent an email with a confirmation link to your email address. In order to complete the sign-up process, please click the confirmation link.
														If you do not receive a confirmation email, please check your spam folder. Also, please verify that you entered a valid email address in our sign-up form.
														If you need assistance, please contact us.</p>
												</div>
											</div>
											<div v-else class="col-lg-12 col-lg-offset-2">

												<div class="alert alert-info alert-dismissable alert-hotel">
													<i class="fa fa-info"></i>
													<b>Attention</b>
													Make sure the e-mail address you entered is exists and that you can open it, because we will send
													the activation code to activate your account to your e-mail.
													Before being activated, your account cannot be used yet, And please provide/upload document proof of
													your status.
												</div>
												<form id="form-register" ref="form">
													<div class="form-group row">
														<label class="col-lg-3 control-label">Email*</label>
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
														<label class="col-lg-3 control-label">Your Status*</label>
														<div class="col-lg-5">
															<?= form_dropdown('status', $participantsCategory, '', [':class'=>"{'is-invalid':validation_error.status}",'v-model'=>'status_selected', 'class' => 'form-control', 'placeholder' => 'Select your status !']); ?>
															<div v-if="validation_error.status" class="invalid-feedback" >
																{{ validation_error.status }}
															</div>
														</div>
													</div>

													<div v-if="needVerification" class="form-group row">
														<label class="col-lg-3 control-label">Upload proof of your status* <small>(jpg,jpeg,png)</small></label>
														<div class="col-lg-5">
															<input type="file" name="proof" accept=".jpg,.png,.jpeg" :class="{'is-invalid':validation_error.proof}" class="form-control-file"/>
															<div v-if="validation_error.proof" class="invalid-feedback d-block">
																{{ validation_error.proof }}
															</div>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-lg-3 control-label">Full Name*</label>
														<div class="col-lg-5">
															<small>*PLEASE FILL YOUR NAME CORRECTLY FOR YOUR CERTIFICATE</small>
															<input type="text" :class="{'is-invalid':validation_error.fullname}" class="form-control" name="fullname"/>
															<div v-if="validation_error.fullname" class="invalid-feedback">
																{{ validation_error.fullname }}
															</div>
														</div>
													</div>


													<div class="form-group row">
														<label class="col-lg-3 control-label">Address</label>
														<div class="col-lg-5">
															<textarea :class="{ 'is-invalid':validation_error.address }" class="form-control" name="address"></textarea>
															<div class="invalid-feedback">
																{{ validation_error.address }}
															</div>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-lg-3 control-label">City</label>
														<div class="col-lg-5">
															<input type="text" :class="{'is-invalid':validation_error.city}" class="form-control" name="city"/>
															<div v-if="validation_error.city" class="invalid-feedback">
																{{ validation_error.city }}
															</div>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-lg-3 control-label">Your Institution*</label>
														<div class="col-lg-5">
															<?= form_dropdown('univ', $participantsUniv, '', [':class'=>"{'is-invalid':validation_error.univ}",'v-model'=>'univ_selected', 'class' => 'form-control', 'placeholder' => 'Select your institution !']); ?>
															<div v-if="validation_error.univ" class="invalid-feedback" >
																{{ validation_error.univ }}
															</div>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-lg-3 control-label">Phone/WA*</label>
														<div class="col-lg-5">
															<input type="text" :class="{ 'is-invalid':validation_error.phone}" @keypress="onlyNumber" class="form-control" name="phone"/>
															<div v-if="validation_error.phone" class="invalid-feedback">
																{{ validation_error.phone }}
															</div>

														</div>
													</div>


													<div class="form-group row">
														<label class="col-lg-3 control-label">Gender*</label>
														<div class="col-lg-5">
															<div class="radio">
																<label>
																	<input type="radio" name="gender" checked value="M"/> Male
																</label>
															</div>
															<div class="radio">
																<label>
																	<input type="radio" name="gender" value="F"/> Female
																</label>
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
												<div class="form-group row">
													<label class="col-lg-3 control-label"></label>
													<div class="col-lg-5 col-lg-offset-3">
														<button :disabled="saving" type="button" @click="register"
																class="btn btn-outline custom-border-width btn-primary custom-border-radius font-weight-semibold text-uppercase">
															<i v-if="saving"  class="fa fa-spin fa-spinner"></i>
															Register
														</button>
														<button type="button"
																class="btn btn-outline custom-border-width btn-default custom-border-radius font-weight-semibold text-uppercase"
																id="resetBtn" style="border-color:red;color:red">Cancel
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</section>
							</div>
						</div>

					</div>
				</div>
				<!-- <div class="gdlr-core-pbf-sidebar-right gdlr-core-column-extend-right  kingster-sidebar-area gdlr-core-column-20 gdlr-core-pbf-sidebar-padding  gdlr-core-line-height" style="padding: 35px 0px 30px 0px;">
					<div class="gdlr-core-sidebar-item gdlr-core-item-pdlr">
						<div id="text-23" class="widget widget_text kingster-widget">
							<div class="textwidget">
								<div class="gdlr-core-widget-box-shortcode " style="color: #ffffff ;padding: 30px 45px;background-color: #192f59 ;">
									<div class="gdlr-core-widget-box-shortcode-content">
										</p>
										<h3 style="font-size: 20px; color: #fff; margin-bottom: 25px;">Department Contact Info</h3>
										<p><span style="color: #3db166; font-size: 16px; font-weight: 600;">Departement of Neurology</span>
											<br /> <span style="font-size: 15px;"><br /> pinperdossi2020banjarmasin@gmail.com<br /> 0821 5490 0203</span></p>
										<p><span style="font-size: 15px;">+1-2345-5432-45<br /> bsba@kuuniver.edu<br /> </span></p>
										<p><span style="font-size: 16px; color: #3db166;">Mon &#8211; Fri 9:00A.M. &#8211; 5:00P.M.</span></p> <span class="gdlr-core-space-shortcode" style="margin-top: 40px ;"></span>
										<h3 style="font-size: 20px; color: #fff; margin-bottom: 15px;">Social Info</h3>
										<div class="gdlr-core-social-network-item gdlr-core-item-pdb  gdlr-core-none-align" style="padding-bottom: 0px ;"><a href="#url" target="_blank" class="gdlr-core-social-network-icon" title="facebook" style="color: #3db166 ;"><i class="fa fa-facebook" ></i></a><a href="#" target="_blank" class="gdlr-core-social-network-icon" title="google-plus" style="color: #3db166 ;"><i class="fa fa-google-plus" ></i></a><a href="#" target="_blank" class="gdlr-core-social-network-icon" title="linkedin" style="color: #3db166 ;"><i class="fa fa-linkedin" ></i></a><a href="#" target="_blank" class="gdlr-core-social-network-icon" title="skype" style="color: #3db166 ;"><i class="fa fa-skype" ></i></a><a href="#url" target="_blank" class="gdlr-core-social-network-icon" title="twitter" style="color: #3db166 ;"><i class="fa fa-twitter" ></i></a><a href="#" target="_blank" class="gdlr-core-social-network-icon" title="instagram" style="color: #3db166 ;"><i class="fa fa-instagram" ></i></a></div> <span class="gdlr-core-space-shortcode" style="margin-top: 40px ;"></span> <a class="gdlr-core-button gdlr-core-button-shortcode  gdlr-core-button-gradient gdlr-core-button-no-border" href="#" style="padding: 16px 27px 18px;margin-right: 20px;border-radius: 2px;-moz-border-radius: 2px;-webkit-border-radius: 2px;"><span class="gdlr-core-content" >Student Resources</span></a>
										<p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->
			</div>
		</div>
	</div>
</div>

<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>

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
</script>
<?php $this->layout->end_script(); ?>

