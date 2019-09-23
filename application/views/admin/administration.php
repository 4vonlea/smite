<?php
/**
 * @var $event
 */
?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml"
	 xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>

<div class="container-fluid mt--7">
	<div key="table" class="row">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h3>Administration</h3>
						</div>
						<div class="col-6 text-right">
							<select class="form-control">
								<option disabled hidden value="">Select Event First</option>
								<option v-for="(event,key) in eventList" :value="key"> {{ event }}</option>
							</select>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<datagrid
						ref="datagrid"
						api-url="<?= base_url('admin/administration/grid'); ?>"
						:fields="[{name:'username',sortField:'username'}, {name:'role',sortField:'role','title':'Role'},{name:'username_',sortField:'username_','title':'Actions'}]">
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
        data: {
            eventList:<?=json_encode($event);?>,
            selectedEvent:"",
		},
        methods: {}
    });
</script>
<?php $this->layout->end_script(); ?>
