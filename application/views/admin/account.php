<?php
/**
 * @var $pricingDefault
 */
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml"
	 xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>

<div class="container-fluid mt--7">
	<div v-if="message" class="row">
		<div class="col-md-12">
			<div class="alert alert-success text-center alert-dismissible fade show" role="alert">
				<strong>{{ message }}</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
								<label class="col-lg-3 control-label">Fullname</label>
								<div class="col-lg-5">
									<input type="text" :class="{'is-invalid':form.validation.name}"
										   class="form-control" v-model="form.model.name"/>
									<div v-if="form.validation.name" class="invalid-feedback">
										{{ form.validation.name }}
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 control-label">Username</label>
								<div class="col-lg-5">
									<input type="text" :class="{'is-invalid':form.validation.username}"
										   class="form-control" v-model="form.model.username"/>
									<div v-if="form.validation.username" class="invalid-feedback">
										{{ form.validation.username }}
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 control-label">Password</label>
								<div class="col-lg-5">
									<input type="password" :class="{'is-invalid':form.validation.password}"
										   class="form-control" v-model="form.model.password"/>
									<div v-if="form.validation.password" class="invalid-feedback">
										{{ form.validation.password }}
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 control-label">Confirm Password</label>
								<div class="col-lg-5">
									<input type="password" :class="{'is-invalid':form.validation.confirm_password}"
										   class="form-control" v-model="form.model.confirm_password"/>
									<div v-if="form.validation.confirm_password" class="invalid-feedback">
										{{ form.validation.confirm_password }}
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 control-label">Role</label>
								<div class="col-lg-5">
									<select name="role" v-model="form.model.role" class="form-control"  :class="{'is-invalid':form.validation.role}">
										<option disabled value="">Select Role</option>
										<option v-for="(name,index) in listRole" :value="index">{{ name }}</option>
									</select>
									<div v-if="form.validation.role" class="invalid-feedback">
										{{ form.validation.role }}
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
								<h3>User Account</h3>
							</div>
							<div class="col-6 text-right">
							<div class="dropdown">
								<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									{{ currentRole }}
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="#" @click="filterRole(-1,'All Role')">All Role</a>
									<a class="dropdown-item" v-for="(val,ind) in listRole" @click="filterRole(ind,val)"  href="#">{{ val }}</a>

								</div>
							</div>
								<button @click="onAdd" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</button>
								<a href="<?=base_url('admin/account/access');?>" class="btn btn-primary"><i class="fa fa-edit"></i> Manage Access</a>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<datagrid
							ref="datagrid"
							api-url="<?= base_url('admin/account/grid'); ?>"
							:fields="[{name:'username',sortField:'username'},{name:'fullname',sortField:'fullname',title:'Fullname'}, {name:'role',sortField:'role','title':'Role'},{name:'username_',sortField:'username_','title':'Actions'}]">
							<template slot="role" slot-scope="prop">
								{{ listRole[prop.row.role] }}
							</template>
							<template slot="username_" slot-scope="props">
								<div class="table-button-container">
									<button @click="resetPass(props)" class="btn btn-warning btn-sm">
										<span class="fa fa-pen"></span> Reset Password
									</button>
									<button v-if="props.row.role != 0 && props.row.role != <?=User_account_m::ROLE_SUPERADMIN;?>" @click="deleteRow(props)" class="btn btn-danger btn-sm">
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
<script src="<?= base_url("themes/script/v-money.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script>
    function model() {
        return {
          	username:"",
          	name:"",
			password:"",
			role:"",
        }
    }
    var app = new Vue({
        el: '#app',
        data: {
            message: '',
			listRole:<?=json_encode(User_account_m::$listRole);?>,
            error: null,
            form: {
			    validation:{},
                show: false,
                title: "Add User",
                saving: false,
                model: model()
            },
			currentRole:"All Role",
        },
        methods: {
			filterRole(id,label){
				if(id >= 0){
					app.$refs.datagrid.additionalQuery = {'role':id};
				}else{
					app.$refs.datagrid.additionalQuery = {};
				}
				this.currentRole = label;
				app.$refs.datagrid.doFilter();

			},
            resetPass(prop){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Reset Password !'
                }).then((result) => {
                    if (result.value) {
                        var url = "<?=base_url('admin/account/reset');?>";
                        $.post(url,{id:prop.row.username},null,"JSON")
                            .done(function (res) {
                                if(res.status)
                                    Swal.fire("Info",res.msg,"info");
                                else
                                    Swal.fire("Failed","Failed to reset !","error");
                            }).fail(function (xhr) {
							var message =  xhr.getResponseHeader("Message");
							if(!message)
								message = 'Server fail to response !';
							Swal.fire('Fail', message, 'error');
                        });
                    }
                });
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
                        var url = "<?=base_url('admin/account/delete');?>";
                        $.post(url,{id:prop.row.username},null,"JSON")
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
            onAdd: function () {
                this.form.show = true;
                this.form.model = model();
                this.form.validation = {};
            },
            formCancel: function () {
                this.form.show = false;
            },
            save: function () {
                this.error = null;
                this.form.saving = true;
                $.post("<?=base_url('admin/account/save');?>", this.form.model, null, 'JSON')
                    .done(function (res, text, xhr) {
                        if(res.status) {
                            app.message = res.msg;
                        }else{
                            if(res.validation)
	                            app.form.validation = res.validation;
                            else
                                Swal.fire("Failed","Server failed to response !","error");
						}
                        app.form.show = false;
                    }).fail(function (xhr) {
                        if(xhr.responseJSON)
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
