let templateButton = `
        <button :disabled="loading || disabled" @click="$emit('click',self)">
            <i v-if="icon && !loading" :class="icon"></i>
            <i v-if="loading" class="fa fa-spin fa-spinner"></i>
            <slot></slot>
        </button>
`;
Vue.component('v-button',{
    template:templateButton,
    props:{
        icon:{
            type:String
        },
        disabled:{
            type:Boolean,
            default:false,
        },
    },
    data:() => {
        return {
            loading : false
        }
    },
    computed:{
        self(){
            return this;
        },
    },
    methods:{
        toggleLoading(){
            this.loading = !this.loading;
        },
       
    }
})