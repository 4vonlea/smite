export default Vue.component("PagePaper", {
    template: `
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div v-if="!loading && !fail">
                <div class="overflow-hidden mb-1">
                    <h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Submit Paper</strong></h2>
                </div>
                <div class="overflow-hidden mb-4 pb-3">
                    <p class="mb-0">Wanna participate on paper, please upload your fullpaper.</p>
                </div>
                <div v-if="paper.status == 0">
                    <div v-if="paper.message" class="alert alert-info">
                        <h4>Your paper has been reviewed</h4>
                        <p>{{ paper.message }}, Please revise and reupload</p>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Title</label>
                        <div class="col-lg-9">
                            <input  :class="{'is-invalid':error_upload.title}" ref="title" class="form-control" name="title"  type="text" value="">
                            <div v-if="error_upload.title" class="invalid-feedback">{{ error_upload.title }}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Upload Paper *<small>(.doc,.docx)</small></label>
                        <div class="col-lg-9">
                            <input  :class="{'is-invalid':error_upload.file}" ref="file" class="form-control-file" accept="application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"  type="file" value="">
                            <div v-if="error_upload.file" class="invalid-feedback">{{ error_upload.file }}</div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button :disabled="uploading" type="button" @click="uploadPaper" class="btn btn-primary">
                            <i v-if="uploading" class="fa fa-spin fa-spinner"></i> Submit
                        </button>
                    </div>
                </div>
                <div v-if="paper.status == 1">
                    <div class="alert alert-info">
                        <h4>Your paper is under review</h4>
                        <p>Please return to check your status later.You will be sent a notification when a decision has been made.</p>
                    </div>
                    <table class="table">
                        <tr><th>Title</th><td>{{paper.title}}</td></tr>
                        <tr><th>File Paper</th><td><a :href="paperUrl">Click here to download !</a> </td></tr>
                        <tr><th>Submitted On</th><td>{{submitOn}}</td></tr>
                    </table>
                </div>
                <div v-if="paper.status == 2">
                    <div class="alert alert-success">
                        <h4>Congratulation. .</h4>
                        <p> Your paper has been accepted, Please register to events.</p>
                    </div>
                </div>
            </div>
        </div>
    `,
    data: function () {
        return {
            loading: false,
            fail: false,
            paper: {},
            error_upload: {},
            uploading:false,
        }
    },
    created() {
        this.fetchData()
    },
    watch: {
        '$route': 'fetchData'
    },
    computed: {
        submitOn() {
            if (this.paper.updated_at) {
                return moment(this.paper.updated_at).format("DD MMM YYYY, [At] HH:mm:ss")
            }
            return "";
        },
        paperUrl() {
            if (this.paper.filename) {
                return this.baseUrl + "file/" + this.paper.filename;
            }
            return "#";
        }
    },
    methods: {
        fetchData() {
            var page = this;
            page.loading = true;
            $.post(this.baseUrl + "get_paper", function (res) {
                page.paper = res;
            }, "JSON").fail(function () {
                page.fail = true;
            }).always(function () {
                page.loading = false;
            });
        },
        uploadPaper() {
            var page = this;
            var fd = new FormData();
            fd.append('file', this.$refs.file.files[0]);
            fd.append('title', this.$refs.title.value);
            page.uploading = true;
            $.ajax({
                url: this.baseUrl + "upload_paper",
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function (response) {
                    if (response.status) {
                        page.paper = response.paper;
                    } else {
                        page.error_upload = response.message;
                    }
                },
            }).always(function () {
                page.uploading = false;
            }).fail(function () {
                Swal.fire('Fail',"Failed to process !",'error');
            });
        }
    }
});