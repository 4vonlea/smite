<?php

/**
 * @var array $events
 */
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8"></div>
<!-- Page content -->
<div class="container-fluid mt--7">
	<!-- Table -->
	<div class="row" v-show="formMode == 0">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-3">
							<h3>Event Discount</h3>
						</div>
						<div class="col-9 text-right">
							<button class="btn btn-primary" @click="addRule"><i class="fa fa-plus"></i>
								Add Discount Rule
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<datagrid ref="datagrid" api-url="<?= base_url('admin/event_discount/grid'); ?>" :fields="[{name:'name',title:'Name Discount',sortField:'name'},{name:'discount',title:'Nominal Discount',sortField:'discount'},{name:'event_combination',title:'Information'},{name:'id',title:'Action'}]">
						<template slot="discount"  slot-scope="props">
							{{ props.row.discount | currency }}
						</template>
						<template slot="event_combination"  slot-scope="props">
							<span v-html="parseEventCombination(props.row.event_combination)"></span>
						</template>
						<template slot="id" slot-scope="props">
							<button class="btn btn-primary btn-sm" @click="editRule(props.row)">
								<span class="fa fa-edit"></span> Edit
							</button>
							<button class="btn btn-danger btn-sm" @click="deleteRule(props.row,$event)">
								<span class="fa fa-trash"></span> Delete
							</button>
						</template>
					</datagrid>
				</div>
			</div>
		</div>
	</div>
	<div class="row" v-show="formMode == 1">
		<div class="col-xl-12">
			<div class="card mb-4">
				<!-- Card header -->
				<div class="card-header">
					<div class="row">
						<div class="col-12">
							<h4 class="card-title">Discount Rule</h4>
						</div>
					</div>
				</div>
				<!-- Card body -->
				<div class="card-body">
					<!-- Form groups used in grid -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Name</label>
								<input type="text" class="form-control" :class="{'is-invalid':validation.name}" v-model="form.name" />
								<div v-if="validation.name" class="invalid-feedback">
									{{validation.name}}
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Pricing Category</label>
								<select class="form-control" :class="{'is-invalid':validation['event_combination[pricingCategory]']}" v-model="form.event_combination.pricingCategory">
									<option value="">-- Choose Pricing Category --</option>
									<option v-for="cat in listPricingCategory" :value="cat.name"> {{ cat.name }}</option>
								</select>
								<div v-if="validation['event_combination[pricingCategory]']" class="invalid-feedback">
									{{validation['event_combination[pricingCategory]']}}
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Nominal Discount (Rp)</label>
								<money v-model="form.discount" v-bind="money" class="form-control" :class="{'is-invalid':validation.discount}"></money>
								<div v-if="validation.discount" class="invalid-feedback d-block">
									{{validation.discount}}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Event Category</th>
										<th>Minimum Followed Event</th>
										<th>
											<button @click="addRuleCategory" class="btn btn-sm btn-primary">Add Category</button>
											<button @click="addRuleEvent" class="btn btn-sm btn-primary">Add Event</button>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(rule,ind) in form.event_combination.ruleCategory">
										<td>
											<select v-if="rule.key.startsWith('event_')" class="form-control" :class="{'is-invalid':validation['event_combination[ruleCategory]['+ind+'][key]']}" v-model="rule.key">
												<option value="">-- Choose Event --</option>
												<option v-for="cat in listEvents" :value="'event_'+cat.id"> {{ cat.name }}</option>
											</select>
											<select v-else class="form-control" :class="{'is-invalid':validation['event_combination[ruleCategory]['+ind+'][key]']}" v-model="rule.key">
												<option value="">-- Choose Event Category --</option>
												<option v-for="cat in eventCategory" :value="cat"> {{ cat }}</option>
											</select>
											<div v-if="validation['event_combination[ruleCategory]['+ind+'][key]']" class="invalid-feedback d-block">
												{{validation['event_combination[ruleCategory]['+ind+'][key]']}}
											</div>
										</td>
										<td>
											<input type="number" class="form-control" :class="{'is-invalid':validation['event_combination[ruleCategory]['+ind+'][val]']}"  v-model="rule.val" />
											<div v-if="validation['event_combination[ruleCategory]['+ind+'][val]']" class="invalid-feedback d-block">
												{{validation['event_combination[ruleCategory]['+ind+'][val]']}}
											</div>
										</td>
										<td>
											<button @click="deleteRulecategory(ind)" class="btn btn-sm btn-danger">
												<i class="fa fa-trash"></i>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					<button @click="save($event)" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
					<button @click="formMode=0" class="btn btn-default"><i class="fa fa-reply"></i> Back</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->layout->begin_head(); ?>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/vue2-datepicker@3.11.0/index.css">
<style>
	.mx-datepicker {
		display: block;
	}
</style>
<?php $this->layout->end_head(); ?>

