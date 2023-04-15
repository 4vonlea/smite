let templateEspay = `
    <div>
        <button type="button" class="btn btn-primary">Online Payment</button>
        <div class="modal fade show" tabindex="-1" aria-labelledby="exampleModalLiveLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Pilih Channel Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <div v-for="row in merchantList" class="col">
                            <div class="card p-2" style="cursor:pointer" @click="onSelect(row)" :class="{'border border-5 border-success':row.bankCode == selectedBank}">
                                <img style="height:50px;width:auto;" :src="logoUrl+row.bankCode+'.png'" class="card-img-top" :alt="row.productName">
                                <div class="card-body">
                                    <h5 class="card-title text-center text-dark">{{ row.productName }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
        </div>
    </div>
`;

Vue.component('espay', {
    template: templateEspay,
    props: {
        'invoice': String,
        'merchantUrl': String,
        'logoUrl':String
    },
    data: () => {
        return {
            bankCode: null,
            merchantList:[],
            selectedBank:null,
        }
    },
    mounted(){
        this.fetchMerchant();
    },
    methods:{
        onSelect(row){
            this.selectedBank = row.bankCode;
        },
        fetchMerchant(){
            $.get(this.merchantUrl,(res)=>{
                if(res.error_code == "0000"){
                    this.merchantList = res.data
                }
            })
        }
    }
});