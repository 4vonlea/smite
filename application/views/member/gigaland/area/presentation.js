export default Vue.component("Presentation", {
    template:`
    <div class="col-lg-12">
        <page-loader :loading="loading" :fail="fail"></page-loader>
        <div v-if="!loading">
            <div class="overflow-hidden mb-1">
                <h2 class="font-weight-normal color-heading text-7 mb-0"><strong class="font-weight-extra-bold">Presentation Gallery</strong></h2>
            </div>
            <div class="overflow-hidden mb-4 pb-3">
                <p class="mb-0">Displaying poster or presentation files uploaded by call for paper's participant</p>
            </div>
            <div class="row">
            <div class="col-md-6 col-sm-12">
                <input type="text" v-model="globalFilter" class="form-control mb-2" placeholder="Please type for search...." @change="doFilter" @keyup="doFilter"/>
            </div>
            <vuetable-pagination 
            ref="pagination"
            activeClass="btn-primary"
            pageClass="btn-default"
            :css="pagerClass"
            @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>

            <vuetable ref="vuetable"
                :api-mode="false"
                :fields="fields"
                :per-page="perPage"
                :data-manager="dataManager"
                pagination-path="pagination"
                @vuetable:pagination-data="onPaginationData">
                    <template slot="poster" slot-scope="props">
                        <div class="text-center">
                        <a v-if="isImage(props.rowData.poster)" :data-voice="props.rowData.voice" target="_blank" :href="baseUrl+'file_presentation/'+props.rowData.poster+'/'+props.rowData.id"  class='magnific'>
                            <img class="img img-thumbnail" width="160" :src="baseUrl+'file_presentation/'+props.rowData.poster+'/'+props.rowData.id" />
                        </a>
                        <span v-else-if="typeFile(props.rowData.poster) == 'Power Point'">
                            <button class="btn btn-primary" @click="$parent.togglePresentation(props.rowData.poster,props.rowData.voice)">Show File {{ typeFile(props.rowData.poster) }}</button>
                        </span>
                        <a v-else target="_blank" :href="baseUrl+'file_presentation/'+props.rowData.poster+'/'+props.rowData.id"  class='btn btn-primary'>
                            Show File {{ typeFile(props.rowData.poster) }}
                        </a>
                        <p class="mt-2">
                            <button class="btn btn-link" @click="likePoster(props.rowData,props.rowData.liked == '0')">
                                <i v-if="props.rowData.isLoading == '0'" :class="[ props.rowData.liked == '1' ? 'text-danger':'text-white']" class="fa fa-heart fa-2x"></i>
                                <i v-if="props.rowData.isLoading == '1'" class="fa fa-spin fa-spinner text-heart fa-2x"></i>
                            </button>
                            {{ props.rowData.jumlah}} Likes
                        </p>
                        </div>
                    </template>
                </vuetable>
            </div>
        </div>
    </div>
    `,
	created() {
        this.fetchData();
	},
    data:function(){
        return {
            fields:[{name:'poster','title':'Presentation File'},{name:'fullname',title:'Author'},{name:'title',title:'Title'},{name:'type',title:'Type'}],
            loading: false,
            perPage:5,
            fail:false,
            data:[],
            globalFilter:'',
            pagerClass: {
                wrapperClass: 'col-md-6 col-sm-12 btn-group text-center',
                activeClass: 'active btn-primary',
                disabledClass: 'disabled btn-outlined-danger',
                pageClass: 'btn btn-default btn-outlined-primary',
                linkClass: 'btn btn-default btn-outlined-primary',
                icons: {
                    first: '',
                    prev: '',
                    next: '',
                    last: '',
                },
            }
        }
    },
    methods: {
        likePoster(row,isLike){
            row.isLoading = "1";
            $.post(this.appUrl+'member/area/like_presentation',{
                id:row.id,
                action:isLike ? "add":"delete"
            }).done(function(res){
                if(isLike){
                    row.liked = "1";
                    row.jumlah++;
                }else{
                    row.liked = "0";
                    row.jumlah--;
                }
            }).always(function(res){
                row.isLoading = "0";
            }).fail(function(res){
                row.isLoading = "0";
                Swal.fire('Fail',"Failed to give a like", 'error');
            });
        },
        isImage(data){
            let extension = data.toLowerCase().split(".");
            if(extension.length > 0){
                return ['jpg','jpeg','png','bmp'].includes(extension[1]);
            }
            return false;
        },
        typeFile(filename){
            let extension = filename.toLowerCase().split(".");
            if(extension.length > 0){
                if(['ppt','pptx'].includes(extension[1])){
                    return "Power Point";
                }
            }
            return "";
        },
        onPaginationData(paginationData) {
            this.$refs.pagination.setPaginationData(paginationData);
            let comp = this;
            $("a.magnific").magnificPopup({
                type:'image',
                image:{
                    markup:`<div class="mfp-figure">
                                <div class="mfp-close"></div>
                                <div class="mfp-img"></div>
                                <div class="mfp-bottom-bar">
                                    <div class="mfp-title"></div>
                                    <div class="mfp-counter"></div>
                                    <audio controls autoplay muted>
                                        <source id="audioPresentation" src="presentationCover.voiceLink" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                    `,
                    cursor:null,
                },
                callbacks:{
                    open:function(e){
                        $("#audioPresentation").attr("src",comp.baseUrl+"file_presentation/"+this.st.el.data('voice')+"/0");
                    }
                },
                closeOnBgClick:false,
        });
        },
        onChangePage(page) {
            this.$refs.vuetable.changePage(page);
        },
        dataManager(sortOrder, pagination) {
            if (this.data.length < 1) return;  
            let local = this.data;
            if (this.globalFilter) {
                let txt = new RegExp(this.globalFilter, 'i')
                local = _.filter(local, function (item) {
                    return item.fullname.search(txt) >= 0 ||
                    item.title.search(txt) >= 0 ||
                    item.type.search(txt) >= 0                    ;
                })
            }
            if (sortOrder.length > 0) {
              console.log("orderBy:", sortOrder[0].sortField, sortOrder[0].direction);
              local = _.orderBy(
                local,
                sortOrder[0].sortField,
                sortOrder[0].direction
              );
            }
      
            pagination = this.$refs.vuetable.makePagination(
              local.length,
              this.perPage
            );
            let from = pagination.from - 1;
            let to = from + this.perPage;
      
            return {
              pagination: pagination,
              data: _.slice(local, from, to)
            };
          },
        fetchData() {
			var page = this;
			page.loading = true;
			page.fail = false;
			$.get(this.appUrl+"member/area/presentationList")
			.always(function(res){
                if (res.status) {
                    // let temp = {};
                    // temp.data = res.data;
                    // temp.total = res.data.length;
                    // temp.per_page = 10;
                    // temp.current_page = 1;
                    page.data = res.data;
                } else {
                    page.fail = true;
                }
			}).fail(function () {
                page.fail = true;
            }).always(function () {
                page.loading = false;
            });;
        },
        doFilter() {
            Vue.nextTick(() => this.$refs.vuetable.refresh())
        },
    }

});