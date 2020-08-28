<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-file-alt fa-fw"></i>
                    {{ $t('evaluation') | capitalize }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <dt class="col-sm-4">{{ $t('title') | capitalize }}</dt>
                    <dd class="col-sm-8">{{evaluation.title}}</dd>

                    <dt class="col-sm-4">{{ $t('date') | capitalize }}</dt>
                    <dd class="col-sm-8">{{evaluation.date_start}} -  {{evaluation.date_end}}</dd>

                    <dt class="col-sm-4">{{ $t('survey') | capitalize }}</dt>
                    <dd class="col-sm-8">{{evaluation.survey_text}}</dd>

                    <dt class="col-sm-4">{{ $t('level') | capitalize }}</dt>
                    <dd class="col-sm-8">{{evaluation.level_name}}</dd>

                    <dt class="col-sm-12">{{ $t('description') | capitalize }}</dt>
                    <dd class="col-sm-12">{{evaluation.descrip}}</dd>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: [ 'id' ],

    data() {
        return {
            evaluation: new Form({
                id: null,
                title: '',
                descrip: '',
                period_id: '',
                period_text: '',
                date_start: moment().format('YYYY-MM-DD'),
                date_end: moment().add(1,'days').format('YYYY-MM-DD'),
                survey_id: '',
                survey_text: '',
                level_id: '',
                level_name: '',
                indicators: [],
            }),
        }
    },

    mounted() {
        this.getEvaluation();
    },

    methods: {
        getEvaluation(){
            axios.get(`/api/evaluations/${this.id}`)
            .then( res => {
                // console.log(res.data.data);
                this.evaluation.fill(res.data.data);
            })
            .catch( err => {
                console.log('ERROR', err);
            })
        }
    },
}
</script>
