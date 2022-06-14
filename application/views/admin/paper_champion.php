<?php

/**
 * @var $admin_paper
 */
$this->layout->begin_head();
?>
<style>
    .modal .table th,
    .modal .table td {
        white-space: normal !important;
    }

    .vuetable-td-title {
        word-wrap: break-word;
        white-space: inherit !important;
        font-size: 12px !important;
    }
    .easy-autocomplete{
        width:100% !important
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.css">
<link href="<?= base_url('themes/script/easyautocomplete/easy-autocomplete.min.css'); ?>" rel="stylesheet">
<?php
$this->layout->end_head();
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid mt--7">
    <div class="col-xl-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h3>Papers Champion</h3>
                    </div>
                    <div class="col-md-8 text-right">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span v-if="filteredPaper == ''">   
                                    All Category
                                </span>
                                <span v-else>
                                    Filter only {{ filteredPaper }}
                                </span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" @click="filterGrid('all','')">All Category</a>
                                <a v-for="(cat,index) in categoryPaper" class="dropdown-item" href="#" @click="filterGrid(cat.id,cat.name)">{{ cat.name }}</a>
                            </div>
                        </div>
                        <button @click="onAdd" type="button" class="btn btn-primary"><i class="fa fa-plus"></i>
                            Add Champion</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <datagrid ref="datagrid" :api-url="apiUrl" :sort-list="[
					{name:'id_paper',sortField:'id_paper','title':'ID Paper'},
					{name:'category_name',sortField:'category_name','title':'Category Paper'},
					{name:'title',sortField:'title','title':'Abstract Title'},
					{name:'fullname',sortField:'fullname','title':'Member Name'},
					{name:'description','sortField':'description','title':'Description'},
				]" :fields="[
                        {name:'id_paper',sortField:'id_paper','title':'ID Paper'},
                        {name:'category_name',sortField:'category_name','title':'Category Paper'},
                        {name:'title',sortField:'title','title':'Abstract Title'},
                        {name:'fullname',sortField:'fullname','title':'Member Name'},
                        {name:'description','sortField':'description','title':'Description'},
						{name:'t_id','title':'Aksi'}
					]">
                    <template slot="title" slot-scope="props">
                        <span class="badge badge-info">Category : {{ props.row.category_name ?? "Not Set"  }}</span>
                        <p style="font-size: 14px;white-space:normal">{{ props.row.title }}</p>
                    </template>
                    <template slot="fullname" slot-scope="props">
                        {{ props.row.fullname }}
                        <hr style="margin-top: 10px;margin-bottom:10px;" />
                        <span style="font-size: 12px;" class="badge badge-info mb-1">{{ props.row.status_member }}</span><br />
                        <span style="font-size: 12px;" class="badge badge-info mb-1">{{ props.row.phone }}</span><br />
                        <span style="font-size: 12px;" class="badge badge-info mb-1">{{ props.row.institution }}</span> <br />
                    </template>
                    <template slot="t_updated_at" slot-scope="props">
                        {{ formatDate(props.row.t_created_at) }}
                    </template>
                    <template slot="t_id" slot-scope="props">
                        <div class="table-button-container">
                            <button class="btn btn-danger btn-sm" @click="deletePaper(props.row,$event)">
                                <span class="fa fa-trash"></span> Delete
                            </button>
                        </div>
                    </template>
                </datagrid>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modal-add" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Champion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="control-label">
                Paper Title
            </label>
            <input type="text" class="form-control autocomplete" placeholder="Please type a paper title or participant name" v-model="form.title" />
        </div>
        <div class="form-group">
            <label class="control-label">
                Description
            </label>
            <input type="text" class="form-control" v-model="form.description" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" @click="onSave" :disabled="saving" class="btn btn-primary">
            <i v-if="saving" class="fa fa-spin fa-spinner"></i> Save
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php $this->layout->begin_script(); ?>
<script src="<?=base_url('themes/script/easyautocomplete/jquery.easy-autocomplete.min.js');?>"></script>
<script>
    
    var app = new Vue({
        el: '#app',
        data: {
            apiUrl: "<?= base_url('admin/paper/grid_champion'); ?>",
            categoryPaper: <?= json_encode($categoryPaper); ?>,
            filteredPaper: "",
            form:{
                title:'',
                paper_id:'',
                description:'',
            },
            saving:false,
        },
        methods: {
            onAdd() {
                this.form = {
                    title:'',
                    paper_id:'',
                    description:'',
                };
                $("#modal-add").modal("show");
            },
            detail(props, $event) {

            },
            deletePaper(row, $event) {
                var btn = event.currentTarget;
                Swal.fire({
                    title: "Are you sure ?",
                    text: `You will delete "${row.title}" From Champion`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        btn.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
                        btn.setAttribute("disabled",true);
                        $.post("<?=base_url("admin/paper/delete_champion");?>", {id:row.t_id}, function (res) {
                            if (res.status) {
                                Swal.fire("Success", "Member deleted successfully", "success");
                                app.$refs.datagrid.refresh();
                            }else
                                Swal.fire("Failed", res.message, "error");
                        }, "JSON").fail(function (xhr) {
							var message =  xhr.getResponseHeader("Message");
							if(!message)
								message = 'Server fail to response !';
							Swal.fire('Fail', message, 'error');
                        }).always(function () {
                            btn.innerHTML = '<i class="fa fa-trash"></i> Delete';
                            btn.removeAttribute("disabled");
                        });
                    }
                });
            },
            filterGrid(id, categoryName) {
                if(id != "all"){
					app.$refs.datagrid.additionalQuery = {'category':id};
				}else{
					app.$refs.datagrid.additionalQuery = {};
				}
                this.filteredPaper = categoryName;
				app.$refs.datagrid.doFilter();
            },
            onSave(){
                this.saving = true;
                $.post("<?=base_url('admin/paper/add_champion');?>",this.form,(res) => {
                    if(res.status){
                        $("#modal-add").modal("hide");
						this.$refs.datagrid.reload();
                    }
                }).always((xhr) => {
                    this.saving = false;
                }).fail((xhr) => {

                });
            }
        }
    });
    $('.autocomplete').easyAutocomplete({
        url: '<?= base_url('admin/paper/search_paper'); ?>',
        listLocation: "items",
        getValue: 'value',
        template: {
            type: "custom",
            method: function(value, item) {
                return `${value} (${item.fullname})`;
            }
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },
        list: {
            onClickEvent: function(cur) {
                var element = $('.autocomplete').getSelectedItemData();
                app.form.title = `${element.value} (${element.fullname})`;
                app.form.paper_id = element.paper_id;
            }
        },
        preparePostData: function(data) {
            data.cari = app.form.title;
            return data;
        },
    });
</script>
<?php $this->layout->end_script(); ?>