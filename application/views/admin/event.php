<?php
/**
 * @var $pricingDefault
 */
?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8"></div>

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
                                            <input type="text" class="form-control form-control-alternative" v-model="form.model.name"
                                                   placeholder="name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Category</label>
                                            <?= form_dropdown('kategory', ['Scientific', 'Family Fun'], '', ['class' => 'form-control  form-control-alternative','v-model'=>'form.model.kategory']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group focused">
                                            <label class="form-control-label">Description</label>
                                            <textarea v-model="form.model.description" rows="4" class="form-control form-control-alternative"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <!-- Address -->
                            <h6 class="heading-small text-muted mb-4">
                                Event Pricing
                                <button type="button" class="btn btn-primary btn-sm" v-on:click="addPricing"><i class="fa fa-plus"></i> Add Price Category </button>
                            </h6>
                            <div class="pl-lg-4">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" v-for="(eventPrice,index) in form.model.event_pricing" v-bind:key="'m'+index">
                                        <a class="nav-link" v-bind:class="[index == 0 ? 'active':'']"  id="pills-home-tab" data-toggle="pill" v-bind:href="'#pills-'+index" role="tab" aria-controls="pills-home" aria-selected="true">{{eventPrice.name}}</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div v-for="(eventPrice,index) in form.model.event_pricing" class="tab-pane fade" v-bind:class="[index == 0 ? 'show active':'']" v-bind:key="'p'+index" v-bind:id="'pills-'+index" role="tabpanel" >
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-address">Pricing Name</label>
                                                    <input v-model="eventPrice.name" id="input-address" class="form-control form-control-alternative"
                                                           placeholder="Pricing Name" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-lg-12">
                                                <label class="form-control-label" for="input-city">Price Date Applies</label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group input-group-alternative">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">From</span>
                                                    </div>
                                                    <vuejs-datepicker input-class="form-control"
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
                                                    <vuejs-datepicker input-class="form-control"
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
                                                    <input type="text" readonly v-model="cat.condition" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" v-if="index==0">Price</label>
                                                    <div class="input-group input-group-alternative">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">IDR</span>
                                                        </div>
                                                        <money v-model="cat.price"
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
                            <button v-on:click="save" v-bind:disabled="form.saving" type="button" class="btn btn-primary"><i :class="[form.saving? 'fa fa-spin fa-spinner':'fa fa-save']"></i> Save</button>
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
                <vue-bootstrap4-table :rows="dt.rows" :columns="dt.columns" :config="dt.config" :actions="dt.actions"
                                      @on-change-query="onChangeGrid"
                                      :totalRows="dt.total_rows"
                                      @on-add="onAdd" @on-delete="onDelete">
                    <template slot="id" slot-scope="props">
                        <button type="button" class="btn btn-primary">Detail</button>
                        <button type="button" class="btn btn-primary">Edit</button>
                    </template>
                </vue-bootstrap4-table>
            </div>
        </div>
    </transition>
</div>
<!-- Table -->



<?php $this->layout->begin_script(); ?>
<script src="<?=base_url("themes/script/v-money.js");?>"></script>
<script src="<?=base_url("themes/script/vuejs-datepicker.min.js");?>"></script>
<script>
    function defaultModel() {
        return {
            event_pricing: [
                {
                    name:"-",
                    condition_date:"",
                    price:<?=json_encode($pricingDefault);?>
                }
            ]
        }
    }
    function formatDate(date){
        if(date)
            return `${date.getFullYear()}-${(date.getMonth()+1)}-${(date.getDay()+1)}`;
        return "";
    }
    var app = new Vue({
        el: '#app',
        components: {
            vuejsDatepicker
        },
        data: {
            message:'',
            error:null,
            money:{
                decimal: ',',
                thousands: '.',
                precision: 0,
                masked: false
            },
            form: {
                show: false,
                title: "",
                saving:false,
                model: defaultModel()
            },
            dt: {
                actions: [
                    {
                        btn_text: "<i class='fa fa-plus'></i> Add Event",
                        event_name: "on-add",
                        class: "btn btn-primary",
                        event_payload: {
                            msg: "my custom msg"
                        }
                    },
                    {
                        btn_text: "<i class='fa fa-trash'></i> Delete Event",
                        event_name: "on-delete",
                        class: "btn btn-primary",
                        event_payload: {
                            msg: "my custom msg"
                        }
                    }
                ],
                rows: [],
                columns: [
                    {
                        label: 'Event Name',
                        name: 'name',
                        sort: true
                    },
                    {
                        label: "Category",
                        name: "kategory",
                        sort: true,
                    },
                    {
                        label: "Operations",
                        name:"id",
                        sort:false,
                    }
                ],
                config: {
                    checkbox_rows: true,
                    rows_selectable: true,
                    card_title: "Events List",
                    show_reset_button: false,
                    server_mode:true,
                },
                queryParams: {
                    sort: [],
                    filters: [],
                    global_search: "",
                    per_page: 10,
                    page: 1,
                },
                total_rows: 0,
            },

        },
        methods: {
            onChangeGrid:function(params){
                this.dt.queryParams = params;
                this.fetchData();
            },
            fetchData:function(){
                 this.dt.queryParams.columns = this.dt.columns;
                $.get("<?=base_url('admin/event/grid');?>",this.dt.queryParams,null,'JSON')
                    .done(function (res) {
                        app.$data.dt.rows = res.rows;
                        app.$data.dt.total_rows = res.total_rows;
                    }).fail(function (xhr) {

                    })
            },
            addPricing: function(){
                this.form.model.pricing.push({
                    name:"-",
                    condition_date:"",
                    price:<?=json_encode($pricingDefault);?>
                });
            },
            onAdd: function () {
                this.form.title = "Add Event";
                this.form.show = true;
                this.form.model = defaultModel();
                this.error = null;
            },
            onDelete: function () {
                alert("Delete")
            },
            formCancel: function () {
                this.form.show = false;
            },

            save: function () {
                this.error = null;
                this.form.saving = true;
                this.form.model.event_pricing.forEach(function (i,v) {
                    if(i.dateFrom || i.dateTo)
                        app.form.model.event_pricing[v].condition_date = formatDate(i.dateFrom)+":"+formatDate(i.dateTo);
                })
                $.post("<?=base_url('admin/event/save');?>",this.form.model,null,'JSON')
                    .done(function (res,text,xhr) {
                        app.message = res.msg;
                        app.form.show = false;
                    }).fail(function (xhr) {
                        app.error = xhr.responseJSON;
                    }).always(function () {
                        app.form.saving = false;
                });
            }
        },
        mounted:function () {
            this.fetchData();
        }
    });
</script>
<?php $this->layout->end_script(); ?>
