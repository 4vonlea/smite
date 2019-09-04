<?php
/**
 * @var $report
 */
?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml"
	 xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>
<div class="container-fluid mt--7">

	<transition name="fade">
		<div v-if="!modeCheck" class="row">
			<div class="col-md-12">
				<div class="card shadow border-0">
					<div class="card-header bg-white pb-5">
						<div class="row">
							<div class="col-6">
								<h3>Presence Check - List Event</h3>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table align-items-center table-flush">
							<thead class="thead-light">
							<tr>
								<th scope="col">Event Name</th>
								<th scope="col">Numbers of Participant</th>
								<th scoprt="col"></th>
							</tr>
							</thead>
							<tbody>
							<tr v-for="row in report.participants_event">
								<td> {{ row.name }}</td>
								<td>{{ row.number_participant }}</td>
								<td>
									<button @click="presencePage(row,$event)" type="button" class="btn btn-primary">
										Check
									</button>
									<button type="button" @click="downloadReport(row)" class="btn btn-primary">Download Report</button>
								</td>
							</tr>
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
		<div v-else class="row">
			<div class="col-md-12">
				<div class="card shadow border-0">
					<div class="card-header bg-white pb-5">
						<div class="row">
							<div class="col-6">
								<h3>Presence Check Event : {{ pageCheck.event.name }} </h3>
							</div>
							<div class="col-6 text-right">
								<button type="button" class="btn btn-primary" @click="back">Back</button>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table">
							<tr>
								<th>Number of Participant :</th>
								<td>{{ pageCheck.numberParticipant }}</td>
								<th>Number of Presence :</th>
								<td>{{ presence }}</td>
							</tr>
							<tr>
								<th>Device Has Camera :</th>
								<td id="cam-has-camera" ref="camHasCamera"></td>
								<th>Result Scan :</th>
								<td>{{ pageCheck.lastResult }}</td>
							</tr>
						</table>
					</div>
					<div class="col-sm-12 text-center">
						<video style="border:solid 3px;max-width: 500px;width: 100%" muted playsinline id="qr-video" ref="qrVideo"></video>
					</div>

				</div>
			</div>
		</div>
	</transition>
</div>
<?php $this->layout->begin_script(); ?>
<script type="module">
    import QrScanner from "<?=base_url("themes/script/qr-scanner.min.js");?>";

    QrScanner.WORKER_PATH = '<?=base_url("themes/script/qr-scanner-worker.min.js");?>';

    var scanner = null;
	var timeOut = null;
    var app = new Vue({
        el: "#app",
        data: {
            modeCheck: false,
            pageCheck: {lastResult:"None"},
            report:<?=json_encode($report);?>,
        },
		computed:{
			presence(){
			    var i = 0;
				$.each(this.pageCheck.data,function (j,r) {
					if(r.presence_at)
						i++;
				});
			    return i;
			}
		},
        methods: {
            back() {
                app.pageCheck = {lastResult:"None"};
                if (scanner) {
                    scanner.stop();
                    this.modeCheck = false;
                }
            },
			downloadReport(row){
                window.open("<?=base_url('admin/presence/report');?>/"+row.id_event);
			},
            presencePage(row, ev) {
                ev.target.innerHTML = "<i class='fa fa-spin fa-spinner'></i> Loading Data . .";
                $.post("<?=base_url('admin/presence/get_data');?>", {id: row.id_event}, function (res) {
                    if (res.status) {
                        app.pageCheck.data = res.data;
                        app.modeCheck = true;
                        app.pageCheck.event = {id: row.id_event, name: row.name};
                        app.pageCheck.numberParticipant = row.number_participant;

                        Vue.nextTick(function () {
                            const video = app.$refs.qrVideo;
                            const camHasCamera = app.$refs.camHasCamera;

                            function setResult(result) {
                                if(app.pageCheck.lastResult != result) {
                                    var found = false;
                                    $.each(app.pageCheck.data, function (i,r) {
                                        if (r.id == result) {
                                            found = true;
                                            var date = new Date();
                                            r.presence_at = date.toISOString().slice(0, 19).replace('T', ' ');
                                            Swal.fire({
                                                title: '<strong>Presence Checked</strong>',
                                                type: 'success',
                                                html:
                                                    `<p>Presence of <b>${r.fullname}</b> As <b>${r.status_member}</b></p>` +
                                                    `<p>Checked At <b>${moment(date).format("DD MMM YYYY, [At] HH:mm:ss")}</b></p>`,
                                                showCloseButton: true,
                                            });
                                            $.post("<?=base_url('admin/presence/save');?>", {
                                                member_id: r.id,
                                                event_id: app.pageCheck.event.id,
                                                created_at: date.toISOString().slice(0, 19).replace('T', ' ')
                                            });
                                        }
                                    });
                                    if(found == false){
                                        Swal.fire("Info", "Participant not register on this event !", "info");
                                    }
                                    app.pageCheck.lastResult = result;
                                    clearTimeout(timeOut);
                                    timeOut = setTimeout(() => app.pageCheck.lastResult = "None", 5000);
                                }
                            }


                            QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);
                            scanner = new QrScanner(video, result => setResult(result));
                            scanner.start();
                            scanner.setInversionMode("both");

                        });
                    } else {
                        Swal.fire("Failed", "Failed to load data !", "error");
                    }
                }, "JSON").always(function () {
                    ev.target.innerHTML = "Check";
                }).fail(function () {
                    Swal.fire("Failed", "Failed to load data !", "error");
                });
            }
        }
    })
</script>
<script type="module">


</script>
<?php $this->layout->end_script(); ?>

