export default Vue.component("PageWebminar",{
    template:`
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div v-if="!loading">
            	<div class="overflow-hidden mb-1">
                    <h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Webminar Link</strong></h2>
                    <div class="overflow-hidden mb-4 pb-3">
                        <p class="mb-0">Please attend the event that you follow via the link below</p>
                    </div>
                </div>
                <div class="row">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Start Time</th>
                                <th>Topic</th>
                                <th>Link</th>
                            </tr>
                        </thead>
                        <tbody v-if="events.length == 0">
                            <tr>
                                <td class="text-center" colspan="3">You haven't participated in any event </td>
                            </tr>
                        </tbody>
                        <tbody v-for="event in events" :key="event.id">
                            <tr>
                                <td class="font-weight-bold text-center" colspan="3">{{event.name}}</td>
                            </tr>
                            <tr v-if="event.special_link.length == 0">
                                <td  colspan="3">Link for event {{event.name}} not yet available</td>
                            </tr>
                            <tr v-for="link in event.special_link">
                                <td>{{ link.date | formatDate }}</td>
                                <td>{{ link.topic }}</td>
                                <td>
                                    <a :href="link.url" target="_blank" class="btn btn-primary">Join Now</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    `,
    data: function () {
        return {
            loading:false,
            fail:false,
			events: [],
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