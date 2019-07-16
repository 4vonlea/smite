export default Vue.component("PagePaper",{
    template:`
        <div class="col-lg-9">
            <page-loader :loading="loading" :fail="fail"></page-loader>
            <div v-if="!loading && !fail">
                <div class="overflow-hidden mb-1">
                    <h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Submit Paper</strong></h2>
                </div>
                <div class="overflow-hidden mb-4 pb-3">
                    <p class="mb-0">Wanna participate on paper, please upload your fullpaper.</p>
                </div>
                <div v-if="status == 0">
                    <div v-if="message_revise" class="alert alert-info">
                        <h4>Your paper has been reviewed</h4>
                        <p>{{ message_revise }}, Please revise and reupload</p>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Title</label>
                        <div class="col-lg-9">
                            <input class="form-control"  type="text" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 font-weight-bold text-dark col-form-label form-control-label text-2">Upload Paper *<small>(.doc,.docx)</small></label>
                        <div class="col-lg-9">
                            <input class="form-control-file" accept="application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"  type="file" value="">
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                <div v-if="status == 1">
                    <div class="alert alert-info">
                        <h4>Your paper is under review</h4>
                        <p>Please return to check your status later.You will be sent a notification when a decision has been made.</p>
                    </div>
                    <table class="table">
                        <tr><th>Title</th><td>Title</td></tr>
                        <tr><th>File Paper</th><td><a href="#">Click here to download !</a> </td></tr>
                        <tr><th>Submitted On</th><td>20 May 2019, At 20:18:00 </td></tr>
                    </table>
                </div>
                <div v-if="status == 2">
                    <div class="alert alert-success">
                        <h4>Congratulation. .</h4>
                        <p> Your paper has been accepted, Please register to events.</p>
                    </div>
                </div>
            </div>
        </div>
    `,
    data:function () {
        return {
            loading:false,
            fail:false,
            status:1,
            message_revise:"Please upload your fullpaper and writer name",
        }
    }
});