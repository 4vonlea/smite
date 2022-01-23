export default Vue.component("Presentation", {
    template:`
    <div class="col-lg-12">
        <page-loader :loading="loading" :fail="fail"></page-loader>
        <div v-if="!loading">
            <div class="overflow-hidden mb-1">
                <h2 class="font-weight-normal color-heading text-7 mb-0"><strong class="font-weight-extra-bold">Presetation</strong></h2>
            </div>
            <div class="overflow-hidden mb-4 pb-3">
                <p class="mb-0">Displaying poster or presentation files uploaded by paper participants</p>
            </div>
            <div class="row">
            <div class="col-md-6 col-sm-12">
                <input type="text" v-model="globalFilter" class="form-control" placeholder="Please type for search...." @change="doFilter" @keyup="doFilter"/>
            </div>
            <vuetable-pagination 
            ref="pagination"
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
                        <a v-if="isImage(props.rowData.poster)" target="_blank" :href="baseUrl+'file_presentation/'+props.rowData.poster+'/'+props.rowData.id"  class='btn btn-primary magnific'>Lihat
                        </a>
                        <span v-else>
                        <a target="_blank" :href="baseUrl+'file_presentation/'+props.rowData.poster+'/'+props.rowData.id"  class='btn btn-primary'>Lihat
                        </a>
                        </span>
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
            fields:[{name:'poster','title':'File Presentasi'},{name:'fullname',title:'Nama Peunggah'},{name:'title',title:'Judul'},{name:'type',title:'Jenis'}],
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
        isImage(data){
            let extension = data.toLowerCase().split(".");
            if(extension.length > 0){
                return ['jpg','jpeg','png','bmp'].includes(extension[1]);
            }
            return false;
        },
        onPaginationData(paginationData) {
            this.$refs.pagination.setPaginationData(paginationData);
            $("a.magnific").magnificPopup({type:'image'});
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