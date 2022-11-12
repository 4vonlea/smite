<?php

/**
 * @var $report
 */
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-header bg-white pb-5">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1>Self Registration</h1>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 text-center" v-if="result.length == 0">
                            <p>Please type your "<strong>Invoice Number then press enter</strong>"" or scan your "<strong>QR Code On Payment Proof</strong>"</p>
                            <div class="input-group border" :class="[isFocusToScan ? 'border-success':'border-danger']">
                                <div v-if="searching" class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fa fa-spin fa-spinner"></i>
                                    </span>
                                </div>
                                <input :readonly="searching" ref="inputScan" v-on:focus="isFocusToScan = true" v-on:focusout="keepFocus" @keyup.enter="doSearchScan" v-model="inputScanValue" type="text" class="form-control" placeholder="Focus On Here" />
                            </div>
                            <h2 class="h1" :class="[isFocusToScan ? 'valid-feedback':'invalid-feedback']">
                                <span v-if="isFocusToScan">On Focus, You Can Scan</span>
                                <span v-else>Please put cursor on input text above and click until border color is green</span>
                            </h2>
                        </div>
                        <div class="col-sm-12 col-md-6 offset-md-3" v-if="result.length > 0">
                            <div class="list-group">
                                <div v-for="row in result" :key="row.id" class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-row align-items-center">
                                        <span class="bg-info rounded-circle text-center" style="width: 40px;height:40px;padding:8px">
                                            {{ row.event[0] }}
                                        </span>
                                        <div class="d-flex flex-column ml-4">
                                            <h4 class="mb-1">{{ row.fullname }}</h4>
                                            <p class="mb-1 text-muted">{{row.event}}</p>
                                        </div>
                                    </div>
                                    <a target="_blank" :href="'<?=base_url('admin/presence/card_and_presence');?>/'+`${row.event_id}/${row.member_id}/${row.id}`" class="btn btn-info">Print Nametag</a>
                                </div>
                            </div>
                            <div class="text-right mt-3">
                                <button class="btn btn-primary" @click="finish">Finish</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->layout->begin_script(); ?>
<script type="module">
    var app = new Vue({
        el: "#app",
        data: {
            isFocusToScan: true,
            inputScanValue: '',
            result: [],
            searching: false,
        },
        computed: {

        },
        mounted(){
            this.$refs.inputScan.focus();
        },
        methods: {
            keepFocus() {
                console.log("Return Focus");
                this.$refs.inputScan.focus();
            },
            finish() {
                this.result = [];
                this.inputScanValue = "";
                Vue.nextTick(() => {
                    this.$refs.inputScan.focus();
                });
            },
            doSearchScan() {
                this.searching = true;
                $.post("<?= base_url('admin/presence/get_event_transaction'); ?>", {
                    invoice_id:this.inputScanValue,
                }).done((res)=>{
                    if(res.status){
                        this.result = res.data;
                    }else{
                        Swal.fire("Info", res.message, "info");
                    }
                }).fail(()=>{
					Swal.fire("Failed", "Failed connect to server, Please try again !", "danger");
                }).always(() => {
                    this.searching = false;
                })
            }
        }
    })
</script>
</script>
<?php $this->layout->end_script(); ?>