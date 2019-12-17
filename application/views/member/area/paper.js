export default Vue.component("PagePaper", {
    template: `
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div class="modal" data-backdrop="static" id="modal-fullpaper">
				<div class="modal-dialog">
					<div class="modal-content">
					  	<!-- Modal Header -->
						<div class="modal-header">
						<h4 class="modal-title">Upload Full Paper</h4>
						</div>
						<!-- Modal body -->
						<div class="modal-body">
							<form ref="formFullpaper" enctype="multipart/form-data">
								<input type="hidden" name="id" :value="formFullpaper.id" />
								<div class="form-group">
									<label class="font-weight-bold text-dark form-control-label text-2">Paper Title</label>
									<label>{{ formFullpaper.title}}</label>
								</div>
								<div class="form-group">
									<label class="font-weight-bold text-dark form-control-label text-2">Presentation On {{ formFullpaper.type_presence}}</label>
								</div>
								<hr/>
								<div class="form-group">
									<label class="font-weight-bold text-dark form-control-label text-2">Fullpaper (doc,docx,ods)</label>
									<input :class="{'is-invalid':error_fullpaper.fullpaper}" type="file" class="form-control-file" name="fullpaper" accept=".doc,.docx,.ods">
									<div v-if="error_fullpaper.fullpaper" class="invalid-feedback">{{ error_fullpaper.fullpaper }}</div>
								</div>
								<div class="form-group">
									<label class="font-weight-bold text-dark form-control-label text-2">Presentation/Poster File (jpg,png,jpeg,ppt,pptx)</label>
									<input :class="{'is-invalid':error_fullpaper.presentation}" type="file" class="form-control-file" name="presentation" accept=".jpg,.jpeg,.png,.ppt,.pptx">
									<div v-if="error_fullpaper.presentation" class="invalid-feedback">{{ error_fullpaper.presentation }}</div>
								</div>	
							</form>					  	
						</div>						
						<!-- Modal footer -->
						<div class="modal-footer">
						<button :disabled="uploadingFullpaper" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button :disabled="uploadingFullpaper" type="button" @click="uploadFullpaper" class="btn btn-primary" >
							<i v-if="uploadingFullpaper" class="fa fa-spin fa-spinner"></i> Upload
						</button>
						</div>
					</div>
				</div>
			</div>
            <div v-if="!loading && !fail">
                <div class="overflow-hidden mb-1">
                    <h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Submit Paper</strong></h2>
                </div>
                <div class="overflow-hidden mb-4 pb-3">
                    <p class="mb-0">Wanna participate on paper, please upload your fullpaper.</p>
                </div>
                <div v-if="mode == 0" class="table-responsive">
                	<table class="table table-bordered">
                		<thead>
                			<tr>
                				<th width="15%">Type</th>
                				<th width="40%">Title</th>
                				<th width="15%">Status</th>
                				<th width="15%">Submitted On</th>
                				<th width="15%"h>
									<button @click="mode = 1; form = {co_author:[],type:'Free Paper',type_presence:'',methods:''};detail=false;error_upload ={};" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr v-if="paper.data.length == 0">
								<td colspan="5" class="text-center">No Data</td>
							</tr>
							<tr v-for="pap in paper.data">
								<td>{{ pap.type }}</td>
								<td style="white-space: normal !important;">{{ pap.title }}</td>
								<td>
									<span style="font-size: 14px" class="badge"  :class="[ pap.status == 2 ?'badge-success': pap.status == 3 ? 'badge-danger':'badge-info' ]">
										{{ paper.status[pap.status] }}
									</span>
									<span v-if="pap.status == 0">
									Please Revise Paper <small>(Click Detail then Edit)</small>
									</span>
									<span v-if="pap.status == 2">
										<span v-if="!pap.fullpaper" style="font-weight: bold">
											<br/>Please upload your fullpaper <a href="#" @click.prevent="modalFullpaper(pap)">Here</a>
										</span>
										<hr/>
										<i class="fa" :class="[pap.fullpaper?'fa-check':'fa-times']"></i> Fullpaper<br/>
										<i class="fa" :class="[pap.poster?'fa-check':'fa-times']"></i> {{ pap.type_presence }} File<br/>
									</span>
								</td>
								<td>{{ formatDate(pap.created_at) }}</td>
								<td>
									<button @click="detailPaper(pap)" class="btn btn-primary"><i class="fa fa-search"></i></button>
									<button v-if="pap.status == 0 || pap.status == 1" @click="deletePaper(pap,$event)" class="btn btn-danger"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
					<p class="mb-0">*<span class="font-weight-bold">(Accepted paper only)</span> you can change/reupload your fullpaper or presentation/poster file, on detail page (icon loop)</p>
				</div>
                <div v-if="mode == 1">
                    <form ref="form" enctype="multipart/form-data">
                    	<input type="hidden" name="id" v-model="form.id" />
                    	<div v-if="detail" class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Status</label>
							<div class="col-lg-9">
								<span class="alert alert-info">{{ paper.status[form.status] }}</span>							
							</div>
						</div>
						<div v-if="detail && form.status == 2" class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Fullpaper Link</label>
							<div class="col-lg-9">
								<span v-if="form.fullpaper" ><a :href="paperUrl(form.fullpaper)">Click Here</a> | </span>
								<a href="#" @click.prevent="modalFullpaper(form)">Change/Upload Fullpaper or Presentation File</a>
							</div>
						</div>
						<div v-if="detail && form.status == 2" class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Presentation/Poster Link</label>
							<div class="col-lg-9">
								<span v-if="form.poster"><a  :href="paperUrl(form.poster)">Click Here</a> | </span>
								<a href="#" @click.prevent="modalFullpaper(form)">Change/Upload Fullpaper or Presentation File</a>
							</div>
						</div>
                    	<div class="form-group row">
                    		<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Type of Abstract*</label>
							<div class="col-lg-9">
								<select :disabled="detail" class="form-control" v-model="form.type" name="type" :class="{'is-invalid':error_upload.type}">
									<option v-for="(type,key) in paper.abstractType"  :value="key">{{ type }}</option>
								</select>
								<div v-if="error_upload.type" class="invalid-feedback">{{ error_upload.type }}</div>
							</div>
						</div>
						<div class="form-group row">
                    		<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Type of Study*</label>
							<div class="col-lg-9">
								<select :disabled="detail" class="form-control" v-model="form.methods" name="methods" :class="{'is-invalid':error_upload.methods}">
									<option disabled value="">Please Select</option>
									<option v-for="(type,key) in paper.typeStudy"  :value="key">{{ type }}</option>
								</select>
								<input :disabled="form.methods != 'Other' || detail" placeholder="If type of study other than above list, Please describe here"  :class="[{'is-invalid':error_upload.methods}, form.methods != 'Other' ? 'd-none':'']" class="form-control mt-1" name="type_study_other"  type="text" v-model="form.type_study_other" value="">
								<div v-if="error_upload.methods" class="invalid-feedback">{{ error_upload.methods }}</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Title*</label>
							<div class="col-lg-9">
								<input :disabled="detail"  :class="{'is-invalid':error_upload.title}" class="form-control" name="title"  type="text" v-model="form.title" value="">
								<div v-if="error_upload.title" class="invalid-feedback">{{ error_upload.title }}</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Abstract*</label>
							<div class="col-lg-9">
								<textarea :disabled="detail"  :class="{'is-invalid':error_upload.introduction}" v-model="form.introduction"  class="form-control" name="introduction">
								</textarea>
								<div v-if="error_upload.title" class="invalid-feedback">{{ error_upload.introduction }}</div>
							</div>
						</div>
						<div class="form-group row">
                    		<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Mode Of Presentation*</label>
							<div class="col-lg-9">
								<select :disabled="detail" class="form-control" v-model="form.type_presence" name="type_presence" :class="{'is-invalid':error_upload.type_presence}">
									<option disabled value="">Please Select</option>
									<option value="Oral">Oral</option>
									<option value="Poster">Poster</option>
								</select>
								<div v-if="error_upload.type_presence" class="invalid-feedback">{{ error_upload.type_presence }}</div>
							</div>
						</div>
<!--						<div class="form-group row">-->
<!--							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Aims*</label>-->
<!--							<div class="col-lg-9">-->
<!--								<textarea :disabled="detail"  :class="{'is-invalid':error_upload.aims}" v-model="form.aims"  class="form-control" name="aims">-->
<!--								</textarea>-->
<!--								<div v-if="error_upload.aims" class="invalid-feedback">{{ error_upload.aims }}</div>-->
<!--							</div>-->
<!--						</div>-->
<!--						<div class="form-group row">-->
<!--							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Methods*</label>-->
<!--							<div class="col-lg-9">-->
<!--								<textarea :disabled="detail"  :class="{'is-invalid':error_upload.methods}" v-model="form.methods"  class="form-control" name="methods">-->
<!--								</textarea>-->
<!--								<div v-if="error_upload.methods" class="invalid-feedback">{{ error_upload.methods }}</div>-->
<!--							</div>-->
<!--						</div>-->
<!--						<div class="form-group row">-->
<!--							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Result*</label>-->
<!--							<div class="col-lg-9">-->
<!--								<textarea :disabled="detail"  :class="{'is-invalid':error_upload.result}"  v-model="form.result" class="form-control" name="result">-->
<!--								</textarea>-->
<!--								<div v-if="error_upload.result" class="invalid-feedback">{{ error_upload.result }}</div>-->
<!--							</div>-->
<!--						</div>-->
<!--						<div class="form-group row">-->
<!--							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Conclusion*</label>-->
<!--							<div class="col-lg-9">-->
<!--								<textarea :disabled="detail"  :class="{'is-invalid':error_upload.conclusion}" v-model="form.conclusion"  class="form-control" name="conclusion">-->
<!--								</textarea>-->
<!--								<div v-if="error_upload.conclusion" class="invalid-feedback">{{ error_upload.conclusion }}</div>-->
<!--							</div>-->
<!--						</div>-->
						<div v-if="!detail" class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Upload Abstract*<small>(.doc,.docx,.ods)</small></label>
							<div class="col-lg-9">
								<input :class="{'is-invalid':error_upload.file}" ref="file" class="form-control-file" accept=".doc,.docx,.ods"  type="file" value="">
								<div v-if="error_upload.file" class="invalid-feedback">{{ error_upload.file }}</div>
							</div>
						</div>
						<div v-if="detail" class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Abstract Link</label>
							<div class="col-lg-9">
								<a :href="paperUrl(form.filename)">Click Here</a>
							</div>
						</div>
						<div v-if="detail && form.status == 0 && form.feedback" class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Feedback File</label>
							<div class="col-lg-9">
								<a :href="feedbackUrl(form.feedback)">Click Here</a>
							</div>
						</div>
						<div v-if="detail && form.message" class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Feedback Message</label>
							<div class="col-lg-9">
								<span>{{ form.message }}</span>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Co-Author <small>if exist</small></label>
							<div class="col-lg-9 text-right">
								<button v-if="!detail" type="button" class="btn btn-primary" @click="addCoAuthor">Add Co-Author</button>
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
									<tr v-for="(n,index) in form.co_author">
										<td><button v-if="!detail" type="button" @click="removeAuthor(index)" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
										<td><input :disabled="detail" type="text" v-model="n.fullname"  :name="'co_author['+index+'][fullname]'" class="form-control"/> </td>
										<td><input :disabled="detail" type="text" v-model="n.email"  :name="'co_author['+index+'][email]'" class="form-control"/> </td>
										<td><input :disabled="detail" type="text" v-model="n.phone"  :name="'co_author['+index+'][phone]'" class="form-control"/> </td>
										<td><input :disabled="detail" type="text" v-model="n.affiliation" :name="'co_author['+index+'][affiliation]'" class="form-control"/> </td>
									</tr>
								</table>
							</div>
						</div>

						<div class="form-group text-right">
							<button :disabled="uploading" class="btn btn-primary" @click="mode = 0">Back</button>
							<button v-if="detail && form.status == 0" :disabled="uploading" class="btn btn-primary" @click="detail = false;">Edit</button>
							<button v-if="!detail" :disabled="uploading" type="button" @click="uploadPaper" class="btn btn-primary">
								<i v-if="uploading" class="fa fa-spin fa-spinner"></i> Submit
							</button>
						</div>
                    </form>
                </div>
            </div>
        </div>
    `,
    data: function () {
        return {
        	mode:0,
			detail:false,
            loading: false,
            fail: false,
            paper: {},
            error_upload: {},
            uploading:false,
			form:{co_author:[],type:'Free Paper'},
			formFullpaper:{},
			uploadingFullpaper:false,
			error_fullpaper:{},
        }
    },
    created() {
        this.fetchData()
    },
    watch: {
        '$route': 'fetchData'
    },
    methods: {
    	uploadFullpaper(){
			var page = this;
			var fd = new FormData(this.$refs.formFullpaper);
			page.uploadingFullpaper = true;
			$.ajax({
				url: this.baseUrl + "upload_fullpaper",
				type: 'post',
				data: fd,
				contentType: false,
				processData: false,
				dataType: 'JSON',
				success: function (response) {
					if (response.status) {
						$("#modal-fullpaper").modal("hide");
						Swal.fire('Success',"Fullpaper or Presentation/Poster File uploaded successfully !",'success');
						if(response.data.fullpaper)
							page.formFullpaper.fullpaper = response.data.fullpaper;
						if(response.data.poster)
							page.formFullpaper.poster = response.data.poster;
						page.fetchData();
					} else {
						page.error_fullpaper = response.message;
					}
				},
			}).always(function () {
				page.uploadingFullpaper = false;
			}).fail(function () {
				Swal.fire('Fail',"Failed to process !",'error');
			});
		},
    	modalFullpaper(paper){
			this.$refs.formFullpaper.reset();
    		this.formFullpaper = paper;
    		this.error_fullpaper = {};
			$("#modal-fullpaper").modal("show");
		},
    	removeAuthor(i){
    		this.form.co_author.splice(i,1);
		},
		addCoAuthor(){
			this.form.co_author.push({'fullname':'','email':'','phone':'','affilition':''});
		},
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
		deletePaper(paper,event){
    		var btn = event.currentTarget;
    		var app = this;
			Swal.fire({
				title: "Are you sure ?",
				text: `You will delete paper with title "${paper.title}"`,
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.value) {
					btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
					btn.setAttribute("disabled",true);
					$.post(this.baseUrl+"delete_paper",paper,function (res) {
						if(res.status){
							app.fetchData();
						}else{
							Swal.fire('Fail',"Failed to delete !",'error');
						}
					}).fail(function () {
						Swal.fire('Fail',"Failed to delete !",'error');
					}).always(function () {
						btn.innerHTML = "<i class='fa fa-trash'></i>";
						btn.removeAttribute("disabled");
					});
				}
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
                        page.fetchData();
						page.mode = 0;
                    } else {
                        page.error_upload = response.message;
                    }
                },
            }).always(function () {
                page.uploading = false;
            }).fail(function () {
                Swal.fire('Fail',"Failed to process !",'error');
            });
        },
		detailPaper(row){
			this.mode = 1;
			this.form = row;
			this.detail = true;
			this.error_upload = {};
		},
		formatDate(date) {
			return moment(date).format("DD MMM YYYY, [At] HH:mm:ss")
		},
		paperUrl(filename) {
			if (filename) {
				return this.baseUrl + "file/" + filename;
			}
			return "#";
		},
		feedbackUrl(feedback) {
			if (feedback) {
				return this.baseUrl + "file/" + feedback;
			}
			return null;
		}
    }
});
