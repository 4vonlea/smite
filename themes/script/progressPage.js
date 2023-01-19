export default Vue.component('page-loader', {
    props: ['loading', 'fail'],
    template: `
    <div :class="[loading || fail ? 'container h-100':'']">
        <div v-if="loading" class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center">
                <i class="fa fa-spin fa-spinner fa-5x"></i>
                <p>Processing....</p>

            </div>
        </div>
        <div v-if="fail" class="d-flex align-items-center justify-content-center h-100">
            <div class="d-flex flex-column alert alert-danger  text-center">
                <div class="fa fa-exclamation-circle" style="font-size: 50px !important;"></div>
                <br/>
                Failed to load your page, Please retry process or click link again
            </div>
        </div>
    </div>         
    `
})

