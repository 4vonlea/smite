<section id="home" aria-label="section" class="banner-section bg-img">
    <div class="container-fluid">
        <div class="section-wrapper text-center text-uppercase">
            <h2 class="pageheader-title mb-3">Sertifikat</h2>
        </div>
        <div class="row">
            <div class="col-lg-4 achievement-area">
                <div class="card">
                    <div class="card-header text-dark h5">
                        Informasi
                    </div>
                    <div class="card-body">
                        <table class="table text-dark">
                            <tbody>
                                <tr>
                                    <th>NIK</th>
                                    <td>
                                        <?= $data['nik']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>
                                        <?= $data['fullname']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>
                                        <?= $data['email']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama Item</th>
                                    <td>
                                        <?= $data['event_name']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama Event</th>
                                    <td>
                                        <?= $data['event_name']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ketua Panitia</th>
                                    <td>
                                        <?= $ketua_panitia; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 achievement-area">
                <div class="card">
                    <div class="card-header">
                        Sertifikat
                    </div>
                    <div class="card-body">
                        <pdfviewer :file="filePdf" download-link="<?=base_url("site/sertifikat/$hashedId/download");?>"></pdfviewer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->layout->begin_script(); ?>
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js" integrity="sha512-tqaIiFJopq4lTBmFlWF0MNzzTpDsHyug8tJaaY0VkcH5AR2ANMJlcD+3fIL+RQ4JU3K6edt9OoySKfCCyKgkng==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?=base_url('themes/script/vue-pdfviewer.js');?>"></script>
    <script>

    var app = new Vue({
        el: '#home',
        data: {
            filePdf: '<?= base64_encode($sertifikat->output()); ?>',
        }
    });

    </script>
<?php $this->layout->end_script();?>
