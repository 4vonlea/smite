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
							<h3>News</h3>
						</div>
						<div class="col-9 text-right">
							<button class="btn btn-primary" @click="addNews"><i
									class="fa fa-plus"></i>
								Add News
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<datagrid
						ref="datagrid"
						api-url="<?= base_url('admin/news/grid'); ?>"
						:fields="[{name:'title',title:'Title',sortField:'title'},{name:'is_show',sortField:'is_show','title':'Is Show On'},{name:'author',sorField:'author',title:'Writer'},{name:'id',title:'Action'}]">
						<template slot="is_show" slot-scope="props">
							<span>{{ props.row.is_show == 1 ? 'Yes':'No'}}</span>
						</template>
						<template slot="id" slot-scope="props">
							<button class="btn btn-primary btn-sm" @click="editNews(props.row)">
								<span class="fa fa-edit"></span> Edit
							</button>
							<button class="btn btn-danger btn-sm" @click="deleteNews(props.row,$event)">
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
						<div class="col-1 text-center">
							<label class="form-control-label" for="example3cols1Input">Title</label>
						</div>
						<div class="col-11">
							<input v-model="form.title" type="text" class="form-control" placeholder="Type News Title Here">
						</div>
					</div>
				</div>
				<!-- Card body -->
				<div class="card-body">
					<!-- Form groups used in grid -->
					<div class="row">
						<div class="col-md-10">
							<textarea id="content_area" v-model="form.content"  class="form-control"></textarea>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Is Show On ?</label>
								<select class="form-control mb-2" v-model="form.is_show">
									<option value="1">Show</option>
									<option value="0">Hide</option>
								</select>
								<hr/>
								<button @click="save($event)" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Save</button>
								<button @click="formMode=0"  class="btn btn-default btn-block"><i class="fa fa-reply"></i> Back</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<script src="<?=base_url('themes/script/tinymce/tinymce.min.js');?>"></script>
<script>
	tinymce.init({
		selector: '#content_area',
		height:400,
		plugins: [
			"code advlist anchor autolink fullscreen help image imagetools",
			"lists link media noneditable preview pagebreak",
			"searchreplace table template visualblocks wordcount responsivefilemanager filemanager"
		],
		toolbar:
			"code undo redo | bold italic | forecolor backcolor  | formatselect | alignleft aligncenter alignright alignjustify | bullist numlist | link image responsivefilemanager | pagebreak",
		image_advtab: true,
		relative_urls:false,
		external_filemanager_path: "<?=base_url('filemanager');?>/",
		filemanager_title: "File Manager",
		filemanager_access_key:"0082577b00bfd2651d8d3cbd8974e6f3"
	});
	var app = new Vue({
		el: '#app',
		data: {
			formMode: 0,
			form:{},
		},
		methods: {
			addNews(){
				this.formMode = 1;
				this.form = {is_show:1};
				tinymce.get("content_area").setContent("");
			},
			editNews(row) {
				this.form = row;
				this.formMode = 1;
				tinymce.get("content_area").setContent(this.form.content);
			},
			save(evt){
				var btn = evt.currentTarget;
				btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
				btn.setAttribute("disabled", true);
				this.form.content = tinymce.get("content_area").getContent();
				$.post("<?=base_url("admin/news/save");?>", this.form, function (res) {
					if (res.status) {
						app.form = res.data;
						Swal.fire("Success", "News saved successfully", "success");
						app.$refs.datagrid.refresh();
					} else
						Swal.fire("Failed", res.message, "error");
				}, "JSON").fail(function (xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function () {
					btn.innerHTML = '<i class="fa fa-save"></i> Save';
					btn.removeAttribute("disabled");
				});
			},
			deleteNews(row, evt) {
				var btn = evt.currentTarget;
				Swal.fire({
					title: "Are you sure ?",
					text: `You will delete "${row.t_name}" From Committee`,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
						btn.setAttribute("disabled", true);
						$.post("<?=base_url("admin/news/delete");?>", {id: row.id}, function (res) {
							if (res.status) {
								Swal.fire("Success", "News deleted successfully", "success");
								app.$refs.datagrid.refresh();
							} else
								Swal.fire("Failed", res.message, "error");
						}, "JSON").fail(function (xhr) {
							var message = xhr.getResponseHeader("Message");
							if (!message)
								message = 'Server fail to response !';
							Swal.fire('Fail', message, 'error');
						}).always(function () {
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
