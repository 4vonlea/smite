<div class="header bg-primary pb-8 pt-5 pt-md-8"></div>
<div id="app" class="container-fluid mt--7">
    <div class="modal" data-backdrop="static" id="modal-fullpaper">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Upload your Full Paper/Presentation</h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form ref="formFullpaper" enctype="multipart/form-data">
                        <input type="hidden" name="id" :value="formFullpaper.id" />
                        <div class="form-group">
                            <label class="font-weight-bold form-control-label text-2 color-heading">Manuscript Title</label>
                            <label>{{ formFullpaper.title}}</label>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold form-control-label text-2 color-heading">Presentation on <strong>"{{formFullpaper.type_presence}}"</strong></label>
                        </div>
                        <hr />
                        <div v-if="formFullpaper.status == 2 && formFullpaper.status_fullpaper != 2 && !isAfter(paper.deadline.fullpaper_cutoff)" class="form-group">
                            <label class="font-weight-bold form-control-label text-2 color-heading">Fullpaper (doc,docx,ods)</label>
                            <input :class="{'is-invalid':error_fullpaper.fullpaper}" type="file" class="form-control" name="fullpaper" accept=".doc,.docx,.ods">
                            <div v-if="error_fullpaper.fullpaper" class="invalid-feedback">{{ error_fullpaper.fullpaper
										}}</div>
                        </div>
                        <div v-if="formFullpaper.status_fullpaper != 2 && isAfter(paper.deadline.fullpaper_cutoff)" class="alert alert-danger">
                            You are no longer allowed to upload fullpaper, because it has passed the time limit
                        </div>
                        <div v-if="formFullpaper.status_fullpaper == 2 && !isAfter(paper.deadline.presentation_cutoff)" class="form-group">
                            <label class="font-weight-bold form-control-label text-2 color-heading">{{ form.type_presence }} ({{ form.ext }})</label>
                            <input :class="{'is-invalid':error_fullpaper.presentation}" type="file" class="form-control" id="presentation_upload" name="presentation" :accept="form.ext">
                            <div v-if="error_fullpaper.presentation" class="invalid-feedback text-danger">{{
										error_fullpaper.presentation }}</div>
                        </div>
                        <hr />
                        <div v-if="formFullpaper.status_fullpaper == 2 && !isAfter(paper.deadline.presentation_cutoff)" class="form-group">
                            <label class="font-weight-bold form-control-label text-2 color-heading">Voice Recording (mp3)</label>
                            <input :class="{'is-invalid':error_fullpaper.voice}" type="file" class="form-control" id="voice_upload" name="voice" accept="mp3">
                            <div v-if="error_fullpaper.voice" class="invalid-feedback text-danger">{{
										error_fullpaper.voice }}</div>
                        </div>
                        <div v-if="isAfter(paper.deadline.presentation_cutoff)" class="alert alert-danger">
                            You are no longer allowed to upload a presentation file, because the time limit has passed
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button :disabled="uploadingFullpaper" type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button :disabled="uploadingFullpaper" type="button" @click="uploadFullpaper" class="btn btn-primary">
                        <i v-if="uploadingFullpaper" class="fa fa-spin fa-spinner"></i> Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="!loading && !fail">
        <div v-if="mode == 1" class="card">
            <div class="card-header h4">
                Form Paper
            </div>
            <div class="card-body">
            <form ref="form" enctype="multipart/form-data" class="form-border">
                <input type="hidden" name="id" v-model="form.id" />
                <div class="form-group row mb-2">
                    <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Participant*</label>
                    <div class="col-lg-9">
                        <vue-chosen v-model="form.member_id" :class="{'is-invalid':error_upload.member_id}" :options="paper.memberList" placeholder="Select Participant"></vue-chosen>
                        <div v-if="error_upload.member_id" class="invalid-feedback d-block">{{ error_upload.member_id }}</div>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Manuscript Section*</label>
                    <div class="col-lg-9">
                        <select :disabled="detail" class="form-control" v-model="form.category" name="category" :class="{'is-invalid':error_upload.category}">
                            <option v-for="(category,key) in paper.categoryPaper" :value="category">{{ category }}</option>
                        </select>
                        <div v-if="error_upload.category" class="invalid-feedback">{{ error_upload.category }}</div>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Manuscript Category*</label>
                    <div class="col-lg-9">
                        <select :disabled="detail" class="form-control" v-model="form.type" name="type" :class="{'is-invalid':error_upload.type}">
                            <option v-for="(type,key) in paper.treePaper[form.category]" :value="key">{{ key }}</option>
                        </select>
                        <div v-if="error_upload.type" class="invalid-feedback">{{ error_upload.type }}</div>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Manuscript Type*</label>
                    <div class="col-lg-9">
                        <select :disabled="detail" class="form-control" v-model="form.methods" name="methods" :class="{'is-invalid':error_upload.methods}">
                            <option v-for="(type,key) in form.category && form.type ? paper.treePaper[form.category][form.type] : []" :value="key">{{ key }}</option>
                        </select>
                        <input :disabled="form.methods != 'Other' || detail" placeholder="If the study type is other than the list above, please describe it here" :class="[{'is-invalid':error_upload.methods}, form.methods != 'Other' ? 'd-none':'']" class="form-control mt-1" name="type_study_other" type="text" v-model="form.type_study_other" value="">
                        <div v-if="error_upload.methods" class="invalid-feedback">{{ error_upload.methods }}</div>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Title*</label>
                    <div class="col-lg-9">
                        <input :disabled="detail" :class="{'is-invalid':error_upload.title}" class="form-control" name="title" type="text" v-model="form.title" value="">
                        <div v-if="error_upload.title" class="invalid-feedback">{{ error_upload.title }}</div>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Abstract*</label>
                    <div class="col-lg-9">
                        <textarea :disabled="detail" rows="5" @keydown='wordCount' @keyup='reduceWord' :class="{'is-invalid':error_upload.introduction}" v-model="form.introduction" class="form-control" name="introduction" style="background-color: transparent;">
										</textarea>
                        <div v-if="error_upload.title" class="invalid-feedback">{{ error_upload.introduction }}</div>
                        <small>{{ wordCountIntroduction }} Word (300 maximum)</small>
                    </div>
                </div>
                
                <div v-if="!detail" class="form-group row mb-2">
                    <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Upload
                        Abstract*<small>(.doc,.docx,.ods)</small></label>
                    <div class="col-lg-9">
                        <input :class="{'is-invalid':error_upload.file}" ref="file" class="form-control-file" accept=".doc,.docx,.ods" type="file" value="">
                        <div v-if="error_upload.file" class="invalid-feedback d-block">{{ error_upload.file }}</div>
                    </div>
                </div>
                <div v-if="detail" class="form-group row mb-2">
                    <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Abstract
                        Link</label>
                    <div class="col-lg-9">
                        <a :href="paperUrl(form.filename, form.id)">Click Here</a>
                    </div>
                </div>
                <div v-if="detail && form.status == 0 && form.feedback" class="form-group row mb-2">
                    <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">File Abstract
                        Feedback</label>
                    <div class="col-lg-9">
                        <a :href="feedbackUrl(form.feedback,form.id)">Click Here</a>
                    </div>
                </div>
                <div v-if="detail && form.message" class="form-group row mb-2">
                    <label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Abstract
                        Feedback</label>
                    <div class="col-lg-9">
                        <span>{{ form.message }}</span>
                    </div>
                </div>
                
                <div v-if="!detail" class="form-group">
                    <div class="form-check">
                        <input type="checkbox" v-model="form.check" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">

                            <span v-if="paper.declaration[form.methods]">
                                {{ paper.declaration[form.methods] }}
                            </span>
                            <span v-else>
                                I Understand
                            </span>
                        </label>
                    </div>
                </div>
                <div class="form-group text-right">
                    <a href="<?=base_url('admin/paper');?>" class="btn btn-primary">Back</a>
                    <button v-if="detail && form.status == 0" :disabled="uploading" class="btn btn-primary" @click="detail = false;">Edit</button>
                    <button v-if="!detail" :disabled="uploading" type="button" @click="uploadPaper" class="btn btn-primary">
                        <i v-if="uploading" class="fa fa-spin fa-spinner"></i> Submit
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<?php $this->layout->begin_head(); ?>
<link href="<?= base_url(); ?>themes/script/chosen/chosen.css" rel="stylesheet">
<style>
   .chosen-container-single .chosen-single {
        height: 38px;
        border-radius: 3px;
        border: 1px solid #CCCCCC;
    }

    .chosen-container-single .chosen-single span {
        padding-top: 4px;
    }

    .chosen-container-single .chosen-single div b {
        margin-top: 4px;
    }
