let templateMappingP2KB = `
    <div>
        <div class="form-group">
            <label class="control-label">Aktivitas</label>
            <v-select :options="aktivitasOptions" placeholder="Please Select..." :value="content.aktivitas" label="aktivitas_name" @input="handleInput('aktivitas',$event)"></v-select>
        </div>
        <div class="form-group">
            <label class="control-label">
                Jenis Aktivitas
                <i v-if="loadingJenisAktivitas" class="fa fa-spin fa-spinner"></i>
            </label>
            <v-select :options="jenisAktivitas" placeholder="Please Select..." :value="content.jenisAktivitas" :disabled="loadingJenisAktivitas || jenisAktivitas.length == 0" label="role_name" @input="handleInput('jenisAktivitas',$event)"></v-select>
        </div>
        <div class="form-group">
            <label class="control-label">
                Nilai SKP
                <i v-if="loadingNilaiSkp" class="fa fa-spin fa-spinner"></i>
            </label>
            <v-select :options="nilaiSkp" placeholder="Please Select..." :value="content.nilaiSkp" :disabled="loadingNilaiSkp || nilaiSkp.length == 0" label="role_name" @input="handleInput('nilaiSkp',$event)"></v-select>
            <input type="text" :value="skp" readonly class="form-control mt-2" />
        </div>
    </div>
`;
Vue.component('v-mapping-p2kb',{
    template:templateMappingP2KB,
    props:{
        value:{
            type:Object,
            default(){
                return {
                    nilaiSkp:null,
                    aktivitas:null,
                    jenisAktivitas:null,
                }
            }
        },
        urlJenisAktivitas:{
            type:String
        },
        urlSkp:{
            type:String
        },
        aktivitasOptions:{
            type:Array,
        },
    },
    mounted(){
        this.content = this.value;
        if(this.content.aktivitas){
            this.handleInput("aktivitas",this.content.aktivitas)
        } 
        if(this.content.jenisAktivitas){
            this.handleInput("jenisAktivitas",this.content.jenisAktivitas)
        } 
        if(this.content.nilaiSkp){
            this.handleInput("nilaiSkp",this.content.nilaiSkp)
        }
    },
    data:() => {
        return {
            aktivitas:[],
            jenisAktivitas:[],
            nilaiSkp:[],
            content:{},
            loading : false,
            loadingJenisAktivitas:false,
            loadingNilaiSkp:false,
            skp:0,
        }
    },
    computed:{
        self(){
            return this;
        },
    },
    methods:{
        handleInput(type,val){
            this.content[type] = val;
            this.$emit("input",this.content);
            if(type == "aktivitas"){
                this.loadingJenisAktivitas = true;
                $.get(this.urlJenisAktivitas+`/${val.aktivitas_code}`,(res) => {
                    this.jenisAktivitas = res.data
                }).always(() => {
                    this.loadingJenisAktivitas = false;
                });
            }else if(type == "jenisAktivitas"){
                this.loadingNilaiSkp = true;
                $.get(this.urlSkp+`/${val.role_code}`,(res) => {
                    this.nilaiSkp = res.data;
                    console.log(this.nilaiSkp);
                }).always(() => {
                    this.loadingNilaiSkp = false;
                });
            }else if(type == "nilaiSkp"){
                this.skp = val.skp;
            }
        },
        toggleLoading(){
            this.loading = !this.loading;
        },
       
    }
})