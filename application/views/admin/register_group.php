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
										<input type="checkbox" v-model="model.selected" name="transaction[event][]" :value="[ev.id,ev.price,ev.product_name]" />
									</td>
									<td>{{ index }}</td>
									<td>{{ formatCurrency(ev.price) }}</td>
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

												<div class="form-group col-6">
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
							<small class="row col-12" for="">*PLEASE FILL YOUR NAME CORRECTLY FOR YOUR CERTIFICATE</small>
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
			saving: false,
			validation_error: {},
			events: <?= json_encode($events); ?>
		},
		computed: {
			total() {
				var total = 0;
				var selected = [];
				this.model.selected.forEach(function(item) {
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
				var status = this.listStatus[this.model.status];
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
			formatCurrency(price) {
				return new Intl.NumberFormat("id-ID", {
					style: 'currency',
					currency: "IDR"
				}).format(price);
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