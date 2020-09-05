export default Vue.component("Material", {
    template:`
    <div class="col-lg-9">
        <page-loader :loading="loading" :fail="fail"></page-loader>
        <div v-if="!loading">
            <div class="overflow-hidden mb-1">
                <h2 class="font-weight-normal text-7 mb-0"><strong class="font-weight-extra-bold">Upload Materi</strong></h2>
            </div>
            <div class="overflow-hidden mb-4 pb-3">
                <p class="mb-0">Upload file materi/bahan untuk seminar</p>
            </div>
            <div class="row">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Bahan</th>
                            <th>Upload/Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(m,index) in listMaterial">
                            <td>
                                {{ m.title }}
                                <span v-if="m.filename" class="badge badge-success">Telah ditambahkan</span>
                                <span v-else class="badge badge-danger">Belum ditambahkan</span>
                            </td>
                            <td>
                                <a target="_blank" v-if="m.filename" :href="m.type == 1 ? m.filename : baseUrl+'file_material/'+m.filename+'/'+m.title" class="btn btn-primary">Lihat Bahan</a>
                                <hr/>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input :id="'radio_file'+index" type="radio" v-model="m.type" value="2" class="custom-control-input">
                                    <label class="custom-control-label" :for="'radio_file'+index">File</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input :id="'radio_link'+index"  type="radio" v-model="m.type" value="1" class="custom-control-input">
                                    <label class="custom-control-label" :for="'radio_link'+index" >Link</label>
                                </div>
                                <div v-if="m.type == 1" class="input-group mb-3">
                                    <input type="text" :ref="'reflink_'+index" :value="m.filename" class="form-control" placeholder="Link (max 250 karakter)" aria-label="URL" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" @click="saveMaterial(m,index,$event)" type="button">Simpan</button>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="input-group mb-3">
                                        <div  class="custom-file">
                                            <input :ref="'reffile_'+index" type="file" class="custom-file-input" @change="browseFile($event,m)" >
                                            <label class="custom-file-label" >{{ m.tempname ? m.tempname : 'Pilih File' }}</label>
                                        </div>
                                        <div class="input-group-append"  style="height:35px">
                                            <button class="btn btn-outline-secondary" @click="saveMaterial(m,index,$event)" type="button">Simpan</button>
                                        </div>
                                    </div>
                                    <small>*ekstensi file 'doc|docx|jpg|jpeg|png|bmp'</small>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    `,
    data:function(){
        return {
            listMaterial:[],
            loading:false,
            fail:false,
        }
    },
    mounted(){
        this.fetchData();
    },
    methods:{
        browseFile(event,row){
            if(event.target.files.length > 0){
                row.tempname = event.target.files[0].name;
            }
        },
        fetchData(){
            var page = this;
			page.loading = true;
			page.fail = false;
			$.get(this.appUrl+"member/area/list_material")
			.always(function(res){
                if (res.status) {
                    page.listMaterial = res.data;
                } else {
                    page.fail = true;
                }
			}).fail(function () {
                page.fail = true;
            }).always(function () {
                page.loading = false;
            });
        },
        saveMaterial(row,index,evt){
            var page = this;
            let valid = true;
            var formData = new FormData();
            if(row.type == 1){
                let value = this.$refs['reflink_'+index][0].value;
                if(value.length > 250){
                    valid = false;
                    Swal.fire('Peringatan',"Panjang karakter maksimal 250",'warning');
                }
                formData.append("filename",value);
            }else{
                let refFile = this.$refs['reffile_'+index][0];
                if(refFile.files.length > 0 ){
                    formData.append("filename",refFile.files[0]);
                }else{
                    valid = false;
                    Swal.fire('Peringatan',"Pilih file baru sebelum melakukan upload",'warning');
                }
            }
            formData.append("ref_upload_id",row.id);
            formData.append("type",row.type);
            formData.append("id",row.id_mum);
            evt.target.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
            evt.target.setAttribute("disabled",true);
            if(valid){
                $.ajax({
                    url : `${this.baseUrl}upload_material`,
                    type : 'POST',
                    data : formData,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success : function(res) {
                        if(res.status){
                            page.fetchData();
                        }else{
                            Swal.fire('Peringatan',res.message,'warning');
                        }
                    }
                }).fail(function () {
                    Swal.fire('Gagal',"Server gagal memproses silakan coba lagi",'error');
                }).always(function () {
                    evt.target.innerHTML = "Simpan";
                    evt.target.removeAttribute("disabled");

                });
            }
        }
    }
});