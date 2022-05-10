<div class="header bg-primary pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<!-- Card stats -->
			<div class="row">
				<div class="col-xl-4 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Total Members</h5>
									<span class="h2 font-weight-bold mb-0">{{ report.total_members }}</span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
										<i class="fas fa-chart-bar"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Unverified Members</h5>
									<span class="h2 font-weight-bold mb-0">{{ report.unverified_members }}</span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-warning text-white rounded-circle shadow">
										<i class="fas fa-chart-pie"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Incoming Paper</h5>
									<span class="h2 font-weight-bold mb-0">{{ report.participants_paper }}</span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
										<i class="fas fa-users"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid mt-3">
		<div class="row flex-nowrap mt-2" style="overflow-x: auto;">
			<div v-for="chart in report.charts" class="col-md-8  mb-2">
				<div class="card card-block">
					<div class="card-body">
						<h5 class="card-title text-center">{{ chart.title }}</h5>
						<line-chart :chart-data="chart.data"></line-chart>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-5">
			<div class="col-xl-12 mb-5 mb-xl-0">
				<div class="card shadow">
					<div class="card-header border-0">
						<div class="row align-items-center">
							<!-- <div class="col-md-3">
								<h3 class="mb-0">Partipants of Events</h3>
							</div> -->
							<div class="col-md-12 text-center">
								<div class="btn-group">
									<div class="btn-group">
										<button class="btn btn-primary dropdown-toggle mt-2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Download Summary
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<button class="dropdown-item" @click="exportSummary('excel')">As Excel</button>
											<button class="dropdown-item" @click="exportSummary('csv')">As CSV</button>
											<button class="dropdown-item" @click="exportSummary('pdf')">As PDF</button>
										</div>
									</div>
									<div class="btn-group">
										<button class="btn btn-primary dropdown-toggle mt-2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Download Members
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_member/excel"); ?>" target="_blank">As Excel</a>
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_member/csv"); ?>" target="_blank">As CSV</a>
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_member/pdf"); ?>" target="_blank">As PDF</a>
										</div>
									</div>
									<div class="btn-group">
										<button class="btn btn-primary dropdown-toggle mt-2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Download Participant Papers
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_paper/excel"); ?>" target="_blank">As Excel</a>
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_paper/csv"); ?>" target="_blank">As CSV</a>
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_paper/pdf"); ?>" target="_blank">As PDF</a>
										</div>
									</div>
									<div class="btn-group">
										<button class="btn btn-primary dropdown-toggle mt-2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Download Members Event
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_member_event/excel"); ?>" target="_blank">As Excel</a>
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_member_event/csv"); ?>" target="_blank">As CSV</a>
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_member_event/pdf"); ?>" target="_blank">As PDF</a>
										</div>
									</div>
									<div class="btn-group">
										<button class="btn btn-primary dropdown-toggle mt-2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Download Transaksi
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_transaksi/excel"); ?>" target="_blank">As Excel</a>
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_transaksi/csv"); ?>" target="_blank">As CSV</a>
											<a class="dropdown-item" href="<?= base_url("admin/dashboard/download_transaksi/pdf"); ?>" target="_blank">As PDF</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3 mt-4">
							<h3 class="mb-0">Partipants of Events</h3>
						</div>
					</div>
					<div class="table-responsive">
						<!-- Projects table -->
						<table class="table align-items-center table-flush">
							<thead class="thead-light">
								<tr>
									<th style="width: 40%" scope="col">Event Name</th>
									<th style="width: 5%" scope="col">Participant</th>
									<th style="width: 5%" scope="col">Qouta</th>
									<!-- <th style="width: 5%" scope="col">Remaining Quota</th> -->
									<!-- <th style="width: 5%" scope="col">Nametag Taken</th> -->
									<!-- <th style="width: 5%" scope="col">Seminar Kit Taken</th> -->
									<!-- <th style="width: 5%" scope="col">Certificate Taken</th> -->
									<?php if ($this->session->user_session['role'] == User_account_m::ROLE_SUPERADMIN) : ?>
										<th style="width: 5%" scope="col">Fund Collected</th>
									<?php endif; ?>
									<th style="width: 25%" scoprt="col"></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="p in report.participants_event">
									<th>{{ p.name }}</th>
									<td>{{ p.number_participant }}</td>
									<td>{{ p.kouta }}</td>
									<!-- <td>{{ p.kouta-p.number_participant }}</td> -->
									<!-- <td>Taken: {{ p.nametag }} | Remaining: {{ p.number_participant - p.nametag }}</td> -->
									<!-- <td>Taken: {{ p.seminarkit }} | Remaining: {{ p.number_participant - p.seminarkit }}</td> -->
									<!-- <td>Taken: {{ p.certificate }} | Remaining: {{ p.number_participant - p.certificate }}</td> -->
									<?php if ($this->session->user_session['role'] == User_account_m::ROLE_SUPERADMIN) : ?>
										<td>{{ formatCurrency(p.fund_collected) }}</td>
									<?php endif; ?>
									<td  v-if="p.id_event != '0'">
										<button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Download Participants
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" @click="downloadParticipant(p.id_event,'excel')">As Excel</a>
											<a class="dropdown-item" @click="downloadParticipant(p.id_event,'csv')">As CSV</a>
											<a class="dropdown-item" @click="downloadParticipant(p.id_event,'pdf')">As PDF</a>
										</div>
									</td>
								</tr>
							</tbody>
							<tfoot class="thead-light">
								<th>Total</th>
								<th>{{ total.number }}</th>
								<th>-</th>
								<th>-</th>
								<th>-</th>
								<th>-</th>
								<th>-</th>
								<?php if ($this->session->user_session['role'] == User_account_m::ROLE_SUPERADMIN) : ?>
									<th>{{ formatCurrency(total.fund) }}</th>
								<?php endif; ?>
								<th></th>
							</tfoot>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<?php $this->layout->begin_script(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script src="https://unpkg.com/vue-chartjs@3.5.1/dist/vue-chartjs.min.js"></script>
<script>
	Vue.component('line-chart', {
		extends: VueChartJs.Bar,
		props: {
			chartData: {
				type: Object,
				default: null
			},
			title:{
				type: String,
				default: ''
			}
		},
		mounted() {
			this.renderChart(this.chartData, {
				responsive: true,
				legend: {
					display: false,
				},
				"hover": {
					"animationDuration": 0
				},
				"animation": {
					"duration": 1,
					"onComplete": function() {
						var chartInstance = this.chart,
							ctx = chartInstance.ctx;

						ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
						ctx.textAlign = 'center';
						ctx.textBaseline = 'bottom';

						this.data.datasets.forEach(function(dataset, i) {
							var meta = chartInstance.controller.getDatasetMeta(i);
							meta.data.forEach(function(bar, index) {
								var data = dataset.data[index];
								ctx.fillText(data, bar._model.x, bar._model.y - 5);
							});
						});
					}
				},
				title: {
					display: false,
					text: ''
				},
				scaleShowValues: true,
				scales: {
					xAxes: [{
					ticks: {
						autoSkip: false
					}
					}]
				}
			})
		}

	})
</script>
<script>
	var app = new Vue({
		"el": "#app",
		data: {
			fetching: false,
			report: {}
		},
		mounted() {
			this.fetchData();
		},
		computed: {
			total() {
				let sum = {
					fund: 0,
					number: 0
				};
				if (this.report.participants_event) {
					for (let i = 0; i < this.report.participants_event.length; i++) {
						sum.fund += parseFloat(this.report.participants_event[i].fund_collected);
						sum.number += parseFloat(this.report.participants_event[i].number_participant);
					}
				}
				return sum;
			}
		},
		methods: {
			downloadParticipant(event_id, tipe) {
				window.open("<?= base_url("admin/dashboard/download_participant"); ?>/" + event_id + "/" + tipe);
			},
			exportSummary(tipe) {
				$.ajax({
					url: '<?= base_url('admin/dashboard/export'); ?>',
					method: 'POST',
					data: {
						tipe: tipe,
						title: 'Summary Participant of Events',
						data: this.report.participants_event
					},
					xhrFields: {
						responseType: 'blob'
					},
					success: function(data, xhr, s) {
						if (data) {
							var a = document.createElement('a');
							var url = window.URL.createObjectURL(data);
							a.href = url;
							a.download = s.getResponseHeader("filename");
							document.body.append(a);
							a.click();
							a.remove();
							window.URL.revokeObjectURL(url);
						}
					}
				});
			},
			fetchData() {
				var app = this;
				app.fetching = true;
				$.post("<?= base_url('admin/dashboard/data'); ?>", null, function(res) {
					if (res.status) {
						app.report = res.report;
					} else {
						Swal.fire('Fail', "Failed to fetch data !", 'warning');
					}
				}, "JSON").fail(function(xhr) {
					var message = xhr.getResponseHeader("Message");
					if (!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
				}).always(function() {
					app.fetching = false;
				});
			},
			formatCurrency(price) {
				return new Intl.NumberFormat("id-ID", {
					style: 'currency',
					currency: "IDR"
				}).format(price);
			}
		}
	})
</script>
<?php $this->layout->end_script(); ?>