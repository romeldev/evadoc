<template>
    <div>
        <div class="row">

            <!-- Evaluation info -->
            <div class="col-md-6 col-lg-4">
                <evaluation-info :id="evaluation_id"></evaluation-info>
            </div>

            <div class="col-md-6 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title" id="exampleModalLabel">
                            <i class="fas fa-users fa-fw"></i>
                            {{ $t('teachers') | capitalize }}
                        </h5>
                        <div class="card-tools">
                            <router-link :to="{name: 'evaluation.index'}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-alt-circle-left"></i>
                                {{ $t('back')}}
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ $t('fullname') | capitalize }}</th>
                                    <th>{{ $t('record') | capitalize }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(teacher, key) in teachers" :key="key">
                                    <!-- <td>{{teacher.code}}</td> -->
                                    <td class="text-center">{{ parseInt(key)+1 }}</td>
                                    <td>{{ teacher.fullname }}</td>
                                    <td class="text-center">{{ teacher.record.toFixed(2) }}</td>
                                    <td width="100" class="text-center">
                                        <router-link class="btn btn-sm btn-outline-primary"
                                            :to="{name: 'evaluation.qualify', params: {evaluation_id:evaluation_id, teacher_code:teacher.code} }">
                                            {{ $t('qualify') }}
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                action: 'qualify',
                resource: 'evaluations',

                evaluation_id: '',

                teachers: [],

                TYPE_MANUAL: 1,
                TYPE_SURVEY: 2,
            }
        },

        created() {
            this.evaluation_id = this.$route.params.evaluation_id;
        },

        mounted(){
            this.getTeachers();
        },

        computed: {
            
        },


        methods: {

            getTeachers() {
                axios.get(`/api/calculator/record-teachers`, {params: {
                    'evaluation_id': this.evaluation_id,
                }})
                .then( res => {
                    console.log('res', res.data)
                    this.teachers = res.data;

                })
                .catch( err => {
                    console.log('ERROR', err);
                })
            },

            back() {
                this.$router.push('/evaluations');
            }
        },
    }
</script>