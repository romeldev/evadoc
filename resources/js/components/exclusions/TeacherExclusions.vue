<style>
    .label-check {
        cursor: pointer;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>

<template>
    <div>
        <!-- Card Table -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-8">
                        <h5> 
                            <i class="fas fa-tags fa-fw"></i>
                            {{ $t(resource) | capitalize }}
                            <a href="" @click.prevent="save()" class="badge badge-success">save</a>
                        </h5>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="input-group input-group-sm">
                                <input type="search" v-model="search" class="form-control form-control-sm" placeholder="search teacher....">
                            </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>{{ $t('teacher') | capitalize }}</th>
                            <th>{{ $t('courses') | capitalize }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="teacher in filterTeachers" :key="teacher.code">
                            <td class="text-center">{{teacher.code}}</td>
                            <td>
                                <div class="form-check pl-1">

                                    <input type="checkbox" style="display:none;"
                                        class="form-check-input check-teacher" 
                                        :id="'teacher-'+teacher.code" v-model="teacher.status"
                                        @change="handleTeacherCheck( $event, teacher )"
                                        :value="teacher.status">

                                    <label class="form-check-label label-check" 
                                        :for="'teacher-'+teacher.code">
                                            <i class="fas fa-check-square text-success fa-fw" v-if="!teacher.status"></i>
                                            <i class="fas fa-window-close text-danger fa-fw" v-else></i>
                                            {{teacher.fullname}}
                                    </label>

                                </div>
                            </td>
                            <td>
                                <div class="form-check pl-1" v-for="(course, keyc) in teacher.courses" :key="keyc">
                                    <input type="checkbox" style="display:none;"
                                        class="form-check-input check-course" 
                                        :id="'course-'+course.key" v-model="course.status"
                                        @change="handleCourseCheck( $event, teacher, course )"
                                        :value="course.status">

                                    <label class="form-check-label label-check" 
                                        :for="'course-'+course.key">
                                            <i class="fas fa-check-square text-success fa-fw" v-if="!course.status"></i>
                                            <i class="fas fa-window-close text-danger fa-fw" v-else></i>
                                            {{course.name}}
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                resource: 'exclusions',
                teachers: [],
                search:  '',
                school_code: 24,
                evaluation_id: 1,
            }
        },

        created() {
            this.getTeachers();
        },

        computed: {
            filterTeachers() {
                return this.teachers.filter(teacher => {
                    return teacher.fullname.toLowerCase().includes(this.search.toLowerCase())
                })
            }
        },

        methods: {

            handleTeacherCheck( evt, teacher ) {
                let selected = teacher.status;
                teacher.status = selected;
                teacher.courses.forEach( course => course.status = selected )
            },

            handleCourseCheck( evt, teacher, course ) {
                console.log(course);
                let selected = course.status
                course.status = selected

                let selectedCourses = 0;
                teacher.courses.forEach( course => {
                    if( course.status===true) selectedCourses++
                })
                teacher.status = selectedCourses == teacher.courses.length
            },

            getTeachers() {
                axios.get(`/api/${this.resource}/teachers`, {params: {
                    'evaluation_id':  this.evaluation_id,
                    'school_code':  this.school_code,
                }}).then( res => {
                    this.teachers = res.data;
                })
                .catch( err => {
                    console.log('ERROR', err);
                })
            },

            save( evt, teacher, course ){
                this.$Progress.start();
                axios.post(`/api/${this.resource}/save-teachers-courses-exclusions`, 
                {
                    'evaluation_id': this.evaluation_id,
                    'school_code': this.school_code,
                    'teachers':  this.teachers,
                }).then( res => {
                    this.$Progress.finish();
                    toast.fire({ icon: 'success', title: 'Data saved!' });
                })
                .catch( err => { 
                    toast.fire({ icon: 'error', title: err.request.statusText });
                })
            },

            crud() {
                this.$Progress.start();
                let url = this.form.action=='create'? `/api/${this.resource}`
                    : `/api/${this.resource}/${this.form.id}`;

                this.form.post(url)
                .then( res => {
                    this.$Progress.finish();
                    this.getItems();
                    this.closeModal();
                })
                .catch( res => { 
                    this.$Progress.fail();
                    console.log('EROR', res)
                })
            },
            
        },
    }
</script>