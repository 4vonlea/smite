<?php
/**
 * @var $report
 */
?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml"
	 xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>
<div class="container-fluid mt--7">

	<transition name="fade">
		<div v-if="mode == 0" class="row">
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
									<button type="button" @click="downloadReport(row)" class="btn btn-primary">Download
										Report
									</button>
									<button type="button" @click="detailPresence(row)" class="btn btn-primary">Detail
										Presence
									</button>
								</td>
							</tr>
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
		<div v-if="mode == 1" class="row">
			<div class="col-md-12">
				<div class="card shadow border-0">
					<div class="card-header bg-white pb-5">
						<div class="row">
							<div class="col-6">
								<h3>Presence Check Event : {{ pageCheck.event.name }} </h3>
							</div>
							<div class="col-6 text-right ">
								<div class="form-inline" style="justify-content: end">
									<label class="label">Mode Scanner : </label>&nbsp;
									<?=form_dropdown('mode',['1'=>'On Web Scanner','2'=>'QR Scanner Device'],'1',['v-model'=>'checkMode','class'=>'form-control','@change'=>'switchMode']);?>
									&nbsp;<button type="button" class="btn btn-primary" @click="back">Back</button>
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table">
							<tr>
								<th>Number of Participant :</th>
								<td>{{ pageCheck.numberParticipant }}</td>
								<th>Number of Presence :</th>
								<td>{{ pageCheck.presence }}</td>
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
						<video style="border:solid 3px;max-width: 500px;width: 100%" muted playsinline id="qr-video"
							   ref="qrVideo"></video>
					</div>
				</div>
			</div>
		</div>
		<div v-if="mode == 2" class="row">
			<div class="col-md-12">
				<div class="card shadow border-0">
					<div class="card-header bg-white pb-5">
						<div class="row">
							<div class="col-6">
								<h3>Presence List - {{ detail.event.name }} </h3>
							</div>
							<div class="col-4">

							</div>
							<div class="col-2">
								<button ref="btnReload" @click="fetchDetail" class="btn btn-primary">Reload Data</button>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row col-md-12">
							<h4>Summary</h4>
						</div>
						<div class="table-responsive">
							<table class="table">
								<thead>
								<tr>
									<th>Date</th>
									<th>Presence</th>
									<th>Absense</th>
									<th>Total Participant</th>
								</tr>
								</thead>
								<tbody>
								<tr v-for="col in dateAbsence">
									<td>{{ col.replace("_"," ").toUpperCase() }}</td>
									<td>{{ detail.summary[col].presence }}</td>
									<td>{{ detail.summary[col].absence }}</td>
									<td>{{ detail.summary[col].presence+detail.summary[col].absence }}</td>
								</tr>
								</tbody>
							</table>
						</div>

						<hr/>
						<div class="row mb-2">
							<div class="col-6">
								<h4>Detail</h4>
							</div>
						</div>

						<div class="table-responsive">
							<div class="row mb-2 mt-2">
								<div class="col form-inline ml-3">
									<label>
										Show&nbsp;
										<select v-model="pageSize" class="form-control form-control-sm">
											<option v-for="c in perPage" :value="c">{{ c }}</option>
										</select>&nbsp;Entries
									</label>
								</div>
								<div class="col mr-3">
									<div class="input-group">
										<input type="text" v-model="globalFilter" @keyup.enter="doFilter"
											   placeholder="Type to search !" class="form-control"/>
										<div class="input-group-append">
											<button type="button" v-on:click="doFilter" class="btn btn-primary"><i
													class="fa fa-search"></i> Search
											</button>
											<button type="button" v-on:click="resetFilter" class="btn btn-primary"><i
													class="fa fa-times"></i> Reset
											</button>
										</div>
									</div>

								</div>
							</div>
							<div style="position: relative">
								<div v-if="loading" class="bg-color-dark"
									 style="width: 100%;height: 100%;position: absolute; z-index: 1000;background-color: darkgrey;opacity: .5">
									<div class="fa fa-spin fa-cog text-primary fa-4x" role="status"
										 style="position:absolute;opacity:1;top: 45%;left: 45%;">
										<span class="sr-only">Loading...</span>
									</div>
								</div>
								<vuetable ref="vuetable"
										  :api-mode="false"
										  :fields="detail.column"
										  :data="detail"
										  :data-total="dataCount"
										  :data-manager="dataManager"
										  data-path="data"
										  pagination-path="pagination"
										  :per-page="pageSize"
										  :css="css.table"
										  @vuetable:pagination-data="onPaginationData">
								</vuetable>
								<div class="row mt-3 mb-3">
									<vuetable-pagination-info ref="paginationInfo"
															  no-data-template="No Data Available !"
															  :css="css.info"></vuetable-pagination-info>
									<vuetable-pagination ref="pagination"
														 :css="css.pagination"
														 @vuetable-pagination:change-page="onChangePage"
									></vuetable-pagination>
								</div>
						</div>

					</div>
					<div class="card-footer text-right">
						<button type="button" class="btn btn-primary" @click="mode = 0">Close</button>
					</div>
				</div>
			</div>
		</div>
	</transition>