<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/v-money.js"); ?>"></script>
<script src="https://unpkg.com/vue2-datepicker@3.11.0" charset="utf-8"></script>
<script>
	Vue.component('date-picker', DatePicker);
	let formatter = new Intl.NumberFormat("id",{style:"currency",currency:"IDR",minimumFractionDigits:0,maximumFractionDigits:0});
	Vue.filter('currency',function(value){
		return formatter.format(value);
	});

	var app = new Vue({
		el: '#app',
		data: {
			formMode: 0,
			listPricingCategory: <?= json_encode($listPricingCategory); ?>,
			listEvents: <?= json_encode($listEvents); ?>,
			eventCategory: <?= Settings_m::eventCategory(); ?>,
			form: {
				id: null,
				name: '',
				event_combination: {
					pricingCategory: '',
					ruleCategory: [],
				}
			},
			money: {
				decimal: ',',
				thousands: '.',
				precision: 0,
				masked: false
			},
			validation: {},
		},
		computed:{
			eventsParsed(){
				let list = {};
				this.listEvents.forEach( row => {
					list['event_'+row.id] = row.name;
				});
				return list;
			}
		},
		methods: {
			addRule() {
				this.formMode = 1;
				this.form = {
					id: null,
					name: '',
					event_combination: {
						pricingCategory: '',
						ruleCategory: [],
					}
				};
			},
			addRuleCategory() {
				this.form.event_combination.ruleCategory.push({
					key: '',
					val: 0,
				});
			},
			addRuleEvent() {
				this.form.event_combination.ruleCategory.push({
					key: 'event_',
					val: 1,
				});
			},
			deleteRulecategory(ind) {
				Swal.fire({
					title: "Are you sure ?",
					text: `You will delete this rule ?`,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						this.form.event_combination.ruleCategory.splice(ind, 1);
					}
				});
			},
			save(evt) {
				var btn = evt.currentTarget;
				btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
				btn.setAttribute("disabled", true);
				this.validation = {};
				$.post("<?= base_url("admin/event_discount/save"); ?>", this.form, (res) => {
					if (res.status) {
						this.form.id = res.data.id;
						Swal.fire("Success", "Discount saved successfully", "success");
						this.$refs.datagrid.refresh();
					} else if (res.validation) {
						this.validation = res.validation;
					} else {
						Swal.fire("Failed", res.message, "error");
					}
				}, "JSON").fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					btn.innerHTML = '<i class="fa fa-save"></i> Save';
					btn.removeAttribute("disabled");
				});
			},
			parseEventCombination(stringJson){
				try {
					let eventCombination = JSON.parse(stringJson);
					let parsed = `
						<span class="badge badge-info mb-1" style="font-size:100%">Pricing Category : ${eventCombination.pricingCategory} </span><br/>
						Rules :
						<ul>
					`;
					Object.keys(eventCombination).forEach( key => {
						if(key != "pricingCategory" && key.startsWith('event_')){
							parsed += `<li>Must follow ${this.eventsParsed[key]}</li>`;
						}else if(key != "pricingCategory"){
							parsed += `<li>Minimum ${eventCombination[key]} event of ${key}</li>`;
						}
					})
					return parsed+"</ul>";
				} catch(e) {
					console.log(e);
				}
				return '';
			},
			editRule(row) {
				let rulesCategory = [];
				try {
					let eventCombination = JSON.parse(row.event_combination);
					Object.keys(eventCombination).forEach( key => {
						if(key != "pricingCategory"){
							rulesCategory.push({
								key:key,
								val:eventCombination[key],
							});
						}
					});
					this.form = {
						id:row.id,
						discount:row.discount,
						name:row.name,
						event_combination: {
							pricingCategory: eventCombination.pricingCategory,
							ruleCategory: rulesCategory,
						}
					};
					this.formMode = 1;
				} catch(e) {
					Swal.fire("Warning","Failed to parse this data, Please delete this rules !", "warning");
				}
				
			},
			deleteRule(row, evt) {
				var btn = evt.currentTarget;
				Swal.fire({
					title: "Are you sure ?",
					text: `You will delete a Rule discount with name "${row.name}"`,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
						btn.setAttribute("disabled", true);
						$.post("<?= base_url("admin/event_discount/delete"); ?>", {
							id: row.id
						}, function(res) {
							if (res.status) {
								Swal.fire("Success", "Rule discount deleted successfully", "success");
								app.$refs.datagrid.refresh();
							} else
								Swal.fire("Failed", res.message, "error");
						}, "JSON").fail(function(xhr) {
							var message = xhr.getResponseHeader("Message");
							if (!message)
								message = 'Server fail to response !';
							Swal.fire('Fail', message, 'error');
						}).always(function() {
							btn.innerHTML = '<i class="fa fa-trash"></i> Delete';
							btn.removeAttribute("disabled");
						});
					}
				});
			},
		},
	});
</script>
<?php $this->layout->end_script(); ?>