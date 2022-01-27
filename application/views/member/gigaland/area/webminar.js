export default Vue.component("PageWebminar", {
    template: `
        <div class="col-lg-12">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div v-if="!loading">
            	<div class="overflow-hidden mb-1">
                    <h2 class="font-weight-normal color-heading text-7 mb-0"><strong class="font-weight-extra-bold">Webinar Link</strong></h2>
                    <div class="overflow-hidden mb-4 pb-3">
                        <p class="mb-0">Please attend the event you participate via the link below</p>
                    </div>
                </div>
                <div class="row table-responsive">
                    <p class="font-weight-bold h5">
                        *Join button cannot be clicked until 5 minutes before start time
                        and not clickable unless you have watched the sponsor
                        <br/>
                        *<span class="badge badge-success h4" style="font-size:14px">{{ jam }}</span> (GMT +7) 
                    </p>
                    <p class="font-weight-bold h5">When the symposium has met the quota, please move to another symposium</p>
                    <!--<p class="font-weight-bold h5">
                        Presentation Material can be access here:
                        <a href="https://drive.google.com/drive/folders/1Wi7t64mOIq_WGD-v7GS7G3dL6CZo8DGr?usp=sharing" target="_blank">heree</a>
                    </p>-->
                    <table class="table text-light">
                        <tbody v-if="events.length == 0">
                            <tr>
                                <td class="text-center" colspan="3">You haven't participated in any events yet </td>
                            </tr>
                        </tbody>
                        <template v-for="event in events">
                            <tr>
                                <td class="font-weight-bold text-center" colspan="3">
                                    {{event.name}}
                                    <hr/>
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li v-for="(d,i) in groupDate(event.special_link)" class="nav-item">
                                            <a :class="{active: d.active}" class="nav-link" data-toggle="pill" :href="'#tabs-'+event.id+'-'+d.index" role="tab" aria-controls="pills-home" aria-selected="true">
                                                {{ i }}
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr v-if="event.special_link.length == 0">
                                <td  colspan="3">Link for event {{event.name}} not yet available</td>
                            </tr>
                            <tr>
                                <td class="tab-content" colspan="3">
                                    <table v-for="(d,i) in groupDate(event.special_link)" :id="'tabs-'+event.id+'-'+d.index" class="table tab-pane fade text-light">
                                        <thead>
                                            <tr>
                                                <th>Start Time</th>
                                                <th>Room</th>
                                                <th width="20%">Link</th>
                                            </tr>
                                        </thead>
                                        <template v-for="(link,indSpl) in d.items">
                                            <tr :class="{'bg-danger':link.showingClass == 'badge badge-danger'}">
                                                <td>
                                                <span style="font-size:14px" class="badge" :class="link.showingClass">{{ link.date | formatDate }}{{ link.dateEnd | formatHour }}</span>
                                                </td>
                                                <td>
                                                    <span v-html="link.room"></span>
                                                    <button data-status='hide' @click="toggle" class="float-right btn btn-sm btn-default" data-toggle="collapse" :data-target="'#speakers_'+event.id+'_'+indSpl+d.index">
                                                        Show Speaker <i class="fa fa-caret-right"></i>
                                                    </button>
                                                </td>
                                                <td :rowspan="2" class="" style="white-space: nowrap;">
                                                    <button :disabled="(link.finishWatch == '0' && link.advertisement) || link.url == '#' || link.timeReady " v-on:click="join(link.url)" class="btn btn-primary btn-block">
                                                    {{ link.showingClass == 'badge badge-success' ? 'Live Now' : 'Join Now' }}
                                                    </button>
                                                    <button v-show="link.urlRecording" @click="showRecording(link)" class="btn btn-primary btn-block">
                                                    View Recordings
                                                    </button>
                                                    <button v-for="(ads,index) in link.advertisement" class="btn btn-block" :class="[ads.watch == '1' ? 'btn-primary':'btn-default']" v-on:click="showAds(index,link,indSpl)">
                                                        View Sponsor {{ index+1}}
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr >
                                                <td colspan="2" :id="'speakers_'+event.id+'_'+indSpl+d.index" class="collapse">
                                                    <ul class="list-group list-group-flush">
                                                        <li v-for="sp in link.speakers" class="list-group-item d-flex">
                                                            <div class="d-flex mr-2">
                                                                <img class="img-thumbnail rounded" style="max-width:80px"  :src="sp.image ? sp.image:appUrl+'/themes/uploads/people.jpg'" alt="Card image cap">
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <h5 style="margin-bottom:0px">{{ sp.topic}}</h5>
                                                                <span>{{ sp.name }}</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </template>
                                    </table>
                                </td>
                            </tr>
                        </template>
                    </table>
                </div>
                <div id="modal-ads" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sponsor Advertisement</h5>
                            <button v-show="modalCloseButton" type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <span style="font-size:100%" v-show="!modalCloseButton" class="badge badge-info">{{timer}}</span>
                        </div>
                        <div class="modal-body">
                                <iframe v-if="ads.type == 'link'" width="100%" :src="ads.url" height="500" />
                                <video v-if="ads.type == 'file'" width="100%" height="500" autoplay>
                                    <source :src="ads.url" type="video/mp4">
                                </video> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
            <div id="modal-recording" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Recording : {{ recording.title }}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <iframe :src="recording.src" height="500" style="width:100%" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>   
            </div>
               
        </div>
    `,
    data: function () {
        return {
            loading: false,
            fail: false,
            events: [],
            ads: {},
            minuteWait: 15,
            modalCloseButton: false,
            timer: 10,
            jam: '',
            recording: {
                src: '',
                title: '',
            },
        }
    },
    filters: {
        formatDate: function (val) {
            return moment(val).format("DD MMMM YYYY [At] HH:mm");
        },
        formatHour: function (val) {
            if (val)
                return " s.d. " + moment(val).format("HH:mm");
            return ""
        },

    },
    created() {
        this.fetchEvents();
        let app = this;
        setInterval(function () {
            app.jam = moment().tz('Asia/Jakarta').format("DD MMMM YYYY [At] HH:mm:ss");
            $.each(app.events, function (i, event) {
                $.each(event.special_link, function (i, v) {
                    v.timeReady = app.more5Minutes(v.date);
                    v.showingClass = app.currentClass(v);
                })
            });
        }, 1000)
    },
    watch: {
        '$route': 'fetchEvents'
    },
    methods: {
        currentClass(link) {
            if (link.dateEnd) {
                let date = moment.tz(link.date, 'Asia/Jakarta');
                let dateEnd = moment.tz(link.dateEnd, 'Asia/Jakarta');
                if (moment().tz('Asia/Jakarta').isBetween(date, dateEnd))
                    return "badge badge-success";
                else if (moment().tz('Asia/Jakarta').isSameOrAfter(dateEnd)) {
                    return "badge badge-danger";
                }
            }
            return "";
        },
        groupDate(special_link) {
            let listDate = {};
            let ind = 0
            $.each(special_link, function (i, v) {
                let date = moment(v.date).format("DD MMMM YYYY");
                if (!listDate[date]) {
                    listDate[date] = {
                        index: ind,
                        items: []
                    };
                    ind++;
                }
                listDate[date].items.push(v);
            });
            return listDate;
        },
        more5Minutes(date) {
            // var duration = moment.duration(moment.tz(date,'Asia/Jakarta').diff(moment().tz('Asia/Jakarta')));
            // console.log(duration.asSeconds(),duration.asSeconds() >  60*this.minuteWait,60*this.minuteWait);
            // console.log(moment.tz(date,'Asia/Jakarta').unix() - moment().tz('Asia/Jakarta').unix());
            return moment.tz(date, 'Asia/Jakarta').unix() - moment().tz('Asia/Jakarta').unix() > 60 * this.minuteWait
        },
        toggle(evt) {
            if (evt.currentTarget.dataset.status == 'hide') {
                evt.currentTarget.dataset.status = 'show';
                evt.currentTarget.innerHTML = `Hide Speaker <i class="fa fa-caret-down"></i>`
            } else {
                evt.currentTarget.dataset.status = 'hide';
                evt.currentTarget.innerHTML = `Show Speaker <i class="fa fa-caret-right"></i>`;
            }
        },
        showRecording(data) {
            this.recording.src = data.urlRecording;
            this.recording.title = data.room;
            $("#modal-recording").modal({ backdrop: 'static', keyboard: false });
            $("#modal-recording").modal("show");
        },
        showAds(index, linkOfSpecial) {
            this.modalCloseButton = false;
            this.ads = linkOfSpecial.advertisement[index];
            var xhttp = new XMLHttpRequest();
            xhttp.open('HEAD', this.ads.url);
            xhttp.onreadystatechange = function () {
                if (this.readyState == this.DONE) {
                    console.log(this.getResponseHeader("Content-Type"));
                }
            };
            xhttp.send();
            $("#modal-ads").modal({ backdrop: 'static', keyboard: false });
            $("#modal-ads").modal("show");
            this.timer = 10;
            var v = this;
            var t = setInterval(function () {
                v.timer--;
                if (v.timer <= 0) {
                    v.modalCloseButton = true;
                    clearInterval(t);
                    var finish = true;
                    v.ads.watch = "1";
                    $.each(linkOfSpecial.advertisement, function (i, val) {
                        finish = finish && val.watch == "1";
                    });
                    if (finish)
                        linkOfSpecial.finishWatch = "1";
                }
            }, 1000);
        },
        join(url) {
            window.open(url, "_blank");
        },
        fetchEvents() {
            var page = this;
            page.loading = true;
            page.fail = false;
            $.post(this.baseUrl + "get_events", null, function (res) {
                if (res.status) {
                    let eventsFollowed = [];
                    $.each(res.events, function (i, event) {
                        if (event.followed) {
                            $.each(event.special_link, function (i, v) { 
                                if(!v.speakers)                             
                                    v.speakers = [];
                                v.timeReady = page.more5Minutes(v.date);
                                v.showingClass = page.currentClass(v);
                                $.get(page.appUrl + `/themes/uploads/speaker/${event.id}${i}.json`, function (res) {
                                    v.speakers = res;
                                });
                            })
                            eventsFollowed.push(event);
                        }
                    });
                    page.events = eventsFollowed;
                } else {
                    page.fail = true;
                }
            }).fail(function () {
                page.fail = true;
            }).always(function () {
                page.loading = false;
            });
        },
    }
});
