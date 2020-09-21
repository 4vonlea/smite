export default Vue.component("PageWebminar",{
    template:`
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div v-if="!loading">
            	<div class="overflow-hidden mb-1">
                    <h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Webinar Link</strong></h2>
                    <div class="overflow-hidden mb-4 pb-3">
                        <p class="mb-0">Silakan hadiri acara yang Anda ikuti melalui link di bawah ini</p>
                      
                    </div>
                </div>
                <div class="row">
                    <p>
                        *Tombol gabung tidak dapat diklik hingga 5 menit sebelum waktu mulai
                        dan tidak dapat diklik kecuali Anda telah menonton sponsor
                        <br/>
                        *WIB (GMT +7)
                    </p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Waktu Mulai</th>
                                <th>Room</th>
                                <th width="20%">Link</th>
                            </tr>
                        </thead>
                        <tbody v-if="events.length == 0">
                            <tr>
                                <td class="text-center" colspan="3">Anda belum berpartisipasi dalam acara apa pun </td>
                            </tr>
                        </tbody>
                        <tbody v-for="event in events" :key="event.id">
                            <tr>
                                <td class="font-weight-bold text-center" colspan="3">{{event.name}}</td>
                            </tr>
                            <tr v-if="event.special_link.length == 0">
                                <td  colspan="3">Link for event {{event.name}} not yet available</td>
                            </tr>
                            <template v-for="(link,indSpl) in event.special_link">
                                <tr>
                                    <td>{{ link.date | formatDate }}</td>
                                    <td>{{ link.room }}</td>
                                    <td :rowspan="2" class="">
                                        <button :disabled="link.finishWatch == '0' && link.advertisement" v-on:click="join(link.url)" class="btn btn-primary btn-block">Gabung Sekarang</button>
                                        <button v-for="(ads,index) in link.advertisement" class="btn btn-block" :class="[ads.watch == '1' ? 'btn-primary':'btn-default']" v-on:click="showAds(index,link,indSpl)">
                                            Lihat Sponsor {{ index+1}}
                                        </button>
                                    </td>
                                </tr>
                                <tr >
                                    <td colspan="2">
                                        <div class="card-deck">
                                            <div v-for="sp in link.speakers" div class="card" style="max-width:200px;">
                                                <div class="card-header">{{sp.topic}}</div>
                                                <img v-if="sp.image" class="card-img-top" style="max-width:200px;max-height:200px" :src="sp.image" alt="Card image cap">
                                                <div class="card-body">
                                                    <p class="card-text">{{sp.name}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="modal-ads" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sponsor Advertisement</h5>
                            <button v-show="modalCloseButton" type="button" class="close" data-dismiss="modal" aria-label="Close">
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
        </div>
    `,
    data: function () {
        return {
            loading:false,
            fail:false,
            events: [],
            ads:{},
            modalCloseButton:false,
            timer:60,
		}
    },
    filters:{
        formatDate:function(val){
            return moment(val).format("DD MMMM YYYY [At] HH:mm");
        }
    },
    created() {
		this.fetchEvents()
	},
	watch: {
		'$route': 'fetchEvents'
	},
    methods:{
        showAds(index,linkOfSpecial){
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
            $("#modal-ads").modal({backdrop:'static',keyboard:false});
            $("#modal-ads").modal("show");
            this.timer = 15;
            var v = this;
            var t = setInterval(function(){
                v.timer--;
                if(v.timer <= 0){
                    v.modalCloseButton = true;
                    clearInterval(t);
                    var finish = true;
                    v.ads.watch = "1";
                    $.each(linkOfSpecial.advertisement,function(i,val){
                        finish = finish && val.watch == "1";
                    });
                    if(finish)
                        linkOfSpecial.finishWatch = "1";
                }
            },1000);
        },
        join(url){
            window.open(url,"_blank");
        },
        fetchEvents() {
			var page = this;
			page.loading = true;
			page.fail = false;
			$.post(this.baseUrl + "get_events", null, function (res) {
				if (res.status) {
                    let eventsFollowed = [];
					$.each(res.events,function(i,event){
                        if(event.followed){
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
