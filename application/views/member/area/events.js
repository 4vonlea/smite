export default Vue.component("PageEvents",{
    template:`
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <h4>Events</h4>

        </div>
    `,
    data:function () {
        return {
            loading:false,
            fail:false,
        }
    }
});