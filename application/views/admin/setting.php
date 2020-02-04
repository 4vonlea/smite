<?php
/**
 * @var $wa_token
 * @var $email_binded
 * @var $event
 * @var $manual
 */
?>
<div class="header bg-info pb-8 pt-5 pt-md-7"></div>
<div class="container-fluid mt--7">
	<div class="row mb-2">
		<div class="col">
			<div class="nav-wrapper">
				<ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0 active" data-toggle="tab"
						   href="#tabs-general" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="ni ni-world mr-2"></i>General</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0"  data-toggle="tab"
						   href="#tabs-certificate" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="ni ni-book-bookmark mr-2"></i>Certificate</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab"
						   href="#tabs-nametag" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="ni ni-book-bookmark mr-2"></i>Name Tag</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab"
						   href="#tabs-notification" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="ni ni-book-bookmark mr-2"></i>Notification</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab"
						   href="#tabs-manual_payment" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="fa fa-money-bill mr-2"></i>Manual Payment(Bank Account)</a>
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
									<button @click="onSave" :disabled="saving" type="button"
											class="btn btn-primary float-right"><i
											:class="[saving ?'fa fa-spin fa-spinner':'fa fa-save']"></i> Save
									</button>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-2">
									<label>Site Logo
									</label>

									<div class="card">
										<img class="card-img-top" :src="logo_src" alt="Card image cap"/>
										<div class="card-body text-center">
											<input id="inputFile" type="file" class="d-none" ref="file"
												   v-on:change="logoUpload"/>
											<button :disabled="uploading" @click="$refs.file.click();"
													class="btn btn-primary">
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
										<input type="text" class="form-control" v-model="form.site_title"/>
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
											<input type="text" class="form-control" v-model="form.format_id_paper"/>
											<div class="input-group-append">
												<span class="input-group-text">[INDEX]</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Chairman of the committee</label>
										<input type="text" class="form-control" v-model="form.ketua_panitia"/>

									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade show" id="tabs-certificate" role="tabpanel">
							<div class="row">
								<div class="col">Certificate Template</div>
							</div>
							<hr/>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-md-3 col-form-label">Template For Events</label>
										<?php
										$list = Event_m::asList($event, "id", "name");
										$list[""] = "Select Event";
										echo form_dropdown("event", $list, "", ['class' => 'form-control col-md-3', '@change' => 'changeCertEvent', 'v-model' => 'selectedEvent', 'id' => 'sel_event']);
										?>
										<div class="col-md-6">
											<div class="custom-file">
												<input ref="certImage" type="file" @change="changeCertImage"
													   class="custom-file-input" id="customFile">
												<label class="custom-file-label" for="customFile">{{ cert.fileName
													}}</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-form-label">Parameter from Member</label>
										<?= form_dropdown("param", ['' => 'Select Parameter', 'fullname' => 'Full Name', 'email' => 'Email', 'gender' => 'Gender', 'status_member' => 'Status Of Member'], "", ['class' => 'form-control col-md-3', 'v-model' => 'selectedParam', 'id' => 'sel_param']); ?>
										<div class="col-md-6">
											<button type="button" @click="addPropertyCert" class="btn btn-primary">Add
												Property
											</button>
											<button :disabled="savingCert" type="button" @click="saveCert"
													class="btn btn-primary">
												<i v-if="savingCert" class="fa fa-spin fa-spinner"></i>
												Save Template
											</button>
											<a class="btn btn-primary" :href="urlPreview" target="_blank">
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
												<tr v-for="(prop,index) in cert.property">
													<td>
														<button @click="deleteProp(index)"
																class="btn btn-sm btn-primary"><i
																class="fa fa-trash"></i></button>
													</td>
													<td>{{ prop.name }}</td>
													<td><input type="number" v-model="prop.style.width" min="0"
															   max="100" step="0.1"
															   class="form-control"/></td>
													<td>
														<select v-model="prop.style.fontWeight" class="form-control">
															<option value="normal">Normal</option>
															<option value="bold">Bold</option>
															<option value="bolder">Bolder</option>
															<option value="lighter">Lighter</option>
														</select>
													</td>
													<td><input type="number" v-model="prop.style.fontSize"
															   class="form-control"/></td>
													<td><input type="number" min="0" max="100" step="0.1"
															   v-model="prop.style.left" class="form-control"/></td>
													<td><input type="number" min="0" max="100" step="0.1"
															   v-model="prop.style.top" class="form-control"/></td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="form-group">
										<label>Preview</label>
									</div>
								</div>
								<div style="position: relative">
									<img :src="cert.image" style="width: 100%"/>
									<div style="background:rgba(0,0,0,0.3)" v-for="prop in certProperty"
										 :style="prop.style">{{ prop.name }}
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade show" id="tabs-nametag" role="tabpanel">
							<div class="row">
								<div class="col">Nametag Template</div>
							</div>
							<hr/>
							<div class="row">
								<div class="col-md-12">
									<p>
										*Recomended image dimension is (530 x 800) px or Aspect ratio Is 5.3 x 8
									</p>
									<div class="form-group row">
										<label class="col-md-3 col-form-label">Template For Events</label>
										<?php
										echo form_dropdown("event_nametag", $list, "", ['class' => 'form-control col-md-3', '@change' => 'changeNametagEvent', 'v-model' => 'selectedEventNametag', 'id' => 'sel_event_nametag']);
										?>
										<div class="col-md-6">
											<div class="custom-file">
												<input ref="nametagImage" type="file" @change="changeNametagImage"
													   class="custom-file-input" id="customFileN">
												<label class="custom-file-label" for="customFileN">{{ nametag.fileName
													}}</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-form-label">Parameter from Member</label>
										<?= form_dropdown("param", ['' => 'Select Parameter','qr'=>'QR Code','fullname' => 'Full Name', 'status_member' => 'Status Of Member'], "", ['class' => 'form-control col-md-3', 'v-model' => 'selectedParamNametag', 'id' => 'sel_param_nametag']); ?>
										<div class="col-md-6">
											<button type="button" @click="addPropertyNametag" class="btn btn-primary">Add
												Property
											</button>
											<button :disabled="savingNametag" type="button" @click="saveNametag"
													class="btn btn-primary">
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
														<button @click="deletePropNametag(index)"
																class="btn btn-sm btn-primary"><i
																class="fa fa-trash"></i></button>
													</td>
													<td>{{ prop.name }}</td>
													<td><input type="number" v-model="prop.style.width" min="0"
															   max="100" step="0.1"
															   class="form-control"/></td>
													<td>
														<select v-model="prop.style.fontWeight" class="form-control">
															<option value="normal">Normal</option>
															<option value="bold">Bold</option>
															<option value="bolder">Bolder</option>
															<option value="lighter">Lighter</option>
														</select>
													</td>
													<td><input type="number" v-model="prop.style.fontSize"
															   class="form-control"/></td>
													<td><input type="number" min="0" max="100" step="0.1"
															   v-model="prop.style.left" class="form-control"/></td>
													<td><input type="number" min="0" max="100" step="0.1"
															   v-model="prop.style.top" class="form-control"/></td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="form-group">
										<label>Preview</label>
									</div>
								</div>
								<div style="position: relative;border: 1px dotted;height: 800px;width: 530px;margin: auto">
									<img :src="nametag.image" style="width: 100%"/>
									<div style="background:rgba(0,0,0,0.3)" v-for="prop in nametagProperty" :style="prop.style">
										<span v-if="prop.name != 'qr'">{{ prop.name }}</span>
										<img v-else src="<?=base_url('themes/uploads/qrpreview.png');?>" :style="{height:prop.height,width:prop.height}"  />
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade show" id="tabs-notification" role="tabpanel">
							<div class="row">
								<div class="col">Notification Setting</div>
							</div>
							<hr/>
							<div class="row">
								<div class="col-3">
									<label>Email Used For Notification</label>
								</div>
								<div v-if="email_notif_binded" class="col-4">
									<input type="text" readonly v-model="email_notif" class="form-control"/>
								</div>
								<div class="col-4">
									<button v-if="email_notif_binded" @click="unbindEmail" class="btn btn-danger">
										Unbind
									</button>
									<a v-else href="<?= base_url("admin/setting/request_auth"); ?>"
									   class="btn btn-primary">Bind</a>
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-3">
									<label>Whatsapp Token (from wablas.com)</label>
								</div>
								<div class="col-6">
									<input type="text" v-model="wa_api_token" class="form-control"/>
								</div>
								<div class="col-3">
									<button @click="saveTokenWa" class="btn btn-primary">Save</button>
								</div>
							</div>
						</div>
						<div class="tab-pane fade show" id="tabs-manual_payment" role="tabpanel">
							<div class="row">
								<div class="col">Manual Payment</div>
							</div>
							<hr/>
							<div class="row">
								<div class="col-md-12">

									<div class="form-group">
										<label class="form-control-label">Email To Receive Notification</label>
										<input type="text" class="form-control" v-model="manualPayment.emailReceive"/>
									</div>
									<div class="form-group">
										<label class="form-control-label">Banks Info</label>
										<table class="table">
											<tr>
												<th>Bank Name</th>
												<th>Account Number</th>
												<th>Account Name Holder</th>
												<th>
													<button
														@click="manualPayment.banks.push({bank:'',no_rekening:'',holder:''});"
														type="btn" class="btn btn-primary"><i class="fa fa-plus"></i>
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
									<div class="form-group text-right">
										<button @click="saveManualPayment" :disabled="savingManual" type="button" class="btn btn-primary">
											<i :class="[savingManual ? 'fa fa-spin fa-spinner':'fa fa-disk']" ></i> Save
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
</div>
<div v-if="loading" class="modal fade show" id="modal-loading"
	 style="display: block; padding-right: 16px;background: rgba(0, 0, 0, 0.3)">
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
<script>
	var banks = <?=$manual;?>;
	var emailReceive = "<?=Settings_m::getSetting("email_receive");?>";
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
            body: {width: "100%"},
            property: []
        }
    }

	function defaultNametag() {
		return {
			image: "",
			base64Image: "",
			fileName: "Select Image as Template",
			body: {width: "100%"},
			property: []
		}
	}

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
            uploading: false,
            preview_certificate: "",
            logo_src: '<?= base_url('themes/uploads/logo.png'); ?>',
            form: {
                preface:<?=json_encode(Settings_m::getSetting('preface'));?>,
                site_title: '<?=Settings_m::getSetting('site_title');?>',
				text_payment_proof:'<?=Settings_m::getSetting('text_payment_proof');?>',
				format_id_paper:'<?=Settings_m::getSetting('format_id_paper');?>',
				ketua_panitia:'<?=Settings_m::getSetting('ketua_panitia');?>',
            },
            email_notif_binded:<?=$email_binded;?>,
            email_notif: "<?=Settings_m::getSetting(Gmail_api::EMAIL_ADMIN_SETTINGS);?>",
            wa_api_token: "<?=$wa_token;?>",
            cert: defaultCert(),
			nametag: defaultNametag(),
            manualPayment: {emailReceive:emailReceive,banks:banks},
			savingManual:false,
        },
        computed: {
            urlPreview() {
                return "<?=base_url('admin/setting/preview_cert');?>/" + this.selectedEvent;
            },
			urlPreviewNametag() {
				return "<?=base_url('admin/setting/preview_nametag');?>/" + this.selectedEventNametag;
			},
            certProperty() {
                var ret = [];
                $.each(this.cert.property, function (i, r) {
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
                        }
                    }
                    ret.push(temp);
                });
                return ret;
            },
			nametagProperty() {
				var ret = [];
				$.each(this.nametag.property, function (i, r) {
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
						"height":r.style.fontSize+"%"
					}
					ret.push(temp);
				});
				return ret;
			}
        },
        methods: {
            saveManualPayment(){
                this.savingManual = true;
                $.post("<?=base_url('admin/setting/save_manual');?>",this.manualPayment, function (res) {
                    if (res.status) {
                        Swal.fire("Success", "Setting Manual Payment Saved Successfully !", "success");
                    }
                }, 'JSON').always(function () {
                    app.savingManual = false;
                });
            },
            deleteProp(index) {
                this.$delete(this.cert.property, index)
            },
			deletePropNametag(index) {
				this.$delete(this.nametag.property, index)
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
				$.post("<?=base_url('admin/setting/get_nametag');?>", {id: this.selectedEventNametag}, function (res) {
					if (res.status) {
						app.nametag = res.data;
					} else {
						app.nametag = defaultNametag();
					}
				}, 'JSON').always(function () {
					app.loading = false;
				});
			},
            changeCertEvent() {
                this.loading = true;
                $.post("<?=base_url('admin/setting/get_cert');?>", {id: this.selectedEvent}, function (res) {
                    if (res.status) {
                        app.cert = res.data;
                    } else {
                        app.cert = defaultCert();
                    }
                }, 'JSON').always(function () {
                    app.loading = false;
                });
            },
            addPropertyCert() {
                this.cert.property.push({
                    "name": this.selectedParam,
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
                toBase64(app.$refs.certImage.files[0]).then(function (result) {
                    if (result)
                        app.cert.base64Image = result;
                    app.cert.event = app.selectedEvent;
                    app.savingCert = true;

                    $.post("<?=base_url("admin/setting/save_cert");?>", app.cert, null, 'JSON')
                        .done(function (res) {
                            if (res.status) {
                                Swal.fire("Success", "Template Certificate Saved Successfully !", "success");
                            }
                        }).fail(function (xhr) {
                        Swal.fire("Failed", "Failed to save data !", "error");
                    }).always(function () {
                        app.savingCert = false;
                    });
                });

            },
			saveNametag() {
				toBase64(app.$refs.nametagImage.files[0]).then(function (result) {
					if (result)
						app.nametag.base64Image = result;
					app.nametag.event = app.selectedEventNametag;
					app.savingNametag = true;

					$.post("<?=base_url("admin/setting/save_nametag");?>", app.nametag, null, 'JSON')
						.done(function (res) {
							if (res.status) {
								Swal.fire("Success", "Template Nametag Saved Successfully !", "success");
							}
						}).fail(function (xhr) {
						Swal.fire("Failed", "Failed to save data !", "error");
					}).always(function () {
						app.savingNametag = false;
					});
				});

			},
            unbindEmail() {
                var app = this;
                $.post("<?=base_url("admin/setting/unbind_email");?>", {}, null, 'JSON')
                    .done(function (res) {
                        if (res.status) {
                            app.email_notif_binded = false;
                            app.email_notif = null;
                        }
                    }).fail(function (xhr) {
                    Swal.fire("Failed", "Failed to load data !", "error");
                }).always(function () {
                    app.$refs.datagrid.loading = false;
                });
            },
            saveTokenWa() {
                var app = this;
                $.post("<?=base_url("admin/setting/save_token_wa");?>", {token: app.wa_api_token}, null, 'JSON')
                    .done(function (res) {
                        app.detailModel = res;
                        $("#modal-detail").modal("show");
                    }).fail(function (xhr) {
                    Swal.fire("Failed", "Failed to load data !", "error");
                }).always(function () {
                    app.$refs.datagrid.loading = false;
                });
            },
            onSave() {
                app.saving = true;
                console.log(app.form);
                $.ajax({
                    url: "<?=base_url('admin/setting/save');?>",
                    type: "POST",
                    data: {settings: app.form},
                }).done(function () {
                    Swal.fire("Success", "Setting has been saved !", "success");
                }).fail(function () {
                    Swal.fire("Failed", "Fail to save setting !", "error");
                }).always(function () {
                    app.saving = false;
                });
            },
            logoUpload() {
                var formData = new FormData();
                formData.append("file", this.$refs.file.files[0]);
                this.uploading = true;
                $.ajax({
                    url: "<?=base_url('admin/setting/upload_logo');?>",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                }).done(function () {
                    app.logo_src = "<?= base_url('themes/uploads/logo.png'); ?>?" + (new Date().getTime());
                }).fail(function (xhr) {
					console.log(xhr);
					if(xhr.responseJSON.error){
						Swal.fire("Failed", xhr.responseJSON.error, "error");
					}else {
						Swal.fire("Failed", "Fail to change logo !", "error");
					}
                }).always(function () {
                    app.uploading = false;
                });
            },
        }
    });
    $(document).ready(function () {
        $("#sel_event option:last").attr({"disabled": "true", "hidden": "true"});
        $("#sel_event_nametag option:last").attr({"disabled": "true", "hidden": "true"});
        $("#sel_param option:first").attr({"disabled": "true", "hidden": "true"});
    });
</script>
<?php $this->layout->end_script(); ?>
