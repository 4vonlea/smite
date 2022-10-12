<?php

/**
 * @var $participantsCategory
 * @var array $events
 * @var array $univDl
 */
$this->layout->begin_head();
?>
<link href="<?= base_url(); ?>themes/script/chosen/chosen.css" rel="stylesheet">
<?php $this->layout->end_head(); ?>

<div class="header bg-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>
<div class="container-fluid mt--7">
	<div class="row">
		<div class="col-md-12">
			<div class="card bg-secondary shadow border-0">
				<div class="card-header bg-white pb-5">
					<div class="row">
						<div class="col-6">
							<h3>Register Participant</h3>
						</div>
					</div>
				</div>
				<div class="card-body px-lg-5 py-lg-5">
					<form id="form-register" ref="form">
						<div class="form-group row">
                            <label class="col-lg-3 control-label">NIK</label>
							<div class="col-lg-5">
								<div class="input-group">
									<input v-on:keyup.enter="checkMember" type="text" v-model="valueData.nik" :class="{'is-invalid':validation_error.nik}" class="form-control mb-0" name="nik" placeholder="NIK anda" />
									<button :disabled="checkingMember" @click="checkMember" class="btn btn-primary" type="button">
										<i v-if="checkingMember" class="fa fa-spin fa-spinner"></i> Cek
									</button>
								</div>
							</div>
                            <div v-if="validation_error.nik" class="invalid-feedback">
                                {{ validation_error.nik }}
                            </div>
                        </div>
						<div class="form-group row">
							<label class="col-lg-3 control-label">Email</label>
							<div class="col-lg-5">
								<input type="text" v-model="valueData.email" :class="{'is-invalid': validation_error.email}" class="form-control" name="email" />
								<div v-if="validation_error.email" class="invalid-feedback">
									{{ validation_error.email }}
								</div>
							</div>
						</div>

						<!--					<div class="form-group row">-->
						<!--						<label class="col-lg-3 control-label">Password</label>-->
						<!--						<div class="col-lg-5">-->
						<!--							<input type="password" :class="{ 'is-invalid':validation_error.password }" class="form-control" name="password"/>-->
						<!--							<div v-if="validation_error.password" class="invalid-feedback">-->
						<!--								{{ validation_error.password }}-->
						<!--							</div>-->
						<!--						</div>-->
						<!--					</div>-->
						<!---->
						<!--					<div class="form-group row">-->
						<!--						<label class="col-lg-3 control-label">Confirm Password</label>-->
						<!--						<div class="col-lg-5">-->
						<!--							<input type="password" :class="{ 'is-invalid': validation_error.confirm_password }" class="form-control" name="confirm_password"/>-->
						<!--							<div v-if="validation_error.confirm_password" class="invalid-feedback">-->
						<!--								{{ validation_error.confirm_password }}-->
						<!--							</div>-->
						<!--						</div>-->
						<!--					</div>-->
						<hr />

						<div class="form-group row">
							<label class="col-lg-3 control-label">Participant Status</label>
							<div class="col-lg-5">
								<?= form_dropdown('status', $participantsCategory, '', [':class' => "{'is-invalid':validation_error.status}", 'class' => 'form-control', 'placeholder' => 'Select your status !', 'v-model' => 'status_participant']); ?>
								<div v-if="validation_error.status" class="invalid-feedback">
									{{ validation_error.status }}
								</div>
							</div>
						</div>

						<!--						<div class="form-group row">-->
						<!--							<label class="col-lg-3 control-label">Upload proof of participant status</label>-->
						<!--							<div class="col-lg-5">-->
						<!--								<input type="file" name="proof" :class="{'is-invalid':validation_error.proof}"-->
						<!--									   class="form-control-file"/>-->
						<!--								<div v-if="validation_error.proof" class="invalid-feedback d-block">-->
						<!--									{{ validation_error.proof }}-->
						<!--								</div>-->
						<!--							</div>-->
						<!--						</div>-->

						<div class="form-group row">
							<label class="col-lg-3 control-label">Full Name</label>
							<div class="col-lg-5">
								<small>*PLEASE FILL YOUR NAME CORRECTLY FOR YOUR CERTIFICATE</small>
								<input type="text" v-model="valueData.fullname" :class="{'is-invalid':validation_error.fullname}" class="form-control" name="fullname" />
								<div v-if="validation_error.fullname" class="invalid-feedback">
									{{ validation_error.fullname }}
								</div>
							</div>
						</div>

						<div class="form-group row">
                            <label class="col-lg-3 control-label">KTA Perdossi</label>
							<div class="col-lg-5">
								<input type="text" v-model="valueData.kta" readonly :class="{'is-invalid':validation_error.kta}" class="form-control mb-0" name="kta" placeholder="Full Name" />
								<div v-if="validation_error.kta" class="invalid-feedback">
									{{ validation_error.kta }}
								</div>
							</div>
                        </div>


						<!-- <div class="form-group row">
							<label class="col-lg-3 control-label">Address</label>
							<div class="col-lg-5">
								<textarea :class="{ 'is-invalid':validation_error.address }" class="form-control" name="address"></textarea>
								<div class="invalid-feedback">
									{{ validation_error.address }}
								</div>
							</div>
						</div> -->


						<div class="form-group row">
							<label class="col-lg-3 control-label">Country</label>
							<div class="col-lg-5">
								<?= form_dropdown("country", $countryDl, "", [':class' => "{ 'is-invalid':validation_error.country}", "class" => 'form-control selectedCountry chosen', 'v-model' => 'selectedCountry']); ?>
								<div v-if="validation_error.country" class="invalid-feedback">
									{{ validation_error.country }}
								</div>
							</div>
						</div>
						<div v-if="selectedCountry == <?= Country_m::COUNTRY_OTHER; ?>" class="form-group row">
							<label class="col-lg-3 control-label">Other Country</label>
							<div class="col-lg-5">
								<input type="text" :class="{ 'is-invalid':validation_error.other_country} " class="form-control" name="other_country" />
								<div v-if="validation_error.other_country" class="invalid-feedback">
									{{ validation_error.other_country }}
								</div>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label">City</label>
							<div class="col-lg-5">
								<input type="text" :class="{'is-invalid':validation_error.city}" class="form-control" name="city" />
								<div v-if="validation_error.city" class="invalid-feedback">
									{{ validation_error.city }}
								</div>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label">Phone/WA</label>
							<div class="col-lg-5">
								<input type="text" v-model="valueData.phone" :class="{ 'is-invalid':validation_error.phone} " class="form-control" @keypress="onlyNumber" name="phone" />
								<div v-if="validation_error.phone" class="invalid-feedback">
									{{ validation_error.phone }}
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label">Institution</label>
							<div class="col-lg-5">
								<?= form_dropdown("univ", $univDl, "", [':class' => "{ 'is-invalid':validation_error.univ}", "class" => 'form-control selectedInstitution chosen', 'v-model' => 'selectedInstitution']); ?>
								<div v-if="validation_error.univ" class="invalid-feedback">
									{{ validation_error.univ }}
								</div>
							</div>
						</div>
						<div v-if="selectedInstitution == <?= Univ_m::UNIV_OTHER; ?>" class="form-group row">
							<label class="col-lg-3 control-label">Other Institution</label>
							<div class="col-lg-5">
								<input type="text" :class="{ 'is-invalid':validation_error.other_institution} " class="form-control" name="other_institution" />
								<div v-if="validation_error.phone" class="invalid-feedback">
									{{ validation_error.other_institution }}
								</div>
							</div>
						</div>
						<!-- <div class="form-group row">
							<label class="col-lg-3 control-label">Gender</label>
							<div class="col-lg-5">
								<div class="radio form-check-inline">
									<label>
										<input type="radio" name="gender" checked value="M" /> Male
									</label>
								</div>
								<div class="radio form-check-inline">
									<label>
										<input type="radio" name="gender" value="F" /> Female
									</label>
								</div>
							</div>
						</div> -->

						<div class="form-group row">
							<label class="col-lg-3 control-label">Sponsor</label>
							<div class="col-lg-5">
								<input type="text" :class="{'is-invalid':validation_error.sponsor}" class="form-control" name="sponsor" />
								<div v-if="validation_error.sponsor" class="invalid-feedback">
									{{ validation_error.sponsor }}
								</div>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label">Method Payment</label>
							<div class="col-lg-5">
								<?= form_dropdown('channel', ['CASH' => 'CASH', 'EDC' => 'EDC', 'MANUAL TRANSFER' => 'MANUAL TRANSFER', Transaction_m::CHANNEL_GL => Transaction_m::CHANNEL_GL], 'CASH', [':class' => "{'is-invalid':validation_error.status}", 'class' => 'form-control', 'placeholder' => 'Select your status !', 'v-model' => 'channel']); ?>
							</div>
						</div>
						<div v-if="channel =='MANUAL TRANSFER' || channel == '<?=Transaction_m::CHANNEL_GL;?>'" class="form-group row">
							<label class="col-lg-3 control-label">Upload Bukti <small>(jpg,png,jpeg,pdf)</small></label>
							<div class="col-lg-5">
								<input type="file" name="proof" :class="{'is-invalid':validation_error.proof}" class="form-control-file" />
								<div v-if="validation_error.proof" class="invalid-feedback d-block">
									{{ validation_error.proof }}
								</div>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label">Status Payment</label>
							<div class="col-lg-5">
								<?= form_dropdown('status_payment', ['pending'=>'Pending','settlement'=>'settlement'], 'pending', [':class' => "{'is-invalid':validation_error.status}", 'class' => 'form-control', 'placeholder' => 'Select your status !', 'v-model' => 'status_payment']); ?>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label">Code Reference/Message</label>
							<div class="col-lg-5">
								<input type="text" :class="{'is-invalid':validation_error.city}" class="form-control" name="message_payment" />
								<div v-if="validation_error.message_payment" class="invalid-feedback">
									{{ validation_error.message_payment }}
								</div>
							</div>
						</div>
						<hr />
						<div class="form-group">
							<label>Events the Followed</label>
							<table class="table table-bordered">
								<tr>
									<th>Check</th>
									<th>Events Name</th>
									<th>Price</th>
								</tr>
								<tr v-for="(ev,index) in filteredEvents">
									<td>
										<input type="checkbox" v-model="selected" name="transaction[event][]" :value="[ev.id,ev.price,ev.price_in_usd,ev.product_name,ev.status]" />
									</td>
									<td>{{ index }} <span style="font-size: 14px;" v-if="ev.event_required">(You must follow event {{ ev.event_required }} to patcipate this event)</span></td>
									<td>
										<span v-show="ev.price != 0 || ev.price_in_usd == 0">{{ formatCurrency(ev.price) }}</span>
										<span v-show="ev.price != 0 && ev.price_in_usd != 0"> / </span>
										<span v-show="ev.price_in_usd != 0">{{formatCurrency(ev.price_in_usd, 'USD')}}</span>
									</td>
								</tr>
								<tr v-for="(ev,index) in discount">
									<td>
										<input type="checkbox" v-model="selected" name="transaction[event][]" :value="[ev.id,ev.price,ev.price_in_usd,ev.event_name,ev.condition]" />
									</td>
									<td>{{ ev.event_name }}</td>
									<td>
										<span v-show="ev.price != 0 || ev.price_in_usd == 0">{{ formatCurrency(ev.price) }}</span>
										<span v-show="ev.price != 0 && ev.price_in_usd != 0"> / </span>
										<span v-show="ev.price_in_usd != 0">{{formatCurrency(ev.price_in_usd, 'USD')}}</span>
									</td>
								</tr>
								<tfoot>
									<th colspan="2">Total Price</th>
									<th>
										{{ formatCurrency(total()) }}
										<input type="text" hidden name="transaction[total_price]" v-model="total()" />
									</th>
								</tfoot>
							</table>
						</div>
					</form>
					<hr />
					<div class="form-group row">
						<div class="col-lg-12 text-right">
							<button :disabled="saving" type="button" @click="register" class="btn btn-primary text-uppercase">
								<i v-if="saving" class="fa fa-spin fa-spinner"></i>
								Register
							</button>
							<button type="button" class="btn btn-default" onclick="$('#form-register').trigger('reset');">Cancel
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/chosen/chosen.jquery.min.js"); ?>"></script>
<script>
	var app = new Vue({
		el: "#app",
		data: {
			selected: [],
			selectedInstitution: "",
			selectedCountry: "",
			listStatus: <?= json_encode($participantsCategory); ?>,
			status_participant: '',
			channel: 'CASH',
			status_payment:'pending',
			saving: false,
			validation_error: {},
			events: <?= json_encode($events); ?>,
			discount: <?= json_encode($discount); ?>,
			valueData:{
                nik:'',
                kta:'',
                fullname:'',
                email:'',
                phone:'',
            },
            checkingMember:false,

		},
		computed: {
			filteredEvents() {
				var rt = {};
				var status = this.listStatus[this.status_participant];
				if (this.events) {
					this.events.forEach(function(item, index) {
						if (item.pricingName.length > 0) {
							Object.keys(item.pricingName[0].pricing).forEach(function(key) {
								if (key == status) {
									rt[item.name] = item.pricingName[0].pricing[key];
									rt[item.name].product_name = `${item.name} (${status})`;
									rt[item.name].status = `${status}`;
									rt[item.name].event_required = item.event_required;
								}
							})
						}
					});
				}
				return rt;
			}
		},
		methods: {
			checkMember(){
                this.checkingMember = true;
                $.get("<?=base_url('member/register/info_member_perdossi');?>/"+this.valueData.nik,(res)=>{
                    if(res.message == "success"){
                            this.valueData.kta = res.member.perdossi_no;
                            this.valueData.fullname = `${res.member.member_title_front} ${res.member.fullname} ${res.member.member_title_back}`;
                            this.valueData.email = res.member.email;
                            this.valueData.phone = res.member.member_phone;
                    }else{
						Swal.fire('Info', `NIK.${this.valueData.nik} : ${res.message}` , 'info');
					}
                }).always(()=>{
                    this.checkingMember = false;
                }).fail(()=>{
                    Swal.fire('Fail', 'Failed to get member information in perdossi API', 'error')
                })
            },
			total(idr = true) {
				var total = 0;
				var selected = [];
				var kurs_usd = <?= json_encode(json_decode(Settings_m::getSetting('kurs_usd'), true)); ?>;
				this.selected.forEach(function(item) {
					selected.push(item[0]);
					if (idr && item[1]!= 0) {
						total += parseFloat(item[1]);
					} else {
						total += (parseFloat(item[2]) * kurs_usd.value);
					}
				});
				// var events = this.filteredEvents;
				// Object.keys(events).forEach(function(key) {
				// 	if (selected.indexOf(events[key].id) >= 0) {
				// 		if (idr && events[key].price != 0) {
				// 			total += parseFloat(events[key].price);
				// 		} else {
				// 			kurs_usd = <?= json_encode(json_decode(Settings_m::getSetting('kurs_usd'), true)); ?>;
				// 			total += (parseFloat(events[key].price_in_usd) * kurs_usd.value);
				// 		}
				// 	}
				// });
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
				this.saving = true;
				app.postRegister = {
					show: false,
					url: "",
					email: ""
				};
				$.ajax({
					url: '<?= base_url('admin/member/register'); ?>',
					type: 'POST',
					contentType: false,
					cache: false,
					processData: false,
					data: formData
				}).done(function(res) {
					if (res.status == false && res.validation_error) {
						app.validation_error = res.validation_error
					} else if (res.status == false && res.message) {
						Swal.fire('Fail', res.message, 'error');
					} else {
						Swal.fire({
							title: '<strong>Registered Successfully</strong>',
							type: 'success',
							html: `<p>Participant successfully registered, an email has been sent to "${res.email}" contains Invoice and Bukti Registrasi files</p>` +
								`<p>to check member you can  <a href="${res.url}" target="_blank">click here</a></p>`,
							showCloseButton: true,
							showCancelButton: true,
							focusConfirm: false,
						});
						$('#form-register').trigger('reset');
					}
				}).fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function(res) {
					app.saving = false;
				});
			},
			formatCurrency(price, currency = 'IDR') {
				return new Intl.NumberFormat("id-ID", {
					style: 'currency',
					currency: currency
				}).format(price);
			},
		}
	});
	$(function() {
		$(".selectedCountry").chosen().change(function() {
			app.selectedCountry = $(this).val();
		});

		$(".selectedInstitution").chosen().change(function() {
			app.selectedInstitution = $(this).val();
		});
	});
</script>
<?php $this->layout->end_script(); ?>