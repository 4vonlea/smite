<?php
/**
 * @var $pricingDefault
 */
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml"
	 xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>

<div class="container-fluid mt--7">
    <div key="table" class="row">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h3>Link Click Report</h3>
                        </div>
                        <div class="col-6 text-right">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Download
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="<?=base_url('admin/click_report/download');?>" target="_blank" class="dropdown-item">Report</a>
                                    <a class="dropdown-item" href="<?=base_url('admin/click_report/download_detail');?>" target="_blank">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <datagrid
                        ref="datagrid"
                        api-url="<?= base_url('admin/click_report/grid'); ?>"
                        :fields='<?=json_encode($field);?>'>
                        <template slot="id" slot-scope="props">
                            <div class="table-button-container">
                                <button @click="edit(props)" class="btn btn-info btn-sm">
                                    <span class="fa fa-edit"></span> Edit
                                </button>
                                <button @click="deleteRow(props)" class="btn btn-warning btn-sm">
                                    <span class="fa fa-trash"></span> Delete
                                </button>
                            </div>
                        </template>
                    </datagrid>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Table -->

<?php $this->layout->begin_script(); ?>
<script>
    var app = new Vue({
        el: '#app',
        data: {        },
        methods: {
			
        }
			
    });
</script>
<?php $this->layout->end_script(); ?>
