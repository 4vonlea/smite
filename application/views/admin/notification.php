<?php
/**
 * @var $event
 */
?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8"></div>
<div class="container-fluid mt--7">
	<div class="row mb-2">
		<div class="col">
			<div class="nav-wrapper">
				<ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab"
						   href="#tabs-send_message" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="ni ni-world mr-2"></i>Send Message</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-1-tab" data-toggle="tab"
						   href="#tabs-certificate" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="ni ni-book-bookmark mr-2"></i>Send Certificate</a>
					</li>
					<li class="nav-item">
						<a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-1-tab" data-toggle="tab"
						   href="#tabs-material" role="tab" aria-controls="tabs-icons-text-1"
						   aria-selected="true"><i class="ni ni-book-bookmark mr-2"></i>Send Material</a>
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
						<div class="tab-pane fade show active" id="tabs-send_message" role="tabpanel">
							<div class="form-group">
								<div class="custom-control custom-radio custom-control-inline">
									<input v-model="message.target" id="customRadio1" class="custom-control-input" name="target" value="all" type="radio">
									<label class="custom-control-label" for="customRadio1">Send to All</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input v-model="message.target" id="customRadio2" class="custom-control-input" name="target" value="event_selected" type="radio">
									<label class="custom-control-label" for="customRadio2">Send to specific event participants</label>
								</div>
								<div class="custom-control custom-checkbox custom-control-inline">
									<input v-model="message.via" type="checkbox" class="custom-control-input" id="customCheck1" name="via" value="email">
									<label class="custom-control-label" for="customCheck1">Using Email</label>
								</div>
								<div class="custom-control custom-checkbox custom-control-inline">
									<input v-model="message.via" type="checkbox" class="custom-control-input" id="customCheck2" name="via" value="wa">
									<label class="custom-control-label" for="customCheck2">Using WA</label>
								</div>
							</div>
							<div v-if="message.target != 'all'" class="form-group">
								<label>To</label>
								<input type="text" v-model="message.to" class="form-control" name="to" />
							</div>
							<div v-if="message.target == 'event_selected'" class="form-group">
								<label>To Participant Event</label>
								<?=form_dropdown('event_notif',$event,'',['class'=>'form-control','v-model'=>'event_notif']);?>
							</div>
							<div class="form-group">
								<label>Subject</label>
								<input type="text" v-model="message.subject" class="form-control" name="subject" />
							</div>
							<div class="form-group">
								<label>Message</label>
								<textarea v-model="message.text" class="form-control" id="exampleFormControlTextarea1" rows="3" ></textarea>
							</div>
							<div class="form-group text-right">
								<button :disabled="sending" type="button" class="btn btn-primary" @click="sendMessage">
									<i v-if="sending" class="fa fa-spin fa-spinner"></i> Send
								</button>
							</div>
						</div>
						<div class="tab-pane fade show" id="tabs-certificate" role="tabpanel">
							<div class="form-group row">

								<label class="form-control-label col-md-3 mt-2">Send To Participant of Event</label>
								<div class="col-md-6">
									<?=form_dropdown('event',$event,'',['class'=>'form-control','v-model'=>'cert_event','placeholder'=>'TEST']);?>
								</div>
								<div class="col-md-3">
									<button :disabled="sendingCert" type="button" @click="sendCert" class="btn btn-primary">
										<i v-if="sendingCert" class="fa fa-spin fa-spinner"></i>
										Send
									</button>
								</div>
							</div>
							<hr/>
						</div>
						<div class="tab-pane fade" id="tabs-material" role="tab">
							<p>
								*Please select event first, if material is exist in table you can send if not please upload first
							</p>
							<div class="form-group row">

								<label class="form-control-label col-md-3 mt-2">Event Name</label>
								<div class="col-md-6">
									<?=form_dropdown('event',$event,'',['class'=>'form-control','v-model'=>'material_event','@change'=>'changeEventMaterial']);?>
								</div>
								<div class="col-md-3">
									<button :disabled="sendingMaterial" type="button" @click="sendMaterial" class="btn btn-primary">
										<i v-if="sendingMaterial" class="fa fa-spin fa-spinner"></i>Send
									</button>
								</div>
							</div>
							<div v-if="material_event" class="row">
								<h5>File Materials</h5>
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
										<tr>
											<th>#</th>
											<th>Name</th>
											<th>Size</th>
											<th>Speed</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
										</thead>
										<tbody>
										<tr v-if="!files.length">
											<td colspan="7">
												<div class="text-center p-3">
													Add Files To Upload !
												</div>
											</td>
										</tr>
										<tr v-for="(file, index) in files" :key="file.id">
											<td>{{index}}</td>
											<td>
												<div class="filename">
													{{file.name}}
												</div>
												<div style="height: 15px" class="progress" v-if="file.active || file.progress !== '0.00'">
													<div :class="{'progress-bar': true, 'progress-bar-striped': true, 'bg-danger': file.error, 'progress-bar-animated': file.active}" role="progressbar" :style="{width: file.progress + '%'}">{{file.progress}}%</div>
												</div>
											</td>
											<td>{{file.size}}</td>
											<td>{{file.speed}}</td>

											<td>{{file.response.message}}</td>
											<td>
												<a class="btn btn-primary" href="#" v-if="file.active" @click.prevent="$refs.upload.update(file, {active: false})">Abort</a>
												<a class="btn btn-primary" href="#" v-else-if="file.error && file.error !== 'compressing' && $refs.upload.features.html5" @click.prevent="$refs.upload.update(file, {active: true, error: '', progress: '0.00'})">Retry upload</a>
												<a class="btn btn-primary" :class="{disabled: file.success || file.error === 'compressing'}" href="#" v-else @click.prevent="file.success || file.error === 'compressing' ? false : $refs.upload.update(file, {active: true})">Upload</a>
												<a class="btn btn-danger" href="#" @click.prevent="removeFileMaterial(file,index)">Remove</a>
												<a class="btn btn-danger" :href="file.url" v-if="file.url" >Download</a>
											</td>
										</tr>
										</tbody>
									</table>
								</div>
								<div class="example-foorer">
									<div class="btn-group">
										<file-upload
											class="btn btn-primary"
											:multiple="uploadOptions.multiple"
											:post-action="uploadOptions.postUrl"
											:data="{event_id:material_event}"
											v-model="files"
											@input-filter="inputFilter"
											@input-file="inputFile"
											ref="upload">
											<i class="fa fa-plus"></i>
											Add Files
										</file-upload>
									</div>
									<button type="button" class="btn btn-success" v-if="!$refs.upload || !$refs.upload.active" @click.prevent="$refs.upload.active = true">
										<i class="fa fa-arrow-up" aria-hidden="true"></i>
										Start Upload
									</button>
									<button type="button" class="btn btn-danger"  v-else @click.prevent="$refs.upload.active = false">
										<i class="fa fa-stop" aria-hidden="true"></i>
										Stop Upload
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-pooling" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">{{ pooling.title }}</h5>
			</div>
			<div class="modal-body">
				<p style="font-size: 12px">*Please wait until prosess completed, don't reload or switch page</p>
				<div class="progress" style="height: 30px">
					<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="pooling.style" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">{{ pooling.processed }} of {{ pooling.size }}</div>
				</div>
				<table class="table">
					<tr>
						<th>Success</th>
						<td>{{ pooling.success }}</td>
					</tr>
					<tr>
						<th>Failed</th>
						<td>{{ pooling.fail }}</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button v-if="pooling.data.length == 0" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<script src="<?=base_url("themes/script/vue-upload-component.js");?>"></script>
