<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="modal-type" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-lock fa-fw"></i>
                            {{ $t('type') | capitalize }}
                        </h5>
                        <!-- <p>{{indicator}}</p> -->
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="crud()">
                            <div class="form-group">
                                <label for="name">{{ $t('name') | capitalize }}</label>
                                <select class="form-control form-control-sm"
                                    v-model="indicator.type_id" 
                                    :disabled="onlyRead"
                                    @change="handleSelectType()">
                                    <option v-for="(type, key) in indicatorTypes" :key="key" :value="type.id">{{type.name}}</option>
                                </select>
                            </div>

                            <div class="form-group" v-if="indicator.type_id==2">
                                <select v-model="indicator.items"  class="form-control form-control-sm" multiple :disabled="onlyRead">
                                    <option v-for="(item, key) in surveyItems(form.survey_id)" 
                                        :key="key" 
                                        :value="item.id"
                                        v-html="item.name"></option>
                                </select>
                            </div>

                            <div class="form-group text-right mb-0">
                                <a href="#" @click.prevent="closeType()" class="btn btn-sm btn-secondary">{{ $t('close') }}</a>
                                <a href="#" @click.prevent="saveType()" class="btn btn-sm btn-primary" v-if="!onlyRead">{{ $t('save') }}</a>
                            </div>
                            
                        </form>
                    </div>
                    <!-- <div class="modal-footer">
                        
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-tags fa-fw"></i>
                    {{ $t('evaluation') | capitalize }}
                </h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="crud()" class="row">

                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="title">{{ $t('title') | capitalize }}</label>
                            <input type="text" v-model="form.title" id="title" class="form-control form-control-sm"
                                :class="{ 'is-invalid': form.errors.has('title') }" :readonly="onlyRead">
                            <has-error :form="form" field="title"></has-error>
                        </div>

                        <div class="form-group">
                            <label for="descrip">{{ $t('description') | capitalize }}</label>
                            <input type="text" v-model="form.descrip" id="descrip" class="form-control form-control-sm"
                                :class="{ 'is-invalid': form.errors.has('descrip') }" :readonly="onlyRead">
                            <has-error :form="form" field="descrip"></has-error>
                        </div>

                        <div class="form-group">
                            <label for="period">{{ $t('period') | capitalize }}</label>
                            <select @change="handlePeriod" v-model="form.period_id" id="period_id" class="form-control form-control-sm"
                                :class="{ 'is-invalid': form.errors.has('period_id') }" :disabled="onlyRead">
                                <option value="">Select...</option>
                                <option v-for="(period, key) in periods" :key="key" :value="period.id">{{period.name}}</option>
                            </select>
                            <has-error :form="form" field="period_id"></has-error>
                            <input type="hidden" v-model="form.period_text" class="form-control form-control-sm">
                        </div>

                        <div class="form-group">
                            <label for="date_start">{{ $t('start date') | capitalize }}</label>
                            <input type="date" v-model="form.date_start" id="date_start" class="form-control form-control-sm"
                                :class="{ 'is-invalid': form.errors.has('date_start') }" :readonly="onlyRead">
                            <has-error :form="form" field="date_start"></has-error>
                        </div>

                        <div class="form-group">
                            <label for="date_end">{{ $t('end date') | capitalize }}</label>
                            <input type="date" v-model="form.date_end" id="date_end" class="form-control form-control-sm"
                                :class="{ 'is-invalid': form.errors.has('date_end') }" :readonly="onlyRead">
                            <has-error :form="form" field="date_end"></has-error>
                        </div>
                    </div>
                    
                    <div class="col-sm-12 col-md-6">

                        <div class="form-group">
                            <label for="survey_id">{{ $t('survey') | capitalize }}</label>
                            <select id="survey_id" class="form-control form-control-sm"
                                v-model="form.survey_id" 
                                :class="{ 'is-invalid': form.errors.has('survey_id') }" 
                                :disabled="onlyRead"
                                @change="handleSelectSurvey()">
                                <option value="">Select...</option>
                                <option v-for="(survey, keys) in surveys" 
                                    :key="keys" 
                                    :value="survey.id" 
                                    v-html="survey.title"></option>
                            </select>
                            <has-error :form="form" field="survey_id"></has-error>
                        </div>

                        <div class="form-group">
                            <label for="level_id">{{ $t('level') | capitalize }}</label>
                            <select id="level_id" class="form-control form-control-sm"
                                v-model="form.level_id" 
                                :class="{ 'is-invalid': form.errors.has('level_id') }" 
                                :disabled="onlyRead">
                                <option value="">Select...</option>
                                <option v-for="(level, key) in levels" 
                                    :key="key" 
                                    :value="level.id" 
                                    v-html="level.name"></option>
                            </select>
                            <has-error :form="form" field="level_id"></has-error>
                        </div>

                        <div class="form-group">
                            <label for="indicators">
                                {{ $t('indicators') | capitalize }}
                                <a href="#" v-if="!onlyRead" @click.prevent="addIndicator()" class="badge badge-secondary badge-pill"> <i class="fa fa-plus fa-fw"></i></a>
                            </label>
                            <div style="height: 340px; overflow-y: scroll;" >  
                                <table class="table table-sm table-hover table-bordered" >
                                    <thead>
                                        <tr>
                                            <td width="30" v-if="!onlyRead"></td>
                                            <td width="30">#</td>
                                            <td>{{ $t('name') | capitalize }}</td>
                                            <td width="30" class="text-center"><i class="fa fa-edit" title="editable"></i></td>
                                            <td>{{ $t('type') | capitalize }}</td>
                                            <td>{{ $t('items') | capitalize }}</td>
                                            <td>{{ $t('weight') | capitalize }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(indicator,key) in this.form.indicators" :key="key">
                                            <td class="text-center" v-if="!onlyRead">
                                                <a href="#" @click.prevent="removeIndicator(key)"><i class="text-danger fas fa-trash"></i></a>
                                            </td>
                                            
                                            <td>{{key+1}}</td>
                                            <td>
                                                <input type="text" v-model="indicator.name" class="form-control form-control-sm" :readonly="onlyRead" :class="{ 'is-invalid': form.errors.has(`indicators.${key}.name`) }">

                                                <has-error :form="form" :field="`indicators.${key}.name`"></has-error>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" v-model="indicator.editable" :disabled="onlyRead">
                                            </td>
                                            <td>
                                                <a href="#" @click.prevent="openModalType(key)" class="badge badge-primary badge-pill">{{typeName(indicator.type_id)}}</a>
                                            </td>
                                            
                                            <td>
                                                <div>
                                                    {{ selectedItems(indicator.items) }}
                                                </div>
                                            </td>
                                            <td width="100">
                                                <input type="numeric" v-model="indicator.weight" class="form-control form-control-sm" :readonly="onlyRead" :class="{ 'is-invalid': form.errors.has(`indicators.${key}.weight`) }">
                                                <has-error :form="form" :field="`indicators.${key}.weight`"></has-error>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td v-if="!onlyRead"></td>
                                            <td colspan="6" class="text-right">
                                                TOTAL: {{totalValues}}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div :class="{ 'is-invalid': form.errors.has('indicators') }"></div>
                            <has-error :form="form" field="indicators"></has-error>

                        </div>
                    </div>

                    <div class="col-12 form-group text-right mb-0">
                        <a href="#" @click.prevent="back()" class="btn btn-sm btn-secondary">{{ $t('back') }}</a>
                        <button v-if="form.action=='create'" class="btn btn-sm btn-primary">{{ $t('create') }}</button>
                        <button v-if="form.action=='edit'" class="btn btn-sm btn-success">{{ $t('edit') }}</button>
                        <button v-if="form.action=='delete'" class="btn btn-sm btn-danger">{{ $t('delete') }}</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                modal: null,
                id: null,
                action: 'create',
                resource: 'evaluations',

                form: new Form({
                    id: null,
                    title: '',
                    descrip: '',
                    period_id: '',
                    period_text: '',
                    date_start: moment().format('YYYY-MM-DD'),
                    date_end: moment().add(1,'days').format('YYYY-MM-DD'),
                    survey_id: '',
                    level_id: '',
                    indicators: [],
                    action: 'create',
                    _method: 'POST'
                }),

                indicator: {
                    id: '',  
                    name: 'indicador #1', 
                    editable: true,
                    type_id: '1', 
                    items: [],
                    value: 0,
                },

                index: null,

                periods: [],
                surveys: [],
                levels: [],
                indicatorTypes: [],
            }
        },

        created() {
            this.initModeForm();
        },

        mounted(){
            this.getEvaluation();
            this.getMetaCrudEvaluation();
        },

        computed: {
            totalValues: {
                get(){
                    var total = 0;
                    this.form.indicators.forEach(indicator => { 
                        if( !isNaN(parseInt(indicator.weight)))
                            total += parseInt(indicator.weight) 
                    });
                    return total;
                },
                set(){}
            },

            onlyRead: {
                get(){
                    return this.form.action=='show'||this.form.action=='delete';
                },
                set(){}
            },
        },


        methods: {

            selectedItems( items )
            {
                return items.length == 0? '': items.length;
            },

            //OK
            typeName( type_id )
            {
                let type = this.indicatorTypes.find(x => x.id == type_id);
                return type==null? 'Unknown': type.name;
            },

            surveyItems( survey_id )
            {
                let survey = this.surveys.find(x => x.id == survey_id );
                return survey==null? []: survey.items;
            },

            openModalType(key) {
                this.index = key;
                this.indicator = JSON.parse(JSON.stringify(this.form.indicators[key]));
                $('#modal-type').modal('show');
            },

            saveType() {

                if( this.validateType() ){
                    let indicatorTmp = JSON.parse(JSON.stringify(this.indicator));
                    this.form.indicators[this.index].key = indicatorTmp.key;
                    this.form.indicators[this.index].id = indicatorTmp.id;
                    this.form.indicators[this.index].name = indicatorTmp.name;
                    this.form.indicators[this.index].editable = indicatorTmp.editable;
                    this.form.indicators[this.index].type_id = indicatorTmp.type_id;
                    this.form.indicators[this.index].items = indicatorTmp.items;
                    this.form.indicators[this.index].value = indicatorTmp.value;
                    $('#modal-type').modal('hide');
                }
            },

            closeType() {
                $('#modal-type').modal('hide');
            },

            validateType() {
                if( this.indicator.type_id==2 && this.indicator.items.length == 0){
                    toast.fire({ icon: 'warning', title: 'Select at least one item.' });
                    return false;
                }
                return true;
            },

            addIndicator() {
                this.form.indicators.push({
                    id: '',  
                    name: 'indicador #1', 
                    editable: false,
                    type_id: '1', 
                    items: [],
                    value: 0,
                });
            },

            removeIndicator( key) {
                this.form.indicators.splice(key, 1);
            },

            totalValues()
            {
                const reducer = (acumulator, currentItem) => acumulator +   currentItem.value;
                return this.form.items.reducer(reducer);
            },

            //OK
            initModeForm() {
                let id = this.$route.params.id;

                if (id=='create'){
                    this.form.id = null;
                    this.form.action = id;
                }else{
                    this.form.id = parseInt(id);
                    this.form.action = this.$route.params.action;
                    if( this.form.action==null) this.form.action = 'show';
                }

                if(this.form.action=='create')
                    this.form._method = 'POST'
                else if(this.form.action=='edit')
                    this.form._method = 'PUT'
                else if(this.form.action=='delete')
                    this.form._method = 'DELETE'
                else
                    this.form._method == 'Unknown'
            },

            //OK
            handlePeriod() {
                if( this.form.period_id){
                    var period = this.periods.find( x => x.id == this.form.period_id);
                    this.form.period_text = period.name;
                    this.form.date_start = period.date_start;
                    this.form.date_end = period.date_end;
                }else{
                    this.form.period_text = '';
                    this.form.date_start = moment().format('YYYY-MM-DD');
                    this.form.date_end = moment().add(1,'days').format('YYYY-MM-DD');
                }
                
            },

            //OK
            handleSelectType() {
                if( this.indicator.type_id==1){
                    this.indicator.survey_id = '';
                    this.indicator.items = [];
                }
            },

            //OK
            handleSelectSurvey() {
                this.form.indicators.forEach( indicator => {
                    indicator.type_id = 1;
                    indicator.items = [];
                })
            },

            getMetaCrudEvaluation() {
                axios.get('/api/common?resource=meta-crud-evaluation')
                .then( res => {
                    this.periods = res.data.periods;
                    this.surveys = res.data.surveys;
                    this.levels = res.data.levels;
                    this.indicatorTypes = res.data.indicatorTypes;
                })
                .catch( err => {
                    console.log('ERR_GET_PERIODS', err.response );
                })
            },

            getEvaluation() {
                if( this.form.action != 'create')
                    axios.get(`/api/${this.resource}/${this.form.id}`)
                    .then( res => {
                        this.form.title = res.data.data.title;
                        this.form.descrip = res.data.data.descrip;
                        this.form.period_id = res.data.data.period_id;
                        this.form.period_text = res.data.data.period_text;
                        this.form.date_start = res.data.data.date_start;
                        this.form.date_end = res.data.data.date_end;
                        this.form.indicators = res.data.data.indicators;
                        this.form.survey_id = res.data.data.survey_id;
                        this.form.level_id = res.data.data.level_id;
                        // console.log(this.form);
                    })
                    .catch( err => {
                        console.log('ERROR', err);
                    })
            },

            crud() {
                this.$Progress.start();
                var url = '';
                if( this.form.action == 'create')
                    url = `/api/${this.resource}`;
                else
                    url = `/api/${this.resource}/${this.form.id}`;
             
                this.form.post(url)
                .then( res => {
                    this.$Progress.finish();
                    this.form.reset();
                    this.$router.replace('/evaluations');
                })
                .catch( res => { 
                    this.$Progress.fail();
                    console.log(this.form.errors);
                    console.log('EROR', res)
                })
            },

            back() {
                this.$router.push('/evaluations');
            }
            
        },
    }
</script>