export default Vue.component("PageSertifikat",{
    template:`
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div v-if="!loading">
            	<div class="overflow-hidden mb-1">
                    <h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Download Sertifikat</strong></h2>
                    <div class="overflow-hidden mb-4 pb-3">
                        <p class="mb-0">Silakan download sertifikat anda</p>
                    </div>
                </div>
                
                <div class="row">
                    <!--
                    <div class="alert alert-success text-center">
                        <h4>
                            Mohon berkenan mengisi kuesioner melalui link berikut<br/><br/>
                            <a @click="klikKuesioner = 1" target="_blank" href="https://forms.gle/TWuc3ubh9nCQfKrp8">Isi Kuesioner</a>
                        </h4>
                        <p>Tombol download sertifikat dapat diklik setelah mengisi kuesioner</p>
                    </div>
                    -->
                    <div class="card-group col-md-12">
                        <div v-for="event in events" :key="event.id" class="card w-50">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ event.name }}</h5>
                                <p class="card-text">Terima kasih atas partisipasi anda, Silakan download sertifikat anda dengan menekan tombol dibawah</p>
                                <button disabled v-if="klikKuesioner == 0" class="btn btn-default">Download Sertifikat</button>
                                <a v-if="klikKuesioner == 1" class="btn btn-primary" :href="'<?=base_url('member/area/certificate');?>/'+event.id+'/'+user.id" target="_blank">Download Sertifikat</a>
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
            klikKuesioner:1,
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
