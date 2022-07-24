let template = `
<div>
    <div class="row">
        <div class="d-flex flex-row align-items-end">
            <div class="me-2">
                <label>Check-in</label><br/>
                <date-picker :disabled-date="disabledDate" :formatter="momentFormat" v-model="form.checkin" />
            </div>
            <div class="me-2">
                <label>Check-out</label><br/>
                <date-picker :disabled-date="disabledDate" :formatter="momentFormat" v-model="form.checkout" />
            </div>
            <span v-if="night" class="badge bg-info me-2">{{ night }} Malam</span>
            <button type="button" @click="search" :disabled="searching" class="btn btn-primary">
                <i v-if="searching" class="fa fa-spin fa-spinner"></i>
                Cari Hotel
            </button>
            <div class="dropdown show">
            <button type="button" @click="showBooking= !showBooking" class="btn btn btn-primary ms-1">
                Pesanan Anda 
                <span class="badge bg-info bg-primary">{{ booking.length }}</span>
                <i class="fa" :class="{'fa-chevron-right':!showBooking,'fa-chevron-down':showBooking}"></i>
            </button>
            </div>
        </div>
    </div>
    <hr/>
    <div class="row mb-3">
        
    </div>
    <div class="row">
        <div class="col">
            <div v-if="hotels && hotels.length == 0" class="alert alert-info text-center">
                <p>Mohon Maaf tidak tersedia Hotel pada tanggal yang anda pilih</p>
            </div>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div v-if="showBooking"  class="col-md-12">
            <div class="card">
                <div class="card-header  bg-info">
                    <p class="card-title text-dark fw-bold">Hotel Pesanan Anda</p>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li v-for="(room,ind) in bookingData" class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 text-dark">{{ room.hotel_name }}</h5>
                                <div v-if="onDelete" clas="col-2">
                                    <button type="button" @click="onDelete(ind,room)" class="btn btn-primary btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <span v-else class="badge bg-info">{{ room.status_payment }}</span>
                            </div>
                            <p>
                                {{ room.name }},
                                <i class="fa fa-moon-o"></i> {{ calculateNight(room.checkin,room.checkout) }} Malam,
                                <i class="fa fa-calendar"></i> {{ formatDate(room.checkin) }} - {{ formatDate(room.checkout) }}
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div v-for="hotel in hotels" class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-dark text-center">{{ hotel.hotel_name }}</h5>
                </div>
                <div class="card-body">
                    <ul class="fa-ul">
                        <li class="card-text">
                            <i class="fa-li fa fa-map-marker"></i> {{ hotel.address }}
                        </li>
                        <li class="card-text">
                            <i class="fa-li fa fa-bed"></i> {{ hotel.name }}, {{ hotel.description }} 
                        </li>
                        <li class="card-text">
                            <i class="fa-li fa fa-money"></i> {{ formatCurrency(hotel.price) }}/malam
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <button type="button" @click="bookHotel($event,hotel)" class="btn btn-primary">Book {{ formatCurrency(hotel.price * night) }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>`;

Vue.component('hotel-booking', {
    template: template,
    props:{
        'minDate': String,
        'maxDate':String,
        'searchUrl':String,
        'bookUrl':String,
        'booking':Array,
        'uniqueId':String,
        'onBook':Function,
        'onDelete':Function,
    },
    data: () => {
        return {
            form:{
                checkin:null,
                checkout:null,
            },
            searching:false,
            hotels:null,
            showBooking:false,
            bookingData:[],
            momentFormat: {
				stringify: (date) => {
					return date ? moment(date).format('DD MMM YYYY') : ''
				},
				parse: (value) => {
					return value ? moment(value, 'DD MMM YYYY').toDate() : null
				},
			}
        }
    },
    mounted(){
        this.bookingData = (this.booking) ? this.booking: [];
    },
    methods:{
        bookHotel(evt,room){
            let btn = evt.currentTarget;
            let tempInner = btn.innerHTML;
			btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
            btn.setAttribute("disabled", true);
            if(typeof this.onBook != 'undefined'){
                this.onBook(btn,room,this.form);
                btn.innerHTML = tempInner;
                btn.removeAttribute("disabled");
            }else{
                $.post(this.bookUrl,{
                    id:room.id,
                    name:room.name,
                    checkin:moment(this.form.checkin).format("YYYY-MM-DD"),
                    checkout:moment(this.form.checkout).format("YYYY-MM-DD"),
                    is_hotel:true,
                    uniqueId:this.uniqueId
                },(res)=>{
                    if(res.status){
                        this.bookingData.push({
                            id:room.id,
                            name:room.name,
                            hotel_name:room.hotel_name,
                            checkin:this.form.checkin,
                            checkout:this.form.checkout,
                            status_payment:'Waiting Payment'
                        });
                        Swal.fire('Berhasil',"Hotel berhasil dipesan !", 'success');
                    }else{
                        Swal.fire('Fail', res.message, 'warning');
                    }
                }).fail((xhr)=>{
                    Swal.fire('Fail', "Failed to book hotels !", 'error');
                }).always((res)=>{
                    btn.innerHTML = tempInner;
                    btn.removeAttribute("disabled");
                })
            }
        },
        search(){
            this.searching = true;
            $.post(this.searchUrl,{checkin:moment(this.form.checkin).format("YYYY-MM-DD"),checkout:moment(this.form.checkout).format("YYYY-MM-DD")},(res)=>{
                if(res.code == '200'){
                    this.hotels = res.data;
                }else{
                    Swal.fire('Warning',res.message, 'warning');
                }
            }).fail((xhr) => {
                Swal.fire('Fail', "Failed to search hotels !", 'error');
            }).always((res) => {
                this.searching = false;
            })
        },
        disabledDate(date,currentValue){
            let dateCheck = moment(date);
            return !(dateCheck.isSameOrAfter(moment(this.minDate,"YYYY-MM-DD")) && dateCheck.isSameOrBefore(moment(this.maxDate,"YYYY-MM-DD")));
        },
        formatCurrency(price, currency = 'IDR') {
            return new Intl.NumberFormat("id-ID", {
                style: 'currency',
                currency: currency,
                minimumFractionDigits:0,
            }).format(price);
        },
        formatDate(date){
            return moment(date).format('DD MMM YYYY');
        },
        calculateNight(checkin,checkout){
            return moment(checkout).diff(checkin,'days');
        }
    },
    computed: {
        night(){
            if(this.form.checkin && this.form.checkout){
                return moment(this.form.checkout).diff(this.form.checkin,'days');
            }
            return null;
        }
    },
});