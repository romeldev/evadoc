<template>
    <div class="row">

        <!-- Evaluation info -->
        <div class="col-md-6 col-lg-4">
            <evaluation-info :id="qualify.evaluation_id"></evaluation-info>
        </div>

        <!-- Indicator form -->
        <div class="col-md-6 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title" id="exampleModalLabel">
                        <i class="fas fa-tags fa-fw"></i>
                        {{ $t('qualify') | capitalize }}
                    </h5>
                    <div class="card-tools">
                        <router-link :to="{name: 'evaluation.teachers', params: {'evaluation_id': qualify.evaluation_id} }" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-alt-circle-left"></i>
                            {{ $t('back')}}
                        </router-link>
                    </div>
                </div>
                <div class="card-body">
                    <form @submit.prevent="save()" class="row">

                        <div class="col-sm-12 form-group">
                            <label>{{ $t('teacher') | capitalize }}</label>
                            <input type="text" readonly class="form-control form-control-sm" v-model="teacher.fullname">
                            <input type="hidden" class="form-control form-control-sm" v-model="qualify.teacher_code">
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="course_id">{{ $t('courses') | capitalize }}</label>
                            <select class="form-control form-control-sm"
                                @change="getCourseQualify()"
                                :class="{ 'is-invalid': qualify.errors.has('course_key') }" 
                                v-model="qualify.course_key">
                                <option value="">Select...</option>
                                <option v-for="(course, key) in teacher.courses" 
                                    :key="key"
                                    :value="course.key"
                                    v-html="course.name+' ('+course.group+')'"></option>
                            </select>
                            <has-error :form="qualify" field="course_key"></has-error>
                        </div>

                        <div class="col-sm-12">

                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th width="30" class="text-center">#</th>
                                        <th>{{ $t('indicator') | capitalize }}</th>
                                        <th>{{ $t('value') | capitalize }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(indicator, key) in qualify.indicators" :key="key">
                                        <td class="text-center">{{key+1}}</td>
                                        <td>{{indicator.name}}</td>
                                        <td width="100">
                                            <input type="text" 
                                                class="form-control form-control-sm"
                                                v-model="indicator.value"
                                                :readonly="indicator.editable!=1"
                                                :class="{'is-invalid':qualify.errors.has(`indicators.${key}.value`)}">

                                            <has-error :form="qualify" :field="`indicators.${key}.value`"></has-error>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                   

                        </div>
                        <div class="col-12 form-group text-right mb-0">
                            <router-link :to="{name: 'evaluation.teachers', params: {evaluation_id:qualify.evaluation_id}}" class="btn btn-sm btn-secondary">{{ $t('back') }}</router-link>
                            <a href="#" @click.prevent="save()" class="btn btn-sm btn-primary">{{ $t('qualify') }}</a>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import EvaInfo from './EvaInfo';

    export default {

        components: {
            'evaluation-info': EvaInfo,
        },

        data() {
            return {

                evaluation: {},
                teacher: {},

                qualify: new Form({
                    id: '',
                    evaluation_id: '',
                    teacher_code: '',
                    course_key: '',
                    indicators: [],
                    updated_at: '',
                }),

                TYPE_MANUAL: 1,
                TYPE_SURVEY: 2,
            }
        },

        created() {
            this.qualify.evaluation_id = this.$route.params.evaluation_id;
            this.qualify.teacher_code = this.$route.params.teacher_code;
        },

        mounted(){
            this.getMeta();
            // this.getQualifyIndicators();
        },

        computed: {

        },


        methods: {
            
            getMeta() {
                let params= {
                    evaluation_id: this.qualify.evaluation_id,
                    teacher_code: this.qualify.teacher_code,
                }
                axios.get('/api/calculator/meta-evaluation-qualify',  {params})
                .then( res => {
                    this.evaluation = res.data.evaluation;
                    this.teacher = res.data.teacher;
                })
                .catch( err => {
                    console.log('ERROR', err);
                })
            },
            
            getQualifyIndicators() {
                axios.get('/api/calculator/qualify',  {
                    evaluation_id: this.qualify.evaluation_id,
                    teacher_code: this.qualify.teacher_code,
                    course_code: this.qualify.course_code,
                })
                .then( res => {
                    this.qualify.indicators = res.data.indicators;
                    console.log(this.qualify);
                })
                .catch( err => {
                    console.log('ERROR', err);
                })
            },

            getCourseQualify() {
                let params= {
                    evaluation_id: this.qualify.evaluation_id,
                    teacher_code: this.qualify.teacher_code,
                    course_key: this.qualify.course_key,
                }

                this.qualify.get(`/api/calculator/course-qualify`, {params})
                .then( res => {
                    this.qualify.fill(res.data);
                })
                .catch( err => {
                    console.log('ERROR', err);
                })
            },

            save() {
                this.qualify.post(`/api/calculator/save-course-qualify`)
                .then( res => {
                    console.log(res);
                    toast.fire({ icon: 'success', title: 'Qualify saved!' });
                })
                .catch( err => {
                    console.error(err);
                })
            },

        },
    }
</script>