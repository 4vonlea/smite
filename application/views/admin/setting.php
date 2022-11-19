<?php

/**
 * @var $wa_token
 * @var $email_binded
 * @var $event
 * @var $manual
 */
?>
<?php $this->layout->begin_head(); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.css">
<?php $this->layout->end_head(); ?>

<div class="header bg-primary pb-8 pt-5 pt-md-7"></div>
<div class="container-fluid mt--7">
	<div class="row mb-2">
		<div class="col">
			<div class="nav-wrapper">
				<ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0 active" data-toggle="tab" href="#tabs-general" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-world mr-2"></i>General</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab" href="#tabs-certificate" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-book-bookmark mr-2"></i>Certificate</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab" href="#tabs-nametag" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-book-bookmark mr-2"></i>Name Tag</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab" href="#tabs-notification" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-book-bookmark mr-2"></i>Notification</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab" href="#tabs-manual_payment" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="fa fa-money-bill mr-2"></i>Payment</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="card shadow">
				<div class="card-body">
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="tabs-general" role="tabpanel">
							<div class="row">
								<div class="col">General Setting</div>
								<div class="col">
									<button @click="onSave" :disabled="saving" type="button" class="btn btn-primary float-right"><i :class="[saving ?'fa fa-spin fa-spinner':'fa fa-save']"></i> Save
									</button>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-2">
									<label>Site Logo
									</label>

									<div class="card">
										<img class="card-img-top" :src="logo_src" alt="Card image cap" />
										<div class="card-body text-center">
											<input id="inputFile" type="file" class="d-none" ref="file" v-on:change="logoUpload" />
											<button :disabled="uploading" @click="$refs.file.click();" class="btn btn-primary">
												<span v-if="uploading" class="fa fa-spin fa-spinner"></span>
												Change
											</button>
										</div>
										<small>*max width 1024px</small>
									</div>
								</div>
								<div class="col-md-9">
									<div class="form-group">
										<label>Site Title</label>
										<input type="text" class="form-control" v-model="form.site_title" />
									</div>
									<div class="form-group">
										<label>Preface</label>
										<textarea rows="4" class="form-control" v-model="form.preface"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Text on Registration Proof</label>
										<textarea class="form-control" v-model="form.text_payment_proof"></textarea>
									</div>
									<div class="form-group">
										<label>Format ID Paper</label>
										<div class="input-group">
											<input type="text" class="form-control" v-model="form.format_id_paper" />
											<div class="input-group-append">
												<span class="input-group-text">[INDEX]</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Chairman of the committee</label>
										<input type="text" class="form-control" v-model="form.ketua_panitia" />
									</div>
								</div>
								<div class="col-md-6">
									<label>Event Date (for Countdown)</label>
									<vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm:ss" formatted="DD MMMM YYYY HH:mm" v-model="form.event_countdown"></vue-ctk-date-time-picker>
								</div>
								<div class="col-md-6">
									<label>Paper Submission Deadline (for Countdown)</label>
									<vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm:ss" formatted="DD MMMM YYYY HH:mm" v-model="form.paper_deadline"></vue-ctk-date-time-picker>
								</div>

							</div>
						</div>
						<div class="tab-pane fade show" id="tabs-certificate" role="tabpanel">
							<div class="row">
								<div class="col">Certificate Template</div>
								<div class="col text-right">
								<button :disabled="savingCert" type="button" @click="saveCert" class="btn btn-primary">
												<i v-if="savingCert" class="fa fa-spin fa-spinner"></i>
												Save Template
											</button>
											<a class="btn btn-primary" :href="urlPreview" target="_blank">
												Preview In PDF
											</a>
								</div>
							</div>
							<hr />
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-md-3 col-form-label">
											Template For Events
											<br/><small>Dimension : 1120px x 790px </small>
										</label>
										<?php
										$list = Event_m::asList($event, "id", "name");
										$list['Paper'] = "Paper";
										$list[""] = "Select Event";
										echo form_dropdown("event", $list, "", ['class' => 'form-control col-md-3', '@change' => 'changeCertEvent', 'v-model' => 'selectedEvent', 'id' => 'sel_event']);
										?>
										<div class="col-md-6">
											<div class="custom-file">
												<input ref="certImage" type="file" @change="changeCertImage" class="custom-file-input" id="customFile">
												<label class="custom-file-label" for="customFile">{{ cert.fileName }}</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-form-label">Parameter from Member</label>
										<select class="form-control col-md-3" name="param" v-model="selectedParam" id="sel_param">
												<option v-for="(val,key) in paramsCertificate" :value="key">{{ val }}</option>
										</select>
										<div class="col-md-6">
											<button type="button" @click="addPropertyCert" class="btn btn-primary">Add
												Property
											</button>
											<button type="button" @click="addCertificatePage" class="btn btn-primary">
												Add Page
											</button>
										</div>
									</div>
									<div class="form-group row">
										<div class="col">
											<div class="pagination">
												<button type="button" class="btb-nav btn" :class="{'active bg-primary': currentCertificatePage == -1}"  @click="currentCertificatePage = -1">1</button>
												<button type="button"  v-for="(page,ind) in this.cert.anotherPage" class="btb-nav btn" @click="currentCertificatePage = ind" :class="{'active bg-primary': currentCertificatePage == ind}">{{ ind+2 }}</button>
											</div>
										</div>
									</div>
									<div v-if="currentCertificatePage == -1" class="form-group row">
										<div class="col-md-12">
											<table class="table">
												<thead>
													<tr>
														<th></th>
														<th>Property Name</th>
														<th>Width Area (%)</th>
														<th>Font Weighted</th>
														<th>Font Size (px)</th>
														<th>Position X (%)</th>
														<th>Position Y (%)</th>
													</tr>
												</thead>
												<tbody>
													<tr v-for="(prop,index) in cert.property">
														<td>
															<button @click="deleteProp(index)" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i></button>
														</td>
														<td>{{ prop.name }}</td>
														<td><input type="number" v-model="prop.style.width" min="0" max="100" step="0.1" class="form-control" /></td>
														<td>
															<select v-model="prop.style.fontWeight" class="form-control">
																<option value="normal">Normal</option>
																<option value="bold">Bold</option>
																<option value="bolder">Bolder</option>
																<option value="lighter">Lighter</option>
															</select>
														</td>
														<td><input type="number" v-model="prop.style.fontSize" class="form-control" /></td>
														<td><input type="number" min="0" max="100" step="0.1" v-model="prop.style.left" class="form-control" /></td>
														<td><input type="number" min="0" max="100" step="0.1" v-model="prop.style.top" class="form-control" /></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div v-if="currentCertificatePage >= 0" class="form-group row">
										<label class="control-label col-md-3">Image File <small>Dimension : 1120px x 790px </small></label>
										<div class="col-md-7" style="padding-left: 0px;padding-right:0px;">
											<div class="custom-file">
												<input ref="certSecondImage" type="file" @change="setCertSecondPage" class="custom-file-input" id="customFile">
												<label class="custom-file-label" for="customFile">{{ cert.anotherPage[currentCertificatePage].filename }}</label>
											</div>
										</div>
										<div class="col-md-2">
											<button type="button" class="btn btn-primary" @click="deleteCertificatePage">Delete Page</button>
										</div>
									</div>
									<div class="form-group">
										<label>Preview</label>
									</div>
								</div>
								<div v-if="currentCertificatePage == -1" style="position: relative">
									<img :src="cert.image" style="width: 100%" />
									<div v-for="(prop,k) in certProperty" @mousedown="setMouseDown(cert.property[k])" @mouseup="setMouseUp(cert.property[k])" :style="[prop.background,prop.style]">
										<span v-if="prop.name != 'qr_code'">{{ prop.name }}</span>
										<img v-else src="<?= base_url('themes/uploads/qrpreview.png'); ?>" :style="{height:prop.style.width,width:prop.style.width}" />
									</div>
								</div>
								<div v-else>
									<img :src="this.cert.anotherPage[currentCertificatePage].image" style="width: 100%" />
								</div>
							</div>
						</div>
						<div class="tab-pane fade show" id="tabs-nametag" role="tabpanel">
							<div class="row">
								<div class="col">Nametag Template</div>
							</div>
							<hr />
							<div class="row">
								<div class="col-md-12">
									<p>
										*Recomended image dimension is (380 x 530) px or ratio near it
									</p>
									<div class="form-group row">
										<label class="col-md-3 col-form-label">Template For Events</label>
										<?php
										echo form_dropdown("event_nametag", $list, "", ['class' => 'form-control col-md-3', '@change' => 'changeNametagEvent', 'v-model' => 'selectedEventNametag', 'id' => 'sel_event_nametag']);
										?>
										<div class="col-md-6">
											<div class="custom-file">
												<input ref="nametagImage" type="file" @change="changeNametagImage" class="custom-file-input" id="customFileN">
												<label class="custom-file-label" for="customFileN">{{ nametag.fileName
													}}</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-form-label">Parameter from Member</label>
										<?= form_dropdown("param", ['' => 'Select Parameter', 'qr' => 'QR Code', 'fullname' => 'Full Name', 'status_member' => 'Status Of Member', 'event_name' => 'Event Name'], "", ['class' => 'form-control col-md-3', 'v-model' => 'selectedParamNametag', 'id' => 'sel_param_nametag']); ?>
										<div class="col-md-6">
											<button type="button" @click="addPropertyNametag" class="btn btn-primary">Add
												Property
											</button>
											<button :disabled="savingNametag" type="button" @click="saveNametag" class="btn btn-primary">
												<i v-if="savingNametag" class="fa fa-spin fa-spinner"></i>
												Save Template
											</button>
											<a class="btn btn-primary" :href="urlPreviewNametag" target="_blank">
												Preview In PDF
											</a>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12">
											<table class="table">
												<thead>
													<tr>
														<th></th>
														<th>Property Name</th>
														<th>Width Area (%)</th>
														<th>Font Weighted</th>
														<th>Font Size (px)</th>
														<th>Position X (%)</th>
														<th>Position Y (%)</th>
													</tr>
												</thead>
												<tbody>
													<tr v-for="(prop,index) in nametag.property">
														<td>
															<button @click="deletePropNametag(index)" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i></button>
														</td>
														<td>{{ prop.name }}</td>
														<td><input type="number" v-model="prop.style.width" min="0" max="100" step="0.1" class="form-control" /></td>
														<td>
															<select v-model="prop.style.fontWeight" class="form-control">
																<option value="normal">Normal</option>
																<option value="bold">Bold</option>
																<option value="bolder">Bolder</option>
																<option value="lighter">Lighter</option>
															</select>
														</td>
														<td><input type="number" v-model="prop.style.fontSize" class="form-control" /></td>
														<td><input type="number" min="0" max="100" step="0.1" v-model="prop.style.left" class="form-control" /></td>
														<td><input type="number" min="0" max="100" step="0.1" v-model="prop.style.top" class="form-control" /></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="form-group">
										<label>Preview</label>
									</div>
								</div>
								<div style="position: relative;border: 1px dotted;height: 530px;width: 380px;margin: auto">
									<img :src="nametag.image" style="width: 100%" />
									<div style="background:rgba(0,0,0,0.3)" v-for="prop in nametagProperty" :style="prop.style">
										<span v-if="prop.name != 'qr'">{{ prop.name }}</span>
										<img v-else src="<?= base_url('themes/uploads/qrpreview.png'); ?>" :style="{height:prop.height,width:prop.height}" />
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade show" id="tabs-notification" role="tabpanel">
							<div class="row">
								<div class="col">Notification Setting</div>
							</div>
							<hr />
							<div class="row">
								<div class="col-3">
									<label>Email Used For Notification</label>
								</div>
								<div class="col-7">
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="customRadioInline1" value="gmail" class="custom-control-input" v-model="typeMailer">
										<label class="custom-control-label" for="customRadioInline1">Using Gmail</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="customRadioInline2" value="mailer" class="custom-control-input" v-model="typeMailer">
										<label class="custom-control-label" for="customRadioInline2">Using Mail Server</label>
									</div>
								</div>
								<div class="col-2 text-right">
									<button @click="saveMailer" :disabled="savingMailer" class="btn btn-primary" type="button">
										<i v-show="savingMailer" class="fa fa-spin fa-spinner"></i> Save
									</button>
								</div>
							</div>
							<div v-if="typeMailer == 'gmail'" class="row mt-5">
								<div class="col-3">
									<label>Gmail Account Used</label>
								</div>
								<div v-if="email_notif_binded" class="col-4">
									<input type="text" readonly v-model="email_notif" class="form-control" />
								</div>
								<div class="col-4">
									<button v-if="email_notif_binded" @click="unbindEmail" class="btn btn-danger">
										Unbind
									</button>
									<a v-else href="<?= base_url("admin/setting/request_auth"); ?>" class="btn btn-primary">Bind</a>
								</div>
							</div>
							<div v-if="typeMailer == 'mailer'" class="row mt-5">
								<div class="col-12">
									<div class="form-group">
										<label class="form-control-label">SMTP Address</label>
										<input type="text" class="form-control" v-model="mailer.smtp_host" />
									</div>
									<div class="form-group">
										<label class="form-control-label">SMTP Port</label>
										<input type="text" class="form-control" v-model="mailer.smtp_port" />
									</div>
									<div class="form-group">
										<label class="form-control-label">Email</label>
										<input type="text" class="form-control" v-model="mailer.email" />
									</div>
									<div class="form-group">
										<label class="form-control-label">Password</label>
										<input type="text" class="form-control" v-model="mailer.password" />
									</div>
								</div>
							</div>
							<hr />
							<div class="row mt-3">
								<div class="col-12">
									<h4>Another Service</h5>
										<hr />
								</div>
								<div class="col-3">
									<label>Whatsapp (Token from wablas.com)</label>
								</div>
								<div class="col-6">
									<input type="text" v-model="wa_api_token" class="form-control" />
								</div>
								<div class="col-3">
									<button @click="saveTokenWa" class="btn btn-primary">Save</button>
								</div>
							</div>
						</div>
						<div class="tab-pane fade show" id="tabs-manual_payment" role="tabpanel">

							<div class="row">
								<div class="col">Kurs USD</div>
								<div class="form-check">
									<input class="form-check-input" v-model="kurs_usd.using_api" type="checkbox" :checked="kurs_usd.using_api" value="kurs_usd.using_api" id="enableApiCurrency" @click="enableApiCurrency">
									<label class="form-check-label" for="enableManualPayment">
										Using API
									</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<div class="input-group input-group-alternative">
											<div class="input-group-prepend">
												<span class="input-group-text">IDR</span>
											</div>
											<money :disabled="kurs_usd.using_api == 1" v-model="kurs_usd.value" v-bind="money" class="form-control"></money>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col">Manual Payment</div>
								<div class="form-check">
									<input class="form-check-input" v-model="enablePayment" type="checkbox" value="manualPayment;Manual Payment" id="enableManualPayment">
									<label class="form-check-label" for="enableManualPayment">
										Enable Manual Payment
									</label>
								</div>
							</div>
							<hr />
							<div class="row">
								<div class="col-md-12">

									<div class="form-group">
										<label class="form-control-label">Email To Receive Notification</label>
										<input type="text" class="form-control" v-model="manualPayment.emailReceive" />
									</div>
									<div class="form-group">
										<label class="form-control-label">Banks Info</label>
										<table class="table">
											<tr>
												<th>Bank Name</th>
												<th>Account Number</th>
												<th>Account Name Holder</th>
												<th>
													<button @click="manualPayment.banks.push({bank:'',no_rekening:'',holder:''});" type="btn" class="btn btn-primary"><i class="fa fa-plus"></i>
													</button>
												</th>
											</tr>
											<tr v-if="manualPayment.banks.length == 0">
												<td class="text-center" colspan="4">No Data</td>
											</tr>
											<tr v-for="(bank,index) in manualPayment.banks">
												<td>
													<input type="text" class="form-control" v-model="bank.bank" />
												</td>
												<td>
													<input type="text" class="form-control" v-model="bank.no_rekening" />
												</td>
												<td>
													<input type="text" class="form-control" v-model="bank.holder" />
												</td>
												<td>
													<button @click="manualPayment.banks.splice(index,1)" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
										</table>
									</div>

								</div>
							</div>
							<div class="row">
								<div class="col">Espay Payment Gateway</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" v-model="enablePayment" value="espay;Online Payment" id="enableEspay">
									<label class="form-check-label" for="enableEspay">
										Enable Espay Payment
									</label>
								</div>
							</div>
							<hr />
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label class="form-control-label">JS KIT URL</label>
										<input type="text" class="form-control" v-model="espay.jsKitUrl" placeholder="https://kit.espay.id/public/signature/js" />
									</div>
									<div class="form-group">
										<label class="form-control-label">Web API Link</label>
										<input type="text" class="form-control" v-model="espay.apiLink" placeholder="https://api.espay.id/rest/merchant/" />
									</div>
									<div class="form-group">
										<label class="form-control-label">Merchant Code</label>
										<input type="text" class="form-control" v-model="espay.merchantCode" />
									</div>
									<div class="form-group">
										<label class="form-control-label">API Key</label>
										<input type="text" class="form-control" v-model="espay.apiKey" />
									</div>
									<div class="form-group">
										<label class="form-control-label">Signature</label>
										<input type="text" class="form-control" v-model="espay.signature" />
									</div>
								</div>
							</div>
							<hr />
							<div class="row">
								<div class="col form-group text-right">
									<button @click="saveManualPayment" :disabled="savingManual" type="button" class="btn btn-primary">
										<i :class="[savingManual ? 'fa fa-spin fa-spinner':'fa fa-disk']"></i> Save
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div v-if="loading" class="modal fade show" id="modal-loading" style="display: block; padding-right: 16px;background: rgba(0, 0, 0, 0.3)">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-body text-center">
				<div class="fa fa-spin fa-spinner fa-4x" role="status">
					<span class="sr-only">Loading...</span>
				</div>
				<p>Loading Data . . .. </p>

			</div>

		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<script src="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.umd.js" charset="utf-8"></script>
