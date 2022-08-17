<?php

/**
 * @var array $events
 */
$this->layout->begin_head();
?>
<style>
pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
.string { color: green; }
.number { color: darkorange; }
.boolean { color: blue; }
.null { color: magenta; }
.key { color: red; }
.show-more{
    color: purple;
}
.show-more:hover{
    cursor: pointer;
    color: blue;
}
</style>
<?php
$this->layout->end_head();
?>
<div class="header bg-primary pb-8 pt-5 pt-md-8"></div>
<!-- Page content -->
<div class="container-fluid mt--7">
    <!-- Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-3">
                            <h3>Log Aktivitas</h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <datagrid ref="datagrid" api-url="<?= base_url('admin/log_proses/grid'); ?>" :fields="[{name:'date',title:'Waktu',sortField:'date'},{name:'controller',title:'URL Akses',sortField:'controller'},{name:'username',sortField:'username','title':'Username'},{name:'request',sorField:'request',title:'Data Yang Dikirim'}]">
                        <template slot="date" slot-scope="props">
                            {{ props.row.date | formatDate }}
                        </template>
                        <template slot="request" slot-scope="props">
                            <pre v-html="prettyJson(props.row.request)" style="overflow-y:clip" :style="{height:props.row.height}">
                            </pre>
                            <span class="show-more" @click="props.row.height = props.row.height == '100px' ? '100%':'100px'">
                                Show {{ (props.row.height == '100px' ? 'More':'Less') }}
                            </span>

                        </template>
                    </datagrid>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $this->layout->begin_script(); ?>
<script>
    Vue.filter('formatDate', function(value) {
        if (value) {
            return moment(value).format('DD MMMM YYYY HH:mm')
        }
    });
    var app = new Vue({
        el: '#app',
        data: {},
        methods: {
            syntaxHighlight(json) {
                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                    var cls = 'number';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            cls = 'key';
                        } else {
                            cls = 'string';
                        }
                    } else if (/true|false/.test(match)) {
                        cls = 'boolean';
                    } else if (/null/.test(match)) {
                        cls = 'null';
                    }
                    return '<span class="' + cls + '">' + match + '</span>';
                });
            },
            prettyJson(jsonString) {
                let object = JSON.parse(jsonString);
                return this.syntaxHighlight(JSON.stringify(object, null, 2));
            }
        },
    });
</script>
<?php $this->layout->end_script(); ?>