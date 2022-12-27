export default Vue.component("Presentation", {
    template:`
    <div class="cs-iconbox cs-style1 cs-white_bg">
        <page-loader :loading="loading" :fail="fail"></page-loader>
        <div v-if="!loading">
            <div class="overflow-hidden mb-1">
                <p class="font-weight-normal mb-0" style="font-size: 30px;"><strong class="font-weight-extra-bold">Presentation Gallery</strong></p>
            </div>
            <div class="overflow-hidden mb-4 pb-3">
                <p class="mb-0">Displaying poster or presentation files uploaded by call for paper's participant</p>
            </div>
            <div class="row justify-content-between">
            <div class="col-md-2 col-sm-3">
                <select class="form-select form-select-sm" v-model.number="perPage" style="padding:.375rem .75rem">
                    <option value="5">Show 5 </option>
                    <option value="10">Show 10 </option>
                    <option value="25">Show 25 </option>
                    <option value="50">Show 50 </option>
                    <option value="100">Show 100 </option>
                </select>
            </div>
            <div class="col-md-4 col-sm-9">
                <input type="text" v-model="globalFilter" class="form-control mb-2" placeholder="Please type for search...." @change="doFilter" @keyup="doFilter"/>
            </div>

            <vuetable ref="vuetable"
                :api-mode="false"
                :fields="fields"
                :per-page="perPage"
                :data-manager="dataManager"
                pagination-path="pagination"
                :css="css.table"
                @vuetable:pagination-data="onPaginationData">
                    <template slot="poster" slot-scope="props">
                        <div class="text-center">
                        <a v-if="isImage(props.rowData.poster)" :data-voice="props.rowData.voice" :data-id="props.rowData.id" target="_blank" :href="baseUrl+'file_presentation/'+props.rowData.poster+'/'+props.rowData.id"  class='magnific'>
                            <img class="img img-thumbnail" width="160" :src="baseUrl+'file_presentation/'+props.rowData.poster+'/'+props.rowData.id" />
                        </a>
                        <span v-else-if="typeFile(props.rowData.poster) == 'Power Point'">
                            <button class="btn btn-primary" @click="$parent.togglePresentation(props.rowData.poster,props.rowData.voice,props.rowData.id)">Show File {{ typeFile(props.rowData.poster) }}</button>
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
                <vuetable-pagination 
                ref="pagination"
                :css="pagerClass"
                @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
    
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
            css:{
                table:{
                    tableHeaderClass:"text-center bg-success",
                    tableClass:"table table-bordered text-light dataTable no-footer"
                }
            },
            pagerClass: {
                wrapperClass: 'offset-md-6 col-md-6 col-sm-12 btn-group text-center mb-2',
                activeClass: 'active bg-primary',
                disabledClass: 'disabled btn-outlined-danger',
                pageClass: 'btn btn-success',
                linkClass: 'btn btn-success',
                icons: {
                    first: '',
                    prev: '',
                    next: '',
                    last: '',
                },
            }
        }
    },
    watch:{
        perPage:(val) => {
            Vue.nextTick(() => {
                this.onChangePage(1);
            });
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
                        $("#audioPresentation").attr("src",comp.baseUrl+"file_presentation/"+this.st.el.data('voice')+"/"+this.st.el.data('id'));
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