<script src="<?= base_url("themes/script/v-money.js"); ?>"></script>

<script>
	
	<?php
		$kursUsd = Settings_m::getSetting("kurs_usd");
	?>
	var banks = <?= $manual; ?>;
	var emailReceive = "<?= Settings_m::getSetting("email_receive"); ?>";
	var kurs_usd = <?= $kursUsd == "" ? '{using_api:false,value:0}' : $kursUsd; ?>;
	const toBase64 = file => new Promise((resolve, reject) => {
		const reader = new FileReader();
		if (file) {
			reader.readAsDataURL(file);
			reader.onload = () => resolve(reader.result);
		} else {
			resolve(null);
		}
		reader.onerror = error => reject(error);
	});

	function defaultCert() {
		return {
			image: "",
			base64Image: "",
			fileName: "Select Image as Template",
			body: {
				width: "100%"
			},
			secondPage:{
				filename:"Select image file",
				base64:"",
			},
			anotherPage:[],
			property: []
		}
	}

	function defaultNametag() {
		return {
			image: "",
			base64Image: "",
			fileName: "Select Image as Template",
			body: {
				width: "100%"
			},
			property: []
		}
	}
	Vue.component('vue-ctk-date-time-picker', window['vue-ctk-date-time-picker']);
	var app = new Vue({
		'el': '#app',
		data: {
			loading: false,
			selectedEvent: "",
			selectedEventNametag: "",
			selectedParam: "",
			selectedParamNametag: "",
			saving: false,
			savingCert: false,
			savingNametag: false,
			savingMailer: false,
			uploading: false,
			preview_certificate: "",
			espay: <?= json_encode(Settings_m::getEspay()); ?>,
			enablePayment: <?= json_encode(Settings_m::getEnablePayment()); ?>,
			typeMailer: "<?= $this->Notification_m->getDefaultMailer() ?>",
			mailer: <?= $this->Notification_m->getValue(Notification_m::SETTING_MAILER) ?>,
			logo_src: '<?= base_url('themes/uploads/logo.png'); ?>',
			form: {
				preface: <?= json_encode(Settings_m::getSetting('preface')); ?>,
				site_title: '<?= Settings_m::getSetting('site_title'); ?>',
				text_payment_proof: '<?= Settings_m::getSetting('text_payment_proof'); ?>',
				format_id_paper: '<?= Settings_m::getSetting('format_id_paper'); ?>',
				ketua_panitia: '<?= Settings_m::getSetting('ketua_panitia'); ?>',
				event_countdown: '<?= Settings_m::getSetting('event_countdown'); ?>',
				paper_deadline: '<?= Settings_m::getSetting('paper_deadline'); ?>',
			},
			email_notif_binded: <?= $email_binded; ?>,
			email_notif: "<?= Settings_m::getSetting(Notification_m::SETTING_GMAIL_ADMIN); ?>",
			wa_api_token: "<?= $wa_token; ?>",
			cert: defaultCert(),
			nametag: defaultNametag(),
			manualPayment: {
				emailReceive: emailReceive,
				banks: banks
			},
			currentCertificatePage:-1,
			kurs_usd: kurs_usd,
			savingManual: false,
			paramsEvent:<?=json_encode(['' => 'Select Parameter', 'fullname' => 'Full Name', 'email' => 'Email', 'gender' => 'Gender', 'status_member' => 'Status Of Member', 'event_name' => 'Event Name', 'alternatif_status' => 'Alternatif Status','alternatif_status2' => 'Alternatif Status 2','qr_code'=>'QR Code (ID Invoice)']);?>,
			paramsPaper:<?=json_encode(['' => 'Select Parameter', 'fullname' => 'Full Name', 'email' => 'Email', 'title' => 'Paper Title', 'id_paper' => 'ID Paper','type_presence'=>'Mode Presentation', 'status' => 'Participant/Champion Status','qr_code'=>'QR Code (ID Paper)']);?>,
			money: {
				decimal: ',',
				thousands: '.',
				precision: 0,
				masked: false
			},
			hoverObject:{},
		},
		computed: {
			paramsCertificate(){
				if(this.selectedEvent == "Paper"){
					return this.paramsPaper;
				}
				return this.paramsEvent;
			},
			urlPreview() {
				return "<?= base_url('admin/setting/preview_cert'); ?>/" + this.selectedEvent;
			},
			urlPreviewNametag() {
				return "<?= base_url('admin/setting/preview_nametag'); ?>/" + this.selectedEventNametag;
			},
			certProperty() {
				var ret = [];
				$.each(this.cert.property, function(i, r) {
					var temp = {
						"name": r.name,
						"background":r.background ?? {background:'rgba(0, 0, 0, 0.3)'},
						"style": {
							"width": r.style.width + "%",
							"textAlign": r.style.textAlign,
							"fontWeight": r.style.fontWeight,
							"position": "absolute",
							"top": r.style.top + "%",
							"left": r.style.left + "%",
							"fontSize": r.style.fontSize + "px",
						}
					}
					ret.push(temp);
				});
				return ret;
			},
			nametagProperty() {
				var ret = [];
				$.each(this.nametag.property, function(i, r) {
					var temp = {
						"name": r.name,
						"style": {
							"width": r.style.width + "%",
							"textAlign": r.style.textAlign,
							"fontWeight": r.style.fontWeight,
							"position": "absolute",
							"top": r.style.top + "%",
							"left": r.style.left + "%",
							"fontSize": r.style.fontSize + "px",
						},
						"height": r.style.fontSize + "%"
					}
					ret.push(temp);
				});
				return ret;
			}
		},
		methods: {
			addCertificatePage(){
				this.cert.anotherPage.push({
					image:null,
					filename:null,
				});
			},
			deleteCertificatePage(){
				this.cert.anotherPage.splice(this.currentCertificatePage,1);
				this.currentCertificatePage = this.currentCertificatePage-1;
			},
			saveMailer() {
				this.savingMailer = true;
				$.post("<?= base_url('admin/setting/save_mailer'); ?>", {
					type: this.typeMailer,
					mailer: this.mailer
				}, function(res) {
					if (res.status) {
						Swal.fire("Success", "Setting Email Saved Successfully !", "success");
					}
				}, 'JSON').always(function() {
					app.savingMailer = false;
				});
			},
			saveManualPayment() {
				this.savingManual = true;
				$.post("<?= base_url('admin/setting/save_manual'); ?>", {
					manualPayment: this.manualPayment,
					espay: this.espay,
					enablePayment: this.enablePayment,
					kurs_usd: this.kurs_usd,
				}, function(res) {
					if (res.status) {
						Swal.fire("Success", "Setting Payment Saved Successfully !", "success");
					} else {
						Swal.fire('Fail', res.message, 'error');
					}
				}, 'JSON').always(function() {
					app.savingManual = false;
				});
			},
			deleteProp(index) {
				this.$delete(this.cert.property, index)
			},
			deletePropNametag(index) {
				this.$delete(this.nametag.property, index)
			},
			setCertSecondPage(e){
				var file = e.target.files[0];
				toBase64(file).then((res) => {
					if(res){
						this.cert.anotherPage[this.currentCertificatePage].filename = file.name;
						this.cert.anotherPage[this.currentCertificatePage].image = res;
					}
				})

			},
			setMouseDown(prop){
				// console.log(prop);
				// prop.background = {background:"grey"};
				// this.hoverObject = style;
			},
			setMouseUp(prop){
				// console.log("Up",prop);
//				prop.background = {background:"rgba(0, 0, 0, 0.3)"};
				// this.hoverObject = {};
			},
			changeCertImage(e) {
				var file = e.target.files[0];
				this.cert.fileName = file.name;
				this.cert.image = URL.createObjectURL(file);
			},
			changeNametagImage(e) {
				var file = e.target.files[0];
				this.nametag.fileName = file.name;
				this.nametag.image = URL.createObjectURL(file);
			},
			changeNametagEvent() {
				this.loading = true;
				$.post("<?= base_url('admin/setting/get_nametag'); ?>", {
					id: this.selectedEventNametag
				}, function(res) {
					if (res.status) {
						app.nametag = res.data;
					} else {
						app.nametag = defaultNametag();
					}
				}, 'JSON').always(function() {
					app.loading = false;
				});
			},
			changeCertEvent() {
				this.loading = true;
				this.currentCertificatePage = -1;
				$.post("<?= base_url('admin/setting/get_cert'); ?>", {
					id: this.selectedEvent
				}, function(res) {
					if (res.status) {
						app.cert = res.data;
					} else {
						app.cert = defaultCert();
					}
				}, 'JSON').always(function() {
					app.loading = false;
				});
			},
			addPropertyCert() {
				this.cert.property.push({
					"name": this.selectedParam,
					"background":{background:"rgba(0, 0, 0, 0.3)"},
					"style": {
						"width": 100,
						"textAlign": "center",
						"fontWeight": "normal",
						"position": "absolute",
						"top": 0,
						"left": 0,
						"fontSize": 12
					}
				});
			},
			addPropertyNametag() {
				this.nametag.property.push({
					"name": this.selectedParamNametag,
					"style": {
						"width": 100,
						"textAlign": "center",
						"fontWeight": "normal",
						"position": "absolute",
						"top": 0,
						"left": 0,
						"fontSize": 12
					}
				});
			},
			saveCert() {
				toBase64(app.$refs.certImage.files[0]).then(function(result) {
					if (result)
						app.cert.base64Image = result;
					app.cert.event = app.selectedEvent;
					app.savingCert = true;
					$.post("<?= base_url("admin/setting/save_cert"); ?>", app.cert, null, 'JSON')
						.done(function(res) {
							if (res.status) {
								Swal.fire("Success", "Template Certificate Saved Successfully !", "success");
							}
						}).fail(function(xhr) {
							var message = xhr.getResponseHeader("Message");
							if (!message)
								message = 'Server fail to response !';
							Swal.fire('Fail', message, 'error');
						}).always(function() {
							app.savingCert = false;
						});
				});

			},
			saveNametag() {
				toBase64(app.$refs.nametagImage.files[0]).then(function(result) {
					if (result)
						app.nametag.base64Image = result;
					app.nametag.event = app.selectedEventNametag;
					app.savingNametag = true;

					$.post("<?= base_url("admin/setting/save_nametag"); ?>", app.nametag, null, 'JSON')
						.done(function(res) {
							if (res.status) {
								Swal.fire("Success", "Template Nametag Saved Successfully !", "success");
							}
						}).fail(function(xhr) {
							var message = xhr.getResponseHeader("Message");
							if (!message)
								message = 'Server fail to response !';
							Swal.fire('Fail', message, 'error');
						}).always(function() {
							app.savingNametag = false;
						});
				});

			},
			unbindEmail() {
				var app = this;
				$.post("<?= base_url("admin/setting/unbind_email"); ?>", {}, null, 'JSON')
					.done(function(res) {
						if (res.status) {
							app.email_notif_binded = false;
							app.email_notif = null;
						}
					}).fail(function(xhr) {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function() {
						app.$refs.datagrid.loading = false;
					});
			},
			saveTokenWa() {
				var app = this;
				$.post("<?= base_url("admin/setting/save_token_wa"); ?>", {
						token: app.wa_api_token
					}, null, 'JSON')
					.done(function(res) {
						app.detailModel = res;
						$("#modal-detail").modal("show");
					}).fail(function(xhr) {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}).always(function() {
						app.$refs.datagrid.loading = false;
					});
			},
			onSave() {
				app.saving = true;
				console.log(app.form);
				$.ajax({
					url: "<?= base_url('admin/setting/save'); ?>",
					type: "POST",
					data: {
						settings: app.form
					},
				}).done(function() {
					Swal.fire("Success", "Setting has been saved !", "success");
				}).fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					app.saving = false;
				});
			},
			logoUpload() {
				var formData = new FormData();
				formData.append("file", this.$refs.file.files[0]);
				this.uploading = true;
				$.ajax({
					url: "<?= base_url('admin/setting/upload_logo'); ?>",
					type: "POST",
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
				}).done(function() {
					app.logo_src = "<?= base_url('themes/uploads/logo.png'); ?>?" + (new Date().getTime());
				}).fail(function(xhr) {
					console.log(xhr);
					if (xhr.responseJSON.error) {
						Swal.fire("Failed", xhr.responseJSON.error, "error");
					} else {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}
				}).always(function() {
					app.uploading = false;
				});
			},
			formatCurrency(price) {
				return new Intl.NumberFormat("id-ID", {
					style: 'currency',
					currency: "IDR"
				}).format(price);
			},
			removeSecondImage(){
				app.saving = true;
				$.ajax({
					url: "<?= base_url('admin/setting/remove_second_image'); ?>",
					type: "POST",
					data: {
						id: app.selectedEvent
					},
				}).done(function() {
					app.changeCertEvent();
					Swal.fire("Success", "Second image removed !", "success");
				}).fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					app.saving = false;
				});
			},
			enableApiCurrency(e) {
				var cur = 'IDR';
				if (e.target.checked) {
					app.kurs_usd.using_api = true;
					$.get("<?= base_url('admin/setting/currency') ?>", function(res) {
						if (res.code == 200) {
							app.kurs_usd.value = res.data[cur];
						} else {
							Swal.fire("Gagal", res.message, "error");
						}
					});
				} else {
					app.kurs_usd.using_api = false;
					app.kurs_usd.value = kurs_usd.value;
				}
			},
		}
	});
	$(document).ready(function() {
		$("#sel_event option:last").attr({
			"disabled": "true",
			"hidden": "true"
		});
		$("#sel_event_nametag option:last").attr({
			"disabled": "true",
			"hidden": "true"
		});
		$("#sel_param option:first").attr({
			"disabled": "true",
			"hidden": "true"
		});
	});
</script>
<?php $this->layout->end_script(); ?>