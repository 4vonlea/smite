<?php
/**
 * @var $pricingDefault
 */
?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml"
     xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>

<div class="container-fluid mt--7">
    <div v-if="message" class="row">
        <div class="col-md-12">
            <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                <strong>{{ message }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    <transition name="fade" mode="out-in">

        <div v-if="form.show" key="form" class="row">
            <div class="col-xl-12">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ form.title }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="#!" v-on:click="formCancel" class="btn btn-sm btn-default"><i
                                            class="fa fa-times"></i> </a>
                            </div>
                        </div>
                    </div>
                    <form>
                        <div class="card-body">
                            <div class="alert alert-danger" v-if="error">
                                <p v-for="msg in error">{{ msg }}</p>
                            </div>
                            <h6 class="heading-small text-muted mb-4">Event information</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="input-username">Name</label>
                                            <input :disabled="detailMode" type="text" class="form-control form-control-alternative"
                                                   v-model="form.model.name"
                                                   placeholder="name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Category</label>
                                            <select :disabled="detailMode" name="kategory" class="form-control  form-control-alternative" v-model="form.model.kategory">
                                                <option value="" hidden selected >Select Event Category</option>
                                                <option v-for="cat in eventCategory" :value="cat">{{ cat}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group focused">
                                            <label class="form-control-label">Description</label>
                                            <textarea :disabled="detailMode" v-model="form.model.description" rows="4"
                                                      class="form-control form-control-alternative"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <!-- Address -->
                            <h6 class="heading-small text-muted mb-4">
                                Event Pricing
                                <button v-if="!detailMode" type="button" class="btn btn-primary btn-sm" v-on:click="addPricing"><i
                                            class="fa fa-plus"></i> Add Price Category
                                </button>
                            </h6>
                            <div class="pl-lg-4">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" v-for="(eventPrice,index) in form.model.event_pricing"
                                        v-bind:key="'m'+index">
                                        <a class="nav-link" v-bind:class="[index == 0 ? 'active':'']"
                                           id="pills-home-tab" data-toggle="pill" v-bind:href="'#pills-'+index"
                                           role="tab" aria-controls="pills-home" aria-selected="true">{{eventPrice.name}}</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div v-for="(eventPrice,index) in form.model.event_pricing" class="tab-pane fade"
                                         v-bind:class="[index == 0 ? 'show active':'']" v-bind:key="'p'+index"
                                         v-bind:id="'pills-'+index" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-address">Pricing
                                                        Name</label>
                                                    <input :disabled="detailMode" v-model="eventPrice.name" id="input-address"
                                                           class="form-control form-control-alternative"
                                                           placeholder="Pricing Name" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-lg-12">
                                                <label class="form-control-label" for="input-city">Price Date
                                                    Applies</label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group input-group-alternative">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">From</span>
                                                    </div>
                                                    <vuejs-datepicker :disabled="detailMode" input-class="form-control"
                                                                      wrapper-class="wrapper-datepicker"
                                                                      :value="form.model.date"
                                                                      v-model="eventPrice.dateFrom"
                                                                      name="uniquename"></vuejs-datepicker>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group input-group-alternative">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">To</span>
                                                    </div>
                                                    <vuejs-datepicker :disabled="detailMode" input-class="form-control"
                                                                      wrapper-class="wrapper-datepicker"
                                                                      v-model="eventPrice.dateTo"

                                                                      :value="form.model.date"
                                                                      name="uniquename"></vuejs-datepicker>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" v-for="(cat,index) in eventPrice.price" v-bind:key="index">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" v-if="index==0" for="input-email">Category
                                                        Participants</label>
                                                    <input :disabled="detailMode" type="text" readonly v-model="cat.condition"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" v-if="index==0">Price</label>
                                                    <div class="input-group input-group-alternative">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">IDR</span>
                                                        </div>
                                                        <money :disabled="detailMode" v-model="cat.price"
                                                               v-bind="money"
                                                               class="form-control"></money>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="card-footer text-right">
                            <button v-if="!detailMode" v-on:click="save" v-bind:disabled="form.saving" type="button"
                                    class="btn btn-primary"><i
                                        :class="[form.saving? 'fa fa-spin fa-spinner':'fa fa-save']"></i> Save
                            </button>
                            <button v-if="detailMode" type="button" v-on:click="detailMode = false" class="btn btn-default"><i
                                        class="fa fa-edit"></i> Edit
                            </button>
                            <button type="button" v-on:click="formCancel" class="btn btn-default"><i
                                        class="fa fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-else="!form.show" key="table" class="row">
            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>Event List</h3>
                            </div>
                            <div class="col-6 text-right">
                                <button @click="onAdd" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Add Event</button>
                                <button  type="button" class="btn btn-primary"  data-toggle="modal" data-target="#modal-event-category"><i class="fa fa-book"></i> Event Categories List</button>
                            </div>
                        </div>
                    </div>

                    <datagrid
                            ref="datagrid"
                            api-url="<?= base_url('admin/event/grid'); ?>"
                            :fields="[{name:'name',sortField:'name'}, {name:'kategory',sortField:'kategory','title':'Category'},{name:'id',title:'Actions',titleClass:'action-th'}]">
                        <template slot="id" slot-scope="props">
                            <div class="table-button-container">
                                <button @click="editRow(props)" class="btn btn-warning btn-sm">
                                    <span class="fa fa-pen"></span> Edit
                                </button>
                                <button @click="detailRow(props)" class="btn btn-info btn-sm">
                                    <span class="fa fa-search"></span> Detail
                                </button>
                                <button @click="deleteRow(props)" class="btn btn-danger btn-sm">
                                    <span class="fa fa-trash"></span> Delete
                                </button>
                            </div>
                        </template>

                    </datagrid>
                </div>

            </div>
        </div>
    </transition>

    <div class="modal" id="modal-event-category">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Event Categories List</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <input v-model="new_event_category" type="text" class="form-control" @keyup.enter="addEventCategory" placeholder="New Event Category"/>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" @click="addEventCategory"><i class="fa fa-plus"></i> </button>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li v-for="(cat,index) in eventCategory" class="list-group-item d-flex justify-content-between align-items-center">
                            {{ cat }}
                            <button @click="removeEventCategory(index)" class="btn badge badge-primary badge-pill"><i class="fa fa-times"></i></button>
                        </li>
                    </ul>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Table -->

<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/v-money.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script>
    var tempCategory = <?=Settings_m::eventCategory();?>;
    function model() {
        return {
            kategory:"",
            event_pricing: [
                {
                    name: "-",
                    condition_date: "",
                    price:<?=json_encode($pricingDefault);?>
                }
            ]
        }
    }

    function postCategory(cat) {
        return $.post('<?=base_url('admin/setting/save/'.Settings_m::EVENT_CATEGORY);?>',{value:cat});
    }

    function formatDate(date) {
        if(typeof date != 'undefined' && date != "")
            date = moment(date);

        if (date)
            return date.format('Y-MM-DD');
        return "";
    }

    var app = new Vue({
        el: '#app',
        components: {
            vuejsDatepicker,
        },
        data: {
            new_event_category:'',
            message: '',
            error: null,
            detailMode:false,
            money: {
                decimal: ',',
                thousands: '.',
                precision: 0,
                masked: false
            },
            eventCategory:<?=Settings_m::eventCategory();?>,
            form: {
                show: false,
                title: "",
                saving: false,
                model: model()
            },

        },
        methods: {
            addPricing: function () {
                this.form.model.event_pricing.push({
                    name: "-",
                    condition_date: "",
                    price:<?=json_encode($pricingDefault);?>
                });
            },
            onAdd: function () {
                this.form.title = "Add Event";
                this.form.show = true;
                this.form.model = model();
                this.error = null;
                this.detailMode = false;
            },
            detailRow:function(row){
                this.form.title = "Detail Event";
                app.$refs.datagrid.loading = true;
                var url = "<?=base_url('admin/event/detail');?>";
                $.post(url,{id:row.row.id},null,'JSON')
                    .done(function (res) {
                        app.form.show = true;
                        app.form.model = res;
                        app.detailMode = true;
                    }).fail(function (xhr) {
                    Swal.fire("Failed","Failed to load data !","error");
                }).always(function () {
                    app.$refs.datagrid.loading = false;

                });
                this.error = null;
            },
            editRow:function(row){
                this.detailMode = false;
                this.form.title = "Edit Event";
                app.$refs.datagrid.loading = true;
                var url = "<?=base_url('admin/event/detail');?>";
                $.post(url,{id:row.row.id},null,'JSON')
                    .done(function (res) {
                        app.form.show = true;
                        app.form.model = res;
                    }).fail(function (xhr) {
                    Swal.fire("Failed","Failed to load data !","error");
                }).always(function () {
                    app.$refs.datagrid.loading = false;

                });
                this.error = null;
            },
            deleteRow: function (row) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        var url = "<?=base_url('admin/event/delete');?>";
                        $.post(url,row.row,null)
                            .done(function () {
                                app.$refs.datagrid.refresh();
                            }).fail(function (xhr) {
                                Swal.fire("Failed","Failed to delete !","error");
                        });
                    }
                })

            },
            formCancel: function () {
                this.form.show = false;
            },
            addEventCategory:function(){
                if(this.new_event_category != ""){
                    tempCategory.push(this.new_event_category);
                    postCategory(tempCategory).done(function () {
                        app.eventCategory.push(app.new_event_category);
                        app.new_event_category = "";
                    }).fail(function () {
                        tempCategory.pop();
                        Swal.fire("Failed","Failed to save !","error");
                    });
                }

            },
            removeEventCategory:function(index){
                var value = tempCategory[index];
                tempCategory.splice(index,1);
                postCategory(tempCategory).done(function () {
                    app.eventCategory.splice(index,1);
                }).fail(function () {
                    tempCategory.push(value);
                    Swal.fire("Failed","Failed to remove !","error");
                });
            },
            save: function () {
                this.error = null;
                this.form.saving = true;
                this.form.model.event_pricing.forEach(function (i, v) {
                    if (i.dateFrom || i.dateTo)
                        app.form.model.event_pricing[v].condition_date = formatDate(i.dateFrom) + ":" + formatDate(i.dateTo);
                })
                $.post("<?=base_url('admin/event/save');?>", this.form.model, null, 'JSON')
                    .done(function (res, text, xhr) {
                        app.message = res.msg;
                        app.form.show = false;
                    }).fail(function (xhr) {
                    app.error = xhr.responseJSON;
                }).always(function () {
                    app.form.saving = false;
                });
            }
        }
    });
</script>
<?php $this->layout->end_script(); ?>
