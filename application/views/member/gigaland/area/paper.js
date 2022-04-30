export default Vue.component("PagePaper", {
    template: `
        <div class="col-lg-12">
			<page-loader :loading="loading" :fail="fail"></page-loader>
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
								<div v-if="formFullpaper.status == 2 && formFullpaper.status_fullpaper != 2 && !isAfter(paper.deadline.fullpaper_cutoff)"
									class="form-group">
									<label class="font-weight-bold form-control-label text-2 color-heading">Fullpaper (doc,docx,ods)</label>
									<input :class="{'is-invalid':error_fullpaper.fullpaper}" type="file"
										class="form-control" name="fullpaper" accept=".doc,.docx,.ods">
									<div v-if="error_fullpaper.fullpaper" class="invalid-feedback">{{ error_fullpaper.fullpaper
										}}</div>
								</div>
								<div v-if="isAfter(paper.deadline.fullpaper_cutoff)" class="alert alert-danger">
									You are no longer allowed to upload fullpaper, because it has passed the time limit
								</div>
								<div v-if="formFullpaper.status_fullpaper == 2 && !isAfter(paper.deadline.presentation_cutoff)"
									class="form-group">
									<label class="font-weight-bold form-control-label text-2 color-heading">{{ form.type_presence }} ({{ form.ext }})</label>
									<input :class="{'is-invalid':error_fullpaper.presentation}" type="file" class="form-control" id="presentation_upload" name="presentation" :accept="form.ext">
									<div v-if="error_fullpaper.presentation" class="invalid-feedback text-danger">{{
										error_fullpaper.presentation }}</div>
								</div>
								<hr/>
								<div v-if="formFullpaper.status_fullpaper == 2 && !isAfter(paper.deadline.presentation_cutoff)"
									class="form-group">
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
							<button :disabled="uploadingFullpaper" type="button" class="btn btn-danger"
								data-bs-dismiss="modal">Close</button>
							<button :disabled="uploadingFullpaper" type="button" @click="uploadFullpaper"
								class="btn btn-primary">
								<i v-if="uploadingFullpaper" class="fa fa-spin fa-spinner"></i> Upload
							</button>
						</div>
					</div>
				</div>
			</div>
			<div v-if="!loading && !fail">
				<div class="overflow-hidden mb-1">
					<h2 class="font-weight-normal color-heading text-7 mb-0"><strong class="font-weight-extra-bold">Send
							Manuscript</strong></h2>
				</div>
				<div class="overflow-hidden mb-4 pb-3">
					<p class="mb-0">If you want to participate, please upload your manuscript.</p>
				</div>
				<ul class="list-group list-group-horizontal flex-fill mb-2 ">
					<li class="list-group-item text-light border color-heading" style="background-color: transparent;">Deadline Abstract <span
							class='badge badge-info'>{{ paper.deadline.paper_deadline | formatDate }}</span></li>
					<li class="list-group-item text-light border color-heading" style="background-color: transparent;">Deadline Fullpaper
						<span class='badge badge-info'>{{ paper.deadline.fullpaper_deadline | formatDate }}</span></li>
					<li class="list-group-item text-light border color-heading" style="background-color: transparent;">Deadline Presentasi
						<span class='badge badge-info'>{{ paper.deadline.presentation_deadline | formatDate }}</span></li>
				</ul>
				<p class="mb-0">*<small class="font-weight-bold">You can change/ reupload your fullpaper or presentation/ poster
						file, on detail page (magnifying glass icon)</small></p>
				<p class="mb-0">*<small class="font-weight-bold">Note: the abstract that has been submitted cannot be deleted,
						please make sure to fill in correctly</small></p>

				<div v-if="mode == 0" class="table-responsive">
					<table class="table text-light border">
						<thead>
							<tr>
								<th class="border-end color-heading" width="15%">Manuscript Section</th>
								<th class="border-end color-heading" width="15%">Manuscript Category</th>
								<th class="border-end color-heading">Title</th>
								<th class="border-end color-heading">Status</th>
								<th class="border-end color-heading" width="90px">
									<button :disabled="isAfter(paper.deadline.paper_cutoff)"
										@click="mode = 1; form = {co_author:[],type:'Case Report',type_presence:'',methods:'',check:null};detail=false;error_upload ={};"
										class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr v-if="paper.data.length == 0">
								<td colspan="5" class="text-center">No Data</td>
							</tr>
							<tr v-for="pap in paper.data">
								<td class="border-end">
									{{ pap.category_paper.name }}
								</td>
								<td class="border-end">
									{{ pap.type }} {{ pap.status }}
								</td>
								<td class="border-end" style="white-space: normal !important;">{{ pap.title }}</td>
								<td class="border-end">
									<ul class="list-group">
										<li class="list-group-item d-flex justify-content-between align-items-center border text-light"
											style="background-color:transparent">
											Abstract
											<span class="badge badge-primary badge-pill">{{ paper.status[pap.status] }}</span>

										</li>
										<li class="list-group-item d-flex justify-content-between align-items-center border text-light"
											style="background-color:transparent">
											Fullpaper
											<span class="badge badge-primary badge-pill">{{ (pap.status == 2 ?
												paper.status[pap.status_fullpaper]:'') }}</span>
										</li>
										<li class="list-group-item d-flex justify-content-between align-items-center border text-light"
											style="background-color:transparent">
											Presentation
											<span class="badge badge-primary badge-pill">{{ (pap.status_fullpaper == 2 ?
												paper.status[pap.status_presentasi]:'') }}</span>
										</li>
									</ul>
									<div class="text-center pt-2">
										<h5 class="badge badge-info" v-if="pap.status == 0">
											Please correct your abstract <br /><small>(Press Details then Edit)</small>
										</h5>
										<h5 class="badge badge-info" v-if="pap.status == 2 && pap.status_fullpaper == 0">
											Please correct your fullpaper <br /><small>(View Details)</small>
										</h5>
										<h5 class="badge badge-info"
											v-if="pap.status_fullpaper == 2 && pap.status_presentasi == 0">
											Please correct your abstract <br /><small>(View Details)</small>
										</h5>
										<span v-if="pap.status_fullpaper == 2">
											<h5 class="badge badge-info">(Presentation on {{ pap.type_presence }})</h5>
										</span>
									</div>

									<span v-if="pap.status == 2">
										<hr />
										<i class="fa" :class="[pap.fullpaper?'fa-check':'fa-times']"></i>Fullpaper File<br />
										<i v-if="pap.status_fullpaper" class="fa"
											:class="[pap.poster?'fa-check':'fa-times']"></i>Presentation Image/ File ({{
										pap.type_presence }})<br />
									</span>
								</td>
								<td>
									<button @click="detailPaper(pap)" class="btn btn-primary"><i
											class="fa fa-search"></i></button>
									<button v-if="(pap.status == 0 || pap.status == 1) && false"
										@click="deletePaper(pap,$event)" class="btn btn-danger"><i
											class="fa fa-trash"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="mode == 1">
					<form ref="form" enctype="multipart/form-data" class="form-border">
						<input type="hidden" name="id" v-model="form.id" />
						<div v-if="detail" class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Manuscsript ID</label>
							<div class="col-lg-9">
								<label>{{ form.id_paper }}</label>
							</div>
						</div>
						<div v-if="detail" class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Status</label>
							<div class="col-lg-9">
								<ul class="list-group">
									<li class="list-group-item d-flex justify-content-between align-items-center border text-light"
										style="background-color:transparent">
										Abstract
										<span class="badge badge-primary badge-pill">{{ paper.status[form.status] }}</span>
									</li>
									<li class="list-group-item d-flex justify-content-between align-items-center border text-light"
										style="background-color:transparent">
										Fullpaper
										<span class="badge badge-primary badge-pill">{{ paper.status[form.status_fullpaper]
											}}</span>
									</li>
									<li class="list-group-item d-flex justify-content-between align-items-center border text-light"
										style="background-color:transparent">
										Presentation
										<span class="badge badge-primary badge-pill">{{ paper.status[form.status_presentasi]
											}}</span>
									</li>
								</ul>
							</div>
						</div>


						<div v-if="detail && form.status_fullpaper == 0 && form.feedback_file_fullpaper"
							class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Feedback File
								Fullpaper</label>
							<div class="col-lg-9">
								<a :href="feedbackUrl(form.feedback_file_fullpaper,form.id)">Click Here</a>
							</div>
						</div>
						<div v-if="detail && form.status_fullpaper == 0 && form.feedback_fullpaper" class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Fullpaper
								Feedback</label>
							<div class="col-lg-9">
								<span>{{ form.feedback_fullpaper }}</span>
							</div>
						</div>
						<div v-if="detail && form.status == 2" class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Fullpaper
								Link</label>
							<div class="col-lg-9">
								<span v-if="form.fullpaper"><a :href="paperUrl(form.fullpaper, form.id, 'Fullpaper')">Click here</a> | </span>
								<a v-if="form.status_fullpaper != 2" href="#" @click.prevent="modalFullpaper(form)">Change/ Upload
									Fullpaper </a>
							</div>
						</div>


						<div v-if="detail && form.status_presentasi == 0 && form.feedback_file_presentasi"
							class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">File Presentation
								Feedback</label>
							<div class="col-lg-9">
								<a :href="feedbackUrl(form.feedback_file_presentasi,form.id)">Click here</a>
							</div>
						</div>
						<div v-if="detail && form.status_presentasi == 0 && form.feedback_fullpaper"
							class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Presentation
								Feedback</label>
							<div class="col-lg-9">
								<span>{{ form.feedback_presentasi }}</span>
							</div>
						</div>
						<div v-if="detail &&  form.status_fullpaper == 2" class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Presentation
								Link</label>
							<div class="col-lg-9">
								<span v-if="form.poster"><a :href="paperUrl(form.poster, form.id, form.type_presence)">Click here</a> | </span>
								<span v-if="form.voice"><a :href="paperUrl(form.voice, form.id,'Voice Recording')">Voice Recording</a> | </span>

								<a v-if="form.status_presentasi != 2" href="#"
									@click.prevent="modalFullpaper(form)">Change/ Upload Presentation File</a>
							</div>
						</div>
						<div class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Manuscript Section*</label>
							<div class="col-lg-9">
								<select :disabled="detail" class="form-control text-light" v-model="form.category" name="category"
									style="background-color: #202429" :class="{'is-invalid':error_upload.category}">
									<option v-for="(category,key) in paper.categoryPaper" :value="category">{{ category }}</option>
								</select>
								<div v-if="error_upload.category" class="invalid-feedback">{{ error_upload.category }}</div>
							</div>
						</div>
						<div class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Manuscript Category*</label>
							<div class="col-lg-9">
								<select :disabled="detail" class="form-control text-light" v-model="form.type" name="type"
									style="background-color: #202429" :class="{'is-invalid':error_upload.type}">
									<option v-for="(type,key) in paper.treePaper[form.category]" :value="key">{{ key }}</option>
								</select>
								<div v-if="error_upload.type" class="invalid-feedback">{{ error_upload.type }}</div>
							</div>
						</div>
						<div class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Manuscript Type*</label>
							<div class="col-lg-9">
								<select :disabled="detail" class="form-control text-light" v-model="form.methods" name="methods"
									style="background-color: #202429" :class="{'is-invalid':error_upload.methods}">
									<option v-for="(type,key) in form.category && form.type ? paper.treePaper[form.category][form.type] : []" :value="key">{{ key }}</option>
								</select>
								<input :disabled="form.methods != 'Other' || detail"
									placeholder="If the study type is other than the list above, please describe it here"
									:class="[{'is-invalid':error_upload.methods}, form.methods != 'Other' ? 'd-none':'']"
									class="form-control mt-1" name="type_study_other" type="text"
									v-model="form.type_study_other" value="">
								<div v-if="error_upload.methods" class="invalid-feedback">{{ error_upload.methods }}</div>
							</div>
						</div>
						<div class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Title*</label>
							<div class="col-lg-9">
								<input :disabled="detail" :class="{'is-invalid':error_upload.title}" class="form-control"
									name="title" type="text" v-model="form.title" value="">
								<div v-if="error_upload.title" class="invalid-feedback">{{ error_upload.title }}</div>
							</div>
						</div>
						<div class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Abstract*</label>
							<div class="col-lg-9">
								<textarea :disabled="detail" rows="5" @keydown='wordCount' @keyup='reduceWord'
									:class="{'is-invalid':error_upload.introduction}" v-model="form.introduction"
									class="form-control" name="introduction" style="background-color: transparent;">
										</textarea>
								<div v-if="error_upload.title" class="invalid-feedback">{{ error_upload.introduction }}</div>
								<small>{{ wordCountIntroduction }} Word (300 maximum)</small>
							</div>
						</div>
						<!--
								<div class="form-group row mb-2">
									<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Presentation On*</label>
									<div class="col-lg-9">
										<select :disabled="detail" class="form-control" v-model="form.type_presence" name="type_presence" :class="{'is-invalid':error_upload.type_presence}">
											<option disabled value="">Please Select</option>
											<option v-for="(type,key) in paper.typePresention"  :value="key">{{ type }}</option>
										</select>
										<div v-if="error_upload.type_presence" class="invalid-feedback">{{ error_upload.type_presence }}</div>
									</div>
								</div>
								-->
						<!--						<div class="form-group row mb-2">-->
						<!--							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Purpose*</label>-->
						<!--							<div class="col-lg-9">-->
						<!--								<textarea :disabled="detail"  :class="{'is-invalid':error_upload.aims}" v-model="form.aims"  class="form-control" name="aims">-->
						<!--								</textarea>-->
						<!--								<div v-if="error_upload.aims" class="invalid-feedback">{{ error_upload.aims }}</div>-->
						<!--							</div>-->
						<!--						</div>-->
						<!--						<div class="form-group row mb-2">-->
						<!--							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Methods*</label>-->
						<!--							<div class="col-lg-9">-->
						<!--								<textarea :disabled="detail"  :class="{'is-invalid':error_upload.methods}" v-model="form.methods"  class="form-control" name="methods">-->
						<!--								</textarea>-->
						<!--								<div v-if="error_upload.methods" class="invalid-feedback">{{ error_upload.methods }}</div>-->
						<!--							</div>-->
						<!--						</div>-->
						<!--						<div class="form-group row mb-2">-->
						<!--							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Result*</label>-->
						<!--							<div class="col-lg-9">-->
						<!--								<textarea :disabled="detail"  :class="{'is-invalid':error_upload.result}"  v-model="form.result" class="form-control" name="result">-->
						<!--								</textarea>-->
						<!--								<div v-if="error_upload.result" class="invalid-feedback">{{ error_upload.result }}</div>-->
						<!--							</div>-->
						<!--						</div>-->
						<!--						<div class="form-group row mb-2">-->
						<!--							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Conclusion*</label>-->
						<!--							<div class="col-lg-9">-->
						<!--								<textarea :disabled="detail"  :class="{'is-invalid':error_upload.conclusion}" v-model="form.conclusion"  class="form-control" name="conclusion">-->
						<!--								</textarea>-->
						<!--								<div v-if="error_upload.conclusion" class="invalid-feedback">{{ error_upload.conclusion }}</div>-->
						<!--							</div>-->
						<!--						</div>-->
						<div v-if="!detail" class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Upload
								Abstract*<small>(.doc,.docx,.ods)</small></label>
							<div class="col-lg-9">
								<input :class="{'is-invalid':error_upload.file}" ref="file" class="form-control-file"
									accept=".doc,.docx,.ods" type="file" value="">
								<div v-if="error_upload.file" class="invalid-feedback">{{ error_upload.file }}</div>
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
						<!-- <div class="form-group row mb-2">
							<label class="col-lg-3 font-weight-bold col-form-label form-control-label text-2 color-heading">Co-Author
								<small>(if any)</small></label>
							<div class="col-lg-9 text-right">
								<button v-if="!detail" type="button" class="btn btn-primary" @click="addCoAuthor">Add
									Co-Author</button>
							</div>
							<div class="col-lg-12 mt-3">
								<table class="table text-light border">
									<tr>
										<th></th>
										<th>Fullname</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Affiliation</th>
									</tr>
									<tr v-for="(n,index) in form.co_author">
										<td><button v-if="!detail" type="button" @click="removeAuthor(index)"
												class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
										<td><input :disabled="detail" type="text" v-model="n.fullname"
												:name="'co_author['+index+'][fullname]'" class="form-control" /> </td>
										<td><input :disabled="detail" type="text" v-model="n.email"
												:name="'co_author['+index+'][email]'" class="form-control" /> </td>
										<td><input :disabled="detail" type="text" v-model="n.phone"
												:name="'co_author['+index+'][phone]'" class="form-control" /> </td>
										<td><input :disabled="detail" type="text" v-model="n.affiliation"
												:name="'co_author['+index+'][affiliation]'" class="form-control" /> </td>
									</tr>
								</table>
							</div>
						</div> -->
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
							<button :disabled="uploading" class="btn btn-primary" @click="mode = 0">Back</button>
							<button v-if="detail && form.status == 0" :disabled="uploading" class="btn btn-primary"
								@click="detail = false;">Edit</button>
							<button v-if="!detail" :disabled="uploading" type="button" @click="uploadPaper"
								class="btn btn-primary">
								<i v-if="uploading" class="fa fa-spin fa-spinner"></i> Submit
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
    `,
    data: function() {
        return {
            mode: 0,
            detail: false,
            loading: false,
            fail: false,
            paper: { deadline: {},declaration:{} },
            error_upload: {},
            uploading: false,
            form: { co_author: [], category: 'AOMC', type: 'ePoster' },
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
                url: this.baseUrl + "upload_fullpaper",
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(response) {
                    if (response.status) {
                        $("#modal-fullpaper").modal("hide");
                        Swal.fire('Success', "Fullpaper or Presentation/ Poster File uploaded successfully !", 'success');
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
            this.form.co_author.push({ 'fullname': '', 'email': '', 'phone': '', 'affilition': '' });
        },
        fetchData() {
            var page = this;
            page.loading = true;
            $.post(this.baseUrl + "get_paper", function(res) {
                page.paper = res;
            }, "JSON").fail(function() {
                page.fail = true;
            }).always(function() {
                page.loading = false;
            });
        },
        deletePaper(paper, event) {
            var btn = event.currentTarget;
            var app = this;
            Swal.fire({
                title: "Are you sure ?",
                text: `You will delete manuscript with title "${paper.title}"`,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
                    btn.setAttribute("disabled", true);
                    $.post(this.baseUrl + "delete_paper", paper, function(res) {
                        if (res.status) {
                            app.fetchData();
                        } else {
                            Swal.fire('Fail', "Failed to delete !", 'error');
                        }
                    }).fail(function() {
                        Swal.fire('Fail', "Failed to delete !", 'error');
                    }).always(function() {
                        btn.innerHTML = "<i class='fa fa-trash'></i>";
                        btn.removeAttribute("disabled");
                    });
                }
            });
        },
        uploadPaper() {
            var page = this;
            if (this.form.check) {
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
                    success: function(response) {
                        if (response.status) {
                            page.fetchData();
                            page.mode = 0;
                        } else {
                            page.error_upload = response.message;
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
        detailPaper(row) {
            this.mode = 1;
            this.form = row;
            this.form.category = row.category_paper.name;
            if (this.form.type_presence == 'Oral') {
                this.form.ext = '.ppt, .pptx, .pdf'
            } else if (this.form.type_presence == 'Viewed Poster' || this.form.type_presence == 'Moderated Poster') {
                this.form.ext = '.jpg, .png, .jpeg, .ppt'
            } else if (this.form.type_presence == 'Voice Recording') {
                this.form.ext = '.mp3, .m4a';
                console.log(this.form.ext);
            }

            $('#presentation_upload').attr('accept', this.form.ext);
            console.log(this.form.ext);

            this.detail = true;
            this.error_upload = {};
        },
        formatDate(date) {
            return moment(date).format("DD MMM YYYY, [At] HH:mm:ss")
        },
        paperUrl(filename, id, type = 'Abstract') {
            if (filename) {
                return `${this.baseUrl}file/${filename}/${id}/${type}`;
            }
            return "#";
        },
        feedbackUrl(feedback,id) {
            if (feedback) {
                return this.baseUrl + `file/${feedback}/${id}/Feedback`;
            }
            return null;
        }
    }
});