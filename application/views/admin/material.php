<?php

/**
 * @var array $statusList
 * @var array $univDl
 */
?>
<?php $this->layout->begin_head(); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.css">
<?php $this->layout->end_head(); ?>

<div class="header bg-info pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<!-- Card stats -->

		</div>
	</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--7">
	<!-- Table -->
	<div class="row">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h3>Material Uploads</h3>
						</div>
						<div class="col-6 text-right">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-status-list"><i class="fa fa-book"></i> Allowed Status To Upload
							</button>
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-upload-list"><i class="fa fa-book"></i> List Upload
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<datagrid @loaded_data="loadedGrid" ref="datagrid" api-url="<?= base_url('admin/material/grid'); ?>" :fields="[{name:'fullname',sortField:'fullname','title':'Speaker Name'}, {name:'title',sortField:'title'},{name:'id_mum',title:'Status'}]">
						<template slot="id_mum" slot-scope="props">
							<span v-if="props.row.filename" class="badge badge-success">Telah ditambahkan</span>
							<span v-else class="badge badge-danger">Belum ditambahkan</span>
							<a target="_blank" v-if="props.row.filename" :href="props.row.type == 1 ? props.row.filename : '<?= base_url('admin/material/file'); ?>/'+props.row.filename+'/'+props.row.title" class="btn btn-sm btn-primary">Lihat Bahan</a>
							<button @click="showModalUpload(props.row)" class="btn btn-primary btn-sm">Upload</button>

						</template>
					</datagrid>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modal-upload">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Upload Material</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<table class="table table-bordered">
				<tr>
					<th>Member Name</th>
					<td>{{ uploadMaterial.fullname }}</td>
				</tr>
				<tr>
					<th>File To Upload</th>
					<td>{{ uploadMaterial.title }}</td>
				</tr>
				<tr>
					<th>Type</th>
					<td>
						<div class="custom-control custom-radio custom-control-inline">
							<input id="radio_file" type="radio" v-model="uploadMaterial.type" value="2" class="custom-control-input">
							<label class="custom-control-label" for="radio_file">File</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input id="radio_link" type="radio" v-model="uploadMaterial.type" value="1" class="custom-control-input">
							<label class="custom-control-label" for="radio_link">Link</label>
						</div>
					</td>
				</tr>
				<tr>
					<th colspan="2">
						<div v-if="uploadMaterial.type == 1" class="input-group mb-3">
							<input type="text" ref="reflink" :value="uploadMaterial.filename" class="form-control" placeholder="Link (max 250 karakter)" aria-label="URL" aria-describedby="basic-addon2">
						</div>
						<div v-else>
							<div class="input-group mb-3">
								<div class="custom-file">
									<input ref="reffile" type="file" class="custom-file-input" @change="browseFile($event)">
									<label class="custom-file-label">{{ uploadMaterial.tempname ? uploadMaterial.tempname : 'Pilih File' }}</label>
								</div>
							</div>
							<small>*ekstensi file 'doc|docx|jpg|jpeg|png|bmp|ppt|pdf|mp4'</small>
						</div>
					</th>
				</tr>
			</table>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" @click="saveMaterial">Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="modal-status-list">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Status Allow To Upload Material</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<table class="table">
					<thead>
						<tr>
							<th>Status Name</th>
							<th>Allow To Upload</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(cat,index) in statusList">
							<td>
								{{ cat.kategory }}
							</td>
							<td>
								<input type="checkbox" v-model="selectedStatus" :value="cat.id" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>


<div class="modal" id="modal-upload-list">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Upload List</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="row mb-3">
					<div class="col-md-5">
						<label>Title</label>
						<input v-model="newList.title" type="text" class="form-control" placeholder="New List" />
					</div>
					<div class="col-md-5">
						<label>Deadline</label>
						<vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm" formatted="DD MMMM YYYY HH:mm" v-model="newList.deadline"></vue-ctk-date-time-picker>
					</div>
					<div class="col-md-2" style="padding-top:20px">
						<div class="btn-group">
							<button type="button" class="btn btn-primary" @click="addList">
								<i class="fa fa-save"></i>
							</button>
							<button type="button" class="btn btn-default" @click="newList = {}">
								<i class="fa fa-minus-square"></i>
							</button>
						</div>
					</div>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>File to Upload</th>
							<th>Deadline</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(cat,index) in uploadList">
							<td>
								{{ cat.title }}
							</td>
							<td>
								{{ cat.deadline | formatDate }}
							</td>
							<td>
								<button @click="removeList(index)" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
								<button @click="editList(index)" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></button>
							</td>
						</tr>
					</tbody>
				</table>

			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>

