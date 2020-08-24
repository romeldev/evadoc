<style>
  .small {
    max-width: 200px;
    margin:  10px auto;
  }
  
</style>

<template>
    <div>

        <div class="row no-print">

            <div class="col-12 form-group">
                <div class="input-group" :class="{ 'is-invalid': report.errors.has('school_code') }">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width:100px;">School</span>
                    </div>
                    <select id="school_code" 
                        v-model="report.school_code"
                        class="form-control form-control"
                        >
                            <option value="">select...</option>
                            <option v-for="(school,keye) in meta.schools" :key="keye" :value="school.code">
                                {{school.name}}
                            </option>
                    </select>
                </div>
                <has-error :form="report" field="school_code"></has-error>
            </div>

            <div class="col-md-5 form-group" >
                <div class="input-group" :class="{ 'is-invalid': report.errors.has('evaluation_id') }">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width:100px;">Evaluation</span>
                    </div>
                    <select v-model="report.evaluation_id" id="evaluation_id" class="form-control form-control">
                        <option value="">select...</option>
                        <option v-for="(evaluation,keye) in meta.evaluations" :key="keye" :value="evaluation.id">
                            {{evaluation.title}} ({{evaluation.period_text}})
                        </option>
                    </select>
                </div>
                <has-error :form="report" field="evaluation_id"></has-error>
            </div>

            <div class="col-md-5 form-group">
                <div class="input-group" :class="{ 'is-invalid': report.errors.has('report_type') }">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width:100px;">Report</span>
                    </div>
                    <select v-model="report.report_type" id="report_type" class="form-control">
                        <option value="">select...</option>
                        <option :value="TYPE_INDICATORS">TYPE_INDICATORS</option>
                        <option :value="TYPE_SURVEY">TYPE_SURVEY</option>
                        <option :value="TYPE_SURVEY_AVG">TYPE_SURVEY_AVG</option>
                    </select>
                </div>
                <has-error :form="report" field="report_type"></has-error>
            </div>

            <div class="col-md-2 form-group">
                <a href="#" class="btn btn-block btn-outline-secondary" @click.prevent="getReport()">
                    <i class="fas fa-search"></i> Search</a>
            </div>

        </div>

        <div class="invoice p-3 mb-3">
            <div class="row">
                <div class="col-sm-12 table-responsive">
                    <div v-html="content" class="table-responsive"></div>
                </div>

                <div class="col-sm-4" v-if="datachart.length!==0">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm">
                                <tbody>
                                    <tr v-for="(item,key) in datachart" :key="key">
                                        <th>{{item.label}}</th>
                                        <td>{{item.number}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>

                <div class="col-sm-8" v-if="datachart.length!==0">
                    <div class="card">
                        <div class="card-body">
                            <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                                <pie-chart :chart-data="datacollection" :height="300" style="height: 300px;"></pie-chart>                      
                            </div> 
                        </div>
                    </div>
                    <!-- <div class="small">
                    </div> -->
                </div>
            </div>

            <!-- this row will not appear when printing -->
            <div class="row no-print mt-3">
                <div class="col-12">
                    <a href="#" @click.prevent="print()" class="btn btn-outline-secondary">
                        <i class="fas fa-print"></i> Print
                    </a>
                    <a href="#" @click.prevent="generate('pdf')" class="btn btn-danger float-right">
                        <i class="fas fa-download"></i> Generate PDF
                    </a>

                    <a href="#" @click.prevent="generate('excel')" class="btn btn-success float-right mr-2">
                        <i class="fas fa-download"></i> Generate Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- Modal Crud -->
        <div class="modal fade" id="survey_teacher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-tags fa-fw"></i>
                            Survey
                            <a href="#" @click.prevent="printSurvey()" class="btn btn-sm btn-outline-secondary"><i class="fas fa-print"></i></a>
                        </h5>
                    </div>
                    <div class="modal-body" id='printSurvey'>
                        <div v-html="content_single" class="table-responsive"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    import PieChart from './PieChart.js'
    
    
    export default {

        components: { PieChart },

        data() {
            return {
                modal: null,
                meta: {
                    evaluations: [],
                    schools: [],
                },

                TYPE_INDICATORS: 1,
                TYPE_SURVEY: 2,
                TYPE_SURVEY_AVG: 3,
                TYPE_SURVEY_SINGLE: 4,

                report: new Form({
                    school_code: 24,
                    evaluation_id: 1,
                    report_type: 1,
                }),

                content: '',
                content_single: '',

                datacollection: {},
                datachart: [],
                errors: [],
            }
        },

        created() {

        },

        mounted(){
            this.modal = $('#survey_teacher');
            let vm = this;
            $(document).on('click','.btn-show-survey-single', function(e) {
                e.preventDefault();
                let params = JSON.parse( $(this).siblings()[0].value )
                vm.showsSurveySingle(params);
            })

            this.getMeta();
        },

        methods: {

            printSurvey()
            {
                // let stylesHtml = '';
                // for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
                //     stylesHtml += node.outerHTML;
                // }
                // console.log(myStyles);

                this.$htmlToPaper('printSurvey');
            },

            fillDataChart ( datachart ) {

                let labels = [];
                let data = [];
                let colours = [];

                this.datachart.forEach(item => {
                    labels.push(item.label)
                    data.push(item.percent)
                    colours.push(item.colour)
                });

                this.datacollection = {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colours,
                    }],
                    
                }
            },

            getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            },

            showsSurveySingle(params){
                this.modal.modal('show');
                params.report_type = 4;
                this.report.get(`/api/reports/report`, {params})
                .then( res => {
                    this.content_single = res.data;
                })
                .catch( err => {
                    console.log('ERROR', err);
                })
            },
            
            print() {
                window.print();
            },

            generate( type ) {
                axios({
                    url: '/api/reports/generate',
                    params: { 'type': type, 'content': this.content },
                    method: 'POST',
                    responseType: 'blob',
                }).then((response) => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');
                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'file.xls');
                    document.body.appendChild(fileLink);
                    fileLink.click();
                });
            },

            getMeta() {
                axios.get(`/api/reports/meta`)
                .then( res => {
                    this.meta.evaluations = res.data.evaluations;
                    this.meta.schools = res.data.schools;
                })
                .catch( err => {
                    console.log('ERROR', err);
                })
            },

            getReport() {
                this.datachart = [];

                this.report.get(`/api/reports/report`, {params: {
                    'school_code': this.report.school_code,
                    'evaluation_id': this.report.evaluation_id,
                    'report_type': this.report.report_type,
                }})
                .then( res => {

                    if( this.report.report_type == this.TYPE_SURVEY ){
                        this.content = res.data.view;
                        this.datachart = res.data.datachart;
                        this.fillDataChart();
                    }else{
                        this.content = res.data;
                    }
                })
                .catch( err => {
                    console.log('ERROR', err.response);
                })
            },

            back() {
                this.$router.push('/evaluations');
            }
        },
    }
</script>

