<?php
/**
 * @var $wa_token
 * @var $email_binded
 * @var $event
 */
?>
<div class="header bg-info pb-8 pt-5 pt-md-7"></div>
<div class="container-fluid mt--7">
	<div class="row mb-2">
		<div class="col">
			<div class="nav-wrapper">
				<ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab"
						   href="#tabs-general" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="ni ni-world mr-2"></i>General</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-1-tab" data-toggle="tab"
						   href="#tabs-certificate" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="ni ni-book-bookmark mr-2"></i>Certificate</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-1-tab" data-toggle="tab"
						   href="#tabs-notification" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="ni ni-book-bookmark mr-2"></i>Notification</a>
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
									<label>Site Logo</label>
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
												<tr v-for="prop in cert.property">
													<td></td>
													<td>{{ prop.name }}</td>
													<td><input type="number" v-model="prop.style.width" min="0" max="100" step="0.1"
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

        var app = new Vue({
            'el': '#app',
            data: {
                loading: false,
                selectedEvent: "",
                selectedParam: "",
                saving: false,
                savingCert: false,
                uploading: false,
                preview_certificate: "",
                logo_src: '<?= base_url('themes/uploads/logo.png'); ?>',
                form: {
                    preface:<?=json_encode(Settings_m::getSetting('preface'));?>,
                    site_title: '<?=Settings_m::getSetting('site_title');?>',
                },
                email_notif_binded:<?=$email_binded;?>,
                email_notif: "<?=Settings_m::getSetting(Gmail_api::EMAIL_ADMIN_SETTINGS);?>",
                wa_api_token: "<?=$wa_token;?>",
                cert: defaultCert()
            },
            computed: {
                urlPreview() {
                    return "<?=base_url('admin/setting/preview_cert');?>/" + this.selectedEvent;
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
                                "fontSize": r.style.fontSize + "px"
                            }
                        }
                        ret.push(temp);
                    });
                    console.log(ret);
                    return ret;
                }
            },
            methods: {
                changeCertImage(e) {
                    var file = e.target.files[0];
                    this.cert.fileName = file.name;
                    this.cert.image = URL.createObjectURL(file);
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
                    }).fail(function () {
                        Swal.fire("Failed", "Fail to change logo !", "error");
                    }).always(function () {
                        app.uploading = false;
                    });
                },
            }
        });
        $(document).ready(function () {
            $("#sel_event option:last").attr({"disabled": "true", "hidden": "true"});
            $("#sel_param option:first").attr({"disabled": "true", "hidden": "true"});
        });
	</script>
	<?php $this->layout->end_script(); ?>
