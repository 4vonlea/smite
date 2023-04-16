<?php

/**
 * @var $pricingDefault
 */
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>

<div class="container-fluid mt--7">
	<div v-if="message" class="row">
		<div class="col-md-12">
			<div class="alert alert-success text-center alert-dismissible fade show" role="alert">
				<strong>{{ message }}</strong>
				<button type="button" class="close" @click="message = ''">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		</div>
	</div>
	<transition name="fade" mode="out-in">
		<div v-if="form.show" key="form" class="row">
			<div class="col-xl-12">
				<div class="card bg-secondary shadow">
					<div class="card-header bg-white border-0">
						<div class="row align-items-center">
							<div class="col-8">
								<h3 class="mb-0">{{ form.title }}</h3>
							</div>
							<div class="col-4 text-right">
								<a href="#!" v-on:click="formCancel" class="btn btn-sm btn-default"><i class="fa fa-times"></i> </a>
							</div>
						</div>
					</div>
					<form ref="form">
						<div class="card-body row">
							<div class="col-md-6 col-sm-12">
								<div class="form-group row">
									<label class="col-lg-3 control-label">Type</label>
									<div class="col-lg-9">
										<select v-model='form.model.type' class='form-control' :class="{'is-invalid':form.validation.type}">
											<option disabled value="">Select Type</option>
											<option v-for="(v,k) in listType" :value="k">{{ v }}</option>
										</select>
										<div v-if="form.validation.type" class="invalid-feedback">
											{{ form.validation.type }}
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 control-label">Video/Image</label>
									<div class="col-lg-9">
										<a v-if="form.model.filename" class="badge badge-info mb-1" target="_blank" :href="'<?= base_url('themes/uploads/video'); ?>/'+form.model.filename">Previous File Click Here</a>
										<div class="input-group mb-3">
											<div class="custom-file">
												<input type="file" ref="inputFile" accept="image/*,video/*" :class="{'is-invalid':form.validation.logo}" v-on:change="browseImage" class="custom-file-input" name="logo" id="inputGroupFile01">
												<label class="custom-file-label" for="inputGroupFile01">{{ form.model.filenametemp }}</label>
											</div>
										</div>
										<div v-if="form.validation.filename" style="display:block" class="invalid-feedback">
											{{ form.validation.filename }}
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 control-label">Title</label>
									<div class="col-lg-9">
										<input type="text" :class="{'is-invalid':form.validation.title}" class="form-control" v-model="form.model.title" />
										<div v-if="form.validation.title" class="invalid-feedback">
											{{ form.validation.title }}
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 control-label">Contestant</label>
									<div class="col-lg-9">
										<input type="text" :class="{'is-invalid':form.validation.uploader}" class="form-control" v-model="form.model.uploader" />
										<div v-if="form.validation.uploader" class="invalid-feedback">
											{{ form.validation.uploader }}
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 control-label">Description</label>
									<div class="col-lg-9">
										<textarea :class="{'is-invalid':form.validation.description}" class="form-control" v-model="form.model.description"></textarea>
										<div v-if="form.validation.description" class="invalid-feedback">
											{{ form.validation.description }}
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 text-center">
								<label>Preview Image/Thumbnail Video</label>
								<img :src="previewImage" class="img img-responsive img-thumbnail" />
							</div>

							<!-- <div class="form-group row">
								<label class="col-lg-12 control-label">File</label>
								<div class="col-lg-12">
									<vue-upload-image url=""></vue-upload-image>
								</div>
							</div> -->
						</div>
						<div class="card-footer text-right">
							<button v-on:click="save" v-bind:disabled="form.saving" type="button" class="btn btn-primary"><i :class="[form.saving? 'fa fa-spin fa-spinner':'fa fa-save']"></i> Save
							</button>
							<button type="button" v-on:click="formCancel" class="btn btn-default"><i class="fa fa-times"></i> Cancel
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div v-else="!form.show" key="table" class="row">
			<div class="col-xl-12">
				<div class="card shadow">
					<div class="card-header">
						<div class="row">
							<div class="col-6">
								<h3>Upload Video</h3>
							</div>
							<div class="col-6 text-right">
								<a href="<?= base_url('admin/upload_video/download_report'); ?>" class="btn btn-primary">Download as Excel</a>
								<button @click="onAdd" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Add Video/Image</button>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<datagrid ref="datagrid" api-url="<?= base_url('admin/upload_video/grid'); ?>" :fields="[{name:'title',sortField:'title'},{name:'type',sortField:'type'}, {name:'uploader',sortField:'uploader','title':'Contestant'},{name:'like_count',sortField:'like_count',title:'Like Count'},{name:'comment',sortField:'comment',title:'Comment Count'},{name:'id',sortField:'id','title':'Actions'}]">
							<template slot="type" slot-scope="props">
								{{ listType[props.row.type]}}
							</template>
							<template slot="id" slot-scope="props">
								<div class="table-button-container">
									<button @click="edit(props)" class="btn btn-info btn-sm">
										<span class="fa fa-edit"></span> Edit
									</button>
									<button @click="onDetail(props,$event)" class="btn btn-info btn-sm">
										<span class="fa fa-search"></span> Detail
									</button>
									<button @click="deleteRow(props)" class="btn btn-warning btn-sm">
										<span class="fa fa-trash"></span> Delete
									</button>
								</div>
							</template>
						</datagrid>
					</div>
				</div>
			</div>
		</div>
	</transition>
	<div class="modal" id="modal-detail" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content table-responsive">
				<div class="modal-header">
					<h5 class="modal-title">Detail</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<table class="table table-border">
					<tr>
						<th>Title</th>
						<td>
							{{ detail.title }}
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<video v-if="detail.type == 1" width="100%" style="width: 100%;" :src="'<?= base_url('themes/uploads/video'); ?>/'+detail.filename" controls>
							</video>
							<img v-if="detail.type == 2" class="img-fluid" :src="'<?= base_url('themes/uploads/video'); ?>/'+detail.filename" />
						</td>
					</tr>
					<tr>
						<th>
							Like
						</th>
						<td>
							{{ detail.likeCount }}
						</td>
					</tr>
					<tr>
						<td colspan="2" style="white-space: pre-line;">
							<p class="text-center">Comments</p>
							<div v-for="com in detail.comments" class="list-group">
								<div class="list-group-item list-group-item-action flex-column align-items-start">
									<div class="d-flex w-100 justify-content-between">
										<h4 class="mb-1">{{ com.username }} :</h5>
											<small>{{ com.created_at | formatDate }}</small>
									</div>
									<p class="mb-1">{{ com.comment }}</p>
								</div>
							</div>
						</td>
					</tr>
				</table>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Table -->

