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
							<h3>Register Group</h3>
						</div>
					</div>
				</div>
				<div class="card-body px-lg-5 py-lg-5">
					<form id="form-register" ref="form">

						<!-- NOTE Bill To -->
						<div class="form-group row">
							<label class="col-lg-3 control-label">Bill To</label>
							<div class="col-lg-5">
								<input type="text" :class="{'is-invalid': validation_error.bill_to}" class="form-control" placeholder="Bill To" v.model="model.bill_to" name="bill_to" />
								<div v-if="validation_error.bill_to" class="invalid-feedback" v-html="validation_error.bill_to"></div>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label">Your Email* <small>(Invoice will be sent  to this email)</small></label>
							<div class="col-lg-5">
								<input type="text" :class="{'is-invalid': validation_error.email_group}" class="form-control" placeholder="Email" v.model="model.email_group" name="email_group" />
								<div v-if="validation_error.email_group" class="invalid-feedback" v-html="validation_error.email_group"></div>
							</div>
						</div>
						<!-- NOTE Status Participant -->
						<div class="form-group row">
							<label class="col-lg-3 control-label">Status Participant</label>
							<div class="col-lg-5">
								<?= form_dropdown('status', $participantsCategory, '', [
									'v-model' => 'model.status',
									':class' => "{'is-invalid':validation_error.status}",
									'class' => 'form-control status-participant select-input',
								]); ?>
								<div v-if="validation_error.status" class="invalid-feedback" v-html="validation_error.status"></div>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label">Method Payment</label>
							<div class="col-lg-5">
								<?= form_dropdown('channel', ['CASH' => 'CASH', 'EDC' => 'EDC', 'MANUAL TRANSFER' => 'MANUAL TRANSFER', Transaction_m::CHANNEL_GL => Transaction_m::CHANNEL_GL], 'CASH', [':class' => "{'is-invalid':validation_error.status}", 'class' => 'form-control', 'placeholder' => 'Select your status !', 'v-model' => 'channel']); ?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label">Status Payment</label>
							<div class="col-lg-5">
								<?= form_dropdown('status_payment', ['pending'=>'Pending','settlement'=>'settlement'], 'pending', [':class' => "{'is-invalid':validation_error.status}", 'class' => 'form-control', 'placeholder' => 'Select your status !', 'v-model' => 'status_payment']); ?>
							</div>
						</div>

						<!-- NOTE Events -->
						<!-- <div class="form-group row">
							<label class="col-lg-3 control-label">Pilih Events</label>
							<div class="col-lg-5">
								<div class="radio form-check-inline" v-for="(ev,index) in filteredEvents">
									<label>
										<input type="checkbox" name="transaction[event][]" v-model="model.selected" :value="ev" /> {{index}}
									</label>
								</div>
							</div>
						</div> -->

						<div v-if="model.status" class="form-group">
							<label>Events the Followed</label>
							<table class="table table-bordered">
								<tr>
									<th>d</th>
									<th>Events Name</th>
									<th>Price</th>
								</tr>
								<tr v-for="(ev,index) in filteredEvents">
									<td>
										<input type="checkbox" v-model="model.selected" name="transaction[event][]" :value="[ev.id,ev.price,ev.price_in_usd,ev.product_name,ev.status]" />
									</td>
									<td>{{ index }} <span style="font-size: 14px;" v-if="ev.event_required">(You must follow event {{ ev.event_required }} to patcipate this event)</span>
									<td>
										<span v-show="ev.price != 0 || ev.price_in_usd == 0">{{ formatCurrency(ev.price) }}</span>
										<span v-show="ev.price != 0 && ev.price_in_usd != 0"> / </span>
										<span v-show="ev.price_in_usd != 0">{{formatCurrency(ev.price_in_usd, 'USD')}}</span>
									</td>
								</tr>
								<!-- <tfoot>
									<th colspan="2">Total Price</th>
									<th>
										{{ formatCurrency(total) }}
										<input type="text" hidden name="transaction[total_price]" v-model="total" />
									</th>
								</tfoot> -->
							</table>
						</div>

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
									<tr v-if="model.members.length == 0">
										<td class="text-center" colspan="7">No Data</td>
									</tr>
									<tr v-for="(member,index) in model.members">
										<td class="text-center">{{(index+1)}}</td>
										<td>
											<div class="row">
												<div class="form-group col-6">
													<label class="control-label">NIK</label>
													<div class="input-group">
														<input type="text" v-on:keyup.enter="checkMember(member)" v-model="member.nik" placeholder="NIK" :class="{'is-invalid':member.validation_error.nik}" class="form-control mb-0" name="nik" />
														<button :disabled="member.checking" @click="checkMember(member)" class="btn btn-primary" type="button">
															<i v-if="member.checking" class="fa fa-spin fa-spinner"></i> Cek
														</button>
													</div>
													<div v-if="member.validation_error.nik" class="invalid-feedback">
														{{ member.validation_error.nik }}
													</div>
												</div>

												<div class="form-group col-6">
													<label class="control-label">Email</label>
													<input type="text" v-model="member.email" placeholder="Email" :class="{'is-invalid': member.validation_error.email}" class="form-control" name="email" />
													<div v-if="member.validation_error.email" class="invalid-feedback">
														{{ member.validation_error.email }}
													</div>
												</div>
												<div class="form-group col-6">
													<label class="control-label">Full Name*</small>
													</label>
													<input type="text" v-model="member.fullname" placeholder="Full Name" :class="{'is-invalid':member.validation_error.fullname}" class="form-control" name="fullname" />
													<div v-if="member.validation_error.fullname" class="invalid-feedback">
														{{ member.validation_error.fullname }}
													</div>
												</div>

												<div class="form-group col-6">
													<label class="control-label">Institution</label>
													<br>
													<?= form_dropdown("univ", $univDl, "", [
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
							<small class="row col-12" for="">*PLEASE FILL YOUR NAME CORRECTLY FOR YOUR CERTIFICATE</small>
							<table class="table table-bordered">
								<tr>
									<td class="text-center">{{ formatCurrency(total()) }}</td>
								</tr>
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
			model: {
				selected: [],
				bill_to: '',
				members: [],
				status: '',
				channel: 'CASH',
			},

			univ: "",
			listStatus: <?= json_encode($participantsCategory); ?>,
			channel: 'CASH',
			status_payment:'pending',
			saving: false,
			validation_error: {},
			events: <?= json_encode($events); ?>
		},
		computed: {
			filteredEvents() {
				var rt = {};
				var status = this.listStatus[this.model.status];
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
			total(idr = true) {
				var total = 0;
				var selected = [];
				this.model.selected.forEach(function(item) {
					selected.push(item[0]);
				});
				var events = this.filteredEvents;
				Object.keys(events).forEach(function(key) {
					if (selected.indexOf(events[key].id) >= 0) {
						if (idr && events[key].price != 0) {
							total += parseFloat(events[key].price);
						} else {
							kurs_usd = <?= json_encode(json_decode(Settings_m::getSetting('kurs_usd'), true)); ?>;
							total += (parseFloat(events[key].price_in_usd) * kurs_usd.value);
						}
					}
				});
				total = total * this.model.members.length;
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
				// var birthday = moment().format("Y-MM-DD");
				// formData.set("birthday", birthday);
				formData.append('model', JSON.stringify(this.model));
				formData.append('group', true);
				this.saving = true;
				app.postRegister = {
					show: false,
					url: "",
					email: ""
				};
				$.ajax({
					url: '<?= base_url('admin/member/register_group'); ?>',
					type: 'POST',
					contentType: false,
					cache: false,
					processData: false,
					data: formData
				}).done(function(res) {
					if (res.status == false) {
						app.model.members = res.members;
						app.validation_error = res.validation_error;
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
						// $('#form-register').trigger('reset');
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
			checkMember(member){
                member.checking = true;
                $.get("<?=base_url('member/register/info_member_perdossi');?>/"+member.nik,(res)=>{
                    if(res.message == "success"){
                            member.kta = res.member.perdossi_no;
                            member.fullname = `${res.member.member_title_front} ${res.member.fullname} ${res.member.member_title_back}`;
                            member.email = res.member.email;
                            member.phone = res.member.member_phone;
                    }
                }).always(()=>{
                    member.checking = false;
                }).fail(()=>{
                    Swal.fire('Fail', 'Failed to get member information in perdossi API', 'error')
                })
            },
			addMembers() {
				// var status = [];
				// app.model.selected.forEach((v, i) => {
				// 	status[i] = '';
				// });

				// console.log(status);

				this.model.members.push({
					email: '',
					fullname: '',
					univ: '',
					other_institution: '',
					sponsor: '',
					price: '',
					message_payment: '',
					nik:'',
                    checking:false,
					kta:'',
                    phone:'',
					validation_error: {
						status: [],
					}
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
			app.model.members[$(this).data('index')].univ = $(this).val();
		});

		$('.status-participant').change(function(e) {
			e.preventDefault();
			app.model.selected = [];
		});
	});
</script>
<?php $this->layout->end_script(); ?>