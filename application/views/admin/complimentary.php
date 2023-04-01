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
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 control-label">Program Name</label>
                                <div class="col-lg-5">
                                    <input type="text" :class="{'is-invalid':form.validation.name}" class="form-control" v-model="form.model.name" />
                                    <div v-if="form.validation.name" class="invalid-feedback">
                                        {{ form.validation.name }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label">Held On</label>
                                <div class="col-lg-5">
                                    <vue-ctk-date-time-picker only-date :no-label="true" format="YYYY-MM-DD" formatted="DD MMMM YYYY" v-model="form.model.held_on"></vue-ctk-date-time-picker>
                                    <div v-if="form.validation.held_on" class="invalid-feedback">
                                        {{ form.validation.held_on }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label">Description</label>
                                <div class="col-lg-5">
                                    <textarea v-model="form.model.description" rows="6" class="form-control form-control-alternative"></textarea>
                                    <div v-if="form.validation.description" class="invalid-feedback">
                                        {{ form.validation.description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <v-button v-on:click="save" type="button" class="btn btn-primary">Save</v-button>
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
                                <h3>Complimentary Program</h3>
                            </div>
                            <div class="col-6 text-right">
                                <a href="<?= base_url('admin/complimentary/download'); ?>" class="btn btn-primary"><i class="fa fa-file"></i> Download</a>
                                <button @click="onAdd" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Add Program</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <datagrid ref="datagrid" api-url="<?= base_url('admin/complimentary/grid'); ?>" :fields="[{name:'name',sortField:'name'}, {name:'held_on',sortField:'held_on'},{name:'description',sortField:'description'},{name:'id',sortField:'id','title':'Actions'}]">
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
<?php $this->layout->begin_head(); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.css">
<?php $this->layout->end_head(); ?>

<?php $this->layout->begin_script(); ?>
<script src="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.umd.js" charset="utf-8"></script>
<script src="<?= base_url("themes/script/v-button.js"); ?>"></script>

<script>
    function model() {
        return {
            name: "",
            held_on: "",
            description: "",
        }
    }
    Vue.component('vue-ctk-date-time-picker', window['vue-ctk-date-time-picker']);

    var app = new Vue({
        el: '#app',
        data: {
            message: '',
            error: null,
            form: {
                validation: {},
                show: false,
                title: "Add Program",
                saving: false,
                model: model()
            },
        },
        methods: {
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
                        var url = "<?= base_url('admin/complimentary/delete'); ?>";
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
                this.form.title = "Edit Program";
                this.form.model = props.row;
                this.form.validation = {};
            },
            onAdd: function() {
                this.form.show = true;
                this.form.title = "New Program";
                this.form.model = model();
                this.form.validation = {};
            },
            formCancel: function() {
                this.form.show = false;
            },
            save: function(self) {
                self.toggleLoading();
                $.post("<?= base_url('admin/complimentary/save'); ?>", this.form.model).done(function(res, text, xhr) {
                    if (res.status) {
                        app.message = res.message;
                        app.form.show = false;
                        Swal.fire("Success", "Program has been saved !", "success");

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
                    self.toggleLoading();

                });
            }
        }
    });
</script>
<?php $this->layout->end_script(); ?>