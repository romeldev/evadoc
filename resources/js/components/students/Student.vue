<template>
    <div class="row">
        <!-- Modal -->
        <div class="col-12">
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-file-alt orange"></i>
                                Survey
                            </h5>
                        </div>
                        <div class="modal-body">

                            <div class="callout callout-info">
                                <h5>Course: {{reply.course_name | capitalize}}</h5>

                                <p>Teacher: {{reply.teacher_fullname | capitalize}}</p>
                            </div>

                            <div class="row">
                                <div class="col-12" v-for="(item, key) in reply.survey_items" :key="key">
                                    <p>{{parseInt(key)+1}}. {{item.name | capitalize}}</p>

                                    <select class="form-control form-control-sm" 
                                        v-model="item.value"
                                        :class="{ 'is-invalid': reply.errors.has(`survey_items.${key}.value`) }">
                                            <option value="">{{$t('select')+'...'}}</option>
                                            <option v-for="(opt, key) in evaluation.survey.scale.options" :key="key" :value="opt.value">{{opt.text}}</option>
                                    </select>
                                    <has-error :form="reply" :field="`survey_items.${key}.value`"></has-error>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <a href="#" @click.prevent="send()" class="btn btn-primary">Send</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>
                                <i class="fas fa-chart-bar fa-fw"></i>
                                {{ evaluation.title|capitalize }}
                                <span class="badge badge-success">{{evaluation.period_text}}</span>
                            </h5>
                            <p class="text-justify text-muted font-italic">
                                {{ evaluation.descrip }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <td>Code</td>
                                <td>Course</td>
                                <td>Teacher</td>
                                <td width="100" class="text-center">Survey</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(course, key) in student.courses" :key="key">
                                <td>{{course.code}}</td>
                                <td>{{course.name}} ({{course.group}})</td>
                                <td>{{course.teacher_fullname}}</td>
                                <td class="text-center">
                                    <a href="#" onclick="return false;" title="already answered!" class="text-success" v-if="course.replied">
                                        <i class="fas fa-check fa-fw "></i>
                                    </a>
                                    <a href="#" title="reply" @click.prevent="openSurvey(course)" v-else> <i class="fas fa-edit fa-fw"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <i class="fas fa-user-graduate"></i>
                    {{ student.fullname }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data() {
        return {
            student_code: '',
            student: {},
            evaluation: {
                survey: {}
            },

            reply: new Form({
                school_code: '',
                evaluation_id: '',
                student_code: '',
                teacher_code: '',
                course_code: '',
                course_group: '',
                survey_items: [],
                teacher_fullname: '',
                course_name: '',
            }),
        }
    },

    created() {
        this.student_code = this.$route.params.code;
        this.getEvaluation();
        this.getStudent();
    },

    methods: {

        openSurvey( course ){
            this.reply.evaluation_id = this.evaluation.id,
            this.reply.student_code = this.student.code;
            this.reply.school_code = this.student.school_code;
            this.reply.teacher_code = course.teacher_code;
            this.reply.course_code = course.code;
            this.reply.course_group = course.group;
            this.reply.survey_items = JSON.parse( JSON.stringify(this.evaluation.survey.items) );
            this.reply.course_name = course.name+' ('+course.group+')';
            this.reply.teacher_fullname = course.teacher_fullname;
            this.reply.survey_items.forEach( item => item.value = '1' );
            $('#myModal').modal('show');
        },

        send() {
            this.$Progress.start();

            this.reply.post('/api/student/survey')
            .then( res => {
                this.$Progress.finish();
                if( res.data ){
                    let course_code = this.reply.course_code;
                    this.student.courses.find(c => c.code==course_code).replied = true;
                    $('#myModal').modal('hide');
                }
            })
            .catch( err => {
                this.$Progress.fail();
                console.log('ERROR', err)
            })
        },

        // OK
        getStudent(){
            axios('/api/student/'+this.student_code)
            .then( res => {
                this.student = res.data;
            })
            .catch(err => {
                console.log(err.response);
            })
        },

        // OK
        getEvaluation(){
            axios('/api/student/evaluation')
            .then( res => {
                this.evaluation = res.data.data;
            })
            .catch(err => {
                console.log(err.response);
            })
        },

    }
}
</script>