</style>
<?php $this->layout->end_head(); ?>
<?php $this->layout->begin_script();?>
<script src="<?= base_url("themes/script/chosen/chosen.jquery.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/chosen/vue-chosen.js"); ?>"></script>
<script>
    new Vue({
        el: '#app',
        data: function() {
            return {
                mode: 1,
                detail: false,
                loading: false,
                fail: false,
                paper: {
                    deadline: {},
                    declaration: {},
                    memberList:[],
                },
                error_upload: {},
                uploading: false,
                form: {
                    co_author: [],
                    category: null,
                    type: null,
                    member_id:"",
                },
                formFullpaper: {},
                uploadingFullpaper: false,
                error_fullpaper: {},
            }
        },
        filters: {
            formatDate: function(val) {
                return moment(val).format("DD MMMM YYYY [At] HH:mm");
            }
        },
        created() {
            this.fetchData()
        },
        watch: {
            '$route': 'fetchData'
        },
        computed: {
            wordCountIntroduction() {
                return (this.form.introduction ? this.form.introduction.trim().split(" ").length : 0);
            }
        },
        methods: {
            isAfter(date) {
                return moment().isAfter(date);
            },
            reduceWord() {
                if (this.form.introduction && this.form.introduction.trim().split(" ").length > 300) {
                    let temp = this.form.introduction.split(" ");
                    temp.splice(300, temp.length - 300);
                    this.form.introduction = temp.join(" ");
                }
            },
            wordCount(evt) {
                if (evt.keyCode != 8 &&
                    evt.keyCode != 37 &&
                    evt.keyCode != 38 &&
                    evt.keyCode != 39 &&
                    evt.keyCode != 40 &&
                    this.form.introduction && this.form.introduction.split(" ").length > 300) {
                    evt.preventDefault();
                }
            },
            uploadFullpaper() {
                var page = this;
                var fd = new FormData(this.$refs.formFullpaper);
                fd.append('type_presence', this.form.type_presence);
                fd.append('ext', this.form.ext);
                page.uploadingFullpaper = true;
                $.ajax({
                    url: "<?=base_url("admin/paper/upload_fullpaper");?>",
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status) {
                            $("#modal-fullpaper").modal("hide");
                            Swal.fire('Success', "Fullpaper or Presentation/Poster File uploaded successfully !", 'success');
                            if (response.data.fullpaper) {
                                page.formFullpaper.fullpaper = response.data.fullpaper;
                                page.form.status_fullpaper = 1;
                            }
                            if (response.data.poster) {
                                page.formFullpaper.poster = response.data.poster;
                                page.form.status_presentasi = 1;
                            }
                            if (response.data.voice) {
                                page.formFullpaper.voice = response.data.voice;
                                page.form.voice = response.data.voice;
                            }
                            page.fetchData();
                        } else {
                            page.error_fullpaper = response.message;
                        }
                    },
                }).always(function() {
                    page.uploadingFullpaper = false;
                }).fail(function() {
                    Swal.fire('Fail', "Failed to process !", 'error');
                });
            },
            modalFullpaper(paper) {
                this.$refs.formFullpaper.reset();
                this.formFullpaper = paper;
                this.error_fullpaper = {};
                $("#modal-fullpaper").modal("show");
            },
            removeAuthor(i) {
                this.form.co_author.splice(i, 1);
            },
            addCoAuthor() {
                this.form.co_author.push({
                    'fullname': '',
                    'email': '',
                    'phone': '',
                    'affilition': ''
                });
            },
            fetchData() {
                var page = this;
                page.loading = true;
                $.post("<?=base_url("admin/paper/get_paper/$id");?>", function(res) {
                    page.paper = res;
                }, "JSON").fail(function() {
                    page.fail = true;
                }).always(function() {
                    page.loading = false;
                });
            },
            uploadPaper() {
                var page = this;
                if (this.form.check) {
                    var fd = new FormData(this.$refs.form);
                    fd.append('file', this.$refs.file.files[0]);
                    // fd.append('title', this.$refs.title.value);
                    fd.append("member_id",this.form.member_id);
                    page.uploading = true;
                    $.ajax({
                        url: "<?=base_url("admin/paper/form_paper");?>",
                        type: 'post',
                        data: fd,
                        contentType: false,
                        processData: false,
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.status) {
                                Swal.fire('Success', "Manuscript Success Submited!", 'success');
                                window.location = "<?=base_url('admin/paper');?>";
                            }
                        },
                    }).always(function() {
                        page.uploading = false;
                    }).fail(function() {
                        Swal.fire('Fail', "Failed to process !", 'error');
                    });
                } else {
                    Swal.fire('Info', "Please agree to manuscript term and condition above!", 'warning');
                }
            },
            // detailPaper(row) {
            //     this.mode = 1;
            //     this.form = row;
            //     this.form.category = row.category_paper.name;
            //     if (this.form.type_presence == 'Oral') {
            //         this.form.ext = '.ppt, .pptx, .pdf'
            //     } else if (this.form.type_presence == 'Viewed Poster' || this.form.type_presence == 'Moderated Poster') {
            //         this.form.ext = '.jpg, .png, .jpeg, .ppt'
            //     } else if (this.form.type_presence == 'Voice Recording') {
            //         this.form.ext = '.mp3, .m4a';
            //         console.log(this.form.ext);
            //     }

            //     $('#presentation_upload').attr('accept', this.form.ext);
            //     console.log(this.form.ext);

            //     this.detail = true;
            //     this.error_upload = {};
            // },
            formatDate(date) {
                return moment(date).format("DD MMM YYYY, [At] HH:mm:ss")
            },
        }
    });
</script>
<?php $this->layout->end_script();?>
