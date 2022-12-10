<?php

/**
 * @var $pricingDefault
 */
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8" xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"></div>

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
        <div v-if="mode == 'form'" key="form" class="row">
            <div class="col-xl-12">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ form.title }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="#!" v-on:click="formCancel" class="btn btn-sm btn-default"><i class="fa fa-times"></i> </a>
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
                                            <input :disabled="detailMode" type="text" class="form-control form-control-alternative" v-model="form.model.name" placeholder="name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Category</label>
                                            <select :disabled="detailMode" name="kategory" class="form-control  form-control-alternative" v-model="form.model.kategory">
                                                <option value="" hidden selected>Select Event Category</option>
                                                <option v-for="cat in eventCategory" :value="cat">{{ cat}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="input-username">Theme</label>
                                            <input :disabled="detailMode" type="text" class="form-control form-control-alternative" v-model="form.model.theme" placeholder="Theme">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="input-username">Held On
                                                (Date)</label>
                                            <vue-ctk-date-time-picker only-date range :disabled="detailMode" :no-label="true" format="YYYY-MM-DD" formatted="DD MMMM YYYY" v-model="form.model.held_on"></vue-ctk-date-time-picker>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="input-username">Held In
                                                (Place)</label>
                                            <input :disabled="detailMode" type="text" class="form-control form-control-alternative" v-model="form.model.held_in" placeholder="Example RSUD Banjarmasin">
                                        </div>
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="input-username">Kouta</label>
                                            <input :disabled="detailMode" type="text" class="form-control form-control-alternative" v-model="form.model.kouta" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label class="form-control-label">Description</label>
                                            <textarea :disabled="detailMode" v-model="form.model.description" rows="6" class="form-control form-control-alternative"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>
                                            Event Required to follow
                                            <i v-if="loadEvent" class="fa fa-spin fa-spinner"></i>
                                        </label>
                                        <select :disabled="detailMode" name="kategory" class="form-control  form-control-alternative" v-model="form.model.event_required">
                                            <option value="0">No Event</option>
                                            <option v-for="ev in eventList" :value="ev.id">{{ ev.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <!-- Address -->
                            <h6 class="heading-small text-muted mb-4">
                                Zoom Link
                                <button v-if="!detailMode" type="button" class="btn btn-primary btn-sm" v-on:click="newLink"><i class="fa fa-plus"></i> Add
                                    Link
                                </button>

                            </h6>
                            <div class="pl-lg-4 table-responsive">
                                <table class="table table-bordered pre-wrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>
                                                Starting Date <br /><small>(WIB GMT+7)</small>
                                                <button @click="sortingZoomLink" class="btn btn-primary btn-sm" type="button">Sorting Asc</button>
                                            </th>
                                            <th>Room</th>
                                            <th>URL</th>
                                            <th v-if="!detailMode">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(link,index) in form.model.special_link" :key="index">
                                            <td>{{ index+1 }}</td>
                                            <td>
                                                {{ link.date | formatDate }} - {{ link.dateEnd | formatDate }}
                                            </td>
                                            <td class="pre-line">
                                                {{link.room}}
                                            </td>
                                            <td>
                                                {{link.url}}
                                            </td>
                                            <td v-if="!detailMode">
                                                <button type="button" v-on:click="removeLink(index)" class="btn btn-danger btn-sm fa fa-trash"></button>
                                                <button :disabled="link.loadSpeaker == '1'" type="button" v-on:click="editLink(index)" class="btn btn-danger btn-sm fa fa-edit">
                                                    <i v-if="link.loadSpeaker == '1'" class="fa fa-spin fa-spinner"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr class="my-4">
                            <!-- Address -->
                            <h6 class="heading-small text-muted mb-4">
                                Event Pricing
                                <button v-if="!detailMode" type="button" class="btn btn-primary btn-sm" v-on:click="addPricing"><i class="fa fa-plus"></i> Add Price Category
                                </button>
                            </h6>
                            <div class="pl-lg-4">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" v-for="(eventPrice,index) in form.model.event_pricing" v-bind:key="'m'+index">
                                        <a class="nav-link" v-bind:class="[index == 0 ? 'active':'']" id="pills-home-tab" data-toggle="pill" v-bind:href="'#pills-'+index" role="tab" aria-controls="pills-home" aria-selected="true">
                                            {{eventPrice.name}}
                                            <span style="border-left: 1px solid;height: 100%; padding: 0px 5px"></span>
                                            <button v-if="!detailMode" type="button" class="btn btn-danger btn-sm fa fa-trash" v-on:click="deletePricing(index,$event)"></button>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div v-for="(eventPrice,index) in form.model.event_pricing" class="tab-pane fade" v-bind:class="[index == 0 ? 'show active':'']" v-bind:key="'p'+index" v-bind:id="'pills-'+index" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-address">Pricing
                                                        Name</label>
                                                    <input :disabled="detailMode" v-model="eventPrice.name" class="form-control form-control-alternative" placeholder="Pricing Name" type="text">
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
                                                    <vuejs-datepicker :disabled="detailMode" input-class="form-control" wrapper-class="wrapper-datepicker" :value="form.model.date" v-model="eventPrice.dateFrom" name="fromdate">
                                                    </vuejs-datepicker>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group input-group-alternative">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">To</span>
                                                    </div>
                                                    <vuejs-datepicker :disabled="detailMode" input-class="form-control" wrapper-class="wrapper-datepicker" v-model="eventPrice.dateTo" :value="form.model.date" name="todate"></vuejs-datepicker>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Show</th>
                                                    <th>Category Participant</th>
                                                    <th>Price</th>
                                                    <th>Price in USD</th>
                                                    <!--													<td></td>-->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(cat,index) in eventPrice.price" v-bind:key="index">
                                                    <td>
                                                        <input type="checkbox" value="1" v-model="cat.show" :disabled="detailMode" />
                                                    </td>
                                                    <td>
                                                        <input :disabled="detailMode" type="text" readonly v-model="cat.condition" class="form-control" />
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">IDR</span>
                                                            </div>
                                                            <money :disabled="detailMode" v-model="cat.price" v-bind="money" class="form-control"></money>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">USD</span>
                                                            </div>
                                                            <money :disabled="detailMode" v-model="cat.price_in_usd" v-bind="money" class="form-control"></money>
                                                        </div>
                                                    </td>
                                                    <!--												<td>-->
                                                    <!--													<button type="button" @click="removePricing(cat.id,index)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </button>-->
                                                    <!--												</td>-->
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button v-if="!detailMode" v-on:click="save" v-bind:disabled="form.saving" type="button" class="btn btn-primary"><i :class="[form.saving? 'fa fa-spin fa-spinner':'fa fa-save']"></i> Save
                            </button>
                            <button v-if="detailMode" type="button" v-on:click="detailMode = false" class="btn btn-default"><i class="fa fa-edit"></i> Edit
                            </button>
                            <button type="button" v-on:click="formCancel" class="btn btn-default"><i class="fa fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-if="mode == 'table'" key="table" class="row">
            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>Event List & Pricing</h3>
                            </div>
                            <div class="col-6 text-right">
                                <button @click="onAdd" type="button" class="btn btn-primary"><i class="fa fa-plus"></i>
                                    Add Event</button>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-event-category"><i class="fa fa-book"></i> Event Categories
                                    List</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <datagrid ref="datagrid" api-url="<?= base_url('admin/event/grid'); ?>" :fields="[{name:'name',sortField:'name'}, {name:'kategory',sortField:'kategory','title':'Category'},{name:'jumlahParticipant',sortField:'jumlahParticipant',title:'Participant'},{name:'id',title:'Actions',titleClass:'action-th'}]">
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
        </div>
    </transition>

    <div class="modal" id="modal-add-link">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">{{linkData.isNew ? "Add" : "Edit"}} Link</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body form-vertical">
                    <div class="form-group">
                        <label>Starting Date</label>
                        <vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm" formatted="DD MMMM YYYY HH:mm" v-model="linkData.model.date"></vue-ctk-date-time-picker>
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <vue-ctk-date-time-picker :no-label="true" format="YYYY-MM-DD HH:mm" formatted="DD MMMM YYYY HH:mm" v-model="linkData.model.dateEnd"></vue-ctk-date-time-picker>
                    </div>
                    <div class="form-group">
                        <label>Room</label>
                        <input v-model="linkData.model.room" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Zoom URL</label>
                        <input v-model="linkData.model.url" type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Recording URL dari Google Drive</label>
                        <input v-model="linkData.model.urlRecording" type="text" class="form-control" />
                    </div>
                    <hr />
                    <div class="form-group">
                        <label>Advertisement</label>
                        <table class="table table-bordered">
                            <tr>
                                <th>Type</th>
                                <th>URL</th>
                                <th>
                                    <button type="button" v-on:click="addAdvertisement" class="btn btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr v-for="(ads,index) in linkData.model.advertisement" :key="index">
                                <td>
                                    <select v-model="ads.type" class="form-control">
                                        <option value="file">File Video</option>
                                        <option value="link">Virtual Booth Link/Site</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" v-model="ads.url" class="form-control" />
                                </td>
                                <td>
                                    <button type="button" v-on:click="linkData.model.advertisement.splice(index,1)" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label>Speakers</label>
                        <table class="table table-bordered">
                            <tr>
                                <th>Image <br /><small>*Max 200 KB</small></th>
                                <th>Name</th>
                                <th>Topic</th>
                                <th>
                                    <button type="button" v-on:click="linkData.model.speakers.push({image:'',name:'',topic:''});" class="btn btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr v-for="(sp,index) in linkData.model.speakers" :key="index+linkData.model.room">
                                <td>
                                    <img v-if="sp.image" :src="sp.image" class="img" style="width:150px" /><br />
                                    <input type="file" accept="image/*" v-on:change="speakerImageChange($event,index);" />
                                </td>
                                <td>
                                    <input type="text" v-model="sp.name" class="form-control" />
                                </td>
                                <td>
                                    <input type="text" v-model="sp.topic" class="form-control" />
                                </td>
                                <td>
                                    <button type="button" v-on:click="linkData.model.speakers.splice(index,1)" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="addLink">{{linkData.isNew ? "Add" : "Edit"}}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

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
                            <input v-model="new_event_category" type="text" class="form-control" @keyup.enter="addEventCategory" placeholder="New Event Category" />
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" @click="addEventCategory"><i class="fa fa-plus"></i> </button>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li v-for="(cat,index) in eventCategory" class="list-group-item d-flex justify-content-between align-items-center">
                            {{ cat }} <button @click="removeEventCategory(index)" class="btn badge badge-primary badge-pill"><i class="fa fa-times"></i></button>
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

<?php $this->layout->begin_head(); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.css">
<style>
    .pre-line {
        white-space: inherit !important;
    }

    .vuetable-td-name {
        white-space: pre-line !important;
        width: 400px;
    }

    .disabled{
		cursor: not-allowed;
		opacity: 0.4;
	}
</style>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<?php $this->layout->end_head(); ?>

<?php $this->layout->begin_script(); ?>
<script src="<?= base_url("themes/script/v-money.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuejs-datepicker.min.js"); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.umd.js" charset="utf-8"></script>
<script src="<?= base_url("themes/script/v-button.js"); ?>"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<script src="<?= base_url("themes/script/v-mapping-p2kb.js"); ?>"></script>
<script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.19.1/package/dist/xlsx.full.min.js"></script>

<script>
    var tempCategory = <?= Settings_m::eventCategory(); ?>;

    function model() {
        return {
            kategory: "",
            special_link: [],
            event_pricing: [{
                name: "-",
                condition_date: "",
                price: <?= json_encode($pricingDefault); ?>
            }]
        }
    }

    function postCategory(cat) {
        return $.post('<?= base_url('admin/setting/save/' . Settings_m::EVENT_CATEGORY); ?>', {
            value: cat
        });
    }

    function formatDate(date) {
        if (typeof date != 'undefined' && date != "")
            date = moment(date);

        if (date)
            return date.format('Y-MM-DD');
        return "";
    }

    Vue.component('vue-ctk-date-time-picker', window['vue-ctk-date-time-picker']);
    Vue.filter('formatDate', function(value) {
        if (value) {
            return moment(value).format('DD MMMM YYYY HH:mm')
        }
    });
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        el: '#app',
        components: {
            vuejsDatepicker,
        },
        data: {
            mode: "table",
            new_event_category: '',
            message: '',
            error: null,
            detailMode: false,
            money: {
                decimal: ',',
                thousands: '.',
                precision: 0,
                masked: false
            },
            linkData: {
                model: {
                    speakers: [],
                    advertisement: [],
                    finishWatch: 0
                },
                isNew: true,
            },
            eventCategory: <?= Settings_m::eventCategory(); ?>,
            form: {
                show: false,
                title: "",
                saving: false,
                model: model()
            },
            loadedSpeaker: 0,
            loadEvent: true,
            eventList: [],
            
        },
        watch: {
            'mode': function(newVal) {
                if (newVal == "form") {
                    this.loadEvent = true;
                    $.get("<?= base_url('admin/event/list_event'); ?>", function(res) {
                        app.eventList = res.data
                    }).always(function() {
                        app.loadEvent = false;
                    })
                } else {
                    this.loadEvent = false;
                }
            }
        },
        methods: {
            sortingZoomLink() {
                this.form.model.special_link.sort((a, b) => {
                    return new Date(a.date) - new Date(b.date)
                })
            },
            addAdvertisement() {
                if (!this.linkData.model.advertisement) {
                    this.linkData.model.advertisement = [];
                }
                this.linkData.model.advertisement.push({
                    type: 'link',
                    url: '',
                    watch: 0
                });
            },
            addPricing: function() {
                this.form.model.event_pricing.push({
                    name: "-",
                    condition_date: "",
                    price: <?= json_encode($pricingDefault); ?>
                });
            },
            speakerImageChange(evt, ind) {
                if (evt.target.files.length > 0) {
                    let size = evt.target.files[0].size / 1024;
                    if (size > 200) {
                        Swal.fire('Warning', "Maximum Filesize is 200 KB !", 'warning');
                        evt.target.value = "";
                    } else {
                        var file = evt.target.files[0];
                        var reader = new FileReader();
                        reader.onloadend = function() {
                            app.linkData.model.speakers[ind].image = reader.result;
                        }
                        reader.readAsDataURL(file);
                    }
                }
            },
            newLink: function() {
                this.linkData.model = {
                    speakers: [],
                    advertisement: [],
                    finishWatch: 0
                };
                this.linkData.isNew = true;
                $("#modal-add-link").modal("show");
            },
            editLink: function(ind) {
                this.linkData.model = this.form.model.special_link[ind];
                this.linkData.isNew = false;
                $("#modal-add-link").modal("show");
            },
            addLink: function() {
                if (this.linkData.isNew) {
                    this.form.model.special_link.push(this.linkData.model);
                }
                $("#modal-add-link").modal("hide");
            },
            detailLink: function(ind) {

            },
            removeLink: function(ind) {
                this.form.model.special_link.splice(ind, 1);
            },
            onAdd: function() {
                this.form.title = "Add Event";
                this.mode = "form";
                this.form.model = model();
                this.error = null;
                this.detailMode = false;
            },
            saveMap(self) {
                self.toggleLoading();
                $.post("<?= base_url('admin/event/map'); ?>/" + this.mapping.id, {
                        map: this.mapping.map
                    }, null, 'JSON')
                    .done(function(res, text, xhr) {
                        if (res.status)
                            app.mode = "table";
                        else
                            Swal.fire('Fail', res.message, 'warning');
                    }).fail(function(xhr) {
                        app.error = xhr.responseJSON;
                        var message = xhr.getResponseHeader("Message");
                        if (message) {
                            Swal.fire('Fail', message, 'error');
                        }
                    }).always(function() {
                        self.toggleLoading();
                    });
            },
            getMap(self, props) {
                self.toggleLoading();
                var url = "<?= base_url('admin/event/map'); ?>/" + props.row.id;
                $.get(url)
                    .done((res) => {
                        this.mode = "mapping";
                        this.mapping = res.data;
                        this.listAktivitas = res.aktivitas;
                    }).fail((xhr) => {
                        var message = xhr.getResponseHeader("Message");
                        if (!message)
                            message = 'Server fail to response !';
                        Swal.fire('Fail', message, 'error');
                    }).always(() => {
                        self.toggleLoading();
                    });
            },
            detailRow: function(row) {
                this.form.title = "Detail Event";
                app.$refs.datagrid.loading = true;
                var url = "<?= base_url('admin/event/detail'); ?>";
                $.post(url, {
                        id: row.row.id
                    }, null, 'JSON')
                    .done(function(res) {
                        app.mode = "form";
                        app.form.model = res;
                        app.detailMode = true;
                    }).fail(function(xhr) {
                        var message = xhr.getResponseHeader("Message");
                        if (!message)
                            message = 'Server fail to response !';
                        Swal.fire('Fail', message, 'error');
                    }).always(function() {
                        app.$refs.datagrid.loading = false;

                    });
                this.error = null;
            },
            editRow: function(row) {
                this.detailMode = false;
                this.form.title = "Edit Event";
                app.$refs.datagrid.loading = true;
                var url = "<?= base_url('admin/event/detail'); ?>";
                $.post(url, {
                        id: row.row.id
                    }, null, 'JSON')
                    .done(function(res) {
                        app.mode = "form";
                        if (res.special_link.length > 0) {
                            app.form.saving = true;
                            app.loadedSpeaker = 0;
                            $.each(res.special_link, function(i, v) {
                                if (!v.advertisement)
                                    v.advertisement = [];
                                if (!v.speakers)
                                    v.speakers = [];
                                v.loadSpeaker = '1';
                                $.ajax({
                                    url: `<?= base_url('/themes/uploads/speaker'); ?>/${row.row.id}${i}.json`,
                                    cache: false,
                                    type: 'get',
                                    success: function(speakers) {
                                        v.speakers = speakers;
                                    }
                                }).always(function() {
                                    v.loadSpeaker = '0';
                                    app.loadedSpeaker++;
                                    if (app.loadedSpeaker >= res.special_link.length) {
                                        app.form.saving = false;
                                    }
                                });
                            });
                        }
                        app.form.model = res;
                    }).fail(function(xhr) {
                        var message = xhr.getResponseHeader("Message");
                        if (!message)
                            message = 'Server fail to response !';
                        Swal.fire('Fail', message, 'error');
                    }).always(function() {
                        app.$refs.datagrid.loading = false;
                    });
                this.error = null;
            },
            deleteRow: function(row) {
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
                        var url = "<?= base_url('admin/event/delete'); ?>";
                        $.post(url, row.row, null)
                            .done(function() {
                                app.$refs.datagrid.refresh();
                            }).fail(function(xhr) {
                                var message = xhr.getResponseHeader("Message");
                                if (!message)
                                    message = 'Server fail to response !';
                                Swal.fire('Fail', message, 'error');
                            });
                    }
                });

            },
            formCancel: function() {
                this.mode = "table";
            },
            addEventCategory: function() {
                if (this.new_event_category != "") {
                    tempCategory.push(this.new_event_category);
                    postCategory(tempCategory).done(function() {
                        app.eventCategory.push(app.new_event_category);
                        app.new_event_category = "";
                    }).fail(function(xhr) {
                        tempCategory.pop();
                        var message = xhr.getResponseHeader("Message");
                        if (!message)
                            message = 'Server fail to response !';
                        Swal.fire('Fail', message, 'error');
                    });
                }
            },
            deletePricing(index, ev) {
                var page = app;
                var item = page.form.model.event_pricing[index];
                Swal.fire({
                    title: "Are you sure ?",
                    text: `You will delete "${item.name}" From Pricing`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        if (item.price.filter(e => e.id).length > 0) {
                            var url = "<?= base_url('admin/event/delete_pricing'); ?>";
                            ev.target.innerHTML = "<i class='fa fa-spin fa-spinner'></i>";
                            ev.target.setAttribute("disabled", true);
                            $.post(url, item, null, 'JSON')
                                .done(function(res) {
                                    if (res.status) {
                                        page.form.model.event_pricing.splice(index, 1);
                                    } else {
                                        Swal.fire("Failed", res.message, "error");
                                    }
                                }).fail(function(xhr) {
                                    var message = xhr.getResponseHeader("Message");
                                    if (!message)
                                        message = 'Server fail to response !';
                                    Swal.fire('Fail', message, 'error');
                                }).always(function() {
                                    ev.target.innerHTML = "";
                                    ev.target.removeAttribute("disabled");
                                });
                        } else {
                            page.form.model.event_pricing.splice(index, 1);
                        }

                    }
                });
            },
            removePricing(id, index) {
                console.log(id);
            },
            removeEventCategory: function(index) {
                var value = tempCategory[index];
                tempCategory.splice(index, 1);
                postCategory(tempCategory).done(function() {
                    app.eventCategory.splice(index, 1);
                }).fail(function(xhr) {
                    tempCategory.push(value);
                    var message = xhr.getResponseHeader("Message");
                    if (!message)
                        message = 'Server fail to response !';
                    Swal.fire('Fail', message, 'error');
                });
            },
            save: function() {
                this.error = null;
                this.form.saving = true;
                this.form.model.event_pricing.forEach(function(i, v) {
                    if (i.dateFrom || i.dateTo)
                        app.form.model.event_pricing[v].condition_date = formatDate(i.dateFrom) + ":" +
                        formatDate(i.dateTo);
                })
                $.post("<?= base_url('admin/event/save'); ?>", this.form.model, null, 'JSON')
                    .done(function(res, text, xhr) {
                        app.message = res.msg;
                        app.mode = "table";
                    }).fail(function(xhr) {
                        app.error = xhr.responseJSON;
                        var message = xhr.getResponseHeader("Message");
                        if (message) {
                            Swal.fire('Fail', message, 'error');
                        }
                    }).always(function() {
                        app.form.saving = false;
                    });
            }
        }
    });
</script>
<?php $this->layout->end_script(); ?>