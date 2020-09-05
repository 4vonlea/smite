<?php
/**
 * @var array $statusList
 * @var array $univDl
 */
?>
<div class="header bg-info pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<!-- Card stats -->
			<div class="row">
				<div class="col-xl-6 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Total Members</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_members }}</span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
										<i class="fas fa-chart-bar"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-6 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Unverified Members</h5>
									<span class="h2 font-weight-bold mb-0">{{ pagination.total_unverified }}</span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-warning text-white rounded-circle shadow">
										<i class="fas fa-chart-pie"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--7">
	<!-- Table -->
	<div class="row">
		<div class="col-xl-12">
			<div class="card shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-6">
							<h3>Material Uploads</h3>
						</div>
						<div class="col-6 text-right">
							<button type="button" class="btn btn-primary" data-toggle="modal"
									data-target="#modal-upload-list"><i class="fa fa-book"></i> List Upload
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<datagrid
						@loaded_data="loadedGrid"
						ref="datagrid"
						api-url="<?= base_url('admin/material/grid'); ?>"
						:fields="[{name:'fullname',sortField:'fullname','title':'Speaker Name'}, {name:'title',sortField:'title'},{name:'id_mum',title:'Status'}]">
						<template slot="id_mum" slot-scope="props">
								<a target="_blank" v-if="props.row.filename" :href="props.row.type == 1 ? props.row.filename : '<?=base_url('admin/material/file');?>/'+props.row.filename+'/'+props.row.title" class="btn btn-sm btn-primary">Lihat Bahan</a>

								<span v-if="props.row.filename" class="badge badge-success">Telah ditambahkan</span>
                                <span v-else class="badge badge-danger">Belum ditambahkan</span>
						</template>
					</datagrid>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="modal-upload-list">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Upload List</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="form-group">
					<div class="input-group">
						<input v-model="newList" type="text" class="form-control" @keyup.enter="addList"
							   placeholder="New List"/>
						<div class="input-group-append">
							<button type="button" class="btn btn-primary" @click="addList"><i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
				<table class="table">
					<thead>
					<tr>
						<th>File to Upload</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="(cat,index) in uploadList">
						<td>
							{{ cat.title }}
						</td>
						<td>
							<button @click="removeList(index)" class="btn btn-danger btn-sm"><i
									class="fa fa-times"></i></button>
						</td>
					</tr>
					</tbody>
				</table>

			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>

<?php $this->layout->begin_script(); ?>

<script>
    var tempStatus = <?=json_encode($uploadList);?>;

    function postStatus(cat) {
        return $.post('<?=base_url('admin/material/add_list');?>', {value: cat});
    }

    var app = new Vue({
        el: '#app',
        data: {
			newList:'',
            uploadList:<?=json_encode($uploadList);?>,
            verifyModel: {},
            pagination: {},
        },
        methods: {
			formatDate(date) {
				return moment(date).format("DD MMM YYYY, [At] HH:mm:ss");
			},
			
            addList: function () {
                if (this.newList != "") {
					tempStatus.push({"title": this.newList});
                    postStatus(tempStatus).done(function (res) {
                        app.uploadList = res;
						tempStatus = res;
                        app.newList = "";
                    }).fail(function (xhr) {
                        tempStatus.pop();
						var message =  xhr.getResponseHeader("Message");
						if(!message)
							message = 'Server fail to response !';
						Swal.fire('Fail', message, 'error');
                    });
                }

            },
            removeList: function (index) {
                var value = this.uploadList[index];
                $.post("<?=base_url('admin/material/remove_list');?>", {id: value.id}, function (res) {
                    if (res.status)
                        app.uploadList.splice(index, 1);
                }, 'JSON').fail(function (xhr) {
					var message =  xhr.getResponseHeader("Message");
					if(!message)
						message = 'Server fail to response !';
					Swal.fire('Fail', message, 'error');
                });
			},
            loadedGrid: function (data) {
                this.pagination = data;
            }
        },
        mounted() {
			<?php if(isset($_GET['q'])):?>
            this.$refs.datagrid.globalFilter = "<?=$_GET['q'];?>";
            this.$refs.datagrid.doFilter();
			<?php endif;?>
        }
    });
</script>
<?php $this->layout->end_script(); ?>
