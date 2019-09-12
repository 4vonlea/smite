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
                <div v-if="paper.status == 0 || paper.status == 3">
                    <div v-if="paper.message" class="alert alert-info">
                        <h4>Your paper has been reviewed</h4>
                        <p v-if="paper.status == 3">Sorry your paper has been, rejected please provide another paper</p>
                        <p>{{ paper.message }} </p>
						<a v-if="feedbackUrl && paper.status != 3" :href="feedbackUrl" >Download Feedback File</a>                        
                        <p size="font-weight:bold">Please revise and reupload</p>
                    </div>
                    <form ref="form" enctype="multipart/form-data">
                    	<div class="form-group row">
                    		<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Type Abstract*</label>
							<div class="col-lg-9">
								<select class="form-control" v-model="paper.type" name="type" :class="{'is-invalid':error_upload.type}">
									<option v-for="(type,key) in paper.abstractType"  :value="key">{{ type }}</option>
								</select>
								<div v-if="error_upload.title" class="invalid-feedback">{{ error_upload.type }}</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Title*</label>
							<div class="col-lg-9">
								<input  :class="{'is-invalid':error_upload.title}" class="form-control" name="title"  type="text" v-model="paper.title" value="">
								<div v-if="error_upload.title" class="invalid-feedback">{{ error_upload.title }}</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Introduction*</label>
							<div class="col-lg-9">
								<textarea  :class="{'is-invalid':error_upload.introduction}" v-model="paper.introduction"  class="form-control" name="introduction">
								</textarea>
								<div v-if="error_upload.title" class="invalid-feedback">{{ error_upload.introduction }}</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Aims*</label>
							<div class="col-lg-9">
								<textarea  :class="{'is-invalid':error_upload.aims}" v-model="paper.aims"  class="form-control" name="aims">
								</textarea>
								<div v-if="error_upload.aims" class="invalid-feedback">{{ error_upload.aims }}</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Methods*</label>
							<div class="col-lg-9">
								<textarea  :class="{'is-invalid':error_upload.methods}" v-model="paper.methods"  class="form-control" name="methods">
								</textarea>
								<div v-if="error_upload.methods" class="invalid-feedback">{{ error_upload.methods }}</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Result*</label>
							<div class="col-lg-9">
								<textarea  :class="{'is-invalid':error_upload.result}"  v-model="paper.result" class="form-control" name="result">
								</textarea>
								<div v-if="error_upload.result" class="invalid-feedback">{{ error_upload.result }}</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Conclusion*</label>
							<div class="col-lg-9">
								<textarea  :class="{'is-invalid':error_upload.conclusion}" v-model="paper.conclusion"  class="form-control" name="conclusion">
								</textarea>
								<div v-if="error_upload.conclusion" class="invalid-feedback">{{ error_upload.conclusion }}</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Upload Paper *<small>(.doc,.docx,.ods)</small></label>
							<div class="col-lg-9">
								<input  :class="{'is-invalid':error_upload.file}" ref="file" class="form-control-file" accept=".doc,.docx,.ods"  type="file" value="">
								<div v-if="error_upload.file" class="invalid-feedback">{{ error_upload.file }}</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Co-Author <small>if exist</small></label>
							<div class="col-lg-9 text-right">
								<button type="button" class="btn btn-primary" @click="addCoAuthor">Add Co-Author</button>
							</div>
							<div class="col-lg-12">
								<table class="table">
									<tr>
										<th></th>
										<th>Fullname</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Affiliation</th>
									</tr>
									<tr v-for="(n,index) in paper.co_author">
										<td><button type="button" @click="removeAuthor(index)" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
										<td><input type="text" v-model="n.fullname"  :name="'co_author['+index+'][fullname]'" class="form-control"/> </td>
										<td><input type="text" v-model="n.email"  :name="'co_author['+index+'][email]'" class="form-control"/> </td>
										<td><input type="text" v-model="n.phone"  :name="'co_author['+index+'][phone]'" class="form-control"/> </td>
										<td><input type="text" v-model="n.affiliation" :name="'co_author['+index+'][affiliation]'" class="form-control"/> </td>
									</tr>
								</table>
							</div>
						</div>

						<div class="form-group text-right">
							<button :disabled="uploading" type="button" @click="uploadPaper" class="btn btn-primary">
								<i v-if="uploading" class="fa fa-spin fa-spinner"></i> Submit
							</button>
						</div>
                    </form>

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
        	typeAbstract:[],
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
        },
		feedbackUrl() {
			if (this.paper.feedback) {
				return this.baseUrl + "file/" + this.paper.feedback;
			}
			return null;
		}
    },
    methods: {
    	removeAuthor(i){
    		this.paper.co_author.splice(i,1);
		},
		addCoAuthor(){
			this.paper.co_author.push({'fullname':'','email':'','phone':'','affilition':''});
		},
        fetchData() {
            var page = this;
            page.loading = true;
            $.post(this.baseUrl + "get_paper", function (res) {
            	if(res.status == 3) {
            		page.paper = {
   						id:res.id,
            			status : 3,
						message:res.message
					};
				}else{
					page.paper = res;
				}
            }, "JSON").fail(function () {
                page.fail = true;
            }).always(function () {
                page.loading = false;
            });
        },
        uploadPaper() {
            var page = this;
            var fd = new FormData(this.$refs.form);
            fd.append('file', this.$refs.file.files[0]);
            // fd.append('title', this.$refs.title.value);
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
