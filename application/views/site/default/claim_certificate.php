<?php

/**
 * @var $report
 */
?>
<section id="app" aria-label="section" class="banner-section bg-img">
    <div class="container">
        <div class="section-wrapper text-center text-uppercase">
            <h2 class="pageheader-title mb-3">Klaim Sertifikat</h2>
        </div>
        <div class="row">
            <div class="col-lg-8 offset-lg-2 achievement-area">
                <div class="row">
                    <div class="col-sm-12 text-center" v-if="result.length == 0">
                        <p>Mohon ketikkan  <strong>Nama atau No Invoice anda kemudian tekan enter</strong></p>
                        <div class="input-group border" :class="[isFocusToScan ? 'border-success':'border-danger']">
                            <span v-if="searching" class="input-group-text">
                                <i class="fa fa-spin fa-spinner"></i>
                            </span>
                            <input :readonly="searching" ref="inputScan" v-on:focus="isFocusToScan = true" v-on:focusout="keepFocus" @keyup.enter="doSearchScan" v-model="inputScanValue" type="text" class="form-control" placeholder="Type Here" />
                        </div>
                        <h2 class="h1" :class="[isFocusToScan ? 'valid-feedback':'invalid-feedback']">
                            <span v-if="isFocusToScan">On Focus, You Can Scan</span>
                            <span v-else>Please put cursor on input text above and click until border color is green</span>
                        </h2>
                    </div>
                    <div class="col-sm-12 col-md-12" v-if="result.length > 0">
                        <div class="list-group">
                            <div v-for="row in result" :key="row.id" class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-row align-items-center">
                                    <span class="bg-info rounded-circle text-center" style="width: 40px;height:40px;padding:8px">
                                        {{ row.event[0] }}
                                    </span>
                                    <div class="d-flex flex-column ms-4">
                                        <h4 class="mb-1 text-dark">{{ row.fullname }}</h4>
                                        <p class="mb-1 text-muted">{{row.event}}</p>
                                    </div>
                                </div>
                                <a target="_blank" :href="'<?= base_url('certificate/claim'); ?>/'+`${row.id}`" class="btn btn-purple">Download Certificate</a>
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
</section>
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
        mounted() {
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
                $.post("<?= base_url('certificate/get_transaction'); ?>", {
                    invoice_id: this.inputScanValue,
                }).done((res) => {
                    if (res.status) {
                        this.result = res.data;
                    } else {
                        Swal.fire("Info", res.message, "info");
                    }
                }).fail(() => {
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