</div>
<?php $this->layout->begin_script(); ?>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>

<script type="module">
    import QrScanner from "<?=base_url("themes/script/qr-scanner.min.js");?>";

    QrScanner.WORKER_PATH = '<?=base_url("themes/script/qr-scanner-worker.min.js");?>';

	var BarcodeScanerEvents = function() {
		this.initialize.apply(this, arguments);
	};

	BarcodeScanerEvents.prototype = {
		initialize : function() {
			$(document).on({
				keyup : $.proxy(this._keyup, this)
			});
		},
		_timeoutHandler : 0,
		_inputString : '',
		_keyup : function(e) {
			console.log(e);
			if (this._timeoutHandler) {
				clearTimeout(this._timeoutHandler);
			}
			this._inputString += String.fromCharCode(e.which);

			this._timeoutHandler = setTimeout($.proxy(function() {
				if (this._inputString.length <= 3) {
					this._inputString = '';
					return;
				}

				$(document).trigger('onbarcodescaned', this._inputString);

				this._inputString = '';

			}, this), 20);
		}
	};

	BarcodeScanerEvents.initialize;

    var scanner = null;
    var timeOut = null;
    var app = new Vue({
        el: "#app",
        data: {
            mode: 0,
            pageCheck: {lastResult: "None"},
			checkMode:2,
            report:<?=json_encode($report);?>,
            detail: {event: {}, data: [], column: [],summary:{}},
            pageSize: 10,
            globalFilter: '',
            perPage: [10, 20, 50, 100],
            loading: false,
            dataCount: 0,
            css: {
                table: {
                    tableWrapper: '',
                    tableHeaderClass: 'mb-0',
                    tableBodyClass: 'mb-0',
                    tableClass: 'table table-bordered table-stripped table-hover table-grid',
                    loadingClass: 'loading',
                    ascendingIcon: 'fa fa-chevron-up',
                    descendingIcon: 'fa fa-chevron-down',
                    ascendingClass: 'sorted-asc',
                    descendingClass: 'sorted-desc',
                    sortableIcon: 'fa fa-sort',
                    detailRowClass: 'vuetable-detail-row',
                    handleIcon: 'fa fa-bars text-secondary',
                    renderIcon(classes, options) {
                        return `<i class="${classes.join(' ')}"></span>`
                    }
                },
                info: {
                    infoClass: 'pl-4 col'
                },
                pagination: {
                    wrapperClass: 'col pagination',
                    activeClass: 'active',
                    disabledClass: 'disabled',
                    pageClass: 'btn btn-border',
                    linkClass: 'btn btn-border',
                    icons: {
                        first: '',
                        prev: '',
                        next: '',
                        last: '',
                    },
                }
            },
        },
        computed: {
            presence() {
                var i = 0;
                $.each(this.pageCheck.data, function (j, r) {
                    if (r.presence_at)
                        i++;
                });
                return i;
            },
			presenceData(){
                var filter = this.detail.filter;
                if(filter != "")
                    return this.detail.data.filter(function (row) {
						return row.fullname.toUpperCase().includes(filter.toUpperCase());
                    });
                return this.detail.data;
			},
			dateAbsence(){
                return this.detail.column.filter(function (row) {
					return row != "fullname" && row != "status_member" && row != "no";
                });
			}
        },
        methods: {
            dataManager(sortOrder, pagination) {
                let data = this.detail.data;
                // account for search filter
                if (this.globalFilter) {
                    // the text should be case insensitive
                    let txt = new RegExp(this.globalFilter, 'i')

                    // search on name, email, and nickname
                    data = _.filter(data, function (item) {
                        return item.fullname.search(txt) >= 0 || item.status_member.search(txt) >= 0
                    })
                }
                if (sortOrder.length > 0) {
                    data = _.orderBy(data, sortOrder[0].sortField, sortOrder[0].direction)
                }
                pagination = this.$refs.vuetable.makePagination(data.length)
                return {
                    pagination: pagination,
                    data: _.slice(data, pagination.from - 1, pagination.to)
                }
            },
            onPaginationData(paginationData) {
                this.$refs.pagination.setPaginationData(paginationData);
                this.$refs.paginationInfo.setPaginationData(paginationData);
            },
            onChangePage(page) {
                this.$refs.vuetable.changePage(page);
            },
            doFilter() {
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            },
            resetFilter() {
                this.globalFilter = "";
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            },
            back() {
                app.pageCheck = {lastResult: "None"};
               	app.closeWebScanner();
				this.mode = 0;
			},
            fetchDetail() {
                var app = this;
                var btn = this.$refs.btnReload;
                this.detail.filter = "";
                if(btn) {
                    btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
                    btn.setAttribute("disabled", true);
                }
                $.post("<?=base_url('admin/presence/get_detail');?>", {id: this.detail.event.id_event}, function (res) {
                    app.detail.data = res.data;
                    app.detail.column = res.column;
                    app.mode = 2;
                    var summary = {};
                    var date = app.dateAbsence;
                    $.each(res.data,function (i,v) {
						$.each(date,function (i,j) {
							var value = v[j]
							if(v[j] != "-"){
							    if(summary[j])
								    summary[j]['presence']++;
							    else
                                    summary[j] = {'presence':1,'absence':0}
							}else{
                                if(summary[j])
                                    summary[j]['absence']++;
                                else
                                    summary[j] = {'presence':0,'absence':1}
							}
                        });
                    });
                    app.detail.pagination = {
                        "total": res.data.length,
                            "per_page": 10,
                            "current_page": 1,
                            "last_page": Math.ceil(res.data.length / 10),
                            "next_page_url": null,
                            "prev_page_url": null,
                            "from": 1,
                            "to": 10,
                    };
                    app.detail.summary = summary;
                }).always(function () {
                    if (btn) {
                        btn.innerHTML = "Reload Data";
                        btn.removeAttribute("disabled");
                    }
                }).fail(function (xhr) {
					var message =  xhr.getResponseHeader("Message");
					if(!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
                });
            },
			switchMode(){
				if(this.checkMode == 1){
					this.closeDeviceListener();
					this.openWebScanner();
				}else if(this.checkMode == 2){
					this.closeWebScanner();
					this.openDeviceListener();
				}
			},
			openDeviceListener(){
				$(document).on( "onbarcodescaned", function(inputString) {
					console.log(inputString);
				});
            	// var UPC = '';
				// document.addEventListener("keydown", function(e) {
				// 	const textInput = e.key || String.fromCharCode(e.keyCode);
				// 	UPC = UPC+textInput;
				// 	console.log(UPC);
				// });
			},
			closeDeviceListener(){
				$(document).off( "onbarcodescaned");
			},
			closeWebScanner(){
				if (scanner) {
					scanner.stop();
					scanner = null;
				}
			},
			openWebScanner(){
				const video = app.$refs.qrVideo;
				const camHasCamera = app.$refs.camHasCamera;

				function setResult(result) {
					var rs = result.split(";");
					if(rs.length > 1)
						result = rs[1];
					if (app.pageCheck.lastResult != result) {

						var found = false;
						$.each(app.pageCheck.data, function (i, r) {
							if (r.id == result) {
								found = true;
								var date = new Date();
								if(!r.presence_at)
									app.pageCheck.presence++;
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
						if (found == false) {
							Swal.fire("Info", "Participant not register on this event !", "info");
						}else{
							app.$forceUpdate();
						}
						app.pageCheck.lastResult = result;
						clearTimeout(timeOut);
						timeOut = setTimeout(() => app.pageCheck.lastResult = "None", 5000);
					}
				}
				QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);
				if(!scanner)
					scanner = new QrScanner(video, result => setResult(result));
				scanner.start();
				scanner.setInversionMode("both");
			},
            detailPresence(row) {
                this.detail.event = row;
				app.fetchDetail();
            },
            downloadReport(row) {
                window.open("<?=base_url('admin/presence/report');?>/" + row.id_event);
            },
            presencePage(row, ev) {
                ev.target.innerHTML = "<i class='fa fa-spin fa-spinner'></i> Loading Data . .";
                $.post("<?=base_url('admin/presence/get_data');?>", {id: row.id_event}, function (res) {
                    if (res.status) {
                        app.pageCheck.data = res.data;
                        app.mode = 1;
                        app.pageCheck.event = {id: row.id_event, name: row.name};
                        app.pageCheck.numberParticipant = row.number_participant;
						app.pageCheck.presence = app.presence;
                        Vue.nextTick(function () {
                        	app.switchMode();
                        });
                    } else {
                        Swal.fire("Failed", "Failed to load data !", "error");
                    }
                }, "JSON").always(function () {
                    ev.target.innerHTML = "Check";
                }).fail(function (xhr) {
					var message =  xhr.getResponseHeader("Message");
					if(!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
                });
            }
        }
    })
</script>
<script type="module">


</script>
<?php $this->layout->end_script(); ?>

