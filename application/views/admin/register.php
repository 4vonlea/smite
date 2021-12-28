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

<div class="header bg-info pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>
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
							<label class="col-lg-3 control-label">Email</label>
							<div class="col-lg-5">
								<input type="text" :class="{'is-invalid': validation_error.email}" class="form-control" name="email" />
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
								<input type="text" :class="{'is-invalid':validation_error.fullname}" class="form-control" name="fullname" />
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
								<input type="text" :class="{'is-invalid':validation_error.city}" class="form-control" name="city" />
								<div v-if="validation_error.city" class="invalid-feedback">
									{{ validation_error.city }}
								</div>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label">Phone/WA</label>
							<div class="col-lg-5">
								<input type="text" :class="{ 'is-invalid':validation_error.phone} " class="form-control" @keypress="onlyNumber" name="phone" />
								<div v-if="validation_error.phone" class="invalid-feedback">
									{{ validation_error.phone }}
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label">Institution</label>
							<div class="col-lg-5">
								<?= form_dropdown("univ", $univDl, "", [':class' => "{ 'is-invalid':validation_error.univ}", "class" => 'form-control chosen', 'v-model' => 'selectedInstitution']); ?>
								<div v-if="validation_error.phone" class="invalid-feedback">
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
						<div class="form-group row">
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
						</div>

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
								<?= form_dropdown('channel', ['CASH' => 'CASH', 'EDC' => 'EDC', 'MANUAL TRANSFER' => 'MANUAL TRANSFER', 'GUARANTEE LETTER' => 'GUARANTEE LETTER'], 'CASH', [':class' => "{'is-invalid':validation_error.status}", 'class' => 'form-control', 'placeholder' => 'Select your status !', 'v-model' => 'channel']); ?>
							</div>
						</div>
						<div v-if="channel =='MANUAL TRANSFER' || channel == 'GUARANTEE LETTER'" class="form-group row">
							<label class="col-lg-3 control-label">Upload Bukti <small>(jpg,png,jpeg,pdf)</small></label>
							<div class="col-lg-5">
								<input type="file" name="proof" :class="{'is-invalid':validation_error.proof}" class="form-control-file" />
								<div v-if="validation_error.proof" class="invalid-feedback d-block">
									{{ validation_error.proof }}
								</div>
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
									<th>d</th>
									<th>Events Name</th>
									<th>Price</th>
								</tr>
								<tr v-for="(ev,index) in filteredEvents">
									<td>
										<input type="checkbox" v-model="selected" name="transaction[event][]" :value="[ev.id,ev.price,ev.product_name]" />
									</td>
									<td>{{ index }}</td>
									<td>{{ formatCurrency(ev.price) }}</td>
								</tr>
								<tfoot>
									<th colspan="2">Total Price</th>
									<th>
										{{ formatCurrency(total) }}
										<input type="text" hidden name="transaction[total_price]" v-model="total" />
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
			listStatus: <?= json_encode($participantsCategory); ?>,
			status_participant: '',
			channel: 'CASH',
			saving: false,
			validation_error: {},
			events: <?= json_encode($events); ?>
		},
		computed: {
			total() {
				var total = 0;
				var selected = [];
				this.selected.forEach(function(item) {
					selected.push(item[0]);
				});
				var events = this.filteredEvents;
				Object.keys(events).forEach(function(key) {
					if (selected.indexOf(events[key].id) >= 0) {
						total += parseFloat(events[key].price);
					}
				});
				return total;
			},
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
								}
							})
						}
					});
				}
				return rt;
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
			formatCurrency(price) {
				return new Intl.NumberFormat("id-ID", {
					style: 'currency',
					currency: "IDR"
				}).format(price);
			}
		}
	});
	$(function() {
		$(".chosen").chosen().change(function() {
			app.selectedInstitution = $(this).val();
		});
	});
</script>
<?php $this->layout->end_script(); ?>