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
                                    <button @click="onSave" :disabled="saving" type="button" class="btn btn-primary float-right"><i :class="[saving ?'fa fa-spin fa-spinner':'fa fa-save']"></i> Save</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Site Logo</label>
                                    <div class="card">
                                        <img class="card-img-top" :src="logo_src" alt="Card image cap"/>
                                        <div class="card-body text-center">
                                            <input id="inputFile" type="file" class="d-none" ref="file" v-on:change="logoUpload" />
                                            <button :disabled="uploading" @click="$refs.file.click();" class="btn btn-primary">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->layout->begin_script();?>
<script>
    var app = new Vue({
        'el':'#app',
        data:{
            saving:false,
            uploading: false,
            logo_src:'<?= base_url('themes/uploads/logo.png'); ?>',
            form:{
                preface:<?=json_encode(Settings_m::getSetting('preface'));?>,
                site_title:'<?=Settings_m::getSetting('site_title');?>',
            }
        },
        methods:{
            onSave(){
                app.saving = true;
                console.log(app.form);
                $.ajax({
                    url: "<?=base_url('admin/setting/save');?>",
                    type: "POST",
                    data:  {settings:app.form},
                }).done(function () {
                    Swal.fire("Success","Setting has been saved !","success");
                }).fail(function () {
                    Swal.fire("Failed","Fail to save setting !","error");
                }).always(function () {
                    app.saving = false;
                });
            },
            logoUpload(){
                var formData = new FormData();
                formData.append("file",this.$refs.file.files[0]);
                this.uploading = true;
                $.ajax({
                    url: "<?=base_url('admin/setting/upload_logo');?>",
                    type: "POST",
                    data:  formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                }).done(function () {
                    app.logo_src = "<?= base_url('themes/uploads/logo.png'); ?>?"+(new Date().getTime());
                }).fail(function () {
                    Swal.fire("Failed","Fail to change logo !","error");
                }).always(function () {
                    app.uploading = false;
                });
            },
        }
    });
</script>
<?php $this->layout->end_script();?>
