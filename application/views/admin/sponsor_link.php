<?php
/**
 * @var $pricingDefault
 */
?>
<div class="header bg-info pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml"
	 xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>

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
								<a href="#!" v-on:click="formCancel" class="btn btn-sm btn-default"><i
										class="fa fa-times"></i> </a>
							</div>
						</div>
					</div>
					<form ref="form">
						<div class="card-body">
							<div class="form-group row">
								<label class="col-lg-3 control-label">Logo</label>
								<div class="col-lg-5">
									<img v-show="form.model.logo" style="max-height:200px;max-width:300px" class="img" :src="form.model.logo" />
									<div class="input-group mb-3">
										<div class="custom-file">
											<input type="file" ref="inputFile" accept="image/*" :class="{'is-invalid':form.validation.logo}" v-on:change="browseImage" class="custom-file-input" name="logo" id="inputGroupFile01">
											<label class="custom-file-label" for="inputGroupFile01">{{ form.model.filename }}</label>
										</div>
									</div>
									<div v-if="form.validation.logo" style="display:block" class="invalid-feedback">
										{{ form.validation.logo }}
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 control-label">Sponsor Name</label>
								<div class="col-lg-5">
									<input type="text" :class="{'is-invalid':form.validation.name}"
										   class="form-control" v-model="form.model.name"/>
									<div v-if="form.validation.name" class="invalid-feedback">
										{{ form.validation.name }}
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 control-label">Category</label>
								<div class="col-lg-5">
									<select v-model='form.model.category' class='form-control' :class="{'is-invalid':form.validation.category}" >
										<option v-for="cat in categoryList" :value="cat">{{ cat }}</option>
									</select>
									<input v-if="form.model.category == 'OTHER'" type="text" :class="{'is-invalid':form.validation.category}"
										   class="form-control" v-model="form.model.newCategory" placeholder="Please type new category" />
									<div v-if="form.validation.category" class="invalid-feedback">
										{{ form.validation.category }}
									</div>
								</div>
							</div>
						
							<div class="form-group row">
								<label class="col-lg-3 control-label">Link URL</label>
								<div class="col-lg-5">
									<input type="text" :class="{'is-invalid':form.validation.link}"
										   class="form-control" v-model="form.model.link"/>
									<div v-if="form.validation.link" class="invalid-feedback">
										{{ form.validation.link }}
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer text-right">
							<button v-on:click="save" v-bind:disabled="form.saving" type="button"
									class="btn btn-primary"><i
									:class="[form.saving? 'fa fa-spin fa-spinner':'fa fa-save']"></i> Save
							</button>
							<button type="button" v-on:click="formCancel" class="btn btn-default"><i
									class="fa fa-times"></i> Cancel
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
								<h3>Sponsor Link</h3>
							</div>
							<div class="col-6 text-right">
								<button @click="onAdd" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Add Sponsor</button>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<datagrid
							ref="datagrid"
							api-url="<?= base_url('admin/sponsor/grid'); ?>"
							:fields="[{name:'name',sortField:'name'}, {name:'category',sortField:'category'},{name:'link',sortField:'link'},{name:'click_count',sortField:'click_count','title':'Click Count'},{name:'id',sortField:'id','title':'Actions'}]">
							<template slot="id" slot-scope="props">
								<div class="table-button-container">
									<button @click="edit(props)" class="btn btn-info btn-sm">
										<span class="fa fa-edit"></span> Edit
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
</div>
<!-- Table -->

<?php $this->layout->begin_script(); ?>
<script>
    function model() {
        return {
          	logo:"",
			name:"",
			filename:"Please pick image",
			link:"",
			category:"",
			newCategory:"",
        }
    }
    var app = new Vue({
        el: '#app',
        data: {
            message: '',
			listRole:<?=json_encode(User_account_m::$listRole);?>,
			error: null,
			categoryList:<?= json_encode($categoryList);?>,
            form: {
			    validation:{},
                show: false,
                title: "Add Sponsor Link",
                saving: false,
                model: model()
            },
        },
        methods: {
			browseImage(event){
				if(event.target.files.length > 0){
					this.form.model.logo = URL.createObjectURL(event.target.files[0]);
					this.form.model.filename = event.target.files[0].name;
				}
			},
			deleteRow(prop){
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
                        var url = "<?=base_url('admin/sponsor/delete');?>";
                        $.post(url,{id:prop.row.id},null,"JSON")
                            .done(function (res) {
                                if(res.status)
	                                app.$refs.datagrid.refresh();
                                else
                                    Swal.fire("Failed","Failed to delete !","error");
                            }).fail(function (xhr) {
							var message =  xhr.getResponseHeader("Message");
							if(!message)
								message = 'Server fail to response !';
							Swal.fire('Fail', message, 'error');
                        });
                    }
                });
			},
			edit:function(props){
				props.row.newCategory = "";
				props.row.filename = props.row.logo;
				props.row.logo = "<?=$this->Sponsor_link_m->getLogoUrl("");?>"+props.row.logo;

				this.form.show = true;
				this.form.title = "Edit Sponsor Link";
                this.form.model = props.row;
                this.form.validation = {};
			},
            onAdd: function () {
				this.form.show = true;
				this.form.title = "Add Sponsor Link";
                this.form.model = model();
                this.form.validation = {};
            },
            formCancel: function () {
                this.form.show = false;
            },
            save: function () {
                this.error = null;
				this.form.saving = true;
				var data = new FormData();
				if(this.$refs.inputFile.files.length > 0)
					data.append("logo",this.$refs.inputFile.files[0]);
				if(this.form.model.id){
					data.append("id",this.form.model.id);
				}
				data.append("data[name]",this.form.model.name);
				data.append("data[link]",this.form.model.link);
				data.append("data[category]",this.form.model.category);
				data.append("new_category",this.form.model.newCategory);
                $.ajax({
					url:"<?=base_url('admin/sponsor/save');?>", 
					data:data,
					contentType:false,
					cache:false,
					method:"POST",
					processData:false,
					dataType:"JSON"}
				).done(function (res, text, xhr) {
					if(res.status) {
						app.message = res.message;
						app.form.show = false;
						if(app.form.model.newCategory != "" && app.form.model.category == "OTHER"){
							app.categoryList.unshift(app.form.model.newCategory);
						}
					}else{
						if(res.validation)
							app.form.validation = res.validation;
						else
							Swal.fire("Failed","Server failed to response !","error");
					}
				}).fail(function (xhr) {
					if(xhr.responseJSON && xhr.responseJSON.validation)
						app.form.validation = xhr.responseJSON.validation;
					else{
						var message =  xhr.getResponseHeader("Message");
						if(!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
					}
                }).always(function () {
                    app.form.saving = false;
                });
            }
        }
    });
</script>
<?php $this->layout->end_script(); ?>