<script>
    Vue.component('file-upload', VueUploadComponent);
	var app = new Vue({
        el: '#app',
		data:{
            sending:false,
            message:{
                "target":"all",
				"via":['wa','email'],
				"to":"",
				"subject":"",
				"text":"",
			},
			sendingCert:false,
			event_notif:"",
			cert_event:"",
			pooling:{title:"",data:[],size:0,success:0,fail:0,processed:0},
			files:[],
			sendingMaterial:false,
			material_event:"",
			material:[],
			uploadOptions:{
				multiple:true,
				postUrl:"<?=base_url("admin/notification/material_upload");?>"
			},
		},
		methods:{
            removeFileMaterial(file,index){
                if(file.url) {
                    $.post("<?=base_url("admin/notification/remove_material");?>", {
                        id: app.material_event,
                        name: file.name
                    },function () {
						app.files.splice(index,1);
                    });
                }else{
                    this.$refs.upload.remove(file);
				}
            },
            changeEventMaterial(){
                app.files = [];
				$.post("<?=base_url("admin/notification/get_material");?>",{event_id:app.material_event},function (res) {
				    $.each(res.files,function (i,v) {
				        v.response = {message:'Exist in server'};
				        v.success = true;
				        v.active = false;
				        v.progress = '0.00';
                        app.files.push(v);
                    })
                },"JSON");
			},
            inputFilter(newFile, oldFile, prevent) {
                if (newFile && !oldFile) {
                    // Before adding a file
                    // Filter system files or hide files
                    if (/(\/|^)(Thumbs\.db|desktop\.ini|\..+)$/.test(newFile.name)) {
                        return prevent()
                    }
                    // Filter php html js file
                    if (/\.(php5?|html?|jsx?)$/i.test(newFile.name)) {
                        return prevent()
                    }
                    // Automatic compression
                    if (newFile.file && newFile.type.substr(0, 6) === 'image/' && this.autoCompress > 0 && this.autoCompress < newFile.size) {
                        newFile.error = 'compressing'
                        const imageCompressor = new ImageCompressor(null, {
                            convertSize: Infinity,
                            maxWidth: 512,
                            maxHeight: 512,
                        })
                        imageCompressor.compress(newFile.file)
                            .then((file) => {
                                this.$refs.upload.update(newFile, { error: '', file, size: file.size, type: file.type })
                            })
                            .catch((err) => {
                                this.$refs.upload.update(newFile, { error: err.message || 'compress' })
                            })
                    }
                }
                if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
                    // Create a blob field
                    newFile.blob = ''
                    let URL = window.URL || window.webkitURL
                    if (URL && URL.createObjectURL) {
                        newFile.blob = URL.createObjectURL(newFile.file)
                    }
                    // Thumbnails
                    // 缩略图
                    newFile.thumb = ''
                    if (newFile.blob && newFile.type.substr(0, 6) === 'image/') {
                        newFile.thumb = newFile.blob
                    }
                }
            },
            inputFile(newFile, oldFile) {
                if (newFile && oldFile) {
                    // update
                    if (newFile.active && !oldFile.active) {
                        // beforeSend
                        // min size
                        if (newFile.size >= 0 && this.minSize > 0 && newFile.size < this.minSize) {
                            this.$refs.upload.update(newFile, { error: 'size' })
                        }
                    }
                    if (newFile.progress !== oldFile.progress) {
                        // progress
                    }
                    if (newFile.error && !oldFile.error) {
                        console.log('error', newFile.error, newFile)
                    }
                    if (newFile.success && !oldFile.success) {
                        newFile.name = newFile.response.data.name;
                        newFile.url = newFile.response.data.url;
                        console.log('success', newFile.success, newFile)
                    }
                }
                if (!newFile && oldFile) {
                    // remove
                    if (oldFile.success && oldFile.response.id) {
                    }
                }
                // Automatically activate upload
                if (Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
                    if (this.uploadAuto && !this.$refs.upload.active) {
                        this.$refs.upload.active = true
                    }
                }
            },
            sendMaterial(){
                var url = "<?=base_url('admin/notification/send_material/preparing');?>";
                var app = this;
                app.sendingMaterial = true;
                $.post(url,{id:this.material_event},null,'JSON')
                    .done(function (res) {
                        if(res.status) {
                            app.pooling.title = "Send Certificate";
                            app.pooling.url = "<?=base_url('admin/notification/send_material');?>";
                            app.pooling.data = res.data;
                            app.poolingStart();
                        }else
                            Swal.fire("Failed",res.message,"error");
                    }).fail(function (xhr) {
                    Swal.fire("Failed","Failed to load data !","error");
                }).always(function () {
                    app.sendingMaterial = false;
                });
			},
            poolingStart(){
                $("#modal-pooling").modal("show");
                this.pooling.size = app.pooling.data.length;
                this.pooling.success = 0;
                this.pooling.fail = 0;
                this.pooling.processed = 0;
                this.pooling.style = {"width":"0%"};
				var proses = function (url,data) {
					$.post(url,data,null,"JSON").done(function (res) {
						app.pooling.success++;
                    }).fail(function (xhr) {
                        app.pooling.fail++;
                    }).always(function () {
                        app.pooling.processed++;
                        var percent = (app.pooling.processed/app.pooling.size)*100;
                        app.pooling.style = {"width": percent+"%"};
                        if(app.pooling.data.length > 0)
							proses(url,app.pooling.data.pop());
                    });
                };

                if(app.pooling.data.length > 0) {
                    var worker = (app.pooling.data.length > 3) ? 3:app.pooling.data.length;
                    app.pooling.style = {"width": "0%"};
                    for(i=0;i<worker;i++) {
                        proses(this.pooling.url, app.pooling.data.pop());
                    }
                }
			},
            sendCert(){
                var url = "<?=base_url('admin/notification/send_cert/preparing');?>";
                var app = this;
                app.sendingCert = true;
                $.post(url,{id:this.cert_event},null,'JSON')
                    .done(function (res) {
                        if(res.status) {
                            app.pooling.title = "Send Certificate";
                            app.pooling.url = "<?=base_url('admin/notification/send_cert');?>";
                            app.pooling.data = res.data;
                            app.poolingStart();
                        }else
                            Swal.fire("Failed",res.message,"error");
                    }).fail(function (xhr) {
                    Swal.fire("Failed","Failed to load data !","error");
                }).always(function () {
                    app.sendingCert = false;
                });
			},
            sendMessage(){
                var url = "<?=base_url('admin/notification/send_message');?>";
                var app = this;
                app.sending = true;
                $.post(url,this.message,null,'JSON')
                    .done(function (res) {
						Swal.fire("Success","Message Sent !","success");
                    }).fail(function (xhr) {
                    Swal.fire("Failed","Failed to request send certificate !","error");
                }).always(function () {
                    app.sending = false;
                });
			}
		}

    })
</script>
<?php $this->layout->end_script(); ?>