<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("components/vue-upload-image.js"); ?>"></script>

<script>
	function model() {
		return {
			filename: "",
			uploader: "",
			description: "",
			type: "",
			title: "",
			filenametemp: "Select File",
		}
	};

	var app = new Vue({
		el: '#app',
		data: {
			message: '',
			listType: <?= json_encode(Upload_video_m::$types); ?>,
			error: null,
			form: {
				validation: {},
				show: false,
				title: "Add Video/Image",
				saving: false,
				model: model()
			},
			previewImage: "",
			detail: {},
		},
		filters: {
			formatDate: function(val) {
				return moment(val).format("DD MMMM YYYY [At] HH:mm");
			}
		},
		methods: {
			generateVideoThumbnail(file) {
				return new Promise((resolve) => {
					const canvas = document.createElement("canvas");
					const video = document.createElement("video");

					// this is important
					video.autoplay = true;
					video.muted = true;
					video.src = URL.createObjectURL(file);

					video.onloadeddata = () => {
						let ctx = canvas.getContext("2d");

						canvas.width = video.videoWidth;
						canvas.height = video.videoHeight;

						ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
						video.pause();
						return resolve(canvas);
					};
				});
			},
			browseImage(event) {
				let target = event.target;
				if (target.files.length > 0) {
					app.previewImage = "";
					this.form.model.filenametemp = target.files[0].name;
					if (this.form.model.type == '<?= Upload_video_m::TYPE_VIDEO; ?>') {
						this.generateVideoThumbnail(target.files[0]).then((canvas) => {
							app.previewImage = canvas.toDataURL("image/png");
						})
					} else {
						app.previewImage = URL.createObjectURL(target.files[0]);
					}
				}
			},
			deleteRow(prop) {
				Swal.fire({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						var url = "<?= base_url('admin/upload_video/delete'); ?>";
						$.post(url, {
								id: prop.row.id
							}, null, "JSON")
							.done(function(res) {
								if (res.status)
									app.$refs.datagrid.refresh();
								else
									Swal.fire("Failed", "Failed to delete !", "error");
							}).fail(function(xhr) {
								var message = xhr.getResponseHeader("Message");
								if (!message)
									message = 'Server fail to response !';
								Swal.fire('Fail', message, 'error');
							});
					}
				});
			},
			edit: function(props) {
				this.form.show = true;
				this.form.title = "Edit Video/Image";
				props.row.filenametemp = "Select File";
				Vue.set(this.form, 'model', Object.assign({}, props.row))
				console.log(this.form.model);
				this.form.validation = {};
			},
			onAdd: function() {
				this.form.show = true;
				this.form.title = "Add Video/Image";
				this.form.model = model();
				this.form.validation = {};
			},
			formCancel: function() {
				this.form.show = false;
			},
			onDetail: function(props, event) {
				event.target.innerHTML = '<i class="fa fa-spin fa-spinner"></i>';
				event.target.setAttribute("disabled", "disabled");
				$.get(`<?= base_url('admin/upload_video/detail'); ?>/${props.row.id}`, function(res) {
					app.detail = res;
					$("#modal-detail").modal("show");
				}, 'JSON').fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					event.target.innerHTML = '<span class="fa fa-search"></span> Detail';
					event.target.removeAttribute("disabled");
				});
			},
			save: function() {
				this.error = null;
				this.form.saving = true;
				var data = new FormData();
				if (this.$refs.inputFile.files.length > 0)
					data.append("file", this.$refs.inputFile.files[0]);
				if (this.form.model.id) {
					data.append("id", this.form.model.id);
				}
				if (this.form.model.type == "<?= Upload_video_m::TYPE_VIDEO; ?>" && this.previewImage) {
					data.append("video_thumb", this.previewImage);
				}
				data.append("data[title]", this.form.model.title);
				data.append("data[uploader]", this.form.model.uploader);
				data.append("data[type]", this.form.model.type);
				data.append("data[description]", this.form.model.description);
				$.ajax({
					url: "<?= base_url('admin/upload_video/save'); ?>",
					data: data,
					contentType: false,
					cache: false,
					method: "POST",
					processData: false,
					dataType: "JSON"
				}).done(function(res, text, xhr) {
					if (res.status) {
						app.message = res.message;
						app.form.show = false;
					} else {
						if (res.validation)
							app.form.validation = res.validation;
						else
							Swal.fire("Failed", "Server failed to response !", "error");
					}
				}).fail(function(xhr) {
					if (xhr.responseJSON && xhr.responseJSON.validation)
						app.form.validation = xhr.responseJSON.validation;
					else {
						var message = xhr.getResponseHeader("Message");
						if (!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}
				}).always(function() {
					app.form.saving = false;
				});
			}
		}
	});
</script>
<?php $this->layout->end_script(); ?>