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

						</div>
					</div>
				</div>
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
			}
		},
		methods:{
            sendMessage(){
                var url = "<?=base_url('admin/notification/send_message');?>";
                var app = this;
                app.sending = true;
                $.post(url,this.message,null,'JSON')
                    .done(function (res) {
						Swal.fire("Success","Message Sent !","success");
                    }).fail(function (xhr) {
                    Swal.fire("Failed","Failed to load data !","error");
                }).always(function () {
                    app.sending = false;
                });
			}
		}

    })
</script>
<?php $this->layout->end_script(); ?>