<?php $this->layout->begin_script(); ?>
<script src="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.umd.js" charset="utf-8"></script>

<script>
	var tempOld = [];
	var tempStatus = <?= json_encode($uploadList); ?>;

	function postStatus(cat) {
		return $.post('<?= base_url('admin/material/add_list'); ?>', {
			value: cat
		});
	}

	Vue.filter('formatDate', function(value) {
		if (value) {
			return moment(value).format('DD MMMM YYYY HH:mm')
		}
	});
	Vue.component('vue-ctk-date-time-picker', window['vue-ctk-date-time-picker']);
	var app = new Vue({
		el: '#app',
		data: {
			newList: {},
			uploadList: <?= json_encode($uploadList); ?>,
			statusList: <?= json_encode($statusList); ?>,
			selectedStatus: <?= $selectedStatus; ?>,
			verifyModel: {},
			pagination: {},
			uploadMaterial: {},
		},
		watch: {
			selectedStatus: function(val, old) {
				if (JSON.stringify(val) !== JSON.stringify(tempOld)) {
					$.post("<?= base_url('admin/material/change_selected'); ?>", {
						'selected_status': val
					}, function(res) {
						tempOld = val;
						console.log("Sukses");
					}, 'JSON').fail(function(xhr) {
						app.selectedStatus = old;
						Swal.fire('Fail', "Server Gagal Memproses", 'error');
					});
				}
			}
		},
		methods: {
			browseFile(event) {
				if (event.target.files.length > 0) {
					this.uploadMaterial.tempname = event.target.files[0].name;
				}
			},
			saveMaterial(evt) {
				var page = this;
				let valid = true;
				var formData = new FormData();
				if (this.uploadMaterial.type == 1) {
					let value = this.$refs['reflink'].value;
					if (value.length > 250) {
						valid = false;
						Swal.fire('Peringatan', "Panjang karakter maksimal 250", 'warning');
					}
					formData.append("filename", value);
				} else {
					let refFile = this.$refs['reffile'];
					if (refFile.files.length > 0) {
						formData.append("filename", refFile.files[0]);
					} else {
						valid = false;
						Swal.fire('Peringatan', "Pilih file baru sebelum melakukan upload", 'warning');
					}
				}
				formData.append("member_id", this.uploadMaterial.member_id);
				formData.append("ref_upload_id", this.uploadMaterial.ref_upload_id);
				formData.append("type", this.uploadMaterial.type);
				formData.append("id", this.uploadMaterial.id_mum);
				evt.target.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
				evt.target.setAttribute("disabled", true);
				if (valid) {
					$.ajax({
						url: `<?=base_url('admin/material/upload_material');?>`,
						type: 'POST',
						data: formData,
						processData: false, // tell jQuery not to process the data
						contentType: false, // tell jQuery not to set contentType
						success: function(res) {
							if (res.status) {
								$("#modal-upload").modal("hide");
								Swal.fire('Success', "Material saved successfully", 'success');
								page.$refs.datagrid.refresh();
							} else {
								Swal.fire('Peringatan', res.message, 'warning');
							}
						}
					}).fail(function() {
						Swal.fire('Gagal', "Server gagal memproses silakan coba lagi", 'error');
					}).always(function() {
						evt.target.innerHTML = "Simpan";
						evt.target.removeAttribute("disabled");
					});
				}
			},
			showModalUpload(row) {
				this.uploadMaterial = {
					id_mum: row.id_mum,
					title: row.title,
					fullname: row.fullname,
					type: (row.type ? row.type : 1),
					filename: row.filename,
					member_id: row.m_id,
					ref_upload_id: row.t_id,
					tempname:null,
				}
				$("#modal-upload").modal("show");
			},
			formatDate(date) {
				return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
			},
			addList: function() {
				postStatus(this.newList).done(function(res) {
					app.uploadList = res;
					app.newList = {};
					Swal.fire('Success', "Material list and deadline saved successfully", 'success');
				}).fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				});
			},
			editList: function(index) {
				this.newList = this.uploadList[index];
			},
			removeList: function(index) {
				var value = this.uploadList[index];
				$.post("<?= base_url('admin/material/remove_list'); ?>", {
					id: value.id
				}, function(res) {
					if (res.status)
						app.uploadList.splice(index, 1);
				}, 'JSON').fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				});
			},
			loadedGrid: function(data) {
				this.pagination = data;
			}
		},
		mounted() {
			<?php if (isset($_GET['q'])) : ?>
				this.$refs.datagrid.globalFilter = "<?= $_GET['q']; ?>";
				this.$refs.datagrid.doFilter();
			<?php endif; ?>
		}
	});
</script>
<?php $this->layout->end_script(); ?>