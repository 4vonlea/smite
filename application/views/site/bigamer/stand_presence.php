<?php
$this->layout->begin_head();
?>
<style>
    .achievement-area-copy {
        background-color: #232a5c;
        padding: 30px;
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<?php $this->layout->end_head(); ?>
<section class="pageheader-section" style="padding-bottom: 50px;">
    <div class="container">
        <div class="section-wrapper text-center text-uppercase">
            <h2 class="pageheader-title">Presensi Kehadiran Pameran</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="index.html">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Prensensi</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<section id="app" class="padding-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-sm-12">
                <div class="alert btn-purple">
                    <h4 class="text-black"><i class="icofont icofont-info-circle"></i> <b>Perhatian</b></h4>
                    <p><b>Silakan lakukan pencarian nama atau email anda pada isian dibawah kemudian tekan tombol "Hadir"</b></p>
                </div>
                <div class="achievement-area-copy card card-achievement mt-2">
                    <div class="card-header text-center">
                        <h4 class="m-0 p-0">
                            <strong class="font-weight-extra-bold ">
                                <?= $sponsor->sponsor; ?>
                            </strong>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <v-select placeholder="Ketikan nama atau email anda" v-model="member" :options="listMembers" name="members"></v-select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-grid gap-2">
                            <v-button @click="setPresence($event)" class="btn btn-purple btn-block">Hadir</v-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<script src="<?= base_url("themes/script/v-button.js"); ?>"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        'el': "#app",
        data: {
            listMembers: <?= json_encode($members); ?>,
            member: null,
        },
        methods: {
            setPresence(self) {
                if(!this.member){
                    Swal.fire('Warning',"Harap pilih nama atau email anda terlebih dahulu", 'warning');
                    return false;
                }
                self.toggleLoading();
                $.post("<?= current_url() ?>", {
                    member_id: this.member.code
                }, (res) => {
                    if (res.status) {
                        Swal.fire({
                            title: 'Successfully',
                            type: 'success',
                            html: `<p>Kehadiran anda berhasil disimpan</p>`,
                        });
                    } else {
                        Swal.fire('Warning', res.message, 'warning');
                    }
                }).fail(() => {
                    Swal.fire('Fail', "Server gagal merespon. Silakan coba kembali", 'error');

                }).always(() => {
                    self.toggleLoading();
                })
            }
        }
    });
</script>
<?php $this->layout->end_script(); ?>