<template>
    <div class="row">

        <div class="col-md-3">
            <div class="card">
                <img class="card-img-top" v-bind:src="form.avatar" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">{{form.fullname|capitalize}}</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Last updated 3 mins ago</small>
                </div>
            </div>
        </div>
        <div class="col-md-9">

            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills mb-0" id="pills-tab" role="tablist">
                        
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab" data-toggle="pill" href="#info" role="tab" aria-controls="pills-info" aria-selected="true">Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="activity-tab" data-toggle="pill" href="#activity" role="tab" aria-controls="pills-info" aria-selected="false">Activity</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="home-tab">
                            <div class="form-group">
                                <label for="fullname">{{ $t('fullname') | capitalize }}</label>
                                <input type="text" v-model="form.fullname" name="fullname" id="fullname" class="form-control form-group-sm"
                                    :class="{ 'is-invalid': form.errors.has('fullname') }" :readonly="action=='show'||action=='delete'">
                                <has-error :form="form" field="fullname"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="name">{{ $t('name') | capitalize }}</label>
                                <input type="text" v-model="form.name" id="name" class="form-control form-group-sm"
                                    :class="{ 'is-invalid': form.errors.has('name') }" :readonly="action=='show'||action=='delete'">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="email">{{ $t('email') | capitalize }}</label>
                                <input type="email" v-model="form.email" id="email" class="form-control form-group-sm"
                                    :class="{ 'is-invalid': form.errors.has('email') }" :readonly="action=='show'||action=='delete'">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="password">{{ $t('password') | capitalize }}</label>
                                <input type="password" v-model="form.password" id="password" class="form-control form-group-sm"
                                    :class="{ 'is-invalid': form.errors.has('password') }" :readonly="action=='show'||action=='delete'">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                            <div class="form-group custom-file">
                                <input type="file" id="photo"  class="custom-file-input"  @change="handlePhoto" :class="{ 'is-invalid': form.errors.has('photo') }" :readonly="action=='show'||action=='delete'">
                                <label class="custom-file-label" for="photo" :data-browse="$t('photo')">{{ $t('select') + '...' }}</label>
                                <has-error :form="form" field="photo"></has-error>
                            </div>

                            <div class="form-group mt-3 mb-0 text-right">
                                <a href="#" @click.prevent="save()" class="btn btn-sm btn-primary">
                                    <i class="fas fa-save"></i> {{ $t('save') }}</a>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="profile-tab">
                            activity....
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {

        data() {
            return {
                resource: 'users',
                userPhoto: 'https://www.w3schools.com/bootstrap/paris.jpg',
                action: 'create',
                form: new Form({
                    id: '',
                    fullname: '',
                    name: '',
                    email: '',
                    photo: '',
                    avatar: '',
                    password: '',
                    created_at: '',
                })
            }
        },

        created() {
            this.getUser();
        },

        mounted() {
        },

        methods: {

            getUser(){
                this.form.get(`/api/user`)
                .then( res => {
                    this.form.fill( res.data );
                })
            },

            handlePhoto( e ) {
                this.form.photo = e.target.files[0];
            },

            save() {
                this.form._method = this.getHttpMethod();
                this.$Progress.start();

                let url = `/api/${this.resource}/${this.$store.getters.user.id}/update-profile`;

                this.form.submit('post', url, {
                    headers: { 'X-Content-Type-Options': 'nosniff', },
                    transformRequest: [function (data, headers) {
                        return objectToFormData(data);
                    }],
                    onUploadProgress: e => {
                        // Do whatever you want with the progress event
                    }
                })
                .then( res => {
                    this.$Progress.finish();
                    let user = res.data;
                    this.getUser();
                    this.$store.dispatch('retrieveUser');
                    // this.$store.commit('retrieveUser', user );
                })
                .catch( res => { 
                    this.$Progress.fail();
                    console.log('EROR', res)
                })
            },

            getHttpMethod()
            {
                if( this.action == 'create')
                    return 'POST';
                else if( this.action == 'edit' )
                    return 'PUT';
                else if( this.action == 'delete' )
                    return 'DELETE';
            }
        }
    }
</script>
