export default Vue.component("PageBilling", {
    template: `
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div class="overflow-hidden mb-1">
                <h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Billing & Cart</strong></h2>
            </div>
            <div class="overflow-hidden mb-4 pb-3">
                <p class="mb-0">Confirm your billing and event you follow</p>
            </div>
            <table class="table">
                <thead>
                    <th></th>
                    <th>Event Name</th>
                    <th>Pricing</th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a title="Remove item" class="fa fa-trash text-danger"></a>
                        </td>
                        <td>Seminar (Early Bird-Perdosky Member)</td>
                        <td>Rp 1000000</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class="text-right font-weight-bold">Total :</td>    
                        <td>Rp 1000000</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><button class="btn btn-primary float-right">Clear Shopping</button></td>
                        <td><button class="btn btn-primary float-right">Checkout</button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    `,
    data: function () {
        return {
            loading: false,
            fail: false,
        }
    }
});