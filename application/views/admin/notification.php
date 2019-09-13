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
						   href="#tabs-certificate" role="tab" aria-controls="tabs-icons-text-1"
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
<!--								<div class="custom-control custom-radio custom-control-inline">-->
<!--									<input v-model="message.target" id="customRadio2" class="custom-control-input" name="target" value="specific" type="radio">-->
<!--									<label class="custom-control-label" for="customRadio2">Send To Specific Contact</label>-->
<!--								</div>-->
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
<script>
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
			cert_event:"",
			pooling:{title:"",data:[],size:0,success:0,fail:0,processed:0},
		},
		methods:{
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
                    var percent = (app.pooling.processed/app.pooling.size)*100;
                    app.pooling.style = {"width": percent+"%"};
                    proses(this.pooling.url, app.pooling.data.pop());
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
