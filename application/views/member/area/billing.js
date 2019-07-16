export default Vue.component("PageBilling",{
    template:`
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <h4>Billing</h4>
        </div>
    `,
    data:function () {
        return {
            loading:false,
            fail:false,
        }
    }
});