var template = `
<div>
    <div class="row mb-2 mt-2">
        <div class="col form-inline ml-3">
            <label>
                Show&nbsp;
                <select v-model="pageSize" class="form-control form-control-sm">
                    <option  v-for="c in perPage" :value="c">{{ c }}</option>
                </select>&nbsp;Entries
            </label>
        </div>
        <div class="col mr-3">
            <div class="input-group">
                <input type="text" v-model="globalFilter" @keyup.enter="doFilter"  placeholder="Type to search !" class="form-control" />
                <div class="input-group-append">
                    <button type="button"  v-on:click="doFilter" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    <button type="button" v-on:click="resetFilter" class="btn btn-primary"><i class="fa fa-times"></i> Reset</button>
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sorting
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a v-for="item in sortingList" class="dropdown-item" href="#" @click="setSort(item)">
                            <i v-if="currentOrder.field == item.field && currentOrder.direction == 'asc'" class="fa fa-chevron-up"></i>
                            <i v-if="currentOrder.field == item.field && currentOrder.direction == 'desc'" class="fa fa-chevron-down"></i>
                            {{ item.title }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="position: relative">
        <div v-if="loading" class="bg-color-dark" style="width: 100%;height: 100%;position: absolute; z-index: 1000;background-color: darkgrey;opacity: .5">
            <div class="fa fa-spin fa-cog text-primary fa-4x" role="status" style="position:absolute;opacity:1;top: 45%;left: 45%;">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <vuetable ref="vuetable"
                  :api-url="apiUrl"
                  :fields="fields"
                  :css="css.table"
                  :per-page="pageSize"
                  data-path="data"
                  pagination-path=""
                  :sort-order="sortingOrder"
                  :append-params="paramsQuery"
                  @vuetable:pagination-data="onPaginationData"
                  @vuetable:loading="onLoading"
                  @vuetable:loaded="onLoaded">

            <template v-for="slotName in fieldSlots" :slot="slotName" slot-scope="props">
                <slot :name="slotName" v-bind:row="props.rowData"></slot>
            </template>
        </vuetable>
        <div class="row mt-3 mb-3">
            <vuetable-pagination-info ref="paginationInfo"
                                      no-data-template="No Data Available !" :css="css.info"></vuetable-pagination-info>
            <vuetable-pagination ref="pagination"
                                 :css="css.pagination"
                                 @vuetable-pagination:change-page="onChangePage"
            ></vuetable-pagination>
        </div>
    </div>
</div>
`;
Vue.component('datagrid', {
    template: template,
    components: {
        'vuetable-pagination': Vuetable.VuetablePagination,
        'vuetable-pagination-info': Vuetable.VuetablePaginationInfo
    },
    props: {
        'fields': Array, 'api-url': String, 'sort-order': Array,'sort-list': Array, 'per-page': {
            'type': Array, 'default': function () {
                return [10, 20, 50, 100];
            }
        }
    },
    data: function () {
        return {
            globalFilter:'',
            loading:false,
            pageSize: 10,
            paramsQuery:{fields:this.getFieldName()},
            additionalQuery:{},
            sortingOrder:[],
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
        }
    },
    created(){
        if(this.sortOrder){
            this.sortingOrder = this.sortOrder;
        }
    },
    computed: {
        fieldSlots: function () {
            var field = [];
            var scopeSlots = this.$scopedSlots;
            this.getFieldName().forEach(function (i) {
                if (typeof scopeSlots[i] !== 'undefined')
                    field.push(i);
            });
            return field;
        },
        sortingList(){
            let sorting = [];
            if(this.sortList){
                sorting = this.sortList;
            }else{
                this.fields.forEach(function (row) {
                    if(row.sortField){
                       sorting.push({field:row.sortField,title:row.title ? row.title : row.name});
                    }
                })
            }
            console.log(this.sortList);
            return sorting;
        },
        currentOrder(){
            let current = {field:'',sortField:'',direction:''};
            if(this.sortingOrder.length > 0){
                current = this.sortingOrder[0];
            }
            return current;
        }
    },
    watch: {
        pageSize(newVal, oldVal) {
            this.$nextTick(() => {
                this.$refs.vuetable.refresh();
            });
        },
    },
    methods: {
        getFieldName(){
            var f = [];
            this.fields.forEach(function (row) {
                var i = null;
                if(typeof row == 'object'){
                    i = row.name;
                }else{
                    i = row;
                }
                f.push(i);
            })
            return f;
        },
        onPaginationData(paginationData) {
            paginationData.from = (paginationData.current_page-1)*paginationData.per_page+1;
            paginationData.to = (paginationData.current_page-1)*paginationData.per_page+paginationData.data.length;
            paginationData.last_page = Math.ceil(paginationData.total/paginationData.per_page);
            // paginationData.next_page_url = (paginationData.current_page < paginationData.last_page ? "" :null);
            // paginationData.prev_page_url = (paginationData.current_page > 1 ? "":null);
            this.$refs.pagination.setPaginationData(paginationData);
            this.$refs.paginationInfo.setPaginationData(paginationData);
            this.paginationData = paginationData;
            this.$emit("loaded_data",paginationData);
        },
        onChangePage(page) {
            this.$refs.vuetable.changePage(page)
        },
        onLoading() {
            this.loading = true;
        },
        onLoaded() {
            this.loading = false;
        },
        doFilter () {
        	var split = this.globalFilter.split(";");
        	if(split.length > 1){
        		this.globalFilter = split[1];
			}
            this.paramsQuery = {
                ...this.additionalQuery,
                'global_filter': this.globalFilter,
                'fields':this.getFieldName(),
            }
            Vue.nextTick( () => this.$refs.vuetable.refresh())
        },
        setSort(row){
            let direction = row.field == this.currentOrder.field && this.currentOrder.direction == 'asc' ? 'desc' : 'asc';
            this.sortingOrder = [
                {field:row.field,sortField:row.field, direction:direction}
            ]
            Vue.nextTick( () => this.$refs.vuetable.refresh())    
        },
        refresh(){
            Vue.nextTick( () => this.$refs.vuetable.refresh())
        },
        reload(){
            Vue.nextTick( () => this.$refs.vuetable.reload())
        },
        resetFilter() {
            this.globalFilter = "";
            this.paramsQuery = {
                ...this.additionalQuery,
                'fields':this.getFieldName(),
            }
            Vue.nextTick( () => this.$refs.vuetable.refresh())
        }
    }
});
